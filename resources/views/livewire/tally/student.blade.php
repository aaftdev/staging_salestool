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
                    <th>Parent</th>
                    <th>Grand Parent</th>
                    <th>Student Name</th>
                    <th>Mailing Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Country Name</th>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th>Address 3</th>
                    <th>Address 4</th>
                    <th>Address 5</th>
                    <th>PinCode</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{$student->Parent}}</td>
                        <td>{{$student->GrandParent}}</td>
                        <td>{{$student->StudentName}}</td>
                        <td>{{$student->MailingName}}</td>
                        <td>{{$student->Email}}</td>
                        <td>{{$student->Phone}}</td>
                        <td>{{$student->Address}}</td>
                        <td>{{$student->CountryName}}</td>
                        <td>{{$student->Address1}}</td>
                        <td>{{$student->Address2}}</td>
                        <td>{{$student->Address3}}</td>
                        <td>{{$student->Address4}}</td>
                        <td>{{$student->Address5}}</td>
                        <td>{{$student->PINCode}}</td>
                        <td>{{$student->Timestamp}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
