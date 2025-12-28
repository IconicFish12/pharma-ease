@extends('template.dashboard')

@section('admin-dashboard')

<div class="p-6 bg-gray-50 min-h-screen">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Audit Log</h1>
        <p class="text-gray-500 text-sm mt-1">
            Manage your pharmacy operations efficiently — secure tracking of employee activities and system changes.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <form action="" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
                    
                    <div class="relative w-full md:w-64">
                        <input 
                            id="searchInput"
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search activities..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                    </div>

                    <select 
                        name="action" 
                        onchange="this.form.submit()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer"
                    >
                        <option value="">All Actions</option>
                        <option value="Login" {{ request('action') == 'Login' ? 'selected' : '' }}>Login</option>
                        <option value="Created" {{ request('action') == 'Created' ? 'selected' : '' }}>Created</option>
                        <option value="Updated" {{ request('action') == 'Updated' ? 'selected' : '' }}>Updated</option>
                        <option value="Deleted" {{ request('action') == 'Deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>

                    <select 
                        name="module" 
                        onchange="this.form.submit()" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer"
                    >
                        <option value="">All Modules</option>
                        <option value="Authentication" {{ request('module') == 'Authentication' ? 'selected' : '' }}>Authentication</option>
                        <option value="User Management" {{ request('module') == 'User Management' ? 'selected' : '' }}>User Management</option>
                        <option value="Inventory" {{ request('module') == 'Inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="Supplier Management" {{ request('module') == 'Supplier Management' ? 'selected' : '' }}>Suppliers</option>
                        <option value="Transaction Details Management" {{ request('module') == 'Transaction Details Management' ? 'selected' : '' }}>Transactions</option>
                    </select>

                    @if(request('search') || request('action') || request('module'))
                    <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition text-sm flex items-center justify-center">
                        Reset Filter
                    </a>
                    @endif

                </form>
            </div>
            
            <div class="relative inline-block text-left group z-10">
                <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Log
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="absolute right-0 top-full pt-2 w-48 hidden group-hover:block">
                    <div class="bg-white rounded-md shadow-lg border border-gray-200 overflow-hidden">
                        <a href="{{ route('audit-logs.export', array_merge(request()->all(), ['format' => 'xlsx'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center gap-2 transition-colors">
                            📄 Export to Excel
                        </a>
                        <a href="{{ route('audit-logs.export', array_merge(request()->all(), ['format' => 'csv'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center gap-2 transition-colors">
                            📝 Export to CSV
                        </a>
                        <a href="{{ route('audit-logs.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 flex items-center gap-2 transition-colors">
                            📕 Export to PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="tableContainer" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm text-gray-500 font-semibold border-b border-gray-200">
                        <th class="py-3 px-2">Timestamp</th>
                        <th class="py-3 px-2">User</th>
                        <th class="py-3 px-2">Role</th>
                        <th class="py-3 px-2">Action</th>
                        <th class="py-3 px-2">Module</th>
                        <th class="py-3 px-2">Details</th>
                        <th class="py-3 px-2">IP Address</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($logs as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-2 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        
                        <td class="py-4 px-2 font-medium">
                            {{ $log->properties['user_name'] ?? 'System' }}
                        </td>
                        
                        <td class="py-4 px-2">
                            {{ $log->properties['role'] ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-2">
                            @php
                                $action = $log->description; 
                                $badgeClass = match($action) {
                                    'Login' => 'bg-green-100 text-green-700',
                                    'Created' => 'bg-blue-100 text-blue-700',
                                    'Updated' => 'bg-yellow-100 text-yellow-700',
                                    'Deleted' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ $action }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-2 capitalize">{{ $log->log_name }}</td>
                        
                        <td class="py-4 px-2 text-gray-500 truncate max-w-xs" title="{{ $log->properties['details'] ?? '' }}">
                            {{Str::limit($log->properties['details'] ?? '-', 50) }}
                        </td>
                        
                        <td class="py-4 px-2 text-gray-500">
                            {{ $log->properties['ip'] ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-500">
                            No audit logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
        
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableContainer = document.getElementById('tableContainer');
        let timeout = null; 

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                // 1. Reset timer kalau user masih mengetik
                clearTimeout(timeout);

                // 2. Tunggu 500ms setelah user berhenti mengetik
                timeout = setTimeout(() => {
                    const query = searchInput.value;
                    
                    // Ambil URL saat ini & update parameter search
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', query);
                    url.searchParams.delete('page'); 

                    // 3. Request data ke server tanpa refresh halaman
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            // 4. Parsing HTML respon
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTable = doc.getElementById('tableContainer');

                            if(newTable && tableContainer) {
                                // 5. Ganti isi tabel lama dengan yang baru
                                tableContainer.innerHTML = newTable.innerHTML;
                                
                                // Update URL di browser
                                window.history.pushState({}, '', url);
                            }
                        })
                        .catch(error => console.error('Error fetching logs:', error));
                }, 500); 
            });
        }
    });
</script>

@endsection