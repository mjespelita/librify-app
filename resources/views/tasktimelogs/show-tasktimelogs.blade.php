
@extends('layouts.main')

@section('content')
    <h1>Tasktimelogs Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ $item->id }}</td>
                    </tr>
                    
        <tr>
            <th>Start_time</th>
            <td>{{ $item->start_time }}</td>
        </tr>
    
        <tr>
            <th>Pause_time</th>
            <td>{{ $item->pause_time }}</td>
        </tr>
    
        <tr>
            <th>Stop_time</th>
            <td>{{ $item->stop_time }}</td>
        </tr>
    
        <tr>
            <th>Total_time</th>
            <td>{{ $item->total_time }}</td>
        </tr>
    
        <tr>
            <th>Users_id</th>
            <td>{{ $item->users_id }}</td>
        </tr>
    
        <tr>
            <th>Tasks_id</th>
            <td>{{ $item->tasks_id }}</td>
        </tr>
    
        <tr>
            <th>Tasks_projects_id</th>
            <td>{{ $item->tasks_projects_id }}</td>
        </tr>
    
        <tr>
            <th>Tasks_projects_workspaces_id</th>
            <td>{{ $item->tasks_projects_workspaces_id }}</td>
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

    <a href='{{ route('tasktimelogs.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
