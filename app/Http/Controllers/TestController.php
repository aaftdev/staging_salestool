<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Batch;
use App\Models\Enrolled;
use App\Models\PaymentProcess;
use App\Models\Receipt;
use App\Models\Due;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
    }
}
