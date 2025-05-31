
@extends('layouts.main')

@section('content')
    <h1>Add Item Quantity - {{ $item->name }}</h1>
    <b>Current Quantity: {{ $item->quantity }}</b>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <form action='{{ url('add-item-quantity-process/'.$item->id) }}' method='POST'>
                        @csrf

                        @if ($item->serial_numbers != "")
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="serialNumberToggle">
                                <label class="form-check-label" for="serialNumberToggle" id="serialNumberLabel">No Serial Number</label>
                            </div>
            
                            <!-- Tag input field and button -->
                            <div class="input-group mb-3 serialNumberCustomToggle" style="display: none">
                                <input type="text" class="form-control" id="tagInput" placeholder="Enter a serial number">
                                <button class="btn btn-outline-secondary" type="button" id="addTagBtn">Add Serial Number</button>
                            </div>

                            <!-- Hidden input field where tags are stored (separated by commas) -->
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="serial_numbers" name="serial_numbers" readonly hidden>
                            </div>

                            <!-- Optional Dropdown for Searchable Serial Numbers -->
                            <ul id="serialDropdown" class="dropdown-menu" style="max-height: 200px; overflow-y: auto; display: none;">
                                <!-- Options will be populated dynamically via JS -->
                            </ul>
            
                            <!-- Display the tags -->
                            <div id="tagContainer" class="mb-3">
                                <!-- Tags will appear here -->
                            </div>
                    
                            <div class='form-group'>
                                <input type='number' class='form-control' id='quantity' name='quantity' required hidden>
                            </div>

                            <div class='form-group'>
                                <label for='name'>Reason</label>
                                <textarea name="reason" class="form-control" id="" cols="30" rows="10" required></textarea>
                            </div>
                            <button type='submit' class='btn btn-primary mt-3'>Submit</button>
                        @else
                            <div class='form-group'>
                                <label for='name'>Number</label>
                                <input type='number' class='form-control' id='quantity' name='quantity' required>
                            </div>

                            <div class='form-group'>
                                <label for='name'>Reason</label>
                                <textarea name="reason" class="form-control" id="" cols="30" rows="10" required></textarea>
                            </div>

                            <button type='submit' class='btn btn-primary mt-3'>Submit</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>

                    <h5>Item Details</h5>

                    <div class='table-responsive'>
                        <table class='table'>
                            
                <tr>
                    <th>Item Id</th>
                    <td>{{ $item->itemId }}</td>
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
                    <td>{{ $item->types->name ?? "no data" }}</td>
                </tr>
            
                <tr>
                    <th>Description</th>
                    <td>{{ $item->description }}</td>
                </tr>
            
                <tr>
                    <th>Quantity</th>
                    <td>{{ $item->quantity }}</td>
                </tr>
            
                <tr>
                    <th>Unit</th>
                    <td>{{ $item->unit }}</td>
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
        </div>
    </div>

@endsection
