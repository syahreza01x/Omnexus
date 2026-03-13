<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminWarehouseController extends Controller
{
    /**
     * Show admin warehouse dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.warehouse.dashboard', [
            'low_stock_products' => Product::where('stock', '<=', 10)->get(),
            'total_products' => Product::count(),
            'recent_stock_logs' => StockLog::with('product', 'changedBy')->latest()->take(10)->get(),
        ]);
    }

    /**
     * Show stock management page.
     */
    public function stocks(Request $request): View
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->low_stock) {
            $query->where('stock', '<=', 10);
        }

        return view('admin.warehouse.stocks.index', [
            'products' => $query->paginate(20),
        ]);
    }

    /**
     * Show stock logs for a product.
     */
    public function productStockLogs(Product $product, Request $request): View
    {
        $query = $product->stockLogs();

        return view('admin.warehouse.stocks.logs', [
            'product' => $product,
            'logs' => $query->with('changedBy')->latest()->paginate(20),
        ]);
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity_change' => ['required', 'integer'],
            'action' => ['required', 'in:added,removed,adjustment'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $stock_before = $product->stock;
        $quantity_change = (int) $validated['quantity_change'];
        $stock_after = $stock_before + $quantity_change;

        if ($stock_after < 0) {
            return redirect()->back()->withErrors(['quantity_change' => 'Stok tidak bisa negatif']);
        }

        $product->update(['stock' => $stock_after]);

        StockLog::create([
            'product_id' => $product->id,
            'changed_by' => $request->user()->id,
            'quantity_change' => $quantity_change,
            'action' => $validated['action'],
            'reason' => $validated['reason'],
            'stock_before' => $stock_before,
            'stock_after' => $stock_after,
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui');
    }

    /**
     * Show all stock logs.
     */
    public function allStockLogs(Request $request): View
    {
        $query = StockLog::with('product', 'changedBy');

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->user_id) {
            $query->where('changed_by', $request->user_id);
        }

        return view('admin.warehouse.stocks.all-logs', [
            'logs' => $query->latest()->paginate(20),
            'products' => Product::get(),
        ]);
    }
}
