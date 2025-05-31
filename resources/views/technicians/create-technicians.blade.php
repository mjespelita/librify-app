
@extends('layouts.main')

@section('content')
    <h1>Create a new employee</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('technicians.store') }}' method='POST'>
                @csrf
                
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class='form-control' id='name' name='name' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Email</label>
            <input type='text' class='form-control' id='email' name='email' required>
        </div>
    
        <div class='form-group'>
            <label for='name'>Password</label>
            <input type='password' class='form-control' id='password' name='password' required>
        </div>

        <div class='form-group'>
            <label for='name'>Select Role</label>
            <select name="role" id="" class="form-control">
                <option value="technician">Technician</option>
                <option value="employee">Employee</option>
                <option value="admin">Super Administrator</option>
                <option value="warehouse_admin">Warehouse Administrator</option>
                <option value="office_admin">Office Administrator</option>
            </select>
        </div>
    
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
