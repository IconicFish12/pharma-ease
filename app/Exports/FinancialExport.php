<?php

namespace App\Exports;

use App\Models\SalesTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
     public function collection()
    {
        return SalesTransaction::with('user')
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function headings(): array
    {
        return ['Date', 'Code', 'Cashier', 'Total Amount'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date,
            $transaction->transaction_code,
            $transaction->user->name ?? '-',
            $transaction->total_amount,
        ];
    }
}
