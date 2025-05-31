@extends('layouts.main')
@section('content')
<!-- Button to trigger the modal -->
<button type="button" style="float: right" class="btn btn-primary mb-2 searchSerialNumberButton" data-bs-toggle="modal" data-bs-target="#exampleModal">
<i class="fas fa-search"></i> Search Serial Number
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Search Serial Number</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- Modal Body -->
         <div class="modal-body">
            <div class="form-group">
               <input type="text" class="form-control searchedSerialNumberInput" placeholder="Serial Number">
            </div>
            <!-- Clickable List -->
            <ul class="list-group searchedSerialNumber" style="overflow-x: scroll; height: 50vh">
               @php
               // Fetch all items from the Items model
               $allItems = \App\Models\Items::all();
               // Fetch the serial numbers from the Onsites and Damages tables with their respective sites_ids
               $onsites = \App\Models\Onsites::select('serial_numbers', 'sites_id')->get();
               $damages = \App\Models\Damages::select('serial_numbers', 'sites_id')->get();
               // Initialize an array to hold all serial numbers with their links
               $serialNumbersWithLinks = [];
               // Process Onsites serial numbers and their corresponding sites_ids
               foreach ($onsites as $onsite) {
               $itemSerialNumbers = array_map('trim', explode(',', $onsite->serial_numbers));
               foreach ($itemSerialNumbers as $serialNumber) {
               $serialNumbersWithLinks[] = [
               'serial_number' => $serialNumber,
               'link' => '/show-sites/' . $onsite->sites_id
               ];
               }
               }
               // Process Damages serial numbers and their corresponding sites_ids
               foreach ($damages as $damage) {
               $itemSerialNumbers = array_map('trim', explode(',', $damage->serial_numbers));
               foreach ($itemSerialNumbers as $serialNumber) {
               $serialNumbersWithLinks[] = [
               'serial_number' => $serialNumber,
               'link' => '/show-sites/' . $damage->sites_id
               ];
               }
               }
               // Process Items serial numbers
               foreach ($allItems as $item) {
               $itemSerialNumbers = array_map('trim', explode(',', $item->serial_numbers));
               foreach ($itemSerialNumbers as $serialNumber) {
               if (isset($item->sites_id)) {
               $serialNumbersWithLinks[] = [
               'serial_number' => $serialNumber,
               'link' => '/show-sites/' . $item->sites_id
               ];
               } else {
               // If no sites_id in Items, use the item id in the link
               $serialNumbersWithLinks[] = [
               'serial_number' => $serialNumber,
               'link' => '/show-items/' . $item->id
               ];
               }
               }
               }
               // Remove duplicates by serial_number
               $serialNumbersWithLinks = array_map("unserialize", array_unique(array_map("serialize", $serialNumbersWithLinks)));
               // Sort by serial_number in alphabetical order
               usort($serialNumbersWithLinks, function ($a, $b) {
               return strcmp($a['serial_number'], $b['serial_number']);
               });
               @endphp
               @foreach ($serialNumbersWithLinks as $serial)
               <li class="list-group-item searchedSerialNumberResult">
                  <a href="{{ $serial['link'] }}" class="text-decoration-none fw-bold text-primary">
                  {{ $serial['serial_number'] }}
                  </a>
               </li>
               @endforeach
            </ul>
         </div>
         <!-- Modal Footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
         </div>
      </div>
   </div>
</div>
<h1>Dashboard</h1>
<b>Hello, Admin</b>
<div class="row">
   <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card">
         <div class="card-body">
            <h5>In used Items</h5>
            <div id="barChartOnsite"></div>
         </div>
      </div>
   </div>
   <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="card">
         <div class="card-body">
            <h5>Damaged Items</h5>
            <div id="barChartDamages"></div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-4">
      <div class="card">
         <div class="card-body">
            <h5 class="card-title">Items Overview</h5>
            <div id="pie-chart-item-overview"></div>
         </div>
      </div>
   </div>
   <div class="col-md-8">
      <div class="row">
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Total Sites</h5>
                  <h1>
                     <i class="fas fa-house"></i>
                     {{ App\Models\Sites::count() }}
                  </h1>
                  <a href="{{ url('sites') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Total Employees</h5>
                  <h1>
                     <i class="fas fa-users"></i>
                     {{ App\Models\User::whereNot('role', 'admin')->count() }}
                  </h1>
                  <a href="{{ url('technicians') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Item Types</h5>
                  <h1>
                     <i class='fas fa-cogs'></i>
                     {{ App\Models\Types::count() }}
                  </h1>
                  <a href="{{ url('types') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Total Warehouse Items</h5>
                  <h1>
                     <i class='fas fa-cogs'></i>
                     {{ App\Models\Items::sum('quantity') - App\Models\Onsites::sum('quantity') - App\Models\Damages::sum('quantity') }}
                  </h1>
                  <a href="{{ url('items') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Total On Site Item Count</h5>
                  <h1>
                     <i class='fas fa-cogs'></i>
                     {{ App\Models\Onsites::sum('quantity') }}
                  </h1>
                  <a href="{{ url('onsites') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <div class="card">
               <div class="card-body">
                  <h5 class="card-title">Total Damage Item Count</h5>
                  <h1>
                     <i class='fas fa-exclamation-triangle'></i>
                     {{ App\Models\Damages::sum('quantity') }}
                  </h1>
                  <a href="{{ url('damages') }}">
                  <button class="btn btn-outline-secondary"><i class="fas fa-eye"></i> View Details</button>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<h4>Site Maps</h4>
<div class="row">
   @forelse (App\Models\Sites::orderBy('id', 'desc')->get() as $item)
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
