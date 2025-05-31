
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All Workspaces</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-workspaces') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\Workspaces::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('workspaces.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add Workspaces</button></a>
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

            <style>
                .workspace-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 20px;
                    padding: 20px;
                }
            
                .workspace-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    padding: 15px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    border-left: 5px solid #198754;
                    transition: 0.3s;
                    position: relative;
                }
            
                .workspace-header {
                    font-weight: bold;
                    font-size: 18px;
                    margin-bottom: 10px;
                }
            
                .workspace-info {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 8px;
                }
            
                .workspace-participants {
                    display: flex;
                    gap: 5px;
                    flex-wrap: wrap;
                    margin-bottom: 8px;
                }
            
                .workspace-participants img {
                    height: 35px;
                    width: 35px;
                    border-radius: 50%;
                    border: 2px solid #ddd;
                }
            
                .workspace-actions {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    margin-top: auto;
                }
            
                .workspace-actions a {
                    text-decoration: none;
                    font-size: 18px;
                    color: #007bff;
                    transition: 0.3s;
                }
            
                .workspace-actions a:hover {
                    color: #0056b3;
                }
            </style>
            
            <div class="workspace-grid">
                @forelse($workspaces as $item)
                    <div class="workspace-card">
                        <div class="workspace-header">
                            <input type="checkbox" class="check" data-id="{{ $item->id }}">
                            {{ $item->name }}
                        </div>

                        <div class="workspace-info">
                            <b>Created On:</b> {{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->created_at) }}
                        </div>
            
                        <div class="workspace-info">
                            <b>Projects:</b> {{ App\Models\Projects::where('workspaces_id', $item->id)->count() }}
                        </div>
            
                        <div class="workspace-info">
                            <b>Tasks:</b> {{ App\Models\Tasks::where('projects_workspaces_id', $item->id)->count() }}
                        </div>
            
                        <div class="workspace-info">
                            <b>Participants:</b>
                            <div class="workspace-participants">
                                @forelse (App\Models\Workspaceusers::where('workspaces_id', $item->id)->get() as $workspaceUser)
                                    <a href="{{ url('/show-technicians/'.$workspaceUser->users_id) }}"><img src="{{ $workspaceUser->users->profile_photo_path ?? '' ? url('/storage/' . $workspaceUser->users->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" alt="User"></a>
                                @empty
                                    <span>No Participants</span>
                                @endforelse
                            </div>
                        </div>
            
                        <div class="workspace-actions">
                            <a href="{{ route('workspaces.show', $item->id) }}"><i class="fas fa-eye text-success"></i></a>
                            <a href="{{ route('workspaces.edit', $item->id) }}"><i class="fas fa-edit text-info"></i></a>
                            <a href="{{ route('workspaces.delete', $item->id) }}"><i class="fas fa-trash text-danger"></i></a>
                        </div>
                    </div>
                @empty
                    <p>No Workspaces Found</p>
                @endforelse
            </div>
            
    </div>

    {{ $workspaces->links('pagination::bootstrap-5') }}

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
@endsection
