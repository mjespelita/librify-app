
@extends('layouts.main')

@section('content')
    <h1>Create a new Damages</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <form action='{{ route('damages.store') }}' method='POST'>
                        @csrf
                        
                <div class='form-group'>
                    <label for='name'>Select Item</label>
                    <select name="items_id" class="form-control" id="">
                        @forelse ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @empty
                            <option value="0">No record</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="serialNumberToggle">
                    <label class="form-check-label" for="serialNumberToggle" id="serialNumberLabel">No Serial Number</label>
                </div>

                <!-- Tag input field and button -->
                <div class="input-group mb-3 serialNumberCustomToggle" style="display: none">
                    <input type="text" class="form-control" id="tagInput" placeholder="Enter a serial number">
                    <button class="btn btn-outline-secondary" type="button" id="addTagBtn">Add Serial Number</button>
                </div>

                <!-- Optional Dropdown for Searchable Serial Numbers -->
                <ul id="serialDropdown" class="dropdown-menu" style="max-height: 200px; overflow-y: auto; display: none;">
                    <!-- Options will be populated dynamically via JS -->
                </ul>

                <!-- Display the tags -->
                <div id="tagContainer" class="mb-3">
                    <!-- Tags will appear here -->
                </div>

                <!-- Hidden input field where tags are stored (separated by commas) -->
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="serial_numbers" name="serial_numbers" readonly hidden>
                </div>
            
                {{-- if authenticated user is an admin --}}
            
                @if (Auth::user()->role === 'admin')
                    <div class='form-group mb-3'>
                        <label for='name'>Select Technician</label>
                        <select name="technicians_id" class="form-control" id="">
                            @forelse ($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @empty
                                <option value="0">No record</option>
                            @endforelse
                        </select>
                    </div>
                @endif

                {{-- if authenticated user is a technician --}}

                @if (Auth::user()->role === 'technician')
                    <div class='form-group mb-3'>
                        <label for='name'>Technician (You)</label>
                        <select name="technicians_id" class="form-control" id="">
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                        </select>
                    </div>
                @endif

                {{-- if authenticated user is an admin --}}
            
                {{-- @if (Auth::user()->role === 'admin') --}}
                    <div class='form-group mb-3'>
                        <label for='name'>Select A Site</label>
                        <select name="sites_id" class="form-control" id="">
                            @forelse ($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @empty
                                <option value="0">No record</option>
                            @endforelse
                        </select>
                    </div>
                {{-- @endif --}}

                {{-- if authenticated user is a technician --}}

                {{-- @if (Auth::user()->role === 'technician')
                    <div class='form-group mb-3'>
                        <label for='name'>Select Your Site</label>
                        <select name="sites_id" class="form-control" id="">
                            @forelse (App\Models\Sites::where('users_id', Auth::user()->id)->get() as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @empty
                                <option value="0">No record</option>
                            @endforelse
                        </select>
                    </div>
                @endif --}}
            
                <div class='form-group'>
                    <label for='name'>Quantity</label>
                    <input type='text' class='form-control' id='quantity' name='quantity' required>
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
                            <h5>All Items</h5>
                        </div>
                        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
                            <a href='{{ url('trash-items') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Items::where('isTrash', '1')->count() }}</span></button></a>
                            <a href='{{ route('items.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Items</button></a>
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
                                    <form action='{{ url('/items-paginate') }}' method='get'>
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
                            <form action='{{ url('/items-filter') }}' method='get'>
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
                            <form action='{{ url('/items-search') }}' method='GET'>
                                <div class='input-group'>
                                    <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                                    <div class='input-group-append'>
                                        <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        
                    <div class='table-responsive mt-4'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th>
                                    <th>Item Id</th>
                                    <th>Name</th>
                                    <th>Type</th>       
                                    <th>Total Quantity</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse(App\Models\Items::paginate(10) as $site)
                                    <tr>
                                        <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $site->id }}'>
                                        </th>
                                        <td><b>{{ $site->itemId }}</b></td><td>{{ $site->name }}</td>
                                        <td><b class="text-success">{{ $site->types->name ?? "no data" }}</b></td>
                                        <td><b class="text-primary">{{ $site->quantity }}</b></td>
                                        <td>{{ $site->unit }}</td>
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
        
            {{ App\Models\Items::paginate(10)->links('pagination::bootstrap-5') }}
        
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
        
                        $.post('/items-delete-all-bulk-data', {
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
        
                        $.post('/items-move-to-trash-all-bulk-data', {
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
