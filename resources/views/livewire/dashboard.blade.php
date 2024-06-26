<div class="mb-5 row">
    <div class="mb-3 col-sm-12 d-flex justify-content-end">
        <div class="form-group" style="max-width: 200px; margin-right: 10px">
            <label for="">Month</label>
            <select wire:model="month" id="" class="form-control" wire:change="getData()">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Year</label>
            <select wire:model="year" id="" class="form-control">
            @for ($i = 1995; $i <= \Carbon\Carbon::now()->format('Y'); $i++)
                <option value="{{ $i }}">{{ $i }}</option>    
            @endfor
            </select>
        </div>
    </div>
    @if (Auth::user()->user_type === 'admin' || Auth::user()->location === 'gurugram')
        <div class="col-sm-12">
        <h2>Gurugram</h2>
        
    </div>
    <div class="col-sm-12">
        <div class="row">
            {{-- <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Booked Amount</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($gurugram_booked_amount) }}</p>
                        @php
                            $avg_order_value = 0;
                            if(!empty($gurugram_booked_count)){
                                $avg_order_value = $gurugram_booked_amount / $gurugram_booked_count;
                            }
                        @endphp
                        <span class="d-block">Total Enrollment Count: {{ number_format($gurugram_booked_count) }}</span>
                        <span class="d-block">Average Order Value: {{ number_format($avg_order_value) }}</span>
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div> --}}
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Monthly Revenue</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($total_gurugram_payment) }}</p>
                        <span class="d-block">Total Transaction Count: {{ $total_gurugram_payment_count }}</span>
                        {{-- <span class="d-block">Average Order Value: {{ number_format($avg_order_value) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                {{-- <div class="dashboard-card-bottom">
                    <span class="badge rounded-pill bg-primary">
                        <i class='bx bx-chevron-up'></i>
                        <span>26%</span>
                    </span>
                    From Previous...
                </div> --}}
            </div>
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Fresh Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($gurugram_payment_realised) }}</p>
                        <span class="d-block">Total Transaction Count: {{ number_format($gurugram_payment_realised_count) }}</span>
                        {{-- <span class="d-block">Total Enrollment Count: {{ number_format($gurugram_payment_realised_count_enroll) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                @php
                    $guru_per = 0;
                    if($gurugram_booked_amount > 0){
                        $guru_per = (int)$gurugram_payment_realised / (int)$gurugram_booked_amount;
                        $guru_per = round($guru_per * 100, 2);
                    }
                @endphp
                <div class="dashboard-card-bottom">
                    Revenue Realised Percentage
                    <span class="badge rounded-pill bg-primary">
                        <span>{{ $guru_per }}%</span>
                    </span>
                </div>
            </div>
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Previous Month Payments</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($guru_prev_month_pay) }}</p>
                        <span class="d-block">Total Transaction Count: {{ $guru_prev_month_count }}</span>
                         {{-- <span class="d-block">Total Enrollment Count: {{ $guru_prev_month_count_enroll }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                {{-- <div class="dashboard-card-bottom">
                    <span class="badge rounded-pill bg-primary">
                        <i class='bx bx-chevron-up'></i>
                        <span>26%</span>
                    </span>
                    From Previous...
                </div> --}}
            </div>
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Booked Amount</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_gurugram_booked_amount) }}</p>
                        {{-- <span>Total Enrollment Count: {{ number_format($today_gurugram_booked_count) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
            <!-- <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Fresh Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_gurugram_payment) }}</p>
                        <span>Total Count: {{ number_format($today_gurugram_payment_count) }}</span>
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div> -->
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_gurugram_payment) }}</p>
                        <span>Total Count: {{ number_format($today_gurugram_payment_count) }}</span>
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Recurring Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_gurugram_rec_pay) }}</p>
                        <span class="d-block">Total Transaction Count: {{ number_format($today_gurugram_rec_pay_count) }}</span>
                        {{-- <span class="d-block">Total Enrollment Count: {{ number_format($today_gurugram_rec_pay_count_enroll) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (Auth::user()->user_type === 'admin' || Auth::user()->location === 'mumbai')
    <div class="mt-5 col-sm-12">
        <h2>Mumbai</h2>
    </div>
    <div class="col-sm-12">
        <div class="row">
            {{-- <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        @php
                            $avg_order_value = 0;
                            if(!empty($mumbai_booked_count)){
                                $avg_order_value = $mumbai_booked_amount / $mumbai_booked_count;
                            }
                        @endphp
                        <h4>Booked Amount</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($mumbai_booked_amount) }}</p>
                        <span class="d-block">Total Enrollment Count: {{ number_format($mumbai_booked_count) }}</span>
                        <span class="d-block">Average Order Value: {{ number_format($avg_order_value) }}</span>
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div> --}}
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Monthly Revenue</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($total_mumbai_payment) }}</p>
                        <span class="d-block">Total Transaction Count: {{ $total_mumbai_payment_count }}</span>
                        {{-- <span class="d-block">Average Order Value: {{ number_format($avg_order_value) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                {{-- <div class="dashboard-card-bottom">
                    <span class="badge rounded-pill bg-primary">
                        <i class='bx bx-chevron-up'></i>
                        <span>26%</span>
                    </span>
                    From Previous...
                </div> --}}
            </div>
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Fresh Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($mumbai_payment_realised) }}</p>
                        <span class="d-block">Total Transaction Count: {{ number_format($mumbai_payment_realised_count) }}</span>
                        {{-- <span class="d-block">Total Enrollment Count: {{ number_format($mumbai_payment_realised_count_enroll) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                @php
                    $mum_per = 0;
                    if($mumbai_booked_amount > 0){
                        $mum_per = (int)$mumbai_payment_realised / (int)$mumbai_booked_amount;
                        $mum_per = round($mum_per * 100, 2);
                    }
                @endphp
                <div class="dashboard-card-bottom">
                    Revenue Realised Percentage
                    <span class="badge rounded-pill bg-primary">
                        <span>{{ $mum_per }}%</span>
                    </span>
                </div>
            </div>
            <div class="rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Previous Month Payments</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($mum_prev_month_pay) }}</p>
                        <span class="d-block">Total Transaction Count: {{ number_format($mum_prev_month_count) }}</span>
                        {{-- <span class="d-block">Total Enrollment Count: {{ number_format($mum_prev_month_count_enroll) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
                {{-- <div class="dashboard-card-bottom">
                    <span class="badge rounded-pill bg-primary">
                        <i class='bx bx-chevron-up'></i>
                        <span>26%</span>
                    </span>
                    From Previous...
                </div> --}}
            </div>
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Booked Amount</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_mumbai_booked_amount) }}</p>
                        {{-- <span>Total Enrollment Count: {{ number_format($today_mumbai_booked_count) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_mumbai_payment) }}</p>
                        <span>Total Count: {{ number_format($today_mumbai_payment_count) }}</span>
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
            <div class="mt-2 rounded shadow col-sm-4 dashboard-card">
                <div class="dashboard-card-content">
                    <div class="dashboard-card-content-title">
                        <h4>Today's Recurring Payment</h4>
                        <p><i class="bx bx-rupee"></i> {{ number_format($today_mumbai_rec_pay) }}</p>
                        <span class="d-block">Total Transaction Count: {{ number_format($today_mumbai_rec_pay_count) }}</span>
                        {{-- <span class="d-block">Total Enrollment Count: {{ number_format($today_mumbai_rec_pay_count_enroll) }}</span> --}}
                    </div>
                    <i class='bx bxs-coin-stack'></i>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
