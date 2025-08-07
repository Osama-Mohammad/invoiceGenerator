<div class="max-w-3xl mx-auto bg-white p-8 shadow-2xl rounded-2xl border border-gray-200">
    <form wire:submit.prevent="saveInvoice" class="space-y-6">
        <!-- Heading -->
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M9 17H5a2 2 0 01-2-2v-6a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2h-4M9 17v2a4 4 0 104 0v-2">
                </path>
            </svg>
            Create Invoice
        </h2>

        <!-- Client & Invoice Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
                <label class="block mb-1 text-gray-700 font-medium">Client Name</label>
                <input type="text" wire:model="clientName" placeholder="Client Name"
                    class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-400 pl-10">
                <span class="absolute left-3 top-10 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.29.535 6.121 1.481M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                @error('clientName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-700 font-medium">Invoice Number</label>
                <input type="text" wire:model="invoiceNumber" placeholder="Invoice #"
                    class="p-3 border rounded-xl w-full focus:ring-2 focus:ring-indigo-400">
                @error('invoiceNumber')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-700 font-medium">Invoice Date</label>
                <input type="date" wire:model="invoiceDate"
                    class="p-3 border rounded-xl w-full focus:ring-2 focus:ring-indigo-400">
                @error('invoiceDate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-700 font-medium">Due Date</label>
                <input type="date" wire:model="dueDate"
                    class="p-3 border rounded-xl w-full focus:ring-2 focus:ring-indigo-400">
                @error('dueDate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="space-y-4">
            <h3 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                </svg>
                Invoice Items
            </h3>

            @foreach ($items as $index => $item)
                <div class="flex gap-2 items-start flex-wrap">
                    <div class="flex flex-col flex-1">
                        <label class="text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" wire:model="items.{{ $index }}.description"
                            placeholder="Description"
                            class="border p-2 rounded-lg w-full focus:ring-2 focus:ring-indigo-300">
                        @error('items.' . $index . '.description')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col w-24">
                        <label class="text-sm font-medium text-gray-700 mb-1">Qty</label>
                        <input type="number" wire:model.live="items.{{ $index }}.quantity" min="1"
                            class="border p-2 rounded-lg w-full focus:ring-2 focus:ring-indigo-300">
                        @error('items.' . $index . '.quantity')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col w-32">
                        <label class="text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                        <input type="number" wire:model.live="items.{{ $index }}.unitPrice" step="0.01"
                            class="border p-2 rounded-lg w-full focus:ring-2 focus:ring-indigo-300">
                        @error('items.' . $index . '.unitPrice')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <button type="button" wire:click="removeItem({{ $index }})"
                            class="text-red-600 hover:bg-red-100 rounded-full p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            <button type="button" wire:click="addItem"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Add Item
            </button>
        </div>

        <!-- Discount & Tax Section -->
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Discount (%)</label>
                    <input type="number" wire:model.live="discount" placeholder="e.g. 10"
                        class="p-3 border rounded-xl w-full focus:ring-2 focus:ring-indigo-400">
                    @error('discount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Tax (%)</label>
                    <input type="number" wire:model.live="tax" placeholder="e.g. 5"
                        class="p-3 border rounded-xl w-full focus:ring-2 focus:ring-indigo-400">
                    @error('tax')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="bg-gray-50 rounded-xl p-4 shadow-inner space-y-1 text-gray-800 font-medium">
            <p>Subtotal: <span class="float-right">${{ number_format($this->subtotal, 2) }}</span></p>
            <p>Discount: <span class="float-right text-red-500">-
                    ${{ number_format($this->discountAmount, 2) }}</span>
            </p>
            <p>Tax: <span class="float-right text-green-600">+ ${{ number_format($this->taxAmount, 2) }}</span></p>
            <hr>
            <p class="text-xl font-bold">Total: <span
                    class="float-right text-indigo-600">${{ number_format($this->total, 2) }}</span></p>
        </div>

        <!-- Client Email -->
        <div>
            <label class="block mb-1 text-gray-700 font-medium">Client Email (Optional)</label>
            <div class="relative">
                <input type="email" wire:model.defer="client_email" placeholder="Enter client email"
                    class="w-full p-3 pl-10 border rounded-xl focus:ring-2 focus:ring-indigo-400">
                <span class="absolute left-3 top-10 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m0 0l4 4m-4-4l4-4" />
                    </svg>
                </span>
            </div>
            @error('client_email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 text-lg font-semibold rounded-xl shadow-lg transition-all duration-200">
            💾 Save Invoice
        </button>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="mt-4 text-green-700 text-center bg-green-100 p-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif
    </form>
</div>
