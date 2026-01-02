@extends('template.dashboard')
@section('admin-dashboard')
    <div class="space-y-6">

        {{-- Header & Export --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Inventory Report</h2>
                <p class="text-muted-foreground">Monitoring stock levels and medicine status.</p>
            </div>
            <a href="{{ route('admin.reports.medicine-report-export') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
                <x-dynamic-component component="lucide-file-spreadsheet" class="h-4 w-4" />
                Export Excel
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600"><x-dynamic-component component="lucide-package"
                            class="h-6 w-6" /></div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Total Items</p>
                        <h3 class="text-2xl font-bold">{{ $totalItems }}</h3>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-50 rounded-full text-orange-600"><x-dynamic-component
                            component="lucide-alert-triangle" class="h-6 w-6" /></div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Low Stock (< 5)</p>
                                <h3 class="text-2xl font-bold text-orange-600">{{ $outOfStock }}</h3>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-full text-red-600"><x-dynamic-component component="lucide-calendar-x"
                            class="h-6 w-6" /></div>
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">Expired</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ $expired }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Unit</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($medicines as $item)
                            <tr class="hover:bg-muted/10">
                                <td class="px-6 py-4 font-medium">{{ $item->medicine_name }} <br> <span
                                        class="text-xs text-muted-foreground">{{ $item->sku }}</span></td>
                                <td class="px-6 py-4">{{ $item->category->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-bold {{ $item->stock <= 5 ? 'text-red-600' : '' }}">
                                    {{ $item->stock }}</td>
                                <td class="px-6 py-4">{{ $item->unit }}</td>
                                <td class="px-6 py-4">
                                    @if ($item->stock <= 5)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Critical</span>
                                    @elseif(\Carbon\Carbon::parse($item->expired_date)->isPast())
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">Expired</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">Good</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 {{ \Carbon\Carbon::parse($item->expired_date)->isPast() ? 'text-red-600 font-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($item->expired_date)->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-muted-foreground">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $medicines->links() }}</div>
        </div>
    </div>
@endsection
