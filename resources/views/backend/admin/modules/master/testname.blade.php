@extends('backend.admin.layouts.main')
@section('title')
Test Name
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Test Name</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal TestName-add" data-bs-toggle="modal" data-bs-target="#addTestNameModel"><i class="ri-add-line "></i> Add Test Name</a>
    </div>
  </div>
     <!-- Test Name modal start -->
  <div class="modal fade" id="addTestNameModel" tabindex="-1" role="dialog" aria-labelledby="addTestNameModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
            <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white testName-title">Add Test Name</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="" id="addTestNameForm" class="needs-validation" novalidate="">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <input type="hidden" id=testNameID>
                                <label class="form-label" for="testType_id">Test Type</label>
                                <select class="form-control form-control-sm" name="testType_id" id="testType_id" required>
                                    <option value="">Select</option>
                                    @foreach ($testtypes as $tt)
                                    <option value="{{$tt->id}}">{{$tt->name}}</option>
                                    @endforeach
                                </select>    
                                <div class="invalid-feedback">
                                    Select Test Type
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="testName">Test Name</label>
                                <input class="form-control form-control-sm" id="testName" type="text"
                                    placeholder="Enter Test Name" style="background-image: none;" required>
                                <div class="invalid-feedback">
                                    Enter Test Name
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="testShortName">Short Name</label>
                                <input class="form-control form-control-sm" id="testShortName" type="text"
                                    placeholder="Enter Test Name" style="background-image: none;" required>
                                <div class="invalid-feedback">
                                    Enter Test Short Name
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="testAmount">Test Amount</label>
                                <input class="form-control form-control-sm" id="testAmount" type="number"
                                    placeholder="Enter Test Name" style="background-image: none;" required>
                                <div class="invalid-feedback">
                                    Enter Test Amount
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addTestNameSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addTestNameUpdate d-none" type="submit"
                            onclick="testNameUpdate(document.getElementById('testNameID').value)">Update</button>
                    </div>
                </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Test Name modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Test Name Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="testname-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Test Name</th>
              <th scope="col">Short Name</th>
              <th scope="col">Test Type</th>
              <th scope="col">Test Amount</th>
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
    const viewTestNames = "{{route('testname.viewTestNames')}}";
    const addTestName = "{{route('testname.addTestName')}}";
    const getTestNameData = "{{route('testname.getTestNameData')}}";
    const updateTestNameData = "{{route('testname.updateTestNameData')}}";
    const statusUpdate = "{{route('testname.statusUpdate')}}";
    const deleteTestNameData = "{{route('testname.deleteTestNameData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/testname.js')}}"></script>
@endsection