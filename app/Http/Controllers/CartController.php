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
            'size' => ['nullable', 'string', 'in:S,M,L,XL,XXL'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (! $product->is_active) {
            return back()->withErrors(['product_id' => 'Produk tidak aktif.']);
        }

        $size = null;
        if ($product->category !== 'Aksesoris') {
            $size = $validated['size'] ?? 'S'; // Default to S if not provided
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = $request->session()->get('cart', []);
        
        $cartKey = (string) $product->id;
        if ($size) {
            $cartKey .= '_' . $size;
        }

        $cart[$cartKey] = [
            'product_id' => $product->id,
            'quantity' => (($cart[$cartKey]['quantity'] ?? 0) + $quantity),
            'size' => $size,
        ];

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $cartKey): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (! array_key_exists($cartKey, $cart)) {
            return back()->withErrors(['product_id' => 'Produk tidak ditemukan di keranjang.']);
        }

        $cart[$cartKey]['quantity'] = (int) $validated['quantity'];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    public function remove(Request $request, string $cartKey): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$cartKey]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}