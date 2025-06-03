@extends('backend.admin.layouts.main')
@section('title')
Billing-edit
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/billing.css')}}">
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Edit Billing</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class=" bg-neutral-100 d-flex align-items-center justify-content-between px-11">
             <input type="hidden" id="billingEdit_billing_id" value="{{$billings[0]->id}}">
          <div class="d-flex align-items-center">
            <p class="mt-3 fw-medium">Bill No : <span class="fw-normal billingEdit-billNo">{{$billings[0]->bill_no}}</span></p>
            <p class="mt-3 fw-medium mx-5">Date : <span class="fw-normal">{{$billings[0]->created_at}}</span></p>
          </div>
          <div class="d-flex align-items-center">
              <div class="mx-1">
                <label for="billingEdit-patient" style="display: none;">Patient Name</label>
               <select id="billingEdit-patient" class="form-select form-select-sm select2-cls" oninput="validateField(this.id,'select')">
                <option value="">Select Patient</option>
                @foreach ($patients as $patient)
                <option value="{{$patient->id}}"{{$patient->id == $billings[0]->patient_id ? 'selected':''}}>{{$patient->name}} ({{$patient->patient_id}})</option>
                @endforeach
              </select>
              <div class="billingAdd-patient_errorCls d-none"></div>
              </div>
              <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#billingEdit-patientModal" onclick="resetAddPatient()">
                  <i class="ri-add-line"></i>
              </button>
          </div>
        </div>
      </div>
      <div class="expity-select-status" style="display:none;"></div>
      <form action="" id="billingEdit-Form">
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
                          @foreach ($billingItems as $item)
                            <tr class="fieldGroup">
                            <td>
                                <input type="hidden" id="billingEdit_id{{$item->id}}" name="billingEdit_id[]" value="{{$item->id}}">
                                    <select id="billingEdit-category{{$item->id}}" name="billingEdit-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicineEdit(this.value,{{$item->id}})" required>
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}"{{$item->category_id == $category->id ? 'selected':''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="billingEdit-name{{$item->id}}" name="billingEdit-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetailsEdit(this.value,{{$item->id}})"  required>
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id="billingEdit-batch{{$item->id}}" name="billingEdit-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiryEdit(this.value,{{$item->id}})" required>
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <div class=" position-relative">
                                        <input id="billingEdit-expiry{{$item->id}}" name="billingEdit-expiry[]" class="form-control radius-8 bg-base"  type="text" value="" value="{{$item->expiry}}" readonly>
                                    </div>
                                </td>
                                <td>
                                    <input id="billingEdit-qty{{$item->id}}" name="billingEdit-qty[]" class="form-control form-control-sm" type="number" placeholder="Quantity" {{$item->qty}} oninput="getBillingAmountEdit({{$item->id}})" required>
                                </td>
                                <td>
                                    <input id="billingEdit-avlQty{{$item->id}}" name="billingEdit-avlQty[]" type="number" class="form-control form-control-sm" value="" placeholder="Avilable Qty" readonly>
                                </td>
                                <td>
                                    <input id="billingEdit-salesPrice{{$item->id}}" name="billingEdit-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" {{$item->sales_price}} readonly>
                                </td>
                                <td>
                                    <input id="billingEdit-tax{{$item->id}}" name="billingEdit-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" {{$item->tax_per}} readonly>
                                </td>

                                <td style="display: none;">
                                    <input id="billingEdit-taxAmount{{$item->id}}" name="billingEdit-taxAmount[]" class="form-control form-control-sm" type="number" value="">
                                </td>
                                <td>
                                    <input id="billingEdit-amount{{$item->id}}" name="billingEdit-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" {{$item->amount}} readonly>
                                </td>
                            </tr>
                          @endforeach
                            <!-- replica table end -->
                            <tr class="newRowAppendBillingEdit">
                              {{-- new billing row appended here using js --}}
                            </tr>
                            <!-- replica table end -->
                        </tbody>
                    </table>
                    <button type="button" class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center addMore" onclick="addNewRowBillingEdit()">
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
                          <select id="billingEdit-resDoctor" class="form-select form-select-sm select2-cls  w-100">
                              <option value="" selected>Select</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{$doctor->id}}"{{$doctor->id == $doctors[0]->id ? 'selected':''}}>{{$doctor->firstname}} {{$doctor->lastname}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label fw-medium">Out Doctor Name</label>
                          <input id="billingEdit-outDoctor" type="text" class="form-control form-control-sm" value="{{$billings[0]->out_doctor_name ?? ''}} ">
                        </div>
                    </div>
                    <label class="form-label fw-medium">Note</label>
                    <textarea id="billingEdit-note" class="form-control " rows="4" cols="50" placeholder="Note">{{$billings[0]->naration ?? ''}}</textarea>
                </div>
                <div class="col-md-4 offset-2">
                    <table class="table table-sm">
                      <tr>
                        <td class="border-0" colspan="2">Total</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalAmount">{{$billings[0]->total_amount}}</span></td>
                      </tr>
                      <tr>
                        <td class="border-0 align-middle">Discount (₹)</td>
                        <td class="border-0"><div class="d-flex align-items-center"><input id="billingEdit-discountPer" class="form-control form-control-sm discount-value-field" type="text" placeholder="Discount" value="{{$billings[0]->discount_per}}" oninput="getBillingAmountEdit()"><span class="ms-1">%</span></div></td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-discountAmount">{{$billings[0]->discount_amount}}</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Taxes (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalTax">{{$billings[0]->taxes}}</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Net Amount (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalNetAmount">{{$billings[0]->net_amount}}</span></td>
                      </tr>
                        <tr>
                        <td class="border-0" colspan="2">Paid Amount (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalPaidAmount">{{$billings[0]->due_amount}}</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="border-0">
                          <select id="billingEdit-paymentMode" class="form-select form-select-sm select2-cls">
                            <option value="">Select Payment Mode</option>
                          @foreach ($paymentmodes as $paymentmode)
                              <option value="{{$paymentmode->id}}">{{$paymentmode->name}}</option>
                          @endforeach
                        </select></td>
                        <td class="border-0">
                          <input id="billingEdit-payAmount" type="number" class="form-control form-control-sm" placeholder="Pay Amount" oninput="checkBillingPayAmount(this.value)">
                        </td>
                    </tr>
                </table>
                <div class="billingEdit-payAmount-error d-none"></div>
                </div>
            </div>
        </div>
        <div class=" pharmacy-footer card-footer border-top">
          <div class="text-end">
                <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Update</button>
            </div>
        </div>
     </form>
    </div>
</div>


@endsection
@section('extra-js')
<script>
     const getBillingNamesSelectEdit = "{{route('billing-edit.getBillingNamesSelectEdit')}}";
     const getBatchExpiryDateEdit = "{{route('billing-add.getBatchExpiryDate')}}";
     const getBillingCategoryDataEdit = "{{route('purchase.getCategoryDatas')}}"; //also used somewhere
     const getBillingMedicineNameEdit = "{{route('billing.getMedicineNames')}}"; //also used in billing-add.js
     const getBatchNumberEdit =  "{{route('billing-add.getBatchNumbers')}}"; //also used in billing-add.js
     const billingEditDatas =  "{{route('billing-Edit.billingEditDatas')}}"; //also used in billing-add.js

// Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-yy ",
        });
    }
    getDatePicker('#billingAdd-patientDOB'); 

    window.onload = function() {
        document.querySelectorAll('[id^="billingEdit-category"]').forEach(function(selectElement) {
            var categoryvalue = selectElement.value;
            var categoryId = selectElement.id.replace("billingEdit-category", "");
            if (categoryvalue) {
                getBillingMedicineSelectedEdit(categoryvalue,categoryId);
            }
        });
    };
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/billing-edit.js')}}"></script>
@endsection