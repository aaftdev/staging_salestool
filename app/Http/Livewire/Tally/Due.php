<?php

namespace App\Http\Livewire\Tally;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Due extends Component
{
    public $dues = [];
    public $search;

    public function mount()
    {
        $this->dues = DB::table('TallyDues')->get();
    }

    public function render()
    {
        return view('livewire.tally.due');
    }

    public function Search()
    {
        if (!empty($this->search)) {
            $this->dues = DB::table('TallyDues')->where('ledgername', 'LIKE', '%' . $this->search . '%')->get();
        } else {
            $this->dues = DB::table('TallyDues')->get();
        }
    }
}
