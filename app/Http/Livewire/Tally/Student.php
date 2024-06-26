<?php

namespace App\Http\Livewire\Tally;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Student extends Component
{
    public $students = [];
    public $search;

    public function mount()
    {
        $this->students = DB::table('TallyStudentMaster')->get();
    }

    public function render()
    {
        return view('livewire.tally.student');
    }

    public function Search()
    {
        if (!empty($this->search)) {
            $this->students = DB::table('TallyStudentMaster')->where('StudentName', 'LIKE', '%' . $this->search . '%')->get();
        } else {
            $this->students = DB::table('TallyStudentMaster')->get();
        }
    }
}
