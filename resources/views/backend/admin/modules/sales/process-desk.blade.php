@extends('backend.admin.layouts.main')
@section('title')
Process Desk
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Process Desk</h6>
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
         <div class="row gy-3 mt-2">
          <div class="col-md-12 ">
              <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0" id="process-desk-table">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Address</th>
                          <th scope="col">Assign To</th>
                          <th scope="col">Narations</th>
                          <th scope="col">Next Follow up</th>
                          <th scope="col">Status</th>
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

  <!-- modal add-narration start -->
     <div class="modal fade" id="add-narration" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-narrationLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-600 ">
            <h6 class="modal-title fw-normal text-md text-white" id="add-narrationLabel">Add Narration</h6>
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
            <label class="form-label fw-medium" for="processDesk-naration">Narration</label>
            <input type="hidden" id="processDesk-leadId">
             <textarea name="#0" class="form-control" id="processDesk-naration" rows="2" cols="50" placeholder="Lead Description" oninput="validateField(this.id,'input')"></textarea>
              <div class="processDesk-naration_errorCls d-none"></div>
             <div class="text-end ">
                <button type="button" class="btn btn-primary-600  btn-sm fw-medium mt-3 narationSubmit" onclick="narationSubmit(document.getElementById('processDesk-leadId').value)"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                 <button class="btn btn-primary-600  btn-sm fw-medium narationSpinn d-none" type="button">
                        Please Wait...
                        </button>
             </div>
          </div>
        </div>
      </div>
    </div>
  <!-- modal add-narration end -->
  
  <!-- modal next follow start -->
     <div class="modal fade" id="add-nextFollowUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-nextFollowUpLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-600 ">
            <h6 class="modal-title fw-normal text-md text-white" id="add-nextFollowUpLabel">Add Next Follow up Date</h6>
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
            <label class="form-label fw-medium" for="followup-date">Follow up Date</label>
            <input type="hidden" id="followup-leadId">
             <input class="form-control" id="followup-date" placeholder="DD/MM/YYYY" oninput="validateField(this.id,'select')">
              <div class="followup-date_errorCls d-none"></div>
             <div class="text-end ">
                <button type="button" class="btn btn-primary-600  btn-sm fw-medium mt-3 followupSubmit" onclick="followupSubmit(document.getElementById('followup-leadId').value)"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                 <button class="btn btn-primary-600  btn-sm fw-medium followupSpinn d-none" type="button">
                        Please Wait...
                        </button>
             </div>
          </div>
        </div>
      </div>
    </div>
  <!-- modal next follow end -->
     <!-- modal transfer-to start -->
     <div class="modal fade" id="transfer-to" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="transfer-toLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header p-11 bg-primary-600 ">
            <h6 class="modal-title fw-normal text-md text-white" id="transfer-toLabel">Transfer To</h6>
            <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close" ></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="leadTransfer-leadId">
            <label class="form-label fw-medium" for="leadTransfer-teamId">Team Member</label>
            <select class="form-select form-select-sm mb-11" id="leadTransfer-teamId" onchange="validateField(this.id,'select')">
                <option value="">Select Team Member</option>
                @foreach ($salesTeamMember as $teamMember)
                <option value="{{$teamMember->id}}">{{$teamMember->name}} ({{$teamMember->teamData->name}})</option>                 
                @endforeach
              </select>
              <div class="leadTransfer-teamId_errorCls d-none"></div>
            <label class="form-label fw-medium" for="leadTransfer-reason">Reason Of Transfer</label>
             <textarea id="leadTransfer-reason" class="form-control" rows="2" cols="50" placeholder="Reason of transfer" oninput="validateField(this.id,'input')"></textarea>
             <div class="leadTransfer-reason_errorCls d-none"></div>
             <div class="text-end ">
                <button type="button" class="btn btn-primary-600  btn-sm fw-medium mt-3 transterToSubmit" onclick="transferToSubmit(document.getElementById('leadTransfer-leadId').value)"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                <button class="btn btn-primary-600  btn-sm fw-medium transterToSpinn d-none" type="button">
                        Please Wait...
                        </button>
             </div>
          </div>
        </div>
      </div>
    </div>
  <!-- modal transfer-to end -->
  {{-- Naration view model start --}}
    <div class="modal fade" id="narationView" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-normal text-md text-white" id="assign-teamLabel">Previous Narations</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="narationHistory">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Sr. No.</th>
                                    <th scope="col">Narrations</th>
                                    <th scope="col">Date & Time</th>
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
  {{-- Naration view model end --}}
@endsection
@section('extra-js')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $('#process-desk-table').on('draw.dt', function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
  });
    // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y ",
        });
    }
    getDatePicker('#followup-date'); 
const viewProcessDeskLeads = "{{route('sales.viewProcessDeskLeads')}}";
const narationAdd = "{{route('sales.narationAdd')}}";
const getNarationData = "{{route('sales.getNarationData')}}";
const tranferToDataSubmit = "{{route('sales.tranferToDataSubmit')}}";
const followupDateSubmit = "{{route('sales.followupDateSubmit')}}";
const deleteLeadData = "{{route('sales.trashLeadData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/process-desk.js')}}"></script>
@endsection
 