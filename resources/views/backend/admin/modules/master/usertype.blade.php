@extends('backend.admin.layouts.main')
@section('title')
User Type
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">User Type</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal userType-add" data-bs-toggle="modal" data-bs-target="#addUserTypeModel"><i class="ri-add-line "></i> Add User Type</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addUserTypeModel" tabindex="-1" role="dialog" aria-labelledby="addUserTypeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Add User Type</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addUserTypeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Name</label>
                    <input type="hidden" id=userTypeNameID>
                    <input class="form-control form-control-sm" id="userTypeName" type="text"
                        placeholder="Enter User Type Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter User Type Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addUserTypeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addUserTypeUpdate d-none" type="submit"
                            onclick="userTypeUpdate(document.getElementById('userTypeNameID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">User Type Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="usertype-table" data-page-length='10'>
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
    const viewUserTypes = "{{route('usertype-view.viewUserTypes')}}";
    const addUserType = "{{route('usertype-add.addUserType')}}";
    const getUserTypeData = "{{route('usertype-getdetails.getUserTypeData')}}";
    const updateUserTypeData = "{{route('usertype-update.updateUserTypeData')}}";
    const statusUpdate = "{{route('usertype-status-update.statusUpdate')}}";
    const deleteUserTypeData = "{{route('usertype-delete.deleteUserTypeData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/usertype.js')}}"></script>
@endsection