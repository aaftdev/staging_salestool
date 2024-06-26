<div>
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <table class="table table-hover shadow bg-white table-show">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Amount</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($complemantries as $key => $complemantry)
            <tr scope="row">
                <td scope="col">{{ $key+1 }}</td>
                <td scope="col">{{ $complemantry->name }}</td>
                <td scope="col">{{ $complemantry->amount }}</td>
                <td scope="col">{{ $complemantry->status ? "Publish" : "Draft" }}</td>
                <td scope="col">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#batchEditModal" wire:click="edit({{ $complemantry->id }})"><i class="bx bx-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $complemantry->id }})"><i class="bx bx-trash"></i></button>
                </td>
            </tr>
            @empty
            <tr scope="row">
                <td colspan="5" class="text-center">No Complematry Program Found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>