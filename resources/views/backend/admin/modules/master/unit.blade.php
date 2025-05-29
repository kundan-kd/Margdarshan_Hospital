@extends('backend.admin.layouts.main')
@section('title')
Unit
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Unit</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal unit-add" data-bs-toggle="modal" data-bs-target="#addUnitModel"><i class="ri-add-line "></i> Add Unit</a>
    </div>
  </div>
     <!-- Unit modal start -->
  <div class="modal fade" id="addUnitModel" tabindex="-1" role="dialog" aria-labelledby="addUnitModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white unit-title">Add Unit</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="unitForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                    <div class="row gy-3">
                    <div class="col-md-12">
                        <label class="form-label" for="unitName">Name</label>
                        <input type="hidden" id=unitID>
                        <input class="form-control form-control-sm mb-1" id="unitName" type="text"
                            placeholder="Enter Unit Name" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter Unit Name
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="unit">Unit</label>
                        <input class="form-control form-control-sm" id="unit" type="text"
                            placeholder="Enter Unit" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter Unit
                        </div>
                    </div>
                    </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm unitSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm unitUpdate d-none" type="button"
                            onclick="unitUpdate(document.getElementById('unitID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Unit modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Unit Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="unit-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Unit</th>
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
    const viewUnit= "{{route('unit.viewUnit')}}";
    const addUnit = "{{route('unit.addUnit')}}";
    const getUnitData = "{{route('unit.getUnitData')}}";
    const updateUnitData = "{{route('unit.updateUnitData')}}";
    const statusUpdate = "{{route('unit.statusUpdate')}}";
    const deleteUnit = "{{route('unit.deleteUnit')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/unit.js')}}"></script>
@endsection