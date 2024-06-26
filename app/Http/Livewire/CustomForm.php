<?php

namespace App\Http\Livewire;

use App\Models\Batch;
use App\Models\Due;
use App\Models\Enrolled;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomForm extends Component
{

    //Generate Due Public 
    public $due_month, $due_year, $due_vchno;

    //Generate Enroll Public 
    public $enroll_month, $enroll_year;

    //Generate Receipt Public 
    public $receipt_month, $receipt_year, $receipt_vchno, $receipt_bank;


    public function render()
    {
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'Aug',
            9 => 'Sept',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];
        return view('livewire.custom-form', compact('months'));
    }

    public function DueGenerate()
    {
        $this->validate([
            "due_month" => 'required',
            "due_year" => 'required',
            "due_vchno" => 'required|numeric',
        ]);

        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog;
        }

        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat;
        }

        DB::table('dues')->truncate();
        $vchno = $this->due_vchno;
        foreach (Sale::where('entry_type', '<>', NULL)->whereMonth('enrollment_date', $this->due_month)->whereYear('enrollment_date', $this->due_year)->get() as $sale) {
            if (($courses[$sale->program_id]->term === 'diploma') && ($batches[$sale->batch_id]->commence_date <= Carbon::today())) {
                $scholarship = $sale->amount - $sale->final_amount;
                $yr = Carbon::parse($sale->created_at)->format('y');
                $yr1 = $yr + 1;
                $session = $yr . "-" . $yr1;
                $due = new Due();
                $due->VchNo = "AAFTE/" . $session . "/" . $vchno++;
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
            }

            if (($courses[$sale->program_id]->term === 'certification') && $sale->entry_type === 'N') {
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
            }
        }

        return session()->flash('due_success', 'Due Generate Successfully !!');
    }

    public function EnrolledGenerate()
    {

        $this->validate([
            "enroll_month" => 'required',
            "enroll_year" => 'required',
        ]);

        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog;
        }

        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat;
        }

        DB::table('enrolleds')->truncate();
        foreach (Sale::whereMonth('enrollment_date', $this->enroll_month)->whereYear('enrollment_date', $this->enroll_year)->get() as $sale) {
            $yr = Carbon::parse($sale->created_at)->format('Y');
            $nxt_yr = $yr + 1;

            $enrolled = new Enrolled();
            $enrolled->Name = $sale->name;
            $enrolled->AdmissionNo = $sale->enrollment_no;
            $enrolled->AdmissionDate = $sale->created_at;
            $enrolled->CourseName = $courses[$sale->program_id]->name;
            $enrolled->BatchName = $batches[$sale->batch_id]->name;
            $enrolled->Session = $yr . "-" . $nxt_yr;
            $enrolled->Status = "Joined";
            $enrolled->Location = $sale->location;
            $enrolled->Email = $sale->email;
            $enrolled->Mobile = $sale->contact;
            $enrolled->Address = !empty($sale->address) ? $sale->address : 'N/A';
            $enrolled->State = $sale->state;
            $enrolled->LedgerName = $sale->enrollment_no . " - " . $sale->name;
            $enrolled->importedflag = 0;
            $enrolled->save();
        }
        return session()->flash('enroll_success', 'Enrolled User Generate Successfully !!');
    }

    public function ReceiptGenerate()
    {

        $this->validate([
            "receipt_month" => 'required',
            "receipt_year" => 'required',
            "receipt_bank" => 'required',
            "receipt_vchno" => 'required|numeric',
        ]);


        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog;
        }

        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat;
        }

        $vchno = $this->receipt_vchno;
        DB::table('receipts')->truncate();
        foreach (Payment::whereMonth('created_at', $this->receipt_month)->whereYear('created_at', $this->receipt_year)->get() as $payment) {
            $sale = Sale::findOrFail($payment->sale_id);
            if ($payment->created_at >= date('2022-07-01')) {
                $receipt = new Receipt();
                $receipt->VchNo = "P-" . $vchno++;
                $receipt->VchDate = Carbon::now();
                $receipt->LedgerName = $sale->enrollment_no . " - " . $sale->name;
                $receipt->State = $sale->state;
                $receipt->EnrollmentNo = $sale->enrollment_no;
                $receipt->BatchStartDate = $sale->batch($sale->batch_id)->commence_date;
                $receipt->Batch = $sale->batch($sale->batch_id)->name;
                $receipt->Course = $sale->batch($sale->program_id)->name;
                $receipt->FeeHead = "Course Fee";
                $receipt->Amount = $payment->paid;
                $receipt->PaymentMode = $payment->mode;
                $receipt->ReferenceNo = $payment->txnid;
                $receipt->ReferenceDate = $payment->created_at;
                $receipt->BankName = $this->receipt_bank;
                $receipt->entry_type = $sale->entry_type;
                $receipt->term = ($courses[$sale->program_id] === 'diploma') ? 'L' : 'S';
                $receipt->importedflag = 0;
                $receipt->save();
            }
        }

        return session()->flash('receipt_success', 'Receipt Generate Successfully !!');
    }
}
