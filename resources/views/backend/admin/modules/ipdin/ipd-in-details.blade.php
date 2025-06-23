@extends('backend.admin.layouts.main')
@section('title')
    IPD details
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
     <input type="hidden" id="patient_Id" value="{{$patients[0]->id}}">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">IPD - In Patient Details<span class="{{$patients[0]->current_status == 'Admitted'?'badge text-sm fw-normal bg-danger-600 mx-1 text-white':'badge text-sm fw-normal bg-success-600 mx-1 text-white'}}">{{$patients[0]->current_status}}</span></h6>
         <div class="d-flex flex-wrap align-items-center gap-2">
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#moveToEmergencyModel" {{$patients[0]->current_status == 'Discharged'?'disabled':''}}> <i class="ri-hotel-bed-line"></i> Move to Emergency</button>
          <button type="button" class="btn btn-danger-600 fw-normal  btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#moveToIcuModel" {{$patients[0]->current_status == 'Discharged'?'disabled':''}}> <i class="ri-hotel-bed-line"></i> Move to ICU</button>
          <button type="button" class="btn btn-success-600 fw-normal  btn-sm d-flex align-items-center gap-2" {{$patients[0]->current_status == 'Discharged'?'disabled':''}}  onclick="patientDischarge({{$patients[0]->id}})"> <i class="ri-thumb-up-line"></i> Discharge</button>
          {{-- <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button> --}}
        </div>
        <!-- <div class="btns">
          <button class="btn btn-danger-600  btn-sm fw-medium"  data-bs-toggle="modal" data-bs-target="#in-patient-icu"><i class="ri-hotel-bed-line"></i> Move to ICU</button>
          <button class="btn btn-success-600  btn-sm fw-medium"  data-bs-toggle="modal" data-bs-target="#in-patient-discharge"><i class="ri-thumb-up-line"></i> Discharge</button>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
    </div>
      @php
      $curr_date = date('d/m/Y');
    @endphp
    <div class="card">
        <div class="card-body p-24">
            <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex w-100 " id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 active " id="Overview-tab" data-bs-toggle="pill" data-bs-target="#pills-Overview" type="button" role="tab" aria-controls="pills-Overview" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-Visits-tab" data-bs-toggle="pill" data-bs-target="#pills-Visits" type="button" role="tab" aria-controls="pills-Visits" aria-selected="false">Visits</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-Medication-tab" data-bs-toggle="pill" data-bs-target="#pills-Medication" type="button" role="tab" aria-controls="pills-Medication" aria-selected="false">Medication</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-lab-tab" data-bs-toggle="pill" data-bs-target="#pills-lab" type="button" role="tab" aria-controls="pills-lab" aria-selected="false">Lab Investigations</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-charges-tab" data-bs-toggle="pill" data-bs-target="#pills-charges" type="button" role="tab" aria-controls="pills-charges" aria-selected="false">Charges</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10  " id="pills-nurse-tab-in" data-bs-toggle="pill" data-bs-target="#pills-nurse-in" type="button" role="tab" aria-controls="pills-nurse-in" aria-selected="true">Nurse Note</button>
                  </li>
                 
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab" aria-controls="pills-history" aria-selected="false">Vital History</button>
                  </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-Overview" role="tabpanel" aria-labelledby="Overview-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-5 p-3 border-end">
                          <h6 class="text-md fw-medium border-bottom pb-8">{{$patients[0]->name}}</h6>
                            <div class=" pb-8">
                                 <table class="cutomer-details w-75 table-sm">
                                  <tr>
                                    <td class="fw-medium">Patient ID :</td>
                                    <td>{{$patients[0]->patient_id}}</td>
                                  </tr>
                                  <tr>
                                    <td class="fw-medium">Gender :</td>
                                    <td>{{$patients[0]->gender}}</td>
                                  </tr>
                                  <tr>
                                    <td class="fw-medium">DOB :</td>
                                    <td> {{$patients[0]->dob}}</td>
                                  </tr>
                                  <tr>
                                    <td class="fw-medium">Guardian Name :</td>
                                    <td> {{$patients[0]->guardian_name}}</td>
                                  </tr>
                                  <tr>
                                    <td class="fw-medium">phone :</td>
                                    <td>{{$patients[0]->mobile}}</td>
                                  </tr>
                                  <tr>
                                    <td class="fw-medium">Bar Code :</td>
                                    <td> <img src="{{asset('backend/uploads/images/barcode.jpg')}}" style="width: 100px;"></td>
                                  </tr>
                                 </table>
                            </div>
                            {{-- @php
                              $doctors =  \App\Models\User::where('id',$appointments[0]->doctor_id)->get();
                            @endphp --}}
                            <h6 class="text-md fw-medium mt-11 border-bottom pb-8">CONSULTANT DOCTOR</h6>
                            <div class="d-flex align-items-center">
                              <p class="mb-0 mx-1">Finding :</p> 
                              <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#ipd-add-finding">
                                <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Finding">
                                  <i class="ri-add-line"></i>
                                </div>
                              </button>
                            </div>
                        </div>
                        <div class="col-md-7 p-3">
                            <div class="mb-5">
                              <h6 class="text-md fw-medium">VISITS</h6>
                                <div class="table-responsive table-height">
                                  <table class="table striped-table border-0 mb-0 table-sm">
                                    <thead>
                                       <tr>
                                        <th class="fw-medium">Appointment Date</th>
                                        <th class="fw-medium">Consultant</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($visitsData as $visit)
                                      @php
                                        $doctor_name = app\Models\User::where('id',$visit->consult_doctor)->get(['name']);
                                      @endphp
                                        <tr>
                                        <td>{{$visit->appointment_date}}</td>
                                        <td>{{$doctor_name[0]->name}}</td>
                                       </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                            <div class="mb-5">
                              <h6 class="text-md fw-medium">MEDICATION</h6>
                                <div class="table-responsive table-height">
                                  <table class="table table striped-table border-0 mb-0 table-sm">
                                    <thead>
                                       <tr>
                                        <th scope="col" class="fw-medium">Date</th>
                                        <th scope="col" class="fw-medium">Medician Name</th>
                                        <th scope="col" class="fw-medium">Dose</th>
                                        <th scope="col" class="fw-medium">Remark</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($medicationData as $medication)
                                      {{-- @php
                                        $medicine_name = app\Models\Medicine::where('id',$medication->medicine_name_id)->get(['name']);
                                      @endphp --}}
                                        <tr>
                                          <td>{{$medication->created_at}}</td>
                                          {{-- <td>{{$medicine_name[0]->name}}</td> --}}
                                          <td>{{$medication->dose}}</td>
                                          <td>{{$medication->dose}}</td>
                                          <td>{{$medication->remarks}}</td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="mb-5">
                                <h6 class="text-md fw-medium">LAB INVESTIGATIONS</h6>
                                <div class="table-responsive table-height">
                                  <table class="table striped-table border-0 mb-0 table-sm">
                                    <thead>
                                       <tr>
                                        <th scope="col" class="fw-medium">Test</th>
                                        <th scope="col" class="fw-medium">Labs</th>
                                        <th scope="col" class="fw-medium">Sample coll</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                     @foreach ($labInvestigationData as $labInv)
                                        @php
                                            $labTestType = app\Models\TestType::where('id',$labInv->test_type_id)->get(['name']);
                                            $labTestName = app\Models\TestName::where('id',$labInv->test_name_id)->get(['name']);
                                        @endphp
                                        <tr>
                                            <td>{{ $labTestType[0]->name }}</td>
                                            <td>{{ $labTestName[0]->name }}</td>
                                            <td>{{ $labInv->created_at->toDateString() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
                 <div class="tab-pane fade" id="pills-Visits" role="tabpanel" aria-labelledby="pills-Visits-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                       <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Checkups</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ipd-new-checkup" onclick="resetVisit()"> <i class="ri-add-line"></i> New Checkup</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="ipd-in-visit-list" data-page-length='10'>
                          <thead>
                             <tr>
                              <th class="fw-medium ">Visit ID</th>
                              <th class="fw-medium ">Appointment Date</th>
                              <th class="fw-medium ">Consultant</th>
                              <th class="fw-medium ">Symptoms</th>
                              <th class="fw-medium ">Status</th>
                              <th class="fw-medium ">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                            {{-- data appended here using datatable from ipd-details-visit.js --}}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!--  opd-visit-view Start -->
              <div class="modal fade" id="ipd-in-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-visit-viewLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header p-11 bg-primary-500">
                      <h6 class="modal-title fw-normal text-md text-white" id="opd-visit-viewLabel">Patient Details</h6>
                      <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="ipdVisitViewDataAppend"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- opd visit view end -->
                <div class="tab-pane fade" id="pills-Medication" role="tabpanel" aria-labelledby="pills-Medication-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Medication</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#ipd-add-medication-dose" onclick="resetMedication()"> <i class="ri-add-line"></i> Add Medication Dose</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table mb-0 w-100" id="ipd-Med-medicineDoseList">
                          <thead>
                            <tr>
                              <th class="fw-medium">Visit ID</th>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Medician Category</th>
                              <th class="fw-medium">Medician Name</th>
                              <th class="fw-medium">Dose</th>
                              <th class="fw-medium">Remarks</th>
                              <th class="fw-medium">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            {{-- data appended using datatable --}}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="tab-pane fade" id="pills-lab" role="tabpanel" aria-labelledby="pills-lab-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Lab Investigations</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#ipd-add-lab" onclick="resetLabTest()"> <i class="ri-add-line"></i> Add Lab</button>
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="ipd-lab-reports-list" data-page-length='10'>
                                  <thead>
                                    <tr >
                                      <th scope="col" class="fw-medium">Sample Date</th>
                                      <th scope="col" class="fw-medium">Tast Type</th>
                                      <th scope="col" class="fw-medium">Test Name</th>
                                      <th scope="col" class="fw-medium">Repost Date</th>
                                      <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      {{-- <tr>
                                        <td>18/05/2025</td>
                                          <td ><span class="text-nowrap">Abodoman X-ray <br> (AX)</span> </td>
                                          <td>Pathology</td>
                                          <td>22/05/2025</td>
                                          <td class="text-nowrap">
                                            <button class="mx-1 bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#opd-lab-test-veiw">
                                              <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                            </button>
                                            <button  class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#opd-edit-lab" >
                                              <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </button>
                                            <button  class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                                              <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                          </td>
                                      </tr> --}}
                                    </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-charges" role="tabpanel" aria-labelledby="pills-charges-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 mb-11 d-flex justify-content-between align-items-center">
                          <h6 class="text-md fw-normal mb-0">Charges</h6>
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ipd-add-charges" onclick="resetCharge()"> <i class="ri-add-line"></i> Add Charges</button>
                        </div>
                      <div class="table-responsive">
                        <table class="table  striped-table w-100" id="ipd-charges-list">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Name</th>
                              <th class="fw-medium">Amount</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>05/04/2023</td>
                              <td>OPD</td>
                              <td>5545.00</td>
                              <td>
                                  <!-- <button class="mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                  </button> -->
                                  <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#opd-edit-charges">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                  </button>
                                  <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                  </button>
                                </td>
                             </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-nurse-in" role="tabpanel" aria-labelledby="pills-nurse-tab-in" tabindex="0">
                  <div class="row">
                      <div class="col-md-12 px-3">
                        <div class="mb-2 mb-11 d-flex justify-content-between align-items-center">
                          <h6 class="text-md fw-normal mb-0">Nurse Note</h6>
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ipd-nurse-note" onclick="resetNurse()"> <i class="ri-add-line"></i> Add Nurse Note</button>
                          <!-- <button class="btn btn-primary-600  btn-sm fw-medium" ><i class="ri-add-line"></i> Add Nurse Note</button> -->
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table w-100" id="ipdNurse-noteList">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Nurse</th>
                              <th class="fw-medium">Note</th>
                              <th class="fw-medium">Comment</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
             <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Vital History</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#ipd-add-vital-history" onclick="resetVital()"> <i class="ri-add-line"></i> Add Vital History</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table w-100" id="ipdVital-list">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Name</th>
                              <th class="fw-medium">Value</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             {{-- <tr>
                              <td>04/02/2025</td>
                              <td>150 ( 03:00 PM)</td>
                              <td>80kg ( 12:55 PM) </td>
                              <td>
                                <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#opd-edit-vital-history">
                                  <iconify-icon icon="lucide:edit"></iconify-icon>
                                </button>
                                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
                                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </button>
                              </td>
                             </tr> --}}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>  
    </div>
  </div>
<!-- modal finding start -->
<div class="modal fade" id="ipd-add-finding" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-findingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-add-findingLabel">Finding</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         finding Modal
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- modal finding end -->
 <!-- modal dr-log start -->
<div class="modal fade" id="ipd-dr-log" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-dr-logLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-medium text-lg" id="ipd-dr-logLabel">Dr. Log</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Dr. Log
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- modal dr-log end -->



<!-- lab-report start -->
<div class="modal fade" id="ipd-lab-report" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-lab-reportLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-lab-reportLabel">Report</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Report
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- lab-report end -->

<!-- Add add-lab Start -->
<div class="modal fade" id="ipd-add-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-add-labLabel">Add Test Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="ipdLab-form">
        <div class="modal-body">
         <div class="row gy-3">
          <div class="col-md-4">
            <input type="hidden" id="ipdLabID">
              <label class="form-label fw-medium" for="ipdLab-testType">Test Type</label> <sup class="text-danger">*</sup>
                 <select id="ipdLab-testType" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                       <option value="">Select</option>
                      @foreach ($testtypes as $testtype)
                       <option value="{{$testtype->id}}">{{$testtype->name}}</option>
                      @endforeach
                    </select>
                    <div class="ipdLab-testType_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium" for="ipdLab-testName">Test Name</label> <sup class="text-danger">*</sup>
                 <select id="ipdLab-testName" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                 
                        <option value="">Select</option>
                      @foreach ($testnames as $testname)
                        <option value="{{$testname->id}}">{{$testname->name}}</option>
                      @endforeach
                    </select>
                    <div class="ipdLab-testName_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Short Name <sup class="text-danger">*</sup></label>
                <input id="ipdLab-shortName" type="text" class="form-control form-control-sm" placeholder=" Short Name" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Amount</label>
                <input id="ipdLab-amount" type="number" class="form-control form-control-sm" placeholder=" Test Amount" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Method</label>
                <input id="ipdLab-method" type="text" class="form-control form-control-sm" placeholder=" Test Method">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Report Days</label>
                <input id="ipdLab-reportDays" type="number" class="form-control form-control-sm" placeholder=" Test Report Days">
            </div>
            <div class="col-md-12 mt-3">
                 <table class="pharmacy-purchase-bill-table table table-hover mb-11 add-test-feilds add-lab-table">
                   <thead>
                          <tr class="border-bottom">
                            <th class="text-nowrap text-neutral-700">Test Parameter Name</th>
                            <th class="text-nowrap text-neutral-700">Reference Range</th>
                            <th class="text-nowrap text-neutral-700">Unit</th>
                          </tr>
                  </thead>
                  <tbody>
                    <tr class="add-lab-fieldGroup">
                      <td>
                        <input id="ipdLab-testParameter" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="ipdLab-testRefRange" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="ipdLab-testUnit" type="text" class="form-control form-control-sm" >
                      </td>
                      {{-- <td>
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-lab-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td> --}}
                    </tr>
                    {{-- <tr class="appendMoreTestName">
                    </tr> --}}
                  </tbody>
                 </table>
                 {{-- <button class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center add-lab-addMore" onclick="addMoreTestName()">
                      <i class="ri-add-line"></i> Add
                  </button> --}}
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdLabSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdLabUpdate d-none" onclick="ipdLabUpdate(document.getElementById('ipdLabID').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Add add-lab end -->

<!-- Add prit Start -->
<div class="modal fade" id="ipd-print-detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-print-detailLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md" id="ipd-print-detailLabel">Print Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Print Details
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Add print end -->



<!-- Add nurse note Start -->
<div class="modal fade" id="ipd-nurse-note" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-nurse-noteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-nurse-noteLabel"> Add Nurse Note</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="ipdNurseNote-form">
        <div class="modal-body">
          <div class="row gy-3">
            <div class="col-md-12">
              <input type="hidden" id="ipdNurseNoteId">
                <label class="form-label fw-medium" for="ipdNurse-name">Nurse<sup class="text-danger">*</sup></label>
                    <select id="ipdNurse-name" class="form-select form-select-sm select2-cls" style="width: 100%;" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                        @foreach ($doctorData as $dData)
                        <option value="{{$dData->id}}">{{$dData->name}}</option>
                        @endforeach
                    </select>
                    <div class="ipdNurse-name_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-medium" for="ipdNurse-note">Note</label> <sup class="text-danger">*</sup>
                <input id="ipdNurse-note"  class="form-control" rows="1" placeholder="Note" oninput="validateField(this.id,'input')">
                <div class="ipdNurse-note_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-medium" for="ipdNurse-comment">Comment</label> <sup class="text-danger">*</sup>
                <textarea id="ipdNurse-comment"  class="form-control" rows="2" placeholder="Comment"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
           <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdNurseNoteSubmit"> <i class="ri-checkbox-circle-line" oninput="validateField(this.id,'input')"></i> Save</button>
           <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdNurseNoteUpdate d-none" onclick="ipdNurseNoteUpdate(document.getElementById('ipdNurseNoteId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add nurse note end -->

<!-- Add nurse note Start -->
<!-- <div class="modal fade" id="ipd-nurse-print" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-nurse-printLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-medium text-lg" id="ipd-nurse-printLabel"> Nurse note</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Nurse note
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div> -->
<!-- Add nurse note end -->
<!-- Move to ICU start -->
<div class="modal fade" id="in-patient-icu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="in-patient-icuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-medium text-lg" id="in-patient-icuLabel"> Move to ICU</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Move to ICU
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Move to ICU end -->
<!-- Move to  Discharge start -->
<div class="modal fade" id="in-patient-discharge" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="in-patient-dischargeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11">
        <h6 class="modal-title fw-medium text-lg" id="in-patient-dischargeLabel"> Discharge</h6>
        <button type="button" class="btn-close text-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Discharge
      </div>
      <div class="modal-footer pt-2 pb-3 border-top-0">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium">Save</button>
        <button type="button" class="btn btn-lilac-600  btn-sm fw-medium">Save & Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Move to  Discharge end -->

<!-- lab-test-veiw start -->
<div class="modal fade" id="ipd-lab-test-veiw" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-lab-test-veiwLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-lab-test-veiwLabel">Lab Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="ipdLabDataAppend"></div>
      </div>
    </div>
  </div>
</div>
<!-- lab-test-veiw end -->

<!-- Edit lab-detail Start -->
 <div class="modal fade" id="ipd-edit-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-edit-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-edit-labLabel">Edit Test Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="row gy-3">
          <div class="col-md-3">
              <label class="form-label fw-medium">Test Name <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control form-control-sm" placeholder=" Test Name">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Short Name <sup class="text-danger">*</sup></label>
                <input type="text" class="form-control form-control-sm" placeholder=" Short Name">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Test Type</label>
                <input type="text" class="form-control form-control-sm" placeholder=" Test Type">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Category Name <sup class="text-danger">*</sup></label>
                 <select class="form-select form-select-sm select2  ">
                      <option selected disabled>Select</option>
                      <option value="1">Clinical Microbiology</option>
                      <option value="2">Clinical Chemistry</option>
                      <option value="3">Hematology</option>
                      <option value="4">Molecular Diagnostics</option>
                      <option value="5">Reproductive Biology</option>
                      <option value="5">Electromagnetic Waves</option>
                  </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Sub Category</label>
                <input type="text" class="form-control form-control-sm" placeholder="Sub Category">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Method</label>
              <input type="text" class="form-control form-control-sm" placeholder="Method">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Report Days <sup class="text-danger">*</sup></label>
                <input type="numbr" class="form-control form-control-sm" placeholder="Report Days">
            </div>
            
            <div class="col-md-3">
              <label class="form-label fw-medium">Charge Category <sup class="text-danger">*</sup></label>
                <select class="form-select form-select-sm select2  ">
                      <option selected disabled>Select</option>
                      <option value="1">Surgical pathology</option>
                      <option value="2">Histopathology </option>
                      <option value="3">Cytopathology</option>
                      <option value="4">Forensic pathology</option>
                      <option value="5">Dermatopathology</option>
                  </select>
            </div>
            <div class="col-md-12 mt-3">
                 <table class="pharmacy-purchase-bill-table table table-hover mb-11 add-test-feilds edit-lab-table">
                   <thead>
                          <tr class="border-bottom">
                            <th class="text-nowrap text-neutral-700">Test Parameter Name <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Reference Range <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Unit <sup class="text-danger">*</sup></th>
                          </tr>
                  </thead>
                  <tbody>
                    <tr class="edit-lab-fieldGroup">
                      <td>
                        <select class="form-select form-select-sm select2  ">
                          <option selected disabled>Select</option>
                          <option value="1">RBC</option>
                          <option value="2">Liver function test</option>
                          <option value="3">TSH (Thyroid Stimulating Hormone)</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center edit-lab-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                    <tr class="edit-lab-fieldGroupCopy" style="display: none;">
                      <td>
                        <select class="form-select form-select-sm select2  ">
                          <option selected disabled>Select</option>
                          <option value="1">RBC</option>
                          <option value="2">Liver function test</option>
                          <option value="3">TSH (Thyroid Stimulating Hormone)</option>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center edit-lab-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                 </table>
                 <button class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center edit-lab-addMore">
                      <i class="ri-add-line"></i> Add
                  </button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-medium mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit lab-detail end -->

<!--  Add medication Start -->
 <div class="modal fade" id="ipd-add-medication-dose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-medication-doseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-medication-doseLabel">Add Medication Dose</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="ipdMed-form">
        <div class="modal-body">
          <div class="row gy-3">
             <div class="col-md-6">
              <input type="hidden" id="ipdMedDoseId">
                  <label class="form-label fw-medium" for="ipdMed-visitid">Visit ID</label> <sup class="text-danger">*</sup>
                    <select id="ipdMed-visitid" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                        @foreach ($visitsData as $visit)
                        <option value="{{$visit->id}}">MDVI0{{$visit->id}}</option>
                        @endforeach
                    </select>
                    <div class="ipdMed-visitid_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="ipdMed-medCategory">Medicine Category</label> <sup class="text-danger">*</sup>
                    <select id="ipdMed-medCategory" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" onchange="medicinelist(this.value)">
                        <option value="">Select</option>
                        @foreach ($medicineCategory as $medCategory)
                        <option value="{{$medCategory->id}}">{{$medCategory->name}}</option>
                        @endforeach
                    </select>
                    <div class="ipdMed-medCategory_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="ipdMed-medName">Medicine Name</label> <sup class="text-danger">*</sup>
                  <select id="ipdMed-medName" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                  </select>
                  <div class="ipdMed-medName_errorCls d-none"></div>
              </div>
                <div class="col-md-6">
                  <label class="form-label fw-medium" for="ipdMed-dose">Dose</label> <sup class="text-danger">*</sup>
                  <input id="ipdMed-dose" type="text" class="form-control form-control-sm" placeholder=" Add Medicine Doses" oninput="validateField(this.id,'select')">
                  <div class="ipdMed-dose_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium">Remarks</label>
                  <input id="ipdMed-remerks" type="text" class="form-control form-control-sm" placeholder=" Remarks">
              </div>
          </div>
        
        </div>
        <div class="modal-footer">
           <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdMedDoseSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdMedDoseUpdate d-none" onclick="ipdMedDoseUpdate(document.getElementById('ipdMedDoseId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add medication end -->
<!-- Add vital History Start -->
<div class="modal fade" id="ipd-add-vital-history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-vital-historyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-add-vital-historyLabel"> Add Vital</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="ipdVital-form">
      <div class="modal-body">
        <table class="pharmacy-purchase-bill-table table table-hover mb-11 add-test-feilds add-vital-table">
                   <thead>
                          <tr class="border-bottom">
                            <th class="text-nowrap text-neutral-700">Vital Name <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">	Vital Value <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Date <sup class="text-danger">*</sup></th>
                          </tr>
                  </thead>
                  <tbody>
                    <tr class="add-vital-fieldGroup">
                      <input type="hidden" id="ipdVitalId">
                      <td>
                        <input type="text" id="ipdVital-name" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="text" id="ipdVital-value" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="date" id="ipdVital-date" class="form-control form-control-sm" placeholder="DD-MM-YYYY" required>
                      </td>
                    </tr>
                  </tbody>
                 </table>
      </div>
       <div class="modal-footer">
        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdVItalSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdVItalUpdate d-none" onclick="ipdVItalUpdate(document.getElementById('ipdVitalId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Add vital History end -->
<!-- edit vital History Start -->
<div class="modal fade" id="ipd-edit-vital-history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-edit-vital-historyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-edit-vital-historyLabel"> Edit Vital</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
                   <thead>
                          <tr class="border-bottom">
                            <th class="text-nowrap text-neutral-700">Vital Name <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">	Vital Value <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Date <sup class="text-danger">*</sup></th>
                          </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                         <select class="form-select form-select-sm" disabled>
                            <option value="1">Select</option>
                            <option value="2" selected>Height (1 - 200 Centimeters)</option>
                            <option value="3" >Weight (1 - 100 Kg)</option>
                            <option value="4" >Pulse (70 - 100 Beats per)</option>
                            <option value="5">Temperature (95.8  -  99.3 Fahrenheit )</option>
                            <option value="6">BP (90/60  -  140/90 mmHg)</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm" placeholder="160">
                      </td>
                      <td>
                         <div class=" position-relative">
                              <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="05/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                     <tr>
                      <td>
                         <select class="form-select form-select-sm" disabled>
                            <option value="1">Select</option>
                            <option value="2" >Height (1 - 200 Centimeters)</option>
                            <option value="3" selected>Weight (1 - 100 Kg)</option>
                            <option value="4" >Pulse (70 - 100 Beats per)</option>
                            <option value="5">Temperature (95.8  -  99.3 Fahrenheit )</option>
                            <option value="6">BP (90/60  -  140/90 mmHg)</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm" placeholder="60">
                      </td>
                      <td>
                         <div class=" position-relative">
                              <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                     <tr>
                      <td>
                         <select class="form-select form-select-sm" disabled>
                            <option value="1">Select</option>
                            <option value="2" >Height (1 - 200 Centimeters)</option>
                            <option value="3" >Weight (1 - 100 Kg)</option>
                            <option value="4" selected>Pulse (70 - 100 Beats per)</option>
                            <option value="5">Temperature (95.8  -  99.3 Fahrenheit )</option>
                            <option value="6">BP (90/60  -  140/90 mmHg)</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm" placeholder="80">
                      </td>
                      <td>
                         <div class=" position-relative">
                              <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                         <select class="form-select form-select-sm" disabled>
                            <option value="1">Select</option>
                            <option value="2" >Height (1 - 200 Centimeters)</option>
                            <option value="3" >Weight (1 - 100 Kg)</option>
                            <option value="4" >Pulse (70 - 100 Beats per)</option>
                            <option value="5" selected>Temperature (95.8  -  99.3 Fahrenheit )</option>
                            <option value="6">BP (90/60  -  140/90 mmHg)</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm" placeholder="96">
                      </td>
                      <td>
                         <div class=" position-relative">
                              <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                         <select class="form-select form-select-sm" disabled>
                            <option value="1">Select</option>
                            <option value="2" >Height (1 - 200 Centimeters)</option>
                            <option value="3" >Weight (1 - 100 Kg)</option>
                            <option value="4" >Pulse (70 - 100 Beats per)</option>
                            <option value="5" >Temperature (95.8  -  99.3 Fahrenheit )</option>
                            <option value="6" selected>BP (90/60  -  140/90 mmHg)</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control form-control-sm" placeholder="120/20">
                      </td>
                      <td>
                         <div class=" position-relative">
                              <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                 </table>
         <!-- <div class="row">
           <div class="col-md-4">
            <label class="form-label fw-medium ">Vital Name</label>
            <select class="form-select form-select-sm" >
                <option value="1">Select</option>
                <option value="2" selected>Height (1 - 200 Centimeters)</option>
                <option value="3" >Weight (1 - 100 Kg)</option>
                <option value="4" >Pulse (70 - 100 Beats per)</option>
                <option value="5">Temperature (95.8  -  99.3 Fahrenheit )</option>
                <option value="6">BP (90/60  -  140/90 mmHg)</option>
            </select>
           </div>
           <div class="col-md-4 mb-3">
            <label class="form-label fw-medium ">Vital Value</label>
            <input type="number" class="form-control form-control-sm" placeholder="160">
           </div>
           <div class="col-md-4 mb-3">
             <label class="form-label fw-medium ">Vital Date</label>
             <div class=" position-relative">
                <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="05/22/2025 03:55 PM" readonly="readonly">
                <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
            </div>
           </div>
         </div> -->
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- edit vital History end -->

<!-- edit-nurse Start -->
<div class="modal fade" id="ipd-edit-nurse" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-edit-nurseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-edit-nurseLabel"> Edit Nurse</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row gy-3">
          <div class="col-md-6">
            <label class="form-label fw-medium">Date<sup class="text-danger">*</sup></label>
            <div class=" position-relative">
                <input class="form-control form-control-sm radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="05/22/2025 03:55 PM" >
                <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
            </div>
          </div>
           <div class="col-md-6">
              <label class="form-label fw-medium">Nurse<sup class="text-danger">*</sup></label>
                  <select class="form-select form-select-sm select2  ">
                      <option selected disabled>Select</option>
                      <option>April Clinton (9020)</option>
                      <option>Natasha  Romanoff (9010)</option>
                  </select>
           </div>
           <div class="col-md-12">
               <label class="form-label fw-medium">Note<sup class="text-danger">*</sup></label>
              <textarea name="note"  class="form-control" rows="1" placeholder="Note"></textarea>
           </div>
           <div class="col-md-12">
               <label class="form-label fw-medium">Comment<sup class="text-danger">*</sup></label>
              <textarea name="note"  class="form-control" rows="2" placeholder="Comment"></textarea>
           </div>
        </div>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- edit-nurse History end -->

<!-- Add charges Start -->
<div class="modal fade" id="ipd-add-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-add-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-add-chargesLabel"> Add Charges</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="ipdCharge-form">
        <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                <input type="hidden" id="ipdChargeId">
                <label class="form-label fw-medium" for="ipdCharge-name">Name</label> <sup class="text-danger">*</sup>
                  <input id="ipdCharge-name" type="text" class="form-control form-control-sm" placeholder="Charge Name" oninput="validateField(this.id,'input')">
                  <div class="ipdCharge-name_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium" for="ipdCharge-amount">Amount</label> <sup class="text-danger">*</sup>
                  <input id="ipdCharge-amount" type="number" class="form-control form-control-sm" placeholder="Charge Amount" oninput="validateField(this.id,'amount')">
                  <div class="ipdCharge-amount_errorCls d-none"></div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdChargeSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdChargeUpdate d-none" onclick="ipdChargeUpdate(document.getElementById('ipdChargeId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!-- Add charges History end -->

<!--  opd new checkup Start -->
 <div class="modal fade" id="ipd-new-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-new-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-new-checkupLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="ipdVisit-modelForm">
      <div class="modal-body">
        <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row gy-3">
             <div class="col-md-12">
              <input type="hidden" id="ipdVisitId">
                <table class="table table-borderless pharmacy-bill-detail-table w-75 ">
                     <tbody>
                      <input type="hidden" id="ipdVisit-patientId" value="{{$patients[0]->id}}">
                      <tr>
                       <th class="fw-medium">Patient Name</th>
                       <td class="text-neutral-700">{{$patients[0]->name}}</td>
                     </tr>
                     <tr>
                       <th class="fw-medium">Gender</th>
                       <td class="text-neutral-700">{{$patients[0]->gender}}</td>
                     </tr>
                  </tbody></table>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium" for="ipdVisit-symptoms">Symptoms</label>
                <input type="text" id="ipdVisit-symptoms" class="form-control form-control-sm" placeholder="Symptoms" value="" oninput="validateField(this.id,'input')">
                <div class="ipdVisit-symptoms_errorCls d-none"></div>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium" for="ipdVisit-previousMedIssue">Previous Medical Issue</label>
               <textarea id="ipdVisit-previousMedIssue" class="form-control " rows="1" placeholder="Previous Medical Issue" oninput="validateField(this.id,'input')" value=""></textarea>
                <div class="ipdVisit-previousMedIssue_errorCls d-none"></div>
             </div>
             <div class="col-md-12">
               <label class="form-label fw-medium">Note</label>
               <textarea  id="ipdVisit-note" class="form-control " rows="2" placeholder="Note" value=""></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-admissionDate">Appointment Date</label>
              <div class=" position-relative">
                    <input id="ipdVisit-admissionDate" class="form-control radius-8 bg-base opd-add-admission-date flatpickr-input active" type="text" value="{{ $curr_date}}" readonly="readonly">
                </div>
            </div>
            <div class="col-md-6">
               <label class="form-label fw-medium" for="ipdVisit-oldPatient">Old Patient</label>
              <select id="ipdVisit-oldPatient" class="form-select form-select-sm select2" oninput="validateField(this.id,'select')">
                 <option value="">Select</option>
                 <option value="1">Yes</option>
                 <option value="0">No</option>
              </select>
               <div class="ipdVisit-oldPatient_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-consultDoctor"> Consultant Doctor</label> <sup class="text-danger">*</sup>
               <select id="ipdVisit-consultDoctor" class="form-select form-select-sm select2" oninput="validateField(this.id,'select')">
                      <option value="">Select</option>
                      @foreach ($doctorData as $doctorName)
                      <option value="{{$doctorName->id}}">{{$doctorName->name}}</option>
                      @endforeach
              </select>
               <div class="ipdVisit-consultDoctor_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-charge"> Applied Charge</label>(₹) <sup class="text-danger">*</sup>
               <input id="ipdVisit-charge" type="number" class="form-control form-control-sm" placeholder="Applied Charge" value="" oninput="validateField(this.id,'amount');calculateAmount()">
                <div class="ipdVisit-charge_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-discount"> Discount</label>% <sup class="text-danger">*</sup>
               <input id="ipdVisit-discount" type="number" class="form-control form-control-sm" placeholder="Discount" value="" oninput="calculateAmount()">
                <div class="ipdVisit-discount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-tax"> Tax</label>% <sup class="text-danger">*</sup>
               <input id="ipdVisit-tax" type="number" class="form-control form-control-sm" placeholder="Discount" value=""  oninput="calculateAmount()">
                <div class="ipdVisit-tax_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="ipdVisit-amount"> Amount</label>(₹) <sup class="text-danger">*</sup>
               <input id="ipdVisit-amount" type="number" class="form-control form-control-sm" placeholder="Amount" value="" readonly>
                <div class="ipdVisit-amount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium" for="ipdVisit-paymentMode"> Payment Mode</label> <sup class="text-danger">*</sup>
               <select id="ipdVisit-paymentMode" class="form-select form-select-sm" oninput="validateField(this.id,'select')">
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="cheque">Cheque</option>
                <option value="other">Other</option>
              </select>
               <div class="ipdVisit-paymentMode_errorCls d-none"></div>
            </div>
            <div class="col-md-6 mb-3" style="display: none1;" id="upi-reference-no">
              <label class="form-label fw-medium ">Reference Number</label>
              <input id="ipdVisit-refNum" type="number" class="form-control form-control-sm" placeholder=" Enter payment reference number">
            </div>
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium" for="ipdVisit-paidAmount">Pay Amount</label> <sup class="text-danger">*</sup>
               <input id="ipdVisit-paidAmount" type="number" class="form-control form-control-sm" placeholder="Paid Amount" oninput="validateField(this.id,'amount')">
                <div class="ipdVisit-paidAmount_errorCls d-none"></div>
            </div>
            <!-- <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Live Consultation</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div> -->
          </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdVisitSubmit"><i class="ri-checkbox-circle-line"></i> Submit</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 ipdVisitUpdate d-none" onclick="ipdVisitUpdate(document.getElementById('ipdVisitId').value)"><i class="ri-checkbox-circle-line"></i> Update</button>
      </div>
    </div>
  </form>
  </div>
</div>
<!-- opd new checkup end -->
 <!--Alert modal start -->
  <div class="modal fade" id="moveToEmergencyModel" tabindex="-1" role="dialog" aria-labelledby="moveToEmergencyModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Bed Number</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <form action="" id="ipd-emergencyRoomForm" class="needs-validation" novalidate="">
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Bed Number</label>
                    {{-- <input type="hidden" id=opd-ipdRoom"> --}}
                   <select class="form-control form-control-sm" name="ipd-emergencyRoom" id="ipd-emergencyRoom" required>
                        <option value="">Select Emergency Bed Number</option>
                        @foreach ($emergencyAvailBeds as $emergencyBed)
                        <option value="{{$emergencyBed->id}}">{{$emergencyBed->bed_no}}</option>
                        @endforeach
                    </select>   
                    <div class="invalid-feedback">
                            Select Emergency Bed
                        </div> 
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm " type="submit">Submit</button>
                    </div>
           </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Alert modal end-->
 <!--Alert ICU modal start -->
  <div class="modal fade" id="moveToIcuModel" tabindex="-1" role="dialog" aria-labelledby="moveToIcuModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Bed Number</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <form action="" id="ipd-icuBedForm" class="needs-validation" novalidate="">
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Bed Number</label>
                    {{-- <input type="hidden" id=opd-ipdRoom"> --}}
                   <select class="form-control form-control-sm" name="ipd-icuBed" id="ipd-icuBed" required>
                        <option value="">Select ICU Bed Number</option>
                        @foreach ($icuAvailBeds as $icubed)
                        <option value="{{$icubed->id}}">{{$icubed->bed_no}}</option>
                        @endforeach
                    </select>   
                    <div class="invalid-feedback">
                            Select ICU Bed
                        </div> 
                </div>
                </div>
                </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger btn-sm" type="button"
                            data-bs-dismiss="modal" onclick="resetmodel()">Cancel</button>
                        <button class="btn btn-primary btn-sm " type="submit">Submit</button>
                    </div>
           </form>
        </div>
      </div>
    </div>
  </div>
 <!-- Alert ICU modal end-->
<!--  opd edit checkup Start -->
 <div class="modal fade" id="ipd-edit-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-edit-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-edit-checkupLabel">Edit Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row gy-3">
             <div class="col-md-12">
                <table class="table table-borderless pharmacy-bill-detail-table w-75 ">
                     <tbody>
                      <tr>
                       <th class="fw-medium">Patient Name</th>
                       <td class="text-neutral-700">Arun Kumar (1234)</td>
                     </tr>
                     <tr>
                       <th class="fw-medium">Gender</th>
                       <td class="text-neutral-700">Male</td>
                     </tr>
                     <tr>
                      <th class="fw-medium">Symptoms</th>
                      <td class="text-neutral-700"> Cold</td>
                     </tr>
                  </tbody></table>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Type</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Cough</option>
              </select>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Title</label>
               <select class="form-select form-select-sm  " >
                 <option selected>Select</option>
              </select>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Symptoms Description</label>
               <textarea  class="form-control " rows="1" placeholder="Symptoms Description"></textarea>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium">Previous Medical Issue</label>
               <textarea  class="form-control " rows="1" placeholder="Previous Medical Issue"></textarea>
             </div>
             <div class="col-md-12">
               <label class="form-label fw-medium">Note</label>
               <textarea  class="form-control " rows="2" placeholder="Note"></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Admission Date <sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control form-control-sm radius-8 bg-base opd-add-admission-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Case</label>
              <input type="text" class="form-control form-control-sm" placeholder="Case">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Casualty</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <div class="col-md-6">
               <label class="form-label fw-medium">Old Patient</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <!-- <div class="col-md-6">
             <label class="form-label fw-medium"> Credit Limit (₹) <sup class="text-danger">*</sup></label>
              <input type="number" class="form-control form-control-sm" placeholder="200000">
            </div> -->
            <div class="col-md-6">
              <label class="form-label fw-medium">Reference</label>
              <input type="text" class="form-control form-control-sm" placeholder="Reference">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Consultant Doctor <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Sunil Kumar (1234)</option>
                 <option value="1">Manoj Gupta (2224)</option>
                 <option value="1">Arjun Kumar (2234)</option>
                 <option value="1">Suraj Kumar (9234)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Charge Category <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">OPD Consultation Fees</option>
                 <option value="1">OPD Service</option>
                 <option value="1">OPD Insurance</option>
                 <option value="1">Blood pressure check</option>
                 <option value="1">Eye check</option>
                 <option value="1">Cholesterol level check</option>
                 <option value="1">Other Charges</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Charge <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Applied Charge (₹) <sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Applied Charge">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Discount %<sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Discount ">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Tax %<sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Discount ">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium"> Amount (₹) <sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Amount ">
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium"> Payment Mode</label>
               <select class="form-select form-select-sm" id="payment-method">
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="cheque">Cheque</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6" style="display: none;" id="upi">
              <label class="form-label fw-medium ">UPI</label>
              <select class="form-select form-select-sm" id="upi-number">
                <option selected="">Select</option>
                <option value="upi-reference-number">Google Pay</option>
                <option value="upi-reference-number">Phone Pay</option>
                <option value="upi-reference-number">Airtel Pay</option>
              </select> 
            </div>
            
            <div class="col-md-6" style="display: none;" id="card">
              <label class="form-label fw-medium ">Card Number</label>
              <input type="number" class="form-control form-control-sm" placeholder="Enter Card Number">
            </div>
            <div class="col-md-6 cheque" style="display: none;">
              <label class="form-label fw-medium ">Cheque Number</label>
              <input type="number" class="form-control form-control-sm" placeholder="Enter Cheque Number">
            </div>
            
            <div class="col-md-6 mb-3" style="display: none;" id="upi-reference-no">
              <label class="form-label fw-medium ">Reference Number</label>
              <input type="number" class="form-control form-control-sm" placeholder=" Enter reference number">
            </div>
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium">Paid Amount <sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Paid Amount ">
            </div>
            <!-- <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Live Consultation</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div> -->
          </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- opd edit checkup end -->

<!--  opd-visit-view Start -->
 <div class="modal fade" id="ipd-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ipd-visit-viewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="ipd-visit-viewLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
             <div class="col-md-12">
                <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr>
                    <th class="fw-medium">Case ID</th>
                    <td>1234</td>
                    <th class="fw-medium">IPD No</th>
                    <td>IPD4125</td>
                </tr>
                 <tr>
                    <th class="fw-medium">Recheckup ID</th>
                    <td>RE123456</td>
                    <th class="fw-medium">Appointment Date</th>
                    <td>20/05/2025</td>
                </tr>
                <tr>
                   <th class="fw-medium">Patient Name</th>
                    <td>Sunil Kumar</td>
                    <th class="fw-medium">Guardian Name</th>
                    <td>Gurav Bhatiya</td>
                </tr>
                <tr>
                   <th class="fw-medium">Gender</th>
                    <td>Male</td>
                    <th class="fw-medium">Marital Status</th>
                    <td>Singal</td>
                </tr>
                <tr>           
                   <th class="fw-medium">Age</th>
                    <td>8 Year 5 Month 17 Days (As Of Date )</td>
                    <th class="fw-medium">Blood Group</th>
                    <td>B+</td>
                </tr>
                <tr>     
                    <th class="fw-medium">Phone</th>
                    <td>+91 2233 445 566</td>
                    <th class="fw-medium">Email</th>
                    <td>sunil@gmail.com</td>
                </tr>
                
                <tr>    
                     <th class="fw-medium">Known Allergies</th>
                    <td>unknown</td>    
                    <th class="fw-medium">Case</th>
                    <td>2200</td>  
                </tr>
                  <tr>         
                    <th class="fw-medium">Consultant Doctor</th>
                    <td>Mohan Kumar Gupta (9008)</td>  
                    <th class="fw-medium">Reference</th>
                    <td>Anil kumar</td> 
                </tr>
                <tr>
                  <th class="fw-medium">Symptoms</th>
                  <td class="text-sm">Cramps and injuries </td>
                </tr>
            </tbody>
            </table>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- opd visit view end -->

@endsection
@section('extra-js')
<script>
  // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y",
        });
    }
    getDatePicker('#ipdVital-date'); 
     getDatePicker('#ipdVisit-admissionDate'); 
    // Flat pickr or date picker js 
    $('#ipd-add-medication-dose').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#ipd-add-medication-dose')
      });
    });
    $('#ipd-add-lab').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#ipd-add-lab')
      });
    });
    $('#ipd-nurse-note').on('shown.bs.modal', function () {
      $('.select2-cls').select2({
          dropdownParent: $('#ipd-nurse-note')
      });
    });
  const ipdVisitMedicineName = "{{route('common.getMedicineName')}}";
  // const patientDischargeStatus = "{{route('ipd.patientDischargeStatus')}}";
  const moveToEmergencyStatus = "{{route('ipd.moveToEmergencyStatus')}}";
  const moveToIcuStatus = "{{route('ipd.moveToIcuStatus')}}";
  const ipdVisitSubmit = "{{route('ipd-visit.ipdVisitSubmit')}}";
  const viewIpdVisit = "{{route('ipd-visit.viewIpdVisit')}}";
  const getIpdVisitData = "{{route('ipd-visit.getIpdVisitData')}}";
  const getIpdVisitDetails = "{{route('ipd-visit.getIpdVisitData')}}";
  const ipdVisitDataUpdate = "{{route('ipd-visit.ipdVisitDataUpdate')}}";
  const ipdVisitDataDelete = "{{route('ipd-visit.ipdVisitDataDelete')}}";

  const ipdMedDataAdd = "{{route('ipd-med.ipdMedDataAdd')}}";
  const viewIpdMedDose = "{{route('ipd-med.viewIpdMedDose')}}";
  const getIpdMedDoseDetails = "{{route('ipd-med.getIpdMedDoseDetails')}}";
  const ipdMedDataUpdate = "{{route('ipd-med.ipdMedDataUpdate')}}";
  const ipdMedDoseDataDelete = "{{route('ipd-med.ipdMedDoseDataDelete')}}";

  const ipdLabSubmit = "{{route('ipd-lab.ipdLabSubmit')}}";
  const viewIpdLabDetails = "{{route('ipd-lab.viewIpdLabDetails')}}";
  const getIpdLabData = "{{route('ipd-lab.getIpdLabData')}}";
  const getIpdLabDetails = "{{route('ipd-lab.getIpdLabDetails')}}";
  const ipdLabUpdateData = "{{route('ipd-lab.ipdLabUpdateData')}}";
  const ipdLabDataDelete = "{{route('ipd-lab.ipdLabDataDelete')}}";

  const ipdChargeSubmit = "{{route('ipd-charge.ipdChargeSubmit')}}";
  const viewIpdCharge = "{{route('ipd-charge.viewIpdCharge')}}";
  const getIpdChargeData = "{{route('ipd-charge.getIpdChargeData')}}";
  const ipdChargeDataUpdate = "{{route('ipd-charge.ipdChargeDataUpdate')}}";
  const ipdChargeDataDelete = "{{route('ipd-charge.ipdChargeDataDelete')}}";

  const ipdVItalSubmit = "{{route('ipd-vital.ipdVItalSubmit')}}";
  const viewIpdVital = "{{route('ipd-vital.viewIpdVital')}}";
  const getIpdVitalData = "{{route('ipd-vital.getIpdVitalData')}}";
  const ipdVItalDataUpdate = "{{route('ipd-vital.ipdVItalDataUpdate')}}";
  const ipdVitalDataDelete = "{{route('ipd-vital.ipdVitalDataDelete')}}";

  const ipdNurseNoteSubmit = "{{route('ipd-nurse.ipdNurseNoteSubmit')}}";
  const viewIpdNurseNote = "{{route('ipd-nurse.viewIpdNurseNote')}}";
  const getIpdNurseNoteData = "{{route('ipd-nurse.getIpdNurseNoteData')}}";
  const ipdNurseNoteDataUpdate = "{{route('ipd-nurse.ipdNurseNoteDataUpdate')}}";
  const ipdNurseDataDelete = "{{route('ipd-nurse.ipdNurseDataDelete')}}";
</script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-visit.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-medication.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-lab.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-charge.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-vital.js')}}"></script>
<script src="{{asset('backend/assets/js/custom/admin/ipdin/ipdin-details/ipdin-details-nurse.js')}}"></script>
@endsection