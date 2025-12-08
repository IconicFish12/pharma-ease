@extends('template.dashboard')
@section('admin-dashboard')
   <div x-data="{
        showAddModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }},
        search: '{{ request('search') }}',

        // Data Form
        editForm: {
            id: '{{ old('id') }}',
            supplier_id: '{{ old('supplier_id') }}',
            user_id: '{{ old('user_id') }}',
            order_date: '{{ old('order_date', date('Y-m-d')) }}',
            status: '{{ old('status', 'pending') }}',
            // Load old medicines jika ada error validasi, jika tidak kosongkan
            medicines: {{ old('medicines') ? json_encode(old('medicines')) : '[]' }}
        },

        // Master Data Obat (untuk dropdown & harga)
        medicineList: {{ json_encode($medicines_list) }},

        // Logic: Tambah Baris
        addMedicineRow() {
            this.editForm.medicines.push({
                medicine_id: '',
                quantity: 1,
                unit_price: 0,
                subtotal: 0
            });
        },

        // Logic: Hapus Baris
        removeMedicineRow(index) {
            this.editForm.medicines.splice(index, 1);
        },

        // Logic: Update Harga/Subtotal saat obat dipilih
        updateRow(index) {
            let row = this.editForm.medicines[index];
            let selectedMed = this.medicineList.find(m => m.medicine_id == row.medicine_id);

            if (selectedMed && row.unit_price == 0) {
                row.unit_price = parseFloat(selectedMed.price);
            }
            row.subtotal = (parseInt(row.quantity) || 0) * (parseFloat(row.unit_price) || 0);
        },

        // Logic: Grand Total
        get grandTotal() {
            return this.editForm.medicines.reduce((sum, row) => sum + ((parseInt(row.quantity) || 0) * (parseFloat(row.unit_price) || 0)), 0);
        },

        // Logic: Reset Form untuk Add
        resetForm() {
            this.editForm = {
                id: null,
                supplier_id: '',
                user_id: '',
                order_date: '{{ date('Y-m-d') }}',
                status: 'pending',
                medicines: []
            };
            this.addMedicineRow(); // Default 1 baris kosong
            this.showAddModal = true;
        },

        // Logic: Isi Form untuk Edit
        openEditModal(item) {
            let dateOnly = item.order_date ? item.order_date.split(' ')[0] : '';
            this.editForm = {
                id: item.order_id,
                supplier_id: item.supplier_id,
                user_id: item.user_id,
                order_date: dateOnly,
                status: item.status,
                medicines: item.medicines.map(med => ({
                    medicine_id: med.medicine_id,
                    quantity: med.pivot.quantity,
                    unit_price: med.pivot.unit_price,
                    subtotal: med.pivot.subtotal
                }))
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

        {{-- Global Validation Errors (Optional, useful for debugging) --}}
        @if ($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-card rounded-xl border border-border shadow-sm">
            {{-- Toolbar --}}
            <div class="p-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-semibold text-lg text-foreground">{{ $mainHeader }}</h3>
                <div class="flex items-center gap-3">
                    <form action="" method="GET" class="relative w-full sm:w-64">
                        <x-dynamic-component component="lucide-search" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search orders..." value="{{ request('search') }}"
                            class="w-full h-9 pl-9 pr-4 rounded-md border border-input bg-background text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                    </form>
                    <button @click="resetForm()" class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add Order
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b border-border">
                        <tr>
                            <th class="px-6 py-4">Code</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Supplier</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($dataArr as $item)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $item->order_code }}</td>
                                <td class="px-6 py-4">{{ optional($item->user)->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ optional($item->supplier)->supplier_name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ date('d M Y', strtotime($item->order_date)) }}</td>
                                <td class="px-6 py-4 font-medium">@money($item->total_price)</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                                        {{ $item->status == 'completed' ? 'bg-green-100 text-green-700' :
                                           ($item->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal({{ json_encode($item) }})" class="p-2 text-orange-600 hover:bg-blue-50 rounded-md transition-colors">
                                            <x-dynamic-component component="lucide-pencil" class="h-4 w-4" />
                                        </button>
                                        <form action="{{ asset("/admin/medicine-order/$item->order_id") }}" method="POST" onsubmit="return confirm('Delete order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors">
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
                                        <x-dynamic-component component="lucide-shopping-cart" class="h-8 w-8 opacity-50" />
                                        <p>No Order found in inventory.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">{{ $dataArr->links() }}</div>
        </div>

        {{-- MODAL FORM (Reusable Add & Edit) --}}
        <div x-show="showAddModal || showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showAddModal = false; showEditModal = false"></div>

            <div class="relative w-full max-w-4xl bg-card rounded-xl shadow-lg border border-border flex flex-col max-h-[90vh] overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold" x-text="showEditModal ? 'Edit Order' : 'New Order'"></h3>
                    <button @click="showAddModal = false; showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form :action="showEditModal ? '{{ url('admin/medicine-order') }}/' + editForm.id : '{{ route('admin.medicine-order') }}'" method="POST">
                        @csrf
                        <template x-if="showEditModal"><input type="hidden" name="_method" value="PUT"></template>
                        <template x-if="showEditModal"><input type="hidden" name="id" x-model="editForm.id"></template>

                        {{-- Main Info --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="space-y-1">
                                <label class="text-sm font-medium">User (Cashier)</label>
                                <select name="user_id" x-model="editForm.user_id" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none" >
                                    <option value="" disabled>Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->user_id }}">{{ $user->name }} ({{ $user->role }})</option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium">Supplier</label>
                                <select name="supplier_id" x-model="editForm.supplier_id" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none" >
                                    <option value="" disabled>Select Supplier</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->supplier_id }}">{{ $sup->supplier_name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="space-y-1">
                                <label class="text-sm font-medium">Order Date</label>
                                <input type="date" name="order_date" x-model="editForm.order_date" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none" >
                                @error('order_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium">Status</label>
                                <select name="status" x-model="editForm.status" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none">
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Medicine Items Table --}}
                        <div class="border rounded-md overflow-hidden mb-4">
                            <table class="w-full text-sm">
                                <thead class="bg-muted text-muted-foreground">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Medicine</th>
                                        <th class="px-4 py-2 w-24">Qty</th>
                                        <th class="px-4 py-2 w-32">Price</th>
                                        <th class="px-4 py-2 w-32 text-right">Subtotal</th>
                                        <th class="px-4 py-2 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    <template x-for="(row, index) in editForm.medicines" :key="index">
                                        <tr>
                                            <td class="p-2">
                                                <select :name="'medicines['+index+'][medicine_id]'" x-model="row.medicine_id" @change="updateRow(index)" class="w-full rounded-md border border-input bg-background px-2 py-1" >
                                                    <option value="" disabled>Select...</option>
                                                    <template x-for="med in medicineList" :key="med.medicine_id">
                                                        <option :value="med.medicine_id" x-text="med.medicine_name"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td class="p-2">
                                                <input type="number" :name="'medicines['+index+'][quantity]'" x-model="row.quantity" @input="updateRow(index)" min="1" class="w-full rounded-md border border-input bg-background px-2 py-1 text-center" >
                                            </td>
                                            <td class="p-2">
                                                <input type="number" :name="'medicines['+index+'][unit_price]'" x-model="row.unit_price" @input="updateRow(index)" class="w-full rounded-md border border-input bg-background px-2 py-1 text-right" >
                                            </td>
                                            <td class="p-2 text-right font-medium" x-text="'Rp ' + (row.quantity * row.unit_price).toLocaleString('id-ID')"></td>
                                            <td class="p-2 text-center">
                                                <button type="button" @click="removeMedicineRow(index)" class="text-red-500 hover:text-red-700"><x-dynamic-component component="lucide-trash" class="h-4 w-4" /></button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-muted/50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-bold text-right">Grand Total:</td>
                                        <td class="px-4 py-2 font-bold text-right text-lg text-primary" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" @click="addMedicineRow()" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mb-6">
                            <x-dynamic-component component="lucide-plus-circle" class="h-4 w-4" /> Add Item
                        </button>

                        <div class="flex justify-end gap-3 pt-6 border-t border-border">
                            <button type="button" @click="showAddModal = false; showEditModal = false" class="px-4 py-2 rounded-md border border-input hover:bg-muted">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Save Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
