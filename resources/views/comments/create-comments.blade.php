
@extends('layouts.main')

@section('content')
    <h1>Create a new comments</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('comments.store') }}' method='POST'>
                @csrf
                
                <div class='form-group'>
                    <label for='name'>Comment</label>
                    <input type='text' class='form-control' id='comment' name='comment' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Tasks_id</label>
                    <input type='text' class='form-control' id='tasks_id' name='tasks_id' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Tasks_projects_id</label>
                    <input type='text' class='form-control' id='tasks_projects_id' name='tasks_projects_id' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Tasks_projects_workspaces_id</label>
                    <input type='text' class='form-control' id='tasks_projects_workspaces_id' name='tasks_projects_workspaces_id' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Users_id</label>
                    <input type='text' class='form-control' id='users_id' name='users_id' required>
                </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
