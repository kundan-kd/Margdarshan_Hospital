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
       <h6 class="fw-normal mb-0">OPD - Out Patient Report</h6>
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
            <table class="table bordered-table mb-0 w-100" id="opd-report-table" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">OPD ID</th>
                  <th scope="col" class="fw-medium">Patient Name</th>
                  <th scope="col" class="fw-medium">DOA</th>
                  <th scope="col" class="fw-medium">DOV</th>
                  <th scope="col" class="fw-medium">Consultant</th>
                  <th scope="col" class="fw-medium">Fee</th>
                  <th scope="col" class="fw-medium">Payment Status</th>
                  <th scope="col" class="fw-medium">Appointment Status</th>
                  <th scope="col" class="fw-medium">Action</th>
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
  const viewOpdReport = "{{route('report.viewOpdReport')}}";
</script>
    <script src="{{asset('backend/assets/js/custom/admin/report/opd-report.js')}}"></script>
</script>
@endsection