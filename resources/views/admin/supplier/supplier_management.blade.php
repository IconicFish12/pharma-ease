@extends('template.dashboard')
@section('admin-dashboard')
    <div x-data="{
        showAddModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }},
        search: '{{ request('search') }}',
        editForm: {
            id: '{{ old('id') }}',
            supplier_name: '{{ old('supplier_name') }}',
            contact_person: '{{ old('contact_person') }}',
            phone_number: '{{ old('phone_number') }}',
            address: '{{ old('address') }}'
        },
        openEditModal(item) {
            // Pastikan mapping ID sesuai nama kolom di database (supplier_id)
            this.editForm = {
                id: item.supplier_id, // PERBAIKAN DISINI (dari item.id ke item.supplier_id)
                supplier_name: item.supplier_name,
                contact_person: item.contact_person,
                phone_number: item.phone_number,
                address: item.address
            };
            this.showEditModal = true;
        }
    }">

        {{-- FLASH MESSAGE SESSION (AUTO HIDE) --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-check-circle" class="h-5 w-5 shrink-0 text-emerald-600" />
                <div>
                    <h4 class="font-semibold text-sm">Success</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 flex items-center gap-3">
                <x-dynamic-component component="lucide-alert-circle" class="h-5 w-5 shrink-0 text-red-600" />
                <div>
                    <h4 class="font-semibold text-sm">Error</h4>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-card rounded-xl border border-border shadow-sm">

            {{-- TOOLBAR SECTION --}}
            <div class="p-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-semibold text-lg text-foreground">Supplier Management</h3>

                <div class="flex items-center gap-3">
                    {{-- Search Form --}}
                    <form action="" method="GET" class="relative w-full sm:w-64">
                        <x-dynamic-component component="lucide-search"
                            class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search suppliers..."
                            value="{{ request('search') }}"
                            class="w-full h-9 pl-9 pr-4 rounded-md border border-input bg-background text-sm outline-none focus:ring-2 focus:ring-ring">
                    </form>

                    <button @click="showAddModal = true"
                        class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add Supplier
                    </button>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b border-border">
                        <tr>
                            <th class="px-6 py-4 w-12">No</th>
                            <th class="px-6 py-4">Supplier Name</th>
                            <th class="px-6 py-4">Contact Person</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Address</th>
                            <th class="px-6 py-4 text-right w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($dataArr as $key => $item)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4 font-medium text-foreground">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-medium text-foreground">{{ $item->supplier_name }}</td>
                                <td class="px-6 py-4 text-foreground">{{ $item->contact_person }}</td>
                                <td class="px-6 py-4 text-muted-foreground">{{ $item->phone_number }}</td>
                                <td class="px-6 py-4 text-muted-foreground truncate max-w-xs" title="{{ $item->address }}">
                                    {{ Str::limit($item->address, 50) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Tombol Edit --}}
                                        <button @click="openEditModal({{ json_encode($item) }})"
                                            class="p-2 rounded-md text-orange-400 hover:bg-blue-50 transition-colors"
                                            title="Edit">
                                            <x-dynamic-component component="lucide-pencil" class="h-4 w-4" />
                                        </button>

                                        {{-- Tombol Delete --}}
                                        {{-- PERBAIKAN: Gunakan $item->supplier_id (bukan $item->id) --}}
                                        <form action="{{ asset("/admin/suppliers/$item->supplier_id") }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-md text-red-600 hover:bg-red-50 transition-colors"
                                                title="Delete">
                                                <x-dynamic-component component="lucide-trash-2" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-dynamic-component component="lucide-truck" class="h-8 w-8 opacity-50" />
                                        <p>No suppliers found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-4 border-t border-border">
                {{ $dataArr->links() }}
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- MODAL ADD SUPPLIER --}}
        {{-- ========================================== --}}
        <div x-show="showAddModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            {{-- Backdrop --}}
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="showAddModal = false"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-lg bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-foreground">Add New Supplier</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    {{-- Action Route Store --}}
                    <form action="{{ route('admin.suppliers-data') }}" method="post">
                        @csrf
                        <div class="space-y-4">

                            {{-- Supplier Name --}}
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier Name</label>
                                <input type="text" name="supplier_name" placeholder="e.g., PT. Farma Jaya" required
                                    value="{{ old('supplier_name') }}"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('supplier_name') border-red-500 @enderror">
                                @error('supplier_name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Contact Person & Phone --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-foreground">Contact Person</label>
                                    <input type="text" name="contact_person" placeholder="e.g., Budi Santoso" required
                                        value="{{ old('contact_person') }}"
                                        class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('contact_person') border-red-500 @enderror">
                                    @error('contact_person')
                                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-foreground">Phone Number</label>
                                    <input type="text" name="phone_number" placeholder="e.g., 08123456789" required
                                        value="{{ old('phone_number') }}"
                                        class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('phone_number') border-red-500 @enderror">
                                    @error('phone_number')
                                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Address</label>
                                <textarea name="address" rows="3" placeholder="Full address..."
                                    class="flex w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="showAddModal = false"
                                class="h-10 px-4 rounded-md border bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="h-10 px-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                                Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- MODAL EDIT SUPPLIER --}}
        {{-- ========================================== --}}
        <div x-show="showEditModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            {{-- Backdrop --}}
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
                @click="showEditModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-lg bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-foreground">Edit Supplier</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    {{-- Dynamic Action Update --}}
                    <form :action="'{{ route('admin.suppliers-data') }}/' + editForm.id" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" x-model="editForm.id">

                        <div class="space-y-4">

                            {{-- Supplier Name --}}
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier Name</label>
                                <input type="text" name="supplier_name" x-model="editForm.supplier_name" required
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('supplier_name') border-red-500 @enderror">
                                @error('supplier_name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Contact Person & Phone --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-foreground">Contact Person</label>
                                    <input type="text" name="contact_person" x-model="editForm.contact_person"
                                        required
                                        class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('contact_person') border-red-500 @enderror">
                                    @error('contact_person')
                                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-foreground">Phone Number</label>
                                    <input type="text" name="phone_number" x-model="editForm.phone_number" required
                                        class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('phone_number') border-red-500 @enderror">
                                    @error('phone_number')
                                        <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Address</label>
                                <textarea name="address" rows="3" x-model="editForm.address"
                                    class="flex w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('address') border-red-500 @enderror"></textarea>
                                @error('address')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="h-10 px-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="h-10 px-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                                Update Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
