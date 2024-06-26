<?php

namespace App\Http\Livewire\Program;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $code, $term;
    public $documents = [];
    public $i = 0;

    public function addRow($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->documents, $i);
    }

    public function removeRow($i)
    {
        unset($this->documents[$i]);
    }

    public function render()
    {
        return view('livewire.program.create');
    }

    public function resetInput()
    {
        $this->name = "";
        $this->code = "";
        $this->documents = [];
    }

    public function save()
    {
        $this->validate([
            "name" => 'required|unique:programs',
            "code" => 'required',
        ]);

        $program = new Program();
        $program->name = $this->name;
        $program->code = $this->code;
        $program->term = $this->term;
        $docs = [];
        if (!empty($this->documents)) {
            foreach ($this->documents as $doc) {
                $docs[$doc['name']] = $doc['pdf']->store('pdf');
            }
        }
        $program->documents = json_encode($docs);

        if ($program->save()) {
            $this->resetInput();
            $this->emit('programRefresh');
            session()->flash('success', 'Program Saved Successfully !!');
        }
    }
}
