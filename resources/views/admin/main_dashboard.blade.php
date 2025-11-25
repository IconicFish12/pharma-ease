@extends('template.dashboard')
@section('admin-dashboard')
    <!-- CONTENT FROM DASHBOARD_INDEX.BLADE.PHP GOES HERE -->
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-foreground">Dashboard</h2>
            <p class="text-muted-foreground">Manage your pharmacy operations efficiently</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1 -->
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Medicines</h3>
                    <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-foreground">1,284</div>
                <p class="text-xs text-muted-foreground"><span class="text-green-600 font-medium">+12%</span> from last
                    month</p>
            </div>
            <!-- Card 2 -->
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Low Stock Items</h3>
                    <svg class="h-4 w-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-foreground">23</div>
                <p class="text-xs text-muted-foreground">Requires immediate attention</p>
            </div>
            <!-- Card 3 -->
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Revenue</h3>
                    <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-foreground">$186,450</div>
                <p class="text-xs text-muted-foreground"><span class="text-green-600 font-medium">+18%</span> from last
                    month</p>
            </div>
            <!-- Card 4 -->
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between pb-2">
                    <h3 class="text-sm font-medium text-muted-foreground">Total Transactions</h3>
                    <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-foreground">1,745</div>
                <p class="text-xs text-muted-foreground"><span class="text-green-600 font-medium">+7%</span> from last
                    month</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Sales Chart -->
            <div class="rounded-xl border bg-white shadow-sm p-6">
                <h3 class="font-semibold mb-4">Sales Chart (Last 30 Days)</h3>
                <div class="h-[250px] w-full flex items-end gap-2">
                    <!-- Mock SVG Chart -->
                    <svg viewBox="0 0 400 200" class="w-full h-full overflow-visible">
                        <g class="stroke-gray-200" stroke-dasharray="4 4" stroke-width="1">
                            <line x1="40" y1="0" x2="40" y2="180" />
                            <line x1="120" y1="0" x2="120" y2="180" />
                            <line x1="200" y1="0" x2="200" y2="180" />
                            <line x1="280" y1="0" x2="280" y2="180" />
                            <line x1="360" y1="0" x2="360" y2="180" />
                            <line x1="40" y1="180" x2="360" y2="180" />
                            <line x1="40" y1="120" x2="360" y2="120" />
                            <line x1="40" y1="60" x2="360" y2="60" />
                        </g>
                        <path d="M40 120 C 80 110, 100 130, 120 110 S 160 80, 200 90 S 260 50, 300 60 S 340 80, 360 70"
                            fill="none" stroke="#60A5FA" stroke-width="3" />
                        <circle cx="40" cy="120" r="3" fill="white" stroke="#60A5FA" stroke-width="2" />
                        <circle cx="120" cy="110" r="3" fill="white" stroke="#60A5FA" stroke-width="2" />
                        <circle cx="200" cy="90" r="3" fill="white" stroke="#60A5FA" stroke-width="2" />
                        <circle cx="360" cy="70" r="3" fill="white" stroke="#60A5FA" stroke-width="2" />
                    </svg>
                </div>
            </div>

            <!-- Transactions Chart -->
            <div class="rounded-xl border bg-white shadow-sm p-6">
                <h3 class="font-semibold mb-4">Transaction Chart (Last 30 Days)</h3>
                <div class="h-[250px] w-full">
                    <svg viewBox="0 0 400 200" class="w-full h-full overflow-visible">
                        <g class="stroke-gray-200" stroke-dasharray="4 4" stroke-width="1">
                            <line x1="40" y1="0" x2="40" y2="180" />
                            <line x1="120" y1="0" x2="120" y2="180" />
                            <line x1="200" y1="0" x2="200" y2="180" />
                            <line x1="280" y1="0" x2="280" y2="180" />
                            <line x1="360" y1="0" x2="360" y2="180" />
                            <line x1="40" y1="180" x2="360" y2="180" />
                            <line x1="40" y1="120" x2="360" y2="120" />
                            <line x1="40" y1="60" x2="360" y2="60" />
                        </g>
                        <path d="M40 130 C 80 110, 100 120, 120 125 S 160 90, 200 80 S 260 90, 300 60 S 340 70, 360 75"
                            fill="none" stroke="#0F6643" stroke-width="3" />
                        <circle cx="40" cy="130" r="3" fill="white" stroke="#0F6643" stroke-width="2" />
                        <circle cx="120" cy="125" r="3" fill="white" stroke="#0F6643" stroke-width="2" />
                        <circle cx="360" cy="75" r="3" fill="white" stroke="#0F6643" stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Nearing Expiry -->
        <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="font-semibold">Medicines Nearing Expiry</h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Item 1 -->
                <div class="flex items-center gap-4 rounded-lg border p-4 hover:bg-muted/50 transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-red-600">Paracetamol 500mg</p>
                        <p class="text-sm text-muted-foreground">Expires: 2025-11-15</p>
                    </div>
                    <div class="text-sm font-medium text-red-600">26 days left</div>
                </div>
                <!-- Item 2 -->
                <div class="flex items-center gap-4 rounded-lg border p-4 hover:bg-muted/50 transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-700">Amoxicillin 250mg</p>
                        <p class="text-sm text-muted-foreground">Expires: 2025-11-20</p>
                    </div>
                    <div class="text-sm font-medium text-gray-500">31 days left</div>
                </div>
            </div>
        </div>

    </div>
@endsection
