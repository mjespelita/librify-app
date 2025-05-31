
@extends('layouts.main')

@section('content')
    <h1>Employee Details</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table class='table'>
                            <tr>
                                <th>Profile Photo</th>
                                <td>
                                    <img src="{{ $item->profile_photo_path ? url('storage/' . $item->profile_photo_path) : '/assets/profile_photo_placeholder.png' }}" height="200" style="border-radius: 50%; border: 3px #F69639 solid;" width="200" alt="User Profile Photo">
                                </td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $item->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $item->email }}</td>
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
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">

                    <div style="display: flex; justify-content: space-between;">
                        <h5>
                            Employee Task Summary for <span id="selected-month-year"></span>: Pending & Completed
                        </h5>
    
                        <a href="{{ url('/show-employee-performance-details/'.$item->id) }}"><button class="btn btn-success">Expand</button></a>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="monthSelect">Select Month</label>
                            <select id="monthSelect" class="form-select">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="yearSelect">Select Year</label>
                            <select id="yearSelect" class="form-select"></select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button id="loadDataBtn" class="btn btn-primary">Load Data</button>
                        </div>
                    </div>
            
                    <div id="lineChartEmployeeDetailsInitialLoad"></div>
                    <div id="lineChartEmployeeDetailsFilteredMonthYear" style="display: none"></div>
                </div>
            </div>
            
            
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">

                    <div>
                        <div style="text-align: right">
                            <button  class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Update Employee Profile</button>
                        </div>

                        <div class='table-responsive'>
                            <table class="table">
                                <tr>
                                    <th><h5>Personal Information</h5></th>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td>{{ ($item->lastname ?? '') . ', ' . ($item->firstname ?? '') . ' ' . ($item->middlename ?? '') ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($item->dateofbirth) ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $item->gender ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nationality</th>
                                    <td>{{ $item->nationality ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <td>{{ $item->maritalstatus ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th><h5>Contact Information</h5></th>
                                </tr>
                                <tr>
                                    <th>Residential Address</th>
                                    <td>{{ $item->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td>{{ $item->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Emergency Contact</th>
                                    <td>{{ $item->emergencycontact ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Employee ID / Staff Number</th>
                                    <td>{{ $item->employeeid ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th><h5>Employment Details</h5></th>
                                </tr>
                                <tr>
                                    <th>Job Title / Designation</th>
                                    <td>{{ $item->jobtitle ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Department / Team</th>
                                    <td>{{ $item->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th><h5>Benefits & Insurance</h5></th>
                                </tr>
                                <tr>
                                    <th>Social Security Number</th>
                                    <td>{{ $item->sss ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Pag-IBIG Member ID</th>
                                    <td>{{ $item->pagibig ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Philhealth ID</th>
                                    <td>{{ $item->philhealth ?? 'N/A' }}</td>
                                </tr>
                            </table>                            
                        </div>

                        <!-- Modal Structure -->
                        <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Employee Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="container mt-4" action="{{ url('/update-employee-profile/'.$item->id) }}" method="POST">
                                        <!-- Section: Personal Information -->
                                        <fieldset class="border p-3 mb-4">
                                            <legend class="w-auto px-2">Personal Information</legend>
                                    
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="firstName" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="firstName" name="firstname" value="{{ $item->firstname ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="middleName" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control" id="middleName" name="middlename" value="{{ $item->middlename ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="lastName" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="lastName" name="lastname" value="{{ $item->lastname ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="dob" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" id="dob" name="dateofbirth" value="{{ $item->dateofbirth ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select class="form-select" id="gender" name="gender">
                                                        <option value="male" {{ $item->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ $item->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                    
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="nationality" class="form-label">Nationality</label>
                                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $item->nationality ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="maritalStatus" class="form-label">Marital Status</label>
                                                    <select class="form-select" id="maritalStatus" name="maritalstatus">
                                                        <option value="single" {{ $item->maritalstatus == 'single' ? 'selected' : '' }}>Single</option>
                                                        <option value="married" {{ $item->maritalstatus == 'married' ? 'selected' : '' }}>Married</option>
                                                        <option value="divorced" {{ $item->maritalstatus == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                        <option value="widowed" {{ $item->maritalstatus == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </fieldset>
                                    
                                        <!-- Section: Contact Information -->
                                        <fieldset class="border p-3 mb-4">
                                            <legend class="w-auto px-2">Contact Information</legend>
                                    
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Residential Address</label>
                                                <input type="text" class="form-control" id="address" name="address" value="{{ $item->address ?? '' }}">
                                            </div>
                                    
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="phone" class="form-label">Mobile Number</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $item->phone ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="emergencyContact" class="form-label">Emergency Contact</label>
                                                    <input type="text" class="form-control" id="emergencyContact" name="emergencycontact" value="{{ $item->emergencycontact ?? '' }}">
                                                </div>
                                            </div>
                                        </fieldset>
                                    
                                        <!-- Section: Employment Details -->
                                        <fieldset class="border p-3 mb-4">
                                            <legend class="w-auto px-2">Employment Details</legend>
                                    
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="employeeId" class="form-label">Employee ID / Staff Number</label>
                                                    <input type="text" class="form-control" id="employeeId" name="employeeid" value="{{ $item->employeeid ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="jobTitle" class="form-label">Job Title / Designation</label>
                                                    <input type="text" class="form-control" id="jobTitle" name="jobtitle" value="{{ $item->jobtitle ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="department" class="form-label">Department / Team</label>
                                                    <input type="text" class="form-control" id="department" name="department" value="{{ $item->department ?? '' }}">
                                                </div>
                                            </div>
                                        </fieldset>
                                    
                                        <!-- Section: Benefits & Insurance -->
                                        <fieldset class="border p-3 mb-4">
                                            <legend class="w-auto px-2">Benefits & Insurance</legend>
                                    
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="sss" class="form-label">Social Security Number</label>
                                                    <input type="text" class="form-control" id="sss" name="sss" value="{{ $item->sss ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="pagibig" class="form-label">Pag-IBIG Member ID</label>
                                                    <input type="text" class="form-control" id="pagibig" name="pagibig" value="{{ $item->pagibig ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="philhealth" class="form-label">Philhealth ID</label>
                                                    <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{ $item->philhealth ?? '' }}">
                                                </div>
                                            </div>
                                        </fieldset>
                                    
                                        @csrf
                                    
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                    

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                     <div class='row'>
                        <div class='col-lg-12 col-md-12 col-sm-12 mt-2'>
                            <h5>{{ $item->name }}'s Tasks</h5>
                            <b>Jump To Date.</b>
                            <form action='{{ url('/admin-my-tasks-filter') }}' method='get'>
                                <div class='input-group'>
                                    <input type='date' class='form-control' id='from' name='from' required> 
                                    <b class='pt-2'>- to -</b>
                                    <input type='date' class='form-control' id='to' name='to' required>
                                    <input type='number' class='form-control' id='to' name='user' required value="{{ $item->id }}" hidden>
                                    <div class='input-group-append'>
                                        <button type='submit' class='btn btn-primary form-control'><i class='fas fa-filter'></i></button>
                                    </div>
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
        
                    <br>
        
                    <h5>Today</h5>
                    <div class='table-responsive'>
                        <div class="task-grid">
                            @forelse($taskAssignments as $taskAssignment)
                                @php
                                    $isEmpty = empty($taskAssignment->tasks->name) || empty($taskAssignment->tasks->status) ||
                                               empty($taskAssignment->projects->name) || empty($taskAssignment->workspaces->name);
                                @endphp
                        
                                @if(!$isEmpty) 
                                    {{-- @if ($taskAssignment->tasks->status === 'pending') --}}
                                        <div class="task-card {{ $taskAssignment->tasks->status === 'completed' ? 'completed' : 'pending' }}">
                                            <div class="task-header">{{ $taskAssignment->tasks->name ?? 'Untitled Task' }}</div>
                            
                                            <div class="task-info">
                                                <b>Status:</b> {{ ucfirst($taskAssignment->tasks->status ?? 'N/A') }}
                                            </div>
                            
                                            <div class="task-info">
                                                <b>Project:</b> {{ $taskAssignment->projects->name ?? 'No Project' }}
                                            </div>
                            
                                            <div class="task-info">
                                                <b>Workspace:</b> {{ $taskAssignment->workspaces->name ?? 'No Workspace' }}
                                            </div>
        
                                            <div class="task-info">
                                                <b>Created On:</b> {{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($taskAssignment->created_at) }}
                                            </div>
                            
                                            <div class="task-actions">
                                                {{-- <input type="checkbox" class="check" data-id="{{ $taskAssignment->id }}"> --}}
                                                <div>
                                                    <a href="{{ route('tasks.show', $taskAssignment->tasks->id ?? '#') }}">
                                                        <button class="btn btn-outline-secondary">
                                                            <i class="fas fa-eye text-secondary"></i> View
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- @endif --}}
                                @endif
                            @empty
                                <p>No tasks found.</p>
                            @endforelse
                        </div>
                    </div>
        
                    {{ $taskAssignments->links('pagination::bootstrap-5') }}
        
                    <h5>Unfinished Tasks</h5>
                    <div class='table-responsive'>
                        <div class="task-grid">
                            @forelse($unfinished_taskAssignments as $unfinished_taskAssignment)
                                @php
                                    $isEmpty = empty($unfinished_taskAssignment->tasks->name) || empty($unfinished_taskAssignment->tasks->status) ||
                                               empty($unfinished_taskAssignment->projects->name) || empty($unfinished_taskAssignment->workspaces->name);
                                @endphp
                        
                                @if(!$isEmpty) 
                                    <div class="task-card {{ $unfinished_taskAssignment->tasks->status === 'completed' ? 'completed' : 'pending' }}">
                                        <div class="task-header">{{ $unfinished_taskAssignment->tasks->name ?? 'Untitled Task' }}</div>
                        
                                        <div class="task-info">
                                            <b>Status:</b> {{ ucfirst($unfinished_taskAssignment->tasks->status ?? 'N/A') }}
                                        </div>
                        
                                        <div class="task-info">
                                            <b>Project:</b> {{ $unfinished_taskAssignment->projects->name ?? 'No Project' }}
                                        </div>
                        
                                        <div class="task-info">
                                            <b>Workspace:</b> {{ $unfinished_taskAssignment->workspaces->name ?? 'No Workspace' }}
                                        </div>
        
                                        <div class="task-info">
                                            <b>Created On:</b> {{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($unfinished_taskAssignment->created_at) }}
                                        </div>
                        
                                        <div class="task-actions">
                                            {{-- <input type="checkbox" class="check" data-id="{{ $unfinished_taskAssignment->id }}"> --}}
                                            <div>
                                                <a href="{{ route('tasks.show', $unfinished_taskAssignment->tasks->id ?? '#') }}">
                                                    <button class="btn btn-outline-secondary">
                                                        <i class="fas fa-eye text-secondary"></i> View
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p>No tasks found.</p>
                            @endforelse
                        </div>
                    </div>
        
                    {{ $unfinished_taskAssignments->links('pagination::bootstrap-5') }}
                {{-- </div>
            </div> --}}
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
            <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
            <script>
                $(document).ready(function () {
                    new Vue({
                        el: '#employee-profile',
                        /*html*/
                        template: `

                        `,
                        data: {
                        },
                        computed: {
                        },
                        methods: {

                        },
                        mounted() {

                        },
                    });
                });
            </script>
        </div>
    </div>

    <a href='{{ route('technicians.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
