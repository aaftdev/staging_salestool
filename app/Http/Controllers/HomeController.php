<?php

namespace App\Http\Controllers;

use App\Exports\PaymentExport;
use App\Exports\SalesExport;
use App\Imports\SaleImport;
use App\Models\Batch;
use App\Models\DirectPayment;
use App\Models\Due;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\PaymentProcess;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\User;
use App\Notifications\PaymentLinkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('sales.index');
    }
    public function dashboard()
    {
        return view('dashboard');
    }

    public function users()
    {
        return view('users');
    }

    public function paid()
    {
        return view('sales.paid');
    }
    public function online_payment()
    {
        return view('sales.online-payment');
    }

    public function program()
    {
        return view('programs');
    }

    public function complemantries()
    {
        return view('complemantries');
    }

    public function batch()
    {
        return view('batch');
    }

    public function fee_structure()
    {
        return view('fee-structure');
    }

    // public function sales(){
    //     return view('sales.index');
    // }

    public function sales_add()
    {
        return view('sales.create');
    }

    public function sales_enrolled()
    {
        return view('sales.enrolled');
    }

    public function sales_edit($id)
    {
        $sale = Sale::find($id);
        return view('sales.edit', compact('sale'));
    }

    public function user_payment($id)
    {
        $sale = Sale::where('short_link', $id)->first();
        return view('payment', compact('sale'));
    }

    public function finance_payment($id)
    {
        $sale = Sale::find(decrypt($id));
        return view('finance', compact('sale'));
    }

    public function thank_you($id)
    {
        $payment = Payment::where('id', decrypt($id))->latest()->first();
        $lead = Sale::find($payment->sale_id);
        return view('thank-you', compact('payment', 'lead'));
    }

    public function direct_thank_you($id)
    {
        $payment = DirectPayment::where('id', decrypt($id))->latest()->first();
        return view('direct-thank-you', compact('payment'));
    }

    public function exportData()
    {
        return FacadesExcel::download(new SalesExport, 'sales.xlsx');
    }

    public function exportPayment()
    {
        return FacadesExcel::download(new PaymentExport, 'payment.xlsx');
    }

    public function profile()
    {
        return view('profile');
    }
    public function profile_update(Request $request)
    {
        $request->validate([
            "password" => "required|min:6|confirmed",
            "password_confirmation" => "required|min:6",
            "old_password" => "required|min:6",
        ]);
        if (Hash::check($request->old_password, Auth::user()->password)) {
            $usr = User::find(Auth::user()->id);
            $usr->password = bcrypt($request->password);
            if ($usr->save()) {
                return redirect()->back()->with('success', 'Your Password Updated Successfully !!');
            }
        } else {
            return redirect()->back()->with('danger', 'Kindly Provide the right old password!!');
        }
    }

    public function generate_offer()
    {
        return view('pdf.generate');
    }

    public function offer_letter($id)
    {
        $lead = Sale::findOrFail(decrypt($id));
        $course = $lead->program($lead->program_id)->name;
        $commence_date = $lead->batch($lead->batch_id)->commence_date;
        $fees = json_decode($lead->payments);
        dd($fees);
        $total_fee = $lead->fee_structure($lead->payment_term)->total_fee;
        $discount = $lead->discount;
        $scholarship = 0;
        if (!empty($discount)) {
            $scholarship = ($total_fee * $discount) / 100;
        }
        $admission_fee = 0;
        if (!empty($fees)) {
            foreach ($fees as $key => $pay) {
                if ($key == 0) {
                    $admission_fee = $pay->amount;
                }
            }
        }
        $admission_fee_word = $this->convertNumber($admission_fee);

        return view('pdf.offer-letter', compact('course', 'lead', 'commence_date', 'fees', 'total_fee', 'scholarship', 'admission_fee', 'admission_fee_word'));
    }
    public function pdf_export($id)
    {
        $lead = Sale::findOrFail(decrypt($id));
        $course = $lead->program($lead->program_id)->name;
        $commence_date = $lead->batch($lead->batch_id)->commence_date;
        $fees = json_decode($lead->payments);
        $total_fee = $lead->fee_structure($lead->payment_term)->total_fee;
        $discount = $lead->discount;
        $scholarship = 0;
        if (!empty($discount)) {
            $scholarship = ($total_fee * $discount) / 100;
        }
        $admission_fee = 0;

        if (!empty($fees)) {
            foreach ($fees as $key => $pay) {
                if ($key == 0) {
                    $admission_fee = $pay->amount;
                }
            }
        }

        if (!empty(Program::find($lead->complementary_id))) {
            $complementary = [
                "name" => $lead->complemantry($lead->complementary_id)->name,
                "amount" => $lead->complemantry($lead->complementary_id)->amount,
            ];
        } else {
            $complementary = [
                "name" => "",
                "amount" => "",
            ];
        }

        $admission_fee_word = $this->convertNumber($admission_fee);
        $pdf = PDF::loadView('pdf.offer-letter', compact('course', 'lead', 'commence_date', 'fees', 'total_fee', 'scholarship', 'admission_fee', 'admission_fee_word', 'complementary'));

        $lead->offer_status = 1;
        $lead->save();

        return $pdf->download('offer-letter.pdf');
    }

    public function datastudio()
    {
        return view('datastudio');
    }

    public function sale_import(Request $request)
    {
        // Sale::query()->truncate();
        $file = $request->file('file');
        FacadesExcel::import(new SaleImport, $file);
        return redirect()->back()->with("excel imported successfully");
    }

    public function pay_now()
    {
        return  view('pay-now');
    }

    public function create_receipt()
    {
        // $batch_names = Batch::where('commence_date', "2022-06-25")->pluck('name');
        // $sale_ids = [];
        // foreach (DB::table('payments')->select('sale_id')->distinct('sale_id')->whereIn('batch_name', $batch_names)->get() as $pay) {
        //     $sale_ids[] = $pay->sale_id;
        // }

        // $VchNo = 1000;
        // foreach (Sale::whereIn('id', $sale_ids)->get() as $sale) {
        //     $due = new Due();
        //     $due->VchNo = ++$VchNo;
        //     $due->VchDate = Carbon::parse('2022-06-25')->format('Y-m-d');
        //     $due->DueDate = Carbon::parse('2022-06-25')->format('Y-m-d');
        //     $due->LedgerName = $sale->name;
        //     $due->EnrollmentNo = $sale->enrollment_no;
        //     $due->State = $sale->state;
        //     $due->Batch = Batch::findOrFail($sale->batch_id)->name;
        //     $due->FeeHead = "Course Fee";
        //     $due->Amount = $sale->final_amount;
        //     $due->save();
        // }

        // return "done";
    }

    public function payment_sync(Request $request)
    {
        $lastPayment = PaymentProcess::where('txnid', $request->txnid)->first();
        $pay = new Payment();
        $pay->name = $lastPayment->name;
        $pay->email = $lastPayment->email;
        $pay->contact = $lastPayment->contact;
        $pay->address = $lastPayment->address;
        $pay->state = $lastPayment->state;
        $pay->sale_id = $lastPayment->sale_id;
        $pay->program_name = $lastPayment->program_name;
        $pay->batch_name = $lastPayment->batch_name;
        $pay->other = $lastPayment->other;
        $pay->fees = $lastPayment->fees;
        $pay->paid = $lastPayment->paid;
        $pay->txnid = $lastPayment->txnid;
        $pay->mode = 'payu';

        if ($pay->save()) {
            $sale = Sale::find($lastPayment->sale_id);
            $sale->status = 1;
            $sale->update();
            return redirect()->route('thank.you', encrypt($pay->id));
        }
    }

    public function payment_check(Request $request)
    {
        if (!empty($request->input('txnid'))) {
            $txnid = $request->input('txnid');
            if (!empty(Payment::where('txnid', $txnid)->first())) {
                return redirect()->back()->with('danger', 'On this txnid, payment already avaliable.');
            } else {
                if (empty(PaymentProcess::where('txnid', $txnid)->first())) {
                    return redirect()->back()->with('danger', 'There is no payment record for this Txnid.');
                } else {
                    $lastPayment = PaymentProcess::where('txnid', $txnid)->first();
                    $pay = Payment::create([
                        "name" => $lastPayment->name,
                        "email" => $lastPayment->email,
                        "contact" => $lastPayment->contact,
                        "address" => $lastPayment->address,
                        "state" => $lastPayment->state,
                        "sale_id" => $lastPayment->sale_id,
                        "program_name" => $lastPayment->program_name,
                        "batch_name" => $lastPayment->batch_name,
                        "other" => $lastPayment->other,
                        "fees" => $lastPayment->fees,
                        "paid" => $lastPayment->paid,
                        "txnid" => $lastPayment->txnid,
                        "mode" => 'payu',
                        "created_at" => $lastPayment->created_at,
                    ]);

                    $sale = Sale::find($lastPayment->sale_id);
                    $sale->status = 1;
                    $sale->update();
                    $lastPayment->delete();
                    return redirect()->route('thank.you', encrypt($pay->id));
                }
            }
        }
        return view('payment-check');
    }

    public function dues()
    {
        return view('dues');
    }

    public function tax_invoice()
    {
        return view('invoice');
    }

    public function custom_form()
    {
        return view('custom-form');
    }
}
