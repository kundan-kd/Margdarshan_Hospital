@extends('backend.admin.layouts.main')
@section('title')
Billing-add
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/billing.css')}}">
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Create Billing</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class=" bg-neutral-100 d-flex align-items-center justify-content-between px-11">
          <div class="d-flex align-items-center">
            @php
              $randomNumber = time().rand(10,99);
              date_default_timezone_set('Asia/Kolkata');
              $time = date('m/d/Y');
            @endphp
            <p class="mt-3 fw-medium">Bill No : <span class="fw-normal billingAdd-billNo">{{$randomNumber}}</span></p>
            <p class="mt-3 fw-medium mx-5">Date : <span class="fw-normal">{{ $time}}</span></p>
          </div>
          <div class="d-flex align-items-center">
              <div class="mx-1">
                <label for="billingAdd-patient" style="display: none;">Patient Name</label>
               <select id="billingAdd-patient" class="form-select form-select-sm select2-cls" oninput="validateField(this.id,'select')">
                <option value="">Select Patient</option>
                @foreach ($patients as $patient)
                <option value="{{$patient->id}}">{{$patient->name}} ({{$patient->patient_id}})</option>
                @endforeach
              </select>
              <div class="billingAdd-patient_errorCls d-none"></div>
              </div>
              <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#billingAdd-patientModal" onclick="resetAddPatient()">
                  <i class="ri-add-line"></i>
              </button>
          </div>
        </div>
      </div>
      <form action="" id="billingAdd-Form">
        <div class="card-body pharmacy-purchase-content pt-1">
            <div class="row mb-3">
                <div class="col-md-12">
                    <table class="pharmacy-purchase-bill-table table table-hover mb-11">
                        <thead >
                            <tr class="border-bottom">
                                <th class="text-nowrap text-neutral-700">
                                    Medicine Category
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Mediciane Name
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Batch
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Expiry Date
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Quantity
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Avilable Qty
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Sales Price (₹)
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Tax (%)
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Amount (₹)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fieldGroup">
                            <td>
                                    <select id="billingAdd-category0" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicine(this.value,0)" required>
                                        <option value="" selected>Select</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="billingAdd-name0" name="billingAdd-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetails(this.value,0)" required>
                                        <option value="" selected>Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id="billingAdd-batch0" name="billingAdd-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiry(this.value,0)" required>
                                        <option selected>Select</option>
                                    </select>
                                </td>
                                <td>
                                    <div class=" position-relative">
                                        <input id="billingAdd-expiry0" name="billingAdd-expiry[]" class="form-control radius-8 bg-base"  type="text" value="" placeholder="00/00/0000" readonly>
                                    
                                    </div>
                                </td>
                                <td>
                                    <input id="billingAdd-qty0" name="billingAdd-qty[]" name="billingAdd-name" class="form-control form-control-sm" type="number" placeholder="Quantity" oninput="getBillingAmount(0)" required>
                                </td>
                                <td>
                                    <input id="billingAdd-avlQty0" name="billingAdd-avlQty[]" type="number" class="form-control form-control-sm" value="" placeholder="Avilable Qty" readonly>
                                </td>
                                <td>
                                    <input id="billingAdd-salesPrice0" name="billingAdd-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" readonly>
                                </td>
                                <td>
                                    <input id="billingAdd-tax0" name="billingAdd-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" readonly>
                                </td>

                                <td style="display: none;">
                                    <input id="billingAdd-taxAmount0" name="billingAdd-taxAmount[]" class="form-control form-control-sm" type="number" value="">
                                </td>
                                <td>
                                    <input id="billingAdd-amount0" name="billingAdd-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" readonly>
                                </td>
                            </tr>
                            <!-- replica table end -->
                            <tr class="newRowAppendBilling">
                              {{-- new billing row appended here using js --}}
                            </tr>
                            <!-- replica table end -->
                        </tbody>
                    </table>
                    <button type="button" class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRowBilling()">
                        <i class="ri-add-line"></i> Add
                    </button>
                </div>
            </div>
            <hr class="mb-3">
            <div class="row">
                <div class="col-md-6">
                  <div class="row ">
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-medium">Res Doctor</label>
                          <select id="billingAdd-resDoctor" class="form-select form-select-sm select2-cls  w-100">
                              <option value="" selected>Select</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{$doctor->id}}">{{$doctor->firstname}} {{$doctor->lastname}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-medium">Out Doctor Name</label>
                          <input id="billingAdd-outDoctor" type="text" class="form-control form-control-sm" placeholder="Out Doctor Name">
                        </div>
                    </div>
                    <label class="form-label fw-medium">Note</label>
                    <textarea id="billingAdd-note" class="form-control " rows="4" cols="50" placeholder="Note"></textarea>
                </div>
                <div class="col-md-4 offset-2">
                    <table class="table table-sm">
                      <tr>
                        <td class="border-0" colspan="2">Total</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-totalAmount">0</span></td>
                      </tr>
                      <tr>
                        <td class="border-0 align-middle">Discount (₹)</td>
                        <td class="border-0"><div class="d-flex align-items-center"><input id="billingAdd-discountPer" class="form-control form-control-sm discount-value-field" type="text" placeholder="Discount" value="" oninput="getBillingAmount()"><span class="ms-1">%</span></div></td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-discountAmount">0</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Taxes (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-totalTax">0</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Net Amount (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-totalNetAmount">0</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="border-0">
                          <select id="billingAdd-paymentMode" class="form-select form-select-sm select2-cls">
                            <option value="">Select Payment Mode</option>
                          @foreach ($paymentmodes as $paymentmode)
                              <option value="{{$paymentmode->id}}">{{$paymentmode->name}}</option>
                          @endforeach
                        </select></td>
                        <td class="border-0">
                          <input id="billingAdd-payAmount" type="number" class="form-control form-control-sm" placeholder="Payment Amount">
                        </td>
                      </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class=" pharmacy-footer card-footer border-top">
          <div class="text-end">
                <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i>Submit</button>
            </div>
        </div>
     </form>
    </div>
</div>

<!-- modal add patient start -->
<div class="modal fade" id="billingAdd-patientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-appointmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="add-appointmentLabel">Add Patient</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="billingAdd-patientForm">
        <div class="modal-body">
            <div class="row gy-3">
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientName">Patient Name</label>
            <input type="text" id="billingAdd-patientName" name="#0" class="form-control form-control-sm" placeholder="Patient Name" oninput="validateField(this.id,'input')">
            <div class="billingAdd-patientName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-guardianName">Gaurdian Name</label>
            <input type="text" id="billingAdd-guardianName" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name" oninput="validateField(this.id,'input')">
            <div class="billingAdd-guardianName_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal mb-3">Gender</label>
              <div class="d-flex align-items-center flex-wrap gap-20 text-sm mt-2">
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="billingAdd-patientGender" id="patientGender1" value="Male">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender1"> Male</label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="billingAdd-patientGender" id="patientGender2" value="Female">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender2"> Female </label>
                </div>
                <div class="form-check checked-primary d-flex align-items-center gap-2">
                  <input class="form-check-input" type="radio" name="billingAdd-patientGender" id="patientGender3" value="Other">
                  <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="patientGender3"> Other </label>
                </div>
              </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientBloodType">Blood Type</label>
            <select class="form-select form-select-sm" id="billingAdd-patientBloodType" oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              @foreach ($bloodtypes as $bloodtype)
                <option value="{{$bloodtype->id}}">{{$bloodtype->name}}</option>
              @endforeach
            
            </select>
            <div class="billingAdd-patientBloodType_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientDOB">DOB</label>
            <input type="date" id="billingAdd-patientDOB" class="form-control form-control-sm" placeholder="DD-MM-YYYY" oninput="validateField(this.id,'select')">
            <div class="billingAdd-patientDOB_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientMStatus">Marital Status</label>
            <select class="form-select form-select-sm" id="billingAdd-patientMStatus"  oninput="validateField(this.id,'select')">
              <option value="">Select</option>
              <option value="Married">Married</option>
              <option value="UnMarried">UnMarried</option>
            </select>
            <div class="billingAdd-patientMStatus_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientMobile">Phone</label>
            <input type="text" id="billingAdd-patientMobile" class="form-control form-control-sm" placeholder="Phone" oninput="validateField(this.id,'mobile')">
            <div class="billingAdd-patientMobile_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal" for="billingAdd-patientAddess">Address</label>
            <input type="text" id="billingAdd-patientAddess"  class="form-control form-control-sm" placeholder="Address"  oninput="validateField(this.id,'input')">
            <div class="billingAdd-patientAddess_errorCls d-none"></div>
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Alt Phone</label>
            <input type="text" id="billingAdd-patientAltMobile" class="form-control form-control-sm" placeholder="Alt Phone">
          </div>
          <div class="col-6">
            <label class="form-label fw-normal">Any Known Allergies</label>
            <input type="text" id="billingAdd-patientAllergy"  class="form-control form-control-sm" placeholder="Any Known Allergies">
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal">Submit</button>
           <!-- <button type="button" class="btn btn-warning-600  btn-sm fw-normal">Save & Book Appointment</button>  -->
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- modal add patient end -->
@endsection
@section('extra-js')
<script>
  
// Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-yy ",
        });
    }
    getDatePicker('#billingAdd-patientDOB'); 
 const getBillingMedicineNames = "{{route('billing.getMedicineNames')}}";
 const getBillingCategoryDatas = "{{route('purchase.getCategoryDatas')}}";
 const getBatchNumbers = "{{route('billing-add.getBatchNumbers')}}";
 const getBatchExpiryDate = "{{route('billing-add.getBatchExpiryDate')}}";
 const billingAddNewPatient = "{{route('appointment-patient.addNewPatient')}}"; //this route is used here from 'appointment.blade.php' page
 const billingAddDatas = "{{route('billing-add.billingAddDatas')}}";

</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/billing-add.js')}}"></script>
@endsection