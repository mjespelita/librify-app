
@extends('layouts.main')

@section('content')
    <h1>Edit Tasktimelogs</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('tasktimelogs.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Start_time</label>
            <input type='text' class='form-control' id='start_time' name='start_time' value='{{ $item->start_time }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Pause_time</label>
            <input type='text' class='form-control' id='pause_time' name='pause_time' value='{{ $item->pause_time }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Stop_time</label>
            <input type='text' class='form-control' id='stop_time' name='stop_time' value='{{ $item->stop_time }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Total_time</label>
            <input type='text' class='form-control' id='total_time' name='total_time' value='{{ $item->total_time }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Users_id</label>
            <input type='text' class='form-control' id='users_id' name='users_id' value='{{ $item->users_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Tasks_id</label>
            <input type='text' class='form-control' id='tasks_id' name='tasks_id' value='{{ $item->tasks_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Tasks_projects_id</label>
            <input type='text' class='form-control' id='tasks_projects_id' name='tasks_projects_id' value='{{ $item->tasks_projects_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Tasks_projects_workspaces_id</label>
            <input type='text' class='form-control' id='tasks_projects_workspaces_id' name='tasks_projects_workspaces_id' value='{{ $item->tasks_projects_workspaces_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
