@extends('layouts.dash')
  
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">             </label>
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
            @if(count($notif))
              <a href="javascript:;" class="bell-icon" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer" style="color: red;"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="{{route('cases')}}">
                    <div class="d-flex py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New Reported Cases </span> for the past 24hours
                        </h6>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            @else
            <a href="javascript:;" class="bell-icon" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="{{route('cases')}}">
                    <div class="d-flex py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">No new notification</span> for the past 24hours
                        </h6>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            @endif
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row min-vh-80">
        <div class="col-12">
          <div class="card h-100">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h5 class="text-white text-capitalize ps-3">Badet Map</h5>
              </div>
            </div>
            <div class="card-body">
              <div id="map" class="mt-5 min-height-500"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
   <script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
    <script>
       
        let map, markers = [];
        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {
            map = L.map('map').setView([14.599512, 120.984222], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            @foreach($data as $c)
              var marker = L.marker([{!!$c->latitude!!}, {!!$c->longitude!!}]).addTo(map);
              var popup = marker.bindPopup('<b>{!!$c->aname!!}</b><br />{!!$c->description!!}');
              popup.openPopup();
            @endforeach
                     
            map.on('click', mapClicked);
            initMarkers();
        }
        initMap();


        function generateMarker(data, index) {
            return L.marker(data.position, {
                    draggable: data.draggable
                })
                .on('click', (event) => markerClicked(event, index))
                .on('dragend', (event) => markerDragEnd(event, index));
        }

        /* ------------------------- Handle Map Click Event ------------------------- */
        function mapClicked($event) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ------------------------ Handle Marker Click Event ----------------------- */
        function markerClicked($event, index) {
            console.log(map);
            console.log($event.latlng.lat, $event.latlng.lng);
        }

        /* ----------------------- Handle Marker DragEnd Event ---------------------- */
        function markerDragEnd($event, index) {
            console.log(map);
            console.log($event.target.getLatLng());
        }
    </script>
@endsection