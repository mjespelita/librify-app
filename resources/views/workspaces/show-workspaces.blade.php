
@extends('layouts.main')

@section('content')
    <h1>Workspaces Details</h1>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class='card'>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table class='table'>
                            
                            <tr>
                                <th>Name</th>
                                <td><b>{{ $item->name }}</b></td>
                            </tr>

                            <tr>
                                <th>Participants</th>
                                <td>
                                    @forelse (App\Models\Workspaceusers::where('workspaces_id', $item->id)->get() as $workspaceUser)
                                        <a href="{{ route('workspaceusers.destroy', $workspaceUser->id) }}"><img class="mb-2" src="{{ $workspaceUser->users?->profile_photo_path ? url('/storage/' . $workspaceUser->users?->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" height="40" width="40" style="border-radius: 50%;" alt="User Profile Photo"></a>
                                    @empty
                                        <b>No Participants</b>
                                    @endforelse

                                    <form class="mt-4" action='{{ route('workspaceusers.store') }}' method='POST'>
                                        @csrf
                                        
                                        <div class='form-group'>
                                            <label for='name'>Select Participant</label>
                                            <select class="form-control" name="users_id" required>
                                                @php
                                                    $addedUsers = App\Models\WorkspaceUsers::where('workspaces_id', $item->id)->pluck('users_id')->toArray();
                                                @endphp
                                            
                                                {{-- @forelse (App\Models\User::whereNot('id', Auth::user()->id)->whereNotIn('id', $addedUsers)->orderBy('id', 'desc')->get() as $user) --}}
                                                @forelse (App\Models\User::whereNotIn('id', $addedUsers)->orderBy('id', 'desc')->get() as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->name }}
                                                    </option>
                                                @empty
                                                    <option value="">No users available...</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    
                                        <div class='form-group'>
                                            {{-- <label for='name'>Workspaces_id</label> --}}
                                            <input type='text' class='form-control' value="{{ $item->id }}" id='workspaces_id' name='workspaces_id' hidden required>
                                        </div>
                            
                                        <button type='submit' class='btn btn-primary mt-3'>Add Participant</button>
                                    </form>
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
                    <h5>Create new project</h5>
                    <form action='{{ route('projects.store') }}' method='POST'>
                        @csrf
                        
                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control' id='name' name='name' required>
                </div>
            
                <div class='form-group'>
                    {{-- <label for='name'>Workspaces_id</label> --}}
                    <input type='text' class='form-control' id='workspaces_id' name='workspaces_id' value="{{ $item->id }}" hidden required>
                </div>
            
                        <button type='submit' class='btn btn-primary mt-3'>Create</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class='card'>
                <div class='card-body'>
                    <h5>Projects of {{ $item->name }}</h5>
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
                                    <form action='{{ url('/projects-paginate') }}' method='get'>
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
                            <form action='{{ url('/projects-filter') }}' method='get'>
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
                            <form action='{{ url('/projects-search') }}' method='GET'>
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
                                    <th>Collaborators</th>
                                    <th>Tasks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @forelse(App\Models\Projects::where('workspaces_id', $item->id)->orderBy('id', 'desc')->paginate(10) as $project)
                                    <tr>
                                        <th scope='row'>
                                            <input type='checkbox' name='' id='' class='check' data-id='{{ $project->id }}'>
                                        </th>
                                        <td>{{ $project->name }}</td>
                                        <td>
                                            @php
                                                $displayedUsers = [];
                                            @endphp
                                        
                                            @forelse (App\Models\Taskassignments::where('tasks_projects_id', $project->id)->get() as $projectUser)
                                                @if (!empty($projectUser->users?->id) && !in_array($projectUser->users->id, $displayedUsers))
                                                    <img class="mb-2" 
                                                        src="{{ $projectUser->users?->profile_photo_path ? url('/storage/' . $projectUser->users->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" 
                                                        height="40" 
                                                        width="40" 
                                                        style="border-radius: 50%;" 
                                                        alt="User Profile Photo">
                                                    @php
                                                        $displayedUsers[] = $projectUser->users->id;
                                                    @endphp
                                                @endif
                                            @empty
                                                <b>No Collaborators</b>
                                            @endforelse
                                        
                                        </td>
                                        <td>{{ App\Models\Tasks::where('projects_id', $project->id)->count() }}</td>
                                        <td>
                                            <a href='{{ route('projects.show', $project->id) }}'><i class='fas fa-eye text-success'></i></a>
                                            <a href='{{ route('projects.edit', $project->id) }}'><i class='fas fa-edit text-info'></i></a>
                                            <a href='{{ route('projects.delete', $project->id) }}'><i class='fas fa-trash text-danger'></i></a>
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
        
            {{ App\Models\Projects::orderBy('id', 'desc')->paginate(10)->links('pagination::bootstrap-5') }}
        
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
        
                        $.post('/projects-delete-all-bulk-data', {
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
        
                        $.post('/projects-move-to-trash-all-bulk-data', {
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

    <a href='{{ route('workspaces.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
