@extends('backend.admin.layouts.main')
@section('title')
Medicine Group
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Medicine Group</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#addMedicineGroupModel"><i class="ri-add-line "></i> Add Group</a>
    </div>
  </div>
     <!-- Medicine group modal start -->
  <div class="modal fade" id="addMedicineGroupModel" tabindex="-1" role="dialog" aria-labelledby="addMedicineGroupModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white medicineGroup-title">Add Medicine Group</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="medicineGroupForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="medicineGroupName">Name</label>
                    <input type="hidden" id=medicineGroupID>
                    <input class="form-control form-control-sm" id="medicineGroupName" type="text"
                        placeholder="Enter Medicine Group" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Group Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm medicineGroupSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm medicineGroupUpdate d-none" type="button"
                            onclick="medicineGroupUpdate(document.getElementById('medicineGroupID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Medicine group modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Medicine Group Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="medicineGroup-table" data-page-length='10'>
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
    const viewMedicineGroup = "{{route('medicine-group.viewMedicineGroup')}}";
    const addMedicineGroup = "{{route('medicine-group.addMedicineGroup')}}";
    const getMedicineGroupData = "{{route('medicine-group.getMedicineGroupData')}}";
    const updateMedicineGroupData = "{{route('medicine-group.updateMedicineGroupData')}}";
    const statusUpdate = "{{route('medicine-group.statusUpdate')}}";
    const deleteMedicineGroup = "{{route('medicine-group.deleteMedicineGroup')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/medicine-group.js')}}"></script>
@endsection