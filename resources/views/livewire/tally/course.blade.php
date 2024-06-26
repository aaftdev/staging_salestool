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
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{$course->Course}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
