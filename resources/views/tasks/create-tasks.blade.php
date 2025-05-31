
@extends('layouts.main')

@section('content')
    <h1>Create a new tasks</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('tasks.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Status</label>
            <input type='text' class='form-control' id='status' name='status' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Projects_id</label>
            <input type='text' class='form-control' id='projects_id' name='projects_id' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Projects_workspaces_id</label>
            <input type='text' class='form-control' id='projects_workspaces_id' name='projects_workspaces_id' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
