<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class InvoiceExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Invoice::where('user_id', $this->filters['user_id']);

        if (!empty($this->filters['searchByEmail'])) {
            $query->where('client_email', 'like', '%' . $this->filters['searchByEmail'] . '%');
        }

        if (!empty($this->filters['searchByInvoiceNumber']) && $this->filters['searchByInvoiceNumber'] > 0) {
            $query->where('invoice_number', $this->filters['searchByInvoiceNumber']);
        }

        if (!empty($this->filters['searchByStatus'])) {
            $query->where('status', $this->filters['searchByStatus']);
        }

        if (
            !empty($this->filters['issueDateFrom']) && !empty($this->filters['issueDateTo'])
            && $this->filters['issueDateFrom'] <= $this->filters['issueDateTo']
        ) {
            $query->whereBetween('invoice_date', [$this->filters['issueDateFrom'], $this->filters['issueDateTo']]);
        }

        if (
            !empty($this->filters['dueDateFrom']) && !empty($this->filters['dueDateTo'])
            && $this->filters['dueDateFrom'] <= $this->filters['dueDateTo']
        ) {
            $query->whereBetween('due_date', [$this->filters['dueDateFrom'], $this->filters['dueDateTo']]);
        }

        return $query->get([
            'invoice_number',
            'invoice_date',
            'due_date',
            'total',
            'client_name',
            'client_email',
            'status',
        ]);
    }


    public function headings(): array
    {
        return [
            'Invoice Number',
            'Issue Date',
            'Due Date',
            'Total Amount',
            'Customer Name',
            'Customer Email',
            'Status',
        ];
    }
}
