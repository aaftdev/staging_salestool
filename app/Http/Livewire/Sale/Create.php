<?php

namespace App\Http\Livewire\Sale;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Complemantry;
use App\Models\DirectPayment;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $search_email, $name, $email, $counsellor, $contact, $batch_id, $amount, $discount, $final_amount, $state, $address, $location, $complementary_id, $enrollment_no, $otd, $direct_id, $sale_id, $type, $created_at, $link;
    public $search_phone;
    public $payment_term;
    public $payment_type = 0;
    public $sales = [];
    public $fees = [];
    public $batches = [];
    public $payments = [];
    public $program_id;
    public $status = 0;
    public $states = ['Andhra Pradesh', 'Andaman and Nicobar Islands', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Dadar and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Lakshadweep', 'Puducherry', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];

    public function mount(Request $request)
    {
        if (!empty($request->input('direct'))) {
            $direct_id = decrypt($request->input('direct'));
            $direct_payment = DirectPayment::findOrFail($direct_id);
            $this->direct_id = $direct_id;
            $this->name = $direct_payment->name;
            $this->email = $direct_payment->email;
            $this->contact = $direct_payment->contact;
            $this->program_id = $direct_payment->course;
            $this->batches = Batch::where('program_id', $this->program_id)->get();
            $this->address = $direct_payment->address;
            $this->state = $direct_payment->state;
        }
    }

    public function render()
    {
        $programs = Program::all();
        $complemantries = Complemantry::all();
        return view('livewire.sale.create', compact('programs', 'complemantries'));
    }

    public function resetInput()
    {
        $this->search_email = $this->search_phone = $this->direct_id = $this->sale_id = "";
    }

    public function enrollment()
    {
        $sale = Sale::latest()->first();
        $this->enrollment_no = "AAFT";
        $batch = Batch::findOrFail($this->batch_id);
        $this->enrollment_no .= '-' . $batch->code;
        $this->enrollment_no .= '|' . ($sale->id + 1);
    }

    public function get_data_from_email()
    {
        $this->validate([
            "search_email" => 'required'
        ]);

        $this->sales = Sale::where('email', $this->search_email)->get();
        $result = "";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-in21.leadsquared.com/v2/LeadManagement.svc/Leads.GetByEmailaddress?accessKey=u$r88f4d6ee8eeb50d10a51a061a15a7669&secretKey=fc71aae79594fdbd585e568f534ce47b1b84e4cc&emailaddress=' . $this->search_email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
            $resultdata = json_decode($response);
            // @$result = $resultdata[0]->ProspectID;
        }
        // return $result;
        // dd($resultdata);
        if (!empty($resultdata)) {
            $this->name = $resultdata[0]->FirstName . " " . $resultdata[0]->LastName;
            $this->email = $resultdata[0]->EmailAddress;
            $this->contact = explode('-', $resultdata[0]->Phone)[1];
            $this->counsellor = $resultdata[0]->OwnerIdName;
            $this->state = $resultdata[0]->mx_State;
            $this->address = $resultdata[0]->mx_Street1;
        } else {
            Session::flash('danger', 'No Record Found !!');
        }
    }

    public function get_data_from_phone()
    {
        $this->validate([
            "search_phone" => 'required'
        ]);
        $this->sales = Sale::where('contact', $this->search_phone)->get();
        $result = "";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-in21.leadsquared.com/v2/LeadManagement.svc/RetrieveLeadByPhoneNumber?accessKey=u$r88f4d6ee8eeb50d10a51a061a15a7669&secretKey=fc71aae79594fdbd585e568f534ce47b1b84e4cc&phone=' . $this->search_phone,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;
            $resultdata = json_decode($response);
            // @$result = $resultdata[0]->ProspectID;
        }
        // return $result;
        if (!empty($resultdata)) {
            $this->name = $resultdata[0]->FirstName . " " . $resultdata[0]->LastName;
            $this->email = $resultdata[0]->EmailAddress;
            $this->contact = explode('-', $resultdata[0]->Phone)[1];
            $this->counsellor = $resultdata[0]->OwnerIdName;
            $this->state = $resultdata[0]->mx_State;
            $this->address = $resultdata[0]->mx_Street1;
        } else {
            Session::flash('danger', 'No Record Found !!');
        }
    }

    public function get_payment_term()
    {
        // dd($this->program_id);
        if (is_numeric($this->program_id)) {
            $this->fees = FeeStructure::where('program_id', $this->program_id)->get();
            $this->batches = Batch::where('program_id', $this->program_id)->get();
        }
    }

    public function get_amount()
    {
        if (is_numeric($this->payment_term)) {
            $fee = FeeStructure::find($this->payment_term);
            $this->amount = $fee->total_fee;
            $this->payments = json_decode($fee->fees);

            foreach ($this->payments as $key => $pay) {
                switch ($key) {
                    case '0':
                        $pay->date = Carbon::now()->format('Y-m-d');
                        break;

                    case '1':
                        $pay->date = Carbon::now()->addDays(7)->format('Y-m-d');
                        break;

                    default:
                        $days = ($key - 1) * 28;
                        $commence_date = Batch::find($this->batch_id)->commence_date;
                        $pay->date = Carbon::parse($commence_date)->addDays($days)->format('Y-m-d');
                        break;
                }
            }
        }
    }

    public function get_discounted_amount()
    {
        $finalAmount = (int)$this->amount - (int)$this->discount;
        $this->final_amount = (int)$finalAmount;
        $lastKey = count($this->payments) - 1;
        $fees = json_decode(FeeStructure::find($this->payment_term)->fees);
        foreach ($this->payments as $key => $pay) {
            if ($key == $lastKey) {
                if (((int)$fees[$key]->amount - (int)$this->discount) < 0) {
                    (int)$this->payments[$key]['amount'] = 0;
                    if (count($this->payments) > 1) {
                        (int)$this->payments[$key - 1]['amount'] = (int)$this->payments[$key - 1]['amount'] + ((int)$fees[$key]->amount - (int)$this->discount);
                    }
                } else {
                    (int)$this->payments[$key]['amount'] = (int)$fees[$key]->amount - (int)$this->discount;
                    if (count($this->payments) > 1) {
                        (int)$this->payments[$key - 1]['amount'] = (int)$fees[$key - 1]->amount;
                    }
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            "name" => 'required',
            "email" => 'required',
            "contact" => 'required',
            "program_id" => 'required',
            "batch_id" => 'required',
            "amount" => 'required',
            "discount" => 'required',
            "final_amount" => 'required',
            "payment_term" => 'required',
            "state" => 'required',
            "location" => 'required',
            "type" => 'required',
        ]);

        if ($sale = Sale::where('email', $this->email)->where('contact', $this->contact)->where('program_id', $this->program_id)->first()) {
            $this->link = route('admin.sales.edit', $sale->id);
            return Session::flash('danger', $sale->name . ' already present in system, kindly check!!!');
        }

        $this->enrollment();
        $sale = new Sale();
        $sale->name = $this->name;
        $sale->email = $this->email;
        $sale->contact = $this->contact;
        $sale->counsellor = $this->counsellor;
        $sale->program_id = $this->program_id;
        $sale->batch_id = $this->batch_id;
        $sale->amount = $this->amount;
        $sale->final_amount = $this->final_amount;
        $sale->payment_term = $this->payment_term;
        $sale->address = $this->address;
        $sale->state = $this->state;
        $sale->status = $this->status;
        $sale->payment_type = $this->payment_type;
        $sale->location = $this->location;
        $sale->enrollment_no = $this->enrollment_no;
        $sale->user_id = Auth::user()->id;
        $sale->payments = json_encode($this->payments);
        $sale->complementary_id = !empty($this->complementary_id) ? $this->complementary_id : 0;

        $discount = $this->discount / $this->amount;
        $discount = $discount * 100;

        $sale->short_link = Str::uuid();

        $sale->payments = json_encode($this->payments);
        $sale->discount = $discount;
        $sale->otd = $this->otd;

        if ($this->type === 'old') {
            $sale->created_at = $this->created_at;
            $sale->type = 0;
        } else {
            $this->type = 1;
        }




        if ($sale->save()) {
            $this->sale_id = $sale->id;
            $this->create_payment();
            return redirect()->route('admin.sales')->with('success', $this->name . ' Added into sales !!');
        }
    }

    public function create_payment()
    {
        if (!empty($this->sale_id) && !empty($this->direct_id)) {
            $direct_payment = DirectPayment::findOrFail($this->direct_id);
            Payment::create([
                "name" => $direct_payment->name,
                "email" => $direct_payment->email,
                "contact" => $direct_payment->contact,
                "address" => $direct_payment->address,
                "state" => $direct_payment->state,
                "sale_id" => $this->sale_id,
                "program_name" => Program::findorFail($this->program_id)->name,
                "batch_name" => Batch::findOrFail($this->batch_id)->name,
                "other" => $direct_payment->paid,
                "fees" => json_encode([]),
                "paid" => $direct_payment->paid,
                "txnid" => $direct_payment->txnid,
                "mode" => 'payu',
                "created_at" => $direct_payment->created_at,
            ]);

            $sale = Sale::find($this->sale_id);
            $sale->status = 1;
            $sale->update();
            $direct_payment->status = "verified";
            $direct_payment->save();
        }
    }
}
