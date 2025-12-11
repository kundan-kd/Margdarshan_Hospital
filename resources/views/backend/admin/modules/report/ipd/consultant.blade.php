@extends('backend.admin.layouts.main')
@section('title')
    Consultant Report
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
        <h6 class="fw-normal mb-0">Consultant</h6>
        <div class="row my-3">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" checked onclick="location.href='{{ route('report.ipdReport') }}'">
            <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio1">Consultant</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" onclick="location.href='{{ route('report.ipdBillingReport') }}'">
            <label class="btn btn-outline-primary-600 px-20 py-11" for="btnradio2">Patient Billing</label>
            </div>
        </div>
        </div>
        <div class="row my-3">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
            <label for="start_date">FROM </label>
            <input type="date" class="form-control ms-2 me-3" id="start_date" value="">

            <label for="end_date" class="me-2">TO </label>
            <input type="date" class="form-control ms-2 me-3" id="end_date" value="">

            <button type="button" class="btn btn-primary" onclick="consultantFilter()">Search</button>
            </div>
        </div>
        </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="consultant-report-table" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">Dr. Name</th>
                  <th scope="col" class="fw-medium">Patient</th>
                  <th scope="col" class="fw-medium">Charge</th>
                  <th scope="col" class="fw-medium">Suggestion</th>
                  <th scope="col" class="fw-medium">Round Time</th>
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
let consultant_table = $('#consultant-report-table').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url: "{{ route('report.consultantReportData') }}",
        type: "POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        },
        error: function(xhr,status,thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
        {
            data:'doctor',
            name:'doctor'
        },
        {
            data:'patient',
            name:'patient'
        },
        {
            data:'charge',
            name:'charge'
        },
        {
            data:'note',
            name:'note'
        },
        {
            data:'created_at',
            name:'created_at'
        }
    ]
});
function consultantFilter(){
    consultant_table.ajax.reload();
}
</script>

@endsection