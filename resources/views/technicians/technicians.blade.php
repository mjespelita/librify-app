
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Employees</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-technicians') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Technicians::where('isTrash', '1')->count() }}</span></button></a>
            <button class='btn btn-success' data-bs-toggle="modal" data-bs-target="#bulkExportModal"><i class='fas fa-file-excel'></i> Bulk Export</button>
            <a href='{{ route('technicians.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Employee</button></a>

            <!-- Modal Structure -->
            <div class="modal fade" id="bulkExportModal" aria-labelledby="bulkExportModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="bulkExportModalLabel">Select an Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="container mt-4" action="{{ url('/bulk-export-employee-tasks/') }}" method="POST">
                            @csrf

                            <input type="text" class="form-control search-someone" placeholder="Search Someone...">

                            <h5 class="py-2">Select Date Range</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control">
                                </div>
                            </div>

                            <div class="searched">
                                {{-- dynamically add here based on searh query --}}
                            </div>

                            <div class="scrollable-user-list" style="max-height: 600px; overflow-y: auto;">
                                @php
                                    $roles = ['admin', 'warehouse_admin', 'office_admin', 'employee', 'technician'];
                                @endphp

                                @foreach ($roles as $role)
                                    @php
                                        $usersByRole = App\Models\User::where('role', $role)->get();
                                    @endphp

                                    @if ($usersByRole->count())
                                        <h5 class="mt-3 text-uppercase">{{ ucwords(str_replace('_', ' ', $role)) }}</h5>
                                        @foreach ($usersByRole as $user)
                                            <div class="mb-2 card p-2 d-flex flex-row align-items-center">
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input me-3">

                                                <img src="{{ $user->profile_photo_path ? url('storage/' . $user->profile_photo_path) : 'assets/profile_photo_placeholder.png' }}"
                                                     alt="{{ $user->name }}"
                                                     class="rounded-circle me-3"
                                                     width="50" height="50">

                                                <div style="text-align: left">
                                                    <strong>{{ $user->name }}</strong><br>
                                                    <small>{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach

                                @if (App\Models\User::count() === 0)
                                    <p>No users found.</p>
                                @endif
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class='fas fa-file-excel'></i> Export
                                </button>
                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </div>

    <div class='card'>
        <div class='card-body'>
            <h5>Administrators</h5>
            <div class='row'>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <div class='row'>
                        <div class='col-8'>
                            <form action='{{ url('/technicians-paginate') }}' method='get'>
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
                    <form action='{{ url('/technicians-filter') }}' method='get'>
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
                    <form action='{{ url('/technicians-search') }}' method='GET'>
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
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            {{-- <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th> --}}
                            <th>Image</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse(App\Models\User::where('role', 'admin')->orWhere('role', 'warehouse_admin')->orWhere('role', 'office_admin')->orderBy('id', 'desc')->paginate(10) as $item)
                            <tr>
                                {{-- <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                </th> --}}
                                <td><img src="{{ $item->profile_photo_path ? url('storage/' . $item->profile_photo_path) : 'assets/profile_photo_placeholder.png' }}" height="50" width="50" style="border-radius: 50%;" alt="User Profile Photo"></td>
                                <td>
                                    <a href="{{ url('show-technicians/'.$item->id) }}" class="fw-bold text-decoration-none text-primary">{{ $item->name }}</a>
                                </td>
                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $item->role)) }}
                                </td>
                                <td>
                                    <a class="fw-bold text-decoration-none text-primary" href="mailto: {{ $item->email }}">{{ $item->email }}</a>
                                </td>
                                <td>
                                    {{-- <a href='{{ route('technicians.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                    <a href='{{ route('technicians.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a> --}}
                                    <a href='{{ route('technicians.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
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

    {{ App\Models\User::where('role', 'admin')->orWhere('role', 'warehouse_admin')->orWhere('role', 'office_admin')->orderBy('id', 'desc')->paginate(10)->links('pagination::bootstrap-5') }}

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">


            <div class='card'>
                <div class='card-body'>
                    <h5>Technicians</h5>

                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    {{-- <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th> --}}
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($technicians as $item)
                                    <tr>
                                        {{-- <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                        </th> --}}
                                        <td><img src="{{ $item->profile_photo_path ? url('storage/' . $item->profile_photo_path) : 'assets/profile_photo_placeholder.png' }}" height="50" width="50" style="border-radius: 50%;" alt="User Profile Photo"></td>
                                        <td>
                                            <a href="{{ url('show-technicians/'.$item->id) }}" class="fw-bold text-decoration-none text-primary">{{ $item->name }}</a>
                                        </td>
                                        <td>
                                            <a class="fw-bold text-decoration-none text-primary" href="mailto: {{ $item->email }}">{{ $item->email }}</a>
                                        </td>
                                        <td>
                                            {{-- <a href='{{ route('technicians.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                            <a href='{{ route('technicians.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a> --}}
                                            <a href='{{ route('technicians.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
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

            {{ $technicians->links('pagination::bootstrap-5') }}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">


    <div class='card'>
        <div class='card-body'>
            <h5>Other Employees</h5>

            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            {{-- <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th> --}}
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse(App\Models\User::where('role', 'employee')->orderBy('id', 'desc')->paginate(10) as $item)
                            <tr>
                                {{-- <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                </th> --}}
                                <td><img src="{{ $item->profile_photo_path ? url('storage/' . $item->profile_photo_path) : 'assets/profile_photo_placeholder.png' }}" height="50" width="50" style="border-radius: 50%;" alt="User Profile Photo"></td>
                                <td>
                                    <a href="{{ url('show-technicians/'.$item->id) }}" class="fw-bold text-decoration-none text-primary">{{ $item->name }}</a>
                                </td>
                                <td>
                                    <a class="fw-bold text-decoration-none text-primary" href="mailto: {{ $item->email }}">{{ $item->email }}</a>
                                </td>
                                <td>
                                    {{-- <a href='{{ route('technicians.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                    <a href='{{ route('technicians.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a> --}}
                                    <a href='{{ route('technicians.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
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

    {{ App\Models\User::where('role', 'employee')->orderBy('id', 'desc')->paginate(10)->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
    <script>
        $(document).ready(function () {


            $(".search-someone").on("keyup", function () {
                const keyword = $(this).val().toLowerCase();
                const $resultsDiv = $(".searched");
                const $originalList = $(".scrollable-user-list");

                // Clear previous search results
                $resultsDiv.empty();

                if (keyword.trim() === "") {
                    // Show original list if no search
                    $originalList.show();
                    return;
                }

                // Hide original list when searching
                $originalList.hide();

                // Loop through user cards and search
                $(".scrollable-user-list .card").each(function () {
                    const $card = $(this);
                    const text = $card.text().toLowerCase();

                    if (text.includes(keyword)) {
                        // Clone the card and append to .searched
                        $resultsDiv.append($card.clone());
                    }
                });

                // If no results found
                if ($resultsDiv.children().length === 0) {
                    $resultsDiv.html("<p>No matching users found.</p>");
                }
            });
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

                $.post('/technicians-delete-all-bulk-data', {
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

                $.post('/technicians-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $("meta[name='csrf-token']").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
