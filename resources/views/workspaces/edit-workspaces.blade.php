@extends('layouts.main')

@section('content')
<h1>Edit Workspaces</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <form action='{{ route('workspaces.update', $item->id) }}' method='POST'>
                        @csrf
                        
                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name='name' value='{{ $item->name }}' required>
                </div>
            
                        <button type='submit' class='btn btn-primary mt-3'>Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
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
                                    <form action='{{ url('/workspaces-paginate') }}' method='get'>
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
                            <form action='{{ url('/workspaces-filter') }}' method='get'>
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
                            <form action='{{ url('/workspaces-search') }}' method='GET'>
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
                                    <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse(App\Models\Workspaces::orderBy('id', 'desc')->paginate(10) as $item)
                                    <tr>
                                        <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $item->id }}'>
                                        </th>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href='{{ route('workspaces.show', $item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                            <a href='{{ route('workspaces.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                            <a href='{{ route('workspaces.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a>
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
        
            {{ App\Models\Workspaces::orderBy('id', 'desc')->paginate(10)->links('pagination::bootstrap-5') }}
        
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
        
                        $.post('/workspaces-delete-all-bulk-data', {
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
        
                        $.post('/workspaces-move-to-trash-all-bulk-data', {
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
