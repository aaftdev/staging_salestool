<?php

namespace App\Http\Livewire\Program;

use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\File;

class Index extends Component
{
    public $listeners = ['programRefresh' => '$refresh'];
    public $programs = [];
    public $search_name, $search_code, $search_term;
    public $limit = 10;
    public $offset = 0;
    public $max = 0;

    public function mount()
    {
        $this->programs = Program::latest()->limit($this->limit)->skip($this->offset)->get();
        $this->max = count(Program::all());
    }

    public function render()
    {
        return view('livewire.program.index');
    }

    public function getProgram()
    {
        $programs = Program::latest();
        if (!empty($this->search_name)) {
            $programs->where('name', 'LIKE', '%' . $this->search_name . '%');
        }
        if (!empty($this->search_code)) {
            $programs->where('code', 'LIKE', '%' . $this->search_code . '%');
        }
        if (!empty($this->search_term) && ($this->search_term !== 'all')) {
            $programs->where('term', $this->search_term);
        }
        $this->max = count($programs->get());
        $this->programs = $programs->limit($this->limit)->skip($this->offset)->get();
    }

    public function edit($id)
    {
        $this->emit('programEdit', $id);
    }

    public function delete($id)
    {
        $program = Program::find($id);

        if (!empty($program->documents)) {
            foreach (json_decode($program->documents) as $pdf) {
                if (File::exists(public_path('assets' . $pdf))) {
                    File::delete(public_path('assets' . $pdf));
                }
            }
        }

        if ($program->delete()) {
            $this->programs = Program::latest()->get();
            session()->flash('success', 'Program Deleted Successfully !!');
        }
    }

    public function next()
    {
        if ($this->max >= $this->offset) {
            $this->offset += $this->limit;
        }
        $this->getProgram();
    }
    public function prev()
    {
        if ($this->offset > 0) {
            $this->offset -= $this->limit;
        }
        $this->getProgram();
    }
}
