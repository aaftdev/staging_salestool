@extends('layouts.base')

@section('content')
<h2 class="title pt-2">Tax Invoice</h2>
<div class="container">
    <div class="row body">
        <div class="col-6">
            <div class="descri">
                <img src="./logo-black.png" alt="" class="img-fluid">
                <div style="margin-left: 1rem">
                    <h4>AAFT E-learn Private Limited</h4>
                    <h4>GSTIN/UIN: 09AATCA5531G1ZF</h4>
                    <div class="address">
                        <p>B-122, Ground Floor,</p>
                        <p>Sector - 5, Noida - 201301,</p>
                        <p>State Name: Uttar Pradesh, Code: 09</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <label for="">Consignee (ship to)</label>
                <h3>Anjana Nayak</h3>
                <h4>Mumbai</h4>
                <h4>State: Maharashtra, Code: 27</h4>
            </div>
            <div class="box">
                <label for="">Buyer (Bill to)</label>
                <h3>Anjana Nayak</h3>
                <h4>Mumbai</h4>
                <h4>State: Maharashtra, Code: 27</h4>
            </div>
        </div>
        <div class="col-6">
            <div class="row details">
                <div class="col-6">
                    <div class="invoice">
                        <span>Invoice No.</span>
                        <h2>AAFTE/22-23/331</h2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="invoice">
                        <span>Dated</span>
                        <h2>25/Jun/2022</h2>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Delivery Note</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Mode/Terms of Payment</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Reference No. & Date.</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Other References</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Buyer's Order No.</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Dated</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Dispatch Doc No.</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Delivery Note Date</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Dispatch through</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="detail-base">
                        <span>Destination</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="detail-base">
                        <span>Terms of Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Particulars</th>
                <th>HSN/SAC</th>
                <th>QTY.</th>
                <th>RATE</th>
                <th>PER</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>
                    <div class="content">
                        <p>Diploma in Jewellery Course- Interstate@18%</p>
                        <p><span>Less:</span> Discount Allowed</p>
                        <p>Output IGST@18%</p>
                        <p><span>Less:</span> Round-Off</p>
                    </div>
                </td>
                <td>99,9293/-</td>
                <td></td>
                <td>18%</td>
                <td></td>
                <td>
                    <div class="total">
                        <p>1,34,746.00</p>
                        <p>(-)50,000.00</p>
                        <p>15,254.28</p>
                        <p>(-)0.28</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="6">Total</td>
                <td><i class="bx bx-rupee"></i> 1,00,000.00</td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <div class="col-6">
            <p>Amount Chargeable (in words)</p>
            <h4>INR One Lakh Only</h4>
        </div>
        <div class="col-6">
            <p style="text-align: right;">E. & O.E</p>
        </div>
    </div>
    <div class="description-box">
        <div class="description-box-1">
            <p>HSN/SAC</p>
            <p>99,9293</p>
        </div>
        <div class="description-box-2 d-none">
            <div class="tax">Taxable Value</div>
            <div class="int">Integrated Tax</div>
            <div class="rate">Rate</div>
            <div class="amount">Amount</div>
            <div class="amt1">84,746.00</div>
            <div class="amt2">15,254.28</div>
            <div class="amt3">84,746.00</div>
            <div class="amt4">15,254.28</div>
            <div class="per">18%</div>
        </div>
        <div class="description-box-4">
            <div class="tax">Taxable Value</div>
            <div class="value">1,01,694.92</div>
            <div class="cent">Central Tax</div>
            <div class="cent-rate">Rate</div>
            <div class="cent-amount">Amount</div>
            <div class="cent-per">9%</div>
            <div class="cent-amt">9,152.54</div>
            <div class="state">State Tax</div>
            <div class="state-rate">Rate</div>
            <div class="state-amount">Amount</div>
            <div class="state-per">9%</div>
            <div class="state-amt">84,746.00</div>
        </div>
        <div class="description-box-3">
            <p>Total Tax Amount</p>
            <p>15,254.28</p>
        </div>
    </div>
    <div class="total-box">
        <p>Total</p>
        <p>15,254.28</p>
    </div>
    <div class="row">
        <div class="col-4" style="padding: 1rem;">Tax Amount (in words)  :</div>
        <div class="col-8" style="font-weight: bold; text-align: right; padding: 1rem;">INR Fifteen Thousand Two Hundred Fifty Four and Twenty Eight paise Only</div>
    </div>
    <h4 style="text-align: right; margin-top: 1rem;">for AAFT Elearn Private Limited (New)</h4>
    <h4 style="text-align: right; margin-top: 3rem;">Authorised Signatory</h4>
</div>
@endsection


@section('styles')
<style>
    body {
        size: 21cm 29.7cm;
        /* change the margins as you want them to be. */
    }

    .title {
        text-align: center;
        font-size: 14px;
        color: #1b1617;
    }

    h4 {
        font-size: 14px;
        color: #1b1617;
    }

    h3 {
        font-weight: bold;
        font-size: 16px;
        color: #1b1617;
    }

    .address {
        padding: 1rem 0rem;
    }

    .address p {
        padding: 0;
        margin: 0;
        font-size: 14px;
        color: #1b1617;
    }

    .box {
        padding: 0.4rem 0rem;
    }

    label {
        /* font-size: 8px; */
        color: #1b1617;
    }

    .container {
        border: 0.5px solid #707070;
        padding: 1rem;
        aspect-ratio: 1:1.4142;
        -webkit-aspect-ratio: 1:1.4142;
    }

    .invoice {
        background-color: #f1f1f1;
        padding: 0.2rem 0.5rem;
        width: 100%;
    }

    .col-6 span {
        /* font-size: 8px; */
        color: #1b1617;
    }

    .invoice h2 {
        font-size: 14px;
        color: #1b1617;
        font-weight: 600;
        letter-spacing: 0.1rem;
    }

    .details .col-6 {
        padding: 0;
        transform: scale(0.98);
        margin-bottom: 0.3rem;
        background-color: #f8f8f8;
        min-height: 3rem;
        display: flex;
        align-items: center;
    }

    .details .col-6 .detail-base {
        padding: 0.2rem 0.5rem;
    }
    .details .col-12 {
        padding: 0;
        transform: scale(0.98);
        margin-bottom: 0.3rem;
        background-color: #f8f8f8;
        min-height: 3rem;
        display: flex;
        align-items: center;
    }

    .details .col-12 .detail-base {
        padding: 0.2rem 0.5rem;
    }

    .table thead th{
        background-color: #1b1617 !important;
        color: #fff !important;
        border:1px solid #1b1617;
    }
    .table td{
        border-left: 1px solid #b5b5b5;
    }
    .table td:nth-last-child(1){
        border-right: 1px solid #b5b5b5;
        font-weight: bold;
    }
    .table td:nth-child(2){
        font-weight: bold;
        text-align: left;
    }
    .table td:nth-child(2) span{
        font-weight: normal;
    }
    .table tr:nth-last-child(1) td{
        background-color: #dfdfdf;
    }
    .table tr:nth-last-child(1) td:nth-child(2){
        font-weight: bold;
    }
    .description-box{
        background-color: #f1f1f1;
        border: 1px solid #b5b5b5;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }
    [class*='description-box-']{
        padding: 1rem;
        border-right: 1px solid #b5b5b5;
    }
    .description-box-1{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .description-box-3{
        border-right: 0px solid transparent;
    }
    .description-box-2{
        padding: 0rem;
        display: grid;
        grid-template-areas: 'tax int int'
                            'tax rate amount'
                            'amt1 per amt2'
                            'amt3 ... amt4';
    }
    .description-box-2 .tax{
        grid-area: tax;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
    }
    .description-box-2 .int{
        grid-area: int;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .description-box-2 .rate{
        grid-area: rate;
        border-right: 1px solid #b5b5b5;
        border-top: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .description-box-2 .amount{
        grid-area: amount;
        border-top: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .description-box-2 .per{
        grid-area: per;
        border-bottom: 1px solid #b5b5b5;
        padding: 0.5rem;
    }
    .description-box-2 .amt1{
        grid-area: amt1;
        border-bottom: 1px solid #b5b5b5;
        border-right: 1px solid #b5b5b5;
        padding: 0.5rem;
    }
    .description-box-2 .amt2{
        grid-area: amt2;
        border-bottom: 1px solid #b5b5b5;
        border-left: 1px solid #b5b5b5;
        padding: 0.5rem;
    }
    .description-box-2 .amt3{
        grid-area: amt3;
        border-right: 1px solid #b5b5b5;
        padding: 0.5rem;
    }
    .description-box-2 .amt4{
        grid-area: amt4;
        border-left: 1px solid #b5b5b5;
        padding: 0.5rem;
    }
    .description-box-3{
        padding: 0;
        display: grid;
        grid-template-areas: 'box1'
        'box1'
        'box2'
        'box3';
    }
    .description-box-3 p:nth-child(1){
        grid-area: box1;
        border-bottom: 1px solid #b5b5b5;
        padding: 0.5rem;
        height: 8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* .description-box-3 p:nth-child(2){
        grid-area: box2;
        border-bottom: 1px solid #b5b5b5;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;

    } */
    .description-box-3 p:nth-child(2){
        grid-area: box3;
        height: 2.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    img{
        width: 8rem;
        object-fit: contain;
    }
    .body .col-6 .descri{
        display: flex; align-items: flex-start;
        border-right: 1px solid #b5b5b5;
    }
    .body .col-6  .box{
        border-top: 1px solid #b5b5b5;
        border-right: 1px solid #b5b5b5;
    }

    .description-box-4{
        padding: 0rem;
        display: grid;
        grid-template-areas: 'tax cent cent state state'
                            'tax cent-rate cent-amount state-rate state-amount'
                            'value cent-per cent-amt state-per state-amt';
    }
    .description-box-4 .tax{
        grid-area: tax;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .value{
        grid-area: value;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
    }
    .description-box-4 .cent{
        grid-area: cent;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .state{
        grid-area: state;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .cent-rate{
        grid-area: cent-rate;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .cent-amount{
        grid-area: cent-amount;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .cent-amt{
        grid-area: cent-amt;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
    }
    .description-box-4 .cent-per{
        grid-area: cent-per;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
    }
    .description-box-4 .state-rate{
        grid-area: state-rate;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .state-amount{
        grid-area: state-amount;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #b5b5b5;
    }
    .description-box-4 .state-amt{
        grid-area: state-amt;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .description-box-4 .state-per{
        grid-area: state-per;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #b5b5b5;
    }
    .total-box{
        display: grid;
        grid-template-columns: 2fr 1fr;
        background-color: #f1f1f1;
        border: 1px solid #b5b5b5;
        border-top: 0px solid transparent;
    }
    .total-box p{
        padding: 1rem;
        margin: 0;
    }
    .total-box p:nth-child(1){
        border-right: 1px solid #b5b5b5;
    }
</style>
@endsection


@section('scripts')
<script>

</script>
@endsection
