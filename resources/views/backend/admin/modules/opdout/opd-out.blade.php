@extends('backend.admin.layouts.main')
@section('title')
    OPD - Out Patient
@endsection
@section('extra-css')
<style>
    .form-select.form-select-sm{
        width:auto !important;
    }
</style>
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">OPD - Out Patient</h6>
    </div>
    <div class="d-flex align-items-center justify-content-between my-3">
        <div class="d-flex justify-content-between w-30">
            <select id="opdoutDoctorId" class="select2-class form-select" onchange="getListFilter()">
                <option value="">👨‍⚕️ Select Doctor</option>
                @foreach ($users as $user)
                <option value="{{$user->id}}">👤Dr.  {{$user->firstname}} {{$user->lastname}}</option>
                @endforeach
            </select>

            <select id="opdoutRoomNum" class="select2-class form-select" onchange="getListFilter()">
                <option value="">🏥 Select Room</option>
                @foreach ($users as $user)
                <option value="{{$user->room_number}}">🚪 Room {{$user->room_number}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="opd-out-details"></div>
</div>
@endsection
<script>
    const viewOpdOut = "{{route('opd-out.viewOpdOut')}}";
    const getPatientUsingDoctor = "{{route('opd-out.getPatientUsingDoctor')}}";
</script>
@section('extra-js')
 {{-----------external js files added for page functions------------}}
 <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout.js')}}"></script>
 <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-patient.js')}}"></script>
 <script>
$(document).ready(function() {
    $('.select2-class').select2({
    });
});
</script>
@endsection