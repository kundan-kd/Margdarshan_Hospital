@extends('backend.admin.layouts.main')
@section('title')
Department
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Department</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#addDepartmentModel"><i class="ri-add-line "></i> Add Department</a>
    </div>
  </div>
     <!-- Department modal start -->
 <div class="modal fade" id="addDepartmentModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white department-title">Add Department</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="departmentForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Name</label>
                    <input type="hidden" id=departmentID>
                    <input class="form-control form-control-sm" id="departmentName" type="text"
                        placeholder="Enter Department" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Department Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm departmentSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm departmentUpdate d-none" type="button"
                            onclick="departmentUpdate(document.getElementById('departmentID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Department modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Department Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="department-table" data-page-length='10'>
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
    const viewDepartments = "{{route('department-view.viewDepartments')}}";
    const addDepartment = "{{route('department-add.addDepartment')}}";
    const getDepartmentData = "{{route('department-getdetails.getDepartmentData')}}";
    const updateDepartmentData = "{{route('department-update.updateDepartmentData')}}";
    const statusUpdate = "{{route('department-status-update.statusUpdate')}}";
    const deleteDepertmentData = "{{route('department-delete.deleteDepertmentData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/department.js')}}"></script>
@endsection