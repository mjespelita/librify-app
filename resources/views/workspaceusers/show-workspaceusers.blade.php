
@extends('layouts.main')

@section('content')
    <h1>Workspaceusers Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ $item->id }}</td>
                    </tr>
                    
        <tr>
            <th>Users_id</th>
            <td>{{ $item->users_id }}</td>
        </tr>
    
        <tr>
            <th>Workspaces_id</th>
            <td>{{ $item->workspaces_id }}</td>
        </tr>
    
                    <tr>
                        <th>Created At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->updated_at) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <a href='{{ route('workspaceusers.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
