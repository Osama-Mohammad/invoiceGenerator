<?php

namespace App\Livewire;

use App\Models\Invoice;
use Livewire\Component;
use App\Events\SendEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvoiceCreator extends Component
{
    public   $dueDate;

    public  $clientName;

    public  $invoiceDate;

    public $invoiceNumber;

    public $discount = 0;

    public $tax = 0;

    public $client_email;

    public function rules()
    {
        return [
            'clientName' => 'required|string|max:255',
            'invoiceNumber' => 'required|numeric',
            'invoiceDate' => 'required|date|after:now',
            'dueDate' => 'required|date|after:invoiceDate',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'client_email' => 'required|email',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitPrice' => 'required|numeric|min:0',
        ];
    }


    public $items = [
        [
            'description' => '',
            'quantity' => 1,
            'unitPrice' => 0
        ]
    ];

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'quantity' => 1,
            'unitPrice' => 0
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // reindex
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum(fn($item) => (float) $item['quantity'] * (float) $item['unitPrice']);
    }

    public function getDiscountAmountProperty()
    {
        return $this->subtotal * ((float) $this->discount / 100);
    }

    public function getTaxAmountProperty()
    {
        return ($this->subtotal -  $this->discountAmount) * ((float) $this->tax / 100);
    }

    public function getTotalProperty()
    {
        return $this->subtotal - $this->discountAmount + $this->taxAmount;
    }


    public function saveInvoice()
    {
        $this->validate();
        $invoice = Invoice::create([
            'user_id' => Auth::id(),
            'client_name' => $this->clientName,
            'invoice_number' => $this->invoiceNumber,
            'invoice_date' => $this->invoiceDate,
            'due_date' => $this->dueDate,
            'subtotal' => $this->subtotal,
            'discount' => $this->discountAmount,
            'tax' => $this->taxAmount,
            'total' => $this->total,
            'client_email'  => $this->client_email
        ]);

        foreach ($this->items as $item) {
            $invoice->invoiceItems()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unitPrice'],
                'total' => $item['quantity'] * $item['unitPrice']
            ]);
        }

        $invoice->load('invoiceItems');

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'invoiceItems' => $invoice->invoiceItems
        ]);

        $pdfBinary = $pdf->output();

        event(new SendEmail($invoice, $this->client_email, $pdfBinary));

        session()->flash('success', 'Invoice created successfully!');

        return response()->streamDownload(function () use ($pdfBinary) {
            echo $pdfBinary;
        }, "invoice_{$invoice->invoice_number}.pdf");
    }

    public function render()
    {
        return view('livewire.invoice-creator');
    }
}
