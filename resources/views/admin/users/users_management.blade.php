@extends('template.dashboard')
@section('admin-dashboard')
<div x-data="{
        showAddModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }},
        search: '{{ request('search') }}',
        editForm: {
            id: '{{ old('id') }}',
            name: '{{ old('name') }}',
            emp_id: '{{ old('emp_id') }}',
            email: '{{ old('email') }}',
            role: '{{ old('role') }}',
            shift: '{{ old('shift') }}',
            date_of_birth: '{{ old('date_of_birth') }}',
            alamat: '{{ old('alamat') }}',
            salary: '{{ old('salary') }}',
            start_date: '{{ old('start_date') }}',
            password: '' 
        },
        openEditModal(item) {
            this.editForm = {
                id: item.user_id, // Pastikan ini sesuai dengan primary key di model (user_id atau id)
                name: item.name,
                emp_id: item.emp_id,
                email: item.email,
                role: item.role,
                shift: item.shift,
                date_of_birth: item.date_of_birth,
                alamat: item.alamat,
                salary: item.salary,
                start_date: item.start_date,
                password: ''
            };
            this.showEditModal = true;
        }
    }">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-check-circle" class="h-5 w-5 shrink-0" />
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-alert-circle" class="h-5 w-5 shrink-0" />
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-card rounded-xl border border-border shadow-sm">
            {{-- Toolbar --}}
            <div class="p-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-semibold text-lg text-foreground">User Management</h3>
                <div class="flex items-center gap-3">
                    {{-- SEARCH FORM --}}
                    <form action="" method="GET" class="relative w-full sm:w-64">
                        <x-dynamic-component component="lucide-search" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                            class="w-full h-9 pl-9 pr-4 rounded-md border border-input bg-background text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                    </form>
                    
                    <button @click="showAddModal = true" class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add User
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b border-border">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Emp ID</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Shift</th>
                            <th class="px-6 py-4">Salary</th>
                            <th class="px-6 py-4">Join Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($dataArr as $item)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4">{{ $loop->iteration + ($dataArr->currentPage() - 1) * $dataArr->perPage() }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground">{{ $item->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $item->email }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $item->emp_id }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize
                                        {{ $item->role == 'admin' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ $item->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 capitalize">{{ $item->shift ?? '-' }}</td>
                                <td class="px-6 py-4 capitalize">@money($item->salary)</td>
                                <td class="px-6 py-4">{{ date('d M Y', strtotime($item->start_date)) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal({{ json_encode($item) }})" class="p-2 text-orange-600 hover:bg-blue-50 rounded-md transition-colors">
                                            <x-dynamic-component component="lucide-pencil" class="h-4 w-4" />
                                        </button>
                                        
                                        <form action="{{ route('admin.users-data.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                                <x-dynamic-component component="lucide-trash-2" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-dynamic-component component="lucide-user" class="h-8 w-8 opacity-50" />
                                        <p>No User found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">{{ $dataArr->links() }}</div>
        </div>

        {{-- Modal Logic (Add & Edit) --}}
        <div x-show="showAddModal || showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showAddModal = false; showEditModal = false"></div>

            <div class="relative w-full max-w-2xl bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold" x-text="showEditModal ? 'Edit User' : 'Add New User'"></h3>
                    <button @click="showAddModal = false; showEditModal = false"><x-dynamic-component component="lucide-x" class="h-5 w-5" /></button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    {{-- FORM UTAMA --}}
                    <form :action="showEditModal ? '{{ route('admin.users-data.index') }}/' + editForm.id : '{{ route('admin.users-data.store') }}'" method="POST">
                        @csrf
                        
                        {{-- FIX UTAMA: Menambahkan hidden ID agar terdeteksi saat redirect error --}}
                        <template x-if="showEditModal">
                            <div>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" x-model="editForm.id">
                            </div>
                        </template>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-sm font-medium">Full Name</label>
                                <input type="text" name="name" x-model="editForm.name" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Employee ID</label>
                                <input type="text" name="emp_id" x-model="editForm.emp_id" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Email</label>
                                <input type="email" name="email" x-model="editForm.email" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Password</label>
                                <input type="password" name="password" x-model="editForm.password" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" :required="!showEditModal" placeholder="Min. 6 characters">
                                <template x-if="showEditModal"><p class="text-xs text-muted-foreground mt-1">Leave empty to keep current password</p></template>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Role</label>
                                <select name="role" x-model="editForm.role" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                                    <option value="" disabled>Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Shift</label>
                                <select name="shift" x-model="editForm.shift" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                    <option value="">None</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift }}">{{ ucfirst($shift) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Salary</label>
                                <input type="number" name="salary" x-model="editForm.salary" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Date of Birth</label>
                                <input type="date" name="date_of_birth" x-model="editForm.date_of_birth" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium">Join Date</label>
                                <input type="date" name="start_date" x-model="editForm.start_date" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
                            </div>
                        </div>

                        <div class="space-y-1 mt-4">
                            <label class="text-sm font-medium">Address</label>
                            <textarea name="alamat" x-model="editForm.alamat" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-border mt-6">
                            <button type="button" @click="showAddModal = false; showEditModal = false" class="px-4 py-2 rounded-md border border-input hover:bg-muted">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection