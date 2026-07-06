<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show checkout page.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('beranda')->with('error', 'Keranjang belanja kosong.');
        }

        $cartProducts = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $cartItems = collect($cart)->map(function ($item, $productId) use ($cartProducts) {
            $product = $cartProducts->get((int) $productId);
            if (! $product) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => (int) $item['quantity'],
                'subtotal' => (int) $item['quantity'] * (int) $product->price,
            ];
        })->filter();

        $subtotal = $cartItems->sum('subtotal');
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();

        return view('checkout', compact('cartItems', 'subtotal', 'addresses'));
    }

    /**
     * Process checkout and create transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('beranda')->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'address_id' => ['required', 'exists:addresses,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Verify address belongs to user
        $address = Address::where('id', $validated['address_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartProducts = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        // Validate stock availability
        foreach ($cart as $productId => $item) {
            $product = $cartProducts->get((int) $productId);

            if (! $product || ! $product->is_active) {
                return back()->withErrors(['cart' => "Produk \"{$product?->name}\" tidak tersedia lagi."]);
            }

            if ($product->stock < (int) $item['quantity']) {
                return back()->withErrors(['cart' => "Stok \"{$product->name}\" tidak mencukupi. Tersedia: {$product->stock}."]);
            }
        }

        // Calculate total
        $totalAmount = collect($cart)->reduce(function ($carry, $item, $productId) use ($cartProducts) {
            $product = $cartProducts->get((int) $productId);
            return $carry + ((int) $item['quantity'] * (int) $product->price);
        }, 0);

        // Create transaction within a DB transaction
        DB::transaction(function () use ($cart, $cartProducts, $address, $validated, $request, $totalAmount) {
            // Create transaction
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

            // Create transaction items & reduce stock
            foreach ($cart as $productId => $item) {
                $product = $cartProducts->get((int) $productId);
                $quantity = (int) $item['quantity'];
                $unitPrice = (int) $product->price;
                $subtotal = $quantity * $unitPrice;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $stockBefore = $product->stock;
                $product->decrement('stock', $quantity);

                StockLog::create([
                    'product_id' => $product->id,
                    'changed_by' => $request->user()->id,
                    'quantity_change' => -$quantity,
                    'action' => 'removed',
                    'reason' => "Pembelian - Order #{$transaction->id}",
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore - $quantity,
                ]);
            }
        });

        // Clear cart
        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
