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
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Profile</h6>
              </div>
            </div>
            @foreach($data as $d)
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <div class="container mt-4 mb-4 p-3 d-flex justify-content-center"> 
                  <div class="card p-4"> 
                    <div class=" image d-flex flex-column justify-content-center align-items-center"> 
                      
          <form class="form-horizontal" action="{{route('update')}}" method="POST">
              @csrf
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <div class="span7" style="padding-left: 50px; margin-bottom: 20px;">
                  <div class="control-group ">
                      <label class="control-label">Name<span class="required">*</span></label>
                      <div class="controls">
                          <input id="name" name="name" class="span5"  placeholder="Enter team name..." type="text"  value="{{$d->name}}">
                      </div>
                  </div>
                </div>
                <div class="span7" style="padding-left: 50px; margin-bottom: 20px;">
                  <div class="control-group ">
                      <label class="control-label">Email<span class="required">*</span></label>
                      <div class="controls">
                          <input id="email" name="email" class="span5"  placeholder="Enter address..." type="email"  value="{{$d->email}}">
                      </div>
                  </div>
                </div>
                <div class="span7" style="padding-left: 50px; margin-bottom: 20px;">
                  <div class="control-group ">
                      <label class="control-label">Password<span class="required">*</span></label>
                      <div class="controls">
                          <input id="pword" name="pword" class="span5"  placeholder="Password" type="password" required>
                      </div>
                  </div>
                </div>

                <div class="span7" style="padding-left: 50px; margin-bottom: 20px;">
                  <div class="control-group ">
                      <label class="control-label">Confirm Password<span class="required">*</span></label>
                      <div class="controls">
                          <input id="cpword" name="cpword" class="span5"  placeholder="Confirm Password" type="password" required> 
                      </div>
                  </div>
                </div>

                  <div style="padding-left: 50px">
                    <div class="span7">
                        <button id="submit-button" type="submit" class="btn btn-info" name="submit-button" value="Confirm">Update</button>
                        <a type="button"  class="btn btn-danger" name="action" href="{{route('team')}}" >Cancel</a>
                    </div>
                  </div>
              </form>

                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>
  </main>
@endsection