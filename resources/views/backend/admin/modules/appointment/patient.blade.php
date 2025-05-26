@extends('backend.admin.layouts.main')
@section('title')
Patient
@endsection
@section('extra-css')

@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Patient</h6>
    <div class="btns">
      {{-- <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"  data-bs-toggle="modal" data-bs-target="#add-appointment" onclick="resetAppointmentForm()"><i class="ri-add-box-line"></i> Create New</button> --}}
      <!-- <a  class="btn btn-warning-600  btn-sm fw-normal " href="#dataTable" download><i class="ri-file-pdf-2-line"></i> Export</a> -->
    </div>
  </div>
  <div class="card basic-data-table">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title mb-0 fw-medium">Patient Details</h5>
     
    </div>
    <div class="card-body">
      <table class="table bordered-table mb-0" id="patient-table" data-page-length='10'>
        <thead>
          <tr>
            <th scope="col" class="fw-medium">Patient ID</th>
            <th scope="col" class="fw-medium">Name</th>
            <th scope="col" class="fw-medium">Guardian</th>
            <th scope="col" class="fw-medium">Gender</th>
            <th scope="col" class="fw-medium">Blood Type</th>
            <th scope="col" class="fw-medium">DOB</th>
            <th scope="col" class="fw-medium">Marital</th>
            <th scope="col" class="fw-medium">Phone</th>
            <th scope="col" class="fw-medium">Alt Phone</th>
            <th scope="col" class="fw-medium">Address</th>
            <th scope="col" class="fw-medium">Allergies</th>
            {{-- <th scope="col" class="fw-medium">Status</th> --}}
            {{-- <th scope="col" class="fw-medium">Action</th> --}}
          </tr>
        </thead>
        <tbody>
               {{-- Table data appended here using ajax datatable --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('extra-js')
<script>
  const viewPatients = "{{route('patient.viewPatients')}}";
    const deletePatientData = "{{route('patient.deletePatientData')}}";
</script>       
  {{-----------external js files added for page functions------------}}
<script src="{{asset('backend/assets/js/custom/admin/appointment/patient.js')}}"></script>
@endsection