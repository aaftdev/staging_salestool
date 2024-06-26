<div>
    <div id="popup" @if(!$popup) class="d-none" @endif>
        <div class="container">
            <div class="items-center mb-3 d-flex justify-content-between">
                <h4>Generate Due</h4>
                <button wire:click="popup" class="btn btn-sm btn-danger"><i class="bx bx-minus"></i></button>
            </div>
            <div class="form row">
                <div class="mb-3 col-sm-12">
                    <label for="">Course Term</label>
                    <select wire:model="term" id="" class="form-control">
                        <option value="">Select A Course Term</option>
                        <option value="L">Diploma</option>
                        <option value="S">Certification</option>
                    </select>
                </div>
                <div class="col-sm-6 mb-3 @if(empty($term) || $term === 'S') d-none @endif">
                    <label for="">Course</label>
                    <select wire:model="course" class="form-control @error('course') is-invalid @enderror" wire:change="selectBatch">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            @if ($course->term === 'diploma')
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3 @if(empty($term) || $term === 'S') d-none @endif">
                    <label for="">Batch</label>
                    <select wire:model="batch" class="form-control @error('batch') is-invalid @enderror">
                        <option value="">Select Batch After Course</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="mb-3 col-sm-12">
                    <label for="">Last Payment Date</label>
                    <input type="date" wire:model='last_date' class="form-control @error('last_date') is-invalid @enderror">
                </div> --}}
                <div class="mb-3 col-sm-12 generate-btn">
                    <button class="btn btn-primary" wire:click="dueGenerate">Generate Due</button>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Dues</h1>
        </div>
        <div class="items-center col-sm-6 d-flex justify-content-end">
            <button wire:click="popup" class="btn btn-primary btn-lg"><i class="bx bx-plus"></i> Generate Due</button>
        </div>
    </div>
    <form id="sale-filter" action="#" class="p-1 mb-4 bg-white border rounded shadow" wire:submit="getDues()">
        <div class="p-1 form-group">
            <label for="">From</label>
            <input type="date" wire:model="from" class="form-control" wire:change="getDues()">
        </div>
        <div class="p-1 form-group">
            <label for="">To</label>
            <input type="date" wire:model="to" class="form-control" wire:change="getDues()">
        </div>
        <div class="p-1 form-group">
            <label for="">Search Name</label>
            <input type="name" wire:model.lazy="name" class="form-control" wire:change="getDues()">
        </div>
        <div class="p-1 form-group">
            <label for="">Enrollment No</label>
            <input type="name" wire:model.lazy="enrollment_no" class="form-control" wire:change="getDues()">
        </div>
        <div class="p-1 form-group">
            <label for="">Program</label>
            <input list="courses" type="name" wire:model.lazy="program" class="form-control" wire:change="getDues()">
            <datalist id="courses">
                @foreach ($courses as $course)
                    <option value="{{ $course->name }}">
                @endforeach
            </datalist>
        </div>
    </form>
    @if (Session::has('success'))
        <div class="text-center alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-container">
        <table class="table bg-white border rounded shadow table-hover table-show">
            <thead>
                <tr>
                    <th>#</th>
                    <th>VchNo</th>
                    <th>VchDate</th>
                    <th>DueDate</th>
                    <th>LedgerName</th>
                    <th>EnrollmentNo</th>
                    <th>State</th>
                    <th>Batch</th>
                    <th>FeeHead</th>
                    <th>Amount</th>
                    <th>Course</th>
                    <th>MRP</th>
                    <th>Scholarship</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dues as $key => $due)
                <tr>
                    <th>{{ ++$key+$offset }}</th>
                    <td>{{$due->VchNo}}</td>
                    <td>{{$due->VchDate}}</td>
                    <td>{{$due->DueDate}}</td>
                    <td>{{$due->LedgerName}}</td>
                    <td>{{$due->EnrollmentNo}}</td>
                    <td>{{$due->State}}</td>
                    <td>{{$due->Batch}}</td>
                    <td>{{$due->FeeHead}}</td>
                    <td>{{$due->Amount}}</td>
                    <td>{{$due->Course}}</td>
                    <td>{{$due->MRP}}</td>
                    <td>{{$due->Scholarship}}</td>
                    <td>{{\Carbon\Carbon::parse($due->created_at)->format('d M, Y')}}</td>
                </tr>
                @empty

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
