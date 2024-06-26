<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\Enrolled;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Helper
{
    public static function generateReceipt($payment_id, $bank)
    {
        $vchno = Receipt::latest()->first()->id;
        $vchno += 1394;
        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog->term;
        }
        $payment = Payment::findOrFail($payment_id);
        $sale = Sale::findOrFail($payment->sale_id);

        if ($payment->created_at >= date('2022-07-01')) {
            $receipt = new Receipt();
            $receipt->VchNo = "P-" . ++$vchno;
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
            $receipt->BankName = $bank;
            $receipt->entry_type = $sale->entry_type;
            $receipt->term = ($courses[$sale->program_id] === 'diploma') ? 'L' : 'S';
            $receipt->importedflag = 0;
            $receipt->save();
            return Session::flash('success', 'Receipt Created');
        }
    }

    public static function StudentMaster($enrollment_no)
    {
        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog->name;
        }

        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat->name;
        }
        if (!Enrolled::where('AdmissionNo', $enrollment_no)->first()) {
            if ($sale = Sale::where('enrollment_no', $enrollment_no)->where('created_at', '>=', date('2022-07-01'))->first()) {
                $yr = Carbon::parse($sale->created_at)->format('Y');
                $nxt_yr = $yr + 1;

                $enrolled = new Enrolled();
                $enrolled->Name = $sale->name;
                $enrolled->AdmissionNo = $sale->enrollment_no;
                $enrolled->AdmissionDate = $sale->created_at;
                $enrolled->CourseName = $courses[$sale->program_id];
                $enrolled->BatchName = $batches[$sale->batch_id];
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

                return Session::flash('message', 'Enrolled Created!!');
            }
        }
        //  else {
        //     $enrolled = Enrolled::where('AdmissionNo', $enrollment_no)->first();
        //     if ($sale = Sale::where('enrollment_no', $enrollment_no)->where('created_at', '>=', date('2022-07-01'))->first()) {
        //         $yr = Carbon::parse($sale->created_at)->format('y');
        //         $nxt_yr = $yr + 1;
        //         $enrolled->Name = $sale->name;
        //         $enrolled->AdmissionNo = $sale->enrollment_no;
        //         $enrolled->AdmissionDate = $sale->created_at;
        //         $enrolled->CourseName = $courses[$sale->program_id];
        //         $enrolled->BatchName = $batches[$sale->batch_id];
        //         $enrolled->Session = $yr . "-" . $nxt_yr;
        //         $enrolled->Status = "Joined";
        //         $enrolled->Location = $sale->location;
        //         $enrolled->Email = $sale->email;
        //         $enrolled->Mobile = $sale->contact;
        //         $enrolled->Address = !empty($sale->address) ? $sale->address : 'N/A';
        //         $enrolled->State = $sale->state;
        //         $enrolled->importedflag = 0;
        //         $enrolled->save();

        //         return Session::flash('message', 'Enrolled Updated!!');
        //     }
        // }
    }


    public static function EntryType($sale_id)
    {
        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog->term;
        }

        $batches = [];
        foreach (Batch::all() as $bet) {
            $batches[$bet->id] = $bet->commence_date;
        }

        if ($sale = Sale::find($sale_id)) {
            if ($payments = Payment::where('sale_id', $sale->id)->sum('paid')) {
                $sale->total_paid = $payments;
                $sale->pending = (int)$sale->final_amount - (int)$payments;

                //Full Diploma Paid
                if (($sale->total_paid > 0) && ($sale->pending <= 0) && ($courses[$sale->program_id] === 'diploma')) {
                    if ($batches[$sale->batch_id] <= Carbon::now()) {
                        $sale->entry_type = 'N';
                    }
                }

                //Full Certificate Paid
                if (($sale->total_paid > 0) && ($sale->pending <= 0) && ($courses[$sale->program_id] === 'certification')) {
                    $sale->entry_type = 'N';
                }


                //Partial Payment
                if (($sale->total_paid > 0) && ($sale->pending > 0)) {
                    $sale->entry_type = 'A';
                }

                $sale->save();
            }
        }
    }

    public function FinanceHelper($sale_id)
    {
        if ($sale = Sale::findOrFail($sale_id)) {
            $this->StudentMaster($sale->enrollment_no);
            $this->EntryType($sale->id);
        }
    }
}
