<div class="max-w-7xl mx-auto px-6 py-10 bg-gray-50 min-h-screen">

    <h1 class="text-3xl font-semibold text-gray-900 mb-8 border-b border-gray-200 pb-4">
        Invoices
    </h1>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-5 right-5 max-w-sm w-full bg-green-50 border border-green-300 text-green-800 px-6 py-3 rounded-md shadow-md font-semibold drop-shadow-md"
            style="display: none;">
            {{ session('success') }}
        </div>
    @endif

    <section aria-label="Search Filters"
        class="mb-8 max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-lg font-medium text-gray-700 mb-6 tracking-wide">Search Filters</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Email -->
            <div>
                <label for="searchByEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="searchByEmail" type="text" wire:model.live.debounce.500='searchByEmail'
                    placeholder="Enter customer email"
                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 placeholder-gray-400
          focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-gray-900 text-sm" />
            </div>

            <!-- Invoice Number -->
            <div>
                <label for="searchByInvoiceNumber" class="block text-sm font-medium text-gray-700 mb-1">Invoice
                    Number</label>
                <input id="searchByInvoiceNumber" type="number" wire:model.live.debounce.500='searchByInvoiceNumber'
                    placeholder="Invoice #"
                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 placeholder-gray-400
          focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-gray-900 text-sm" />
            </div>

            <!-- Status -->
            <div>
                <label for="searchByStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="searchByStatus" wire:model.live.debounce.500='searchByStatus'
                    class="block w-full rounded-md border border-gray-300 shadow-sm px-3 py-2
          focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-gray-900 text-sm">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <!-- Issue Date Range -->
            <div class="md:col-span-3 flex gap-5">
                <div class="flex-1">
                    <label for="issueDateFrom" class="block text-sm font-medium text-gray-700 mb-1">Issue Date
                        From</label>
                    <input id="issueDateFrom" type="date" wire:model.lazy="issueDateFrom"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-900
            focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white shadow-sm" />
                </div>
                <div class="flex-1">
                    <label for="issueDateTo" class="block text-sm font-medium text-gray-700 mb-1">Issue Date To</label>
                    <input id="issueDateTo" type="date" wire:model.lazy="issueDateTo"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-900
            focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white shadow-sm" />
                </div>
            </div>

            <!-- Due Date Range -->
            <div class="md:col-span-3 flex gap-5">
                <div class="flex-1">
                    <label for="dueDateFrom" class="block text-sm font-medium text-gray-700 mb-1">Due Date From</label>
                    <input id="dueDateFrom" type="date" wire:model.lazy="dueDateFrom"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-900
            focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white shadow-sm" />
                </div>
                <div class="flex-1">
                    <label for="dueDateTo" class="block text-sm font-medium text-gray-700 mb-1">Due Date To</label>
                    <input id="dueDateTo" type="date" wire:model.lazy="dueDateTo"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-900
            focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white shadow-sm" />
                </div>
            </div>

        </div>
    </section>

    <section aria-label="Invoice List" class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
        <button wire:click="exportCsv" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">
            Export Invoices CSV
        </button>

        <table class="min-w-full table-auto text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase text-gray-600 tracking-wide">
                <tr>
                    <th class="px-5 py-4">Invoice #</th>
                    <th class="px-5 py-4">Issue Date</th>
                    <th class="px-5 py-4">Due Date</th>
                    <th class="px-5 py-4">Amount Due</th>
                    <th class="px-5 py-4">Customer Name</th>
                    <th class="px-5 py-4">Customer Email</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr wire:key="{{ $invoice->id }}" class="border-b hover:bg-gray-50">
                        <td class="px-5 py-3 font-semibold text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                        <td class="px-5 py-3 font-mono text-gray-900">${{ number_format($invoice->total, 2) }}</td>
                        <td class="px-5 py-3">{{ $invoice->client_name }}</td>
                        <td class="px-5 py-3">{{ $invoice->client_email }}</td>
                        <td class="px-5 py-3">
                            {{-- Status --}}
                            <select wire:model.live.debounce.500='statuses.{{ $invoice->id }}'
                                class="border border-gray-300 rounded-md px-3 py-1 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white shadow-sm">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </td>
                        <td class="px-5 py-3 text-right space-x-3">

                            {{-- View --}}
                            <a href="{{ route('invoice_show', $invoice->id) }}"
                                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>

                            {{-- Send --}}
                            <button wire:click='send({{ $invoice->id }})'
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4l16 8-16 8V4zm0 0l8 8-8 8" />
                                </svg>
                                Send
                            </button>

                            {{-- Download --}}
                            <button wire:click='download({{ $invoice->id }})'
                                class="inline-flex items-center text-green-600 hover:text-green-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                Download
                            </button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500 font-medium">No invoices found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-8 px-5">
            {{ $invoices->links(data: ['scrollTo' => false]) }}
        </div>
    </section>
</div>
