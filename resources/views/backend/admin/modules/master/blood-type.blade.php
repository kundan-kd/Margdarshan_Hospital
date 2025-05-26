@extends('backend.admin.layouts.main')
@section('title')
Blood Type
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Blood Type</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#addBloodTypeModel"><i class="ri-add-line "></i> Add Blood Type</a>
    </div>
  </div>
     <!-- Blood Type modal start -->
 <div class="modal fade" id="addBloodTypeModel" tabindex="-1" role="dialog" aria-labelledby="addBloodTypeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bloodType-title">Add Blood Type</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="bloodTypeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                     <div class="row gy-3">
                      <div class="col-md-12">
                        <label class="form-label" for="bloodTypeName">Name</label>
                        <input type="hidden" id=bloodTypeID>
                        <input class="form-control form-control-sm" id="bloodTypeName" type="text"
                            placeholder="Enter Blood Type" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter Blood Type Name
                        </div>
                    </div>
                    </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm bloodTypeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm bloodTypeUpdate d-none" type="button"
                            onclick="bloodTypeUpdate(document.getElementById('bloodTypeID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Blood Type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Blood Type Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="bloodType-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Status</th>
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
    const viewBloodType= "{{route('blood-type.viewBloodType')}}";
    const addBloodType = "{{route('blood-type.addBloodType')}}";
    const getBloodTypeData = "{{route('blood-type.getBloodTypeData')}}";
    const updateBloodTypeData = "{{route('blood-type.updateBloodTypeData')}}";
    const statusUpdate = "{{route('blood-type.statusUpdate')}}";
    const deleteBloodType = "{{route('blood-type.deleteBloodType')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/blood-type.js')}}"></script>
@endsection