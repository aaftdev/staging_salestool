<?php

namespace App\Exports;

use App\Models\Batch;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public $program;

    public function __construct($program)
    {
        $this->program = $program;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $sale = Sale::latest();
        if (!empty($this->program)) {
            $sale->whereIn('program_id', $this->program);
        }
        return $sale->get();
    }

    public function headings(): array
    {
        return ["ID", "Name", "Email", "Counsellor", "Contact", "Program", "Batch", "Scholorship", "Amount", "Final Amount", "Pay Terms", "State", "Address", "Status", "Created at", "Updated at"];
    }

    public function map($sale): array
    {
        $sale_status = "offered";
        switch ($sale->status) {
            case '0':
                $sale_status = "Offered";
                break;
            case '1':
                $sale_status = "Enrolled";
                break;
            case '2':
                $sale_status = "Offered Expired";
                break;

            default:
                $sale_status = "Offered";
                break;
        }
        return [
            $sale->id,
            $sale->name,
            $sale->email,
            $sale->counsellor,
            $sale->contact,
            Program::find($sale->program_id) ? Program::find($sale->program_id)->name : "",
            Batch::find($sale->batch_id) ? Batch::find($sale->batch_id)->name : "",
            $sale->discount,
            $sale->amount,
            $sale->final_amount,
            $sale->payment_term,
            $sale->state,
            $sale->address,
            $sale_status,
            Carbon::parse($sale->created_at)->format('M d,Y'),
            Carbon::parse($sale->updated_at)->format('M d,Y'),
        ];
    }
}
