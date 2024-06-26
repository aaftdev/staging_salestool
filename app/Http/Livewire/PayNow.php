<?php

namespace App\Http\Livewire;

use App\Models\Batch;
use App\Models\DirectPayment;
use App\Models\FeeStructure;
use App\Models\Program;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PayNow extends Component
{
    public $search_contact, $search_email;
    public $search_term = 'diploma';
    public $sales = [];
    public $batches = [];
    public $programs = [];
    public $search_form = false;
    public $pay_form = false;
    public $name, $email, $contact, $counsellor, $state, $address, $program_id, $batch_id, $batch_commence, $other;
    public $states = ['Andhra Pradesh', 'Andaman and Nicobar Islands', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Dadar and Nagar Haveli', 'Daman and Diu', 'Delhi', 'Lakshadweep', 'Puducherry', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];

    public function render()
    {
        // $programs = Program::latest()->get();
        return view('livewire.pay-now');
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
        if (!empty($resultdata)) {
            $this->pay_form = !$this->pay_form;
            $this->name = $resultdata[0]->FirstName . " " . $resultdata[0]->LastName;
            $this->email = $resultdata[0]->EmailAddress;
            $this->contact = explode('-', $resultdata[0]->Phone)[1];
            $this->counsellor = $resultdata[0]->OwnerIdName;
            $this->state = $resultdata[0]->mx_State;
            $this->address = $resultdata[0]->mx_Street1;
        } else {
            $this->search_form = !$this->search_form;
            Session::flash('danger', 'No Record Found !!');
        }
    }

    public function get_data_from_phone()
    {
        $this->validate([
            "search_contact" => 'required|numeric'
        ]);
        $result = "";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-in21.leadsquared.com/v2/LeadManagement.svc/RetrieveLeadByPhoneNumber?accessKey=u$r88f4d6ee8eeb50d10a51a061a15a7669&secretKey=fc71aae79594fdbd585e568f534ce47b1b84e4cc&phone=' . $this->search_contact,
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
            $this->pay_form = !$this->pay_form;
            $this->name = $resultdata[0]->FirstName . " " . $resultdata[0]->LastName;
            $this->email = $resultdata[0]->EmailAddress;
            $this->contact = explode('-', $resultdata[0]->Phone)[1];
            $this->counsellor = $resultdata[0]->OwnerIdName;
            $this->state = $resultdata[0]->mx_State;
            $this->address = $resultdata[0]->mx_Street1;
        } else {
            $this->search_form = !$this->search_form;
            Session::flash('danger', 'No Record Found !!');
        }
    }

    public function search()
    {
        // dd($this->search_term);
        $this->programs = Program::where('term', $this->search_term)->get();
        $this->search_form = !$this->search_form;
        if (!empty($this->search_email)) {
            // $this->get_data_from_email();
            $sale = Sale::where('email', $this->search_email)->get();
            if (count($sale) === 0) {
                $this->get_data_from_email();
            } else {
                $this->sales = $sale;
            }
        }
        if (!empty($this->search_contact)) {
            // $this->get_data_from_phone();
            $sale = Sale::where('contact', $this->search_contact)->get();
            if (count($sale) === 0) {
                $this->get_data_from_phone();
            } else {
                $this->sales = $sale;
            }
        }
    }

    public function back()
    {
        $this->search_form = !$this->search_form;
    }

    public function selectBatch()
    {
        $this->batches = Batch::where('program_id', $this->program_id)->get();
    }
    public function BatchCommence()
    {
        $this->batch_commence = Carbon::parse(Batch::find($this->batch_id)->commence_date)->format('d/m/y');
    }

    public function save()
    {
        $this->validate([
            "name" => 'required',
            "email" => 'required',
            "contact" => 'required',
            "program_id" => 'required',
            "address" => 'required',
            "state" => 'required',
            "other" => 'required',
        ]);

        $payment_process = DirectPayment::create([
            "name" => $this->name,
            "email" => $this->email,
            "contact" => $this->contact,
            "address" => $this->address,
            "state" => $this->state,
            "course" => $this->program_id,
            "batch" => $this->batch_id,
            "amount" => FeeStructure::where('program_id', $this->program_id)->first()->total_fee,
            "paid" => $this->other,
            "txnid" => "AAFTE-" . substr(hash('sha256', mt_rand() . microtime()), 0, 20),
        ]);

        return redirect()->to('/payu-money-payment?direct=1&wrap=' . encrypt($payment_process->id));
    }
}
