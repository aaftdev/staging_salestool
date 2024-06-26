<?php

namespace App\Http\Livewire;

use App\Models\Batch;
use App\Models\DirectPayment;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OnlinePayment extends Component
{
    public $paid;
    public $email, $contact, $txnid, $program, $from, $to, $programs, $direct_payment;
    public $verify_offer = false;
    public $sales = [];
    public $courses = [];
    public $batches = [];
    public function mount()
    {
        $this->getSale();
        $this->programs = Program::latest()->get();
    }
    public function render()
    {
        return view('livewire.online-payment');
    }

    public function verifyOffer($id)
    {
        $this->direct_payment = $id;
        $this->verify_offer = true;
        $payment = DirectPayment::find($id);

        $sales_email = Sale::where('email', $payment->email)->where('program_id', $payment->course)->pluck('id');
        $sales_contact = Sale::where('contact', $payment->contact)->where('program_id', $payment->course)->pluck('id');
        $ids = [];
        foreach ($sales_email as $id) {
            $ids[] = $id;
        }
        foreach ($sales_contact as $id) {
            $ids[] = $id;
        }

        $this->courses = [];
        foreach (Program::all() as $prog) {
            $this->courses[$prog->id] = $prog->name;
        }

        $this->batches = [];
        foreach (Batch::all() as $bat) {
            $this->batches[$bat->id] = $prog->name;
        }
        $this->sales = Sale::whereIn('id', array_unique($ids))->get();
    }

    public function Back()
    {
        $this->direct_payment = 0;
        $this->verify_offer = false;
    }

    public function MapOffer($id)
    {
        $sale = Sale::find($id);
        if ($pay = DirectPayment::find($this->direct_payment)) {
            Payment::create([
                "name" => $pay->name,
                "email" => $pay->email,
                "contact" => $pay->contact,
                "address" => $pay->address,
                "state" => $pay->state,
                "sale_id" => $id,
                "program_name" => Program::findorFail($sale->program_id)->name,
                "batch_name" => Batch::findOrFail($sale->batch_id)->name,
                "other" => $pay->paid,
                "fees" => json_encode([]),
                "paid" => $pay->paid,
                "txnid" => $pay->txnid,
                'reference_date' => $pay->created_at,
                "mode" => 'payu',
                "created_at" => $pay->created_at,
            ]);

            $sale->status = 1;
            $sale->enrollment_date = $pay->reference_date;
            $sale->update();
            $pay->status = "verified";
            $pay->save();

            $this->Back();
            return Session::flash('success', 'Payment Mapped Successfully !!');
        }
    }

    public function getSale()
    {
        $tmpPaid = DirectPayment::latest()->where('status', 'un-verified');
        if (!empty($this->from)) {
            $tmpPaid->where('created_at', '>=', Carbon::parse($this->from)->format('Y-m-d'));
        }
        if (!empty($this->to)) {
            $tmpPaid->where('created_at', '>=', Carbon::parse($this->from)->format('Y-m-d'));
        }
        if (!empty($this->email)) {
            $tmpPaid->where('email', 'LIKE', '%' . $this->email . '%');
        }
        if (!empty($this->contact)) {
            $tmpPaid->where('contact', 'LIKE', '%' . $this->contact . '%');
        }
        if (!empty($this->txnid)) {
            $tmpPaid->where('txnid', 'LIKE', '%' . $this->txnid . '%');
        }
        $this->paid = $tmpPaid->get();
    }
}
