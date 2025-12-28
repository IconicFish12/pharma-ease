@extends('template.dashboard')

@section('admin-dashboard')
    <script>
        window.cashierSystem = function() {
            return {
                medicines: [],
                search: '',
                cart: [],
                cashReceived: '',
                isLoading: false,

                init() {
                    console.log('Alpine Init Berjalan...'); 

                    let rawData = @json($medicines);
                    
                    console.log("Raw Data:", rawData);

                    if (!rawData || rawData.length === 0) {
                        console.warn("Data obat kosong dari Controller");
                        this.medicines = [];
                        return;
                    }

                    this.medicines = rawData.map(item => ({
                        medicine_id: item.medicine_id,
                        medicine_name: item.medicine_name,
                        sku: item.sku,
                        stock: parseInt(item.stock),
                        price: parseFloat(item.price), 
                        category: item.category
                    }));
                },

                get filteredMedicines() {
                    if (this.search === '') return this.medicines;
                    const keyword = this.search.toLowerCase();
                    return this.medicines.filter(item => {
                        return item.medicine_name.toLowerCase().includes(keyword) ||
                               item.sku.toLowerCase().includes(keyword);
                    });
                },

                get grandTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                addToCart(item) {
                    if (item.stock <= 0) { alert('Stok Habis!'); return; }

                    let existingItem = this.cart.find(c => c.medicine_id === item.medicine_id);
                    
                    // Cek sisa stok real-time (Stok Awal - Jumlah di Cart)
                    let currentQtyInCart = existingItem ? existingItem.quantity : 0;
                    
                    if (currentQtyInCart + 1 > item.stock) {
                        alert('Stok tidak mencukupi!');
                        return;
                    }

                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            medicine_id: item.medicine_id,
                            medicine_name: item.medicine_name,
                            price: item.price,
                            stock: item.stock,
                            quantity: 1
                        });
                    }
                },

                updateQty(index, amount) {
                    let item = this.cart[index];
                    let newQty = item.quantity + amount;
                    if (newQty > 0 && newQty <= item.stock) {
                        item.quantity = newQty;
                    } else if (newQty > item.stock) {
                        alert('Maksimal stok tercapai');
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                },

                submitTransaction() {
                    if (!confirm('Proses transaksi?')) return;
                    this.isLoading = true;

                    const userId = '{{ Auth::id() }}';

                    const payload = {
                        items: this.cart.map(i => ({ medicine_id: i.medicine_id, quantity: i.quantity })),
                        cash_received: this.cashReceived,
                        user_id: userId
                    };

                    fetch('{{ route("admin.transaction.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isLoading = false;
                        if (data.status === 'success') {
                            alert('Transaksi Berhasil!\nKembalian: ' + this.formatRupiah(data.change));
                            this.cart = [];
                            this.cashReceived = '';
                            
                            payload.items.forEach(sold => {
                                let med = this.medicines.find(m => m.medicine_id === sold.medicine_id);
                                if(med) med.stock -= sold.quantity;
                            });
                        } else {
                            alert('Gagal: ' + data.message);
                        }
                    })
                    .catch(err => {
                        this.isLoading = false;
                        console.error(err);
                        alert('Terjadi kesalahan sistem');
                    });
                }
            }
        }
    </script>

    <div x-data="cashierSystem()" x-init="init()" class="h-[calc(100vh-120px)] flex flex-col lg:flex-row gap-4">

        <div class="flex-1 flex flex-col gap-4 h-full">
            <div class="bg-card p-4 rounded-xl border border-border shadow-sm">
                <div class="relative">
                    <x-dynamic-component component="lucide-search"
                        class="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                    <input type="text" x-model="search" placeholder="Cari nama obat atau SKU..."
                        class="w-full h-10 pl-10 pr-4 rounded-md border bg-background text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    <template x-for="item in filteredMedicines" :key="item.medicine_id">
                        <div @click="addToCart(item)"
                            class="group bg-card p-4 rounded-xl border border-border shadow-sm hover:shadow-md cursor-pointer transition-all flex flex-col justify-between relative overflow-hidden">
                            
                            <div class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="item.stock <= 10 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600'">
                                Stok: <span x-text="item.stock"></span>
                            </div>

                            <div>
                                <h4 class="font-semibold text-foreground mb-1" x-text="item.medicine_name"></h4>
                                <p class="text-xs text-muted-foreground mb-2" x-text="item.sku"></p>
                                <p class="text-xs text-blue-600 bg-blue-50 inline-block px-2 py-1 rounded" 
                                   x-text="item.category ? item.category.name : '-'"></p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border flex items-center justify-between">
                                <span class="font-bold text-lg text-foreground" x-text="formatRupiah(item.price)"></span>
                                <button class="h-8 w-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="filteredMedicines.length === 0" class="col-span-full flex flex-col items-center justify-center py-12 text-muted-foreground">
                        <x-dynamic-component component="lucide-package-open" class="h-10 w-10 mb-2 opacity-50" />
                        <p>Obat tidak ditemukan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[400px] bg-card rounded-xl border border-border shadow-sm flex flex-col h-full">
            <div class="p-4 border-b border-border bg-muted/30">
                <h3 class="font-semibold text-lg flex items-center gap-2">
                    <x-dynamic-component component="lucide-shopping-cart" class="h-5 w-5" />
                    Current Order
                </h3>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                <template x-for="(cartItem, index) in cart" :key="cartItem.medicine_id">
                    <div class="flex flex-col gap-2 p-3 rounded-lg border border-border bg-background">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-sm" x-text="cartItem.medicine_name"></h4>
                                <p class="text-xs text-muted-foreground" x-text="formatRupiah(cartItem.price)"></p>
                            </div>
                            <p class="font-semibold text-sm" x-text="formatRupiah(cartItem.price * cartItem.quantity)"></p>
                        </div>

                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center gap-3 bg-muted/50 rounded-md p-1">
                                <button @click="updateQty(index, -1)" class="w-6 h-6 rounded bg-white shadow-sm flex items-center justify-center hover:text-red-600">
                                    <x-dynamic-component component="lucide-minus" class="h-3 w-3" />
                                </button>
                                <span class="text-sm font-medium w-6 text-center" x-text="cartItem.quantity"></span>
                                <button @click="updateQty(index, 1)" class="w-6 h-6 rounded bg-white shadow-sm flex items-center justify-center hover:text-emerald-600">
                                    <x-dynamic-component component="lucide-plus" class="h-3 w-3" />
                                </button>
                            </div>
                            <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 text-xs flex items-center gap-1">
                                <x-dynamic-component component="lucide-trash-2" class="h-3 w-3" />
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-muted-foreground opacity-60">
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            </div>

            <div class="p-4 border-t border-border bg-muted/10 space-y-4">
                <div class="flex justify-between text-lg font-bold text-foreground">
                    <span>Total</span>
                    <span x-text="formatRupiah(grandTotal)"></span>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-medium uppercase text-muted-foreground">Uang Diterima</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-sm font-semibold text-muted-foreground">Rp</span>
                        <input type="number" x-model.number="cashReceived"
                            class="w-full h-10 pl-10 pr-4 rounded-md border bg-background text-sm font-semibold outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div x-show="grandTotal > 0 && cashReceived >= grandTotal" 
                     class="flex justify-between items-center text-sm px-3 py-2 bg-emerald-100 rounded-md border border-emerald-200 text-emerald-800">
                    <span class="font-bold">Kembalian:</span>
                    <span class="font-bold text-lg" x-text="formatRupiah(cashReceived - grandTotal)"></span>
                </div>

                <button @click="submitTransaction()"
                    :disabled="cart.length === 0 || cashReceived < grandTotal || isLoading"
                    class="w-full h-11 flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isLoading">Bayar & Cetak Struk</span>
                    <span x-show="isLoading" class="animate-spin">Proses...</span>
                </button>
            </div>
        </div>
    </div>
@endsection