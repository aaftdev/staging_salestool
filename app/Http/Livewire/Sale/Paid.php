<?php

namespace App\Http\Livewire\Sale;

use App\Models\Payment;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Paid extends Component
{
    public $paid, $limit, $offset;
    public $email, $contact, $txnid, $program, $from, $to, $programs, $max, $revenue;

    public function mount()
    {
        $this->limit = 10;
        $this->offset = 0;
        $this->programs = Program::latest()->get();
        $this->getSale();
    }
    public function render()
    {
        return view('livewire.sale.paid');
    }
    public function getSale()
    {
        $tmpSale = DB::table('sales');
        if (!empty($this->email)) {
            $tmpSale->where('email', 'LIKE', '%' . $this->email . '%');
        }
        if (!empty($this->contact)) {
            $tmpSale->where('contact', 'LIKE', '%' . $this->contact . '%');
        }
        $ids = $tmpSale->pluck('id');

        $tmpPaid = Payment::whereIn('sale_id', $ids);
        if (!empty($this->txnid)) {
            $tmpPaid = $tmpPaid->Where('txnid', 'LIKE', '%' . $this->txnid . '%');
        }

        if (!empty($this->from)) {
            $tmpPaid->WhereDate('reference_date', '>=', Carbon::parse($this->from));
        }
        if (!empty($this->to)) {
            $tmpPaid->WhereDate('reference_date', '<=', Carbon::parse($this->to));
        }

        $this->max = count($tmpPaid->get());
        $this->revenue = number_format($tmpPaid->sum('paid'));
        $this->paid = $tmpPaid->latest()->limit($this->limit)->skip($this->offset)->get();
        // $this->paid = $tmpPaid->groupBy('txnid')->select('txnid', DB::raw('count(*) as total'))->get();
    }

    public function next()
    {
        $this->offset += $this->limit;
        $this->getSale();
    }
    public function prev()
    {
        if ($this->offset > 0) {
            $this->offset -= $this->limit;
        }
        $this->getSale();
    }
}
