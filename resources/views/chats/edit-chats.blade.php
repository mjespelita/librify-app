
@extends('layouts.main')

@section('content')
    <h1>Edit Chats</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('chats.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' value='{{ $item->name }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Is_group</label>
            <input type='text' class='form-control' id='is_group' name='is_group' value='{{ $item->is_group }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Users_id</label>
            <input type='text' class='form-control' id='users_id' name='users_id' value='{{ $item->users_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
