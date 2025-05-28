@extends('backend.admin.layouts.main')
@section('title')
purchase-edit
@endsection
@section('extra-css')
<link rel="stylesheet" href="{{asset('backend/assets/css/custom/admin/pharmacy/purchase.css')}}">
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class=" mb-24">
      <h6 class="fw-medium mb-0">Medicine Purchase Details</h6>
   
    </div>
  
    <div class="card basic-data-table">
      <div class="card-body">
         <div class="row">
            <div class="row ">
                <div class="col-md-7 mt-3">
                  <table class="table table-borderless pharmacy-bill-detail-table">
                     <tr>
                       <th class="fw-medium">Supplier Name</th>
                       <td class="text-neutral-700">{{$purchases[0]->vendorData->name}}</td>
                       <th class="fw-medium">Supplier Contact</th>
                       <td class="text-neutral-700">{{$purchases[0]->vendorData->phone}}</td>
                     </tr>
                     <tr>
                      <th class="fw-medium">Supplier GST Number</th>
                      <td class="text-neutral-700">{{$purchases[0]->vendorData->gst_number}}</td>
                       <th class="fw-medium">Address</th>
                       <td class="text-neutral-700">{{$purchases[0]->vendorData->address}}</td>
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
                                  MRP (₹)
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Sale Price (₹)
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                  Quantity
                              </th>
                              <th class="text-nowrap text-neutral-700 ">
                                   Purchase Price (₹)
                              </th>
                              <th class="text-nowrap text-neutral-700 ">
                                  Tax (%)
                              </th>
                              <th class="text-nowrap text-neutral-700">
                                 Amount (₹)
                              </th>
                          </tr>
                  </thead>
                  <tbody>
                    @foreach ( $purchaseItems as $items)
                      <tr>
                        <td class="text-neutral-700">{{$items->categoryData->name}}</td>
                        <td class="text-neutral-700">{{$items->medicineNameData->name}}</td>
                        <td class="text-neutral-700">{{$items->batch_no}}</td>
                        <td class="text-neutral-700">{{$items->expiry}}</td>
                        <td class="text-neutral-700">{{$items->mrp}}</td>
                        <td class="text-neutral-700">{{$items->sales_price}}</td>
                        <td class="text-neutral-700">{{$items->qty}}</td>
                        <td class="text-neutral-700">{{$items->purchase_rate}}</td>
                        <td class="text-neutral-700">{{$items->tax}}</td>
                        <td class="text-neutral-700">{{$items->amount}}</td>
                      </tr>
                       @endforeach
                  </tbody>
                  </table>
                </div>
                <div class="col-md-6 ">
                     <table class="table table-borderless w-50 pharmacy-bill-detail-table">
                        <tr>
                          <td class="text-neutral-700 fw-medium">Payment Mode <span class="fw-medium">: {{$purchases[0]->payment_mode}}</span></td>
                        </tr>
                       
                     </table>
                </div>
                <div class="col-md-3 offset-md-3 ">
                     <table class="table table-borderless pharmacy-bill-detail-table" >
                        <tr>
                          <td class="text-neutral-700 fw-medium">Total</td>
                          <td class="text-neutral-700 text-end">₹ {{$purchases[0]->total_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Discount</td>
                          <td class="text-neutral-700 text-end">{{$purchases[0]->total_discount_per}}%</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Tax Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$purchases[0]->total_tax}}</td>
                        </tr>
                         <tr>
                          <td class="text-neutral-700 fw-medium">Net Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$purchases[0]->net_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Paid Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$purchases[0]->paid_amount}}</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Refund Amount</td>
                          <td class="text-neutral-700 text-end">₹ 0</td>
                        </tr>
                        <tr>
                          <td class="text-neutral-700 fw-medium">Due Amount</td>
                          <td class="text-neutral-700 text-end">₹ {{$purchases[0]->due}}</td>
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
