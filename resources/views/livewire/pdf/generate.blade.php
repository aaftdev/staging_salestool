<div>
    <div wire:loading.flex wire:target="sendNotification" class="fixed-top h-100 w-100 align-items-center justify-content-center" style="background-color: rgba(0,0,0,.5);">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    @if(Session::has('send_notification'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('send_notification') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <form action="" class="form d-flex align-items-center">
        <div class="form-group w-100 p-1">
            <label for="">Program</label>
            <select wire:model="program" id="" class="form-control" wire:change="filterBatch">
                <option value="" selected>Select A Program</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group w-100 p-1">
            <label for="">Batch</label>
            <select wire:model="batch" id="" class="form-control" wire:change="filterLead">
                <option value="" selected>Select A Batch</option>
                @foreach($batches as $bat)
                <option value="{{ $bat->id }}">{{ $bat->name }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-bordred bg-white table-show mt-5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Mail Status</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>
                    @switch($lead->mail_status)
                    @case(1)
                    Sent
                    @break

                    @case(2)
                    Re-Sent
                    @break

                    @default
                    @endswitch
                </td>
                <td>
                    @switch($lead->offer_status)
                    @case(1)
                    Generated
                    @break

                    @case(2)
                    Re-Generated
                    @break

                    @default
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('admin.pdf.export', encrypt($lead->id)) }}" class="btn btn-sm btn-outline-primary"><i class="bx bxs-download"></i></a>
                    <button wire:click="sendNotification({{ $lead->id }})" class="btn btn-sm btn-outline-primary"><i class="bx bx-envelope"></i></button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No Lead Found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>