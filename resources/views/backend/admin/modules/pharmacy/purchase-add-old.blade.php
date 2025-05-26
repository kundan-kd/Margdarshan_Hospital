@extends('backend.admin.layouts.main')
@section('title')
purchase-add
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/purchase.css')}}">
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Purchase Bill</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class="row bg-neutral-100 align-items-center mx-2">
                 <!-- <div class="col-md-1"><label class="form-label fw-medium mb-0">Bill No :</label></div>
                 <div class="col-md-2"><input class="form-control form-control-sm" type="number" placeholder="Bill No" ></div> -->
                <div class="col-md-3 d-flex align-items-center">
                    <span class="form-label fw-medium mb-0" style="width: 28%;">Bill No :</span>
                    <input class="form-control form-control-sm" type="number" placeholder="Bill No">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <select class="form-select form-select-sm select2 medician-category" style="width: 100%;">
                        <option selected disabled>Select Supplier</option>
                        <option value="1">Sunil Kumar</option>
                        <option value="2">Gautam Singh</option>
                        <option value="3">Pardep Kumar</option>
                    </select>
                </div>
                <div class="col-md-3 offset-md-3 text-end">
                    <p class="mt-3 fw-medium">Date : <span class="fw-normal">09/05/2025 12:40 PM</span></p>
                </div>
            </div>
      </div>
      <div class="card-body pharmacy-purchase-content pt-1">
          <div class="row mb-3">
              <div class="col-md-12">
                  <table class="pharmacy-purchase-bill-table table table-hover">
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
                                  MRP
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Sale Price
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Tax
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Qty
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Purchase Rate
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Amount
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr class="fieldGroup">
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category w-100">
                                      <option selected disabled>Select</option>
                                      <option value="1">cat A</option>
                                      <option value="2">Cat B</option>
                                      <option value="3">Cat C</option>
                                  </select>
                              </td>
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category w-100">
                                      <option selected disabled>Select</option>
                                      <option value="1">Azethromicne</option>
                                      <option value="2">Paracitamol</option>
                                      <option value="3">Lisinopril</option>
                                      <option value="4">Amlodipine</option>
                                  </select>
                              </td>
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category w-100">
                                      <option selected>Select</option>
                                      <option value="1">Batch A</option>
                                      <option value="2">Batch B</option>
                                      <option value="3">Batch C</option>
                                  </select>
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="text" placeholder="Expiry Date">
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="number" placeholder="MRP">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Sale Price">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Tax">
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="number" placeholder="Qty">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Purchase Rate">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Amount">
                              </td>
                              <td>
                                  <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore">
                                      <i class="ri-add-line"></i>
                                  </button>
                              </td>
                          </tr>
                          <!-- replica table start -->
                          <tr class="fieldGroupCopy" style="display: none;">
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category" style="width: 100%;">
                                      <option selected disabled>Select</option>
                                      <option value="1">cat A</option>
                                      <option value="2">Cat B</option>
                                      <option value="3">Cat C</option>
                                  </select>
                              </td>
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category" style="width: 100%;">
                                      <option selected disabled>Select</option>
                                      <option value="1">Azethromicne</option>
                                      <option value="2">Paracitamol</option>
                                      <option value="3">Lisinopril</option>
                                      <option value="4">Amlodipine</option>
                                  </select>
                              </td>
                              <td>
                                  <select class="form-select form-select-sm select2 medician-category" style="width: 100%;">
                                      <option selected>Select</option>
                                      <option value="1">Batch A</option>
                                      <option value="2">Batch B</option>
                                      <option value="3">Batch C</option>
                                  </select>
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="text" placeholder="Expiry Date">
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="number" placeholder="MRP">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Sale Price">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Tax">
                              </td>
                              <td>
                                  <input class="form-control form-control-sm" type="number" placeholder="Qty">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Purchase Rate">
                              </td>
                              <td>
                                  <input type="number" class="form-control form-control-sm" placeholder="Amount">
                              </td>
                              <td>
                                  <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove">
                                      <i class="ri-close-line"></i>
                                  </button>
                              </td>
                          </tr>
                          <!-- replica table end -->
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="row">
              <div class="col-md-6">
                  <label class="form-label">Note</label>
                  <textarea name="#0" class="form-control " rows="4" cols="50" placeholder="Note"></textarea>
              </div>
              <div class="col-md-4 offset-md-2">
                  <div class="border p-11 my-11">
                      <div class="d-flex justify-content-between align-items-center fw-normal text-sm">
                          <p class="mb-1">Total</p>
                          <p class="mb-1">$204</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center fw-normal text-sm">
                          <p class="mb-1">Discount</p>
                          <p class="mb-1">15%</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center fw-normal text-sm">
                          <p class="mb-1">Taxes</p>
                          <p class="mb-1">5%</p>
                      </div>
                      <div class="d-flex justify-content-between align-items-center fw-normal text-sm">
                          <p class="mb-1">Net Amoun</p>
                          <p class="mb-1">$182</p>
                      </div>
                  </div>
                  <div class="d-flex justify-contant-between align-items-center">
                      <select class="form-select form-select-sm ">
                          <option>Payment Mode</option>
                          <option>Card</option>
                          <option>UPI</option>
                          <option>Cash</option>
                      </select>
                      <p class="mb-1 w-100 text-end">
                          <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center" >
                            
                                  <i class="ri-add-line cursor-pointer"></i>
                            
                          </button>
                      </p>
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
<!-- modal extra-field start -->
<div class="modal fade" id="extra-field" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="extra-fieldLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title fw-normal text-lg" id="extra-fieldLabel">Extra field</h6>
              <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               Extra field
            </div>
            <div class="modal-footer pt-2 pb-3 border-top-0">
              <button type="button" class="btn btn-primary-600  btn-sm fw-normal">Save</button>
              <button type="button" class="btn btn-lilac-600  btn-sm fw-normal">Save & Print</button>
            </div>
          </div>
        </div>
      </div>
<!-- modal extra-field end -->

<!-- modal payment-detail start -->
<div class="modal fade" id="payment-detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="payment-detailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title fw-normal text-lg" id="payment-detailLabel">Extra field</h6>
              <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               Extra field
            </div>
            <div class="modal-footer pt-2 pb-3 border-top-0">
              <button type="button" class="btn btn-primary-600  btn-sm fw-normal">Save</button>
              <button type="button" class="btn btn-lilac-600  btn-sm fw-normal">Save & Print</button>
            </div>
          </div>
        </div>
      </div>
@endsection
@section('extra-js')
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase-add.js')}}"></script>
@endsection