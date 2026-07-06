<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomOrderRequest;
use App\Models\CustomOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
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
    public function store(StoreCustomOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle file upload
        if ($request->hasFile('design_file')) {
            $path = $request->file('design_file')->store('design_files', 'public');
            $validated['design_file'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'menunggu_review'; // Initial status

        CustomOrder::create($validated);

        return redirect()->route('custom-orders.index')->with('success', 'Custom Order berhasil dibuat. Harap menunggu review dari admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomOrder $customOrder): View
    {
        // Enforce ownership: customer cannot view other customers' orders
        if ($customOrder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

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
                    $order->customer_name,
                    ucfirst($order->category),
                    $order->product === 'lainnya' ? 'Lainnya' : $order->product,
                    $order->other_product_name ?? '-',
                    $order->quantity,
                    $order->color,
                    $order->size ?? '-',
                    $order->material_type,
                    $order->production_technique,
                    $order->deadline->format('Y-m-d'),
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
     * Approve the custom order mockup.
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
     * Request a revision for the custom order.
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
        }

        $customOrder->update($updateData);

        return redirect()->back()->with('success', 'Permintaan revisi berhasil dikirim.');
    }
}
