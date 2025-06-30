@extends('backend.admin.layouts.main')
@section('title')
Discharge Billing
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/billing.css')}}">
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Discharge Billing</h6>
    </div>
    <div class="pharmacy-purchase-wrapper card">
      <div class="card-header pb-4 border-bottom-0">
        <div class=" bg-neutral-100 d-flex align-items-center justify-content-between px-11">
          <div class="d-flex align-items-center">
            @php
              $randomNumber = time().rand(10,99);
              date_default_timezone_set('Asia/Kolkata');
               $dateTime = date('d/m/Y h:i A');   
            @endphp
            <p class="mt-3 fw-medium">Bill No : <span class="fw-normal billingAdd-billNo">{{$randomNumber}}</span></p>
            <p class="mt-3 fw-medium mx-5">Date : <span class="fw-normal">{{ $dateTime}}</span></p>
          </div>
          <div class="d-flex align-items-center">
              <div class="mx-1">
                <label for="billingAdd-patient" style="display: none;">Patient Name</label>
               <select id="billingAdd-patient" class="form-select form-select-sm select2-cls" oninput="validateField(this.id,'select')">
                <option value="">Select Patient</option>
                <option value="0">Cash</option>
                {{-- @foreach ($patients as $patient)
                <option value="{{$patient->id}}">{{$patient->name}} ({{$patient->patient_id}})</option>
                @endforeach --}}
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
             <div class="table-responsive scroll-sm">
                    <h6 class="text-xl fw-normal">Payment Details</h6>
                    <table class="table bordered-table text-sm">
                      <thead>
                        <tr>
                          <th scope="col" class="text-sm">Transaction ID </th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm text-end">Amount (₹)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>TRANS123456</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td class="text-end">2000.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
            <hr class="mb-3">
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-4 offset-2">
                    <table class="table table-sm">
                      <tr>
                        <td class="border-0" colspan="2">Total</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-totalAmount">0</span></td>
                      </tr>
                      <tr>
                        <td class="border-0 align-middle">Discount (₹)</td>
                        <td class="border-0"><div class="d-flex align-items-center"><input id="billingAdd-discountPer" class="form-control form-control-sm discount-value-field" type="number" placeholder="Discount" value="" oninput="getBillingAmount()"><span class="ms-1">%</span></div></td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-discountAmount">0</span></td>
                      </tr>
                      <tr>
                        <td class="border-0" colspan="2">Net Amount (₹)</td>
                        <td class="border-0 text-end fs-6">₹ <span class="billingAdd-totalNetAmount">0</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="border-0">
                          <select id="billingAdd-paymentMode" class="form-select form-select-sm select2-cls">
                            <option value="">Select Payment Mode</option>
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
                <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 billingAddSubmitBtn"> <i class="ri-checkbox-circle-line"></i>Submit</button>
                <button class="btn btn-primary billingAddSpinnBtn d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Please Wait...
                </button>
            </div>
        </div>
     </form>
    </div>
</div>
@endsection