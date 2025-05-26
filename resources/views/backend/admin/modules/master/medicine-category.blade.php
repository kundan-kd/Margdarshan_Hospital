@extends('backend.admin.layouts.main')
@section('title')
Medicine Category
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Medicine Category</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#addMedicineCategoryModel"><i class="ri-add-line "></i> Add Category</a>
    </div>
  </div>
     <!-- Medicine Category modal start -->
 <div class="modal fade" id="addMedicineCategoryModel" tabindex="-1" role="dialog" aria-labelledby="addMedicineCategoryModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white medicineCategory-title">Add Medicine Category</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="medicineCategoryForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="medicineCategoryName">Name</label>
                    <input type="hidden" id=medicineCategoryID>
                    <input class="form-control form-control-sm" id="medicineCategoryName" type="text"
                        placeholder="Enter Medicine Category" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Category Name
                    </div>
                 </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm medicineCategorySubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm medicineCategoryUpdate d-none" type="button"
                            onclick="medicineCategoryUpdate(document.getElementById('medicineCategoryID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Medicine Category modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Medicine Category Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="medicineCategory-table" data-page-length='10'>
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
    const viewMedicineCategory = "{{route('medicine-category.viewMedicineCategory')}}";
    const addMedicineCategory = "{{route('medicine-category.addMedicineCategory')}}";
    const getMedicineCategoryData = "{{route('medicine-category.getMedicineCategoryData')}}";
    const updateMedicineCategoryData = "{{route('medicine-category.updateMedicineCategoryData')}}";
    const statusUpdate = "{{route('medicine-category.statusUpdate')}}";
    const deleteMedicineCategory = "{{route('medicine-category.deleteMedicineCategory')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/medicine-category.js')}}"></script>
@endsection