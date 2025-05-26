@extends('backend.admin.layouts.main')
@section('title')
Company
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Company</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#addCompanyModel"><i class="ri-add-line "></i> Add Company</a>
    </div>
  </div>
     <!-- Company modal start -->
   <div class="modal fade" id="addCompanyModel" tabindex="-1" role="dialog" aria-labelledby="addCompanyModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white company-title">Add Company</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="companyForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="companyName">Name</label>
                    <input type="hidden" id=companyID>
                    <input class="form-control form-control-sm" id="companyName" type="text"
                        placeholder="Enter Company" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Company Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm companySubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm companyUpdate d-none" type="button"
                            onclick="companyUpdate(document.getElementById('companyID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Company modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Company Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="company-table" data-page-length='10'>
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
    const viewCompany= "{{route('company.viewCompany')}}";
    const addCompany = "{{route('company.addCompany')}}";
    const getCompanyData = "{{route('company.getCompanyData')}}";
    const updateCompanyData = "{{route('company.updateCompanyData')}}";
    const statusUpdate = "{{route('company.statusUpdate')}}";
    const deleteCompany = "{{route('company.deleteCompany')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/company.js')}}"></script>
@endsection