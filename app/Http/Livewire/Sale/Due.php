<?php

namespace App\Http\Livewire\Sale;

use App\Models\Batch;
use App\Models\Due as ModelsDue;
use App\Models\Program;
use App\Models\Receipt;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Due extends Component
{
    public $dues, $limit, $offset, $max, $totalCount, $name, $from, $to, $enrollment_no, $program;
    public $popup = false;
    public $batches = [];
    public $course, $batch, $last_date;
    public $term = '';

    public function mount()
    {
        $this->limit = 10;
        $this->offset = 0;
        $this->getDues();
    }

    public function render()
    {
        return view('livewire.sale.due', ['courses' => Program::all()]);
    }

    public function getDues()
    {
        $name = $this->name;
        $from = $this->from;
        $to = $this->to;
        $enrollment_no = $this->enrollment_no;
        $program = $this->program;
        $tmpDue = ModelsDue::latest()->where(function ($query) use ($name, $from, $to, $enrollment_no, $program) {
            if (!empty($name)) {
                $query->where('LedgerName', 'LIKE', '%' . $name . '%');
            }
            if (!empty($from)) {
                dd($from);
                $query->where('created_at', '>=', Carbon::parse($from)->format('Y-m-d'));
            }
            if (!empty($to)) {
                $query->where('created_at', '<=', Carbon::parse($to)->format('Y-m-d'));
            }
            if (!empty($enrollment_no)) {
                $query->where('EnrollmentNo', 'LIKE', '%' . $enrollment_no . '%');
            }
            if (!empty($program)) {
                $query->where('Course', 'LIKE', '%' . $program . '%');
            }
        });
        $this->max = count($tmpDue->get());
        $this->dues = $tmpDue->limit($this->limit)->skip($this->offset)->get();
        // $this->dues = $tmpDue->get();
    }
    public function next()
    {
        if ($this->max >= $this->offset) {
            $this->offset += $this->limit;
        }
        $this->getDues();
    }
    public function prev()
    {
        if ($this->offset > 0) {
            $this->offset -= $this->limit;
        }
        $this->getDues();
    }

    public function popup()
    {
        $this->popup = !$this->popup;
    }

    public function selectBatch()
    {
        if (!empty($this->course)) {
            $this->batches = Batch::where('program_id', $this->course)->get();
        }
    }

    public function dueGenerate()
    {
        $courses = [];
        foreach (Program::all() as $prog) {
            $courses[$prog->id] = $prog;
        }
        $batches = [];
        foreach (Batch::all() as $bat) {
            $batches[$bat->id] = $bat;
        }
        $batch = $this->batch;
        $course = $this->course;
        $due_count = 0;

        // if (empty(ModelsDue::first())) {
        //     $vchno = 503;
        // } else {
        //     $vchno = explode('/', ModelsDue::latest()->first()->VchNo)[2];
        // }

        if ($this->term === 'L') {
            foreach (Receipt::all() as $receipt) {
                if (empty(ModelsDue::where('EnrollmentNo', $receipt->EnrollmentNo)->first())) {
                    $enrollment_no = $receipt->EnrollmentNo;

                    $sale = Sale::where(function ($query) use ($enrollment_no, $course, $batch) {
                        $query->where('batch_id', $batch);
                        $query->where('program_id', $course);
                        $query->where('enrollment_no', $enrollment_no);
                    })->first();

                    if (!empty($sale)) {
                        if ($batches[$sale->batch_id]->commence_date <= Carbon::today()) {
                            $sale_ids[] = $sale->id;
                        }
                    }
                }
            }
        }

        if ($this->term === 'S') {
            foreach (Receipt::all() as $receipt) {
                if (empty(ModelsDue::where('EnrollmentNo', $receipt->EnrollmentNo)->first())) {
                    $enrollment_no = $receipt->EnrollmentNo;
                    $sale = Sale::where('enrollment_no', $enrollment_no)->first();
                    if (!empty($sale) && ($courses[$sale->program_id]->term === 'certification') && ($receipt->entry_type === 'N')) {
                        $sale_ids[] = $sale->id;
                    }
                }
            }
        }

        // dd($sale_ids);

        if (!empty($sale_ids)) {
            foreach (Sale::whereIn('id', array_unique($sale_ids))->get() as $sale) {
                $vchno = explode('/', ModelsDue::latest()->first()->VchNo)[2] + 1;
                $scholarship = $sale->amount - $sale->final_amount;
                $yr = Carbon::parse($sale->created_at)->format('y');
                $yr1 = $yr + 1;
                $session = $yr . "-" . $yr1;
                $due = new ModelsDue();
                $due->VchNo = "AAFTE/" . $session . "/" . $vchno;
                $due->VchDate = Carbon::now();
                $due->DueDate = $batches[$sale->batch_id]->commence_date;
                $due->LedgerName = $sale->enrollment_no . " - " . $sale->name;
                $due->EnrollmentNo = $sale->enrollment_no;
                $due->State = $sale->state;
                $due->Batch = $batches[$sale->batch_id]->name;
                $due->Course = $courses[$sale->program_id]->name;
                $due->FeeHead = "Course Fee";
                $due->Amount = $sale->final_amount;
                $due->MRP = $sale->amount;
                $due->Scholarship = $scholarship;
                $due->created_at = Carbon::now();
                $due->save();
                ++$due_count;
            }
        }
        $this->popup();
        $this->getDues();
        if ($due_count > 0) {
            return Session::flash('success', $due_count . ' Due Generated !!');
        } else {
            return Session::flash('success', 'There were no due to create !!');
        }
    }
}
