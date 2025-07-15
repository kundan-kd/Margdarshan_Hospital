@extends('backend.admin.layouts.main')
@section('title')
Trash List
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Trash List</h6>
      <!-- <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="create-bill.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create Bill</a>
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
      <!-- <div class="btns">
          <a class="btn btn-primary-600  btn-sm fw-medium mx-11" href="create-bill.html"><i class="ri-add-line mx-4 "></i>Create Bill</a>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line mx-4 "></i> Export</button>
      </div> -->
           
  </div>
    <div class="card">
      <div class="card-body">
         <div class="row gy-3">
          <div class="col-md-12 ">
              <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="lead-trash-lists">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Lead Source</th>
                          <th scope="col">Address</th>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col" >Pin</th>
                          <th scope="col">Assigned To</th>
                          <th scope="col">Trashed At</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                       
                      </tbody>
                    </table>
                </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>


  @endsection
@section('extra-js')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
   $('#lead-trash-lists').on('draw.dt', function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
  });
     const viewTrashLead = "{{route('sales.viewTrashLead')}}";
     const restoreLeadData = "{{route('sales.restoreLeadData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/lead.js')}}"></script>
@endsection
 