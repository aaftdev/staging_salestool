<?php

namespace App\Http\Livewire\Tally;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Course extends Component
{
    public $courses = [];
    public $search;

    public function mount()
    {
        $this->courses = DB::table('TallyCourse')->get();
    }

    public function render()
    {
        return view('livewire.tally.course');
    }

    public function Search()
    {
        if (!empty($this->search)) {
            $this->courses = DB::table('TallyCourse')->where('Course', 'LIKE', '%' . $this->search . '%')->get();
        } else {
            $this->courses = DB::table('TallyCourse')->get();
        }
    }
}
