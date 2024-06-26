<div>
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade shadow show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <form id="sale-filter" action="#" class="p-1 mb-4 bg-white border rounded shadow">
        <div class="p-1 form-group">
            <label for="">Name</label>
            <input type="text" wire:model="search_name" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="">Code</label>
            <input type="text" wire:model="search_code" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="">Term</label>
            <select wire:model="search_term" class="form-control">
                <option value="all">All</option>
                <option value="diploma">Diploma</option>
                <option value="certification">Certification</option>
            </select>
        </div>
        <div class="p-1 form-group">
            <label for="" class="invisible d-block">Search</label>
            <button class="btn btn-danger" type="button" wire:click="getProgram"><i class="bx bx-search"></i></button>
        </div>
    </form>

    <div class="d-flex align-items-center justify-content-start">
        <div class="form-group">
            <label for="">Show</label>
            <select wire:model="limit" id="" class="form-control" wire:change="getProgram">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
    </div>
    <table class="table table-hover shadow bg-white table-show">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Code</th>
                <th scope="col">Term</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programs as $key => $program)
            <tr scope="row">
                <td scope="col">{{ ++$key + $offset }}</td>
                <td scope="col">{{ $program->name }}</td>
                <td scope="col">{{ $program->code }}</td>
                <td scope="col">{{ Str::title($program->term) }}</td>
                <td scope="col">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                        data-bs-target="#programEditModal" wire:click="edit({{ $program->id }})"><i
                            class="bx bx-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $program->id }})"><i
                            class="bx bx-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr scope="row">
                <td colspan="4" class="text-center">No Program Found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
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
