@extends('backend.admin.layouts.main')
@section('title')
Lead Center
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Lead Assign</h6>
      <!-- <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="create-bill.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create Bill</a>
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
      <!-- <div class="btns">
          <a class="btn btn-primary-600  btn-sm fw-medium mx-11" href="create-bill.html"><i class="ri-add-line mx-4 "></i>Create Bill</a>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line mx-4 "></i> Export</button>
      </div> -->
           
  </div>
  <div class="d-flex align-items-center justify-content-end mb-3">
      <button class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" onclick="bulkAssign()"> Bulk Assign</button>
  </div>
    <div class="card">
      <div class="card-body">
         <div class="row gy-3">
          <div class="col-md-12 ">
              <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="lead-center-lists">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Lead Source</th>
                          <th scope="col">Address</th>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col" >Pin</th>
                          <th scope="col" >Campaign</th>
                          <th scope="col">Assigned To</th>
                          <th scope="col">Lead Created</th>
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

  <!-- modal bill-details start -->
     <div class="modal fade" id="single-lead-assign-team" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="single-lead-assign-teamLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-600 ">
            <h6 class="modal-title fw-normal text-md text-white" id="assign-teamLabel">Assign To Member</h6>
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="leadId">
            <label for="lead-center-teamMemberId" style="display: none;">Team Member</label>
             <select class="form-select form-select-sm select2-cls" id="lead-center-teamMemberId" style="width: 100%;" onchange="validateField(this.id,'select')">
                  <option value="">Select Team Member</option>
                  @foreach ($salesTeamMember as $teamMember)
                  <option value="{{$teamMember->id}}">{{$teamMember->name}} ({{$teamMember->teamData->name}})</option>
                  @endforeach 
              </select>
               <div class="lead-center-teamMemberId_errorCls d-none"></div>
              <div class="text-end">
                 <button type="button" class="btn btn-primary-600  btn-sm fw-medium m-2 singleLeadAssignSubmit" onclick="singleLeadAssignSubmit(document.getElementById('lead-center-teamMemberId').value)"> <i class="ri-checkbox-circle-line"></i> Assign</button>
                 <button class="btn btn-primary-600  btn-sm fw-medium singleLeadAssignSpinn d-none" type="button">
                        Please Wait...
                        </button>
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
  $('#single-lead-assign-team').on('shown.bs.modal', function () {
    $('.select2-cls').select2({
        dropdownParent: $('#single-lead-assign-team')
    });
  });
  $('#lead-center-lists').on('draw.dt', function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
  });

     const viewSingleAssignLeads = "{{route('sales.viewSingleAssignLeads')}}";
     const assignSingleLead = "{{route('sales.assignSingleLead')}}";
     const trashLeadData = "{{route('sales.trashLeadData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/lead-center-single.js')}}"></script>
@endsection
 