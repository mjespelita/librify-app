
@extends('layouts.main')

@section('content')
    <h1>Are you sure you want to delete this tasktimelogs?</h1>

    <form action='{{ route('tasktimelogs.destroy', $item->id) }}' method='GET'>
        @csrf
        @method('DELETE')
        <button type='submit' class='btn btn-danger'>Yes, Delete</button>
        <a href='{{ route('tasktimelogs.index') }}' class='btn btn-secondary'>Cancel</a>
    </form>
@endsection
