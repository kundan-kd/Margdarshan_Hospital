@extends('backend.admin.layouts.main')
@section('title')
purchase-edit
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/purchase.css')}}">
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Edit Purchase</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class="row bg-neutral-100 align-items-center mx-2">
                 <!-- <div class="col-md-1"><label class="form-label fw-medium mb-0">Bill No :</label></div>
                 <div class="col-md-2"><input class="form-control form-control-sm" type="number" placeholder="Bill No" ></div> -->
                <div class="col-md-3 d-flex align-items-center">
                   <input type="hidden" id="purchaseEdit_purchase_id" value="{{$purchase[0]->id}}">
                    <span class="form-label fw-medium mb-0" style="width: 28%;">Bill No :</span>
                    <input id="purchaseEdit_billNo" class="form-control form-control-sm" type="text" value="{{$purchase[0]->bill_no}}">
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <select id="purchaseEdit_vendor" class="form-select form-select-sm select2 medician-category" style="width: 100%;">
                        <option selected disabled>Select Vendor</option>
                        <option value="Sunil Kumar" {{$purchase[0]->vendor_id == 'Sunil Kumar' ? 'selected' : ''}}>Sunil Kumar</option>
                        <option value="Gautam Singh" {{$purchase[0]->vendor_id == 'Gautam Singh' ? 'selected' : ''}}>Gautam Singh</option>
                        <option value="Pardep Kumar" {{$purchase[0]->vendor_id == 'Pardep Kumar' ? 'selected' : ''}}>Pardep Kumar</option>
                        <option value="Mukesh Kumar" {{$purchase[0]->vendor_id == 'Mukesh Kumar' ? 'selected' : ''}}>Mukesh Kumar</option>
                    </select>
                </div>
                <div class="col-md-3 offset-md-3 text-end">
                    <p class="mt-3 fw-medium">Date : <span class="fw-normal">09/05/2025 12:40 PM</span></p>
                </div>
            </div>
      </div>
      <div class="sumTaxAmountClsEdit" style="display: none;"></div>
      <form id="purchaseEdit_form">
      <div class="card-body pharmacy-purchase-content pt-1">
          <div class="row mb-3">
              <div class="col-md-12">
                  <table class="pharmacy-purchase-bill-table table table-hover">
                      <thead >
                          <tr class="border-bottom">
                              <th class="text-nowrap text-neutral-700">
                                  Category
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Name
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
                                  Qty
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Purchase Rate
                              </th>
                               <th class="text-nowrap text-neutral-700">
                                  Tax
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Amount
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                        @php

                      //      dd($currTaxData);
                        @endphp
                      
                        @foreach ($purchaseItems as $purchaseItem)
                          <tr class="fieldGroup">
                              <td>
                                <input type="hidden" id="purchaseEdit_id{{$purchaseItem->id}}" name="purchaseEdit_id[]" value="{{$purchaseItem->id}}">
                             
                                  <select id="purchaseEdit_category{{$purchaseItem->id}}" name="purchaseEdit_category[]" class="form-select form-select-sm select2Edit-cls" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Syrup" {{ $purchaseItem->category_id == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                        <option value="Injection" {{ $purchaseItem->category_id == 'Injection' ? 'selected' : '' }}>Injection</option>
                                        <option value="Capsule" {{ $purchaseItem->category_id == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                        <option value="Tablet" {{ $purchaseItem->category_id == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="Ointment" {{ $purchaseItem->category_id == 'Ointment' ? 'selected' : '' }}>Ointment</option>

                                  </select>
                              </td>
                              <td>
                                  <select id="purchaseEdit_name{{$purchaseItem->id}}" name="purchaseEdit_name[]" class="form-select form-select-sm select2Edit-cls" required>
                                      <option value="" selected disabled>Select</option>
                                        <option value="Paracitamol" {{ $purchaseItem->name_id == 'Paracitamol' ? 'selected' : '' }}>Paracitamol</option>
                                        <option value="Azrithimycin" {{ $purchaseItem->name_id == 'Azrithimycin' ? 'selected' : '' }}>Azrithimycin</option>
                                        <option value="Aceloc" {{ $purchaseItem->name_id == 'Aceloc' ? 'selected' : '' }}>Aceloc</option>
                                        <option value="Calpol" {{ $purchaseItem->name_id == 'Calpol' ? 'selected' : '' }}>Calpol</option>
                                        <option value="Metrogly" {{ $purchaseItem->name_id == 'Metrogly' ? 'selected' : '' }}>Metrogly</option>
                                        <option value="Oxalgin" {{ $purchaseItem->name_id == 'Oxalgin' ? 'selected' : '' }}>Oxalgin</option>
                                        <option value="Metacin" {{ $purchaseItem->name_id == 'Metacin' ? 'selected' : '' }}>Metacin</option>

                                  </select>
                              </td>
                              <td>
                                  <input id="purchaseEdit_batch{{$purchaseItem->id}}" name="purchaseEdit_batch[]" class="form-control form-control-sm" type="text" value="{{$purchaseItem->batch_no}}" required>
                              </td>
                              <td>
                                  <input id="purchaseEdit_expiry{{$purchaseItem->id}}" name="purchaseEdit_expiry[]" class="form-control form-control-sm expiry-date" type="text" value="{{$purchaseItem->expiry}}" required>
                              </td>
                              <td>
                                  <input id="purchaseEdit_mrp{{$purchaseItem->id}}" name="purchaseEdit_mrp[]" class="form-control form-control-sm" type="number"value="{{$purchaseItem->mrp}}" required>
                              </td>
                              <td>
                                  <input id="purchaseEdit_salesPrice{{$purchaseItem->id}}" name="purchaseEdit_salesPrice[]" type="number" class="form-control form-control-sm" value="{{$purchaseItem->sales_price}}" required>
                              </td>
                              
                              <td>
                                  <input id="purchaseEdit_qty{{$purchaseItem->id}}" name="purchaseEdit_qty[]" class="form-control form-control-sm" type="number" value="{{$purchaseItem->qty}}" oninput="getAmountEdit({{$purchaseItem->id}})" required>
                              </td>
                              <td>
                                  <input id="purchaseEdit_purchaseRate{{$purchaseItem->id}}" name="purchaseEdit_purchaseRate[]" type="number" class="form-control form-control-sm" value="{{$purchaseItem->purchase_rate}}" oninput="getAmountEdit({{$purchaseItem->id}})" required>
                              </td>
                              <td>
                                  <input id="purchaseEdit_tax{{$purchaseItem->id}}" name="purchaseEdit_tax[]" type="number" class="form-control form-control-sm" value="{{$purchaseItem->tax}}" oninput="getTaxEdit({{$purchaseItem->id}})" required>
                              </td>
                              @php
                                  $taxx = ($purchaseItem->amount * $purchaseItem->tax)/100;
                              @endphp
                              <td style="display: none;">
                                  <input id="purchaseEdit_taxAmount{{$purchaseItem->id}}" name="purchaseEdit_taxAmount[]" type="number" class="form-control form-control-sm"value="{{$taxx}}">
                              </td>
                              <td>
                                  <input id="purchaseEdit_amount{{$purchaseItem->id}}" name="purchaseEdit_amount[]" type="number" class="form-control form-control-sm" value="{{$purchaseItem->amount}}" readonly>
                              </td>
                               <td>
                                  <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center" onclick="deleteRowEdit(this)">
                                      <i class="ri-delete-bin-line"></i>
                                  </button>
                                  {{-- <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center" onclick="deleteRowEdit(this,{{$purchaseItem->id}},{{$purchaseItem->amount}},{{$purchaseItem->tax}})">
                                      <i class="ri-delete-bin-line"></i>
                                  </button> --}}
                              </td>
                          </tr>
                          @endforeach
                          <!-- copy table start -->
                          <tr class="newRowAppendEdit"></tr>
                          <!-- copy table end -->
                      </tbody>
                    
                  </table>
                    <div>
                        <button class="mx-1 fw-semibold w-64-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRowEdit()">
                        <i class="ri-add-line">Add</i>
                        </button>
                    </div>
              </div>
          </div>

          <div class="row">
              <div class="col-md-6">
                  <label class="form-label">Note</label>
                  <textarea id="purchaseAdd_naration" name="#0" class="form-control " rows="4" cols="50" placeholder="Note"></textarea>
              </div>
               <div class="col-md-4 offset-2">
                  <table class="table table-sm">
                    <tr>
                      <td class="border-0" colspan="2">Total</td>
                      <td class="border-0 text-end fs-6">₹ <span class="purchaseEdit_totalAmt">{{$purchase[0]->total_amount}}</span></td>
                    </tr>
                    <tr>
                      <td class="border-0 align-middle">Discount</td>
                      <td class="border-0"><div class="d-flex align-items-center">
                        <input id="purchaseEdit_discount" class="form-control form-control-sm discount-value-field" type="text" value="{{$purchase[0]->total_discount_per}}" placeholder="Discount" oninput="updateAmountEdit()"><span class="ms-1">%</span></div>
                      </td>
                      <td class="border-0 text-end fs-6">₹ <span class="purchaseEdit_discountAmt">{{$purchase[0]->total_discount}}</td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Taxes</td>
                      <td class="border-0 text-end fs-6">₹ <span class="purchaseEdit_taxAmt">{{$purchase[0]->total_tax}}</span></td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Net Amount</td>
                      <td class="border-0 text-end fs-6">₹ <span class="purchaseEdit_netTotalAmt">{{$purchase[0]->net_amount}}</span></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="border-0">
                        <select id="purchaseEdit_paymentMode" class="form-select form-select-sm ">
                          <option value="">Payment Mode</option>
                          <option value="Card" {{$purchase[0]->payment_mode == 'Card' ? 'selected' : ''}}>Card</option>
                          <option value="UPI" {{$purchase[0]->payment_mode == 'UPI' ? 'selected' : ''}}>UPI</option>
                          <option value="Cash" {{$purchase[0]->payment_mode == 'Cash' ? 'selected' : ''}}>Cash</option>
                      </select></td>
                      <td class="border-0">
                         <input id="purchaseEdit_payAmount" type="number" class="form-control form-control-sm" value="{{$purchase[0]->paid_amount}}">
                         <div class="purchaseEdit_payAmount_cls"></div>
                      </td>
                    </tr>
                  </table>
              </div>
          </div>
      </div>
      <div class=" pharmacy-footer card-footer border-top">
        <div class="text-end">
              <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2"><i class="ri-checkbox-circle-line"></i>Update</button>
          </div>
      </div>
      <form>
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
<script>
  const purchaseUpdateDatas = "{{route('purchase.purchaseUpdateDatas')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase-edit.js')}}"></script>
@endsection

    {{-- abc();
    function abc(){
    let taxes = JSON.parse('<?php //print_r($currTaxDataValue) ?>'); // currTaxDataValue passed from controller
    taxes.forEach(element => {
        getTaxEdit(element.id);
    });
      } --}}
