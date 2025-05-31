
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Customers</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            {{-- <a href='{{ url('trash-customers') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Customers::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('customers.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Customers</button></a> --}}
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>

            <div class='table-responsive mt-5'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            {{-- <th>Uploaded By</th> --}}
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($omadaCustomers as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ empty($item->description) ? "no description" : $item->description }}</td>
                                {{-- <td>{{ $item->users->name ?? "no data" }}</td> --}}
                                <td>
                                    {{-- <a href='{{ route('customers.show', $item->customerId) }}'><i class='fas fa-eye text-success'></i></a> --}}
                                    {{-- <a href='{{ route('customers.edit', $item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                    <a href='{{ route('customers.delete', $item->id) }}'><i class='fas fa-trash text-danger'></i></a> --}}
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

    {{-- {{ $omadaCustomers->links('pagination::bootstrap-5') }} --}}

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

                $.post('/customers-delete-all-bulk-data', {
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

                $.post('/customers-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $("meta[name='csrf-token']").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
