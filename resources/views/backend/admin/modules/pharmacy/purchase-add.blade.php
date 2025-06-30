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
    <div class="randNumNew" style="display: none;"></div>
    <div class="sumTaxAmountCls" style="display: none;"></div>
    <div class="pharmacy-purchase-wrapper card">
        <div class="card-header pb-4 border-bottom-0">
                <div class="row bg-neutral-100 align-items-center mx-2 gy-2 pb-11">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <label for="purchaseAdd_billNo" style="display:none;">Vendor Bill No.</label>
                            <span class="form-label fw-medium mb-0" style="width: 28%;">Bill No :</span> 
                            <input id="purchaseAdd_billNo" class="form-control form-control-sm" type="text" placeholder="Vendor Bill No" oninput="validateField(this.id,'input')">
                        </div>
                        <div class="purchaseAdd_billNo_errorCls d-none" style="padding-left: 75px;"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <label for="purchaseAdd_vendor" style="display:none;">Vendor</label>
                            <select id="purchaseAdd_vendor" class="form-select form-select-sm select2-cls medician-category" style="width: 100%;" oninput="validateField(this.id,'select')">
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="purchaseAdd_vendor_errorCls d-none"></div>
                    </div>
                    @php
                        $time = date('m/d/Y');
                    @endphp
                    <div class="col-md-3 offset-md-3 text-end">
                        <p class="mb-0 fw-medium">Date : <span class="fw-normal">{{$time}}</span></p>
                    </div>
                </div>
        </div>
        <form id="purchaseAdd_form">
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
                            <tr class="fieldGroup">
                                <td>
                                    <select id="purchaseAdd_category0" name="purchaseAdd_category[]" class="form-select form-select-sm select2-cls" onchange="getPurchaseMedicine(this.value,0)" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="purchaseAdd_name0" name="purchaseAdd_name[]" class="form-select form-select-sm select2-cls" required>
                                        <option value="" selected>Select</option>
                                    </select>
                                </td>
                                <td>
                                    <input id="purchaseAdd_batch0" name="purchaseAdd_batch[]" class="form-control form-control-sm" type="text" placeholder="Batch No" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_expiry0" name="purchaseAdd_expiry[]" class="form-control form-control-sm expiry-date" type="text" placeholder="Expiry Date" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_mrp0" name="purchaseAdd_mrp[]" class="form-control form-control-sm" type="number" placeholder="MRP" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_salesPrice0" name="purchaseAdd_salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sale Price" required>
                                </td>
                                
                                <td>
                                    <input id="purchaseAdd_qty0" name="purchaseAdd_qty[]" class="form-control form-control-sm" type="number" placeholder="Qty" oninput="getAmount(0)" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_purchaseRate0" name="purchaseAdd_purchaseRate[]" type="number" class="form-control form-control-sm" placeholder="Purchase Rate" oninput="getAmount(0)" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_tax0" name="purchaseAdd_tax[]" type="number" class="form-control form-control-sm" placeholder="Tax" oninput="getTax(0)" required>
                                </td>
                                <td>
                                    <input id="purchaseAdd_amount0" name="purchaseAdd_amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" readonly>
                                </td>
                                
                            </tr>
                            <!-- replica table start -->
                            <tr class="newRowAppend">
                                
                            </tr>
                            <!-- replica table end -->
                        </tbody>
                    </table>
                    <div>
                            <button type="button" class="mx-1 fw-semibold w-64-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRow()">
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
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_totalAmt">0</span></td>
                        </tr>
                        {{-- <tr>
                        <td class="border-0 align-middle">Discount</td>
                        <td class="border-0"><div class="d-flex align-items-center">
                            <input id="purchaseAdd_discount" class="form-control form-control-sm discount-value-field" type="text" value="0" placeholder="Discount" oninput="getDiscount(this.value)"><span class="ms-1">%</span></div>
                        </td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_discountAmt">0</td>
                        </tr> --}}
                        <tr>
                        <td class="border-0" colspan="2">Taxes</td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_taxAmt">0</span></td>
                        </tr>
                        <tr>
                        <td class="border-0" colspan="2">Net Amount</td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_netTotalAmt">0</span></td>
                        </tr>
                        <tr>
                        <td colspan="2" class="border-0">
                            <select id="purchaseAdd_paymentMode" class="form-select form-select-sm ">
                            <option value="">Payment Mode</option>
                            <option value="Card">Card</option>
                            <option value="UPI">UPI</option>
                            <option value="Cash">Cash</option>
                        </select></td>
                        <td class="border-0">
                            <input id="purchaseAdd_payAmount" type="number" class="form-control form-control-sm" placeholder="Payment Amount" oninput="checkPayAmountPurchaseAdd(document.getElementsByClassName('purchaseAdd_netTotalAmt')[0].innerHTML,this.value)">
                             <div class="purchaseAdd_payAmount_cls"></div>
                        </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class=" pharmacy-footer card-footer border-top">
            <div class="text-end">
                <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 purchaseAddSubmitBtn"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                <button class="btn btn-primary purchaseAddSpinnBtn d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Please Wait...
                </button>
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
    //    function getDatePicker(receiveID) {
    //     flatpickr(receiveID, {
    //         enableTime: true,
    //         dateFormat: "d/m/Y H:i",
    //     });
    // }
    // getDatePicker('#purchaseAdd_expiry0'); 
    const purchaseAddDatas = "{{route('purchase.purchaseAddDatas')}}";
     const getPurchaseNames = "{{route('billing.getMedicineNames')}}";
     const getCategoryDatas = "{{route('purchase.getCategoryDatas')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase-add.js')}}"></script>
@endsection