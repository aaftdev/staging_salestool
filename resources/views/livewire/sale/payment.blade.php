<div>
    <form action="#" class="mb-5 row border p-3 rounded shadow bg-white" wire:submit.prevent="save">
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Name</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" readonly>
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Email</label>
            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" readonly>
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Program Name</label>
            <input type="text" wire:model="program_name" class="form-control @error('program_name') is-invalid @enderror" readonly>
            @error('program_name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Mobile Number</label>
            <input type="number" wire:model="contact" class="form-control @error('contact') is-invalid @enderror">
            @error('contact')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group {{ $program_term === 'certification' ? 'd-none' : '' }}">
            <label for="">Batch</label>
            <input type="text" wire:model="batch" class="form-control @error('batch') is-invalid @enderror">
            @error('batch')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group {{ $program_term === 'certification' ? 'd-none' : '' }}">
            <label for="">Batch Commence Date</label>
            <input type="text" wire:model="batch_commence" class="form-control @error('batch_commence') is-invalid @enderror">
            @error('batch_commence')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Address</label>
            <input type="text" wire:model="address" class="form-control @error('address') is-invalid @enderror">
            @error('address')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">State</label>
            <input type="text" wire:model="state" list="states" class="form-control @error('state') is-invalid @enderror" readonly>
            <datalist id="states">
                @forelse ($states as $item)
                <option value="{{ $item }}">{{ $item }}</option>
                @empty

                @endforelse
            </datalist>
            @error('state')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        @if (Session::has('danger'))
        <div class="alert alert-danger alert-dismissible fade show col-sm-12" role="alert">
            {{ Session::get('danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="mt-4"></div>
        <h4 class="col-sm-8 d-flex align-items-end">Fee Details</h4>
        {{-- <p class="col-sm-8">Select Installment</p> --}}
        <p class="col-sm-4 d-flex flex-column align-items-end">
            <span class="pb-2">Total Paid: <i class='bx bx-rupee'></i> {{ $paid_total }}</span>
            <span>Pending Amount : <i class='bx bx-rupee'></i> {{ $remaining_amount }}</span>
            <span>Discount : <i class='bx bx-rupee'></i> {{ $scholarship }}</span>
        </p>

        <div class="col-sm-12">
            <h4 class="pay-installment my-2">Pay Installment</h4>
            <table class="table">
                <tbody>
                    @forelse ($fees as $key => $fee)
                    <tr class="row px-2">
                        <td class="col-sm-10">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" wire:click="getAmount('check')" wire:model="amounts.{{ $key }}" value="{{ !empty($paid_fees[$key]) ? $paid_fees[$key] : $fee }}" class="me-2" id="" @if (!empty($paid_fees) && !empty($paid_fees_status[$key])) disabled @endif>
                                <span class="d-inline-block me-2">{{ $key }}</span>
                                @if (array_key_last($this->fees) === $key)
                                <span class="d-inline-block me-2">(with Scholarship)</span>
                                @endif
                                @if (!empty($paid_fees) && !empty($paid_fees_status[$key]))
                                <span class="badge bg-success">Paid</span>
                                @endif
                            </div>
                        </td>
                        <td class="col-sm-2">
                            <div class="d-flex align-items-center">
                                <i class='bx bx-rupee'></i> {{ !empty($paid_fees[$key]) ? round($paid_fees[$key]) : round($fee) }}
                            </div>
                        </td>
                    </tr>
                    @empty

                    @endforelse
                    <tr class="row px-2">
                        <td class="col-sm-10 d-flex align-items-center">Others</td>
                        <td class="col-sm-2">
                            <input type="number" wire:model="other" wire:keyup="getAmount('other')" class="form-control @error('other') is-invalid @enderror" @if(empty($remaining_amount)) disabled @endif>
                            @error('other')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    @if (!empty($otd))
                        <tr class="row px-2">
                        <td class="col-sm-10 d-flex align-items-center">
                            One Time Discount
                        </td>
                        <td class="col-sm-2">
                            <i class="bx bx-rupee"></i>
                            {{ $otd }}
                        </td>
                    </tr>
                    @endif
                    <tr class="row px-2">
                        <td class="col-sm-10 d-flex align-items-center">
                            <h5>TOTAL</h5>
                        </td>
                        <td class="col-sm-2">
                            <i class="bx bx-rupee"></i>
                            {{ $total }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-sm-12 text-center">
            @if (!empty($remaining_amount))
            <button class="btn btn-danger" style="min-width: 200px">Pay Now</button>
            @else
            <button type="button" style="min-width: 200px" class="btn btn-success">Paid</button>
            @endif
        </div>
    </form>
</div>