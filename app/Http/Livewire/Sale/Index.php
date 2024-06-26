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
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithFileUploads;
    public $sales, $email, $contact, $counsellor, $program, $from, $to, $shareLink, $sale, $location, $name;
    public $limit, $offset, $max, $totalCount;
    public $status = 'all';

    public function mount()
    {
        $this->limit = 10;
        $this->offset = 0;
        $this->getOffer();
    }

    public function render()
    {
        $programs = Program::latest()->get();
        $counsellors = Sale::select('counsellor')->distinct()->get();
        return view('livewire.sale.index', compact('programs', 'counsellors'));
    }

    public function getOffer()
    {
        if (Auth::user()->user_type === 'admin') {
            $tmpSale = DB::table('sales');
        } else {
            $tmpSale = Sale::where('location', Auth::user()->location);
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
        if ($this->status !== 'all') {
            $tmpSale->where('status', $this->status);
        }
        if (!empty($this->location)) {
            $tmpSale->where('location', $this->location);
        }
        $this->max = count($tmpSale->get());

        $this->sales = $tmpSale->latest()->limit($this->limit)->skip($this->offset)->get();
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
            $this->sales = Sale::latest()->limit($this->limit)->skip($this->offset)->get();
        }
    }

    public function export()
    {
        return Excel::download(new SalesExport($this->program), 'sales.xlsx');
    }

    public function next()
    {
        if ($this->max >= $this->offset) {
            $this->offset += $this->limit;
        }
        $this->getOffer();
    }
    public function prev()
    {
        if ($this->offset > 0) {
            $this->offset -= $this->limit;
        }
        $this->getOffer();
    }
}
