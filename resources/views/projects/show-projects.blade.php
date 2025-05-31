
@extends('layouts.main')

@section('content')
    <h1>Projects Details</h1>

    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4">
            <div class='card'>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table class='table'>
                            
                            <tr>
                                <th>Name</th>
                                <td>{{ $item->name }}</td>
                            </tr>
                        
                            <tr>
                                <th>Workspaces</th>
                                <td><a class="nav-link fw-bold text-primary" href="{{ url('show-workspaces/'.($item->workspaces->id ?? '')) }}">{{ ($item->workspaces->name ?? "no data") }}</a></td>
                            </tr>

                            <tr>
                                <th>Collaborators</th>
                                <td>
                                    @php
                                        $displayedUsers = [];
                                    @endphp

                                    @forelse (App\Models\Taskassignments::where('tasks_projects_id', $item->id)->get() as $collaborator)
                                        @if (!in_array($collaborator->users?->id, $displayedUsers))
                                            <img class="mb-2" src="{{ $collaborator->users?->profile_photo_path ? url('/storage/' . $collaborator->users?->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" height="40" width="40" style="border-radius: 50%;" alt="User Profile Photo">
                                            @php
                                                $displayedUsers[] = $collaborator->users?->id;
                                            @endphp
                                        @endif
                                    @empty
                                        <b>No Collaborators</b>
                                    @endforelse  
                                </td>
                            </tr>
            
                            <tr>
                                <th>Created At</th>
                                <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->updated_at) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class='card'>
                <div class='card-body'>
                    <h5>Create a task</h5>
                    <form action='{{ route('tasks.store') }}' method='POST'>
                        @csrf

                        <div class='form-group my-3'>
                            <label for="priority" style="font-weight: bold; display: block; margin-bottom: 10px;">Select Task Type</label>
                            <small>Scheduled option means the task will automatically reappear for the assignee every day at 8:00 AM, helping them stay on track with recurring work.</small> <br>
                            <select id="ampmPicker" class="form-control" name="isScheduled">
                                <option value="0">Not - Scheduled</option>
                                <option value="1">Scheduled</option>
                            </select>
                        </div>
                        
                        <div class='form-group my-3'>
                            <label for='name' style="font-weight: bold; display: block; margin-bottom: 10px;">Subject</label>
                            <input type='text' class='form-control' id='name' name='name' required>
                        </div>
                    
                        <div class='form-group my-3'>
                            <label for='name' style="font-weight: bold; display: block; margin-bottom: 10px;">Status</label>
                            <select name="status" class="form-control" id="">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div style="margin: 20px 0; font-family: Arial, sans-serif;">
                            <label for="priority" style="font-weight: bold; display: block; margin-bottom: 10px;">Select Priority</label>
                            <small>Marking a task as High priority will pause any other tasks the lead’s assignee is working on, so they can fully focus on this one.</small> <br>
                            <label style="margin-right: 20px;">
                                <input type="radio" id="priority" name="priority" value="low" checked style="margin-right: 5px;">
                                Low
                            </label>
                            <label>
                                <input type="radio" id="priority" name="priority" value="high" style="margin-right: 5px;">
                                High
                            </label>
                        </div>

                        <hr>
                        
                        <div style="margin: 20px 0; font-family: Arial, sans-serif;">
                            <label for="audience" style="font-weight: bold; display: block; margin-bottom: 10px;">Select Audience</label>
                            <small>Choosing Public lets other team members see the task, even if they’re not assigned to it. Private keeps it visible only to the people involved.</small> <br>
                            <label style="margin-right: 20px;">
                                <input type="radio" id="audience" name="audience" value="1" checked style="margin-right: 5px;">
                                Private
                            </label>
                            <label>
                                <input type="radio" id="audience" name="audience" value="0" style="margin-right: 5px;">
                                Public
                            </label>
                        </div>
                        

                        {{-- calendar --}}
                        <label for="audience" style="font-weight: bold; display: block; margin-bottom: 10px;">Select Deadline</label>
                        
                        <div class="calendar">
                            <div class="calendar-header">
                                <button type="button" class="btn btn-secondary prevMonth" >&#10094;</button>
                                <h2 id="month-year"></h2>
                                <button type="button" class="btn btn-secondary nextMonth" >&#10095;</button>
                            </div>
                            <div class="weekdays">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>
                            <div class="days" id="calendar-days"></div>
                        </div>
                        
                        <div class="clock-container">
                            <label>Select Time:</label>
                            <div class="time-selector">
                                <select id="hourPicker"></select> :
                                <select id="minutePicker"></select>
                                <select id="ampmPicker">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class='form-group my-3'>
                            <input type='datetime-local' class='form-control deadline' hidden id='deadline' name='deadline' required>
                        </div>
                    
                        <div class='form-group my-3'>
                            {{-- <label for='name'>Projects_id</label> --}}
                            <input type='text' class='form-control' id='projects_id' value="{{ $item->id }}" hidden name='projects_id' required>
                        </div>
                    
                        <div class='form-group my-3'>
                            {{-- <label for='name'>Projects_workspaces_id</label> --}}
                            <input type='text' class='form-control' id='projects_workspaces_id' value="{{ $item->workspaces->id ?? '' }}" hidden name='projects_workspaces_id' required>
                        </div>
            
                        <button type='submit' class='btn btn-primary mt-3'>Create</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class='card'>
                <div class='card-body'>
                    <h5>Tasks from {{ $item->name }}</h5>
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
        
                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            {{-- <thead>
                                <tr>
                                    <th scope='col'>
                                    <input type='checkbox' name='' id='' class='checkAll'>
                                    </th>
                                    <th>Name</th>
                                    <th>Assignees</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                    <th>Priority</th>
                                    <th>Scheduled</th>
                                    <th>Actions</th>
                                </tr>
                            </thead> --}}

                            <tbody>
                                @php
                                    $scheduledTasks = App\Models\Tasks::where('projects_id', $item->id)
                                        ->where('isScheduled', 1)
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);

                                    $nonScheduledTasks = App\Models\Tasks::where('projects_id', $item->id)
                                        ->where('isScheduled', 0)
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
                                @endphp

                                <br>

                                <b class="text-primary mt-5">Scheduled Tasks</b>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope='col'>
                                                <input type='checkbox' name='' id='' class='checkAll'>
                                                </th>
                                            <th>Task Name</th>
                                            <th>Assigned Users</th>
                                            <th>Status</th>
                                            <th>Deadline</th>
                                            <th>Priority</th>
                                            <th>Scheduled</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($scheduledTasks as $task)
                                            <tr>
                                                <th scope='row'>
                                                    <input type='checkbox' class='check' data-id='{{ $task->id }}'>
                                                </th>
                                                <td>{{ $task->name }}</td>
                                                <td>
                                                    @forelse (App\Models\Taskassignments::where('tasks_id', $task->id)->get() as $taskUser)
                                                        <img class="mb-2" src="{{ $taskUser->users?->profile_photo_path ? url('/storage/' . $taskUser->users?->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" height="40" width="40" style="border-radius: 50%;" alt="User Profile Photo">
                                                    @empty
                                                        <b>No Assignees</b>
                                                    @endforelse    
                                                </td>
                                                <td>
                                                    @if($task->status === 'completed')
                                                        <b class="text-success"><i class="fas fa-check"></i></b>
                                                    @else
                                                        <b class="text-danger"><i class="fas fa-times"></i></b>
                                                    @endif
                                                </td>
                                                <td>{{ Smark\Smark\Dater::humanReadableDateWithDay($task->deadline) }}</td>
                                                <td>
                                                    @if($task->priority === 'high')
                                                        <b class="text-danger"><i class="fas fa-arrow-up"></i></b>
                                                    @else
                                                        <b class="text-primary"><i class="fas fa-arrow-down"></i></b>
                                                    @endif
                                                </td>
                                                <td><b class="text-success">Yes</b></td>
                                                <td>
                                                    <a href='{{ route('tasks.show', $task->id) }}'><i class='fas fa-eye text-success'></i></a>
                                                    <a href='{{ route('tasks.edit', $task->id) }}'><i class='fas fa-edit text-info'></i></a>
                                                    <a href='{{ route('tasks.delete', $task->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8">No Scheduled Tasks...</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <b class="text-danger">Non-Scheduled Tasks</b>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Task Name</th>
                                            <th>Assigned Users</th>
                                            <th>Status</th>
                                            <th>Deadline</th>
                                            <th>Priority</th>
                                            <th>Scheduled</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($nonScheduledTasks as $task)
                                            <tr>
                                                <th scope='row'>
                                                    <input type='checkbox' class='check' data-id='{{ $task->id }}'>
                                                </th>
                                                <td>{{ $task->name }}</td>
                                                <td>
                                                    @forelse (App\Models\Taskassignments::where('tasks_id', $task->id)->get() as $taskUser)
                                                        <img class="mb-2" src="{{ $taskUser->users?->profile_photo_path ? url('/storage/' . $taskUser->users?->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" height="40" width="40" style="border-radius: 50%;" alt="User Profile Photo">
                                                    @empty
                                                        <b>No Collaborators</b>
                                                    @endforelse    
                                                </td>
                                                <td>
                                                    @if($task->status === 'completed')
                                                        <b class="text-success"><i class="fas fa-check"></i></b>
                                                    @else
                                                        <b class="text-danger"><i class="fas fa-times"></i></b>
                                                    @endif
                                                </td>
                                                <td>{{ Smark\Smark\Dater::humanReadableDateWithDay($task->deadline) }}</td>
                                                <td>
                                                    @if($task->priority === 'high')
                                                        <b class="text-danger"><i class="fas fa-arrow-up"></i></b>
                                                    @else
                                                        <b class="text-primary"><i class="fas fa-arrow-down"></i></b>
                                                    @endif
                                                </td>
                                                <td><b class="text-danger">No</b></td>
                                                <td>
                                                    <a href='{{ route('tasks.show', $task->id) }}'><i class='fas fa-eye text-success'></i></a>
                                                    <a href='{{ route('tasks.edit', $task->id) }}'><i class='fas fa-edit text-info'></i></a>
                                                    <a href='{{ route('tasks.delete', $task->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8">No Non-Scheduled Tasks...</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            {{ App\Models\Tasks::where('projects_id', $item->id)->orderBy('id', 'desc')->paginate(10)->links('pagination::bootstrap-5') }}
        
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
        </div>
    </div>

    <a href='{{ route('projects.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
