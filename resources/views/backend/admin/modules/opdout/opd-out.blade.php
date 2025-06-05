@extends('backend.admin.layouts.main')
@section('title')
    OPD - Out Patient
@endsection
@section('extra-css')
<style>
    .form-select.form-select-sm{
        width:auto !important;
    }
</style>
@endsection
@section('main-container')
  <div class="dashboard-main-body">

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
       <h6 class="fw-normal mb-0">OPD - Out Patient</h6>
     </div>
      <div class=" row my-3">
        <div class="col-md-4">
            <div class="d-flex justify-content-between ">
            <select id="opdoutDoctorId" class="select2-class form-select" onchange="getListFilter()">
                <option value="">👨‍⚕️ Select Doctor</option>
                @foreach ($users as $user)
                <option value="{{$user->id}}">👤Dr.  {{$user->firstname}} {{$user->lastname}}</option>
                @endforeach
            </select>
            <select id="opdoutRoomNum" class="select2-class form-select" onchange="getListFilter()">
                <option value="">🏥 Select Room</option>
                @foreach ($users as $user)
                <option value="{{$user->room_number}}">🚪 Room {{$user->room_number}}</option>
                @endforeach
            </select>
        </div>
        </div>
        <div class="col-md-8 ">
            <div class="d-flex flex-wrap align-items-center justify-content-end gap-2">
              <a href="opd-add-patient.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Add Patient</a>
              {{-- <a href="#" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</a> --}}
            </div>
        </div>
       
      </div>

    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0 w-100" id="opd-out-list-table" data-page-length='10'>
          <thead>
            <tr >
              <th scope="col" class="fw-medium">Token no.</th>
              <th scope="col" class="fw-medium">Doctor</th>
              <th scope="col" class="fw-medium">Appointment Date</th>
              {{-- <th scope="col" class="fw-medium">Phone</th>
              <th scope="col" class="fw-medium">Sex</th>
              <th scope="col" class="fw-medium">Status</th>
              <th scope="col" class="fw-medium">Action</th> --}}
            </tr>
          </thead>
          <tbody>
           <tr>
              <td><a href="{{route('opd-out.detail')}}" class="text-primary-600">OPD_123</a></td>
              <td>Arun Kumar</td>
              <td>07-05-2025 03:28 PM</td>
              {{-- <td>+91 1234 567 890</td>
              <td>Male</td>
              <td><span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Paid</span></td>
              <td>
                <button class="mx-1 bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#opd-visit-view">
                  <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                </button>
                <a href="opd-edit-visit-detail.html"><button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </button>
                </a>
                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                 <a href="opd-invoice.html" target="_blank"><button class="mx-1 bg-warning-200 bg-hover-warning-300 text-warning-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <i class="ri-printer-line"></i>
                </button>
                </a>
              </td> --}}
           </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<!-- opd-view start -->
<div class="modal fade" id="opd-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-visit-viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header p-11 bg-primary-500">
          <h6 class="modal-title fw-normal text-md text-white" id="opd-visit-viewLabel">Visit Details</h6>
          <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr>
                    <th class="fw-medium text-sm text-neutral-600">	OPD ID</th>
                    <td>OPD12345</td>
                    <th class="fw-medium text-sm text-neutral-600">OPD Checkup ID</th>
                    <td>CK012345</td>
                </tr>
                <tr>
                   <th class="fw-medium text-sm text-neutral-600">Appointment ID</th>
                    <td>APPOINT2547</td>
                    <th class="fw-medium text-sm text-neutral-600">Appointment No</th>
                    <td>5</td>
                </tr>
                <tr>
                   <th class="fw-medium text-sm text-neutral-600">Appointment Date</th>
                    <td>05/29/2025 10:21 AM</td> 
                    <th class="fw-medium text-sm text-neutral-600">Patient Name</th>
                    <td>Aman Kumar (1254)</td>
                </tr>
                <tr>     
                    <th class="fw-medium text-sm text-neutral-600">Old Patient</th>
                    <td>No</td>
                    <th class="fw-medium text-sm text-neutral-600">Guardian Name</th>
                    <td>Mohan Kumar</td>
                </tr>
                <tr>           
                   <th class="fw-medium text-sm text-neutral-600">Gender</th>
                    <td>Male</td>
                    <th class="fw-medium text-sm text-neutral-600">Marital Status</th>
                    <td>Singal</td>
                </tr>
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">Phone</th>
                    <td>+91 1234 456 789</td>    
                    <th class="fw-medium text-sm text-neutral-600">Email</th>
                    <td>aman@gmail.com</td>  
                </tr>
                  <tr>    
                     <th class="fw-medium text-sm text-neutral-600">	Address</th>
                    <td>Patna</td>    
                    <th class="fw-medium text-sm text-neutral-600">Age</th>
                    <td>36 Years</td>  
                </tr>
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">	Blood Group</th>
                    <td>O+</td>    
                    <th class="fw-medium text-sm text-neutral-600">Known Allergies</th>
                    <td></td>  
                </tr>
                
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">Casualty</th>
                    <td></td>    
                    <th class="fw-medium text-sm text-neutral-600">	Reference</th>
                    <td></td>  
                </tr>
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">TPA</th>
                    <td>Health Life Insurance</td>    
                    <th class="fw-medium text-sm text-neutral-600">	Consultant Doctor</th>
                    <td>Suraj Kumar (12345)</td>  
                </tr>
            </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('extra-js')
<script>
    let viewOpdOut = "{{route('opd-out.viewOpdOut')}}";
</script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout.js')}}"></script>
 {{-----------external js files added for page functions------------}}
<script>
$(document).ready(function() {
    $('.select2-class').select2({
    });
});
</script>
@endsection