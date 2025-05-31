@extends('layouts.main')
@section('content')
<h1>Employee Details</h1>
<div ng-app="ShowEmployeePerformanceDetails">
   <div ng-controller="ShowEmployeePerformanceDetailsController">
      <div class="card">
         <div class="card-body">
            <div class="row">
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
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class='card'>
               <div class='card-body'>
                  <h5>Account Information</h5>
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
                           <td>
                              {{ $item->name }}
                              {{-- <button ng-click="handleClick('account_information', 'name', '{{ $item->name }}')" class="btn btn-secondary">Include</button> --}}
                           </td>
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
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal"><i class="fas fa-file-excel" ></i> Export Tasks</button>
                  <!-- Modal Structure -->
                  <div class="modal fade" id="exportModal" aria-labelledby="exportModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title" id="exportModalLabel">Select Date Range</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                           </div>
                           <div class="modal-body">
                              <form class="container mt-4" action="{{ url('export-employee-tasks-details/'.$item->id) }}" method="POST">
                                 @csrf
                                 <div class="row mb-3">
                                    <div class="col-md-6">
                                       <label for="from_date" class="form-label">From Date</label>
                                       <input type="date" name="from_date" id="from_date" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                       <label for="to_date" class="form-label">To Date</label>
                                       <input type="date" name="to_date" id="to_date" class="form-control">
                                    </div>
                                 </div>
                                 <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                    <i class='fas fa-file-excel'></i> Export
                                    </button>
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
         <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
               <div class="card">
                  <div class="card-body">
                     <div>
                        <div class='table-responsive'>
                           <table class="table">
                              <tr>
                                 <th>
                                    <h5>Personal Information</h5>
                                 </th>
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
                                 <th>
                                    <h5>Contact Information</h5>
                                 </th>
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
                                 <th>
                                    <h5>Employment Details</h5>
                                 </th>
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
                                 <th>
                                    <h5>Benefits & Insurance</h5>
                                 </th>
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
                     {{-- 
                  </div>
                  --}}
                  {{-- 
               </div>
               --}}
            </div>
         </div>
      </div>
   </div>
</div>
<a href='{{ route('technicians.index') }}' class='btn btn-primary'>Back to List</a>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   angular.module('ShowEmployeePerformanceDetails', [])
       .config(function ($interpolateProvider) {
           $interpolateProvider.startSymbol('[[')
           $interpolateProvider.endSymbol(']]')
       })
       .controller('ShowEmployeePerformanceDetailsController', function ($scope, $http) {
   
           $scope.selected = [];
   
           $scope.handleClick = function (type, name, value) {
               $scope.selected.push({
                   type: type,
                   name: name,
                   value: value
               });
               console.log($scope.selected)
           }
   
       })
</script>
@endsection