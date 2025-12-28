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
                    <input type="text" placeholder="Search activities..." 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-64">
                    
                    <select class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 focus:outline-none bg-white">
                        <option value="">All Actions</option>
                        <option value="Login">Login</option>
                        <option value="Create">Create</option>
                        <option value="Update">Update</option>
                        <option value="Failed Login">Failed Login</option>
                        <option value="Delete">Delete</option>
                    </select>

                    <select class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-600 focus:outline-none bg-white">
                        <option value="">All Modules</option>
                        <option value="Authentication">Authentication</option>
                        <option value="User Management">User Management</option>
                        <option value="Inventory">Inventory</option>
                        <option value="Transactions">Transactions</option>
                        <option value="Supplier Management">Supplier Management</option>
                        <option value="Purchase Orders">Purchase Orders</option>
                    </select>
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