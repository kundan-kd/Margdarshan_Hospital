@extends('backend.admin.layouts.main')
@section('title')
Narration List
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Narration List</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal narationList-add" data-bs-toggle="modal" data-bs-target="#addNarationListModel"><i class="ri-add-line "></i> Add Narration</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addNarationListModel" tabindex="-1" role="dialog" aria-labelledby="addNarationListModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white narationList-title">Add Narration</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addNarationListForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="naration">Narration</label>
                    <input type="hidden" id=narationID>
                    <input class="form-control form-control-sm" id="naration" type="text"
                        placeholder="Enter Narration Title" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Narration
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm narationListSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm narationListUpdate d-none" type="button"
                            onclick="narationListUpdate(document.getElementById('narationID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Narration List Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="narationList-table" data-page-length='10'>
          <thead>
            <tr>
              <th scope="col">Narration</th>
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
    const viewNarationLists = "{{route('naration-list.viewNarationLists')}}";
    const addNarationList = "{{route('naration-list.addNarationList')}}";
    const getNarationListData = "{{route('naration-list.getNarationListData')}}";
    const updateNarationListData = "{{route('naration-list.updateNarationListData')}}";
    const statusUpdate = "{{route('naration-list.statusUpdate')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/naration-list.js')}}"></script>
@endsection