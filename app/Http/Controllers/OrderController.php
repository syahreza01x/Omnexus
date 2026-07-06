<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show user's order history.
     */
    public function index(Request $request): View
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('transactions'));
    }

    /**
     * Show order details.
     */
    public function show(Request $request, Transaction $transaction): View
    {
        // Ensure user can only see their own orders
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        $transaction->load('items.product');

        return view('orders.show', compact('transaction'));
    }

    /**
     * Upload payment proof.
     */
    public function uploadProof(Request $request, Transaction $transaction): RedirectResponse
    {
        // Ensure user can only upload for their own orders
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Status pesanan tidak mengizinkan upload bukti pembayaran.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'max:2048'], // max 2MB
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            // Delete old proof if exists
            if ($transaction->payment_proof_path) {
                Storage::disk('public')->delete($transaction->payment_proof_path);
            }

            $transaction->update([
                'payment_proof_path' => $path,
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}
