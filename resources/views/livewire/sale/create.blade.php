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

    @if (count($sales) !== 0)
    <div class="p-4 mt-5 border rounded shadow">
        <h3 class="pb-3">Existing Offers</h3>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" data-toggle="tooltip" title="#">#</th>
                        <th scope="col" data-toggle="tooltip" title="Offer Date">Offer Date</th>
                        <th scope="col" data-toggle="tooltip" title="Candidate Name">Candidate Name</th>
                        @if(Auth::user()->user_type !== "team")
                        <th scope="col" data-toggle="tooltip" title="E-Mail">E-Mail</th>
                        <th scope="col" data-toggle="tooltip" title="Contact">Contact</th>
                        @endif
                        <th scope="col" data-toggle="tooltip" title="Program">Program</th>
                        <th scope="col" data-toggle="tooltip" title="Counsellor">Counsellor</th>
                        <th scope="col" data-toggle="tooltip" title="Batch">Batch</th>
                        <th scope="col" data-toggle="tooltip" title="Booked Amount">Booked Amount</th>
                        <th scope="col" data-toggle="tooltip" title="Final Amount">Final Amount</th>
                        <th scope="col" data-toggle="tooltip" title="Payable Amount">Payable Amount</th>
                        <th scope="col" data-toggle="tooltip" title="Status">Status</th>
                        <th scope="col" data-toggle="tooltip" title="Created By">Created By</th>
                        <th scope="col" data-toggle="tooltip" title="Action" class="sticky">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $key => $sale)
                    <tr scope="row">
                        <td>{{ ++$key }}</td>
                        <td scope="col" data-toggle="tooltip">
                            {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y') }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $sale->name }}">{{ $sale->name }}</td>
                        @if(Auth::user()->user_type !== "team")
                        <td scope="col" data-toggle="tooltip" title="{{ $sale->email }}">{{ $sale->email }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $sale->contact }}">{{ $sale->contact }}</td>
                        @endif
                        <td scope="col" data-toggle="tooltip"
                            title="{{ (\App\Models\Program::find($sale->program_id)) ? \App\Models\Program::find($sale->program_id)->name : '' }}">
                            @if (\App\Models\Program::find($sale->program_id))
                            {{ \App\Models\Program::find($sale->program_id)->name }}
                            @endif
                        </td>
                        <td scope="col" data-toggle="tooltip" title="{{ $sale->counsellor }}">{{ $sale->counsellor }}</td>
                        <td scope="col" data-toggle="tooltip"
                            title="{{ (\App\Models\Batch::find($sale->batch_id)) ? \App\Models\Batch::find($sale->batch_id)->name : '' }}">
                            @if (\App\Models\Batch::find($sale->batch_id))
                            {{ \App\Models\Batch::find($sale->batch_id)->name }}
                            @endif
                        </td>
                        <td scope="col" data-toggle="tooltip" title="{{ round($sale->amount) }}">{{ round($sale->amount) }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ round($sale->final_amount) }}">
                            {{ round($sale->final_amount) }}</td>
                        @php
                        $paid_amount = 0;
                        foreach(App\Models\Payment::where('sale_id',$sale->id)->get() as $payment){
                        $paid_amount += $payment->paid;
                        }
                        @endphp
                        <td scope="col" data-toggle="tooltip" title="{{ round($sale->final_amount) - round($paid_amount) }}">
                            {{ round($sale->final_amount) - round($paid_amount) }}
                        </td>
                        <td scope="col" data-toggle="tooltip">
                            @switch($sale->status)
                            @case(0)
                            Offered
                            @break
                            @case(1)
                            Enrolled
                            @break
                            @case(2)
                            Offer Expired
                            @break
                            @default
                            Offered
                            @endswitch
                        </td>
                        <td scope="col" data-toggle="tooltip"
                            title="@if(!empty($sale->user_id)) {{ \App\Models\User::find($sale->user_id)->name }} @endif">
                            @if(!empty($sale->user_id))
                            {{ \App\Models\User::find($sale->user_id)->name }}
                            @endif
                        </td>
                        <td scope="col" class="sticky">
                            <div class="btn-group">
                                @if (Auth::user()->user_type !== 'manager')
                                <a href="{{ route('admin.sales.edit', $sale->id) }}" class="btn btn-outline-danger btn-sm">
                                    <i class="bx bx-pen"></i>
                                </a>
                                <a href="{{ route('admin.finance.payment', encrypt($sale->id)) }}"
                                    class="btn btn-outline-danger btn-sm">
                                    <i class='bx bxs-credit-card'></i>
                                </a>
                                @endif
                                <button type="button" wire:click="linkSend('{{$sale->short_link}}')"
                                    class="btn btn-outline-danger btn-sm">
                                    <i class="bx bx-send"></i>
                                </button>
                                @if ($sale->status !== 2 && !empty($sale->short_link) && (Auth::user()->user_type !==
                                'manager'))
                                <button type="button" data-label="{{ route('user.payment', $sale->short_link) }}"
                                    class="btn btn-outline-danger btn-sm copy-to-clip">
                                    <i class="bx bx-link"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr scope="row">
                        <td colspan="11" class="text-center">No Sale Found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(Session::has('danger'))
    <div class="mt-3 text-center alert alert-danger alert-dismissible fade show" role="alert">
        <a href="{{ $link }}">{{ Session::get('danger') }}</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="" submit:prevent.submit="save" class="p-4 mt-5 border rounded shadow row">
        <h4 class="pb-3">Create Offer</h4>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Name</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Email</label>
            <input type="text" wire:model="email" class="form-control @error('email') is-invalid @enderror">
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
            <select wire:model="program_id" class="form-control @error('program_id') is-invalid @enderror" wire:change="get_payment_term">
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