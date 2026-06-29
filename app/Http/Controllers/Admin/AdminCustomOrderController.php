<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCustomOrderController extends Controller
{
    /**
     * Display a listing of all custom orders.
     */
    public function index(Request $request): View
    {
        $query = CustomOrder::with('user');

        // Apply Status Filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Apply Search (User name, email, WhatsApp, or product)
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product', 'like', '%' . $search . '%')
                  ->orWhere('other_product_name', 'like', '%' . $search . '%')
                  ->orWhere('whatsapp_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $customOrders = $query->latest()->paginate(20);
        
        $statuses = [
            'menunggu_review' => 'Menunggu Review',
            'menunggu_persetujuan_customer' => 'Menunggu Persetujuan Customer',
            'diproses' => 'Diproses',
            'produksi' => 'Produksi',
            'siap_diambil_dikirim' => 'Siap Diambil / Dikirim',
            'selesai' => 'Selesai',
        ];

        return view('admin.web.custom-orders.index', compact('customOrders', 'statuses'));
    }

    /**
     * Display the specified custom order.
     */
    public function show(CustomOrder $customOrder): View
    {
        $statuses = [
            'menunggu_review' => 'Menunggu Review',
            'menunggu_persetujuan_customer' => 'Menunggu Persetujuan Customer',
            'diproses' => 'Diproses',
            'produksi' => 'Produksi',
            'siap_diambil_dikirim' => 'Siap Diambil / Dikirim',
            'selesai' => 'Selesai',
        ];

        return view('admin.web.custom-orders.show', compact('customOrder', 'statuses'));
    }

    /**
     * Update status of the custom order.
     */
    public function update(Request $request, CustomOrder $customOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:menunggu_review,menunggu_persetujuan_customer,diproses,produksi,siap_diambil_dikirim,selesai'],
        ]);

        $customOrder->update($validated);

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi: ' . $customOrder->status_label);
    }

    /**
     * Download the design file uploaded by the customer.
     */
    public function downloadDesign(CustomOrder $customOrder)
    {
        if (!$customOrder->design_file || !Storage::disk('public')->exists($customOrder->design_file)) {
            abort(404, 'File desain tidak ditemukan di storage.');
        }

        // Generate default download filename preserving original extension
        $extension = pathinfo($customOrder->design_file, PATHINFO_EXTENSION);
        $filename = 'desain-order-' . str_pad($customOrder->id, 5, '0', STR_PAD_LEFT) . '.' . $extension;

        return Storage::disk('public')->download($customOrder->design_file, $filename);
    }
}
