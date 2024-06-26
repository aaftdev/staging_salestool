<?php

namespace App\Http\Livewire\Tally;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Receipt extends Component
{
    public $receipts = [];
    public $search;

    public function mount()
    {
        $this->receipts = DB::table('TallyReceipts')->get();
    }

    public function render()
    {
        return view('livewire.tally.receipt');
    }

    public function Search()
    {
        if (!empty($this->search)) {
            $this->receipts = DB::table('TallyReceipts')->where('ledgername', 'LIKE', '%' . $this->search . '%')->get();
        } else {
            $this->receipts = DB::table('TallyReceipts')->get();
        }
    }
}
