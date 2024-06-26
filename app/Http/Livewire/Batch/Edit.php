<?php

namespace App\Http\Livewire\Batch;

use App\Models\Batch;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public $listeners = ['batchEdit' => 'batchEdit'];
    public $name;
    public $program_id;
    public $batch_id, $commence_date;

    public function render()
    {
        $programs = Program::all();
        return view('livewire.batch.edit', compact('programs'));
    }

    public function batchEdit($id)
    {
        $batch = Batch::findOrFail($id);
        $this->name = $batch->name;
        $this->program_id = $batch->program_id;
        $this->batch_id = $id;
        $this->commence_date = Carbon::parse($batch->commence_date)->format('Y-m-d');
    }

    public function resetInput()
    {
        $this->name = $this->program_id = "";
    }

    public function update()
    {
        $this->validate([
            "name" => 'required|unique:batches,id,' . $this->program_id,
            "program_id" => 'required',
        ]);

        if (empty($this->commence_date)) {
            $code = Program::find($this->program_id)->code . "-SELF";
        } else {
            $code = Program::find($this->program_id)->code . "-" . Str::upper(Carbon::parse($this->commence_date)->format('M')) . "|" . Carbon::parse($this->commence_date)->format('Y');
        }

        $program = Batch::find($this->batch_id);
        $program->name = $this->name;
        $program->program_id = $this->program_id;
        $program->commence_date = $this->commence_date;
        $program->code = $code;
        // dd($program);

        if ($program->save()) {
            $this->resetInput();
            $this->emit('batchRefresh');
            session()->flash('success', 'Batch Updated Successfully !!');
        }
    }
}
