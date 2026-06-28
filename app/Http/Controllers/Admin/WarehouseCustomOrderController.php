<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseCustomOrderController extends Controller
{
    /**
     * Display a listing of custom orders for the warehouse.
     */
    public function index(Request $request): View
    {
        $query = CustomOrder::with('user')->whereIn('status', ['produksi', 'siap_diambil_dikirim']);

        // Optional search within allowed statuses
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product', 'like', '%' . $search . '%')
                  ->orWhere('other_product_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $customOrders = $query->latest()->paginate(20);

        return view('admin.warehouse.custom-orders.index', compact('customOrders'));
    }

    /**
     * Display the specified custom order.
     */
    public function show(CustomOrder $customOrder): View
    {
        // Enforce warehouse authorization check
        if (!in_array($customOrder->status, ['produksi', 'siap_diambil_dikirim'])) {
            abort(403, 'Akses ditolak. Pesanan tidak dalam tahap produksi atau siap diambil.');
        }

        return view('admin.warehouse.custom-orders.show', compact('customOrder'));
    }
}
