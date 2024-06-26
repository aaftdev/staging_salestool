<?php

namespace App\Http\Livewire\Batch;

use App\Models\Batch;
use App\Models\Program;
use Livewire\Component;

class Index extends Component
{
    public $listeners = ['batchRefresh' => '$refresh'];
    public $batches = [];
    public $search_name, $search_code, $search_program;
    public $limit = 10;
    public $offset = 0;
    public $max = 0;

    public function mount()
    {
        $this->batches = Batch::latest()->limit($this->limit)->skip($this->offset)->get();
        $this->max = count(Batch::all());
    }

    public function render()
    {
        return view('livewire.batch.index');
    }

    public function edit($id)
    {
        $this->emit('batchEdit', $id);
    }

    public function delete($id)
    {
        $batch = Batch::find($id);

        if ($batch->delete()) {
            $this->batches = Batch::latest()->get();
            session()->flash('success', 'Batch Deleted Successfully !!');
        }
    }

    public function getBatch()
    {
        $batches = Batch::latest();
        if (!empty($this->search_name)) {
            $batches->where('name', 'LIKE', '%' . $this->search_name . '%');
        }
        if (!empty($this->search_program)) {
            $programs = Program::where('name', 'LIKE', '%' . $this->search_program . '%')->pluck('id');
            $batches->whereIn('program_id', $programs);
        }
        if (!empty($this->search_code)) {
            $batches->where('code', 'LIKE', '%' . $this->search_code . '%');
        }
        $this->max = count($batches->get());
        $this->batches = $batches->limit($this->limit)->skip($this->offset)->get();
    }

    public function next()
    {
        if ($this->max >= $this->offset) {
            $this->offset += $this->limit;
        }
        $this->getBatch();
    }
    public function prev()
    {
        if ($this->offset > 0) {
            $this->offset -= $this->limit;
        }
        $this->getBatch();
    }
}
