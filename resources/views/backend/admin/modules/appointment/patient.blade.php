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
      <div class="d-flex flex-wrap align-items-center gap-2">
              <a class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#patient-add-patient" onclick="resetPatientAddPatient()"> <i class="ri-add-line"></i> Add Patient</a>
          </div>
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
            <th scope="col" class="fw-medium">Action</th>
          </tr>
        </thead>
        <tbody>
               {{-- Table data appended here using ajax datatable --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!-- modal2 start -->
<div class="modal fade" id="patient-add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-appointmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="patient-add-appointmentLabel">Add Patient</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close" onclick="reopenAppointment()"></button>
      </div>
      <form action="" id="patient-addPatientForm">
        <div class="modal-body">
            <div class="row gy-3">
          <div class="col-6">
            <input type="hidden" id="patient-patientId">
            <label class="form-label fw-normal" for="patient-patientName">Patient Name</label>
            <input type="text" id="patient-patientName" name="#0" class="form-control form-control-sm" placeholder="Patient Name" oninput="validateField(this.id,'input')">
            <div class="patient-patientName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-guardianName">Gaurdian Name</label>
            <input type="text" id="patient-guardianName" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name" oninput="validateField(this.id,'input')">
            <div class="patient-guardianName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal mb-3">Gender</label>
              <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patient-patientGender" id="patient-patientGender1" value="Male">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patient-patientGender1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patient-patientGender" id="patient-patientGender2" value="Female">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patient-patientGender2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patient-patientGender" id="patient-patientGender3" value="Other">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patient-patientGender3"> Other </label>
                </div>
              </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm" id="patient-patientBloodType" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
            </select>
            <div class="patient-patientBloodType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-patientDOB">DOB</label>
            <input type="date" id="patient-patientDOB" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
            <div class="patient-patientDOB_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-patientMStatus">Marital Status</label>
            <select class="form-select form-select-sm" id="patient-patientMStatus"  oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="patient-patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-patientMobile">Phone</label>
            <input type="text" id="patient-patientMobile" class="form-control form-control-sm" placeholder="Phone" oninput="validateField(this.id,'mobile')">
            <div class="patient-patientMobile_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patient-patientAddess">Address</label>
            <input type="text" id="patient-patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="patient-patientAddess_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="text" id="patient-patientAltMobile" class="form-control form-control-sm" placeholder="Alt Phone">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="patient-patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal" onclick="reopenAppointment()">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal patientAddPatientSubmit">Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal patientAddPatientUpdate d-none" onclick="patientAddPatientUpdate(document.getElementById('patient-patientId').value)">Update</button>
           <!-- <button type="button" class="btn btn-warning-600  btn-sm fw-normal">Save & Book Appointment</button>  -->
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal 2 end -->
@endsection
@section('extra-js')
<script>
    const viewPatients = "{{route('patient.viewPatients')}}";
    const deletePatientData = "{{route('patient.deletePatientData')}}";
    const patientAddNewPatient = "{{route('patient.patientAddNewPatient')}}"; 
    const newPatientData = "{{route('patient.newPatientData')}}"; 
    const patientAddNewPatientDataUpdate = "{{route('patient.patientAddNewPatientDataUpdate')}}"; 
</script>       
  {{-----------external js files added for page functions------------}}
<script src="{{asset('backend/assets/js/custom/admin/appointment/patient.js')}}"></script>
@endsection