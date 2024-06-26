<?php

namespace App\Exports;

use App\Models\Payment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Payment::all();
    }

    public function headings(): array
    {
        return ["ID", "Name", "Email", "Program Name", "Batch Name", "Contact", "State", "Addrss", "others", "Fees", "Paid", "Txn ID", "Payment Mode", "Reference Date", "Created At"];
    }

    public function map($row): array
    {

        return [
            $row->id,
            $row->name,
            $row->email,
            $row->program_name,
            $row->batch_name,
            $row->contact,
            $row->state,
            $row->address,
            $row->other,
            $row->fees,
            $row->paid,
            $row->txnid,
            $row->mode,
            Carbon::parse($row->reference_date)->format('M d,Y'),
            Carbon::parse($row->created_at)->format('M d,Y'),
        ];
    }
}
