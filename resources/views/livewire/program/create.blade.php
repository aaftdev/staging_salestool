<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="programCreateModal" tabindex="-1"
        aria-labelledby="programCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="programCreateModalLabel">Add Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade shadow show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form class="form" wire:submit.prevent="save" enctype="multipart/form-data">
                        <div class="mt-3 form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3 form-group">
                            <label for="">Code</label>
                            <input type="text" class="form-control" wire:model="code">
                            @error('code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3 form-group">
                            <label for="">Term</label>
                            <select wire:model="term" class="form-control">
                                <option value="diploma">Diploma</option>
                                <option value="certification">Certification</option>
                            </select>
                            @error('term')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3 form-group">
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <label for="">Documents</label>
                                <button type="button" wire:click="addRow({{$i}})"
                                    class="btn btn-sm btn-outline-primary"><i class="bx bx-plus"></i></button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $key => $doc)
                                    <tr>
                                        <td>
                                            <input type="file" placeholder="Document"
                                                wire:model="documents.{{ $key }}.pdf" class="form-control">
                                            <span wire:loading
                                                wire:target="documents.{{ $key }}.pdf">Uploading...</span>
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Name" wire:model="documents.{{ $key }}.name"
                                                class="form-control">
                                        </td>
                                        <td>
                                            @if($i !== 0)
                                            <button class="btn btn-sm btn-outline-danger" type="button"
                                                wire:click="removeRow({{ $key }})"><i class="bx bx-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="save()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
