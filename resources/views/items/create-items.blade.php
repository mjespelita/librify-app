
@extends('layouts.main')

@section('content')
    <h1>Create a new items</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <form action='{{ route('items.store') }}' method='POST'>
                        @csrf

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
        
                        <!-- Display the tags -->
                        <div id="tagContainer" class="mb-3">
                            <small class="serialNumberCustomToggle" style="display: none">(Click serial number to remove)</small>
                            <!-- Tags will appear here -->
                        </div>
            
                        <div class='form-group'>
                            <label for='name'>Name</label>
                            <input type='text' class='form-control' id='name' name='name' required>
                        </div>
                    
                        <div class='form-group'>
                            <label for='name'>Model</label>
                            <input type='text' class='form-control' id='model' name='model' required>
                        </div>
                    
                        <div class='form-group'>
                            <label for='name'>Brand</label>
                            <input type='text' class='form-control' id='brand' name='brand' required>
                        </div>
                    
                        <div class='form-group'>
                            <label for='name'>Type</label>
                            <select name="types_id" class="form-control" id="">
                                @forelse ($types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @empty
                                    <option value="0" disabled>No Types Available</option>
                                @endforelse
                            </select>
                        </div>
                    
                        <div class='form-group'>
                            <label for='name'>Description</label>
                            <textarea name="description" class="form-control" id="" cols="30" rows="10" required></textarea>
                        </div>
                    
                        <div class='form-group'>
                            <label for='name'>Initial Quantity</label>
                            <input type='number' class='form-control' id='quantity' name='quantity' required>
                        </div>
                    
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
            
                        <button type='submit' class='btn btn-primary mt-3'>Create</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            
            <div class='card'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='col-lg-6 col-md-6 col-sm-12'>
                            <h1>All Item Types</h1>
                        </div>
                        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
                            <a href='{{ url('trash-types') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Types::where('isTrash', '1')->count() }}</span></button></a>
                            <a href='{{ route('types.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Types</button></a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                            <div class='row'>
                                <div class='col-4'>
                                    <button type='button' class='btn btn-outline-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        Action
                                    </button>
                                    <div class='dropdown-menu'>
                                        <a class='dropdown-item bulk-move-to-trash' href='#'>
                                            <i class='fa fa-trash'></i> Move to Trash
                                        </a>
                                        <a class='dropdown-item bulk-delete' href='#'>
                                            <i class='fa fa-trash'></i> <span class='text-danger'>Delete Permanently</span> <br> <small>(this action cannot be undone)</small>
                                        </a>
                                    </div>
                                </div>
                                <div class='col-8'>
                                    <form action='{{ url('/types-paginate') }}' method='get'>
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
                        <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                            <form action='{{ url('/types-filter') }}' method='get'>
                                <div class='input-group'>
                                    <input type='date' class='form-control' id='from' name='from' required> 
                                    <b class='pt-2'>- to -</b>
                                    <input type='date' class='form-control' id='to' name='to' required>
                                    <div class='input-group-append'>
                                        <button type='submit' class='btn btn-primary form-control'><i class='fas fa-filter'></i></button>
                                    </div>
                                </div>
                                @csrf
                            </form>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                            <!-- Search Form -->
                            <form action='{{ url('/types-search') }}' method='GET'>
                                <div class='input-group'>
                                    <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                                    <div class='input-group-append'>
                                        <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        
                    <div class='table-responsive'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse(App\Models\Types::paginate(10) as $item)
                                    <tr>
                                        <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                        </th>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href='{{ route('types.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                            <a href='{{ route('types.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                            <a href='{{ route('types.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                        </td>
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
        
            {{ App\Models\Types::paginate(10)->links('pagination::bootstrap-5') }}
        
            <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
            <script>
                $(document).ready(function () {
        
                    // checkbox
        
                    var click = false;
                    $('.checkAll').on('click', function() {
                        $('.check').prop('checked', !click);
                        click = !click;
                        this.innerHTML = click ? 'Deselect' : 'Select';
                    });
        
                    $('.bulk-delete').click(function () {
                        let array = [];
                        $('.check:checked').each(function() {
                            array.push($(this).attr('data-id'));
                        });
        
                        $.post('/types-delete-all-bulk-data', {
                            ids: array,
                            _token: $("meta[name='csrf-token']").attr('content')
                        }, function (res) {
                            window.location.reload();
                        })
                    })
        
                    $('.bulk-move-to-trash').click(function () {
                        let array = [];
                        $('.check:checked').each(function() {
                            array.push($(this).attr('data-id'));
                        });
        
                        $.post('/types-move-to-trash-all-bulk-data', {
                            ids: array,
                            _token: $("meta[name='csrf-token']").attr('content')
                        }, function (res) {
                            window.location.reload();
                        })
                    })
                });
            </script>

        </div>
    </div>

    

    

@endsection
