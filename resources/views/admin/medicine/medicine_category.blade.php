@extends('template.dashboard')
@section('admin-dashboard')
    <div x-data="{
        showAddModal: {{ $errors->any() && !old('id') ? 'true' : 'false' }},
        showEditModal: {{ $errors->any() && old('id') ? 'true' : 'false' }},
        search: '{{ request('search') }}',
        editForm: {
            id: '{{ old('id') }}',
            name: '{{ old('name') }}',
            description: '{{ old('description') }}'
        },
        openEditModal(item) {
            this.editForm = {
                id: item.category_id,
                name: item.name,
                description: item.description
            };
            this.showEditModal = true;
        }
    }">

        <div class="bg-card rounded-xl border border-border shadow-sm">

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

            {{-- TOOLBAR SECTION --}}
            <div class="p-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-semibold text-lg text-foreground">Category List</h3>

                <div class="flex items-center gap-3">
                    {{-- Search Form --}}
                    {{-- Sesuaikan route('admin.categories.index') dengan route kamu --}}
                    <form action="" method="GET" class="relative w-full sm:w-64">
                        <x-dynamic-component component="lucide-search"
                            class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <input type="text" name="search" placeholder="Search categories..."
                            value="{{ request('search') }}"
                            class="w-full h-9 pl-9 pr-4 rounded-md border border-input bg-background text-sm outline-none focus:ring-2 focus:ring-ring">
                    </form>

                    <button @click="showAddModal = true"
                        class="h-9 px-4 inline-flex items-center justify-center gap-2 rounded-md bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <x-dynamic-component component="lucide-plus" class="h-4 w-4" />
                        Add Category
                    </button>

                    <a href="{{ route('admin.medicine') }}">
                        <button
                            class="h-9 px-2 inline-flex items-center justify-center gap-1 rounded-md  bg-green-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors shadow-sm">
                            <x-dynamic-component component="lucide-move-left" class="h-4 w-4" />
                            Back to Medicine
                        </button>
                    </a>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b border-border">
                        <tr>
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4 w-1/4">Category Name</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($dataArr as $key => $item)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4 font-medium text-foreground">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-medium text-foreground">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-muted-foreground">
                                    {{ Str::limit($item->description, 60) ?: '-' }}
                                </td>
                                <td class="px-3 py-4 ">
                                    <div class="flex items-center justify-items-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button @click="openEditModal({{ json_encode($item) }})"
                                            class="p-2 rounded-md text-orange-400 hover:bg-blue-50 transition-colors"
                                            title="Edit">
                                            <x-dynamic-component component="lucide-pencil" class="h-4 w-4" />
                                        </button>

                                        {{-- Tombol Delete --}}
                                        {{-- Pastikan route destroy sudah ada --}}
                                        {{-- action="{{ route('admin.categories.destroy', $item->id) }}" --}}
                                        <form action="{{ asset("/admin/medicine-category/$item->category_id") }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                                <td colspan="4" class="px-6 py-12 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <x-dynamic-component component="lucide-folder-open" class="h-8 w-8 opacity-50" />
                                        <p>No categories found.</p>
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
        {{-- MODAL ADD CATEGORY --}}
        {{-- ========================================== --}}
        <div x-show="showAddModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="showAddModal = false"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-md bg-card rounded-xl shadow-lg border border-border flex flex-col overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-foreground">Add Category</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.medicine-category') }}" method="post">
                        @csrf
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category Name</label>
                                <input type="text" name="name" placeholder="e.g., Antibiotics"
                                    value="{{ old('name') }}"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('name') @enderror">
                                {{-- Error Message --}}
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Description</label>
                                <textarea name="description" rows="3" placeholder="Category description..."
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('description') @enderror">{{ old('description') }}</textarea>
                                {{-- Error Message --}}
                                @error('description')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-3">
                            <button type="button" @click="showAddModal = false"
                                class="h-10 px-4 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="h-10 px-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition-colors">
                                Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ========================================== --}}
        {{-- MODAL EDIT CATEGORY --}}
        {{-- ========================================== --}}
        <div x-show="showEditModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">

            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
                @click="showEditModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-md bg-card rounded-xl shadow-lg border border-border flex flex-col overflow-hidden">

                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-foreground">Edit Category</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <x-dynamic-component component="lucide-x" class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-6">
                    {{-- Dynamic Action untuk Update --}}
                    {{-- :action="'{{ url('admin/categories') }}/' + editForm.id" --}}
                    <form :action="'{{ route('admin.medicine-category') }}/' + editForm.id" method="post">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Category Name</label>
                                <input type="text" name="name" x-model="editForm.name" required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('name') @enderror">
                                {{-- Error Message --}}
                                @error('name')
                                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-foreground">Description</label>
                                <textarea name="description" rows="3" x-model="editForm.description"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none @error('description') @enderror"></textarea>
                                {{-- Error Message --}}
                                @error('description')
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
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
