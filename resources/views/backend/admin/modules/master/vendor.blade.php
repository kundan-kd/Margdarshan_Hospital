@extends('backend.admin.layouts.main')
@section('title')
Vendor
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Vendor</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal vendor-add" data-bs-toggle="modal" data-bs-target="#addVendorModel"><i class="ri-add-line "></i> Add Vendor</a>
    </div>
  </div>
     <!-- vendor modal start -->
  <div class="modal fade" id="addVendorModel" tabindex="-1" role="dialog" aria-labelledby="addVendorModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white vendor-title">Add Vendor</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="vendorForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id=vendorID>
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <label class="form-label" for="vendorName">Name</label>
                            <input class="form-control form-control-sm" id="vendorName" type="text"
                                placeholder="Enter Vendor Name" style="background-image: none;" required>
                            <div class="invalid-feedback">
                                Enter Vendor Name
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="vendorPhone">Phone</label>
                            <input class="form-control form-control-sm" id="vendorPhone" type="text"
                                placeholder="Enter Vendor Phone" style="background-image: none;" required>
                            <div class="invalid-feedback">
                                Enter Vendor Phone Number
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="vendorEmail">Email</label>
                            <input class="form-control form-control-sm" id="vendorEmail" type="text"
                                placeholder="Enter Vendor Email" style="background-image: none;" required>
                            <div class="invalid-feedback">
                                Enter Vendor Email ID
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="vendorAddress">Address</label>
                            <input class="form-control form-control-sm" id="vendorAddress" type="text"
                                placeholder="Enter Vendor Address" style="background-image: none;" required>
                            <div class="invalid-feedback">
                                Enter Vendor Address
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="vendorGST">GST Number</label>
                            <input class="form-control form-control-sm" id="vendorGST" type="text"
                                placeholder="Enter Vendor GST" style="background-image: none;" required>
                            <div class="invalid-feedback">
                                Enter Vendor GST Number
                            </div>
                        </div>
                        </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm vendorSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm vendorUpdate d-none" type="button"
                            onclick="vendorUpdate(document.getElementById('vendorID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Vendor modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Vendor Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="vendor-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Phone</th>
              <th scope="col">Email ID</th>
              <th scope="col">Address</th>
              <th scope="col">Gst Number</th>
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
    const viewVendor= "{{route('vendor.viewVendor')}}";
    const addVendor = "{{route('vendor.addVendor')}}";
    const getVendorData = "{{route('vendor.getVendorData')}}";
    const updateVendorData = "{{route('vendor.updateVendorData')}}";
    const statusUpdate = "{{route('vendor.statusUpdate')}}";
    const deleteVendor = "{{route('vendor.deleteVendor')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/vendor.js')}}"></script>
@endsection