<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomOrderRequest;
use App\Models\Address;
use App\Models\CustomOrder;
use App\Models\CustomOrderRevision;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    /**
     * Display a listing of all custom orders.
     */
    public function index(Request $request): View
    {
        $customOrders = Auth::user()
            ->customOrders()
            ->latest()
            ->paginate(10);

        return view('custom-orders.index', compact('customOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('custom-orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('product_id')) {
            // Reza's basis product flow
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'category' => 'nullable|string|max:255',
                'material' => 'nullable|string|max:255',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string',
                'design_file' => 'nullable|image|max:5120', // Max 5MB image
            ]);

            $designPath = null;
            if ($request->hasFile('design_file')) {
                $designPath = $request->file('design_file')->store('custom_designs', 'public');
            }

            $order = CustomOrder::create([
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
                'category' => $validated['category'],
                'material' => $validated['material'],
                'quantity' => $validated['quantity'],
                'user_design_path' => $designPath,
                'design_file' => $designPath, // sync
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            return redirect()->route('custom-orders.show', $order)->with('success', 'Custom Order berhasil dibuat! Silakan tunggu konfirmasi Admin.');
        } else {
            // Ardiman's detailed specification form flow
            $rules = (new StoreCustomOrderRequest())->rules();
            $attributes = (new StoreCustomOrderRequest())->attributes();
            $validated = $request->validate($rules, [], $attributes);
            
            if ($request->hasFile('design_file')) {
                $path = $request->file('design_file')->store('design_files', 'public');
                $validated['design_file'] = $path;
                $validated['user_design_path'] = $path; // sync
            }

            $validated['user_id'] = Auth::id();
            $validated['status'] = 'menunggu_review'; // Initial status

            CustomOrder::create($validated);

            return redirect()->route('custom-orders.index')->with('success', 'Custom Order berhasil dibuat. Harap menunggu review dari admin.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, CustomOrder $customOrder): View
    {
        // Enforce ownership: customer cannot view other customers' orders
        if ($customOrder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $customOrder->load(['product_relation', 'revisions' => function ($query) {
            $query->oldest();
        }]);

        return view('custom-orders.show', compact('customOrder'));
    }

    /**
     * Export the customer's custom order history as a CSV file.
     */
    public function exportCsv()
    {
        $orders = Auth::user()->customOrders()->latest()->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada riwayat pesanan untuk diunduh.');
        }

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID Pesanan', 
                'Nama Pemesan',
                'Kategori', 
                'Produk', 
                'Nama Produk Lainnya', 
                'Jumlah', 
                'Warna', 
                'Ukuran', 
                'Jenis Bahan', 
                'Teknik Produksi', 
                'Deadline', 
                'Catatan', 
                'Status', 
                'Tanggal Dibuat'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $order->customer_name ?? $order->user->name,
                    ucfirst($order->category),
                    $order->product_id ? ($order->product_relation->name ?? 'Custom Product') : ($order->product === 'lainnya' ? 'Lainnya' : $order->product),
                    $order->other_product_name ?? '-',
                    $order->quantity,
                    $order->color ?? '-',
                    $order->size ?? '-',
                    $order->material_type ?? $order->material ?? '-',
                    $order->production_technique ?? '-',
                    $order->deadline ? $order->deadline->format('Y-m-d') : '-',
                    $order->notes ?? '-',
                    $order->status_label,
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="riwayat-custom-order-' . Auth::id() . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Approve the custom order mockup (Ardiman's flow).
     */
    public function approveMockup(CustomOrder $customOrder): RedirectResponse
    {
        if ($customOrder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $customOrder->update([
            'status' => 'diproses'
        ]);

        return redirect()->back()->with('success', 'Desain mockup berhasil disetujui. Pesanan Anda kini sedang diproses.');
    }

    /**
     * Request a revision for the custom order (Ardiman's flow).
     */
    public function requestRevision(Request $request, CustomOrder $customOrder): RedirectResponse
    {
        if ($customOrder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'revision_notes' => 'required|string|max:1000',
            'new_design_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,cdr|max:10240'
        ]);

        $updateData = [
            'status' => 'revisi_desain',
            'revision_notes' => $request->revision_notes,
            'revision_count' => $customOrder->revision_count + 1
        ];

        if ($request->hasFile('new_design_file')) {
            $path = $request->file('new_design_file')->store('design_files', 'public');
            $updateData['design_file'] = $path; // update reference design file
            $updateData['user_design_path'] = $path; // sync
        }

        $customOrder->update($updateData);

        return redirect()->back()->with('success', 'Permintaan revisi berhasil dikirim.');
    }

    /**
     * Store revision message (Reza's flow).
     */
    public function storeRevision(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required_without:attachment_file|string|nullable',
            'attachment_file' => 'nullable|image|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('custom_designs/revisions', 'public');
        }

        $customOrder->revisions()->create([
            'sender_type' => 'user',
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
        ]);

        if ($customOrder->status === 'designing' || $customOrder->status === 'approved') {
            $customOrder->update(['status' => 'revision']);
        }

        return back()->with('success', 'Pesan revisi berhasil dikirim.');
    }

    /**
     * Approve the custom order design (Reza's flow).
     */
    public function approve(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($customOrder->status !== 'designing' && $customOrder->status !== 'revision') {
            return back()->with('error', 'Status pesanan tidak dapat disetujui.');
        }

        $customOrder->update(['status' => 'approved']);

        return back()->with('success', 'Desain disetujui! Silakan lanjut ke Pembayaran.');
    }

    /**
     * Show checkout page for custom order (Reza's flow).
     */
    public function checkout(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id || $customOrder->status !== 'approved') {
            abort(403);
        }

        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();
        return view('custom-orders.checkout', compact('customOrder', 'addresses'));
    }

    /**
     * Process checkout for custom order (Reza's flow).
     */
    public function processCheckout(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id || $customOrder->status !== 'approved') {
            abort(403);
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $address = Address::where('id', $validated['address_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $product = $customOrder->product_relation;

        if (!$product) {
            return back()->withErrors(['checkout' => "Produk basis tidak ditemukan."]);
        }

        if ($product->stock < $customOrder->quantity) {
            return back()->withErrors(['checkout' => "Stok produk basis tidak mencukupi."]);
        }

        $totalAmount = $customOrder->price_per_item * $customOrder->quantity;

        DB::transaction(function () use ($customOrder, $product, $address, $validated, $request, $totalAmount) {
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'shipping_name' => $request->user()->name,
                'shipping_phone' => $address->phone ?? $request->user()->phone,
                'shipping_address' => $address->address_line,
                'shipping_city' => $address->city,
                'shipping_province' => $address->province,
                'shipping_postal_code' => $address->postal_code,
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $customOrder->quantity,
                'unit_price' => $customOrder->price_per_item,
                'subtotal' => $totalAmount,
            ]);

            $stockBefore = $product->stock;
            $product->decrement('stock', $customOrder->quantity);

            StockLog::create([
                'product_id' => $product->id,
                'changed_by' => $request->user()->id,
                'quantity_change' => -$customOrder->quantity,
                'action' => 'removed',
                'reason' => "Pembelian Custom Order #{$customOrder->id} - Transaksi #{$transaction->id}",
                'stock_before' => $stockBefore,
                'stock_after' => $stockBefore - $customOrder->quantity,
            ]);

            $customOrder->update([
                'status' => 'completed',
                'transaction_id' => $transaction->id
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Custom Order berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
