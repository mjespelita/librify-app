
@extends('layouts.main')

@section('content')
    <h1>Edit Tasks</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('tasks.update', $item->id) }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' value='{{ $item->name }}' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Status</label>
            <select name="status" class="form-control" id="">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class='form-group my-3 my-3'>
            <label for='name'>Select Priority</label> <br> <br>
            <input type='radio' id='priority' name='priority' value="low" checked> Low
            <input type='radio' id='priority' name='priority' value="high"> High
        </div>

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
                        
        <div class="calendar">
            <div class="calendar-header">
                <button type="button" class="btn btn-secondary prevMonth">&#10094;</button>
                <h2 id="month-year"></h2>
                <button type="button" class="btn btn-secondary nextMonth">&#10095;</button>
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
        
        <div class='form-group'>
            <input type='datetime-local' class='form-control deadline' id='deadline' name='deadline' required>
        </div>

        {{-- end calendar --}}
    
        <div class='form-group'>
            {{-- <label for='name'>Projects_id</label> --}}
            <input type='text' class='form-control' hidden id='projects_id' name='projects_id' value='{{ $item->projects_id }}' required>
        </div>
    
        <div class='form-group'>
            {{-- <label for='name'>Projects_workspaces_id</label> --}}
            <input type='text' class='form-control' hidden id='projects_workspaces_id' name='projects_workspaces_id' value='{{ $item->projects_workspaces_id }}' required>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
