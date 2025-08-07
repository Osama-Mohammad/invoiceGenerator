<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Invoices</h2>
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-5 right-5 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-md shadow-lg font-semibold drop-shadow-md"
            style="display: none;">
            <p>Invoice Sent Via Email Successfully!</p>
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3">Invoice #</th>
                    <th class="px-4 py-3">Issue Date</th>
                    <th class="px-4 py-3">Due Date</th>
                    <th class="px-4 py-3">Amount Due</th>
                    <th class="px-4 py-3">Customer Name</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr wire:key="{{ $invoice->id }}" class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3">${{ number_format($invoice->total, 2) }}</td>
                        <td class="px-4 py-3">{{ $invoice->client_name }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            {{-- View --}}
                            <a href="{{ route('invoice_show', $invoice->id) }}"
                                class="inline-flex items-center text-indigo-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
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
                                class="inline-flex items-center text-blue-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4l16 8-16 8V4zm0 0l8 8-8 8" />
                                </svg>
                                Send
                            </button>

                            {{-- Download --}}
                            <button class="inline-flex items-center text-green-600 hover:underline"
                                wire:click='download({{ $invoice->id }})'>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
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
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No Invoices Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
