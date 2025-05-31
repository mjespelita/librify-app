
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>My Sites</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-sites') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Sites::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('sites.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Sites</button></a>
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>
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
                            <form action='{{ url('/sites-paginate') }}' method='get'>
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
                    <form action='{{ url('/sites-filter') }}' method='get'>
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
                    <form action='{{ url('/sites-search') }}' method='GET'>
                        <div class='input-group'>
                            <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                            <div class='input-group-append'>
                                <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th>
                            <th>Name</th>
                            <th>Phonenumber <small>(Click to dial)</small></th>
                            <th>Items</th>
                            <th>Damages</th>
                            <th>Added By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($sites as $item)
                            <tr>
                                <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                </th>
                                <td>
                                    <a class="fw-bold text-decoration-none text-primary" href="{{ url('/show-sites/'.$item->id) }}">{{ $item->name }}</a>
                                </td>
                                <td>
                                    <a href="tel:{{ $item->phonenumber }}" class="text-primary text-decoration-none fw-bold">{{ $item->phonenumber }}</a>
                                </td>
                                <td>
                                    <b class="text-primary">{{ App\Models\Onsites::where('sites_id', $item->id)->count() }}</b>
                                </td>
                                <td>
                                    <b class="text-danger">{{ App\Models\Damages::where('sites_id', $item->id)->count() }}</b>
                                </td>
                                <td>
                                    {{ $item->users->name." (".$item->users->role.")" ?? "no data" }}
                                </td>
                                <td>
                                    <a href='{{ route('sites.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                    <a href='{{ route('sites.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                    <a href='{{ route('sites.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No Record...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> --}}

            <div class="row mt-3">
                @forelse($sites as $item)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card shadow-sm h-100 border-dark">
                            <div class="card-body">
                                <h5 class="card-title text-dark">{{ $item->name }}</h5>
                                <div>
                                    <iframe
                                        width="100%"
                                        height="300"
                                        src="{{ $item->google_map_link }}"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="d-flex flex-wrap gap-3 mb-2">
                                    <div>
                                        <i class="fas fa-phone text-secondary me-1"></i>
                                        <a href="tel:{{ $item->phonenumber }}" class="text-decoration-none">{{ $item->phonenumber }}</a>
                                    </div>
                                    <div>
                                        <i class="fas fa-box text-primary me-1"></i>
                                        <span class="text-primary fw-bold">{{ App\Models\Onsites::where('sites_id', $item->id)->count() }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        <span class="text-danger fw-bold">{{ App\Models\Damages::where('sites_id', $item->id)->count() }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user text-dark me-1"></i>
                                        {{ $item->users->name ?? "no data" }}
                                    </div>
                                </div>                                
            
                                <a href="{{ route('sites.show', $item->id) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('sites.edit', $item->id) }}" class="btn btn-outline-info btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('sites.delete', $item->id) }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No Sites Available.
                        </div>
                    </div>
                @endforelse
            </div>


        </div>
    </div>

    {{ $sites->links('pagination::bootstrap-5') }}

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

                $.post('/sites-delete-all-bulk-data', {
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

                $.post('/sites-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $("meta[name='csrf-token']").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
