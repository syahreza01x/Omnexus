<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (! $product->is_active) {
            return back()->withErrors(['product_id' => 'Produk tidak aktif.']);
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        $cart[$productId] = [
            'product_id' => $product->id,
            'quantity' => (($cart[$productId]['quantity'] ?? 0) + $quantity),
        ];

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        if (! array_key_exists($productId, $cart)) {
            return back()->withErrors(['product_id' => 'Produk tidak ditemukan di keranjang.']);
        }

        $cart[$productId]['quantity'] = (int) $validated['quantity'];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $product->id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}