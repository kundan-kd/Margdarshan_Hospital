@extends('backend.admin.layouts.main')
@section('title')
Medicin Bill Details
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/purchase.css')}}">
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class=" mb-24">
      <h6 class="fw-medium mb-0">Medicine Bill Details</h6>
   
    </div>
  
    <div class="card basic-data-table">
      <div class="card-body">
         <div class="row">
            <div class="row ">
                <div class="col-md-7 mt-3">
                  <table class="table table-borderless pharmacy-bill-detail-table">
                     <tr>
                       <th class="fw-medium">Patient ID</th>
                       <td class="text-neutral-700">{{$patient_id}}</td>
                       <th class="fw-medium">Patient Name</th>
                       <td class="text-neutral-700">{{$patientName}}</td>
                     </tr>
                     <tr>
                      <th class="fw-medium">Bill Number</th>
                      <td class="text-neutral-700">{{$billings[0]->bill_no}}</td>
                     </tr>
                  </table>
                </div>
                <div class="col-md-12 ">
                  <table class=" pharmacy-purchase-bill-table table table-hover border">
                     <thead>
                             <tr >
                              <th class="text-nowrap text-neutral-700">
                                  Medicine Category
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Mediciane Name
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Batch No
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Expiry Date
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Quantity
                              </th>
                               <th class="text-nowrap text-neutral-700">
                                  MRP (₹)
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Tax(%)
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                 Amount (₹)
                              </th>
                          </tr>
                  </thead>
                  <tbody>
                    @foreach ( $billingItems as $items)
                      <tr>
                        <td class="text-neutral-700">{{$items->categoryData->name}}</td>
                        <td class="text-neutral-700">{{$items->medicineNameData->name}}</td>
                        <td class="text-neutral-700">{{$items->batchData->batch_no}}</td>
                        <td class="text-neutral-700">{{$items->expiry}}</td>
                        <td class="text-neutral-700">{{$items->qty}}</td>
                        <td class="text-neutral-700">{{$items->sales_price}}</td>
                        <td class="text-neutral-700">{{$items->tax_per}}</td>
                        <td class="text-neutral-700">{{$items->amount}}</td>
                      </tr>
                       @endforeach
                  </tbody>
                  </table>
                </div>
                <div class="col-md-6 ">
                     <table class="table table-borderless w-50 pharmacy-bill-detail-table">
                        <tr>
                          {{-- <td class="text-neutral-700 fw-medium">Payment Mode <span class="fw-medium">: {{$billings[0]->payment_mode}}</span></td> --}}
                        </tr>
                       
                     </table>
                </div>
                <div class="col-md-3 offset-md-3 ">
                     <table class="table table-borderless pharmacy-bill-detail-table" >
                        <tr>
                          <td class="text-neutral-700 fw-medium">Total</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->total_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Discount</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->discount_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Tax Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->taxes}}</td>
                        </tr>
                         <tr>
                          <td class="text-neutral-700 fw-medium">Net Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->net_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Paid Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->paid_amount ?? 0}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Due Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$billings[0]->due_amount ?? 0}}</td>
                        </tr>
                     </table>
                </div>
             </div>
         </div>
      </div>
    </div>
  </div>
@endsection
@section('extra-js')
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase.js')}}"></script>
@endsection
