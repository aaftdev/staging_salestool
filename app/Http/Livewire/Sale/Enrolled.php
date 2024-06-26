<?php

namespace App\Http\Livewire\Sale;

use App\Exports\SalesExport;
use App\Mail\PaymentLinkMail;
use App\Models\Program;
use App\Models\Sale;
use App\Models\User;
use App\Notifications\PaymentLinkNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Enrolled extends Component
{
    public $sales, $email, $contact, $counsellor, $program, $from, $to, $shareLink, $sale, $name;
    public $limit, $offset, $max;

    public function mount()
    {
        $this->limit = 10;
        $this->offset = 0;
        $sale_ids = [];
        foreach (DB::table('payments')->select('sale_id')->distinct()->get() as $id) {
            $sale_ids[] = $id->sale_id;
        }
        $this->max = count(Sale::where('status', 1)->get());
        if (Auth::user()->user_type === 'admin') {
            $this->sales = Sale::latest()->limit($this->limit)->skip($this->offset)->where('status', 1)->get();
        } else {
            $this->sales = Sale::latest()->where('location', Auth::user()->location)->limit($this->limit)->skip($this->offset)->get();
        }
    }

    public function render()
    {
        $sale_ids = [];
        foreach (DB::table('payments')->select('sale_id')->distinct()->get() as $id) {
            $sale_ids[] = $id->sale_id;
        }
        $prog_ids = [];
        if (Auth::user()->user_type === 'admin') {
            $sales = Sale::latest()->where('status', 1)->get();
        } else {
            $sales = Sale::latest()->where('location', Auth::user()->location)->get();
        }
        foreach ($sales as $prog) {
            $prog_ids[] = $prog->program_id;
        }
        $programs = Program::whereIn('id', $prog_ids)->get();
        $counsellors = Sale::select('counsellor')->distinct()->get();
        return view('livewire.sale.enrolled', compact('programs', 'counsellors'));
    }

    public function getSale()
    {

        $sale_ids = [];
        foreach (DB::table('payments')->select('sale_id')->distinct()->get() as $id) {
            $sale_ids[] = $id->sale_id;
        }
        if (Auth::user()->user_type === 'admin') {
            $tmpSale = DB::table('sales');
        } else {
            $tmpSale = DB::table('sales')->where('location', Auth::user()->location);
        }

        if (!empty($this->email)) {
            $tmpSale->where('email', 'LIKE', '%' . $this->email . '%');
        }

        if (!empty($this->name)) {
            $tmpSale->where('name', 'LIKE', '%' . $this->name . '%');
        }

        if (!empty($this->program)) {
            $tmpSale->WhereIn('program_id', $this->program);
        }
        if (!empty($this->counsellor)) {
            $tmpSale->WhereIn('counsellor', $this->counsellor);
        }
        if (!empty($this->contact)) {
            $tmpSale->Where('contact', 'LIKE', '%' . $this->contact . '%');
        }
        if (!empty($this->from)) {
            $tmpSale->whereDate('created_at', '>=', Carbon::parse($this->from));
        }
        if (!empty($this->to)) {
            $tmpSale->whereDate('created_at', '<=', Carbon::parse($this->to));
        }
        $this->max = count($tmpSale->where('status', 1)->get());
        $this->sales = $tmpSale->where('status', 1)->limit($this->limit)->skip($this->offset)->get();
    }

    public function shareLinkFetch($link)
    {
        $tmpLink = route("user.payment", $link);
        $this->shareLink = $tmpLink;
    }

    public function linkSend($link)
    {
        $user = Sale::where('short_link', $link)->first();
        if ($user->id < 10) {
            $enroll_no = "00" . $user->id;
        } elseif ($user->id >= 10 && $user->id < 100) {
            $enroll_no = "0" . $user->id;
        } else {
            $enroll_no = $user->id;
        }
        $data = [
            "name" => $user->name,
            "link" => route("user.payment", $link),
            "enroll_no" => $enroll_no,
            "program_name" => Program::find($user->program_id)->name,
        ];
        // $user->notify(new PaymentLinkNotification($data));
        Mail::to($user->email)->send(new PaymentLinkMail($data));

        return Session::flash('sent_mail', 'Payment Link send to ' . $user->name . ' !!!');
    }

    public function saleDelete($id)
    {
        if (!empty($id)) {
            $this->sale = Sale::findorFail($id);
        } else {
            $this->sale = "";
        }
    }

    public function salePermanentDelete()
    {
        if (!empty($this->sale)) {
            $this->sale->delete();
            Session::flash('success', $this->sale->name . ' deleted permanently !!');
            $this->sale = "";

            $sale_ids = DB::table('payments')->select('sale_id')->distinct()->get();
            $this->sales = Sale::latest()->whereIn('sale_id', $sale_ids)->limit($this->limit)->skip($this->offset)->get();
        }
    }

    public function export()
    {
        return Excel::download(new SalesExport($this->program), 'sales.xlsx');
    }
    public function next()
    {
        if ($this->max > $this->offset) {
            $this->offset += $this->limit;
        }
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
