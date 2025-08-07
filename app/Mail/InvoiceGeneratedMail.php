<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $invoiceItems;
    public $pdfBinary;

    public function __construct(Invoice $invoice, string $pdfBinary)
    {
        $this->invoice = $invoice;
        $this->invoiceItems = $invoice->invoiceItems;
        $this->pdfBinary = $pdfBinary;
    }

    public function build()
    {
        return $this
            ->subject('Invoice #' . $this->invoice->invoice_number)
            ->markdown('emails.invoices.generated', [
                'invoice' => $this->invoice,
                'items' => $this->invoiceItems,
            ])
            ->attachData(
                $this->pdfBinary,
                "invoice_{$this->invoice->invoice_number}.pdf",
                ['mime' => 'application/pdf']
            );
    }
}
