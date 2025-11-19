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
    #addNewMedicine{
        position: absolute;
        top: 10px;
        right: 8px;
        background-color: #0d6efd;
        color: white;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 12px;
        cursor: pointer;
        line-height: 1;
        z-index: 100;
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
                                <th class="text-nowrap text-neutral-700" style="width: 230px;">
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
                                    <div style="position: relative; width: 230px;">
                                        <input id="purchaseAdd_name0" class="form-control form-control-sm" type="text" placeholder="Medicine Name"
                                        oninput="getMedicineNames(document.getElementById('purchaseAdd_category0').value,this.value)" autocomplete="off"
                                        style="width: 100%; padding-right: 60px;"/>
                                        <span class="add-new-medicine d-none" id="addNewMedicine">Add New</span>
                                    </div>
                                    <input type="hidden" id="purchaseAdd_nameId0" name="purchaseAdd_name[]">

                                    <div class="d-block position-relative" style="z-index: 99;">
                                        <ul class="search-item list-group position-absolute rounded-0 medicine-name-list" style="width: -webkit-fill-available;">
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
                                    <button type="button" class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore purchaseAddBtn" onclick="addNewRow()">
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
    </form>
    </div>
</div>
<!-- medicine add model start -->
   <!-- Modal to medician-list-add start -->
<div class="modal fade" id="newMedicineAdd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newMedicineAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0">
    <form id="newMedicineAdd_form">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-lg text-white" id="newMedicineAddLabel">Add Medicine</h6>
        <button type="button" class="btn-close btn-custom text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="row">
          <input type="hidden" id="newMed_id">            
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_category">Category</label>
                <select id="newMed_category" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                    <option value="">Select</option>
                      @foreach ($categories as $category)
                          <option value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                  </select>
                 <div class="newMed_category_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_name">Medicine Name</label>
                <input id="newMed_name" type="text" class="form-control form-control-sm" placeholder=" Medicine Name" autocomplete="off" oninput="validateField(this.id,'input')">
                  <div class="newMed_name_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_company">Company</label>
                <select id="newMed_company" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                    <option value="">Select</option>
                    @foreach ($companies as $company)
                          <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                  </select>
                 <div class="newMed_company_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_unit">Unit</label>
                <select id="newMed_unit" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                   <option value="">Select</option>
                    @foreach ($units as $unit)
                          <option value="{{$unit->id}}">{{$unit->unit}}</option>
                    @endforeach
                  </select>
                 <div class="newMed_unit_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_reOrderingLevel">Re-Ordering Level</label>
              <input id="newMed_reOrderingLevel" type="number" class="form-control form-control-sm" placeholder="Re-Ordering Level" oninput="validateField(this.id,'select')">
             <div class="newMed_reOrderingLevel_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_rack">Rack</label>
                <input id="newMed_rack" type="text" class="form-control form-control-sm" placeholder="Rack">
                <div class="newMed_rack_errorCls d-none"></div>
            </div>
          
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_composition">Composition</label>
               <select id="newMed_composition" name="newMed_composition[]" class="form-select form-select-sm select2-cls" multiple="multiple" style="width: 100%" oninput="validateField(this.id,'select')">
                   <option value="">Select</option>
                    @foreach ($compositions as $composition)
                          <option value="{{$composition->id}}">{{$composition->name}}</option>
                    @endforeach
                  </select>
               <div class="newMed_composition_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_hsn">HSN Number</label>
                <input id="newMed_hsn" type="text" class="form-control form-control-sm" placeholder="HSN Number" oninput="validateField(this.id,'input')">
                <div class="newMed_hsn_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_taxes">Taxes</label>
                <input id="newMed_taxes" type="number" class="form-control form-control-sm" placeholder="Taxes" oninput="validateField(this.id,'select')">
                <div class="newMed_taxes_errorCls d-none"></div>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label fw-normal" for="newMed_boxPacking">Box / Packing</label>
                <input id="newMed_boxPacking" type="text" class="form-control form-control-sm" placeholder="Box / Packing" oninput="validateField(this.id,'select')">
                 <div class="newMed_boxPacking_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-normal">Narration</label>
                <textarea id="newMed_narration" name="#0" class="form-control " rows="2" placeholder="Narration"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal" onclick="resetNewMedicineAdd()">Cancel</button>
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal">Save</button>
        {{-- <button type="button" class="btn btn-primary-600  btn-sm fw-normal medicineUpdateBtn d-none" onclick="medicineUpdate(document.getElementById('createMed_id').value)"><i class="ri-checkbox-circle-line"></i> Update</button> --}}
         </form>
      </div>
    </div>
  </div>
  {{-- medicine add model end --}}
@endsection
@section('extra-js')
<script>
        function getDatePicker(receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y",
            
        });
    }
    getDatePicker('#purchaseAdd_Date'); 
    // Initialize Select2 for the main medicine dropdown with custom "No results" message

// Handle click on the "Add New" badge inside the noResults message
  $(document).on('click', '#addNewMedicine', function (e) {
    e.preventDefault();

    let form = $('#newMedicineAdd_form');
    let purchaseAdd_category0 = $('#purchaseAdd_category0');
    let purchaseAdd_name0 = $('#purchaseAdd_name0');

    if (form.length) form[0].reset();

    $('#newMed_category').val(purchaseAdd_category0.val()).trigger('change');
    $('#newMed_name').val(purchaseAdd_name0.val()).trigger('change');

    $('#newMedicineAdd').modal('show');
});

// Reinitialize Select2 inside the modal only targetting inside model dropdown
$('#newMedicineAdd').on('show.bs.modal', function () {
  $('#newMedicineAdd .select2-cls').select2({
    dropdownParent: $('#newMedicineAdd')
  });
});
    const purchaseAddDatas = "{{route('purchase.purchaseAddDatas')}}";
     const getPurchaseNames = "{{route('billing.getMedicineNames')}}";
     const getMedicineData = "{{route('common.getMedicineData')}}";
     const newMedicineAdd = "{{route('medicine.medicineAdd')}}";  // this round is also used in medicine.blade.php
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase-add.js')}}"></script>
@endsection