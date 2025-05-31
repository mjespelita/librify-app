
@extends('layouts.main')

@section('content')
    <h1>Create a new itemlogs</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('itemlogs.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Items_id</label>
            <input type='text' class='form-control' id='items_id' name='items_id' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Reason</label>
            <input type='text' class='form-control' id='reason' name='reason' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Quantity</label>
            <input type='text' class='form-control' id='quantity' name='quantity' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
