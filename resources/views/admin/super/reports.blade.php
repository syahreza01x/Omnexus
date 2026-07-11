@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 print:hidden">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Laporan Transaksi</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Data penjualan lengkap</p>
    </div>
    <div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </button>
    </div>
</div>

<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 print:border-none print:shadow-none">
    <!-- Filters (Hidden in print) -->
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 print:hidden">
        <form method="GET" action="{{ route('admin.super.reports') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2 text-white text-sm font-medium hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">Filter</button>
                <a href="{{ route('admin.super.reports') }}" class="inline-block ml-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">Reset</a>
            </div>
        </form>
    </div>

    <!-- Print Header (Hidden on screen) -->
    <div class="hidden print:block mb-8 text-center">
        <h2 class="text-2xl font-bold">Laporan Transaksi Interco</h2>
        <p class="text-sm text-gray-600 mt-1">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        @if(request('start_date') || request('end_date'))
            <p class="text-sm">Periode: {{ request('start_date') ?: '-' }} s/d {{ request('end_date') ?: '-' }}</p>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900 print:bg-transparent">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider print:text-black">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 print:border-b">
                @forelse ($transactions as $tx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 print:hover:bg-transparent">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">#{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $tx->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $tx->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            @foreach($tx->items as $item)
                                <div>{{ $item->quantity }}x {{ Str::limit($item->product->name, 30) }}</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                @if($tx->status === 'completed') bg-green-100 text-green-800
                                @elseif($tx->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif print:border print:border-gray-400 print:bg-transparent">
                                {{ $tx->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
            <!-- Print Footer Total -->
            @if($transactions->count() > 0)
                <tfoot class="bg-gray-50 dark:bg-gray-900 print:bg-transparent border-t-2 border-gray-200 print:border-gray-800">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100 text-right uppercase">Total Halaman Ini</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100 text-right">Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <!-- Pagination (Hidden in print) -->
    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700 print:hidden">
        {{ $transactions->links() }}
    </div>
</div>

<style>
    @media print {
        @page { margin: 1cm; size: landscape; }
        body { background: white !important; }
        .page-topbar, aside, nav, .print\:hidden { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .flex-1 { flex: none !important; }
        table { width: 100% !important; border-collapse: collapse !important; }
        th, td { border: 1px solid #ddd !important; padding: 8px !important; color: black !important; }
    }
</style>
@endsection
