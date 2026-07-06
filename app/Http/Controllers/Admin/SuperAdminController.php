<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class SuperAdminController extends Controller
{
    public function dashboard(): View
    {
        $totalRevenue = Transaction::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('total_amount');
        $totalOrders = Transaction::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Product::count();

        // Get revenue for last 7 days for chart
        $revenueData = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            $revenueData[] = Transaction::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        return view('admin.super.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalUsers', 'totalProducts', 'revenueData', 'labels'
        ));
    }

    public function reports(Request $request): View
    {
        $query = Transaction::with(['user', 'items.product'])->latest();
        
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.super.reports', compact('transactions'));
    }
}
