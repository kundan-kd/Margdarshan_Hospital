<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <link rel="icon" type="image/png" href="{{asset('backend/assets/images/favicon.png')}}" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/remixicon.css')}}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/bootstrap.min.css')}}">
  <!-- Apex Chart css -->
  {{-- <link rel="stylesheet" href="{{asset('backend/assets/css/lib/apexcharts.css')}}"> --}}
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/dataTables.min.css')}}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor-katex.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor.atom-one-dark.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor.quill.snow.css')}}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/flatpickr.min.css')}}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/full-calendar.css')}}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/jquery-jvectormap-2.0.5.css')}}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/magnific-popup.css')}}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/slick.css')}}">
  <!-- prism css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/prism.css')}}">
  <!-- file upload css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/file-upload.css')}}">
  
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/audioplayer.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">

 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 {{-- for date month format --}}
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css"> 
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

  {{-----------common css for common custom styling in all pages------------}}
  <link rel="stylesheet" href="{{asset('backend/assets/css/custom/common.css')}}">
  {{-- add extra css for the particular files --}}
  @yield('extra-css') 
</head>
  <body>
    {{-- toast alert included --}}
    @include('backend.alert.toast')
<aside class="sidebar">
  <button type="button" class="sidebar-close-btn">
    <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
  </button>
  <div>
    <a href="{{route('auth.dashboard')}}" class="sidebar-logo">
      <img src="{{asset('backend/assets/images/logo.png')}}" alt="site logo1" class="light-logo">
      <img src="{{asset('backend/assets/images/logo.png')}}" alt="site logo2" class="dark-logo">
      <img src="{{asset('backend/assets/images/favicon.png')}}" alt="site logo3" class="logo-icon">
    </a>
  </div>
  <div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">
      <li class="mb-1">
        <a href="{{route('auth.dashboard')}}">
      <i class="ri-home-4-line"></i>
          <span>Dashboard</span>
        </a>
      </li>
      {{-- <li class="sidebar-menu-group-title">Application</li> --}}
      <li class="dropdown mb-1">
        <a href="javascript:void(0)">
          <i class="ri-calendar-event-line"></i>
          <span>Appointment</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('appointment.index')}}"><i class="ri-calendar-fill"></i></i>Book Appointment</a>
          </li>
          <li>
            <a href="{{route('patient.index')}}"><i class="ri-user-2-line"></i>Patients</a>
          </li>
        </ul>
      </li> 

      <li class="dropdown mb-1">
        <a href="javascript:void(0)">
          <i class="ri-capsule-line"></i>
          <span>Pharmacy</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('billing.index')}}"><i class="ri-bill-line"></i>Billing</a>
          </li>
          <li>
            <a href="{{route('medicine.index')}}"><i class="ri-capsule-fill"></i>Medicine</a>
          </li>
          <li>
            <a href="{{route('purchase.index')}}"><i class="ri-shopping-bag-4-line"></i>Purchase</a>
          </li>
        </ul>
      </li>
      <li class="mb-1">
        <a href="{{route('opd-out.index')}}">
          <i class="ri-stethoscope-line"></i>
          <span>OPD-Out</span>
        </a>
      </li>
      <li class="mb-1">
        <a href="{{route('ipd-in.index')}}">
          <i class="ri-stethoscope-line"></i>
          <span>IPD-In Patients</span>
        </a>
      </li>
      <li class="mb-1">
        <a href="{{route('emergency.index')}}">
          <i class="ri-hospital-line"></i>
          <span>Emergency</span>
        </a>
      </li>
      <li class="dropdown mb-1">
        <a href="javascript:void(0)">
         <i class="ri-settings-3-line"></i>
          <span>Master</span> 
        </a>
        <ul class="sidebar-submenu">
          <li>
            <a href="{{route('usertype.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>User Type</a>
          </li>
          <li>
            <a href="{{route('department.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Department</a>
          </li>
          <li>
            <a href="{{route('user.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>User</a>
          </li>
          <li>
            <a href="{{route('medicine-category.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Medicine Category</a>
          </li>
          <li>
            <a href="{{route('company.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Company</a>
          </li>
          <li>
            <a href="{{route('medicine-group.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Medicine Group</a>
          </li>
          <li>
            <a href="{{route('unit.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Unit</a>
          </li>
          <li>
            <a href="{{route('blood-type.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Blood Type</a>
          </li>
          <li>
            <a href="{{route('paymentmode.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Payment Mode</a>
          </li>
           <li>
            <a href="{{route('vendor.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Vendor</a>
          </li>
           <li>
            <a href="{{route('bedgroup.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Bed/Room Group</a>
          </li>
          <li>
            <a href="{{route('bedtype.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Bed Type</a>
          </li>
           <li>
            <a href="{{route('bed.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Bed</a>
          </li>
           <li>
            <a href="{{route('roomtype.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Room Type</a>
          </li>
           <li>
            <a href="{{route('roomnum.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Room Number</a>
          </li>
           <li>
            <a href="{{route('testtype.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Test Type</a>
          </li>
           <li>
            <a href="{{route('testname.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Test Name</a>
          </li>
           <li>
            <a href="{{route('composition.index')}}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Composition</a>
          </li>
        </ul>
      </li>
      
 
    </ul>
  </div>
</aside>

<main class="dashboard-main">
  <div class="navbar-header">
  <div class="row align-items-center justify-content-between">
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-4">
        <button type="button" class="sidebar-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
          <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
        </button>
        <button type="button" class="sidebar-mobile-toggle">
          <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
        </button>
        <form class="navbar-search">
          <input type="text" name="search" placeholder="Search">
          <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
        </form>
      </div>
    </div>
    <div class="col-auto">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
        <!-- Notification dropdown start -->
        {{-- <div class="dropdown">
            <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
              <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
            </button>
            <div class="dropdown-menu to-top dropdown-menu-lg p-0">
              <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                <div>
                  <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                </div>
                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">02</span>
              </div>
              
             <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
              <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                  <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                    <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                  </span> 
                  <div>
                    <h6 class="text-md fw-semibold mb-4">Congratulations</h6>
                    <p class="mb-0 text-sm text-secondary-light text-w-200-px">Your profile has been Verified. Your profile has been Verified</p>
                  </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
              </a>
              
              <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                  <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                  </span> 
                  <div>
                    <h6 class="text-md fw-semibold mb-4">Ronald Richards</h6>
                    <p class="mb-0 text-sm text-secondary-light text-w-200-px">You can stitch between artboards</p>
                  </div>
                </div>
                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
              </a>
             
             </div>
  
              <div class="text-center py-12 px-16"> 
                  <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Notification</a>
              </div>
  
            </div>
          </div> --}}
          <!-- Notification dropdown end -->

        <div class="dropdown">
          <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
            <img src="{{asset('backend/assets/images/user.png')}}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
          </button>
          <div class="dropdown-menu to-top dropdown-menu-sm">
            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
              <div>
                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{auth()->user()->name ?? ''}}</h6>
                <span class="text-secondary-light fw-medium text-sm">Admin</span>
              </div>
              <button type="button" class="hover-text-danger">
                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
              </button>
            </div>
            <ul class="to-top-list">
              <li>
                <a href="{{route('profile.index')}}" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="view-profile.html"> 
                <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>  My Profile</a>
              </li>
              <li>
                <a href="{{route('auth.logout')}}" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>  Log Out</a>
              </li>
            </ul>
          </div>
        </div><!-- Profile dropdown end -->
      </div>
    </div>
  </div>
</div> 