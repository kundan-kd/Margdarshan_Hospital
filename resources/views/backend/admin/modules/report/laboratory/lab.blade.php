@extends('backend.admin.layouts.main')
@section('title')
    Lab Report
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
       <h6 class="fw-normal mb-0">Laboratory Report</h6>
     </div>
        <div class="row my-3">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
            <label for="start_date">FROM </label>
            <input type="date" class="form-control ms-2 me-3" id="start_date" value="">

            <label for="end_date" class="me-2">TO </label>
            <input type="date" class="form-control ms-2 me-3" id="end_date" value="">

            <button type="button" class="btn btn-primary" onclick="labReportFilter()">Search</button>
            </div>
        </div>
        </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="lab-report-table" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">Patient Id & Name</th>
                  <th scope="col" class="fw-medium">Test Type</th>
                  <th scope="col" class="fw-medium">Test Name</th>
                  <th scope="col" class="fw-medium">Amount</th>
                  <th scope="col" class="fw-medium">Sample Date</th>
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
 let lab_table = $('#lab-report-table').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:"{{ route('report.labReportData') }}",
        type:"POST",
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        },
        data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        }
    },    
    columns:
    [
        {data:'patient',name:'patient'},
        {data:'test_type',name:'test_type'},
        {data:'test_name',name:'test_name'},
        {data:'amount',name:'amount'},
        {data:'created_at',name:'created_at'}
    ]
});
function labReportFilter(){
   lab_table.ajax.reload();
}
</script>
@endsection