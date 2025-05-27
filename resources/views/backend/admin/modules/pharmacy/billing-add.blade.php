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
            <p class="mt-3 fw-medium">Bill No : <span class="fw-normal">456789</span></p>
            <p class="mt-3 fw-medium mx-5">Date : <span class="fw-normal">09/05/2025 12:40 PM</span></p>
          </div>
          <div class="d-flex align-items-center">
              <div class="mx-1">
                <form class="navbar-search">
                <input type="text" name="search" placeholder="Search Patient" class="patient-search">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
              </form>
              </div>
              <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#add-patient">
                  <i class="ri-add-line"></i>
              </button>
          </div>
        </div>
      </div>
             <!-- <div class="bg-neutral-100 d-flex justify-content-between align-items-center  px-11">
                <div class=" ">
                    <p class="mt-3 fw-medium">Bill No : <span class="fw-normal">456789</span></p>
                </div>

                <div class="d-flex align-items-center">
                    <div class="">
                        <form class="navbar-search w-50">
                      <input type="text" name="search" placeholder="Search Patient" class="patient-search">
                       <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                    </div>
                    <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center">
                        <i class="ri-add-line"></i>
                    </button>
                </div>
                <div class="">
                    <p class="mt-3 fw-medium">Date : <span class="fw-normal">09/05/2025 12:40 PM</span></p>
                </div>
            </div>
      </div> -->
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
                                  Tax
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Amount (₹)
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr class="fieldGroup">
                           <td>
                                  <select id="billingAdd-category0" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getMedicine(this.value)">
                                      <option selected disabled>Select</option>
                                      @foreach ($medicines as $category)
                                      <option value="{{$category->category}}">{{$category->categoryData->name}}</option>
                                      @endforeach
                                  </select>
                              </td>
                              <td>
                                  <select id="billingAdd-name0" name="billingAdd-name[]" class="form-select form-select-sm select2-cls w-100">
                                      <option selected disabled>Select</option>
                                      <span class="medicine-names"></span>
                                      {{-- <option value="1">Azethromicne</option>
                                      <option value="2">Paracitamol</option>
                                      <option value="3">Lisinopril</option>
                                      <option value="4">Amlodipine</option> --}}
                                  </select>
                              </td>
                              <td>
                                  <select id="billingAdd-batch0" name="billingAdd-batch[]" class="form-select form-select-sm select2-cls w-100">
                                      <option selected>Select</option>
                                      <option value="1">Batch A</option>
                                      <option value="2">Batch B</option>
                                      <option value="3">Batch C</option>
                                  </select>
                              </td>
                              <td>
                                  <div class=" position-relative">
                                      <input id="billingAdd-expiry0" name="billingAdd-expiry[]" class="form-control radius-8 bg-base expiry-date"  type="text" placeholder="12/2024">
                                      <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                                  </div>
                              </td>
                              <td>
                                  <input id="billingAdd-qty0" name="billingAdd-qty[]" name="billingAdd-name" class="form-control form-control-sm" type="number" placeholder="Quantity">
                              </td>
                              <td>
                                  <input id="billingAdd-avlQty0" name="billingAdd-avlQty[]" type="number" class="form-control form-control-sm" placeholder="Avilable Qty">
                              </td>
                              <td>
                                  <input id="billingAdd-salesPrice0" name="billingAdd-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price">
                              </td>
                              <td>
                                  <input id="billingAdd-tax0" name="billingAdd-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax">
                              </td>
                              <td>
                                  <input id="billingAdd-amount0" name="billingAdd-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount">
                              </td>
                              {{-- <td>
                                    <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove">
                                      <i class="ri-close-line"></i>
                                  </button>
                              </td> --}}
                          </tr>
                         
                          <!-- replica table end -->
                          <tr class="newRowAppendBilling">
                             
                          </tr>
                          <!-- replica table end -->
                      </tbody>
                  </table>
                  <button class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRowBilling()">
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
                         <select class="form-select form-select-sm select2 medician-category w-100">
                            <option selected disabled>Res Doctor</option>
                            <option value="1">Doctor</option>
                            <option value="2">Doctor</option>
                            <option value="3">Doctor</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                         <label class="form-label fw-medium">Out Doctor Name</label>
                         <input type="text" class="form-control form-control-sm" placeholder="Out Doctor Name">
                      </div>
                   </div>
                  <label class="form-label fw-medium">Note</label>
                  <textarea name="#0" class="form-control " rows="4" cols="50" placeholder="Note"></textarea>
              </div>
              <div class="col-md-4 offset-2">
                  <table class="table table-sm">
                    <tr>
                      <td class="border-0" colspan="2">Total (₹)</td>
                      <td class="border-0 text-end fs-6">204.00</td>
                    </tr>
                    <tr>
                      <td class="border-0 align-middle">Discount (₹)</td>
                      <td class="border-0"><div class="d-flex align-items-center"><input class="form-control form-control-sm discount-value-field" type="text" placeholder="Discount"><span class="ms-1">%</span></div></td>
                      <td class="border-0 text-end fs-6">152.00</td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Taxes (₹)</td>
                      <td class="border-0 text-end fs-6">5124.00</td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Net Amount (₹)</td>
                      <td class="border-0 text-end fs-6">20412.00</td>
                    </tr>
                    <tr>
                      <td colspan="2" class="border-0">
                        <select class="form-select form-select-sm ">
                          <option>Payment Mode</option>
                          <option>Card</option>
                          <option>UPI</option>
                          <option>Cash</option>
                      </select></td>
                      <td class="border-0">
                         <input type="number" class="form-control form-control-sm" placeholder="Payment Amount">
                      </td>
                    </tr>
                  </table>
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


<!-- modal add-patient start -->
 <div class="modal fade" id="add-patient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-appointmentLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header p-11">
            <h6 class="modal-title fw-normal text-lg" id="add-appointmentLabel">Patient Details</h6>
            <button type="button" class="btn-close text-sm " data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
             <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-normal">Patient Name</label>
              <input type="text" name="#0" class="form-control form-control-sm" placeholder="Patient Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-normal">Gaurdian Name</label>
              <input type="text" name="#0" class="form-control form-control-sm" placeholder="Gaurdian Name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-normal mb-3">Gender</label>
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
            
            <div class="col-md-3">
              <!-- <label class="form-label fw-normal">DOB</label>
              <input type="date" class="form-control form-control-sm"> -->
              <label for="DOB" class="form-label fw-normal ">DOB</label>
                  <div class=" position-relative">
                      <input class="form-control form-control-sm radius-8 bg-base" id="dob" type="text" placeholder="03/12/2024">
                      <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                  </div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-normal">Blood type</label>
              <select class="form-select form-select-sm">
                <option>A+</option>
                <option>B+</option>
                <option>AB+</option>
                <option>O+</option>
                <option>A-</option>
                <option>B-</option>
                <option>AB-</option>
                <option>O-</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-normal">Marital Status</label>
              <select class="form-select form-select-sm">
                <option>Single</option>
                <option>Married</option>
                <option>Divorced</option>
                <option>Widowed</option>
                <option>Separated</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-normal">Address</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Address">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-normal">Phone</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Phone">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-normal">Alt Phone</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Alt Phone">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-normal">Any Known Allergies</label>
              <input type="text"  class="form-control form-control-sm" placeholder="Any Known Allergies">
            </div>
          </div>
          </div>
          <div class="modal-footer ">
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
            <!-- <button type="button" class="btn btn-lilac-600  btn-sm fw-normal">Save & Book Appointment</button> -->
          </div>
        </div>
      </div>
    </div>
<!-- modal add-patient end -->
@endsection
@section('extra-js')
<script>
 const getMedicineNames = "{{route('billing.getMedicineNames')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/billing-add.js')}}"></script>
@endsection