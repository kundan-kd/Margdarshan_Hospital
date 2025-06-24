@extends('backend.admin.layouts.main')
@section('title')
Test Type
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Test Type</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal testtype-add" data-bs-toggle="modal" data-bs-target="#addTestTypeModel"><i class="ri-add-line "></i> Add Test Type</a>
    </div>
  </div>
     <!-- Test type modal start -->
  <div class="modal fade" id="addTestTypeModel" tabindex="-1" role="dialog" aria-labelledby="addTestTypeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white TestType-title">Add Test Type</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addTestTypeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Name</label>
                    <input type="hidden" id=testTypeNameID>
                    <input class="form-control form-control-sm" id="testTypeName" type="text"
                        placeholder="Enter Test Type Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Test Type Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addTestTypeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addTestTypeUpdate d-none" type="submit"
                            onclick="testTypeUpdate(document.getElementById('testTypeNameID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Test type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Test Type Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="testtype-table" data-page-length='10'>
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
    const viewTestTypes = "{{route('testtype.viewTestTypes')}}";
    const addTestType = "{{route('testtype.addTestType')}}";
    const getTestTypeData = "{{route('testtype.getTestTypeData')}}";
    const updateTestTypeData = "{{route('testtype.updateTestTypeData')}}";
    const statusUpdate = "{{route('testtype.statusUpdate')}}";
    const deleteTestTypeData = "{{route('testtype.deleteTestTypeData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/testtype.js')}}"></script>
@endsection