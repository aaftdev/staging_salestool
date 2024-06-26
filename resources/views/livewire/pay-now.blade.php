<div class="payment-box">
    <div class="row bg-white p-3 {{ $search_form ? 'd-none':'' }}">
        <div class="col-sm-6">
            @if (Session::has('danger'))
            <div class="alert alert-primary" role="alert">
                {{ Session::get('danger') }}
            </div>
            @endif
            <h3 class="pt-2 pb-4 search-title">Pay for your AAFT Online Course</h3>
            <div class="form-group mb-3">
                <label for="">Email Address</label>
                <input type="email" wire:model="search_email" class="form-control" placeholder="Email Address">
            </div>
            <div class="form-group mb-0 d-flex justify-content-center">
                <label for="" class="fw-bolder">OR</label>
            </div>
            <div class="form-group mb-3">
                <label for="">Contact Number</label>
                <input type="text" wire:model="search_contact" class="form-control" placeholder="Contact Number">
            </div>
            <div class="form-group mb-3">
                <input type="radio" wire:model="search_term" value="diploma"> Diploma
                <input type="radio" wire:model="search_term" value="certification"> Certification
            </div>
            <div class="form-group mb-0 d-flex justify-content-center">
                <label for="" class="invisible">OR</label>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" style="width: 150px; border-radius: 1.5rem;"
                    wire:click="search">Continue</button>
            </div>
        </div>
        <div class="col-sm-6"></div>
    </div>

    {{-- Sales Table --}}
    @if (count($sales) !== 0)
    <div class="mt-5 bg-white p-2 {{ $search_form ? '':'d-none' }}">
        <h5 class="pt-2 pb-4 text-center">Please Click on Link for payment</h5>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th colspan="col">#</th>
                    <th colspan="col">Name</th>
                    <th colspan="col">Email</th>
                    <th colspan="col">Program Name</th>
                    <th colspan="col">Batch Name</th>
                    <th colspan="col">Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $key => $sale)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->email }}</td>
                    <td>{{ \App\Models\Program::find($sale->program_id)->name }}</td>
                    <td>{{ \App\Models\Batch::find($sale->batch_id)->name }}</td>
                    <td>
                        <a href="{{ route('user.payment',$sale->short_link) }}"
                            class="btn btn-sm btn-outline-primary"><i class="bx bx-link"></i> Pay Now</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button class="btn btn-dark" wire:click="back()"><i class='bx bx-arrow-back'></i> BACK</button>
        </div>
    </div>
    @endif

    {{-- payment form --}}
    <form action="#" class="payment-form mb-5 row p-3 bg-white {{ $pay_form ? '' : 'd-none' }}"
        wire:submit.prevent="save">
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Name <span class="text-danger">*</span></label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Email <span class="text-danger">*</span></label>
            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Program Name <span class="text-danger">*</span></label>
            {{-- <input type="text" wire:model="program_name" class="form-control @error('program_name') is-invalid @enderror"> --}}
            <select wire:model="program_id" id="" class="form-control @error('program_id') is-invalid @enderror"
                wire:change="selectBatch">
                <option value="">Select A Program</option>
                @foreach ($programs as $prog)
                <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                @endforeach
            </select>
            @error('program_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">Mobile Number <span class="text-danger">*</span></label>
            <input type="number" wire:model="contact" class="form-control @error('contact') is-invalid @enderror">
            @error('contact')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group {{ $search_term === 'certification' ? 'd-none' : '' }}">
            <label for="">Batch</label>
            {{-- <input type="text" wire:model="batch" class="form-control @error('batch') is-invalid @enderror"> --}}
            <select wire:model="batch_id" id="" class="form-control @error('batch_id') is-invalid @enderror"
                wire:change="BatchCommence">
                <option value="">Select A Batch</option>
                @foreach ($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
            @error('batch_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group {{ $search_term === 'certification' ? 'd-none' : '' }}">
            <label for="">Batch Commence Date</label>
            <input type="text" wire:model="batch_commence"
                class="form-control @error('batch_commence') is-invalid @enderror">
            @error('batch_commence')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 col-sm-6 form-group">
            <label for="">Address <span class="text-danger">*</span></label>
            <input type="text" wire:model="address" class="form-control @error('address') is-invalid @enderror">
            @error('address')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3 col-sm-6 form-group">
            <label for="">State <span class="text-danger">*</span></label>
            <input type="text" wire:model="state" list="states"
                class="form-control @error('state') is-invalid @enderror">
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
        {{-- <h4 class="col-sm-8 d-flex align-items-end">Fee Details</h4> --}}
        <div class="col-sm-12">
            <h4 class="pay-installment my-2 bg-primary">Enter the amount</h4>
            <table class="table">
                <tbody>
                    <tr class="row px-2">
                        <td class="col-sm-9 d-flex align-items-center">Payment</td>
                        <td class="col-sm-3 d-flex flex-column align-items-center">
                            <input type="number" wire:model="other"
                                class="form-control @error('other') is-invalid @enderror">
                            @error('other')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr class="row px-2">
                        <td class="col-sm-10 d-flex align-items-center">
                            <h5>TOTAL</h5>
                        </td>
                        <td class="col-sm-2">
                            <h5><i class="bx bx-rupee"></i> {{ $other }}</h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-danger" style="min-width: 200px">Pay Now</button>
        </div>
    </form>
</div>
