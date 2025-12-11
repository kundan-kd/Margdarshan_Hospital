@extends('backend.admin.layouts.main')
@section('title')
    OPD Report
@endsection
@section('extra-css')
<style>
    .form-select.form-select-sm{
        width:auto !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <h6 class="fw-normal mb-0">Expiry & Near-expiry Products</h6>
      <div class="row my-3">
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
          <input type="radio" class="btn-check" name="btnradio" id="btnradio1" checked onclick="location.href='{{ route('report.expiryReport') }}'">
          <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio1">Expiry</label>
          <input type="radio" class="btn-check" name="btnradio" id="btnradio3" onclick="location.href='{{ route('report.profitMargin') }}'">
          <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio3">Profit</label>
        </div>
      </div>
    </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="expiry-report-table" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">Group</th>
                  <th scope="col" class="fw-medium">Medicine Name</th>
                  <th scope="col" class="fw-medium">Batch</th>
                  <th scope="col" class="fw-medium">Avl Qty</th>
                  <th scope="col" class="fw-medium">Expiry On</th>
                </tr>
              </thead>
              <tbody>
               
              </tbody>
            </table>
      </div>
      </div>
    </div>
  </div>
@endsection
@section('extra-js')
<script>
   const getExpiryData = "{{route('report.expiryData')}}";
</script>
    <script src="{{asset('backend/assets/js/custom/admin/report/pharmacy-report.js')}}"></script>
@endsection