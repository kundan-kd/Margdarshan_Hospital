@extends('backend.admin.layouts.main')
@section('title')
Lead Add
@endsection
@section('main-container')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Lead</h6>
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
            <form action="" id="lead-addForm" class="needs-validation" novalidate>
                <div class="row gy-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label fw-medium"> Name</label>
                        <input type="text" id="lead-name" class="form-control form-control-sm" placeholder="Name" required>
                        <div class="invalid-feedback">
                            Enter Name
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Mobile No</label>
                        <input type="number" id="lead-mobile" class="form-control form-control-sm" placeholder="Mobile No" oninput="this.value=this.value.slice(0,10)" required>
                        <div class="invalid-feedback">
                            Enter Mobile No.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Lead Source</label>
                        <input type="text" id="lead-source" class="form-control form-control-sm" placeholder="Lead Source" required>
                        <div class="invalid-feedback">
                            Enter Lead Source
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Address</label>
                        <input type="text" id="lead-address" class="form-control form-control-sm" placeholder="Address" required>
                        <div class="invalid-feedback">
                            Enter Address
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">City</label>
                        <input type="text" id="lead-city" class="form-control form-control-sm" placeholder="City" required>
                        <div class="invalid-feedback">
                            Enter City
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">State</label>
                        <select name="state" id="lead-state" class="form-control form-control-sm" required>
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
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Pin</label>
                        <input type="number" id="lead-pin" class="form-control form-control-sm" placeholder="Pin" oninput="this.value=this.value.slice(0,6)" required>
                        <div class="invalid-feedback">
                            Enter Pin
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Campaign Name</label>
                        <input type="text" id="campaign-name" class="form-control form-control-sm" placeholder="Campaign Name">
                        <div class="invalid-feedback">
                            Enter Campaign Name
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <label class="form-label fw-medium">Lead For Team</label>
                        <select id="lead-team" class="form-select form-select-sm" required>
                            <option value="">Choose Team</option>
                            @foreach ($teams as $team)
                            <option value="{{$team->id}}">{{$team->name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Select Team
                        </div>
                    </div> --}}
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-600  btn-sm fw-medium m-2 leadSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
                        <button class="btn btn-primary-600  btn-sm fw-medium leadSpinn d-none" type="button">
                        Please Wait...
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
 @endsection
@section('extra-js')
<script>
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

    const addLead = "{{route('sales.addLead')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/sales/lead.js')}}"></script>
@endsection
 