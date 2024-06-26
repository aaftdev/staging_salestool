@extends('layouts.template')

@section('styles')
<style>
    body {
        /* font-weight: 500; */
        font-size: 15px;
        color: #333 !important;
    }

    p span {
        display: block;
    }

    * {
        letter-spacing: 1px;
    }

    img {
        height: 50px;
        object-fit: contain;
    }

    .container {
        padding: 0px 2.5rem;
    }

    .page-break {
        page-break-after: always;
    }

    .text-center {
        text-align: center;
    }

    .text-end {
        text-align: right;
    }

    .my-3 {
        margin-block: 1rem;
    }

    .ml-2 {
        margin-left: 1rem;
    }

    .text-danger {
        color: red;
    }

    .table {
        border: 1px solid #eaeaea;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead tr {
        background-color: #0275d8;
        color: #fff;
    }

    .table thead tr th {
        padding: 1rem 0px;
    }

    .table tbody tr td {
        text-align: center;
        padding: 0.5rem 0px;
        border: 1px solid #eaeaea;
    }

    ul {
        list-style: upper-alpha;
    }
</style>
@endsection

@section('content')
@php
$admission_date = \Carbon\Carbon::now()->format('d-M-Y');
foreach(json_decode($lead->payments) as $key => $fee){
if($key === 0){
$admission_date = \Carbon\Carbon::parse($fee->date)->format('d-M-Y');
}
}
@endphp
<div class="container">
    <div class="text-center my-3">
        <img src="{{ asset('logo-black.png') }}" alt="" class="img-fluid">
    </div>
    <div class="text-end">
        Date: {{ \Carbon\Carbon::now()->format('d-M-Y') }}
    </div>
    <div class="body-content">
        <p>Dear {{$lead->name}},</p>
        <p>Sub: Admission Offer to the {{ $course }}, <b>{{ \Carbon\Carbon::parse($commence_date)->format("M'Y") }}</b> at AAFT.</p>
        <p>Congratulations! Further to your application and based on your performance in the interview with us, you have been provisionally selected for admission to the {{ $course }}, <b>{{ \Carbon\Carbon::parse($commence_date)->format("M'Y") }}</b> batch. We congratulate you on your selection and invite you to chart your career leveraging the fantastic platform offered by AAFT, one of India's most prestigious creative arts institutions with India’s first Creative Arts University.</p>
        <p>Considering your background, interest and career ambition, we are certain that AAFT is the right place for you to pursue this program and become a confident professional. We believe you will benefit greatly from the unique academic experience and industry exposure that AAFT has to offer and take the next best step towards a rewarding career. </p>
        <p>Please be informed that you would be required to pay the admission fee of INR {{ $admission_fee }}/- ({{ $admission_fee_word }} only) by <b>{{ $admission_date }}</b>. The payment can be made through Credit Card/ Debit Card, Online Banking or Demand Draft/ Cheque in favor of "AAFT ELearn Pvt. Ltd'' payable at Noida for which the details are given in the subsequent pages.</p>
        <p>We congratulate you on your selection and welcome you to AAFT.</p>
        @if(!empty($complementary['name']))
        <p><b>Please Note</b> - Upon adhering to the admission fee deadline you would be given access to a complementary program i.e. {{ $complementary['name'] }} (worth Rs {{ $complementary['amount'] }}) to kickstart your learning immediately.</p>
        @endif
        <p>
            <span>Best Regards,</span>
            <span>Albeena Abbas</span>
            <span>Director Academics</span>
            <span>AAFT</span>
        </p>

        <!-- page break -->
        <div class="page-break"></div>
        <!-- page break -->

        <div class="text-center my-3">
            <img src="{{ asset('logo-black.png') }}" alt="" class="img-fluid">
        </div>
        <div class="text-center">Annexure 1</div>
        <p>Admission Terms and Conditions</p>
        <ul>
            <li>Provisional Offer:</li>
            <p>This is a provisional offer of admission and shall be confirmed subject to receipt of fee within the stipulated timeline.</p>
            <li>Commencement Date:</li>
            <p>The {{ $course }} will commence in the month of <b>{{ \Carbon\Carbon::parse($commence_date)->format('M Y') }}</b>. The exact schedule of the orientation program and course commencement details will be shared with all admitted candidates soon. If there will be a need for a change in schedule, the same shall be communicated to all candidates through email and call.</p>
            <li>Fee Schedule:</li>
            <p>The fee structure and payment schedule are provided in Annexure 2. Please note that a failure to comply with the mentioned fee schedule will lead to disqualification from the program.</p>
            <p class="text-danger">*Please note, admission fees once paid would be non-refundable.</p>
            <li>Program Completion Guidelines:</li>
            <p>The {{ $course }} follows a continuous evaluation methodology comprising assessment components in learning, assignments and multiple projects. Candidates need to clear all evaluations to successfully graduate from the program. </p>
            <li>Laptop:</li>
            <p>Possession of a laptop is mandatory for the participants to use the tools and virtual lab.</p>
            <li>Address:</li>
            <p>All correspondence by post/courier including the Cheque / DD should be mailed to our Noida city office at the following address only:</p>
            <div class="ml-2">
                <p>Admissions Office</p>
                <p>AAFT Online (AAFT ELearn Pvt. Ltd.),</p>
                <p>B-122, Sector-5, Noida, </p>
                <p>Gautam Buddha Nagar,</p>
                <p>Uttar Pradesh - 201301</p>
                <p>GST No. : 09AATCA5531G1ZF</p>
            </div>

            <!-- page break -->
            <!-- <div class="page-break"></div> -->
            <!-- page break -->
            <div class="text-center my-3">
                <img src="{{ asset('logo-black.png') }}" alt="" class="img-fluid">
            </div>
            <p class="text-danger">*Please mention your Name and Number clearly on the envelope and the same on the reverse side of the Cheque/DD.</p>

            <li>Admissions Helpline:</li>
            <p>For any admission related query, candidates can write to us at <a href="mailto:admissions_support@aaftonline.com">admissions_support@aaftonline.com</a> or call us at <a href="tel:+91-98109 88976">+91-98109 88976</a></p>
            <p class="text-danger">Please note that all rights for the admission in the program are reserved with the Program Director's Office. Any decision regarding the same taken by the program director’s office will be final and binding. The admission offered to you is subject to the availability of seats in the program.</p>
            <li>Deferment Policy:</li>
            <ul>
                <li>Deferment before batch commencement:</li>
                    <p>In case you wish to change your batch before batch commencement, you may opt for the next immediate batch. In case you wish to opt for a batch beyond the next immediate batch, a deferment fee of INR 5,000 is applicable upon acceptance of your deferment request.</p>

                <li>Deferment after batch commencement :</li>
                     <p>In case you wish to change your batch after batch commencement, a deferment fee of INR 5,000 is applicable upon acceptance of your deferment request.</p>
                </ul>
                 <li>Financial Assistance:</li>
                    <p>AAFT’s {{ $course }} is supported by loan providers such as Eduvanz, Early Salary, etc. In case you want to avail financial assistance, the admissions team would be happy to connect you.</p>
        </ul>

        <!-- page break -->
        <div class="page-break"></div>
        <!-- page break -->

        <div class="text-center my-3">
            <img src="{{ asset('logo-black.png') }}" alt="" class="img-fluid">
        </div>

        <p>Program Fee Schedule</p>
        <p>The program fee for candidates pursuing the {{ $course }} at AAFT is INR {{ round($total_fee) }} (All Inclusive). Candidates are advised to pay the program fee as per the following schedule:</p>
        <table class="table">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th>To be paid before</th>
                    <th>Amount (All Inclusive)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fees as $fee)
                <tr>
                    <td>{{ $fee->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($fee->date)->format('d-M-Y') }}</td>
                    <td>INR {{ round($fee->amount) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2">Total</td>
                    <td>INR {{ round($total_fee) - round($scholarship) }}</td>
                </tr>
            </tbody>
        </table>
        @if(!empty($scholarship))
        <p>You are entitled to a Merit-based Scholarship of INR {{ round($scholarship) }} which has been adjusted against your course fee.</p>
        @endif
        <p>Details for Fee Remittance </p>
        <p>1. To pay using Credit Card/Debit Card/Net Banking please use the following link:</p>
        <div class="text-center">
            <a href="{{ route('user.payment', $lead->short_link) }}">Click Here To Pay Fees</a>
        </div>
        <p>2. Candidates availing NEFT/RTGS/Loan facility kindly use the below mentioned details to pay the fees:</p>
        <table class="table">
            <tbody>
                <tr>
                    <td>Account Name</td>
                    <td>AAFT ELearn Pvt. Ltd.</td>
                </tr>
                <tr>
                    <td>Account Number</td>
                    <td>50200053822317</td>
                </tr>
                <tr>
                    <td>IFSC Code</td>
                    <td>HDFC0009239</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td>HDFC Bank Ltd</td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td>Sector-45, Gautam Budh Nagar, Noida, Uttar Pradesh.</td>
                </tr>
                <tr>
                    <td>Account Type</td>
                    <td>Current Account</td>
                </tr>
            </tbody>
        </table>
        <p>3. Candidates paying by Cheque / Demand Draft (DD) need to draw the same in favor of "AAFT ELearn</p>
    </div>

    <!-- page break -->
    <!-- <div class="page-break"></div> -->
    <!-- page break -->
    <!-- 
    <div class="text-center my-3">
        <img src="{{ asset('logo-black.png') }}" alt="" class="img-fluid">
    </div> -->
    <p>Pvt. Ltd" payable at Noida (Please mention your name on the backside of the Cheque/DD)</p>
    <p>4. Please note that after making the payment through any of the mediums mentioned above, you are required to email the transaction details - Amount, Transaction number and Time & Date of Transaction to <a href="mailto:admissions_support@aaftonline.com">admissions_support@aaftonline.com</a> along with your Name, Contact No. The subject line for the email should be: {{ $course }} fee payment details.</p>
</div>
@endsection