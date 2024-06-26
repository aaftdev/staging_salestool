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
            <label for="" class="invisible d-block">Search</label>
            <button class="btn btn-danger" type="button" wire:click="getFee"><i class="bx bx-search"></i></button>
        </div>
    </form>
    <table class="table table-hover mt-3 shadow bg-white table-show">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Program Name</th>
                <th scope="col">Total Fee</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fees as $key => $fee)
            <tr scope="row">
                <td scope="col">{{ \Carbon\Carbon::parse($fee->created_at)->format('d/m/Y') }}</td>
                <td scope="col">{{ $fee->name }}</td>
                <td scope="col">{{ $fee->program($fee->program_id) }}</td>
                <td scope="col">{{ $fee->total_fee }}</td>
                <td scope="col">
                    <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="modal" data-bs-target="#feeEditModal" wire:click="edit({{ $fee->id }})"><i class="bx bx-pen"></i></button>
                    {{-- <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $fee->id }})"><i class="bx bx-trash"></i></button> --}}
                </td>
            </tr>
            @empty
            <tr scope="row">
                <td colspan="5" class="text-center">No Fee Structure Found.</td>
            </tr>
            @endforelse
        </tbody>
      </table>
</div>
