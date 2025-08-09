<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Invoice;
use Livewire\Component;
use App\Events\SendEmail;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Exports\InvoiceExport;
use App\Exports\InvoicesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceIndex extends Component
{
    use WithPagination;

    public $user;

    public $statuses = [];
    #[Url]
    public $searchByEmail = '';

    #[Url]
    public $searchByInvoiceNumber = 0;

    #[Url]
    public $searchByStatus = '';

    public $issueDateFrom = '';
    public $issueDateTo = '';
    public $dueDateFrom = '';
    public $dueDateTo = '';

    public $paginateNumber = 10;

    public function mount(User $user)
    {
        $this->user = $user->load('invoices');
        $invoices = $this->user->invoices;
        foreach ($invoices as $invoice) {
            $this->statuses[$invoice->id] = $invoice->status;
        }
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

    public function updatedStatuses(string $value, int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = $value;
        $invoice->save();

        session()->flash('success', "Invoice #{$invoice->invoice_number} status updated to {$value}.");
    }

    public function updated()
    {
        $this->resetPage();
    }


    public function exportCsv()
    {
        $filters = [
            'user_id'              => $this->user->id,
            'searchByEmail'        => $this->searchByEmail,
            'searchByInvoiceNumber' => $this->searchByInvoiceNumber,
            'searchByStatus'       => $this->searchByStatus,
            'issueDateFrom'        => $this->issueDateFrom,
            'issueDateTo'          => $this->issueDateTo,
            'dueDateFrom'          => $this->dueDateFrom,
            'dueDateTo'            => $this->dueDateTo,
        ];

        return Excel::download(new InvoiceExport($filters), 'invoices export ' . now() . '.csv');
    }

    public function render()
    {
        $query =  Invoice::where('user_id', $this->user->id);

        if ($this->searchByEmail !== '') {
            $query->where('client_email', 'like', '%' . $this->searchByEmail . '%');
        }

        if ($this->searchByInvoiceNumber > 0) {
            $query->where('invoice_number',  $this->searchByInvoiceNumber);
        }

        if ($this->searchByStatus !== '') {
            $query->where('status',  $this->searchByStatus);
        }

        if ($this->issueDateFrom && $this->issueDateTo && $this->issueDateFrom <= $this->issueDateTo) {
            $query->whereBetween('invoice_date', [$this->issueDateFrom, $this->issueDateTo]);
        }

        if ($this->dueDateFrom && $this->dueDateTo && $this->dueDateFrom <= $this->dueDateTo) {
            $query->whereBetween('due_date', [$this->dueDateFrom, $this->dueDateTo]);
        }

        $invoices = $query->paginate($this->paginateNumber);

        return view('livewire.invoice-index', compact('invoices'));
    }
}


/* Here i swapped all of  these  with the updated function as it hits all of them*/
    // public function updatedSearchByEmail(){$this->resetPage();}
    // public function updatedSearchByInvoiceNumber(){$this->resetPage();}
    // public function updatedSearchByStatus(){$this->resetPage();}
    // public function updatedIssueDateFrom(){$this->resetPage();}
    // public function updatedIssueDateTo(){$this->resetPage();}
    // public function updatedDueDateFrom(){$this->resetPage();}
    // public function updatedDueDateTo(){$this->resetPage();}
