@extends('backend.admin.layouts.main')
@section('title')
Bulk Lead Add
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Bulk Leads</h6>
      <!-- <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="create-bill.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create Bill</a>
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
      <!-- <div class="btns">
          <a class="btn btn-primary-600  btn-sm fw-medium mx-11" href="create-bill.html"><i class="ri-add-line mx-4 "></i>Create Bill</a>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line mx-4 "></i> Export</button>
      </div> -->
  </div>
    <div class="card">
      <div class="card-body">
         <div class="d-flex justify-content-end">
            <a href="{{ asset('backend/uploads/csv-demo.csv') }}" download class="text-decoration-none text-primary">CSV Demo</a>
        </div>
         <div class="row gy-3 mt-2">
          <form action="" id="lead-appendForm" class="needs-validation" novalidate>
                <div class="row gy-3 mt-2">
                    <div class="col-md-3">
                        <label class="form-label fw-medium"> Name</label>
                        <input type="text" id="lead-nameAppend" class="form-control form-control-sm" placeholder="Name" required>
                        <div class="invalid-feedback">
                            Enter Name
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Mobile No</label>
                        <input type="number" id="lead-mobileAppend" class="form-control form-control-sm" placeholder="Mobile No" oninput="this.value=this.value.slice(0,10)" required>
                        <div class="invalid-feedback">
                            Enter Mobile No.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Lead Source</label>
                        <input type="text" id="lead-sourceAppend" class="form-control form-control-sm" placeholder="Lead Source" required>
                        <div class="invalid-feedback">
                            Enter Lead Source
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Address</label>
                        <input type="text" id="lead-addressAppend" class="form-control form-control-sm" placeholder="Address" required>
                        <div class="invalid-feedback">
                            Enter Address
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">City</label>
                        <input type="text" id="lead-cityAppend" class="form-control form-control-sm" placeholder="City" required>
                        <div class="invalid-feedback">
                            Enter City
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">State</label>
                        <select name="state" id="lead-stateAppend" class="form-control form-control-sm" required>
                            <option value="">Select State</option>
                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                            <option value="Daman and Diu">Daman and Diu</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Ladakh">Ladakh</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Puducherry">Puducherry</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                        <div class="invalid-feedback">
                            Select State
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Pin</label>
                        <input type="number" id="lead-pinAppend" class="form-control form-control-sm" placeholder="Pin" oninput="this.value=this.value.slice(0,6)" required>
                        <div class="invalid-feedback">
                            Enter Pin
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                         <button type="button" class="btn btn-primary-600  btn-sm fw-normal" style="margin: 8px;" data-bs-toggle="modal" data-bs-target="#addCSV">Upload</button>
                        <button type="submit" class="btn btn-primary-600  btn-sm fw-medium m-2 leadSubmit"> <i class="ri-checkbox-circle-line"></i> Add Bulk</button>
                        
                    </div>
                </div>
            </form>
          <div class="col-md-12 ">
         <div class="table-responsive scroll-sm leads-table border rounded">
                    <table class="table bordered-table sm-table mb-0 border-0">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Lead Source</th>
                          <th scope="col">Address</th>
                          <th scope="col">State</th>
                          <th scope="col">City</th>
                          <th scope="col" >Pin</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody class="appendLeadData">
                       
                      </tbody>
                    </table>
                </div>
            </div>
          <div class="col-md-12 d-flex justify-content-end">
              <button type="button" class="btn btn-primary-600  btn-sm fw-medium m-2 bulkLeadSubmit d-none" onclick="submitBulkLead()"> <i class="ri-checkbox-circle-line"></i> Submit</button>
              <button class="btn btn-primary-600  btn-sm fw-medium bulkLeadSpinn d-none" type="button">
                        Please Wait...
                        </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- csv upload start -->
  <div class="modal fade" id="addCSV" tabindex="-1" role="dialog" aria-labelledby="addCSV" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white bedtype-title">Upload CSV</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form id="csv_form" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <label class="form-label" for="csv_file">File <span><small>(format should be same as csv demo)</small></span></label>
                    <input class="form-control form-control-sm" id="csv_file" type="file" style="background-image: none;padding-top: 5px !important;" required>
                    <div class="invalid-feedback">
                        Upload CSV file
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-sm csvAddBtn" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm csvSpinnBtn d-none" type="button" disabled>
                        Please Wait...
                        </button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- csv upload end-->
 @endsection
@section('extra-js')
<script>
      $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
    const addBulkLead = "{{route('sales.addBulkLead')}}";
    const csvUpload = "{{ route('sales.csvUpload') }}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/lead.js')}}"></script>
@endsection
 