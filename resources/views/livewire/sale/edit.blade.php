<div>
    <form action="" submit:prevent.submit="save" class="p-4 border rounded shadow row">
        <h4 class="pb-3">Search Offer in LSQ</h4>
        <div class="col-sm-6 d-flex align-items-end justify-content-between">
            <div class="form-group flex-fill">
                <label for="">Email</label>
                <input type="email" wire:model="search_email" class="form-control @error('search_email') is-invalid @enderror">
                @error('search_mail')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group" style="width: 50px">
                <button type="button" wire:click="get_data_from_email" class="btn btn-danger flex-fill">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
        <div class="col-sm-6 d-flex align-items-end justify-content-between">
            <div class="form-group flex-fill">
                <label for="">Phone</label>
                <input type="number" wire:model="search_phone" class="form-control @error('search_phone') is-invalid @enderror">
                @error('search_mail')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group" style="width: 50px">
                <button type="button" wire:click="get_data_from_phone" class="btn btn-danger flex-fill">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
    </form>

    <form action="" submit:prevent.submit="save" class="p-4 mt-5 border rounded shadow row">
        <h4 class="pb-3">Edit Offer</h4>
        <div class="mb-3 col-sm-4 form-group">
            <label for="">Enrollment No</label>
            <input type="text" wire:model="enrollment_no" class="form-control @error('enrollment_no') is-invalid @enderror" disabled>
            @error('enrollment_no')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-4 form-group">
            <label for="">Name</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" readonly>
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-4 form-group">
            <label for="">Email</label>
            <input type="text" wire:model="email" class="form-control @error('email') is-invalid @enderror" readonly>
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Counsellor</label>
            <input type="text" wire:model="counsellor" class="form-control @error('counsellor') is-invalid @enderror">
            @error('counsellor')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Mobile Number</label>
            <input type="text" wire:model="contact" class="form-control @error('contact') is-invalid @enderror">
            @error('contact')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Program Name</label>
            <select wire:model="program_id" class="form-control @error('program_id') is-invalid @enderror" wire:change="get_payment_term" disabled>
                <option value="">Please Select Program</option>
                @forelse ($programs as $key => $program)
                <option value="{{ $program->id }}">{{ $program->name }}</option>
                @empty

                @endforelse
            </select>
            @error('program_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Batch Name</label>
            <select wire:model="batch_id" class="form-control @error('batch_id') is-invalid @enderror" wire:change="get_payment_term">
                <option value="">Please Select batch</option>
                @forelse ($batches as $key => $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @empty

                @endforelse
            </select>
            @error('batch_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Payment Term</label>
            <select wire:model="payment_term" class="form-control @error('payment_term') is-invalid @enderror" wire:change="get_amount">
                <option value="">Please Select Payment Term</option>
                @forelse ($fees as $key => $fee)
                <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                @empty

                @endforelse
            </select>
            @error('payment_term')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Amount</label>
            <input type="number" wire:model="amount" class="form-control @error('amount') is-invalid @enderror">
            @error('amount')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Scholarship</label>
            <input type="number" wire:model="discount" class="form-control @error('discount') is-invalid @enderror" wire:keyup="get_discounted_amount()">
            @error('discount')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">One Time Discount</label>
            <input type="number" wire:model="otd" class="form-control @error('otd') is-invalid @enderror">
            @error('otd')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Final Amount</label>
            <input type="number" wire:model="final_amount" class="form-control @error('final_amount') is-invalid @enderror">
            @error('final_amount')
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
            <input type="text" wire:model="state" list="states" class="form-control @error('state') is-invalid @enderror">
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

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Status</label>
            <select wire:model="status" class="form-control">
                <option value="0">Offered</option>
                <option value="1">Enrolled</option>
                <option value="2">Offer Expired</option>
                <option value="3">Dropout</option>
            </select>
            @error('status')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>



        <div class="mb-3 col-sm-6 form-group">
            <label for="">Payment Type</label>
            <select wire:model="payment_type" class="form-control">
                <option value="0">Self</option>
                <option value="1">Finance</option>
            </select>
            @error('payment_type')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Location</label>
            <select wire:model="location" class="form-control">
                <option value="">Select Location</option>
                <option value="mumbai">Mumbai</option>
                <option value="gurugram">Gurugram</option>
            </select>
            @error('location')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Complementary Program</label>
            <select wire:model="complementary_id" class="form-control">
                <option value="">Select Complementary Program</option>
                @foreach($complemantries as $complemantry)
                <option value="{{ $complemantry->id }}">{{ $complemantry->name }}</option>
                @endforeach
            </select>
            @error('complementary_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Offer Type</label>
            <select wire:model="type" class="form-control">
                <option value="">Select Offer Type</option>
                <option value="old">Old</option>
                <option value="new">New</option>
            </select>
            @error('type')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @if ($type === 'old')
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Created At</label>
            <input type="date" class="form-control" wire:model="created_at">
            @error('created_at')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @endif
        
        <!-- Payment Date Plan -->
        @if(!empty($payments))
        <div class="col-sm-12 form-group">
            <label for="">Payment Date Plan</label>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Last Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $key => $fee)
                    <tr>
                        <td> <input type="text" wire:model="payments.{{ $key }}.name" class="form-control" disabled></td>
                        <td> <input type="text" wire:model="payments.{{ $key }}.amount" class="form-control" disabled></td>
                        <td>
                            <input type="date" wire:model="payments.{{ $key }}.date" class="form-control">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="col-sm-12 d-flex justify-content-start">
            <button class="btn btn-danger" type="button" style="min-width: 200px" wire:click="save">SAVE</button>
        </div>
    </form>
</div>