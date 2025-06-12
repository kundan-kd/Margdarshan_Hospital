@extends('backend.admin.layouts.main')
@section('title')
    IPD
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
      <div class="d-flex flex-wrap align-items-center justify-content-between  mb-24">
          <h6 class="fw-normal mb-0">IPD - In Patient</h6>
          <div class="d-flex flex-wrap align-items-center gap-2">
              <a href="{{route('ipd-in.ipdInPatientAdd')}}" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Add Patient</a>
              <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
          </div>
     </div>
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0 w-100" id="ipd-in-list" data-page-length='10'>
          <thead>
            <tr >
              <th scope="col" class="fw-medium">Patient Name</th>
              <th scope="col" class="fw-medium">Admission Date </th>
              <th scope="col" class="fw-medium">Emergency No.</th>
              <th scope="col" class="fw-medium">Sex</th>
              <th scope="col" class="fw-medium">Doctor</th>
              <th scope="col" class="fw-medium">Bed No.</th>
              <th scope="col" class="fw-medium">Room No.</th>
              <th scope="col" class="fw-medium">Status</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
           <tr>
              <td><a href="{{route('ipd-in.ipdInDetails')}}" class="text-primary-600">Rahul Kumar</a></td>
              <td>20-05-2025</td>
              <td>9876543210</td>
              <td>Male</td>
              <td>Ravi Sankar</td>
              <td>4</td>
              <td>201</td>
              <td><span class="bg-success-focus text-success-main px-16 py-4 radius-4 fw-medium text-sm">Paid</span></td>
               <td>
                <button class="mx-1 bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#ipd-visit-view">
                  <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                </button>
                <a href="ipd-edit-visit-detail.html"><button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="lucide:edit"></iconify-icon>
                </button>
                </a>
                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                <a href="ipd-invoice.html" target="_blank"><button class="mx-1 bg-warning-200 bg-hover-warning-300 text-warning-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
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

<!-- ipd-view start -->
<div class="modal fade" id="ipd-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-visit-viewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header p-11 bg-primary-500">
          <h6 class="modal-title fw-normal text-md text-white" id="ipd-visit-viewLabel">Patient Details</h6>
          <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                      <tr>
                          <th class="fw-medium">IPD Checkup ID</th>
                          <td>CK012345</td>
                          <th class="fw-medium">	IPD ID</th>
                          <td>IPD12345</td>
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
<!-- ipd-view-end -->
@endsection
@section('extra-js')
<script>
</script>
@endsection