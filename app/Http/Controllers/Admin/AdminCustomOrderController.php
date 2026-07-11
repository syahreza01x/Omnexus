<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\CustomOrderRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCustomOrderController extends Controller
{
    public function index()
    {
        $orders = CustomOrder::with(['user', 'product'])->latest()->paginate(20);
        return view('admin.web.custom-orders.index', compact('orders'));
    }

    public function show(CustomOrder $customOrder)
    {
        $customOrder->load(['user', 'product', 'revisions' => function ($query) {
            $query->oldest();
        }]);

        return view('admin.web.custom-orders.show', compact('customOrder'));
    }

    public function updateStatus(Request $request, CustomOrder $customOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,designing,revision,approved,rejected,completed',
            'price_per_item' => 'nullable|numeric|min:0',
        ]);

        $customOrder->status = $validated['status'];
        
        if (isset($validated['price_per_item'])) {
            $customOrder->price_per_item = $validated['price_per_item'];
        }

        $customOrder->save();

        return back()->with('success', 'Status pesanan custom berhasil diperbarui.');
    }

    public function storeRevision(Request $request, CustomOrder $customOrder)
    {
        $validated = $request->validate([
            'message' => 'required_without:attachment_file|string|nullable',
            'attachment_file' => 'nullable|image|max:5120',
            'is_final_design' => 'nullable|boolean',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('custom_designs/revisions', 'public');
            
            // If admin checks "This is final design"
            if ($request->boolean('is_final_design')) {
                $customOrder->update(['admin_design_path' => $attachmentPath]);
            }
        }

        $customOrder->revisions()->create([
            'sender_type' => 'admin',
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
        ]);

        return back()->with('success', 'Pesan balasan berhasil dikirim.');
    }
}
