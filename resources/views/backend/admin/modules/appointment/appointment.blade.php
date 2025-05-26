@extends('backend.admin.layouts.main')
@section('title')
Appointment
@endsection
@section('extra-css')
<style>
.search-item .list-group-item:hover {
    background-color: #007bff; /* Bootstrap active color */
    color: #fff;
    cursor: pointer;
}
</style>
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Appointment</h6>
    <div class="btns">
      <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"  data-bs-toggle="modal" data-bs-target="#add-appointment" onclick="resetAppointmentForm()"><i class="ri-add-box-line"></i> Create New</button>
      <!-- <a  class="btn btn-warning-600  btn-sm fw-normal " href="#dataTable" download><i class="ri-file-pdf-2-line"></i> Export</a> -->
    </div>
  </div>
  <div class="card basic-data-table">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title mb-0 fw-medium">Appointment Details</h5>
     
    </div>
    <div class="card-body">
      <table class="table bordered-table mb-0" id="appointment-book-table" data-page-length='10'>
        <thead>
          <tr>
            <th scope="col" class="fw-medium">Patient Name</th>
            <th scope="col" class="fw-medium">Appointment Date</th>
            <th scope="col" class="fw-medium">Phone no</th>
            <th scope="col" class="fw-medium">Gender</th>
            <th scope="col" class="fw-medium">Doctor</th>
            <th scope="col" class="fw-medium">Tocken No</th>
            <th scope="col" class="fw-medium">Fee</th>
            <th scope="col" class="fw-medium">Status</th>
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
@php 
$ddate = date("d/m/yy");
@endphp
  <!-- modal 1 start -->
<div class="modal fade" id="add-appointment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-appointmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="add-appointmentLabel">Book Appointment</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="appointmentForm">
        <div class="modal-body">
          <div class="row gy-3">
            <div class="col-5">
              <!-- <label class="form-label fw-normal ">Search Patient</label>  -->
                <div>
                  <label class="form-label fw-normal w-100" for="itemSearchInput">Search Patient</label>
                   <div class="input-group">
                      <span class="input-group-text text-muted"><i class="ri-search-line"></i></span>
                      <input id="itemSearchInput" class="form-control form-control-sm" type="text" placeholder="Search item" oninput="getPatientData(this.value)">
                       
                  </div>
                   <div class="d-block position-relative" style="z-index :99;">
                    <ul id="searchItemDropdown" class="search-item list-group position-absolute w-100 rounded-0 patient-name-list d-none">
                      <div class="patient-name">
                         <!-- dropdown list of patients appended here using js -->
                      </div>
                    </ul>
                  </div>
                </div>
              <div class="patient-notfound d-none"></div>
              <div class="itemSearchInput_errorCls d-none"></div>
            </div> 
            <!-- data-toggle="tooltip" title="Click me! -->
            <div class="col-1 px-0 mt-5">      
                <div data-bs-toggle="modal" data-bs-target="#add-patient">

                  <button type="button" class="mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center"
                  data-bs-toggle="tooltip" data-bs-placement="top"
                  data-bs-custom-class="tooltip-primary"
                  data-bs-title="Add Patient" onclick="resetAddPatient()" >
                  <i class="ri-add-line fw-bold"></i>
                  </button>
                  {{-- <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" style="margin-top: 4px" onclick="manageAddPatient()">
                  <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Add Patient">
                    <i class="ri-add-line fw-bold"></i>
                  </div>
                </button> --}}

                </div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal ">Patient Name</label>
              <input type="hidden" id="patientNameApptID" style="display:none;">
              <input type="text" id="patientNameAppt" name="#0" class="form-control form-control-sm" placeholder="Patient Name" readonly>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="departmentAppt">Department</label>
              <select id="departmentAppt" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" >
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
              </select>
              <div class="departmentAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="doctorAppt">Doctor</label>
              <select id="doctorAppt" class="form-select form-select-sm select2-cls" onchange="getDocRoomNum(this.value)" style="width: 100%" oninput="validateField(this.id,'select')">
                <option value="">Select Doctor</option>
                @foreach ($doctors as $doctor)
                <option value="{{$doctor->id}}">Dr. {{$doctor->firstname}} {{$doctor->lastname}}</option>
                @endforeach
              </select>
              <div class="doctorAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="dateAppt">Appointment Date</label>
              <input type="date" id="dateAppt" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
               <div class="dateAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="paymentModeAppt">Payment Mode</label>
              <select id="paymentModeAppt" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" >
                <option value="">Payment Mode</option>
                @foreach ($paymentmodes as $paymentmode)
                <option value="{{$paymentmode->id}}">{{$paymentmode->name}}</option>
                @endforeach
              </select>
              <div class="paymentModeAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal ">Room No</label>
              <input type="text" id="roomNumAppt" class="form-control form-control-sm " placeholder="Room No" readonly>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal ">Fee</label>
              <input type="text" id="opd_fee" class="form-control form-control-sm" placeholder="Doctor Fee" readonly>
            </div>
        </div>
        </div>
        <div class="modal-footer">
           <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal appointmentSubmitBtn">Submit</button>
          <!-- <button type="button" class="btn btn-warning-600  btn-sm fw-normal appointmentSubmitBtn">Submit & Print</button> -->
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal appointmentUpdateBtn d-none" onclick="updateAppointment(document.getElementById('patientNameApptID').value)">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal 1 end -->
  <!-- modal2 start -->
<div class="modal fade" id="add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-appointmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="add-appointmentLabel">Add Patient</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close" onclick="reopenAppointment()"></button>
      </div>
      <form action="" id="addPatientForm">
        <div class="modal-body">
            <div class="row gy-3">
          <div class="col-6">
            <label class="form-label fw-normal" for="patientName">Patient Name</label>
            <input type="text" id="patientName" name="#0" class="form-control form-control-sm" placeholder="Patient Name" oninput="validateField(this.id,'input')">
            <div class="patientName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="guardianName">Gaurdian Name</label>
            <input type="text" id="guardianName" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name" oninput="validateField(this.id,'input')">
            <div class="guardianName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal mb-3">Gender</label>
              <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patientGender" id="patientGender1" value="Male">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patientGender" id="patientGender2" value="Female">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="patientGender" id="patientGender3" value="Other">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender3"> Other </label>
                </div>
              </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm" id="patientBloodType" oninput="validateField(this.id,'select')">
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
            <div class="patientBloodType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientDOB">DOB</label>
            <input type="date" id="patientDOB" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
            <div class="patientDOB_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientMStatus">Marital Status</label>
            <select class="form-select form-select-sm" id="patientMStatus"  oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientMobile">Phone</label>
            <input type="text" id="patientMobile" class="form-control form-control-sm" placeholder="Phone" oninput="validateField(this.id,'mobile')">
            <div class="patientMobile_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientAddess">Address</label>
            <input type="text" id="patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="patientAddess_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="text" id="patientAltMobile" class="form-control form-control-sm" placeholder="Alt Phone">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal" onclick="reopenAppointment()">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal">Submit</button>
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
  const viewAppointments = "{{route('appointment.viewAppointments')}}";
  const addNewPatient = "{{route('appointment-patient.addNewPatient')}}";
  const searchPatient = "{{route('appointment-patient.searchPatient')}}";
  const getPatient = "{{route('appointment-patient.getPatient')}}";
  const getDoctorData = "{{route('appointment-patient.getDoctorData')}}";
  const appointmentBook = "{{route('appointment-booking.appointmentBook')}}";
  const getAppointmentData = "{{route('appointment-booking.getAppointmentData')}}";
  const updateAppointmentData = "{{route('appointment-booking.updateAppointmentData')}}";
  const deleteAppointmentData = "{{route('appointment-booking.deleteAppointmentData')}}";
</script>
  {{-----------external js files added for page functions------------}}
<script src="{{asset('backend/assets/js/custom/admin/appointment/appointment.js')}}"></script>
<script>
  //  -- select2 js library included for dropdown search and select box.. other method for implenting used due to boostrap conflicts--
 window.addEventListener('load', () => {
    $('.select2-cls').select2({
    dropdownParent: $('#add-appointment')
  });
});

// Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-yy ",
        });
    }
    getDatePicker('#dateAppt'); 
    getDatePicker('#patientDOB'); 

</script>
 <script>
        // const itemSearchInput = document.getElementById('itemSearchInput');
        // const searchItemDropdown = document.getElementById('searchItemDropdown');

        // itemSearchInput.addEventListener('input', function () {
        //     if (this.value.trim() !== '') {
        //     searchItemDropdown.style.display = 'block';
        //     } else {
        //     searchItemDropdown.style.display = 'none';
        //     }
        // });
    </script>
@endsection