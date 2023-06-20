@extends('layouts.dash')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


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
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Handling Team Information</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <div class="container mt-4 mb-4 p-3 d-flex justify-content-center"> 
                  <div class="card p-4"> 
                    <div class=" image d-flex flex-column justify-content-center align-items-center"> <button class="btn btn-secondary"> 
                      @if(count($data))
                        @foreach($data as $d)
                        <span class="name mt-3">Team Name</span>
                        <br> 
                        <span class="idd">{{$d->team_name}}</span> 
                        <br>
                        <hr>
                          <div class="d-flex flex-row justify-content-center align-items-center gap-2"> <span class="idd1">Address</span> 
                            <br>

                          </div> 
                            <span class="idd">{{$d->address}}</span> 
                           <br>
                        <hr>
                          <div class="d-flex flex-row justify-content-center align-items-center gap-2"> <span class="idd1">Contact #</span> 
                            <br>
                        
                          </div> 
                          <span class="idd">{{$d->contact_number}}</span> 
                           <br>
                        <hr>
                          <div class="d-flex flex-row justify-content-center align-items-center mt-3">  
                                      @if($d->status == 0)
                                      <a href="{{route('cases')}}" data-toggle="modal" class="btn btn-primary">Available</a>

                                      <a></a>
                                      @elseif($d->status == 1)
                                      <a data-toggle="modal" class="badge badge-sm bg-gradient-warning">Team is Dispatched</a>
                                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">
                                          Resolve Case
                                      </button>
                                      
                                    <!-- Modal -->
                                    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to resolve the case?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger" id="confirmDelete">Resolve</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                      @endif
                          </div>
                                                    <!-- Button to trigger the modal -->
                          

                          <script>
                            document.getElementById("confirmDelete").addEventListener("click", function() {
                                window.location.href = "{{ route('resolve', $d->id) }}";
                            });

                          </script>
 
                          <script>
                            function confirmResolve() {
                                if (window.confirm("Is the team finish with their emergency?")) {
                                    // User clicked the "OK" button
                                    window.location.href = "{{ route('resolve', $d->id) }}";
                                } else {
                                    // User clicked the "Cancel" button
                                    return false;
                                }
                            }
                        </script>
                        @endforeach
                      @endif
                      
                      </div> 

                      
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
@endsection