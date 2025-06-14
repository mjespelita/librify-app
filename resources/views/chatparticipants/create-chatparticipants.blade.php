
@extends('layouts.main')

@section('content')
    <h1>Create a new chatparticipants</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('chatparticipants.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Chats_id</label>
            <input type='text' class='form-control' id='chats_id' name='chats_id' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Chats_users_id</label>
            <input type='text' class='form-control' id='chats_users_id' name='chats_users_id' required>
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
