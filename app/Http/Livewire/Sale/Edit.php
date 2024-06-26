<?php

namespace App\Http\Livewire\Sale;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Complemantry;
use App\Models\FeeStructure;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public $search_email, $name, $email, $counsellor, $contact, $batch_id, $amount, $discount, $final_amount, $state, $address, $payment_type, $location, $user_id, $complementary_id, $otd, $type, $created_at;
    public $search_phone, $enrollment_no;
    public $payment_term;
    public $fees = [];
    public $batches = [];
    public $payments = [];
    public $program_id;
    public $sale;
    public $status = 0;
    public $states = ['Andhra Pradesh', 'Andaman and Nicobar Islands', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Dadar and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Lakshadweep', 'Puducherry', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];

    public function mount($sale)
    {
        $this->sale = $sale;
        $this->name = $sale->name;
        $this->email = $sale->email;
        $this->contact = $sale->contact;
        $this->counsellor = $sale->counsellor;
        $this->batch_id = $sale->batch_id;
        $this->amount = $sale->amount;
        $this->final_amount = $sale->final_amount;
        $this->state = $sale->state;
        $this->address = $sale->address;
        $this->program_id = $sale->program_id;
        $this->status = $sale->status;
        $this->payment_type = $sale->payment_type;
        $this->payments = json_decode($sale->payments);
        $this->payment_term = $sale->payment_term;
        $this->location = $sale->location;
        $this->complementary_id = $sale->complementary_id;
        $this->otd = $sale->otd;
        $this->enrollment_no = $sale->enrollment_no;

        $discount = $sale->discount * $sale->amount;
        $this->type = $sale->type ? "new" : 'old';
        $this->created_at = Carbon::parse($sale->created_at)->format('Y-m-d');
        $this->discount = round($discount / 100);

        $lastKey = count($this->payments) - 1;

        $fees = json_decode(FeeStructure::find($sale->payment_term)->fees);
        foreach ($fees as $key => $pay) {
            if ($key == $lastKey) {
                if (((int)$fees[$key]->amount - (int)$this->discount) < 0) {
                    (int)$this->payments[$key]->amount = 0;
                    if (count($this->payments) > 1) {
                        (int)$this->payments[$key - 1]->amount = (int)$this->payments[$key - 1]->amount + ((int)$fees[$key]->amount - (int)$this->discount);
                    }
                } else {
                    (int)$this->payments[$key]->amount = (int)$fees[$key]->amount - (int)$this->discount;
                    if (count($this->payments) > 1) {
                        (int)$this->payments[$key - 1]->amount = (int)$fees[$key - 1]->amount;
                    }
                }
            }
        }

        $this->get_payment_term();
        $this->payment_term = $sale->payment_term;
    }

    public function render()
    {
        $programs = Program::all();
        $complemantries = Complemantry::all();
        return view('livewire.sale.edit', compact('programs', 'complemantries'));
    }


    public function get_data_from_email()
    {
        $this->validate([
            "search_email" => 'required'
        ]);
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
            "amount" => 'required',
            "discount" => 'required',
            "final_amount" => 'required',
            "payment_term" => 'required',
            "location" => 'required',
            "type" => 'required',
        ]);

        $sale = $this->sale;
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
        $sale->payments = $this->payments;
        $sale->location = $this->location;
        $sale->user_id = Auth::user()->id;

        $discount = $this->discount / $this->amount;
        $discount = $discount * 100;
        $sale->complementary_id = !empty($this->complementary_id) ? $this->complementary_id : 0;
        $sale->otd = $this->otd;

        if ($this->type === 'old') {
            $sale->type = 0;
            $sale->created_at = $this->created_at;
        } else {
            $sale->type = 1;
        }

        $sale->discount = $discount;
        if (empty($sale->short_link)) {
            $sale->short_link = Str::uuid();
        }

        if ($sale->save()) {
            // $receipt = new Helper();
            // $receipt->StudentMaster($sale->enrollment_no);
            return redirect()->route('admin.sales')->with('success', $this->name . ' Updated into sales !!');
        }
    }
}
