<?php

namespace App\Livewire;

use App\Events\SendEmail;
use App\Models\Invoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class InvoiceIndex extends Component
{

    public $user;
    public $invoices;

    public function mount(User $user)
    {
        $this->user = $user->load('invoices');
        $this->invoices = $this->user->invoices;
    }

    public function download(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'invoiceItems' => $invoice->invoiceItems
        ]);
        $pdfBinary = $pdf->output();
        return response()->streamDownload(function () use ($pdfBinary) {
            echo $pdfBinary;
        }, "invoice_{$invoice->invoice_number}.pdf");
    }

    public function send(int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->load('invoiceItems');
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'invoiceItems' => $invoice->invoiceItems
        ]);
        $pdfBinary = $pdf->output();
        event(new SendEmail($invoice, $invoice->client_email, $pdfBinary));
        session()->flash('success', 'Invoice Sent Via Email Successfully!');
    }

    public function render()
    {
        return view('livewire.invoice-index');
    }
}
