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
    <h6 class="fw-normal mb-0">Medicine Details</h6>
    </div>
    
    <div class="card basic-data-table">
      <div class="card-body">
         <div class="row">
            <div class="col-md-9">
                <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr>
                    <th class="fw-medium">Medicine Name</th>
                    <td>{{$medicines->name}}</td>
                    <th class="fw-medium">Medicine Category</th>
                    <td>{{$medicines->categoryData->name}}</td>
                </tr>
                <tr>
                   <th class="fw-medium">Medicine Company</th>
                    <td>{{$medicines->companyData->name}}</td>
                    <th class="fw-medium">Medicine Composition</th>
                    <td>{{$medicines->composition}}</td>
                </tr>
                <tr>     
                    <th class="fw-medium">Medicine Group</th>
                    <td>{{$medicines->groupData->name}}</td>
                    <th class="fw-medium">Unit</th>
                    <td>{{$medicines->unitData->name}}</td>
                </tr>
                <tr>           
                   <th class="fw-medium">Min Level</th>
                    <td>1</td>
                    <th class="fw-medium">Re-Order Level</th>
                    <td>{{$medicines->re_ordering_level}}</td>
                </tr>
                <tr>    
                     <th class="fw-medium">Tax</th>
                    <td>{{$medicines->taxes}}%</td>    
                    <th class="fw-medium">Box/Packing</th>
                    <td>{{$medicines->box_packing}}</td>  
                </tr>
                  <tr>    
                     <th class="fw-medium">Rack Number</th>
                    <td>{{$medicines->rack}}</td>    
                    <th class="fw-medium">Note</th>
                    <td>{{$medicines->narration}}</td>  
                </tr>
            </tbody>
            </table>
            </div>
         </div>
         <hr class="mb-3">
         <div class="row">
            <div class="col-md-12">
                <table class="table bordered-table mb-0" id="medician-details" data-page-length='10'>
          <thead>
            <tr >
              <th scope="col" class="fw-medium">Inward Date</th>
              <th scope="col" class="fw-medium">Batch No</th>
              <th scope="col" class="fw-medium">Purchase No</th>
              <th scope="col" class="fw-medium">Expiry Date</th>
              <th scope="col" class="fw-medium">Purchase Rate (₹)</th>
              <th scope="col" class="fw-medium">Amount (₹)</th>
              <th scope="col" class="fw-medium">Quantity</th>
              <th scope="col" class="fw-medium">MRP (₹)</th>
              <th scope="col" class="fw-medium">Sale Price (₹)</th>
              {{-- <th scope="col" class="fw-medium">Action</th> --}}
            </tr>
          </thead>
          <tbody>
            @foreach ($purchaseItems as $items)
            <tr>
                <td>{{$items->created_at}}</td>
                <td>{{$items->batch_no}}</td>
                <td>MGPR0{{$items->purchase_id}}</td>
                <td>{{$items->expiry}}</td>
                <td>{{$items->purchase_rate}}</td>
                <td>{{$items->amount}}</td>
                <td>{{$items->qty}}</td>
                <td>{{$items->mrp}}</td>
                <td>{{$items->sales_price}}</td>
                {{-- <td>
                 <button class="mx-1 w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </td> --}}
            </tr>
              @endforeach
          </tbody>
        </table>
            </div>
         </div>
      </div>
    </div>
  </div>
@endsection
@section('extra-js')
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/medicine.js')}}"></script>
@endsection
