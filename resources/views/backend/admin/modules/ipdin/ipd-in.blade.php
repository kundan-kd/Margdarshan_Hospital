@extends('backend.admin.layouts.main')
@section('title')
    IPD
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
      <div class="d-flex flex-wrap align-items-center justify-content-between  mb-24">
          <h6 class="fw-normal mb-0">IPD - In Patient</h6>
          <div class="d-flex flex-wrap align-items-center gap-2">
              <a class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1 ipd-add-patientLabel" data-bs-toggle="modal" data-bs-target="#ipd-add-patient" onclick="resetAddPatient();getBedData()"> <i class="ri-add-line"></i> Add IPD Patient</a>
              <!--<button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>-->
          </div>
     </div>
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0 w-100" id="ipd-in-patient-list" data-page-length='10'>
          <thead>
            <tr >
              <th scope="col" class="fw-medium">Patient ID</th>
              <th scope="col" class="fw-medium">Depertment</th>
              <th scope="col" class="fw-medium">Bed No.</th>
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
           </tr> --}}
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
  <!-- modal2 start -->
<div class="modal fade" id="ipd-add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-patientLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="ipd-add-patientLabel">Add Patient</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close" onclick="reopenAppointment()"></button>
      </div>
      <form action="" id="ipd-addPatientForm">
        <div class="modal-body">
            <div class="row gy-3">
          <div class="col-6">
            <input type="hidden" id="ipdPatientId">
            <label class="form-label fw-normal" for="ipd-patientName">Patient Name</label>
            <input type="text" id="ipd-patientName" name="#0" class="form-control form-control-sm" placeholder="Patient Name" oninput="validateField(this.id,'input')">
            <div class="ipd-patientName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-guardianName">Gaurdian Name</label>
            <input type="text" id="ipd-guardianName" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name" oninput="validateField(this.id,'input')">
            <div class="ipd-guardianName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal mb-3">Gender</label>
              <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="ipd-patientGender" id="ipd-patientGender1" value="Male">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="ipd-patientGender" id="pipd-atientGender2" value="Female">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="ipd-patientGender" id="ipd-patientGender3" value="Other">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender3"> Other </label>
                </div>
              </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm select2-cls" id="ipd-patientBloodType"style="width:100%" oninput="validateField(this.id,'select')">
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
            <div class="ipd-patientBloodType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientDOB">DOB</label>
            <input type="date" id="ipd-patientDOB" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
            <div class="ipd-patientDOB_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientMStatus">Marital Status</label>
            <select class="form-select form-select-sm select2-cls" id="ipd-patientMStatus" style="width: 100%" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="ipd-patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientMobile">Phone</label>
            <input type="number" id="ipd-patientMobile" class="form-control form-control-sm" placeholder="Phone" oninput="this.value=this.value.slice(0,10);validateField(this.id,'mobile')">
            <div class="ipd-patientMobile_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientAddess">Address</label>
            <input type="text" id="ipd-patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="ipd-patientAddess_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="number" id="ipd-patientAltMobile" class="form-control form-control-sm" placeholder="Alt Phone" oninput="this.value=this.value.slice(0,10)">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="ipd-patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="ipd-patientBedNum">Bed Number</label>
            <select class="form-select form-select-sm select2-cls" id="ipd-patientBedNum" style="width: 100%" onchange="getBedDetails(this.value)" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
            </select>
            <div class="ipd-patientBedNum_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Bed Type</label>
            <input type="text" id="ipd-patientBedType"  class="form-control form-control-sm" placeholder="Bed Type" readonly>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Floor</label>
            <input type="text" id="ipd-patientBedFloor"  class="form-control form-control-sm" placeholder="Floor No." readonly>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Bed Charge</label>
            <input type="text" id="ipd-patientBedCharge"  class="form-control form-control-sm" placeholder="Bed Charge" readonly>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal ipdPatientSubmit">Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal ipdPatientUpdate d-none" onclick="ipdPatientUpdate(document.getElementById('ipdPatientId').value)">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal 2 end -->
@endsection
@section('extra-js')
<script>
  $('#ipd-add-patient').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#ipd-add-patient')
      });
    });
    // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y ",
        });
    }
    getDatePicker('#ipd-patientDOB'); 

  const addNewPatientIpd = "{{route('ipd-addPatient')}}"; 
  const viewPatientsIpd = "{{route('ipd-viewPatients')}}"; 
  const getIpdPatientData = "{{route('ipd-getIpdPatientData')}}"; 
  const ipdPatientDataUpdate = "{{route('ipd-ipdPatientDataUpdate')}}"; 
  const ipdPatientDataDelete = "{{route('ipd-ipdPatientDataDelete')}}"; 
  const getBedDetailsIpd = "{{route('ipd-getBedDetailsIpd')}}"; 
  const getBedDataIpd = "{{route('ipd-getBedDataIpd')}}"; 
  

  
</script>
  <script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin.js')}}"></script>
@endsection