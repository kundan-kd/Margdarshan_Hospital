@extends('backend.admin.layouts.main')
@section('title')
Bed
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Bed</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal bed-add" data-bs-toggle="modal" data-bs-target="#addBedModel"><i class="ri-add-line "></i> Add Bed</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addBedModel" tabindex="-1" role="dialog" aria-labelledby="addBedModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bed-title">Add Bed</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addBedForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <input type="hidden" id=bedID>
                    <label class="form-label" for="bedGroup">Bed Group</label>
                    <select class="form-control form-control-sm" name="bedGroup" id="bedGroup" required>
                        <option value="">Select</option>
                        @foreach ($bedgroups as $bg)
                        <option value="{{$bg->id}}">{{$bg->name}}</option>
                        @endforeach
                    </select>    
                    <div class="invalid-feedback">
                        Select Bed Group
                    </div>
                </div>
                  <div class="col-md-12">
                    <label class="form-label" for="bedType">Bed Type</label>
                    <select class="form-control form-control-sm" name="bedType" id="bedType" required>
                        <option value="">Select</option>
                        @foreach ($bedtypes as $bt)
                        <option value="{{$bt->id}}">{{$bt->name}}</option>
                        @endforeach
                    </select>    
                    <div class="invalid-feedback">
                        Select Bed Type
                    </div>
                </div>
                  <div class="col-md-12">
                    <label class="form-label" for="bedFloor">Floor</label>
                    <select class="form-control form-control-sm" name="bedFloor" id="bedFloor" required>
                        <option value="">Select</option>
                        <option value="-1">Under Ground Floor</option>
                        <option value="0">Ground Floor</option>
                        <option value="1">1st Floor</option>
                        <option value="2">2nd Floor</option>
                        <option value="3">3rd Floor</option>
                        <option value="4">4rd Floor</option>
                        <option value="5">5rd Floor</option>
                    </select>    
                    <div class="invalid-feedback">
                        Select Bed Flood
                    </div>
                </div>
                  <div class="col-md-12">
                    <label class="form-label" for="bedNumber">Bed Name/Number</label>
                    <input class="form-control form-control-sm" id="bedNumber" type="text"
                        placeholder="Enter Bed Number" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Bed Number
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm addBedSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addBedUpdate d-none" type="submit"
                            onclick="bedUpdate(document.getElementById('bedID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Bed Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="bed-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Bed Number</th>
              <th scope="col">Bed Group</th>
              <th scope="col">Bed Type</th>
              <th scope="col">Floor</th>
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
    const viewBeds = "{{route('bed.viewBeds')}}";
    const addBed = "{{route('bed.addBed')}}";
    const getBedData = "{{route('bed.getBedData')}}";
    const updateBedData = "{{route('bed.updateBedData')}}";
    const statusUpdate = "{{route('bed.statusUpdate')}}";
    const deleteBedData = "{{route('bed.deleteBedData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/bed.js')}}"></script>
@endsection