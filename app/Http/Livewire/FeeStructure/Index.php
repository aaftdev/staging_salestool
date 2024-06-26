<?php

namespace App\Http\Livewire\FeeStructure;

use App\Models\FeeStructure;
use App\Models\Program;
use Livewire\Component;

class Index extends Component
{
    public $fees = [];
    public $listeners = ['feeRefresh' => '$refresh'];
    public $search_program, $search_name;

    public function mount()
    {
        $this->fees = FeeStructure::latest()->get();
    }

    public function render()
    {
        return view('livewire.fee-structure.index');
    }

    public function edit($id)
    {
        $this->emit('feeEdit', $id);
    }

    public function delete($id)
    {
        $program = FeeStructure::find($id);

        if ($program->delete()) {
            $this->fees = FeeStructure::all();
            session()->flash('success', 'Fee Structure Deleted Successfully !!');
        }
    }

    public function getFee()
    {
        $fees = FeeStructure::latest();
        if (!empty($this->search_name)) {
            $fees->where('name', 'LIKE', '%' . $this->search_name . '%');
        }
        if (!empty($this->search_program)) {
            $programs = Program::where('name', 'LIKE', '%' . $this->search_program . '%')->pluck('id');
            $fees->whereIn('program_id', $programs);
        }
        $this->fees = $fees->get();
    }
}
