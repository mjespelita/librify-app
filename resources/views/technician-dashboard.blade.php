@extends('layouts.main')

@section('content')
    <h1>Dashboard</h1>
    <b>Hello, Technician - {{ Auth::user()->name }}</b>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">On Site Items</h5>
                    <h1>
                        <i class="fas fa-boxes"></i>
                        {{ App\Models\Onsites::where('technicians_id', Auth::user()->id)->sum('quantity') }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Damaged Items</h5>
                    <h1>
                        <i class="fas fa-house"></i>
                        {{ App\Models\Damages::where('technicians_id', Auth::user()->id)->sum('quantity') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <h4>Site Maps</h4>
    <div class="row">
        @forelse (App\Models\Sites::where('users_id', Auth::user()->id)->orderBy('id', 'desc')->get() as $item)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <b>{{ $item->name }}</b> <br>
                        <div>
                            <iframe
                                width="100%"
                                height="300"
                                src="{{ $item->google_map_link }}"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <a href="{{ url('show-sites/'.$item->id) }}">
                            <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4>No Sites....</h4>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection