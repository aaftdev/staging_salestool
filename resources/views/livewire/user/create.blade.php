<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="UserCreateModal" tabindex="-1" aria-labelledby="UserCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="UserCreateModalLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
              <form class="form" wire:submit.prevent="save">
                    <div class="mt-3 form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" wire:model="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-3 form-group">
                        <label for="">Email</label>
                        <input type="email" wire:model="email" class="form-control">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-3 form-group">
                        <label for="">Location</label>
                        <select wire:model="location" id="" class="form-control">
                            <option value="mumbai">Mumbai</option>
                            <option value="gurugram">Gurugram</option>
                        </select>
                        @error('location')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-3 form-group">
                        <label for="">User Type</label>
                        <select wire:model="user_type" id="" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="team">Team</option>
                            <option value="ops">OPS</option>
                        </select>
                        @error('user_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-3 form-group">
                        <label for="">Password</label>
                        <input type="text" wire:model="password" class="form-control">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
