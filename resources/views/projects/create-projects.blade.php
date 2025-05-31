
@extends('layouts.main')

@section('content')
    <h1>Create a new projects</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('projects.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Workspaces_id</label>
            <input type='text' class='form-control' id='workspaces_id' name='workspaces_id' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
