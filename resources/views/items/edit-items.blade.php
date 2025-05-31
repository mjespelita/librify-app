
@extends('layouts.main')

@section('content')
    <h1>Edit Items</h1>

    <div class='card'>
        <div class='card-body'>    
            <form action='{{ route('items.update', $item->id) }}' method='POST'>
                @csrf
                

                @if ($item->serial_numbers != "")
                    <!-- Display the tags -->
                    <div id="tagContainer" class="mb-3">
                        @foreach(explode(',', $item->serial_numbers) as $serial_number)
                            <span class="badge bg-success me-2 mb-2" style="cursor: pointer;">{{ trim($serial_number) }}</span>
                        @endforeach
                    </div>
                    
                @endif

                <!-- Hidden input field where tags are stored (separated by commas) -->
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="serial_numbers" name="serial_numbers" readonly hidden value="{{ $item->serial_numbers }}">
                </div>
                
                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name='name' value='{{ $item->name }}' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Model</label>
                    <input type='text' class='form-control' id='model' name='model' value='{{ $item->model }}' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Brand</label>
                    <input type='text' class='form-control' id='brand' name='brand' value='{{ $item->brand }}' required>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Type</label>
                    <select name="types_id" class="form-control" id="">
                        @forelse ($types as $_item)
                            @if ($_item->id == $item->types_id)
                                <option value="{{ $_item->id }}" selected>{{ $_item->name }}</option>
                            @else
                                <option value="{{ $_item->id }}">{{ $_item->name }}</option>
                            @endif
                        @empty
                            <option value="0" disabled>No Types Available</option>
                        @endforelse
                    </select>
                </div>
            
                <div class='form-group'>
                    <label for='name'>Description</label>
                    <textarea name="description" class="form-control" id="" cols="30" rows="10" required>{{ $item->description }}</textarea>
                </div>

                @if ($item->serial_numbers != null)
                    <div class="form-group">
                        {{-- <label for="name">Quantity</label> --}}
                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $item->quantity }}" required hidden>
                    </div>
                @else
                    <div class="form-group">
                        <label for="name">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $item->quantity }}" required>
                    </div>
                @endif
            
                <div class='form-group'>
                    <label for='name'>Unit</label>
                    <select name="unit" class="form-control" id="">
                        <option value="pcs">Pieces</option> <!-- For individual items, routers, APs, etc. -->
                        <option value="meters">Meters</option> <!-- For cables (fiber optic, Ethernet) -->
                        <option value="ft">Feet</option> <!-- For cables (fiber optic, Ethernet) -->
                        <option value="kg">Kilograms</option> <!-- For equipment weight, like routers or switches -->
                        <option value="lbs">Pounds</option> <!-- For equipment weight, like routers or switches -->
                        <option value="amps">Amperes</option> <!-- For power consumption of devices like routers or switches -->
                        <option value="watt">Watts</option> <!-- For power consumption of network devices -->
                        <option value="dbm">dBm</option> <!-- For signal strength of wireless devices (APs, antennas) -->
                        <option value="MHz">MHz</option> <!-- For frequency of wireless equipment (routers, antennas) -->
                        <option value="GHz">GHz</option> <!-- For wireless frequency, e.g., Wi-Fi routers -->
                        <option value="slots">Slots</option> <!-- For network devices that support slots for additional modules -->
                        <option value="ports">Ports</option> <!-- For tracking the number of ports on devices like switches and routers -->
                        <option value="cm">Centimeters</option> <!-- For small connectors or components (e.g., cable connectors) -->
                        <option value="lbs">Pounds</option> <!-- For item weight like routers or large hardware -->
                        <option value="units">Units</option> <!-- For specific types of items being tracked (e.g., routers, switches) -->
                    </select>
                </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
