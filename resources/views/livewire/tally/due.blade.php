<div>
    <div class="row mb-5">
        <div class="col-sm-8"></div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Search</label>
                    <input type="text" class="form-control" wire:model.debounce="search" wire:keyup="Search">
                </div>
            </div>
        </div>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vch No</th>
                    <th>Vch Date</th>
                    <th>Reg No</th>
                    <th>Ledger Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dues as $due)
                    <tr>
                        <td>{{$due->vchno}}</td>
                        <td>{{$due->vchdate}}</td>
                        <td>{{$due->regno}}</td>
                        <td>{{$due->ledgername}}</td>
                        <td>{{$due->amount}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
