
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Items</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-items') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Items::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('items.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Items</button></a>
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
                            <th>Model</th>
                            <th>Brand</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Total Quantity</th>
                            <th>In Warehouse</th>
                            <th>On Site</th>
                            <th>Damages</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                </th>
                                <td><b>{{ $item->itemId }}</b></td>
                                <td>
                                    <a class="fw-bold text-decoration-none text-primary" href="{{ url('/show-items/'.$item->id) }}">{{ $item->name }}</a>
                                </td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->brand }}</td>
                                <td><b class="text-success">{{ $item->types->name ?? "no data" }}</b></td>
                                <td>{{ Smark\Smark\Stringer::truncateString($item->description, 20) }}</td>
                                <td><b class="text-primary">{{ $item->quantity }}</b></td>
                                <td><b class="text-success">{{ $item->quantity - App\Models\Onsites::where('items_id', $item->id)->sum('quantity') - App\Models\Damages::where('items_id', $item->id)->sum('quantity') }}</b></td>
                                <td><b class="text-primary">{{ App\Models\Onsites::where('items_id', $item->id)->sum('quantity') }}</b></td>
                                <td><b class="text-danger">{{ App\Models\Damages::where('items_id', $item->id)->sum('quantity') }}</b></td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    <a href='{{ url('/create-add-item-quantity/'.$item->id) }}'><i class='fas fa-plus text-primary'></i></a>
                                    <a href='{{ route('items.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                    <a href='{{ route('items.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                    <a href='{{ route('items.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                    <a href='{{ url('view-add-item-quantity-logs', $item->id) }}'><i class='fas fa-bars text-primary'></i></a>
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

    {{ $items->links('pagination::bootstrap-5') }}

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
@endsection
