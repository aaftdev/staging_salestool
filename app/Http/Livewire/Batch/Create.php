<?php

namespace App\Http\Livewire\Batch;

use App\Models\Batch;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $program_id, $name, $commence_date;

    public function mount()
    {
        $this->programs = Program::all();
        $this->commence_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.batch.create');
    }

    public function resetInput()
    {
        $this->program_id = $this->name = "";
    }

    public function save()
    {
        $this->validate([
            "name" => 'required|unique:batches',
            "program_id" => 'required|numeric',
        ]);

        if (empty($this->commence_date)) {
            $code = Program::find($this->program_id)->code . "-SELF";
        } else {
            $code = Program::find($this->program_id)->code . "-" . Str::upper(Carbon::parse($this->commence_date)->format('M')) . "|" . Carbon::parse($this->commence_date)->format('Y');
        }
        Batch::create([
            "name" => $this->name,
            "program_id" => $this->program_id,
            "commence_date" => $this->commence_date,
            "code" => $code,
        ]);

        $this->resetInput();
        $this->emit('batchRefresh');
        session()->flash('success', 'Batch Saved Successfully !!');
    }
}
