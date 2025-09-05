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
      @can('Book Appointment Add')
      <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"  data-bs-toggle="modal" data-bs-target="#add-appointment" onclick="resetAppointmentForm()"><i class="ri-add-box-line"></i> Create New</button>
      @endcan
       <button><a class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-1" id="excelBtn"><i class="ri-file-excel-2-line"></i> Excel</a></button>
    </div>
  </div>
  <div class="card basic-data-table">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title mb-0 fw-medium">Appointment Details</h5>
     
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="appointment-book-table" data-page-length='10'>
                <thead>
                  <tr>
                    <th scope="col" class="fw-medium">Appointment Date</th>
                    <th scope="col" class="fw-medium">Appointment ID</th>
                    <th scope="col" class="fw-medium">Patient Name</th>
                    <th scope="col" class="fw-medium">Phone no</th>
                    <th scope="col" class="fw-medium">Gender</th>
                    <th scope="col" class="fw-medium">Doctor</th>
                    <th scope="col" class="fw-medium">Fee</th>
                    <th scope="col" class="fw-medium">Payment Status</th>
                    <th scope="col" class="fw-medium">Visit Status</th>
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
                      <input id="itemSearchInput" class="form-control form-control-sm" type="text" placeholder="Search Patient" oninput="getPatientData(this.value)">                       
                  </div>
                   <div class="d-block position-relative" style="z-index :99;">
                    <ul id="searchItemDropdown" class="search-item list-group position-absolute w-100 rounded-0 patient-name-list d-none">
                      <!-- dropdown list of patients appended here using JS -->
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
                  data-bs-title="Add Patient" onclick="resetAddPatient()" style="margin-top: 4px;">
                  <i class="ri-add-line fw-bold"></i>
                  </button>
                </div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal ">Patient Name</label>
              <input type="hidden" id="patientNameApptID" style="display:none;">
              <input type="text" id="patientNameAppt" name="#0" class="form-control form-control-sm" placeholder="Patient Name" readonly>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="departmentAppt">Department</label>
              <select id="departmentAppt" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" onchange="getDoctor(this.value)" >
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
              </select>
              <div class="departmentAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="doctorAppt">Doctor</label>
              <select id="doctorAppt" class="form-select form-select-sm select2-cls" style="width: 100%" onchange="getDocRoomNum(this.value)" oninput="validateField(this.id,'select')">
                <option value="">Select Doctor</option>
              </select>
              <div class="doctorAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="roomNumAppt">OPD Room</label>
              {{-- <input type="hidden" id="roomNumApptId" style="display:none;"> --}}
              {{-- <input type="text" id="roomNumAppt" class="form-control form-control-sm " placeholder="OPD Room No" readonly> --}}

               <select id="roomNumAppt" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                <option value="">Select OPD Room</option>
                @foreach ($opd_rooms as $rooms)
                <option value="{{$rooms->id}}">{{$rooms->room_num}}</option>
                @endforeach
              </select>
               <div class="roomNumAppt_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="opd_fee">Fee</label>
              <input type="text" id="opd_fee" class="form-control form-control-sm" placeholder="Doctor Fee" oninput="validateField(this.id,'amount')">
               <div class="opd_fee_errorCls d-none"></div>
            </div>
            <div class="col-12">
              <label class="form-label fw-normal" for="dateAppt">Appointment Date</label>
              <input type="date" id="dateAppt" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
               <div class="dateAppt_errorCls d-none"></div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
           <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
           @can('Book Appointment Add')
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal appointmentSubmitBtn">Submit</button>
          @endcan
          {{-- @can('Book Appointment Edit')
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal appointmentUpdateBtn d-none" onclick="updateAppointment(document.getElementById('patientNameApptID').value)">Update</button>
          @endcan --}}
          <button class="btn btn-primary-600  btn-sm fw-normal appointmentSpinn d-none" type="button">
            <span class="sr-only">please wait...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal 1 end -->
  <!-- modal patient add start -->
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
            <label class="form-label fw-normal" for="patientMobile">Phone</label>
            <input type="number" id="patientMobile" class="form-control form-control-sm" placeholder="Phone" maxlength="10" oninput="this.value=this.value.slice(0,10);validateField(this.id,'mobile');getPatientDetailsOpd(this.value)">
            <div class="d-block position-relative" style="z-index :99;">
                    <ul class="search-item list-group position-absolute w-100 rounded-0 patient-data-list-opd">
                      <!-- dropdown list of patients appended here using JS -->
                    </ul>
              </div>
            <div class="patientMobile_errorCls d-none"></div>
          </div>
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
            <label class="form-label fw-normal" for="patientEnrtyType">Entry Type</label>
            <select class="form-select form-select-sm select2-cls" id="patientEnrtyType" style="width: 100%" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="General">General</option>
              <option value="3rd Party Insurance">3rd Party Insurance</option>
              <option value="CGSH/ECHS">CGSH/ECHS</option>
            </select>
            <div class="patientEnrtyType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm select2-cls" style="width:100%" id="patientBloodType" oninput="validateField(this.id,'select')">
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
            <select class="form-select form-select-sm select2-cls" style="width:100%" id="patientMStatus"  oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="patientAddess">Address</label>
            <input type="text" id="patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="patientAddess_errorCls d-none"></div>
          </div>
          {{-- <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="number" id="patientAltMobile" class="form-control form-control-sm" maxlength="10" placeholder="Alt Phone" oninput="this.value=this.value.slice(0,10)">
          </div> --}}
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal" onclick="reopenAppointment()">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal patientSubmit">Submit</button>
           <button class="btn btn-primary-600  btn-sm fw-normal patientSpinn d-none" type="button">
            <span class="sr-only">please wait...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal patient add end -->

    <!-- paid amount modal start -->
  <div class="modal fade" id="appointment-edit-modal" tabindex="-1" role="dialog" aria-labelledby="appointment-edit-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Make Payment</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
               <form action="" id="apptEdit-form" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <input type="hidden" id="apptEdit_id">
                    <label class="form-label" for="apptEditPaymentAmt">Fee Amount</label>
                    <input class="form-control form-control-sm" id="apptEditPaymentAmt"
                        placeholder="Enter Reason" style="background-image: none;" readonly>
                    <div class="invalid-feedback">
                        Enter Reason For Cancel
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="apptEditPaymentMode">Payment Mode</label>
                    <select class="form-control form-control-sm" id="apptEditPaymentMode" type="text"
                        placeholder="Enter Reason" style="background-image: none;">
                        <option value="">Select</option>
                        @foreach ($paymentmodes as $pmode)
                             <option value="{{$pmode->id}}" {{$pmode->id == 2 ? 'selected':''}}>{{$pmode->name}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Enter Reason For Cancel
                    </div>
                  </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm apptEditSubmit" type="button" onclick="updatePayment(document.getElementById('apptEdit_id').value)">Submit</button>
                        <button class="btn btn-primary-600  btn-sm fw-normal apptEditSpinn d-none" type="button">
                          <span class="sr-only">please wait...</span>
                        </button>
                    </div>
             </form>
        </div>
      </div>
    </div>
  </div>
 <!-- paid amount model end-->
     <!-- paid amount modal start -->
     @php
        $ddate = date('d-m-Y');
     @endphp
  <div class="modal fade" id="appointment-visit-modal" tabindex="-1" role="dialog" aria-labelledby="appointment-visit-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Visit Appointment</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
               {{-- <form action="" id="apptEdit-form" class="needs-validation" novalidate="">
                @csrf --}}
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <input type="hidden" id="apptVisit_id">
                    <label class="form-label" for="apptVisitDate">Visit Date</label>
                    <input type="date" id="apptVisitDate" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
                    <div class="apptVisitDate_errorCls d-none"></div>
                    </div>
                  </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm apptVisitSubmit" type="button" onclick="updateVisit(document.getElementById('apptVisit_id').value)">Submit</button>
                        <button class="btn btn-primary-600  btn-sm fw-normal apptVisitSpinn d-none" type="button">
                          <span class="sr-only">please wait...</span>
                        </button>
                    </div>
             {{-- </form> --}}
        </div>
      </div>
    </div>
      <div class="modal fade" id="appointment-dateedit-modal" tabindex="-1" role="dialog" aria-labelledby="appointment-dateedit-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Appointment Date Edit</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
               {{-- <form action="" id="apptEdit-form" class="needs-validation" novalidate="">
                @csrf --}}
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <input type="hidden" id="apptDateEdit_id">
                    <label class="form-label" for="apptNewDate">Appointment New Date</label>
                    <input type="date" id="apptNewDate" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
                    <div class="apptNewDate_errorCls d-none"></div>
                    </div>
                  </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm apptVisitSubmit" type="button" onclick="updateAppointmentDate(document.getElementById('apptDateEdit_id').value)">Submit</button>
                        <button class="btn btn-primary-600  btn-sm fw-normal apptVisitSpinn d-none" type="button">
                          <span class="sr-only">please wait...</span>
                        </button>
                    </div>
             {{-- </form> --}}
        </div>
      </div>
    </div>
  </div>
 <!-- paid amount model end-->
  <!-- delete reason modal start -->
  <div class="modal fade" id="appointment-delete-modal" tabindex="-1" role="dialog" aria-labelledby="appointment-delete-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Add Reason For Appointment Cancel</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              {{-- <form action="" id="reason-form" class="needs-validation" novalidate="">
                @csrf --}}
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="apptdeleteReason">Reason</label>
                    <input type="hidden" id="appt_id">
                    <input class="form-control form-control-sm" id="apptdeleteReason" type="text"
                        placeholder="Enter Reason" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Reason For Cancel
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm reasonSubmit" type="submit" onclick="reasonSubmitDelete(document.getElementById('appt_id').value)">Submit</button>
                    </div>
              {{-- </form> --}}
        </div>
      </div>
    </div>
  </div>
 <!-- delete reason model end-->
@endsection
@section('extra-js')
<script>
  const viewAppointments = "{{route('appointment.viewAppointments')}}";
  const addNewPatient = "{{route('appointment-patient.addNewPatient')}}"; // this route also used in 'billing-add.blade.php' page
  const searchPatient = "{{route('appointment-patient.searchPatient')}}";
  const getPatient = "{{route('appointment-patient.getPatient')}}";
  const appointmentBook = "{{route('appointment-booking.appointmentBook')}}";
  const getAppointmentData = "{{route('appointment-booking.getAppointmentData')}}";
  const updateAppointmentData = "{{route('appointment-booking.updateAppointmentData')}}";
  const deleteAppointmentData = "{{route('appointment-booking.deleteAppointmentData')}}";
  const getDoctorList = "{{route('appointment-booking.getDoctorList')}}";
  const getDoctorAddedData = "{{route('appointment-booking.getDoctorAddedData')}}";
  const getDoctorData = "{{route('appointment-booking.getDoctorData')}}";
  const updateAppointmentVisitData = "{{route('appointment-booking.updateAppointmentVisitData')}}";
  const appointmentDataUpdate = "{{route('appointment-booking.appointmentDataUpdate')}}";
  const getPatientDataUsingMobile ="{{route('common.getPatientData')}}"; // also used in ipd-in.blade
     const fillPatientData = "{{route('common.fillPatientData')}}"; 

</script>
  {{-----------external js files added for page functions------------}}
<script src="{{asset('backend/assets/js/custom/admin/appointment/appointment.js')}}"></script>
<script>
  //  -- select2 js library included for dropdown search and select box.. other method for implenting used due to boostrap conflicts--
 $('#add-appointment').on('shown.bs.modal', function () {
    $('.select2-cls').select2({
        dropdownParent: $('#add-appointment')
    });
});
 $('#add-patient').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#add-patient')
      });
    });

    // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "Y-m-d",
        });
    }
    
    flatpickr("#patientDOB", {
        dateFormat: "d-m-Y",
        defaultDate: "today",
        onReady: function (selectedDates, dateStr, instance) {
            const yearElement = instance.currentYearElement;
            yearElement.addEventListener("click", function () {
                const yearList = document.createElement("select");
                // Create year options
                for (let y = 1925; y <= new Date().getFullYear(); y++) {
                    const opt = document.createElement("option");
                    opt.value = y;
                    opt.text = y;
                    if (y === parseInt(yearElement.value)) {
                        opt.selected = true;
                    }
                    yearList.appendChild(opt);
                }
                // Replace input with dropdown
                yearElement.parentNode.replaceChild(yearList, yearElement);
                // Update Flatpickr on change
                yearList.addEventListener("change", function () {
                    instance.changeYear(parseInt(this.value));
                    instance.redraw();
                });
            });
        }
    });
    getDatePicker('#dateAppt'); 
    // getDatePicker('#patientDOB'); 
    getDatePicker('#apptVisitDate'); 
    getDatePicker('#apptNewDate'); 

</script>

@endsection