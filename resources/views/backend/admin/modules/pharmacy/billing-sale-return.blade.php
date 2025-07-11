@extends('backend.admin.layouts.main')
@section('title')
View Sale Return
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/billing.css')}}">
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">View Sale Return</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class=" bg-neutral-100 d-flex align-items-center justify-content-between px-11">
             <input type="hidden" id="billingEdit_billing_id" value="{{$billings[0]->id}}">
          <div class="d-flex align-items-center">
            <p class="mt-3 fw-medium">Bill No : <span class="fw-normal billingEdit-billNo">{{$billings[0]->bill_no}}</span></p>
          </div>
          <div class="d-flex align-items-center">
              <div class="mx-1">
                <label for="billingEdit-patient" style="display: none;">Patient Name</label>
               <select id="billingEdit-patient" class="form-select form-select-sm select2-cls" oninput="validateField(this.id,'select')" disabled>
                <option value="0"{{$billings[0]->patient_id == 0 ? 'selected':''}}>Cash</option>
                @foreach ($patients as $patient)
                <option value="{{$patient->id}}"{{$patient->id == $billings[0]->patient_id ? 'selected':''}}>{{$patient->name}} ({{$patient->patient_id}})</option>
                @endforeach
              </select>
              <div class="billingAdd-patient_errorCls d-none"></div>
              </div>
              {{-- <button class="mx-1 fw-semibold w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#billingEdit-patientModal" onclick="resetAddPatient()">
                  <i class="ri-add-line"></i>
              </button> --}}
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
                                    Return Quantity
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Sales Price (₹)
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Amount (₹)
                                </th>
                                <th class="text-nowrap text-neutral-700">
                                    Tax (%)
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <div class="billingEditMedicineData"></div>
                          @foreach ($billingItems as $item)
                            <tr class="fieldGroup">
                            <td>
                                <input type="hidden" id="billingEdit_id{{$item->id}}" name="billingEdit_id[]" value="{{$item->id}}">
                                    <select id="billingEdit-category{{$item->id}}" name="billingEdit-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicineEdit(this.value,{{$item->id}})" disabled>
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}"{{$item->category_id == $category->id ? 'selected':''}}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="billingEdit-name{{$item->id}}" name="billingEdit-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetailsEdit(this.value,{{$item->id}})" disabled>
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id="billingEdit-batch{{$item->id}}" name="billingEdit-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiryEdit(this.value,{{$item->id}})"disabled>
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <div class=" position-relative">
                                        <input id="billingEdit-expiry{{$item->id}}" name="billingEdit-expiry[]" class="form-control radius-8 bg-base"  type="text" value="" value="{{$item->expiry}}" disabled>
                                    </div>
                                </td>
                                <td>
                                    <input id="billingEdit-qty{{$item->id}}" name="billingEdit-qty[]" class="form-control form-control-sm" type="number" placeholder="Quantity" value="{{$item->return_qty}}" disabled>
                                </td>
                                <td>
                                    <input id="billingEdit-salesPrice{{$item->id}}" name="billingEdit-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" value="{{$item->sales_price}}" disabled>
                                </td>
                                 <td>
                                    <input id="billingEdit-amount{{$item->id}}" name="billingEdit-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" value="{{$item->amount}}" disabled>
                                </td>
                                <td>
                                    <input id="billingEdit-tax{{$item->id}}" name="billingEdit-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" value="{{$item->tax_per}}" disabled>
                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="mb-3">
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-4 offset-2">
                    <table class="table table-sm">
                      <tr>
                        <td class="border-0" colspan="2">Total (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalAmount">{{$billings[0]->total_amount}}</span></td>
                      </tr>
                      <tr>
                        <td class="border-0 align-middle">Discount (₹)</td>
                        <td class="border-0">
                        </td>
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
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalPaidAmount">{{$billings[0]->paid_amount ?? 0}}</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Sale Return Amount (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingEdit-totalReturnAmount">{{$billings[0]->return_amount ?? 0}}</span></td>
                      </tr> 
                </table>
                </div>
            </div>
        </div>
     </form>
    </div>
</div>


@endsection
@section('extra-js')
<script>
     const billingEditAutoLoadData = "{{route('billing-edit.billingEditAutoLoadData')}}";
     const getBillingNamesSelectEdit = "{{route('billing-edit.getBillingNamesSelectEdit')}}";
     const getBatchExpiryDateEdit = "{{route('billing-add.getBatchExpiryDate')}}";
     const getBillingCategoryDataEdit = "{{route('purchase.getCategoryDatas')}}"; //also used somewhere
     const getBillingMedicineNameEdit = "{{route('billing.getMedicineNames')}}"; //also used in billing-add.js
     const getBatchNumberEdit =  "{{route('billing-add.getBatchNumbers')}}"; //also used in billing-add.js
     const billingEditDatas =  "{{route('billing-Edit.billingEditDatas')}}"; //also used in billing-add.js    
     const getBillingData = "{{route('billing-Edit.getBillingData')}}";

// Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-yy ",
        });
    }
    getDatePicker('#billingAdd-patientDOB'); 

   
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/billing-edit.js')}}"></script>
<script>
     window.onload = function() {
        document.querySelectorAll('[id^="billingEdit-category"]').forEach(function(selectElement) {
            var categoryvalue = selectElement.value;
            var categoryId = selectElement.id.replace("billingEdit-category", "");
            if (categoryvalue) {
                getBillingMedicineSelectedEdit(categoryvalue,categoryId);
            }
        });
    };
//     auto_load_data(document.getElementById('billingEdit_billing_id').value);
//     $(document).ready(function() {
    
// });
</script>
@endsection