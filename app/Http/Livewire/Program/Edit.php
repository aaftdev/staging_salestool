<?php

namespace App\Http\Livewire\Program;

use App\Models\Program;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $listeners = ['programEdit' => 'programEdit'];
    public $name;
    public $code, $term;
    public $prog_id;
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
        return view('livewire.program.edit');
    }

    public function programEdit($id)
    {
        $this->documents = [];
        $program = Program::findOrFail($id);
        $this->name = $program->name;
        $this->code = $program->code;
        $this->term = $program->term;
        $this->prog_id = $id;
        if (!empty($program->documents)) {
            foreach (json_decode($program->documents) as $doc_name => $doc_pdf) {
                array_push($this->documents, [
                    "name" => $doc_name,
                    "pdf" => $doc_pdf,
                ]);
            }
        }
        $this->i = count($this->documents);
    }

    public function resetInput()
    {
        $this->name = "";
        $this->code = "";
        $this->documents = [];
    }

    public function update()
    {
        $this->validate([
            "name" => 'required|unique:programs,id,' . $this->prog_id,
            "code" => 'required'
        ]);

        $program = Program::find($this->prog_id);
        $program->name = $this->name;
        $program->code = $this->code;
        $program->term = $this->term;

        $docs = [];
        if (!empty($this->documents)) {
            foreach ($this->documents as $doc) {
                if (is_string($doc['pdf'])) {
                    $docs[$doc['name']] = $doc['pdf'];
                } else {
                    $docs[$doc['name']] = $doc['pdf']->store('pdf');
                }
            }
        }
        $program->documents = json_encode($docs);

        if ($program->save()) {
            $this->resetInput();
            $this->emit('programRefresh');
            session()->flash('success', 'Program Updated Successfully !!');
        }
    }
}
