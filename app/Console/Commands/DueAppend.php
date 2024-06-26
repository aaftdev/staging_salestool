<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Due;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DueAppend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'due:append';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Due Append Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog;
        }
        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat;
        }
        $due_count = 0;
        foreach (Receipt::all() as $receipt) {
            if (empty(Due::where('EnrollmentNo', $receipt->EnrollmentNo)->first())) {
                $enrollment_no = $receipt->EnrollmentNo;
                $sale = Sale::where('enrollment_no', $enrollment_no)->first();
                if (!empty($sale) && ($courses[$sale->program_id]->term === 'certification') && ($receipt->entry_type === 'N')) {
                    $sale_ids[] = $sale->id;
                }
            }
        }

        if (!empty($sale_ids)) {
            foreach (Sale::whereIn('id', array_unique($sale_ids))->get() as $sale) {
                $vchno = explode('/', Due::latest()->first()->VchNo)[2] + 1;
                $scholarship = $sale->amount - $sale->final_amount;
                $yr = Carbon::parse($sale->created_at)->format('y');
                $yr1 = $yr + 1;
                $session = $yr . "-" . $yr1;
                $due = new Due();
                $due->VchNo = "AAFTE/" . $session . "/" . ++$vchno;
                $due->VchDate = Carbon::now();
                $due->DueDate = $batches[$sale->batch_id]->commence_date;
                $due->LedgerName = $sale->enrollment_no . " - " . $sale->name;
                $due->EnrollmentNo = $sale->enrollment_no;
                $due->State = $sale->state;
                $due->Batch = $batches[$sale->batch_id]->name;
                $due->Course = $courses[$sale->program_id]->name;
                $due->FeeHead = "Course Fee";
                $due->Amount = $sale->final_amount;
                $due->MRP = $sale->amount;
                $due->Scholarship = $scholarship;
                $due->created_at = Carbon::now();
                $due->save();
                ++$due_count;
            }
        }
        if ($due_count > 0) {
            $this->info($due_count . ' Due Generated !!');
        } else {
            $this->info('There were no due to create !!');
        }
    }
}
