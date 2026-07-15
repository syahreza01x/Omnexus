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

        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = collect($cart)->map(function ($item, $cartKey) use ($cartProducts) {
            $product = $cartProducts->get((int) $item['product_id']);
            if (! $product) {
                return null;
            }

            $price = (int) $product->price;
            $size = $item['size'] ?? null;
            if ($size && $product->category !== 'Aksesoris') {
                if ($size === 'M') $price += 5000;
                elseif ($size === 'L') $price += 10000;
                elseif ($size === 'XL') $price += 20000;
                elseif ($size === 'XXL') $price += 30000;
            }

            return [
                'product' => $product,
                'quantity' => (int) $item['quantity'],
                'size' => $size,
                'subtotal' => (int) $item['quantity'] * $price,
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
            'shipping_method' => ['required', 'in:pickup,delivery'],
            'address_id' => ['required_if:shipping_method,delivery', 'nullable', 'exists:addresses,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $address = null;

        if ($validated['shipping_method'] === 'delivery') {
            // Verify address belongs to user
            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
        }

        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Validate stock availability
        foreach ($cart as $cartKey => $item) {
            $product = $cartProducts->get((int) $item['product_id']);

            if (! $product || ! $product->is_active) {
                return back()->withErrors(['cart' => "Produk \"{$product?->name}\" tidak tersedia lagi."]);
            }

            if ($product->stock < (int) $item['quantity']) {
                return back()->withErrors(['cart' => "Stok \"{$product->name}\" tidak mencukupi. Tersedia: {$product->stock}."]);
            }
        }

        // Calculate total
        $totalAmount = collect($cart)->reduce(function ($carry, $item, $cartKey) use ($cartProducts) {
            $product = $cartProducts->get((int) $item['product_id']);
            
            $price = (int) $product->price;
            $size = $item['size'] ?? null;
            if ($size && $product->category !== 'Aksesoris') {
                if ($size === 'M') $price += 5000;
                elseif ($size === 'L') $price += 10000;
                elseif ($size === 'XL') $price += 20000;
                elseif ($size === 'XXL') $price += 30000;
            }
            
            return $carry + ((int) $item['quantity'] * $price);
        }, 0);

        // Create transaction within a DB transaction
        DB::transaction(function () use ($cart, $cartProducts, $address, $validated, $request, $totalAmount) {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'shipping_method' => $validated['shipping_method'],
                'shipping_name' => $address ? $request->user()->name : null,
                'shipping_phone' => $address ? ($address->phone ?? $request->user()->phone) : null,
                'shipping_address' => $address?->address_line,
                'shipping_city' => $address?->city,
                'shipping_province' => $address?->province,
                'shipping_postal_code' => $address?->postal_code,
            ]);

            // Create transaction items & reduce stock
            foreach ($cart as $cartKey => $item) {
                $product = $cartProducts->get((int) $item['product_id']);
                $quantity = (int) $item['quantity'];
                
                $price = (int) $product->price;
                $size = $item['size'] ?? null;
                if ($size && $product->category !== 'Aksesoris') {
                    if ($size === 'M') $price += 5000;
                    elseif ($size === 'L') $price += 10000;
                    elseif ($size === 'XL') $price += 20000;
                    elseif ($size === 'XXL') $price += 30000;
                }
                
                $unitPrice = $price;
                $subtotal = $quantity * $unitPrice;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'size' => $size,
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
