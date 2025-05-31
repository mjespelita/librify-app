
@extends('layouts.main')

@section('content')
    <h1>Edit Workspaceusers</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('workspaceusers.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Users_id</label>
            <input type='text' class='form-control' id='users_id' name='users_id' value='{{ $item->users_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Workspaces_id</label>
            <input type='text' class='form-control' id='workspaces_id' name='workspaces_id' value='{{ $item->workspaces_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
