@extends('backend.admin.layouts.main')
@section('title')
    ICU Report
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
       <h6 class="fw-normal mb-0">ICU - Patient Report</h6>
     </div>
        <div class="row my-3">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
            <label for="start_date">FROM </label>
            <input type="date" class="form-control ms-2 me-3" id="start_date" value="">

            <label for="end_date" class="me-2">TO </label>
            <input type="date" class="form-control ms-2 me-3" id="end_date" value="">

            <button type="button" class="btn btn-primary" onclick="opdReportFilter()">Search</button>
            </div>
        </div>
        </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="icu-report-table" data-page-length='10'>
              <thead>
                <tr> 
                  <th scope="col" class="fw-medium">IP No.</th>
                  <th scope="col" class="fw-medium">Patient Name</th>
                  <th scope="col" class="fw-medium">DOA</th>
                  <th scope="col" class="fw-medium">Vital Name</th>
                  <th scope="col" class="fw-medium">Range</th>
                  <th scope="col" class="fw-medium">Vital Date & Time</th>
                  <th scope="col" class="fw-medium">Added At</th>
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
   const getIcuReport = "{{route('report.viewIcuReport')}}";
</script>
    <script src="{{asset('backend/assets/js/custom/admin/report/icu-report.js')}}"></script>

 {{-----------external js files added for page functions------------}}
<script>
$(document).ready(function() {
    $('.select2-class').select2({
    });
});
</script>
@endsection