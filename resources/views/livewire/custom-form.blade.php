<div>
    <h2>Generate Dues</h2>
    <form wire:submit.prevent="DueGenerate" class="form row">
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Month</label>
            <select name="due_month" wire:model="due_month" id="" class="form-control @error('due_month') is-invalid @enderror">
                <option value="">Select Month</option>
                @foreach ($months as $key => $month)
                    <option value="{{ $key }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Year</label>
            <select name="due_year" wire:model="due_year" id="" class="form-control @error('due_year') is-invalid @enderror">
                <option value="">Select Year</option>
                @for ($i = 2018; $i < 2024; $i++)
                    <option value="{{ $i }}"> {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">VchNo.</label>
            <input type="text" wire:model="due_vchno" class="form-control @error('due_vchno') is-invalid @enderror">
        </div>
        <div class="col-sm-6 mb-3">
            <label for="" class="invisible d-block">Button</label>
            <button type="submit" class="btn btn-danger">Due Generate</button>
        </div>
    </form>
    @if (Session::has('due_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('due_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <h2>Generate Enrolled Students</h2>
    <form wire:submit.prevent="EnrolledGenerate" class="form row">
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Month</label>
            <select name="enroll_month" wire:model="enroll_month" id="" class="form-control @error('enroll_month') is-invalid @enderror">
                <option value="">Select Month</option>
                @foreach ($months as $key => $month)
                    <option value="{{ $key }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Year</label>
            <select name="enroll_year" wire:model="enroll_year" id="" class="form-control @error('enroll_year') is-invalid @enderror">
                <option value="">Select Year</option>
                @for ($i = 2018; $i < 2024; $i++)
                    <option value="{{ $i }}"> {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-sm-6 mb-3">
            <label for="" class="invisible d-block">Button</label>
            <button type="submit" class="btn btn-danger">Due Enrolled</button>
        </div>
    </form>
    @if (Session::has('enroll_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('enroll_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <h2>Generate Receipts</h2>
    <form wire:submit.prevent="ReceiptGenerate" class="form row">
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Month</label>
            <select name="receipt_month" wire:model="receipt_month" id="" class="form-control @error('receipt_month') is-invalid @enderror">
                <option value="">Select Month</option>
                @foreach ($months as $key => $month)
                    <option value="{{ $key }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Year</label>
            <select name="receipt_year" wire:model="receipt_year" id="" class="form-control @error('receipt_year') is-invalid @enderror">
                <option value="">Select Year</option>
                @for ($i = 2018; $i < 2024; $i++)
                    <option value="{{ $i }}"> {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">VchNo</label>
            <input type="text" wire:model="receipt_vchno" class="form-control @error('receipt_vchno') is-invalid @enderror ">
        </div>
        <div class="form-group col-sm-6 mb-3"> 
            <label for="">Bank</label>
            <select wire:model="receipt_bank" class="form-control @error('receipt_bank') is-invalid @enderror ">
                <option value="">Select Bank</option>
                <option value="ICICI">ICICI</option>
                <option value="HDFC">HDFC</option>
            </select>
        </div>
        <div class="col-sm-6 mb-3">
            <label for="" class="invisible d-block">Button</label>
            <button type="submit" class="btn btn-danger">Generate Receipt</button>
        </div>
    </form>
    @if (Session::has('receipt_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('receipt_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
