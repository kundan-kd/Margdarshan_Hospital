@extends('backend.admin.layouts.main')
@section('title')
Billing
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Billing</h6>
      <div class="btns">
          <a href="{{route('billing.billingAdd')}}" class="btn btn-primary-600  btn-sm fw-normal" ><i class="ri-add-line"></i>Create New</a>
          {{-- <button class="btn btn-warning-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Export</button> --}}
      </div>
    </div>
    <div class="card basic-data-table">
      <div class="card-body">
        <table class="table bordered-table mb-0" id="billing-list-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col" class="fw-medium">Patient Name</th>
              <th scope="col" class="fw-medium">Billing Date & Time</th>
              <th scope="col" class="fw-medium">Bill No.</th>
              <th scope="col" class="fw-medium">Discount</th>
              <th scope="col" class="fw-medium">Total</th>
              <th scope="col" class="fw-medium">Net Amount</th>
              <th scope="col" class="fw-medium">Paid Amount</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
            {{-- data appended here using datatable from billing.js --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@section('extra-js')
<script>
  const billingView = "{{route('billing.billingView')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/billing.js')}}"></script>
@endsection