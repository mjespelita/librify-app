
@extends('layouts.main')

@section('content')
    <h1>Edit Deployedtechnicians</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('deployedtechnicians.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Sites_id</label>
            <input type='text' class='form-control' id='sites_id' name='sites_id' value='{{ $item->sites_id }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Technicians_id</label>
            <input type='text' class='form-control' id='technicians_id' name='technicians_id' value='{{ $item->technicians_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
