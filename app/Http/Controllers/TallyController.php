<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TallyController extends Controller
{
    public function tally_batches()
    {
        return view('tally.batches');
    }

    public function tally_courses()
    {
        return view('tally.courses');
    }

    public function tally_dues()
    {
        return view('tally.dues');
    }

    public function tally_receipts()
    {
        return view('tally.receipts');
    }

    public function tally_student()
    {
        return view('tally.student');
    }
}
