@extends('backend.admin.layouts.main')
@section('title')
purchase-add
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/purchase.css')}}">
<style>
    .medicine-name-list li{
        cursor: pointer;
    }
</style>
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
                <div class="row bg-neutral-100 align-items-center my-2 mx-2 gy-2 pb-11">
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
                    <div class="col-md-2">
                        <label for="purchaseAdd_Date" style="display:none;">Purchase Date</label>
                        <input type="date" id="purchaseAdd_Date" class="form-control form-control-sm" placeholder="Select Purchase Date" oninput="validateField(this.id,'select')">
                        <div class="purchaseAdd_Date_errorCls d-none"></div>
                    </div>
                </div>
        </div>
        <form id="purchaseAdd_form">
        <div class="card-body pharmacy-purchase-content pt-1">
            <div class="row mb-3">
                <div class="col-md-12">
                    <table class="pharmacy-purchase-bill-table table table-hover mb-11">
                        <thead >
                            <tr class="border-bottom">
                                <th class="text-nowrap text-neutral-700">
                                    Category
                                </th>
                                <th class="text-nowrap text-neutral-700" style="width: 250px;">
                                    Name <div class="spinner-border spinner-border-sm name-loader d-none" role="status"></div>
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
                                    Total Amount
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Tax (%)
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Purchase Rate
                                </th>
                                
                                <th class="text-nowrap text-neutral-700">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fieldGroup">
                                <td>
                                    <select id="purchaseAdd_category0" name="purchaseAdd_category[]" class="form-select form-select-sm select2-cls" onchange="getPurchaseMedicine(this.value,0)" >
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{-- <select id="purchaseAdd_name0" name="purchaseAdd_name[]" class="form-select form-select-sm select2-cls"  onchange="getTaxValue(this.value,0)">
                                        <option value="" selected>Select</option>
                                    </select> --}}
                                      <input id="purchaseAdd_name0" class="form-control form-control-sm" type="text" placeholder="Medicine Name" oninput="getMedicineNames(document.getElementById('purchaseAdd_category0').value,this.value)" style="width: 250px;">
                                      <input type="hidden" id="purchaseAdd_nameId0" name="purchaseAdd_name[]">

                                      <div class="d-block position-relative" style="z-index :99;">
                                            <ul class="search-item list-group position-absolute rounded-0 medicine-name-list">
                                            <!-- dropdown list of patients appended here using JS -->
                                            </ul>
                                    </div>
                                </td>
                                <td>
                                    <input id="purchaseAdd_batch0" name="purchaseAdd_batch[]" class="form-control form-control-sm" type="text" placeholder="Batch No" >
                                </td>
                                <td>
                                    <input id="purchaseAdd_expiry0" name="purchaseAdd_expiry[]" class="form-control form-control-sm expiry-date" type="text" placeholder="Expiry Date" >
                                </td>
                                <td>
                                    <input id="purchaseAdd_mrp0" name="purchaseAdd_mrp[]" class="form-control form-control-sm" type="number" placeholder="MRP"  step="0.01">
                                </td>
                                <td>
                                    <input id="purchaseAdd_salesPrice0" name="purchaseAdd_salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sale Price" step="0.01">
                                </td>
                                
                                <td>
                                    <input id="purchaseAdd_qty0" name="purchaseAdd_qty[]" class="form-control form-control-sm" type="number" placeholder="Qty" min="0" oninput="getAmount(0)" >
                                </td>
                                 <td>
                                    <input id="purchaseAdd_amount0" name="purchaseAdd_amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" min="0" oninput="getAmount(0)">
                                </td>
                                <td>
                                    <input id="purchaseAdd_tax0" name="purchaseAdd_tax[]" type="number" class="form-control form-control-sm" placeholder="Tax" oninput="getTax(0)"  readonly>
                                </td>
                                <td style="display: none;">
                                    <input id="purchaseAdd_taxAmount0" name="purchaseAdd_taxAmount[]" type="number" class="form-control form-control-sm" placeholder="Tax"  readonly>
                                </td>
                                 <td>
                                    <input id="purchaseAdd_purchaseRate0" name="purchaseAdd_purchaseRate[]" type="number" class="form-control form-control-sm" placeholder="Rate"  step="0.01" readonly>
                                </td>
                                <td>
                                    <button type="button" class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRow()">
                                <i class="ri-add-line"></i>
                            </button>
                                </td>
                                
                                
                            </tr>
                            <!-- replica table start -->
                            <tr class="newRowAppend">
                                
                            </tr>
                            <!-- replica table end -->
                        </tbody>
                    </table>
                    {{-- <div>
                            <button type="button" class="mx-1 fw-semibold w-64-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRow()">
                                <i class="ri-add-line">Add</i>
                            </button>
                    </div> --}}
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
                        <tr>
                        <td class="border-0 align-middle">Discount</td>
                        <td class="border-0"><div class="d-flex align-items-center">
                            <input id="purchaseAdd_discount" class="form-control form-control-sm discount-value-field" type="text" placeholder="Discount" oninput="getDiscount(this.value)"><span class="ms-1">%</span></div>
                        </td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_discountAmt">0</td>
                        </tr>
                        <tr>
                        <td class="border-0" colspan="2">Taxes</td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_taxAmt">0</span></td>
                        </tr>
                        <tr>
                        <td class="border-0" colspan="2">Net Amount</td>
                        <td class="border-0 text-end fs-6">₹ <span class="purchaseAdd_netTotalAmt">0</span></td>
                        </tr>
                        <tr>
                        <td colspan="2" class="border-0 pmode">
                            <select id="purchaseAdd_paymentMode" class="form-select form-select-sm ">
                                <option value="">Payment Mode</option>
                                <option selected value="Cash">Cash</option>
                                <option value="UPI">UPI</option>
                                <option value="Card">Card</option>
                                <option value="Internet Banking">Internet Banking</option>
                                <option value="Others">Others</option>
                            </select>
                         <td class="border-0 pmodeTxn d-none">
                          <input id="purchaseAdd-txn" type="text" class="form-control form-control-sm" placeholder="Transaction No.">
                        </td>
                        </td>
                        <td class="border-0">
                            <input id="purchaseAdd_payAmount" type="number" step="0.01" class="form-control form-control-sm" placeholder="Payment Amount" oninput="checkPayAmountPurchaseAdd(document.getElementsByClassName('purchaseAdd_netTotalAmt')[0].innerHTML,this.value)">
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
                <button class="btn btn-primary-600  btn-sm fw-normal purchaseAddSpinnBtn d-none" type="button">
                    Please Wait...
                </button>
            </div>
        </div>
    <form>
    </div>
</div>
@endsection
@section('extra-js')
<script>
        function getDatePicker(receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y",
            
        });
    }
    getDatePicker('#purchaseAdd_Date'); 
    const purchaseAddDatas = "{{route('purchase.purchaseAddDatas')}}";
     const getPurchaseNames = "{{route('billing.getMedicineNames')}}";
     const getMedicineData = "{{route('common.getMedicineData')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase-add.js')}}"></script>
@endsection