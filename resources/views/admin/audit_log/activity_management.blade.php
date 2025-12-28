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
                    <form action="" method="GET" class="flex flex-col md:flex-row gap-4 mb-6">
    
    <div class="relative w-full md:w-64">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search activities..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
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
    <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition text-sm flex items-center">
        Reset Filter
    </a>
    @endif

</form>
                </div>
                
                <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
    
                    <span class="font-medium text-sm">Export Audit Log</span>
                </button>
            </div>

            <div class="overflow-x-auto">
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
        
        <td class="py-4 px-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
        
        <td class="py-4 px-2 font-medium">
            {{ $log->properties['user_name'] ?? 'System' }}
        </td>
        
        <td class="py-4 px-2">
            {{ $log->properties['role'] ?? '-' }}
        </td>
        
        <td class="py-4 px-2">
            @php
                // Kita ambil text dari description yang sudah kita perpendek di Model tadi
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
        
        <td class="py-4 px-2 text-gray-500">
            {{ $log->properties['details'] ?? '-' }}
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
</tbody>
                </table>
            </div>

            <div class="mt-4">
                {{-- {{ $logs->links() }} --}}
            </div>
            
        </div>
    </div>

@endsection