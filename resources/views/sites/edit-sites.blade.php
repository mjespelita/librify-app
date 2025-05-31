
@extends('layouts.main')

@section('content')
    <h1>Edit site</h1>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class='card'>
                <div class='card-body'>
                    <form action='{{ route('sites.update', $item->id) }}' method='POST'>
                        @csrf
                        
                <div class='form-group'>
                    <label for='name'>Name</label>
                    <input type='text' class='form-control searchBoxInput' id='searchBox' name='name' required value="{{ $item->name }}">
                </div>
            
                <div class='form-group'>
                    <label for='name'>Phonenumber</label> <br>
                    <small>(Ex: 09xxx... 11 characters)</small>
                    <input type='number' class='form-control' id='phonenumber' name='phonenumber' required value="{{ $item->phonenumber }}">
                </div>
        
                <div class='form-group'>
                    <input type='text' class='form-control mapCoordinatesData' id='google_map_link' name='google_map_link' hidden value="{{ $item->google_map_site }}">
                </div>

                <div class="form-group">
                    <label for='name'>Current Site Map</label> <br>
                    <iframe
                        width="300"
                        height="300"
                        src="{{ $item->google_map_link }}"
                        allowfullscreen>
                    </iframe>
                </div>
            
                        <button type='submit' class='btn btn-primary mt-3'>Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h3>Preview Site</h3>
                    
                    <!-- Search Box -->
                    {{-- <input type="text" id="searchBox" class="form-control mb-3 searchBoxInput" placeholder="Enter a place to search"> --}}

                    <!-- Google Maps Iframe -->
                    <div id="mapContainer">
                        <iframe
                            id="googleMap"
                            width="100%" height="500" frameborder="0" style="border:0"
                            src="{{ $item->google_map_link }}"
                            allowfullscreen>
                        </iframe>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
