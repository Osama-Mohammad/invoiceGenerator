<?php

namespace App\Livewire;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceShow extends Component
{
    public $invoice;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load('invoiceItems');
    }

    public function render()
    {
        return view('livewire.invoice-show');
    }
}
