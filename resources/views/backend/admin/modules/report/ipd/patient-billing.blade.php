@extends('backend.admin.layouts.main')
@section('title')
    Patient Billing
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
        <h6 class="fw-normal mb-0">Patient Billing Report</h6>
        <div class="row my-3">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" onclick="location.href='{{ route('report.ipdReport') }}'">
            <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio1">Consultant</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" checked onclick="location.href='{{ route('report.ipdBillingReport') }}'">
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

            <button type="button" class="btn btn-primary" onclick="patientBillingFilter()">Search</button>
            </div>
        </div>
        </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="patient-billing-report" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">IP No.</th>
                  <th scope="col" class="fw-medium">Patient</th>
                  <th scope="col" class="fw-medium">Type</th>
                  <th scope="col" class="fw-medium">DOA</th>
                  <th scope="col" class="fw-medium">DOD</th>
                  <th scope="col" class="fw-medium">Consultant</th>
                  <th scope="col" class="fw-medium">Bill Total</th>
                  <th scope="col" class="fw-medium">Paid Amount</th>
                  <th scope="col" class="fw-medium">Due</th>
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
    let patient_billing_table = $('#patient-billing-report').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ route('report.patientBillingReportData') }}",
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
                data:'ip_no',
                name:'ip_no'
            },
            {
                data:'patient',
                name:'patient'
            },
            {
                data:'type',
                name:'type'
            },
            {
                data:'doa',
                name:'doa'
            },
            {
                data:'dod',
                name:'dod'
            },
            {
                data:'consultant',
                name:'consultant'
            },
            {
                data:'bill',
                name:'bill'
            },
            {
                data:'paid',
                name:'paid'
            },
            {
                data:'due',
                name:'due'
            },
            {
                data:'action',
                name:'action'
            }
        ]
    });
    function patientBillingFilter(){
        patient_billing_table.ajax.reload();
    }
    function printBill(id,admit_id){
        window.open('/discharge-bill-print/' + id + '/' + admit_id);
    }
    function dischargeFormPrint(id,admit_id){
        window.open('/discharge-form-print/' + id + '/' + admit_id);
    }
</script>

@endsection