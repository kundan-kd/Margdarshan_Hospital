@extends('backend.admin.layouts.main')
@section('title')
User
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">User</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal bed-add" data-bs-toggle="modal" data-bs-target="#add-user"><i class="ri-add-line "></i> Add New User</a>
    </div>
  </div>
 <!-- user model start -->
<div class="modal fade" id="add-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-userLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary-600 p-11">
        <h6 class="modal-title fw-normal text-white text-md" id="user-add-appointmentLabel">Add User</h6>
        <button type="button" class="btn-close btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="addUser-form">
        <div class="modal-body">
          <div class="row gy-3">
            <input type="hidden" id="userId">
            <div class="col-6">
              <label class="form-label fw-normal" for="user-departmentId">Department</label>
                <select class="form-select form-select-sm select2-cls" id="user-departmentId" style="width: 100%" oninput="validateField(this.id,'select')">
                  <option value="">Select</option>
                  <option value="Married">Married</option>
                  <option value="UnMarried">UnMarried</option>
                </select>            
              <div class="user-departmentId_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-userType">User Type</label>
                <select class="form-select form-select-sm select2-cls" id="user-userType" style="width: 100%" oninput="validateField(this.id,'select')">
                  <option value="">Select</option>
                  <option value="Married">Married</option>
                  <option value="UnMarried">UnMarried</option>
                </select>                    
                <div class="user-userType_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-bloodType">Blood Type</label>
                <select class="form-select form-select-sm select2-cls" id="user-bloodType" style="width: 100%" oninput="validateField(this.id,'select')">
                  <option value="">Select</option>
                  <option value="Married">Married</option>
                  <option value="UnMarried">UnMarried</option>
                </select>
                <div class="user-bloodType_errorCls d-none"></div>  
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-name">Name</label>
              <input type="text" id="user-name" class="form-control form-control-sm" placeholder="Enter User Name" oninput="validateField(this.id,'input')">
              <div class="user-name_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-fname">Father's Name</label>
              <input type="text" id="user-fname" class="form-control form-control-sm" placeholder="Enter Father's Name" oninput="validateField(this.id,'input')">
              <div class="user-fname_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-mname">Mother's Name</label>
              <input type="text" id="user-mname" class="form-control form-control-sm" placeholder="Enter Mother's Name" oninput="validateField(this.id,'input')">
              <div class="user-mname_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-dob">DOB</label>
              <input type="date" id="user-dob" class="form-control form-control-sm" placeholder="Enter Date of birth" oninput="validateField(this.id,'input')">
              <div class="user-dob_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-doj">DOJ</label>
              <input type="date" id="user-doj" class="form-control form-control-sm" placeholder="Enter Date of joining" oninput="validateField(this.id,'input')">
              <div class="user-doj_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-pan">PAN</label>
              <input type="text" id="user-pan" class="form-control form-control-sm" placeholder="Enter PAN Number" oninput="validateField(this.id,'input')">
              <div class="user-pan_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-adhar">Adhar No.</label>
              <input type="text" id="user-adhar" class="form-control form-control-sm" placeholder="Enter Adhar Number" oninput="validateField(this.id,'input')">
              <div class="user-adhar_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-email">Email ID</label>
              <input type="text" id="user-email" class="form-control form-control-sm" placeholder="Enter Valid Email ID" oninput="validateField(this.id,'email')">
              <div class="user-email_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-mobile">Phone No.</label>
              <input type="text" id="user-mobile" class="form-control form-control-sm" placeholder="Enter Phone Number" oninput="validateField(this.id,'mobile')">
              <div class="user-mobile_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-pass">Password</label>
              <input type="password" id="user-pass" class="form-control form-control-sm" placeholder="Enter Password" oninput="validateField(this.id,'input')">
              <div class="user-pass_errorCls d-none"></div>
            </div>
            <div class="col-6">
              <label class="form-label fw-normal" for="user-cpass">Confirm Password</label>
              <input type="text" id="user-cpass" class="form-control form-control-sm" placeholder="Re-Enter Password" oninput="validateField(this.id,'input')">
              <div class="user-cpass_errorCls d-none"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal userAddSubmit">Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal userAddUpdate d-none" onclick="userAddUpdate(document.getElementById('userId').value)">Update</button>
           <!-- <button type="button" class="btn btn-warning-600  btn-sm fw-normal">Save & Book Appointment</button>  -->
        </div>
      </form>
    </div>
  </div>
</div>
  <!-- user model end -->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">User Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="user-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Phone</th>
              <th scope="col">Email ID</th>
              <th scope="col">DOJ</th>
              <th scope="col">Department</th>
              <th scope="col">User Type</th>
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
    $('#add-user').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#add-user')
      });
    });

     const viewUsers = "{{route('user.viewUsers')}}";
     const addUser = "{{route('user.addUser')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/user.js')}}"></script>
@endsection