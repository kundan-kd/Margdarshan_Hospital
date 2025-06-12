@extends('backend.admin.layouts.main')
@section('title')
    Emergency
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

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">Emergency</h6>
  <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="add-emergency-patient.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create New</a>
          <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
        </div>
  <!-- <div class="btns">
    <a class="btn btn-primary-600  btn-sm fw-normal mx-2" href="./emergency-detail.html"><i class="ri-add-line"></i> Create New</a>
    <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
  </div> -->
</div>
    
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0 w-100" id="emergency-list" data-page-length='10'>
          <thead>
            <tr >
              <th scope="col" class="fw-medium">Patient Name</th>
              <th scope="col" class="fw-medium">Admission Date & Time</th>
              <th scope="col" class="fw-medium">Emergancy No.</th>
              <th scope="col" class="fw-medium">Sex</th>
              <th scope="col" class="fw-medium">Doctor</th>
              <th scope="col" class="fw-medium">Bed No.</th>
              <th scope="col" class="fw-medium">RMO</th>
              <th scope="col" class="fw-medium">Status</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
           <tr>
              <td><a href="{{route('emergency.emergencyDetails')}}" class="text-primary-600">Arun Kumar</a></td>
              <td>07-05-2025 03:28 PM</td>
              <td>+911234567890</td>
              <td>Male</td>
              <td>Niraj Kumar</td>
              <td>2</td>
              <td>Non</td>
              <td><span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-normal text-sm">Paid</span></td>
              <td>
                <button class="mx-1 bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#emergency-patient-details">
                  <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                </button>
                <a href="edit-emergency-patient-details.html"><button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </button>
                </a>
                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                <a href="emergency-invoice.html" target="_blank"><button class="mx-1 bg-warning-200 bg-hover-warning-300 text-warning-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <i class="ri-printer-line"></i>
                </button>
                </a>
              </td>
           </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<!-- emergency-patient-details start -->
<div class="modal fade" id="emergency-patient-details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-patient-detailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header p-11 bg-primary-500">
          <h6 class="modal-title fw-normal text-md text-white" id="emergency-patient-detailsLabel">Emergency Patient Details</h6>
          <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr>
                    <th class="fw-medium">Emergency Checkup ID</th>
                    <td>CK012345</td>
                    <th class="fw-medium">	Emergency ID</th>
                    <td>Emergency12345</td>
                </tr>
                <tr>
                   <th class="fw-medium">Case ID</th>
                    <td>2547</td>
                    <th class="fw-medium">Patient Name</th>
                    <td>Aman Kumar (1254)</td>
                </tr>
                <tr>     
                    <th class="fw-medium">Old Patient</th>
                    <td>No</td>
                    <th class="fw-medium">Guardian Name</th>
                    <td>Mohan Kumar</td>
                </tr>
                <tr>           
                   <th class="fw-medium">Gender</th>
                    <td>Male</td>
                    <th class="fw-medium">Marital Status</th>
                    <td>Singal</td>
                </tr>
                <tr>    
                     <th class="fw-medium">Phone</th>
                    <td>+91 1234 456 789</td>    
                    <th class="fw-medium">Email</th>
                    <td>aman@gmail.com</td>  
                </tr>
                  <tr>    
                     <th class="fw-medium">	Address</th>
                    <td>Patna</td>    
                    <th class="fw-medium">Age</th>
                    <td>36 Years</td>  
                </tr>
                <tr>    
                     <th class="fw-medium">	Blood Group</th>
                    <td>O+</td>    
                    <th class="fw-medium">Known Allergies</th>
                    <td></td>  
                </tr>
                <tr>    
                     <th class="fw-medium">Appointment Date</th>
                    <td>05/29/2025 10:21 AM</td>    
                    <th class="fw-medium">	Case</th>
                    <td></td>  
                </tr>
                <tr>    
                     <th class="fw-medium">Casualty</th>
                    <td></td>    
                    <th class="fw-medium">	Reference</th>
                    <td></td>  
                </tr>
                <tr>    
                     <th class="fw-medium">TPA</th>
                    <td>Health Life Insurance</td>    
                    <th class="fw-medium">	Consultant Doctor</th>
                    <td>Suraj Kumar (12345)</td>  
                </tr>
            </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
<!-- emergency-patient-details-end -->
@endsection
@section('extra-js')
<script>
</script>
@endsection