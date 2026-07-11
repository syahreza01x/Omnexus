<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CustomOrder;
use App\Models\CustomOrderRevision;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->customOrders()->latest()->get();
        return view('custom-orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
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
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('custom-orders.show', $order)->with('success', 'Custom Order berhasil dibuat! Silakan tunggu konfirmasi Admin.');
    }

    public function show(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id) {
            abort(403);
        }

        $customOrder->load(['product', 'revisions' => function ($query) {
            $query->oldest();
        }]);

        return view('custom-orders.show', compact('customOrder'));
    }

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

    public function checkout(Request $request, CustomOrder $customOrder)
    {
        if ($customOrder->user_id !== $request->user()->id || $customOrder->status !== 'approved') {
            abort(403);
        }

        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();
        return view('custom-orders.checkout', compact('customOrder', 'addresses'));
    }

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

        $product = $customOrder->product;

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
