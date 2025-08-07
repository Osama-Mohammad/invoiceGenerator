<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }

        .totals {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>

<body>
    <h1>Invoice #{{ $invoice->invoice_number }}</h1>

    <p><strong>Client Name:</strong> {{ $invoice->client_name }}</p>
    <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
    <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="totals"><strong>Subtotal:</strong></td>
                <td style="text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="totals"><strong>Discount:</strong></td>
                <td style="text-align: right;">-${{ number_format($invoice->discount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="totals"><strong>Tax:</strong></td>
                <td style="text-align: right;">+${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="totals"><strong>Total:</strong></td>
                <td style="text-align: right;">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Thank you for your business!
    </div>
</body>

</html>
