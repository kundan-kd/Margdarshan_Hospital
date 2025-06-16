@extends('backend.admin.layouts.main')
@section('title')
purchase
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Purchase</h6>
      <div class="btns">
          <a href="{{route('purchase.purchaseAdd')}}" class="btn btn-primary-600  btn-sm fw-normal" ><i class="ri-add-line"></i>Add Purchase</a>
          {{-- <button class="btn btn-warning-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Export</button> --}}
      </div>
    </div>
    <!-- <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-medium mb-0">Medicines</h6>
  <div class="btns">
    <a class="btn btn-primary-600  btn-sm fw-normal mx-2" href="./ipd-in-patientDetail.html"><i class="ri-add-box-line"></i> Create New</a>
    <a class="btn btn-success-600  btn-sm fw-normal mx-2" href="./ipd-in-patientDetail.html"><i class="ri-briefcase-5-line"></i> Medicines</a>
    <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
  </div>
</div> -->

    
    <div class="card basic-data-table">
      <!-- <div class="card-header text-end">
        <div class="btns">
            <a class="btn btn-primary-600  btn-sm fw-normal mx-2" href="./ipd-in-patientDetail.html"><i class="ri-add-box-line"></i> Create New</a>
            <a class="btn btn-warning-600  btn-sm fw-normal "><i class="ri-file-pdf-2-line"></i> Export</a>
          </div>
      </div> -->
      <div class="card-body">
        <table class="table bordered-table mb-0" id="purchase-list-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col" class="fw-medium">Billing Date & Time</th>
              <th scope="col" class="fw-medium">Bill No.</th>
              <th scope="col" class="fw-medium">Net Amount</th>
              <th scope="col" class="fw-medium">Discount</th>
              <th scope="col" class="fw-medium">Total</th>
              <th scope="col" class="fw-medium">Paid Amount</th>
              <th scope="col" class="fw-medium">Due</th>
              <th scope="col" class="fw-medium">Naration</th>
              <th scope="col" class="fw-medium">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Move medician-create-new start -->
<div class="modal fade" id="medician-create-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="medician-create-newLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-normal text-lg" id="medician-create-newLabel"> Create new item</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         Create new item
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-normal">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Move to medician-create-new end -->
 <!-- Move to medician-list-add start -->
<div class="modal fade" id="medician-list-add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="medician-list-addLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-normal text-lg" id="medician-list-addLabel"> Medician add</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Medician add
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-normal">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Move to medician-list-addend -->
@endsection
@section('extra-js')
<script>
  const purchaseView = "{{route('purchase.purchaseView')}}";
  const deletePurchasedetails = "{{route('purchase.deletePurchasedetails')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/pharmacy/purchase.js')}}"></script>
@endsection