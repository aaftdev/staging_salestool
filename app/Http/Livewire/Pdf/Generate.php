<?php

namespace App\Http\Livewire\Pdf;

use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\Program;
use App\Models\Sale;
use App\Notifications\OfferLetter;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class Generate extends Component
{
    public $program, $batch;
    public $batches = [];
    public $leads = [];

    public function render()
    {
        $programs = Program::latest()->get();
        return view('livewire.pdf.generate', compact('programs'));
    }

    public function filterBatch()
    {
        $this->leads = Sale::where('program_id', $this->program)->get();
        $this->batches = Batch::where('program_id', $this->program)->get();
    }
    public function filterLead()
    {
        $this->leads = Sale::where('batch_id', $this->batch)->get();
    }

    public function sendNotification($id)
    {
        $lead = Sale::find($id);
        $fees = json_decode($lead->payments);
        $admission_fee = 0;
        foreach ($fees as $key => $fee) {
            if ($key == 0) {
                $admission_fee = $fee->amount;
            }
        }
        $attachments = [];
        foreach (json_decode($lead->program($lead->program_id)->documents) as $docname => $docfile) {
            $attachments[public_path('assets/' . $docfile)] = [
                'as' => $docname . '.pdf',
                'mime' => 'application/pdf',
            ];
        }
        $course = $lead->program($lead->program_id)->name;
        $commence_date = $lead->batch($lead->batch_id)->commence_date;
        $total_fee = $lead->amount;
        $discount = $lead->discount;
        $scholarship = 0;
        if (!empty($discount)) {
            $scholarship = ($total_fee * $discount) / 100;
        }
        $admission_fee = 0;
        if (!empty($lead->payments)) {
            foreach (json_decode($lead->payments) as $key => $pay) {
                if ($key == 0) {
                    $admission_fee = $pay->amount;
                    $admission_fee_date = Carbon::parse($pay->date)->format('M d, Y');
                }
            }
        }

        $admission_fee_word = $this->convertNumber($admission_fee);

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
        $pdf = PDF::loadView('pdf.offer-letter', compact('course', 'lead', 'commence_date', 'fees', 'total_fee', 'scholarship', 'admission_fee', 'admission_fee_word', 'complementary'));
        Storage::put('pdf/offer-letter.pdf', $pdf->output());

        $attachments[public_path('assets/pdf/offer-letter.pdf')] = [
            'as' => 'offer-letter.pdf',
            'mime' => 'application/pdf',
        ];


        $data = [
            "short_link" => $lead->short_link,
            "name" => $lead->name,
            "course" => $course,
            "commence_date" => $commence_date,
            "admission_fee" => $admission_fee,
            "admission_fee_date" => $admission_fee_date,
            "attachments" => $attachments,
            "scholorship" => $scholarship,
        ];
        $lead->notify(new OfferLetter($data));
        $lead->mail_status = 1;
        $lead->save();
        return session()->flash('send_notification', "Offer Letter Mail sent to " . $lead->name . " successfully !!");
    }


    public function convertNumber($num = false)
    {
        $num = str_replace(array(',', ''), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ($hundreds == 1 ? '' : '') . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '');
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $words = implode(' ',  $words);
        $words = preg_replace('/^\s\b(and)/', '', $words);
        $words = trim($words);
        $words = ucfirst($words);
        // $words = $words . ".";
        return $words;
    }
}
