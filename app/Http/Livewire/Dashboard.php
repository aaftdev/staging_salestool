<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Sale\Finance;
use App\Http\Livewire\Sale\Payment;
use App\Models\Payment as ModelsPayment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Collection as collect;

class Dashboard extends Component
{
    public $month, $gurugram_booked_amount, $mumbai_booked_amount, $gurugram_booked_count, $mumbai_booked_count, $gurugram_payment_realised, $mumbai_payment_realised, $gurugram_payment_realised_count, $mumbai_payment_realised_count, $total_offer_amount, $year, $today_gurugram_booked_amount, $today_gurugram_booked_count, $today_mumbai_booked_amount, $today_mumbai_booked_count, $gurugram_payment_realised_count_enroll, $guru_prev_month_count_enroll;
    public $guru_prev_month_pay, $guru_prev_month_count, $mum_prev_month_pay, $mum_prev_month_count, $today_gurugram_payment, $today_gurugram_payment_count, $today_mumbai_payment, $today_mumbai_payment_count;
    public $today_gurugram_rec_pay, $today_gurugram_rec_pay_count, $today_gurugram_rec_pay_count_enroll, $mumbai_payment_realised_count_enroll, $mum_prev_month_count_enroll, $today_mumbai_rec_pay, $today_mumbai_rec_pay_count, $today_mumbai_rec_pay_count_enroll;

    public $total_gurugram_payment, $total_gurugram_payment_count, $total_mumbai_payment, $total_mumbai_payment_count;
    public function mount()
    {
        $this->year = Carbon::now()
            ->format('Y');
        $this->month = Carbon::now()
            ->format('m');
        $this->getData();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function getData()
    {
        $month = $this->month;
        $year = $this->year;

        // Total Gurugram Transaction Amount This Month
        $this->total_gurugram_payment = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'gurugram')->sum('payments.paid');

        // Total Gurugram Transaction Count This Month
        $this->total_gurugram_payment_count = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'gurugram')->count();

        // Fresh Gurugram Transaction Amount This Month
        $this->gurugram_payment_realised = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->whereMonth('sales.enrollment_date', $month)
            ->whereYear('sales.enrollment_date', $year)
            ->where('sales.location', 'gurugram')->sum('paid');

        // Fresh Gurugram Transaction Count This Month
        $this->gurugram_payment_realised_count = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->whereMonth('sales.enrollment_date', $month)
            ->whereYear('sales.enrollment_date', $year)
            ->where('sales.location', 'gurugram')->count();

        // Prev Gurugram Transaction Amount This Month
        $this->guru_prev_month_pay = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
                ->whereMonth('payments.reference_date', $month)
                ->whereYear('payments.reference_date', $year)
                ->whereMonth('sales.enrollment_date', $month)
                ->whereYear('sales.enrollment_date', $year)
                ->where('sales.location', 'gurugram')->pluck('sales.id'))
            ->where('sales.location', 'gurugram')->sum('paid');

        // Prev Gurugram Transaction Count This Month
        $this->guru_prev_month_count = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
                ->whereMonth('payments.reference_date', $month)
                ->whereYear('payments.reference_date', $year)
                ->whereMonth('sales.enrollment_date', $month)
                ->whereYear('sales.enrollment_date', $year)
                ->where('sales.location', 'gurugram')->pluck('sales.id'))
            ->where('sales.location', 'gurugram')->count();


        //Fresh Gurugram Transaction Amount Today
        $this->today_gurugram_payment = DB::table('payments')
            ->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
           // ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'gurugram')
            ->sum('payments.paid');

        //Fresh Gurugram Transaction Count Today
        $this->today_gurugram_payment_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
          //  ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'gurugram')
            ->count();

        //Prev Enrolled Gurugram Transaction Amount Today
        $this->today_gurugram_rec_pay = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
                ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
                ->where('sales.location', 'gurugram')->pluck('sales.id'))
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'gurugram')->sum('payments.paid');

        //Prev Enrolled Gurugram Transaction Count Today
        $this->today_gurugram_rec_pay_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
                ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
                ->where('sales.location', 'gurugram')->pluck('sales.id'))
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'gurugram')->count();

        // Today's Gurugram Fresh Booked Amount
        $this->today_gurugram_booked_amount = DB::table('sales')
          //  ->leftJoin('payments', 'payments.sale_id', '=', 'sales.id')
          //  ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
         //   ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
             ->whereDate('sales.created_at', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'gurugram')
            ->where('sales.status', '1')
            ->sum('sales.final_amount');


        // Total Mumbai Transaction Amount This Month
        $this->total_mumbai_payment = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')
            ->sum('paid');

        // Total Mumbai Transaction Count This Month
        $this->total_mumbai_payment_count = DB::table('payments')->leftJoin('sales', 'sales.id', '=', 'payments.sale_id')
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')
            ->count();

        // Fresh Mumbai Transaction Amount This Month
        $this->mumbai_payment_realised = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereMonth('sales.enrollment_date', $month)
            ->whereYear('sales.enrollment_date', $year)
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')->sum('payments.paid');


        // Fresh Mumbai Transaction Count This Month
        $this->mumbai_payment_realised_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereMonth('sales.enrollment_date', $month)
            ->whereYear('sales.enrollment_date', $year)
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')->count();

        // Prev Mumbai Transaction Amount This Month
        $this->mum_prev_month_pay = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereMonth('sales.enrollment_date', $month)
                ->whereYear('sales.enrollment_date', $year)
                ->whereMonth('payments.reference_date', $month)
                ->whereYear('payments.reference_date', $year)
                ->where('sales.location', 'mumbai')->pluck('sales.id'))
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')->sum('payments.paid');

        // Prev Mumbai Transaction Count This Month
        $this->mum_prev_month_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereMonth('sales.enrollment_date', $month)
                ->whereYear('sales.enrollment_date', $year)
                ->whereMonth('payments.reference_date', $month)
                ->whereYear('payments.reference_date', $year)
                ->where('sales.location', 'mumbai')->pluck('sales.id'))
            ->whereMonth('payments.reference_date', $month)
            ->whereYear('payments.reference_date', $year)
            ->where('sales.location', 'mumbai')->count();

        // Today's Mumbai Fresh Transaction Amount
        $this->today_mumbai_payment = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
           // ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'mumbai')
            ->sum('payments.paid');

        // Today's Mumbai Fresh Transaction Count
        $this->today_mumbai_payment_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
         //   ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'mumbai')
            ->count();

        // Today's Mumbai Transaction Amount for pre enrolled offers
        $this->today_mumbai_rec_pay = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
                ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
                ->where('sales.location', 'mumbai')->pluck('sales.id'))
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'mumbai')->sum('payments.paid');

        // Today's Mumbai Transaction Count for pre enrolled offers
        $this->today_mumbai_rec_pay_count = DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
            ->whereNotIn('payments.sale_id', DB::table('payments')->leftJoin('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
                ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
                ->where('sales.location', 'mumbai')->pluck('sales.id'))
            ->whereDate('payments.reference_date', Carbon::today()->format('Y-m-d'))
            ->where('sales.location', 'mumbai')->count();

        $this->today_mumbai_booked_amount = DB::table('sales')
          //  ->leftJoin('payments', 'payments.sale_id', '=', 'sales.id')
          //  ->whereDate('sales.enrollment_date', Carbon::today()->format('Y-m-d'))
            ->whereDate('sales.created_at', Carbon::today()->format('Y-m-d'))
            ->where('sales.status', '1')
            ->where('sales.location', 'mumbai')
            ->sum('sales.final_amount');
    }
}
