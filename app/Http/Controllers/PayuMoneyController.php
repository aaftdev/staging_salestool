<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\DirectPayment;
use App\Models\Payment;
use App\Models\PaymentProcess;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class PayuMoneyController
 */
class PayuMoneyController extends \InfyOm\Payu\PayuMoneyController
{
    public function paymentCancel(Request $request)
    {
        $data = $request->all();
        echo "<pre>";
        print_r($data);
        die;
        // your code here
    }

    public function paymentSuccess(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $validHash = $this->checkHasValidHas($data);

        if (!$validHash) {
            echo "Invalid Transaction. Please try again";
        } else {
            if (strpos($request->input('txnid'), 'FTE')) {
                $lastPayment = DirectPayment::where('txnid', $request->input('txnid'))->first();
                $lastPayment->status = 'un-verified';
                $lastPayment->save();
                return redirect()->route('direct.thank.you', encrypt($lastPayment->id));
            } else {
                $lastPayment = PaymentProcess::where('txnid', $request->input('txnid'))->first();
                if (!Payment::where('txnid', $lastPayment->txnid)->first()) {
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
                        "reference_date" => $lastPayment->created_at,
                        "created_at" => Carbon::now(),
                        "mode" => 'payu',
                    ]);

                    $sale = Sale::find($lastPayment->sale_id);
                    if ($sale->status === 0) {
                        $sale->enrollment_date = $lastPayment->reference_date;
                    }
                    $sale->status = 1;
                    // $date = explode('-', Carbon::parse($sale->enrollment_date)->format('Y-m-d'));
                    $sale->update();
                    $lastPayment->delete();
                    $receipt = new Helper();
                    $receipt->EntryType($sale->id);
                    $receipt->generateReceipt($pay->id, 'ICICI');
                    $receipt->StudentMaster($sale->enrollment_no);
                    return redirect()->route('thank.you', encrypt($pay->id));
                }
            }
        }
    }
}
