@extends('backend.admin.layouts.main')
@section('title')
Team
@endsection
@section('main-container')
<div class="dashboard-main-body">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-normal mb-0">Team</h6>
    <div class="btns">
      <a class="btn btn-primary-600  btn-sm fw-normal team-add" data-bs-toggle="modal" data-bs-target="#addteamModel"><i class="ri-add-line "></i> Add Team</a>
    </div>
  </div>
     <!-- user type modal start -->
  <div class="modal fade" id="addTeamModel" tabindex="-1" role="dialog" aria-labelledby="addTeamModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white team-title">Add Team</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form action="" id="addTeamForm" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="teamName">Name</label>
                    <input type="hidden" id=teamID>
                    <input class="form-control form-control-sm" id="teamName" type="text"
                        placeholder="Enter Team Name" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Team Name
                    </div>
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm addTeamSubmit" type="submit">Submit</button>
                        <button class="btn btn-primary btn-sm addTeamUpdate d-none" type="submit"
                            onclick="teamUpdate(document.getElementById('teamID').value)">Update</button>
                    </div>
              </form>
        </div>
      </div>
    </div>
  </div>
 <!-- user type modal end-->
    <div class="card basic-data-table">
      <div class="card-header">
        <h5 class="card-title mb-0">Team Details</h5>
      </div>
      <div class="card-body">
        <table class="table bordered-table mb-0" id="team-table" data-page-length='10'>
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
    const viewteams = "{{route('team.viewTeams')}}";
    const addteam = "{{route('team.addTeam')}}";
    const getTeamData = "{{route('team.getTeamData')}}";
    const updateTeamData = "{{route('team.updateTeamData')}}";
    const statusUpdate = "{{route('team.statusUpdate')}}";
    const deleteTeamData = "{{route('team.deleteTeamData')}}";
</script>
  {{-----------external js files added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/admin/master/team.js')}}"></script>
@endsection