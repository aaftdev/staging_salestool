<div>
    <div id="verify_offer" class="{{ $verify_offer ? "" : 'd-none' }}">
        <div class="container">
            <div class="d-flex align-middle justify-content-between mb-4">
                <h2>Offer Exist</h2>
                <button type="button" wire:click="Back()" class="btn btn-danger btn-sm"><i class='bx bx-arrow-back'></i>
                    Back</button>
            </div>
            <div class="table-container">
                <table class="table table-show">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Offer Date</th>
                            <th>Candidate Name</th>
                            <th>E-Mail</th>
                            <th>Contact</th>
                            <th>Program</th>
                            <th>Counsellor</th>
                            <th>Batch</th>
                            <th>Booked Amount</th>
                            <th>Final Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $key => $sale)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{  $sale->created_at }}</td>
                            <td>{{  $sale->name }}</td>
                            <td>{{  $sale->email }}</td>
                            <td>{{  $sale->contact }}</td>
                            <td>{{  $courses[$sale->program_id] }}</td>
                            <td>{{  $sale->counsellor }}</td>
                            <td>{{  $batches[$sale->batch_id] }}</td>
                            <td>{{  $sale->amount }}</td>
                            <td>{{  $sale->final_amount }}</td>
                            <td>{{  $sale->status }}</td>
                            <td>{{  $sale->created_at }}</td>
                            <td>
                                <button class="btn btn-outline-danger btn-sm" type="button"
                                    wire:click="MapOffer({{ $sale->id }})">Map Offer</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-middle justify-content-end">
                <a href="{{ route('admin.sales.add') }}?direct={{ encrypt($direct_payment) }}" class="btn
                        btn-outline-primary btn-sm"><i class='bx bx-folder-plus'></i> Create New Offer</a>
            </div>
        </div>
    </div>
    <form id="sale-filter" action="#" class="p-1 mb-4 border rounded shadow form-inline d-flex align-items-end">
        <div class="p-1 form-group">
            <label for="">From</label>
            <input wire:change="getSale" type="date" wire:model="from" class="form-control" id="datefield">
        </div>
        <div class="p-1 form-group">
            <label for="">To</label>
            <input wire:change="getSale" type="date" wire:model="to" class="form-control" id="datefield1">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Email</label>
            <input wire:keyup="getSale" type="email" wire:model="email" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Contact</label>
            <input wire:keyup="getSale" type="number" wire:model="contact" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="">Program</label>
            {{-- <input wire:change="getSale" type="text" wire:model="program" class="form-control"> --}}
            <div class="dropdown w-100">
                <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Program
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    @foreach($programs as $prog)
                    <li class="d-flex align-items-center justify-content-start"><input type="checkbox"
                            wire:click="getSale" wire:model.defer="program.{{ $prog->id }}"
                            class="dropdown-item checkbox" value="{{ $prog->id }}"><span>{{ $prog->name }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="p-1 form-group">
            <label for="">Txnid</label>
            <input wire:change="getSale" type="text" wire:model="txnid" class="form-control">
        </div>
        {{-- <div class="p-1 form-group">
            <button class="btn btn-danger">Search</button>
        </div> --}}
    </form>
    @if(Auth::user()->user_type !== "team")
    <div class="my-3 d-flex align-items-center justify-content-end">
        <a href="{{ route('admin.export-payment') }}" class="btn btn-outline-danger btn-sm">
            <i class='bx bx-export'></i> Export Payment
        </a>
    </div>
    @endif
    <div class="text-center alert alert-info w-100" wire:loading wire:target="getSale" role="alert">
        Searching...
    </div>
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    <div class="table-container">
        <table class="table bg-white border rounded shadow table-hover">
            <thead>
                <tr>
                    <th scope="col" data-toggle="tooltip" title="Payment Date">Payment Date</th>
                    <th scope="col" data-toggle="tooltip" title="TXNID">TXNID</th>
                    <th scope="col" data-toggle="tooltip" title="Candidate Name">Candidate Name</th>
                    @if(Auth::user()->user_type !== "team")
                    <th scope="col" data-toggle="tooltip" title="E-Mail">E-Mail</th>
                    <th scope="col" data-toggle="tooltip" title="Contact">Contact</th>
                    @endif
                    <th scope="col" data-toggle="tooltip" title="Program">Program</th>
                    {{-- <th scope="col" data-toggle="tooltip" title="Counsellor">Counsellor</th> --}}
                    <th scope="col" data-toggle="tooltip" title="Batch">Batch</th>
                    <th scope="col" data-toggle="tooltip" title="State">State</th>
                    <th scope="col" data-toggle="tooltip" title="Paid">Paid</th>
                    <th scope="col" data-toggle="tooltip" title="Mode">Status</th>
                    {{-- <th scope="col" data-toggle="tooltip" title="Paid By">Paid By</th> --}}
                    <th scope="col" data-toggle="tooltip" title="Receipt">Receipt</th>
                    <th scope="col" data-toggle="tooltip" title="Receipt">Verify</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paid as $key => $payment)
                <tr scope="row">
                    <td scope="col" data-toggle="tooltip"
                        title="{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}">
                        {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->txnid }}">{{ $payment->txnid }}</td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->name }}">{{ $payment->name }}</td>
                    @if(Auth::user()->user_type !== "team")
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->email }}">{{ $payment->email }}</td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->contact }}">{{ $payment->contact }}
                    </td>
                    @endif
                    <td scope="col" data-toggle="tooltip"
                        title="{{ \App\Models\Program::find($payment->course)->name }}">
                        {{ \App\Models\Program::find($payment->course)->name }}
                    </td>
                    {{-- <td scope="col"  data-toggle="tooltip" title="{{ $payment->sale($payment->sale_id)->counsellor }}">{{ $payment->sale($payment->sale_id)->counsellor }}
                    </td> --}}
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->batch_name }}">
                        {{ $payment->batch_name }}
                    </td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->state }}">{{ $payment->state }}</td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->paid}}">{{ $payment->paid}}</td>
                    <td scope="col" data-toggle="tooltip" title="{{ $payment->status }}">{{ $payment->status }}</td>
                    {{-- <td scope="col"  data-toggle="tooltip" title="@if (!empty($payment->user_id)) {{ \App\Models\User::find($payment->user_id)->name }}
                    @else SELF @endif">
                    @if (!empty($payment->user_id))
                    {{ \App\Models\User::find($payment->user_id)->name }}
                    @else
                    SELF
                    @endif
                    </td> --}}
                    <td scope="col" data-toggle="tooltip">
                        <a href="{{ route('direct.thank.you', encrypt($payment->id)) }}"
                            class="btn btn-outline-primary btn-sm"><i class='bx bx-receipt'></i></a>
                    </td>
                    <td scope="col" data-toggle="tooltip">
                        {{-- <a href="{{ route('admin.sales.add') }}?direct={{ encrypt($payment->id) }}" class="btn
                        btn-outline-primary btn-sm"><i class='bx bx-folder-plus'></i></a> --}}
                        <button class="btn btn-outline-primary btn-sm" wire:click="verifyOffer({{ $payment->id }})"><i
                                class='bx bx-folder-plus'></i></button>
                    </td>
                </tr>
                @empty
                <tr scope="row">
                    <td @if(Auth::user()->user_type === 'admin') colspan="12" @else colspan="10" @endif
                        class="text-center w-100">No Sale Found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
