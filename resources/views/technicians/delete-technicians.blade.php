
@extends('layouts.main')

@section('content')
    <h1>Are you sure you want to delete this employee?</h1>

    <form action='{{ route('technicians.destroy', $item->id) }}' method='GET'>
        @csrf
        @method('DELETE')
        <button type='submit' class='btn btn-danger'>Yes, Delete</button>
        <a href='{{ route('technicians.index') }}' class='btn btn-secondary'>Cancel</a>
    </form>
@endsection
