<div>
    <!-- Modal -->
    <x-partials.alert />
    <div wire:ignore.self class="modal fade" id="batchEditModal" tabindex="-1" aria-labelledby="batchEditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchEditModalLabel">Edit Batch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade shadow show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form class="form" wire:submit.prevent="save">
                        <div class="form-group mt-3">
                            <label for="">Program</label>
                            <select wire:model="program_id" class="form-control">
                                <option value="">Select Program</option>
                                @forelse ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @empty

                                @endforelse
                            </select>
                            @error('program_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Name</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Commence Date</label>
                            <input type="date" wire:model="commence_date" class="form-control">
                            @error('commence_date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="update()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
