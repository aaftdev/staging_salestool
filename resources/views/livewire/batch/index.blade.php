<div>
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
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
            <label for="">Program Name</label>
            <input type="text" wire:model="search_program" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="">Code</label>
            <input type="text" wire:model="search_code" class="form-control">
        </div>
        <div class="p-1 form-group">
            <label for="" class="invisible d-block">Search</label>
            <button class="btn btn-danger" type="button" wire:click="getBatch"><i class="bx bx-search"></i></button>
        </div>
    </form>
    <div class="mb-3 d-flex flex-column">
        <label for="">Show</label>
        <select wire:model="limit" class="form-control" wire:change="getOffer()" style="width: 100px">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <table class="table table-hover shadow bg-white table-show">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Program</th>
                <th scope="col">Code</th>
                <th scope="col">Commence Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($batches as $key => $batch)
            <tr scope="row">
                <td scope="col">{{ ++$key+$offset }}</td>
                <td scope="col">{{ $batch->name }}</td>
                <td scope="col">{{ $batch->program($batch->program_id)->name }}</td>
                <td scope="col">{{ $batch->code }}</td>
                <td scope="col">{{ \Carbon\Carbon::parse($batch->commence_date)->format('M d,Y') }}</td>
                <td scope="col">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#batchEditModal"
                        wire:click="edit({{ $batch->id }})"><i class="bx bx-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $batch->id }})"><i
                            class="bx bx-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr scope="row">
                <td colspan="5" class="text-center">No batch Found.</td>
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
