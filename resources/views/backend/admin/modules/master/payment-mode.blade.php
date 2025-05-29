@extends('backend.admin.layouts.main')
@section('title')
Payment Mode
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Payment Mode</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal paymentMode-add" data-bs-toggle="modal" data-bs-target="#addPaymentModeModel"><i class="ri-add-line "></i> Add Payment Mode</a>
    </div>
  </div>
     <!-- Payment Mode modal start -->
 <div class="modal fade" id="addPaymentModeModel" tabindex="-1" role="dialog" aria-labelledby="addPaymentModeModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white paymentMode-title">Add Payment Mode</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="paymentModeForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Name</label>
                    <input type="hidden" id=paymentModeID>
                    <input class="form-control form-control-sm" id="paymentModeName" type="text"
                        placeholder="Enter Payment Mode" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Payment Mode Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm paymentModeSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm paymentModeUpdate d-none" type="button"
                            onclick="paymentModeUpdate(document.getElementById('paymentModeID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Payment Mode modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Payment Mode Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="paymentMode-table" data-page-length='10'>
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
    const viewPaymentModes = "{{route('paymentmode-view.viewPaymentModes')}}";
    const addPaymentMode = "{{route('paymentmode-add.addPaymentMode')}}";
    const getPaymentModeData = "{{route('paymentmode-getdetails.getPaymentModeData')}}";
    const updatePaymentModeData = "{{route('paymentmode-update.updatePaymentModeData')}}";
    const statusUpdate = "{{route('paymentmode-status-update.statusUpdate')}}";
    const deletePaymentModeData = "{{route('paymentmode-delete.deletePaymentModeData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/payment-mode.js')}}"></script>
@endsection