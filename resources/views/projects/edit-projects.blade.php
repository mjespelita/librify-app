
@extends('layouts.main')

@section('content')
    <h1>Edit Projects</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('projects.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' value='{{ $item->name }}' required>
        </div>
    
        <div class='form-group'>
            {{-- <label for='name'>Workspaces_id</label> --}}
            <input type='text' class='form-control' id='workspaces_id' hidden name='workspaces_id' value='{{ $item->workspaces_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
