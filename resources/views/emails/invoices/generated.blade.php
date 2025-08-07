@component('mail::message')
# Invoice #{{ $invoice->invoice_number }}

Hello {{ $invoice->client_name }},

Please find below your invoice details:
We also provided a PDF copy for you'r convenience in the attachments:

@component('mail::table')
| Description       | Quantity | Unit Price | Total       |
| ----------------- |:--------:| ----------:| -----------:|
@foreach($items as $item)
| {{ $item->description }} | {{ $item->quantity }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->total, 2) }} |
@endforeach
|                   |          | **Subtotal:** | ${{ number_format($invoice->subtotal, 2) }} |
|                   |          | **Discount:** | -${{ number_format($invoice->discount, 2) }} |
|                   |          | **Tax:**      | +${{ number_format($invoice->tax, 2) }} |
|                   |          | **Total:**    | ${{ number_format($invoice->total, 2) }} |
@endcomponent

Thanks for doing  business with us!

Regards,
{{ config('app.name') }}
@endcomponent
