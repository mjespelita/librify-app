
@extends('layouts.main')

@section('content')
    <h1>Edit Itemlogs</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('itemlogs.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Items_id</label>
            <input type='text' class='form-control' id='items_id' name='items_id' value='{{ $item->items_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Reason</label>
            <input type='text' class='form-control' id='reason' name='reason' value='{{ $item->reason }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Quantity</label>
            <input type='text' class='form-control' id='quantity' name='quantity' value='{{ $item->quantity }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
