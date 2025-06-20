@extends('backend.admin.layouts.main')
@section('title')
Room Type
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Room Type</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal roomType-add" data-bs-toggle="modal" data-bs-target="#addRoomTypeModel"><i class="ri-add-line "></i> Add Room Type</a>
    </div>
  </div>
     <!-- room type modal start -->
  <div class="modal fade" id="addRoomTypeModel" tabindex="-1" role="dialog" aria-labelledby="addRoomTypeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white roomType-title">Add room Type</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addRoomTypeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="roomTypeName">Name</label>
                    <input type="hidden" id=roomTypeNameID>
                    <input class="form-control form-control-sm" id="roomTypeName" type="text"
                        placeholder="Enter room Type Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Room Type Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm roomTypeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm roomTypeUpdate d-none" type="submit"
                            onclick="roomTypeUpdate(document.getElementById('roomTypeNameID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- room type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Room Type Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="roomtype-table" data-page-length='10'>
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
    const viewRoomTypes = "{{route('roomtype.viewRoomTypes')}}";
    const addRoomType = "{{route('roomtype.addRoomType')}}";
    const getRoomTypeData = "{{route('roomtype.getRoomTypeData')}}";
    const updateRoomTypeData = "{{route('roomtype.updateRoomTypeData')}}";
    const statusUpdate = "{{route('roomtype.statusUpdate')}}";
    const deleteRoomTypeData = "{{route('roomtype.deleteRoomTypeData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/roomtype.js')}}"></script>
@endsection