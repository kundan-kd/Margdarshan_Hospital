@extends('backend.admin.layouts.main')
@section('title')
Room Number
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Room Number</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal roomNum-add" data-bs-toggle="modal" data-bs-target="#addRoomNumModel"><i class="ri-add-line "></i> Add Room Number</a>
    </div>
  </div>
     <!-- room type modal start -->
  <div class="modal fade" id="addRoomNumModel" tabindex="-1" role="dialog" aria-labelledby="addRoomNumModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white roomNum-title">Add Room Number</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addRoomNumForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                    <input type="hidden" id=roomNumID>
                    <div class="col-md-12">
                        <label class="form-label" for="roomGroup">Room Group</label>
                        <select class="form-control form-control-sm" name="roomGroup" id="roomGroup" required>
                            <option value="">Select</option>
                            @foreach ($bedRoomGroups as $rg)
                            <option value="{{$rg->id}}">{{$rg->name}}</option>
                            @endforeach
                        </select>    
                        <div class="invalid-feedback">
                            Select Room Group
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="roomType">Room Type</label>
                        <select class="form-control form-control-sm" name="roomType" id="roomType" required>
                            <option value="">Select</option>
                            @foreach ($roomTypes as $rt)
                            <option value="{{$rt->id}}">{{$rt->name}}</option>
                            @endforeach
                        </select>    
                        <div class="invalid-feedback">
                            Select Room Type
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="roomNum">Room Number</label>
                        <input class="form-control form-control-sm" id="roomNum" type="text"
                            placeholder="Enter Room Number" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter Room Number
                        </div>
                    </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm roomNumSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm roomNumUpdate d-none" type="submit"
                            onclick="roomNumUpdate(document.getElementById('roomNumID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- room type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Room Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="roomNum-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Room Number</th>
              <th scope="col">Room Group</th>
              <th scope="col">Room Type</th>
              <th scope="col">Current Status</th>
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
    const viewRoomNums = "{{route('roomnum.viewRoomNums')}}";
    const addRoomNum = "{{route('roomnum.addRoomNum')}}";
    const getRoomNumData = "{{route('roomnum.getRoomNumData')}}";
    const updateRoomNumData = "{{route('roomnum.updateRoomNumData')}}";
    const statusUpdate = "{{route('roomnum.statusUpdate')}}";
    const deleteRoomNumData = "{{route('roomnum.deleteRoomNumData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/room-num.js')}}"></script>
@endsection