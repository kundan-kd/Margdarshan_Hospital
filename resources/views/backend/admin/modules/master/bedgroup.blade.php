@extends('backend.admin.layouts.main')
@section('title')
Bed/Room Group
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Bed/Room Group</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal bedGroup-add" data-bs-toggle="modal" data-bs-target="#addBedGroupModel"><i class="ri-add-line "></i> Add Group</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addBedGroupModel" tabindex="-1" role="dialog" aria-labelledby="addBedGroupModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedGroup-title">Add Bed Group</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addBedGroupForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Name</label>
                    <input type="hidden" id=bedGroupNameID>
                    <input class="form-control form-control-sm" id="bedGroupName" type="text"
                        placeholder="Enter Group Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Bed/Room Group Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addBedGroupSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addBedGroupUpdate d-none" type="submit"
                            onclick="bedGroupUpdate(document.getElementById('bedGroupNameID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Bed/Room Group Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="bedGroup-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col align-items-left">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
           {{-- here data appended through datatable --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
@section('extra-js')
<script>
    const viewBedGroups = "{{route('bedgroup.viewBedGroups')}}";
    const addBedGroup = "{{route('bedgroup.addBedGroup')}}";
    const getBedGroupData = "{{route('bedgroup.getBedGroupData')}}";
    const updateBedGroupData = "{{route('bedgroup.updateBedGroupData')}}";
    const statusUpdate = "{{route('bedgroup.statusUpdate')}}";
    const deleteBedGroupData = "{{route('bedgroup.deleteBedGroupData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/bedgroup.js')}}"></script>
@endsection