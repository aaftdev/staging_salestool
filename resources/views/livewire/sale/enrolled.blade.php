<div>
    <!-- Delete Sale -->
    @if(!empty($sale))
    <div id="delete-popup">
        <div class="card">
            <div class="card-header">
                Delete Offer Letter
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $sale->name }} Offer</h5>
                <p class="card-text">Are you sure, you want to delete {{ $sale->name }} offer ??</p>
                <button type="button" class="btn btn-danger me-2" wire:click="salePermanentDelete()"><i
                        class="bx bx-trash"></i> Delete</a>
                    <button type="button" class="btn btn-dark" wire:click="saleDelete(0)"><i
                            class='bx bx-arrow-back'></i> Back</button>
            </div>
        </div>
    </div>
    @endif
    <!-- Delete Sale Exit -->
    <form id="sale-filter" action="#"
        class="p-1 mb-4 bg-white border rounded shadow form-inline d-flex align-items-center">
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
            <input type="email" wire:model="email" class="form-control" wire:change="getSale()">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Name</label>
            <input type="name" wire:model="name" class="form-control" wire:change="getSale()">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Contact</label>
            <input type="number" wire:model="contact" class="form-control" wire:change="getSale()">
        </div>
        <div class="p-1 form-group">
            <label for="">Program</label>
            {{-- <select class="form-control js-example-basic-multiple" wire:ignore.self wire:model="program" multiple>
                <option value="" selected>Please Select Program</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}">{{ $prog->name }}</option>
            @endforeach
            </select> --}}
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
</form>

<div class="my-3 d-flex align-items-center justify-content-end">
    <a href="#" wire:click.prevent="export" class="btn btn-outline-danger btn-sm">
        <i class='bx bx-export'></i> Export Sales
    </a>
</div>
<div class="text-center alert alert-info w-100" wire:loading wire:target="getSale" role="alert">
    Searching...
</div>

@if (Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ Session::get('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (Session::has('sent_mail'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ Session::get('sent_mail') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="d-flex flex-column mb-3">
    <label for="">Show</label>
    <select wire:model="limit" class="form-control" wire:change="getSale()" style="width: 100px">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
</div>
<div class="table-container">
    <table class="table bg-white border rounded shadow table-hover table-show">
        <thead>
            <tr>
                <th scope="col" data-toggle="tooltip" title="#">#</th>
                <th scope="col" data-toggle="tooltip" title="Offer Date">Offer Date</th>
                <th scope="col" data-toggle="tooltip" title="Candidate Name">Candidate Name</th>
                <th scope="col" data-toggle="tooltip" title="E-Mail">E-Mail</th>
                <th scope="col" data-toggle="tooltip" title="Contact">Contact</th>
                <th scope="col" data-toggle="tooltip" title="Program">Program</th>
                <th scope="col" data-toggle="tooltip" title="Counsellor">Counsellor</th>
                <th scope="col" data-toggle="tooltip" title="Batch">Batch</th>
                <th scope="col" data-toggle="tooltip" title="Booked Amount">Booked Amount</th>
                <th scope="col" data-toggle="tooltip" title="Final Amount">Final Amount</th>
                <th scope="col" data-toggle="tooltip" title="OTD">OTD</th>
                <th scope="col" data-toggle="tooltip" title="Payable Amount">Payable Amount</th>
                <th scope="col" data-toggle="tooltip" title="Status">Status</th>
                <th scope="col" data-toggle="tooltip" title="Created By">Created By</th>
                <th scope="col" data-toggle="tooltip" title="Action">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $key => $sale)
            @php
                $paid_amount = 0;
                foreach(App\Models\Payment::where('sale_id',$sale->id)->get() as $payment){
                    $paid_amount += $payment->paid;
                }
            @endphp
            <tr scope="row" @if((round($sale->final_amount) - round($paid_amount) - round($sale->otd)) == 0) class="alert alert-success" @endif>
                <td>{{ ++$key + $offset }}</td>
                <td scope="col" data-toggle="tooltip"
                    title="{{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y') }}">
                    {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y') }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ $sale->name }}">{{ $sale->name }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ $sale->email }}">{{ $sale->email }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ $sale->contact }}">{{ $sale->contact }}</td>
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
                <td scope="col" data-toggle="tooltip" title="{{ $sale->amount }}">{{ $sale->amount }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ $sale->final_amount }}">{{ $sale->final_amount }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ $sale->otd }}">{{ empty($sale->otd) ? 0 : $sale->otd }}</td>
                <td scope="col" data-toggle="tooltip" title="{{ round($sale->final_amount) - round($paid_amount) - round($sale->otd)  }}">{{ round($sale->final_amount) - round($paid_amount) - round($sale->otd)  }}</td>
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
                        <a href="{{ route('admin.sales.edit', $sale->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="bx bx-pen"></i>
                        </a>
                        <button type="button" wire:click="linkSend('{{$sale->short_link}}')"
                            class="btn btn-outline-danger btn-sm">
                            <i class="bx bx-send"></i>
                        </button>
                        <a href="{{ route('admin.finance.payment', encrypt($sale->id)) }}"
                            class="btn btn-outline-danger btn-sm">
                            <i class='bx bxs-credit-card'></i>
                        </a>
                        @if ($sale->status !== 2 && !empty($sale->short_link))
                        <button type="button" data-label="{{ route('user.payment', $sale->short_link) }}"
                            class="btn btn-outline-danger btn-sm copy-to-clip">
                            <i class="bx bx-link"></i>
                        </button>
                        @endif

                        @if(Auth::user()->user_type === 'admin')
                        <button type="button" class="btn btn-outline-danger btn-sm"
                            wire:click="saleDelete({{ $sale->id }})">
                            <i class="bx bx-trash"></i>
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
<div wire:ignore.self class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popupModalLabel">Please Copy Your Link From Here:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" wire:model="shareLink" class="form-control">
            </div>
        </div>
    </div>
</div>
</div>
