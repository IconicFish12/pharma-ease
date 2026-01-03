@extends('template.dashboard')
@section('admin-dashboard')
    <div class="space-y-6">

        {{-- Header & Date Filter --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Financial Overview</h2>
                <p class="text-muted-foreground">Revenue, expenses, and profit analysis.</p>
            </div>

            <form action="{{ route('admin.reports.financial-report') }}" method="GET"
                class="flex flex-col sm:flex-row items-end gap-3 bg-card p-2 rounded-lg border">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-muted-foreground ml-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-muted-foreground ml-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-emerald-700">Filter</button>
                    <a href="{{ route('admin.reports.financial-report-export', request()->query()) }}"
                        class="h-9 px-4 inline-flex items-center justify-center rounded-md border border-input bg-background hover:bg-muted text-foreground text-sm font-medium">
                        <x-dynamic-component component="lucide-download" class="h-4 w-4" />
                    </a>
                </div>
            </form>
        </div>

        {{-- Money Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-muted-foreground">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">@money($revenue)</h3>
                    <span class="text-xs text-muted-foreground mt-1">Income from sales</span>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-muted-foreground">Total Expenses</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-2">@money($expenses)</h3>
                    <span class="text-xs text-muted-foreground mt-1">Cost of medicine orders</span>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-muted-foreground">Net Profit (Est.)</p>
                    <h3 class="text-2xl font-bold {{ $profit >= 0 ? 'text-blue-600' : 'text-orange-600' }} mt-2">
                        @money($profit)
                    </h3>
                    <span class="text-xs text-muted-foreground mt-1">Revenue - Expenses</span>
                </div>
            </div>
        </div>

        {{-- Transaction List --}}
        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="p-4 border-b bg-muted/20">
                <h3 class="font-semibold text-sm">Recent Transactions (Sales)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Transaction Code</th>
                            <th class="px-6 py-4">Cashier</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-muted/10">
                                <td class="px-6 py-4">{{ date('d M Y H:i', strtotime($trx->transaction_date)) }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $trx->kode_penjualan }}</td>
                                <td class="px-6 py-4">{{ $trx->user->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 text-right font-medium text-green-600">@money($trx->total_price)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-muted-foreground">No transactions found in
                                    this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $transactions->appends(request()->query())->links() }}</div>
        </div>
    </div>
@endsection
