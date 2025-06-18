@extends('backend.admin.layouts.main')
@section('title')
Bed Type
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Bed Type</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal userType-add" data-bs-toggle="modal" data-bs-target="#addBedTypeModel"><i class="ri-add-line "></i> Add Bed Type</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addBedTypeModel" tabindex="-1" role="dialog" aria-labelledby="addBedTypeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Add Bed Type</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addBedTypeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="bedTypeName">Name</label>
                    <input type="hidden" id=bedTypeNameID>
                    <input class="form-control form-control-sm" id="bedTypeName" type="text"
                        placeholder="Enter User Type Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Bed Type Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addBedTypeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addBedTypeUpdate d-none" type="submit"
                            onclick="bedTypeUpdate(document.getElementById('userTypeNameID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Bed Type Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="bedtype-table" data-page-length='10'>
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
    const viewBedTypes = "{{route('bedtype.viewBedTypes')}}";
    const addBedType = "{{route('bedtype.addBedType')}}";
    const getBedTypeData = "{{route('bedtype.getBedTypeData')}}";
    const updateBedTypeData = "{{route('bedtype.updateBedTypeData')}}";
    const statusUpdate = "{{route('bedtype.statusUpdate')}}";
    const deleteBedTypeData = "{{route('bedtype.deleteBedTypeData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/bedtype.js')}}"></script>
@endsection