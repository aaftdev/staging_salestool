<div>
    <!-- Delete Sale -->
    @if(!empty($user))
    <div id="delete-popup">
        <div class="card">
            <div class="card-header">
                Delete User !!
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $user->name }} Offer</h5>
                <p class="card-text">Are you sure, you want to delete {{ $user->name }} ??</p>
                <button type="button" class="btn btn-danger me-2" wire:click="userPermanentDelete()"><i class="bx bx-trash"></i> Delete</a>
                    <button type="button" class="btn btn-dark" wire:click="userDelete(0)"><i class='bx bx-arrow-back'></i> Back</button>
            </div>
        </div>
    </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="table-container">
        <table class="table bg-white border rounded shadow table-hover table-show">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Location</th>
                    <th scope="col">Role</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                <tr scope="row">
                    <td>{{ ++$key }}</td>
                    <td scope="col">{{ $user->name }}</td>
                    <td scope="col">{{ $user->email }}</td>
                    <td scope="col">{{ $user->location }}</td>
                    <td scope="col">{{ $user->user_type }}</td>
                    <td scope="col">{{ \Carbon\Carbon::parse($user->created_at)->format('M d,Y') }}</td>
                    <td scope="col" class="sticky">
                        <div class="d-flex flex-row align-items-center">
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#UserEditModal" wire:click="edit('{{ $user->id }}')"><i class="bx bx-pen"></i></button>
                            <button type="button" wire:click="delete('{{$user->id}}')" class="btn btn-outline-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr scope="row">
                    <td colspan="4" class="text-center">No User Found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>