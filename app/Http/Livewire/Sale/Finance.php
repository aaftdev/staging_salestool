<?php

namespace App\Http\Livewire\Sale;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Finance extends Component
{
    public $sale, $name, $email, $contact, $program_id, $fees, $program_name, $sale_id, $payment, $batch, $txnid, $mode, $created_at, $bank;
    public $amounts = [];
    public $total = 0;
    public $gst = 0;
    public $other = 0;
    public $paid_total = 0;
    public $paid_other = 0;
    public $paid_fees = [];
    public $paid_fees_status = [];
    public $remaining_amount = 0;
    public $discount = 0;
    public $states = ['Andhra Pradesh', 'Andaman and Nicobar Islands', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Dadar and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Lakshadweep', 'Puducherry', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];
    public $batch_commence;
    public $popup = false;
    public $modes = ["UPI", "Net Banking", "PayU Money", "Cash", "Loan", "SHOPSE", "EarlySalary", "CREDNC", "Eduvanz", "Financepeer","Razorpay","GrayQuest", "Jodo", "Varthana", "Bajaj Finance"];


    public function mount($sale)
    {
        $this->sale_id = $sale->id;
        $this->name = $sale->name;
        $this->email = $sale->email;
        $this->contact = $sale->contact;
        $this->address = $sale->address;
        $this->state = $sale->state;
        $this->discount = $sale->discount;
        $this->program_name = Program::find($sale->program_id) ? Program::find($sale->program_id)->name : '';
        $this->batch = Batch::find($sale->batch_id) ? Batch::find($sale->batch_id)->name : "";

        if (Batch::find($sale->batch_id)) {
            $this->batch_commence = Carbon::parse(Batch::find($sale->batch_id)->commence_date)->format('d/m/Y');
        }
        $payments = Payment::where('sale_id', $sale->id)->get();

        foreach (json_decode($sale->payments) as $key => $fee) {
            $this->fees[$fee->name] = $fee->amount;
            $this->paid_fees[$key] = 0;
        }

        if (!empty($payments)) {
            foreach ($payments as $payment) {
                $this->paid_total += $payment->paid;
            }
        }

        $countFee = 0;

        // $this->fees[array_key_last($this->fees)] = ($this->fees[array_key_last($this->fees)] - ((array_sum($this->fees) * $this->discount) / 100));

        foreach ($this->fees as $key => $value) {
            $countFee += $value;
            if ($this->paid_total >= $countFee) {
                $this->paid_fees[$key] = $value;
                $this->paid_fees_status[$key] = true;
            } else {
                $this->paid_fees[$key] = $countFee - $this->paid_total;
                $this->paid_fees_status[$key] = false;
                break;
            }
        }

        $this->remaining_amount = array_sum($this->fees) - $this->paid_total - $sale->otd;
    }

    public function render()
    {
        return view('livewire.sale.finance');
    }

    public function popup()
    {
        $this->validate([
            "name" => 'required',
            "email" => 'required',
            "contact" => 'required',
            "program_name" => 'required',
            "address" => 'required',
            "state" => 'required',
            "other" => "required",
        ]);
        if (empty($this->total)) {
            session()->flash('danger', 'Manual Payment Can not be Zero (0)');
        } else {
            $this->popup = !$this->popup;
        }
    }

    public function getAmount()
    {
        if (is_numeric($this->other)) {
            $this->total = array_sum($this->amounts) + $this->other;
            $this->gst = ($this->total * 18) / 100;

            if ((array_sum($this->fees) - $this->paid_total) < $this->total) {
                session()->flash('danger', 'Kindly Check Your Other Amount !!');
            }
        }
    }

    public function save()
    {
        $this->validate([
            "txnid" => 'required',
            "created_at" => 'required',
            "mode" => "required",
            "bank" => "required",
        ]);
        $payment_process = Payment::create([
            "name" => $this->name,
            "email" => $this->email,
            "contact" => $this->contact,
            "address" => $this->address,
            "state" => $this->state,
            "sale_id" => $this->sale_id,
            "program_name" => $this->program_name,
            "batch_name" => $this->batch,
            "other" => $this->other,
            "fees" => json_encode($this->amounts),
            "paid" => $this->total,
            "txnid" => $this->txnid,
            "reference_date" => Carbon::parse($this->created_at),
            "created_at" => Carbon::now(),
            "mode" => $this->mode,
            "user_id" => Auth::user()->id,
        ]);

        $sale = Sale::findOrFail($this->sale_id);
        if ($sale->status === 0) {
            $sale->enrollment_date = $payment_process->reference_date;
        }
        $sale->status = 1;
        $date = explode('-', Carbon::parse($sale->enrollment_date)->format('Y-m-d'));
        $sale->save();

        $receipt = new Helper();
        $receipt->FinanceHelper($this->sale_id);
        $bank = $this->bank;
        $receipt->generateReceipt($payment_process->id, $bank);


        // dd(Payment::latest()->first());

        return redirect()->route('admin.sales')->with('success', $payment_process->name . ' payment added !!');
    }
}
