
@extends('layouts.main')

@section('content')
    <h1>Damages Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ $item->id }}</td>
                    </tr>
                    
        <tr>
            <th>Items_id</th>
            <td>{{ $item->items_id }}</td>
        </tr>
    
        <tr>
            <th>Items_types_id</th>
            <td>{{ $item->items_types_id }}</td>
        </tr>
    
        <tr>
            <th>Technicians_id</th>
            <td>{{ $item->technicians_id }}</td>
        </tr>
    
        <tr>
            <th>Sites_id</th>
            <td>{{ $item->sites_id }}</td>
        </tr>
    
        <tr>
            <th>Quantity</th>
            <td>{{ $item->quantity }}</td>
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

    <a href='{{ route('damages.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
