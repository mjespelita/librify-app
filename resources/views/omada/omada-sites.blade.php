
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Sites</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            {{-- <a href='{{ url('trash-sites') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Sites::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('sites.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Sites</button></a> --}}
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>

            <div class='table-responsive mt-5'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th>
                            <th>Name</th><th>Customer</th><th>Region</th><th>Timezone</th><th>Scenario</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($omadaSites as $item)
                            <tr>
                                <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ $item['siteId'] }}'>
                                </th>
                                <td>
                                    <a href="{{ url('/omada-sites-statistics', $item['siteId']) }}" class="nav-link text-primary fw-bold">{{ $item['name'] }}</a></td>
                                    <td>{{ $item['customerName'] }}</td>
                                    <td>{{ $item['region'] }}</td>
                                    <td>{{ $item['timezone'] }}</td>
                                    <td>{{ $item['scenario'] }}</td>
                                <td>
                                    {{-- <a href='{{ route('sites.show', $item->siteId) }}'><i class='fas fa-eye text-success'></i></a> --}}
                                    {{-- <a href='{{ route('sites.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                    <a href='{{ route('sites.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a> --}}
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

    {{-- {{ $sites->links('pagination::bootstrap-5') }} --}}

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
