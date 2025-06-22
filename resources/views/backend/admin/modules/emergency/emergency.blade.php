@extends('backend.admin.layouts.main')
@section('title')
    Emergency
@endsection
@section('extra-css')
<style>
    .form-select.form-select-sm{
        width:auto !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection
@section('main-container')
  <div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">Emergency</h6>
  <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="add-emergency-patient.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-add-patient" onclick= "resetAddPatient();getBedDataEmergency()"> <i class="ri-add-line"></i> Add Patient</a>
          {{-- <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button> --}}
        </div>
  <!-- <div class="btns">
    <a class="btn btn-primary-600  btn-sm fw-normal mx-2" href="./emergency-detail.html"><i class="ri-add-line"></i> Create New</a>
    <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
  </div> -->
</div>
    
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0 w-100" id="emergency-patient-list" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col" class="fw-medium">Patient ID</th>
              <th scope="col" class="fw-medium">Name</th>
              <th scope="col" class="fw-medium">Gender</th>
              <th scope="col" class="fw-medium">Blood Type</th>
              <th scope="col" class="fw-medium">DOB</th>
              <th scope="col" class="fw-medium">Phone</th>
              <th scope="col" class="fw-medium">Allergies</th>
              <th scope="col" class="fw-medium">Status</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
           {{-- <tr>
              <td><a href="#" class="text-primary-600">Arun Kumar</a></td>
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
           </tr> --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- modal2 start -->
<div class="modal fade" id="emergency-add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-add-patientLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="emergency-add-patientLabel">Add Patient</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="emergency-addPatientForm">
        <div class="modal-body">
            <div class="row gy-3">
          <div class="col-6">
            <input type="hidden" id="emergencyPatientId">
            <label class="form-label fw-normal" for="emergency-patientName">Patient Name</label>
            <input type="text" id="emergency-patientName" name="#0" class="form-control form-control-sm" placeholder="Patient Name" oninput="validateField(this.id,'input')">
            <div class="emergency-patientName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-guardianName">Gaurdian Name</label>
            <input type="text" id="emergency-guardianName" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name" oninput="validateField(this.id,'input')">
            <div class="emergency-guardianName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal mb-3">Gender</label>
              <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="emergency-patientGender" id="emergency-patientGender1" value="Male">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="emergency-patientGender" id="pemergency-atientGender2" value="Female">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="emergency-patientGender" id="emergency-patientGender3" value="Other">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender3"> Other </label>
                </div>
              </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm" id="emergency-patientBloodType"style="width:100%" oninput="validateField(this.id,'select')">
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
            <div class="emergency-patientBloodType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientDOB">DOB</label>
            <input type="date" id="emergency-patientDOB" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
            <div class="emergency-patientDOB_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientMStatus">Marital Status</label>
            <select class="form-select form-select-sm" id="emergency-patientMStatus" style="width: 100%" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="emergency-patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientMobile">Phone</label>
            <input type="number" id="emergency-patientMobile" class="form-control form-control-sm" placeholder="Phone" oninput="this.value=this.value.slice(0,10);validateField(this.id,'mobile')">
            <div class="emergency-patientMobile_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientAddess">Address</label>
            <input type="text" id="emergency-patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="emergency-patientAddess_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="number" id="emergency-patientAltMobile" class="form-control form-control-sm" placeholder="Alt Phone" oninput="this.value=this.value.slice(0,10)">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="emergency-patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
            <div class="col-6">
            <label class="form-label fw-normal" for="emergency-patientBedNum">Bed Number</label>
            <select class="form-select form-select-sm select2-cls" id="emergency-patientBedNum" style="width: 100%" onchange="getBedDetails(this.value)" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
            </select>
            <div class="emergency-patientBedNum_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Bed Type</label>
            <input type="text" id="emergency-patientBedType"  class="form-control form-control-sm" placeholder="Bed Type" readonly>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Floor</label>
            <input type="text" id="emergency-patientBedFloor"  class="form-control form-control-sm" placeholder="Floor No." readonly>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Bed Charge</label>
            <input type="text" id="emergency-patientBedCharge"  class="form-control form-control-sm" placeholder="Bed Charge" readonly>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal" onclick="reopenAppointment()">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal emergencyPatientSubmit">Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal emergencyPatientUpdate" onclick="emergencyPatientUpdate(document.getElementById('emergencyPatientId').value)">Update</button>
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
   $('#emergency-add-patient').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#emergency-add-patient')
      });
    });
  const addPatient = "{{route('emergency-addPatient')}}"; 
  const viewPatients = "{{route('emergency-viewPatients')}}"; 
  const getEmergencyPatientData = "{{route('emergency-getEmergencyPatientData')}}"; 
  const emergencyPatientDataUpdate = "{{route('emergency-emergencyPatientDataUpdate')}}"; 
  const emergencyPatientDataDelete = "{{route('emergency-emergencyPatientDataDelete')}}"; 
  const getBedDatasEmergency = "{{route('emergency-getBedDatasEmergency')}}"; 
  const getBedDetailsEmergency = "{{route('emergency-getBedDetailsEmergency')}}"; 

  
</script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency.js')}}"></script>
@endsection