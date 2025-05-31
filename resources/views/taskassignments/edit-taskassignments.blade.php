
@extends('layouts.main')

@section('content')
    <h1>Edit Taskassignments</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('taskassignments.update', $item->id) }}' method='POST'>
                @csrf
                
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
    
        <div class='form-group'>
            <label for='name'>Users_id</label>
            <input type='text' class='form-control' id='users_id' name='users_id' value='{{ $item->users_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Role</label>
            <input type='text' class='form-control' id='role' name='role' value='{{ $item->role }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
