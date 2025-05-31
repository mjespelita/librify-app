
@extends('layouts.main')

@section('content')
    <h1>Items Details</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table class='table'>
                            
                            <tr>
                                <th>Item Id</th>
                                <td><b>{{ $item->itemId }}</b></td>
                            </tr>
                        
                            <tr>
                                <th>Name</th>
                                <td>{{ $item->name }}</td>
                            </tr>
                        
                            <tr>
                                <th>Model</th>
                                <td>{{ $item->model }}</td>
                            </tr>
                        
                            <tr>
                                <th>Brand</th>
                                <td>{{ $item->brand }}</td>
                            </tr>
                        
                            <tr>
                                <th>Type</th>
                                <td><b class="text-success">{{ $item->types->name ?? "no data" }}</b></td>
                            </tr>
                        
                            <tr>
                                <th>Description</th>
                                <td>{{ $item->description }}</td>
                            </tr>
                        
                            <tr>
                                <th>Total Quantity</th>
                                <td><b class="text-primary">{{ $item->quantity }} {{ $item->unit }}(s)</b></td>
                            </tr>

                            <tr>
                                <th>In Warehouse Qty.</th>
                                <td>
                                    <div class="row">
                                        <div class="col-3">
                                            <b class="text-success">{{ $item->quantity - App\Models\Onsites::where('items_id', $item->id)->sum('quantity') - App\Models\Damages::where('items_id', $item->id)->sum('quantity') }} {{ $item->unit }}(s)</b>
                                        </div>
                                        <div class="col-9">
                                            @if($item->serial_numbers)
                                                @php
                                                    // Get all serial numbers from the Damages model using pluck for better performance
                                                    $damagedSerialNumbers = App\Models\Damages::pluck('serial_numbers')->toArray();
                                                    $damagedSerialNumbers = array_map(function($serial_numbers) {
                                                        return array_map('trim', explode(',', $serial_numbers));
                                                    }, $damagedSerialNumbers);
                                                    $damagedSerialNumbers = array_merge(...$damagedSerialNumbers);
                                                    
                                                    // Get all serial numbers from the Onsites model using pluck for better performance
                                                    $onsiteSerialNumbers = App\Models\Onsites::pluck('serial_numbers')->toArray();
                                                    $onsiteSerialNumbers = array_map(function($serial_numbers) {
                                                        return array_map('trim', explode(',', $serial_numbers));
                                                    }, $onsiteSerialNumbers);
                                                    $onsiteSerialNumbers = array_merge(...$onsiteSerialNumbers);

                                                    // Merge both damaged and onsite serial numbers and remove duplicates
                                                    $allExcludedSerialNumbers = array_merge($damagedSerialNumbers, $onsiteSerialNumbers);
                                                    $allExcludedSerialNumbers = array_map('trim', $allExcludedSerialNumbers); // Clean up extra spaces
                                                    $allExcludedSerialNumbers = array_unique($allExcludedSerialNumbers); // Remove duplicates
                                                @endphp
                                                
                                                @foreach(explode(',', $item->serial_numbers) as $serial_number)
                                                    @php
                                                        $serial_number = trim($serial_number);
                                                    @endphp
                                                    @if(!in_array($serial_number, $allExcludedSerialNumbers)) <!-- Check if the serial number is not in the damages or onsite list -->
                                                        <span class="custom-badge-in-warehouse">{{ $serial_number }}</span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="custom-badge no-serial">no serial numbers</span>
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>On-Site Qty.</th>
                                <td>
                                    <div class="row">
                                        <div class="col-3">
                                            <b class="text-primary">{{ App\Models\Onsites::where('items_id', $item->id)->sum('quantity') }} {{ $item->unit }}(s)</b>
                                        </div>
                                        <div class="col-9">
                                            @if($item->serial_numbers)
                                                @foreach(App\Models\Onsites::where('items_id', $item->id)->get() as $_item)
                                                    @foreach(explode(',', $_item->serial_numbers) as $serial_number)
                                                        <span class="custom-badge-in-onsite">
                                                            <a class="text-light text-decoration-none fw-bold" href="{{ url('/show-sites/'.$_item->sites_id) }}">
                                                                {{ $serial_number }}
                                                            </a>
                                                        </span>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <span class="custom-badge no-serial">no serial numbers</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th>Damaged Qty.</th>
                                <td>
                                    <div class="row">
                                        <div class="col-3">
                                            <b class="text-danger">{{ App\Models\Damages::where('items_id', $item->id)->sum('quantity') }} {{ $item->unit }}(s)</b>
                                        </div>
                                        <div class="col-9">
                                            @if($item->serial_numbers)
                                                @foreach(App\Models\Damages::where('items_id', $item->id)->get() as $_item)
                                                    @foreach(explode(',', $_item->serial_numbers) as $serial_number)
                                                        <span class="custom-badge-in-damage">
                                                            <a class="text-light text-decoration-none fw-bold" href="{{ url('/show-sites/'.$_item->sites_id) }}">
                                                                {{ $serial_number }}
                                                            </a>
                                                        </span>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <span class="custom-badge no-serial">no serial numbers</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
                            <tr>
                                <th>Unit</th>
                                <td>{{ $item->unit }}</td>
                            </tr>

                            {{-- <tr>
                                <th>Serial Numbers</th>
                                <td>
                                    @if($item->serial_numbers)
                                        @foreach(explode(',', $item->serial_numbers) as $serial_number)
                                            <span class="custom-badge">{{ trim($serial_number) }}</span>
                                        @endforeach
                                    @else
                                        <span class="custom-badge no-serial">no serial numbers</span>
                                    @endif
                                </td>
                            </tr> --}}
            
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
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <h5>On Site - {{ $item->name ?? "no data" }}</h5>
                    <div class='row'>
                        <div class='col-lg-6 col-md-6 col-sm-12 mt-2'>
                            <div class='row'>
                                <div class='col-8'>
                                    <form action='{{ url('/onsites-paginate') }}' method='get'>
                                        <div class='input-group'>
                                            <input type='number' name='paginate' class='form-control' placeholder='Paginate' value='{{ request()->get('paginate', 10) }}'>
                                            <div class='input-group-append'>
                                                <button class='btn btn-success' type='submit'><i class='fa fa-bars'></i></button>
                                            </div>
                                        </div>
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Type</th>
                                    <th>Serial #</th>
                                    <th>Technician</th>
                                    <th>Site</th>
                                    <th>Qty.</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse(App\Models\Onsites::where('items_id', $item->id)->paginate(10) as $_item)
                                    <tr>
                                        <td><b>{{ $_item->items->itemId ?? "no data" }}</b></td>
                                        <td><b class="text-success">{{ $_item->types->name ?? "no data" }}</b></td>
                                        <td>
                                            @if($_item->serial_numbers)
                                                @foreach(explode(',', $_item->serial_numbers) as $serial_number)
                                                    <span class="custom-badge">{{ trim($serial_number) }}</span>
                                                @endforeach
                                            @else
                                                <span class="custom-badge no-serial">no serial numbers</span>
                                            @endif
                                        </td>
                                        <td>{{ $_item->technicians->name ?? "no data" }}</td>
                                        <td>
                                            <a class="fw-bold text-primary text-decoration-none" href="{{ url('/show-sites/'.$_item->sites->id) }}">
                                                {{ $_item->sites->name ?? "no data" }}
                                            </a>
                                        </td>
                                        <td>{{ $_item->quantity }}</td>
                                        <td>{{ $_item->items->unit ?? "no data" }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>No Record...</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            {{ App\Models\Onsites::where('items_id', $item->id)->paginate(10)->links('pagination::bootstrap-5') }}
        </div>
    </div>
    <a href='{{ route('items.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
