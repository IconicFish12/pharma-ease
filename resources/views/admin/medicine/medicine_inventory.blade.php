@extends('template.dashboard')
@section('admin-dashboard')
    <div x-data="{
        showAddModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }},
        search: '{{ request('search') }}',
        editForm: {
            id: '{{ old('medicine_id') }}',
            medicine_name: '{{ old('medicine_name') }}',
            sku: '{{ old('sku') }}',
            description: '{{ old('description') }}',
            category_id: '{{ old('category_id') }}',
            supplier_id: '{{ old('supplier_id') }}',
            stock: '{{ old('stock') }}',
            unit: '{{ old('unit') }}',
            {{-- Tambahkan unit di sini --}}
            price: '{{ old('price') }}',
            expired_date: '{{ old('expired_date') }}'
        },
        openEditModal(item) {
            // Mapping data dari backend ke form edit alpine
            this.editForm = {
                id: item.medicine_id, // Perbaikan: Gunakan item.id (standard Laravel) kecuali PK custom
                medicine_name: item.medicine_name,
                sku: item.sku,
                description: item.description,
                category_id: item.category_id,
                supplier_id: item.supplier_id,
                stock: item.stock,
                unit: item.unit, // Mapping unit
                price: item.price,
                expired_date: item.expired_date
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
                <h3 class="font-semibold text-lg text-foreground">Inventory Management</h3>

                <div class="flex items-center gap-3">
                    {{-- Search Form --}}
                    <form action="{{ route('admin.medicine') }}" method="GET" class="relative w-full sm:w-64">
                        <x-dynamic-component component="lucide-search"
                            class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search medicines..."
                            value="{{ request('search') }}"
                            class="w-full h-9 pl-9 pr-4 rounded-md border bg-background text-sm outline-none focus:ring-2 focus:ring-ring">
                    </form>

                    <button
                        class="h-9 w-9 flex items-center justify-center rounded-md border bg-background hover:bg-muted text-foreground transition-colors">
                        <x-dynamic-component component="lucide-filter" class="h-4 w-4" />
                    </button>

                    <button @click="showAddModal = true"
                        class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add New Medicine
                    </button>

                    {{-- Link ke Halaman Kategori (Opsional) --}}
                    @if (Route::has('admin.medicine-category'))
                        <a href="{{ route('admin.medicine-category') }}">
                            <button
                                class="h-9 px-2 inline-flex items-center justify-center gap-1 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors shadow-sm">
                                <x-dynamic-component component="lucide-layout-grid" class="h-4 w-4" />
                                Category
                            </button>
                        </a>
                    @endif
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b border-border">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">SKU</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Supplier</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Expiry Date</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($dataArr as $key => $item)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4 font-medium text-foreground">
                                    {{ $dataArr->firstItem() + $key }}
                                </td>
                                <td class="px-6 py-4 font-medium text-foreground">{{ $item->medicine_name }}</td>
                                <td class="px-6 py-4 text-muted-foreground">{{ $item->sku }}</td>
                                <td class="px-6 py-4 text-foreground">{{ optional($item->category)->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-muted-foreground">
                                    {{ optional($item->supplier)->supplier_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-foreground">{{ $item->stock }} {{ $item->unit }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $stockStatus = '';
                                        $badgeClass = '';
                                        if ($item->stock >= 50) {
                                            $stockStatus = 'In Stock';
                                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif ($item->stock >= 10) {
                                            $stockStatus = 'Medium';
                                            $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                        } else {
                                            $stockStatus = 'Low Stock';
                                            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                                        }
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                        {{ $stockStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-foreground">
                                    {{ $item->expired_date ? date('d M Y', strtotime($item->expired_date)) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-foreground">
                                    @money($item->price)
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Tombol Edit --}}
                                        <button @click="openEditModal({{ json_encode($item) }})"
                                            class="p-2 rounded-md text-blue-600 hover:bg-blue-50 transition-colors"
                                            title="Edit">
                                            <x-dynamic-component component="lucide-pencil" class="h-4 w-4" />
                                        </button>

                                        {{-- Tombol Delete (Perbaikan: Ditambahkan) --}}
                                        <form action="{{ asset("/admin/medicine/$item->medicine_id") }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this medicine?');">
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
                                <td colspan="10" class="px-6 py-12 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-dynamic-component component="lucide-package-open" class="h-8 w-8 opacity-50" />
                                        <p>No medicines found in inventory.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION SECTION --}}
            <div class="p-4 border-t border-border">
                {{ $dataArr->links() }}
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- MODAL ADD MEDICINE --}}
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
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-lg bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground">Add New Medicine</h3>
                        <p class="text-sm text-muted-foreground">Enter the medicine details to add to inventory.</p>
                    </div>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                {{-- FORM ADD --}}
                <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar">
                    {{-- Pastikan route name sesuai di web.php --}}
                    <form action="{{ route('admin.medicine') }}" method="post">
                        @csrf

                        {{-- Name --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Medicine Name</label>
                            <input type="text" name="medicine_name" placeholder="e.g., Paracetamol 500mg"
                                value="{{ old('medicine_name') }}"
                                class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('medicine_name') border-red-500 @enderror">
                            @error('medicine_name')
                                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SKU & Price --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">SKU / Code</label>
                                <input type="text" name="sku" placeholder="e.g., MED001"
                                    value="{{ old('sku') }}"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('sku') border-red-500 @enderror">
                                @error('sku')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Price (Rp)</label>
                                <input type="number" name="price" placeholder="e.g., 50000"
                                    value="{{ old('price') }}"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Description</label>
                            <textarea name="description" rows="2" placeholder="Optional description..."
                                class="flex w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category & Supplier --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category</label>
                                <div class="relative">
                                    <select name="category_id"
                                        class="flex h-10 w-full appearance-none rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('category_id') border-red-500 @enderror">
                                        <option value="" disabled selected>Select...</option>
                                        @foreach ($categories ?? [] as $cat)
                                            <option value="{{ $cat->category_id }}"
                                                {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>{{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                                @error('category_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier</label>
                                <div class="relative">
                                    <select name="supplier_id"
                                        class="flex h-10 w-full appearance-none rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('supplier_id') border-red-500 @enderror">
                                        <option value="" disabled selected>Select...</option>
                                        @foreach ($suppliers ?? [] as $sup)
                                            <option value="{{ $sup->supplier_id }}"
                                                {{ old('supplier_id') == $sup->supplier_id ? 'selected' : '' }}>
                                                {{ $sup->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                                @error('supplier_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Quantity, Unit, Expiry --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Quantity</label>
                                <input type="number" name="stock" placeholder="100"
                                    value="{{ old('stock') }}"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Expiry</label>
                                <input type="date" name="expired_date"  value="{{ old('expired_date') }}"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('expired_date') border-red-500 @enderror">
                                @error('expired_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-4 border-t border-border flex items-center justify-end gap-3">
                            <button type="button" @click="showAddModal = false"
                                class="h-10 px-4 rounded-md border bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="h-10 px-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                                Add Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- MODAL EDIT MEDICINE --}}
        {{-- ========================================== --}}
        <div x-show="showEditModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
                @click="showEditModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-lg bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground">Edit Medicine</h3>
                        <p class="text-sm text-muted-foreground">Update the medicine details below.</p>
                    </div>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar">
                    {{-- Form Edit Dynamic Action --}}
                    <form :action="'{{ route('admin.medicine') }}/' + editForm.id" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Hidden ID untuk validasi unique ignore --}}
                        <input type="hidden" name="medicine_id" x-model="editForm.id">

                        {{-- Name --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Medicine Name</label>
                            <input type="text" name="medicine_name" x-model="editForm.medicine_name"
                                class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('medicine_name') border-red-500 @enderror">
                            @error('medicine_name')
                                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- SKU & Price --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">SKU / Code</label>
                                <input type="text" name="sku" x-model="editForm.sku"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('sku') border-red-500 @enderror">
                                @error('sku')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Price (Rp)</label>
                                <input type="number" name="price" x-model="editForm.price"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Description</label>
                            <textarea name="description" rows="2" x-model="editForm.description"
                                class="flex w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('description') border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category & Supplier --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category</label>
                                <div class="relative">
                                    <select name="category_id" x-model="editForm.category_id"
                                        class="flex h-10 w-full appearance-none rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('category_id') border-red-500 @enderror">
                                        <option value="" disabled>Select...</option>
                                        @foreach ($categories ?? [] as $cat)
                                            <option value="{{ $cat->category_id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                                {{-- Perbaikan: Hapus karakter 'e' typo --}}
                                @error('category_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier</label>
                                <div class="relative">
                                    <select name="supplier_id" x-model="editForm.supplier_id"
                                        class="flex h-10 w-full appearance-none rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('supplier_id') border-red-500 @enderror">
                                        <option value="" disabled>Select...</option>
                                        @foreach ($suppliers ?? [] as $sup)
                                            <option value="{{ $sup->supplier_id }}">{{ $sup->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                                @error('supplier_id')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Quantity</label>
                                <input type="number" name="stock" x-model="editForm.stock"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Expiry</label>
                                <input type="date" name="expired_date" x-model="editForm.expired_date"
                                    class="flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('expired_date') border-red-500 @enderror">
                                @error('expired_date')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="pt-4 border-t border-border flex items-center justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="h-10 px-4 rounded-md border bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="h-10 px-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                                Update Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
