@extends('backend.admin.layouts.main')
@section('title')
    IPD add patient
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
      <div class="mb-24">
         <h6 class="fw-normal mb-0">Add IPD Patient</h6>
     </div>
     <div class="card">
        <div class="card-header pb-4 border-bottom-0">
        <div class=" bg-neutral-100 d-flex align-items-center gap-3 p-11">
          <select class="form-select form-select-sm select2 w-25" >
              <option selected disabled>Enter Patient Name or Id</option>
              <option value="1">Sunil Kumar</option>
              <option value="2">Gautam Singh</option>
              <option value="3">Pardep Kumar</option>
          </select>
          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ipd-add-patient"> <i class="ri-add-line"></i> New Patient</button>
        </div>
      </div>
      <div class="card-body">
       <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row gy-3">
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Type</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Cough</option>
              </select>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Title</label>
               <select class="form-select form-select-sm  " >
                 <option selected>Select</option>
              </select>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Description</label>
               <textarea  class="form-control " rows="1" placeholder="Symptoms Description"></textarea>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Previous Medical Issue</label>
               <textarea  class="form-control " rows="1" placeholder="Previous Medical Issue"></textarea>
             </div>
             <div class="col-md-12">
               <label class="form-label fw-medium">Note</label>
               <textarea  class="form-control " rows="2" placeholder="Note"></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Admission Date <sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control form-control-sm radius-8 bg-base ipd-add-admission-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Case</label>
              <input type="text" class="form-control form-control-sm" placeholder="Case">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Casualty</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <div class="col-md-6">
               <label class="form-label fw-medium">Old Patient</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium"> Credit Limit (₹) <sup class="text-danger">*</sup></label>
              <input type="number" class="form-control form-control-sm" placeholder="200000">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Reference</label>
              <input type="text" class="form-control form-control-sm" placeholder="Reference">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Consultant Doctor <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Sunil Kumar (1234)</option>
                 <option value="1">Manoj Gupta (2224)</option>
                 <option value="1">Arjun Kumar (2234)</option>
                 <option value="1">Suraj Kumar (9234)</option>
              </select>
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium"> Bed Group </label>
               <select class="form-select form-select-sm">
                    <option value="">Select</option>
                        <option value="1">VIP Ward - Ground  Floor</option>
                        <option value="2">Private Ward - 3rd Floor</option>
                        <option value="3">General Ward Male - 3rd Floor</option>
                        <option value="4">ICU - 2nd Floor</option>
                        <option value="5">NICU - 2nd Floor</option>
                        <option value="6">AC (Normal) - 1st Floor</option>
                        <option value="7">Non AC - 4th Floor</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium">Bed Number  <sup class="text-danger">*</sup></label>
             <select class="form-select form-select-sm select2" >
                 <option selected>10</option>
                 <option value="1">11</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Live Consultation</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div>
       </div>
     </div>
     <div class=" pharmacy-footer card-footer border-top">
        <div class="text-end">
              <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
          </div>
      </div>
     </div>
  </div>
<!-- ipd-add-patient start -->
     <div class="modal fade" id="ipd-add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-patientLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-500">
            <h6 class="modal-title fw-normal text-md text-white" id="ipd-add-patientLabel">Patient Details</h6>
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
             <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Patient Name</label>
              <input type="text" name="#0" class="form-control form-control-sm" placeholder="Patient Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Gaurdian Name</label>
              <input type="text" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium mb-3">Gender</label>
               <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="horizontal" id="horizontal1">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="horizontal1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="horizontal" id="horizontal2">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="horizontal2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="horizontal" id="horizontal3">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="horizontal3"> other </label>
                </div>
               </div>
            </div>
            <div class="col-md-2">
              <label class="form-label fw-medium">Blood type</label>
              <select class="form-select form-select-sm">
                <option>A+</option>
                <option>B+</option>
                <option>O</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="DOB" class="form-label fw-medium ">DOB</label>
                  <div class=" position-relative">
                      <input class="form-control form-control-sm  bg-base" id="dob" type="text" placeholder="03/12/2024">
                      <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                  </div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Marital Status</label>
              <select class="form-select form-select-sm">
                <option>Married</option>
                <option>Unmarried</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Phone</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Phone">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-medium">Alt Phone</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Alt Phone">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Address</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Address">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Any Known Allergies</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Any Known Allergies">
            </div>
          </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
  <!-- ipd-add-patient end -->
@endsection
@section('extra-js')
<script>
</script>
@endsection