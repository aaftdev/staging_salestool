<div>
    <form id="sale-filter" action="#" class="p-1 mb-4 border rounded shadow form-inline d-flex align-items-end">
        <div class="p-1 form-group">
            <label for="">From</label>
            <input type="date" wire:model="from" class="form-control" wire:change="getSale()" id="datefield">
        </div>
        <div class="p-1 form-group">
            <label for="">To</label>
            <input type="date" wire:model="to" class="form-control" wire:change="getSale()" id="datefield1">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Email</label>
            <input type="email" wire:model.lazy="email" class="form-control" wire:change="getSale()">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Contact</label>
            <input type="number" wire:model.lazy="contact" class="form-control" wire:change="getSale()">
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
                            wire:click="getSale" wire:model.defer="program.{{ $prog->id }}" wire:change="getSale()"
                            class="dropdown-item checkbox" value="{{ $prog->id }}"><span>{{ $prog->name }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="p-1 form-group">
            <label for="">Txnid</label>
            <input type="text" wire:model.lazy="txnid" class="form-control" wire:change="getSale()">
        </div>
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
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column">
                <label for="">Show</label>
                <select wire:model="limit" class="form-control" wire:change="getSale()" style="width: 100px">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <p>Total Revenue: {{ $revenue }}</p>
        </div>
        <div class="table-container">
            <table class="table bg-white border rounded shadow table-hover table-show">
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
                        <th scope="col" data-toggle="tooltip" title="Counsellor">Counsellor</th>
                        <th scope="col" data-toggle="tooltip" title="Batch">Batch</th>
                        <th scope="col" data-toggle="tooltip" title="State">State</th>
                        <th scope="col" data-toggle="tooltip" title="Paid">Paid</th>
                        <th scope="col" data-toggle="tooltip" title="Mode">Mode</th>
                        <th scope="col" data-toggle="tooltip" title="Paid By">Paid By</th>
                        <th scope="col" data-toggle="tooltip" title="Paid By">Created At</th>
                        <th scope="col" data-toggle="tooltip" title="Receipt">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paid as $key => $payment)
                    <tr scope="row">
                        <td scope="col" data-toggle="tooltip"
                            title="{{ \Carbon\Carbon::parse($payment->reference_date)->format('d/m/Y') }}">
                            {{ \Carbon\Carbon::parse($payment->reference_date)->format('d/m/Y') }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->txnid }}">{{ $payment->txnid }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->sale($payment->sale_id)->name }}">
                            {{ $payment->sale($payment->sale_id)->name }}</td>
                        @if(Auth::user()->user_type !== "team")
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->sale($payment->sale_id)->email }}">
                            {{ $payment->sale($payment->sale_id)->email }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->sale($payment->sale_id)->contact }}">
                            {{ $payment->sale($payment->sale_id)->contact }}</td>
                        @endif
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->program_name }}">
                            {{ $payment->program_name }}
                        </td>
                        <td scope="col" data-toggle="tooltip"
                            title="{{ $payment->sale($payment->sale_id)->counsellor }}">
                            {{ $payment->sale($payment->sale_id)->counsellor }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->batch_name }}">
                            {{ $payment->batch_name }}
                        </td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->sale($payment->sale_id)->state }}">
                            {{ $payment->sale($payment->sale_id)->state }}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->paid}}">{{ $payment->paid}}</td>
                        <td scope="col" data-toggle="tooltip" title="{{ $payment->mode }}">{{ $payment->mode }}</td>
                        <td scope="col" data-toggle="tooltip"
                            title="@if (!empty($payment->user_id)) {{ \App\Models\User::find($payment->user_id)->name }} @else SELF @endif">
                            @if (!empty($payment->user_id))
                            {{ \App\Models\User::find($payment->user_id)->name }}
                            @else
                            SELF
                            @endif
                        </td>
                        <td scope="col" data-toggle="tooltip"
                            title="{{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}">
                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</td>
                        <td scope="col" data-toggle="tooltip">
                            <a href="{{ route('thank.you', encrypt($payment->id)) }}"
                                class="btn btn-outline-primary btn-sm"><i class='bx bx-receipt'></i></a>
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
        <div class="d-flex justify-content-between">
            <span>Total: {{ $max }}</span>
            <div class="d-flex">
                <button class="btn btn-outline-primary me-1" wire:click="prev()">
                    <i class='bx bx-arrow-back'></i>
                </button>
                <div class="button btn btn-secondary me-1">{{ ($offset + 10) / 10 }}</div>
                <button class="btn btn-outline-primary" wire:click="next()">
                    <i class='bx bx-right-arrow-alt'></i>
                </button>
            </div>
        </div>
    </div>
