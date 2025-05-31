
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>Notifications</h1>
        </div>
        {{-- <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-types') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Types::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('types.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Types</button></a>
        </div> --}}
    </div>
    
    <div class='card'>
        <div class='card-body'>

            <div class='table-responsive'>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th>Notification</th>
                            <th>Date and Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (Auth::user()->role === "admin")
                            @forelse(App\Models\InternalNotification::orderBy('created_at', 'desc')->paginate(50) as $item)
                                <tr>
                                    <td>
                                        <a class="fw-bold text-decoration-none text-success" href="{{ url('/show-tasks/'.$item->tasks_id) }}">{{ $item->notification }}</a>
                                    </td>
                                    <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No Record...</td>
                                </tr>
                            @endforelse
                        @endif

                        @if (Auth::user()->role != "admin")
                            @php
                                // Get all task IDs assigned to the logged-in user
                                $assignedTaskIds = App\Models\Taskassignments::where('users_id', Auth::id())
                                                        ->pluck('tasks_id');
                            @endphp

                            @forelse(App\Models\InternalNotification::whereIn('tasks_id', $assignedTaskIds)
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(50) as $item)
                                <tr>
                                    <td>
                                        <a class="fw-bold text-decoration-none text-success" href="{{ url('/show-tasks/'.$item->tasks_id) }}">
                                            {{ $item->notification }}
                                        </a>
                                    </td>
                                    <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No Record...</td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ App\Models\InternalNotification::orderBy('created_at', 'desc')->paginate(50)->links('pagination::bootstrap-5') }}

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
@endsection
