
@extends('layouts.main')

@section('content')
    <h1>Messages Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ $item->id }}</td>
                    </tr>
                    
        <tr>
            <th>Message</th>
            <td>{{ $item->message }}</td>
        </tr>
    
        <tr>
            <th>Chats_id</th>
            <td>{{ $item->chats_id }}</td>
        </tr>
    
        <tr>
            <th>Chats_users_id</th>
            <td>{{ $item->chats_users_id }}</td>
        </tr>
    
        <tr>
            <th>Users_id</th>
            <td>{{ $item->users_id }}</td>
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

    <a href='{{ route('messages.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
