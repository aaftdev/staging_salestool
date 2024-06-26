<?php

namespace App\Http\Livewire\Tally;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Batch extends Component
{
    public $batches = [];
    public $search;

    public function mount()
    {
        $this->batches = DB::table('TallyBatch')->get();
    }

    public function render()
    {
        return view('livewire.tally.batch');
    }

    public function Search()
    {
        if (!empty($this->search)) {
            $this->batches = DB::table('TallyBatch')->where('Batch', 'LIKE', '%' . $this->search . '%')->get();
        } else {
            $this->batches = DB::table('TallyBatch')->get();
        }
    }
}
