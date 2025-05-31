
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Tasks</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-tasks') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Tasks::where('isTrash', '1')->count() }}</span></button></a>
            {{-- <a href='{{ route('tasks.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Tasks</button></a> --}}
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
                            <form action='{{ url('/tasks-paginate') }}' method='get'>
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
                    <form action='{{ url('/tasks-filter') }}' method='get'>
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
                    <form action='{{ url('/tasks-search') }}' method='GET'>
                        <div class='input-group'>
                            <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                            <div class='input-group-append'>
                                <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <style>
                .task-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 20px;
                    padding: 20px;
                }
            
                .task-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    padding: 15px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    border-left: 5px solid #007bff;
                    transition: 0.3s;
                    position: relative;
                }
            
                .task-header {
                    font-weight: bold;
                    font-size: 18px;
                    margin-bottom: 10px;
                }
            
                .task-info {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 8px;
                }
            
                .task-assignees {
                    display: flex;
                    gap: 5px;
                    flex-wrap: wrap;
                    margin-bottom: 8px;
                }
            
                .task-assignees img {
                    height: 35px;
                    width: 35px;
                    border-radius: 50%;
                    border: 2px solid #ddd;
                }
            
                .task-status {
                    font-weight: bold;
                    padding: 5px 10px;
                    border-radius: 5px;
                    display: inline-block;
                    margin-bottom: 8px;
                }
            
                .task-status.completed {
                    background: #198754;
                    color: white;
                }
            
                .task-status.pending {
                    background: #DC3545;
                    color: white;
                }
            
                .task-actions {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    margin-top: auto;
                }
            
                .task-actions a {
                    text-decoration: none;
                    font-size: 18px;
                    color: #007bff;
                    transition: 0.3s;
                }
            
                .task-actions a:hover {
                    color: #0056b3;
                }
            </style>
            
            <div class="task-grid">
                @if (Auth::user()->role === "admin" || Auth::user()->role === "warehouse_admin" || Auth::user()->role === "office_admin")
                    @forelse($tasks as $item)
                        <div class="task-card">
                            <div class="task-header">
                                <input type="checkbox" class="check" data-id="{{ $item->id }}">
                                {{ $item->name }}
                            </div>
            
                            <div style="display: flex; gap: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 8px; font-family: Arial, sans-serif; font-size: 14px; align-items: center; flex-wrap: wrap;">

                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-exclamation-circle" style="color: #e67e22;"></i>
                                    <span><strong>Priority:</strong>
                                        <span style="font-weight: bold; color: {{ $item->priority === 'high' ? '#e74c3c' : '#2ecc71' }};">
                                            {{ ucfirst($item->priority) }}
                                        </span>
                                    </span>
                                </div>
                            
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-clock" style="color: #3498db;"></i>
                                    <span><strong>Scheduled:</strong> {{ $item->isScheduled ? 'Yes' : 'No' }}</span>
                                </div>
                            
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-user-shield" style="color: #2ecc71;"></i>
                                    <span><strong>Audience:</strong> {{ $item->isPrivate ? 'Private' : 'Public' }}</span>
                                </div>
                            
                            </div>
                            

                            <hr>
                            

                            <div class="task-info">
                                <b>Project:</b> 
                                <a class="fw-bold text-primary" href="{{ url('show-projects/'.($item->projects->id ?? 'no-data')) }}">
                                    {{ $item->projects->name ?? 'No Project' }}
                                </a>
                            </div>
            
                            <div class="task-info">
                                <b>Workspace:</b> 
                                <a class="fw-bold text-primary" href="{{ url('show-workspaces/'.($item->workspaces->id ?? 'no-data')) }}">
                                    {{ $item->workspaces->name ?? 'No Workspace' }}
                                </a>
                            </div>

                            <div class="task-info">
                                <b>Created On:</b> 
                                {{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}
                            </div>
            
                            <div class="task-info">
                                <b>Assignees:</b>
                                <div class="task-assignees">
                                    @forelse (App\Models\Taskassignments::where('tasks_id', $item->id)->get() as $taskUser)
                                        <a href="{{ url('show-technicians/'.$taskUser->users_id) }}"><img src="{{ $taskUser->users?->profile_photo_path ? url('/storage/' . $taskUser->users->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" alt="User"></a>
                                    @empty
                                        <span>No Assignees</span>
                                    @endforelse
                                </div>
                            </div>
            
                            <div class="task-status {{ $item->status === 'completed' ? 'completed' : 'pending' }}">
                                <i class="fas {{ $item->status === 'completed' ? 'fa-check' : 'fa-hourglass' }}"></i>
                                {{ ucfirst($item->status ?? 'No Status') }}
                            </div>
            
                            <div class="task-actions">
                                <a href="{{ route('tasks.show', $item->id) }}"><i class="fas fa-eye text-success"></i></a>
                                <a href="{{ route('tasks.edit', $item->id) }}"><i class="fas fa-edit text-info"></i></a>
                                <a href="{{ route('tasks.delete', $item->id) }}"><i class="fas fa-trash text-danger"></i></a>
                            </div>
                        </div>
                    @empty
                        <p>No Tasks Found</p>
                    @endforelse
                @endif
            
                @if (Auth::user()->role === "technician")
                    @forelse(App\Models\Taskassignments::where('users_id', Auth::user()->id)->get() as $item)
                        <div class="task-card">
                            <div class="task-header">
                                <input type="checkbox" class="check" data-id="{{ $item->tasks->id ?? '' }}">
                                {{ $item->tasks->name ?? 'No Task' }}
                            </div>
            



                            <div class="task-info">
                                <b>Project:</b> 
                                <a class="fw-bold text-primary" href="{{ url('show-projects/'.($item->projects->id ?? 'no-data')) }}">
                                    {{ $item->projects->name ?? 'No Project' }}
                                </a>
                            </div>
            
                            <div class="task-info">
                                <b>Workspace:</b> 
                                <a class="fw-bold text-primary" href="{{ url('show-workspaces/'.($item->workspaces->id ?? 'no-data')) }}">
                                    {{ $item->workspaces->name ?? 'No Workspace' }}
                                </a>
                            </div>

                            <div class="task-info">
                                <b>Created On:</b> 
                                {{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}
                            </div>
            
                            <div class="task-status {{ $item->status === 'completed' ? 'completed' : 'pending' }}">
                                <i class="fas {{ $item->status === 'completed' ? 'fa-check' : 'fa-hourglass' }}"></i>
                                {{ ucfirst($item->status ?? 'No Status') }}
                            </div>
            
                            <div class="task-actions">
                                <a href="{{ route('tasks.show', $item->tasks->id ?? '') }}"><i class="fas fa-eye text-success"></i></a>
                                <a href="{{ route('tasks.edit', $item->tasks->id ?? '') }}"><i class="fas fa-edit text-info"></i></a>
                                <a href="{{ route('tasks.delete', $item->tasks->id ?? '') }}"><i class="fas fa-trash text-danger"></i></a>
                            </div>
                        </div>
                    @empty
                        <p>No Tasks Found</p>
                    @endforelse
                @endif
            </div>
            
        </div>
    </div>

    {{ $tasks->links('pagination::bootstrap-5') }}

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

                $.post('/tasks-delete-all-bulk-data', {
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

                $.post('/tasks-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $("meta[name='csrf-token']").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
