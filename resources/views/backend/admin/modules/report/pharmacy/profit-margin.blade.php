@extends('backend.admin.layouts.main')
@section('title')
    Profit Report
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
      <h6 class="fw-normal mb-0">Profit Margin</h6>
      <div class="row my-3">
          <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" onclick="location.href='{{ route('report.expiryReport') }}'">
            <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio1">Expiry</label>
            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" checked onclick="location.href='{{ route('report.profitMargin') }}'">
            <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio3">Profit</label>
          </div>
      </div>
    </div>
    <div class="card basic-data-table">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table bordered-table mb-0 w-100" id="profit-report-table" data-page-length='10'>
              <thead>
                <tr>
                  <th scope="col" class="fw-medium">Bill No</th>
                  <th scope="col" class="fw-medium">Bill Date</th>
                  <th scope="col" class="fw-medium">Purchase Cost</th>
                  <th scope="col" class="fw-medium">Sales Amount</th>
                  <th scope="col" class="fw-medium">Profit</th>
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
  //  const getBillingProfitData = "{{route('report.profitMarginData')}}";

   let profit_margin = $('#profit-report-table').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url: "{{route('report.profitMarginData')}}",
        type:"POST",
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert("Error: "+thrown);
        }
    },  
        columns:[
            {
                data:'bill_no',
                name:'bill_no'
            },
            {
                data:'bill_date',
                name:'bill_date'
            },
            {
                data:'purchase_cost',
                name:'purchase_cost'
            },
            {
                data:'sales_amount',
                name:'sales_amount'
            },
            {
                data:'profit',
                name:'profit'
            }
        ]
});
</script>
{{-- <script src="{{asset('backend/assets/js/custom/admin/report/pharmacy-report.js')}}"></script> --}}
@endsection