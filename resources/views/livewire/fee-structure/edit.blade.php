<div>
  <!-- Modal -->
  <div wire:ignore.self class="modal fade" id="feeEditModal" tabindex="-1" aria-labelledby="feeEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="feeEditModalLabel">Edit Fee Stucture</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form class="form" wire:submit.prevent="save">
            <div class="form-group mt-3">
              <label for="">Program Name</label>
              <select wire:model="program_id" class="form-control @error('program_id') is-invalid @enderror" wire:change="createName">
                <option value="">Please Select A Program</option>
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
              <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
              @error('name')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group mt-3">
              <label for="">Total Fee</label>
              <input type="number" wire:model="total_fee" class="form-control @error('total_fee') is-invalid @enderror">
              @error('total_fee')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group mt-3 d-flex justify-content-end">
              <button type="button" class="btn btn-outline-primary" wire:click="add({{$i}})"><i class="bx bx-plus"></i></button>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Percent</th>
                  <th scope="col">Amount</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($fees as $key => $value)
                <tr>
                  <th scope="row">{{ $key+1 }}</th>
                  <td>
                    <input type="text" wire:model="fees.{{ $key }}.name" class="form-control @error('fees.{{ $key }}.name') is-invalid @enderror">
                    @error('fees.{{ $key }}.name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </td>
                  <td>
                    <input type="number" wire:model="fees.{{ $key }}.percent" class="form-control @error('fees.{{ $key }}.percent') is-invalid @enderror" wire:keyup="calculateAmount({{ $key }})">
                    @error('fees.{{ $key }}.percent')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </td>
                  <td>
                    <input type="number" wire:model="fees.{{ $key }}.amount" class="form-control @error('fees.{{ $key }}.amount') is-invalid @enderror" wire:keyup="calculatePercent({{ $key }})">
                    @error('fees.{{ $key }}.amount')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </td>
                  <td><button class="btn btn-outline-primary" type="button" wire:click="remove({{$key}})"><i class="bx bx-minus"></i></button></td>
                </tr>
                @endforeach
              </tbody>
            </table>
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