
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>My On Site Items</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-onsites') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Onsites::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('onsites.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Onsites</button></a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5>Select A Site</h5>
                    <input type="text" class="form-control selectTechnicianSearchBar" placeholder="Search Site Name...">
                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                <tr>
                                    <td><a class="nav-link {{ request()->is('my-onsite-items/*') ? 'fw-bold text-success' : '' }}" href="{{ url('/my-onsite-items/'.Auth::user()->id) }}">All</a></td>
                                </tr>
                                @forelse(App\Models\Sites::all() as $_site)
                                    <tr class="selectTechnicianSearchBarResult">
                                        <td class='{{ request()->is('view-my-onsite-items-on-site/'.$_site->id) ? 'fw-bold text-success' : '' }}'><a class="nav-link" href="{{ url('/view-my-onsite-items-on-site/'.$_site->id) }}">{{ $_site->name }}</a></td>
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
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class='card'>
                <div class='card-body'>

                    <h5>From {{ $site->name }}</h5>

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
                        <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                            <form action='{{ url('/onsites-filter') }}' method='get'>
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
                            <form action='{{ url('/onsites-search') }}' method='GET'>
                                <div class='input-group'>
                                    <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search Site...'>
                                    <div class='input-group-append'>
                                        <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        
                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th>
                                    <th>Item ID</th>
                                    <th>Item</th>
                                    <th>Serial #</th>
                                    <th>Item Type</th>
                                    <th>Technician</th>
                                    <th>Site</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Last Modified By</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse($onsites as $item)
                                    <tr>
                                        <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                        </th>
                                        <td><b>{{ $item->items->itemId ?? "no data" }}</b></td>
                                        <td>{{ $item->items->name ?? "no data" }}</td>
                                        <td>
                                            @if($item->serial_numbers)
                                                @foreach(explode(',', $item->serial_numbers) as $serial_number)
                                                    <span class="custom-badge">{{ trim($serial_number) }}</span>
                                                @endforeach
                                            @else
                                                <span class="custom-badge no-serial">no serial numbers</span>
                                            @endif
                                        </td>
                                        <td><b class="text-success">{{ $item->types->name ?? "no data" }}</b></td>
                                        <td>{{ $item->technicians->name ?? "no data" }}</td>
                                        <td>
                                            <a class="text-primary fw-bold text-decoration-none" href="{{ url('/show-sites/'.$item->sites->id) }}">
                                                {{ $item->sites->name ?? "no data" }}
                                            </a>
                                        </td>
                                        <td><b class="text-success">{{ $item->quantity }}</b></td>
                                        <td>{{ $item->items->unit ?? "no data" }}</td>
                                        <td>
                                            {{ App\Models\User::where('id', $item->updated_by)->value('name') ?? "no name" }} ({{ App\Models\User::where('id', $item->updated_by)->value('role') ?? "no role" }})
                                        </td>
                                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->updated_at) }}</td>
                                        <td>
                                            {{-- <a href='{{ route('onsites.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a> --}}
                                            <a href='{{ route('onsites.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                            <a href='{{ route('onsites.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
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
        
            {{ $onsites->links('pagination::bootstrap-5') }}
        </div>
    </div>
    
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

                $.post('/onsites-delete-all-bulk-data', {
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

                $.post('/onsites-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $("meta[name='csrf-token']").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
