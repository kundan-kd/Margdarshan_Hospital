@extends('backend.admin.layouts.main')
@section('title')
Lead Add
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Leads</h6>
      <!-- <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="create-bill.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create Bill</a>
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
      <!-- <div class="btns">
          <a class="btn btn-primary-600  btn-sm fw-medium mx-11" href="create-bill.html"><i class="ri-add-line mx-4 "></i>Create Bill</a>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line mx-4 "></i> Export</button>
      </div> -->
           
  </div>
  <div class="d-flex align-items-center justify-content-between mb-3">
     <select class="form-select form-select-sm w-20" style="width: 20%;">
                <option>Select team member</option>
                <option>Team A</option>
                <option>Team B</option>
              </select>

       <button class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> Save</button>
  </div>
    <div class="card">
      <div class="card-body">
         <div class="row gy-3">
          <div class="col-md-12 ">
              <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="lead-lists">
                      <thead>
                        <tr>
                          <th scope="col">
                            <div class="d-flex align-items-center gap-10">
                                <!-- <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border input-form-dark" type="checkbox" name="checkbox" id="selectAll">
                                </div> -->
                                Select
                            </div>
                          </th>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Lead Source</th>
                          <th scope="col">Address</th>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col" >Pin</th>
                          <th scope="col">Team</th>
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

  <!-- modal bill-details start -->
     <div class="modal fade" id="assign-team" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="assign-teamLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-600 ">
            <!-- <h6 class="modal-title fw-normal text-md text-white" id="assign-teamLabel">Pharmacy Bill Details</h6> -->
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
             <select class="form-select form-select-sm">
                  <option selected="">Select Team Member</option>
                  <option value="">Team A</option>
                  <option value="1">Team B</option>   
              </select>
              <div class="text-end">
                 <button type="button" class="btn btn-primary-600  btn-sm fw-medium m-2 "> <i class="ri-checkbox-circle-line"></i> Assign</button>
              </div>
          </div>
        </div>
      </div>
    </div>
  <!-- modal bill-details end -->
  @endsection
@section('extra-js')
<script>
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

     const viewLeads = "{{route('sales.viewLeads')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/lead.js')}}"></script>
@endsection
 