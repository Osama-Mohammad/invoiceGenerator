<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg border border-gray-200">
    <!-- Invoice Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Invoice #{{ $invoice->invoice_number }}</h2>
        <p class="text-gray-600">Client: <span class="font-semibold">{{ $invoice->client_name }}</span></p>
        <p class="text-gray-600">Invoice Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
        <p class="text-gray-600">Due Date: {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
    </div>

    <!-- Invoice Items Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 border"># items</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Quantity</th>
                    <th class="px-4 py-2 border">Unit Price</th>
                    <th class="px-4 py-2 border">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $index => $item)
                    <tr class="text-center border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->description }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Invoice Summary -->
    <div class="mt-6 text-right space-y-2">
        <p class="text-gray-700">Subtotal: <span
                class="font-semibold">${{ number_format($invoice->subtotal, 2) }}</span></p>
        <p class="text-gray-700">Discount: <span
                class="font-semibold">${{ number_format($invoice->discount, 2) }}</span></p>
        <p class="text-gray-700">Tax: <span class="font-semibold">${{ number_format($invoice->tax, 2) }}</span></p>
        <p class="text-xl text-gray-900 font-bold">Total: ${{ number_format($invoice->total, 2) }}</p>
    </div>
</div>
