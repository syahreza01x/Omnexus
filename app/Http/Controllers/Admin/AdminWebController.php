<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminWebController extends Controller
{
    /**
     * Show admin web dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.web.dashboard', [
            'total_users' => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
            'total_transactions' => Transaction::count(),
            'recent_transactions' => Transaction::with('user')->latest()->take(10)->get(),
        ]);
    }

    /**
     * Show products list.
     */
    public function products(Request $request): View
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        return view('admin.web.products.index', [
            'products' => $query->paginate(20),
        ]);
    }

    /**
     * Show create product form.
     */
    public function createProduct(): View
    {
        return view('admin.web.products.create');
    }

    /**
     * Store new product.
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:products'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:piece,kg,meter,liter,dozen,box'],
            'category' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:15360'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('product-images', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.web.products')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Show edit product form.
     */
    public function editProduct(Product $product): View
    {
        return view('admin.web.products.edit', ['product' => $product]);
    }

    /**
     * Update product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:piece,kg,meter,liter,dozen,box'],
            'category' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:15360'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('product-images', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.web.products')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Show users list.
     */
    public function users(Request $request): View
    {
        $query = User::where('role', 'user');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        return view('admin.web.users.index', [
            'users' => $query->paginate(20),
        ]);
    }

    /**
     * Show transactions list.
     */
    public function transactions(Request $request): View
    {
        $query = Transaction::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        return view('admin.web.transactions.index', [
            'transactions' => $query->latest()->paginate(20),
            'statuses' => ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'],
        ]);
    }

    /**
     * Show transaction details.
     */
    public function showTransaction(Transaction $transaction): View
    {
        return view('admin.web.transactions.show', [
            'transaction' => $transaction->load('user', 'items.product'),
        ]);
    }

    /**
     * Update transaction status.
     */
    public function updateTransactionStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,processing,shipped,completed,cancelled'],
        ]);

        $transaction->update($validated);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui');
    }
}
