@extends('backend.admin.layouts.main')
@section('main-container')
  <div class="dashboard-main-body">
      <div class="patientDetailsData">
        {{-- patient details appended here from opdout.js --}}
      </div>
  </div>
  @endsection
@section('extra-js')
 {{-----------external js files added for page functions------------}}
 <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-patient.js')}}"></script>

@endsection