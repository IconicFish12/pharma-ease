@extends('template.dashboard')
@section('admin-dashboard')
    {{--
        x-data setup:
        - showAddModal/showEditModal: kontrol visibilitas modal
        - editForm: objek untuk menampung data obat yang sedang diedit
        - openEditModal(item): fungsi untuk mengisi form edit saat tombol diklik
    --}}
    <div x-data="{
        showAddModal: false,
        showEditModal: false,
        search: '{{ request('search') }}',
        editForm: {
            id: null,
            medicine_name: '',
            sku: '',
            description: '',
            category_id: '',
            supplier_id: '',
            stock: 0,
            unit: '',
            price: 0,
            expired_date: ''
        },
        openEditModal(item) {
            // Mapping data dari backend ke form edit alpine
            this.editForm = {
                id: item.id,
                medicine_name: item.medicine_name,
                sku: item.sku,
                description: item.description,
                category_id: item.category_id, // Pastikan controller mengirim category_id
                supplier_id: item.supplier_id, // Pastikan controller mengirim supplier_id
                stock: item.stock,
                unit: item.unit,
                price: item.price,
                expired_date: item.expired_date
            };
            this.showEditModal = true;
        }
    }">

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
                            class="w-full h-9 pl-9 pr-4 rounded-md border border-input bg-background text-sm outline-none focus:ring-2 focus:ring-ring">
                    </form>

                    <button
                        class="h-9 w-9 flex items-center justify-center rounded-md border border-input bg-background hover:bg-muted text-foreground transition-colors">
                        <x-dynamic-component component="lucide-filter" class="h-4 w-4" />
                    </button>

                    <button @click="showAddModal = true"
                        class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add New Medicine
                    </button>
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
                                {{-- Gunakan optional() untuk mencegah error jika relasi null --}}
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
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- Tombol Edit: Mengirim data item ke fungsi Alpine --}}
                                    <button @click="openEditModal({{ json_encode($item) }})"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                        Edit
                                    </button>
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
            {{-- <div class="p-4 border-t border-border">
                {{ $dataArr->links() }}
            </div> --}}
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
                    {{-- Ganti route('admin.medicine.store') sesuai route Anda --}}
                    <form action="{{ route('admin.medicine') }}" method="post">
                        @csrf

                        {{-- Name --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Medicine Name</label>
                            <input type="text" name="medicine_name" placeholder="e.g., Paracetamol 500mg" required
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                        </div>

                        {{-- Code & Price --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">SKU / Code</label>
                                <input type="text" name="sku" placeholder="e.g., MED001" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Price (Rp)</label>
                                <input type="number" name="price" placeholder="e.g., 50000" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Description</label>
                            <textarea name="description" rows="2" placeholder="Optional description..."
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none"></textarea>
                        </div>

                        {{-- Category & Supplier --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category</label>
                                <div class="relative">
                                    <select name="category_id" required
                                        class="flex h-10 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                        <option value="" disabled selected>Select...</option>
                                        {{-- Loop Categories dari Controller --}}
                                        @foreach ($categories ?? [] as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier</label>
                                <div class="relative">
                                    <select name="supplier_id" required
                                        class="flex h-10 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                        <option value="" disabled selected>Select...</option>
                                        {{-- Loop Suppliers dari Controller --}}
                                        @foreach ($suppliers ?? [] as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                            </div>
                        </div>

                        {{-- Quantity, Unit, Expiry --}}
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Quantity</label>
                                <input type="number" name="stock" placeholder="100" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Unit</label>
                                <select name="unit" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                    <option>Strip</option>
                                    <option>Bottle</option>
                                    <option>Box</option>
                                    <option>Pcs</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Expiry</label>
                                <input type="date" name="expired_date" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="pt-4 border-t border-border flex items-center justify-end gap-3">
                            <button type="button" @click="showAddModal = false"
                                class="h-10 px-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
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
                    {{-- Pastikan ada route 'admin.medicine.update' yang menerima ID --}}
                    {{-- Contoh: Route::put('/admin/medicine/{id}', ...) --}}
                    <form :action="'{{ url('admin/medicine') }}/' + editForm.id" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Medicine Name</label>
                            <input type="text" name="medicine_name" x-model="editForm.medicine_name" required
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                        </div>

                        {{-- SKU & Price --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">SKU / Code</label>
                                <input type="text" name="sku" x-model="editForm.sku" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Price (Rp)</label>
                                <input type="number" name="price" x-model="editForm.price" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1.5 mb-4">
                            <label class="text-sm font-medium text-foreground">Description</label>
                            <textarea name="description" rows="2" x-model="editForm.description"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none"></textarea>
                        </div>

                        {{-- Category & Supplier --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category</label>
                                <div class="relative">
                                    <select name="category_id" x-model="editForm.category_id" required
                                        class="flex h-10 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                        <option value="" disabled>Select...</option>
                                        @foreach ($categories ?? [] as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Supplier</label>
                                <div class="relative">
                                    <select name="supplier_id" x-model="editForm.supplier_id" required
                                        class="flex h-10 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                        <option value="" disabled>Select...</option>
                                        @foreach ($suppliers ?? [] as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-dynamic-component component="lucide-chevron-down"
                                        class="absolute right-3 top-3 h-4 w-4 text-muted-foreground pointer-events-none" />
                                </div>
                            </div>
                        </div>

                        {{-- Quantity, Unit, Expiry --}}
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Quantity</label>
                                <input type="number" name="stock" x-model="editForm.stock" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Unit</label>
                                <select name="unit" x-model="editForm.unit" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                    <option>Strip</option>
                                    <option>Bottle</option>
                                    <option>Box</option>
                                    <option>Pcs</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Expiry</label>
                                <input type="date" name="expired_date" x-model="editForm.expired_date" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="pt-4 border-t border-border flex items-center justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="h-10 px-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
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
