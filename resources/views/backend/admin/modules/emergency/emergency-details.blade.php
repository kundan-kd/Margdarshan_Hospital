@extends('backend.admin.layouts.main')
@section('title')
    Emergency details
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
        <h6 class="fw-normal mb-0">Emergency Details<span class="{{$patients[0]->current_status == 'Admitted'?'badge text-sm fw-normal bg-danger-600 mx-1 text-white':'badge text-sm fw-normal bg-success-600 mx-1 text-white'}}">{{$patients[0]->current_status}}</span></h6>
        <div class="d-flex flex-wrap align-items-center gap-2">
          @can('Emergency Move To IPD')
            <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#moveToIpdModel" {{$patients[0]->current_status == 'Discharged'?'disabled':''}} onclick="#"> <i class="ri-stethoscope-line"></i> Move to IPD</button>
          @endcan
          @can('Emergency Move To ICU')
            <button type="button" class="btn btn-danger-600 fw-normal  btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#moveToIcuModel" {{$patients[0]->current_status == 'Discharged'?'disabled':''}} onclick="#"> <i class="ri-stethoscope-line"></i> Move to ICU</button>
          @endcan
          {{-- <button class="btn btn-danger-600  btn-sm fw-normal d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#emergency-icu"><i class="ri-hotel-bed-line"></i> Move to ICU</button> --}}
          {{-- <button type="button" class="btn btn-success-600 fw-normal  btn-sm d-flex align-items-center gap-2" {{$patients[0]->current_status == 'Discharged'?'disabled':''}}  onclick="patientDischargeE({{$patients[0]->id}})"> <i class="ri-thumb-up-line"></i> Discharge</button> --}}
          @can('Emergency Discharge')
            <button type="button" class="btn btn-success-600 fw-normal  btn-sm d-flex align-items-center gap-2" {{$patients[0]->current_status == 'Discharged'?'disabled':''}} data-bs-toggle="modal" data-bs-target="#emergencyDischargeModel" onclick="patientDischargeE({{$patients[0]->id}})"> <i class="ri-thumb-up-line"></i> Discharge</button>
          @endcan
          {{-- <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button> --}}
        </div>
        <!-- <div class="btns">
            <button class="btn btn-primary-600  btn-sm fw-normal " data-bs-toggle="modal" data-bs-target="#emergency-emergency"><i class="ri-stethoscope-line"></i> Move to emergency</button>
            <button class="btn btn-danger-600  btn-sm fw-normal " data-bs-toggle="modal" data-bs-target="#emergency-icu"><i class="ri-hotel-bed-line"></i> Move to ICU</button>
            <button class="btn btn-success-600  btn-sm fw-normal" data-bs-toggle="modal" data-bs-target="#emergency-discharge"><i class="ri-thumb-up-line"></i> Discharge</button>
            <button class="btn btn-warning-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Export</button> 
        </div> -->
    </div>
    @php
      $curr_date = date('d/m/Y');
    @endphp
    <div class="card">
        <div class="card-body p-24">
            <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex w-100 " id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 active " id="Overview-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-Overview-emergency" type="button" role="tab" aria-controls="pills-Overview-emergency" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-Visits-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-Visits-emergency" type="button" role="tab" aria-controls="pills-Visits-emergency" aria-selected="false">Visits</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-Medication-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-Medication-emergency" type="button" role="tab" aria-controls="pills-Medication-emergency" aria-selected="false">Medication</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 " id="pills-lab-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-lab-emergency" type="button" role="tab" aria-controls="pills-lab-emergency" aria-selected="false">Lab Investigations</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-charges-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-charges-emergency" type="button" role="tab" aria-controls="pills-charges-emergency" aria-selected="false">Charges</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-timeline-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-nurse-emergency" type="button" role="tab" aria-controls="pills-nurse-emergency" aria-selected="false">Nurse Note</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-history-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-history-emergency" type="button" role="tab" aria-controls="pills-history-emergency" aria-selected="false">Vital History</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-bills-tab-emergency" data-bs-toggle="pill" data-bs-target="#pills-bills-emergency" type="button" role="tab" aria-controls="pills-history-emergency" aria-selected="false">Bills</button>
                  </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-Overview-emergency" role="tabpanel" aria-labelledby="Overview-tab-emergency" tabindex="0">
                    <div class="row">
                        <div class="col-md-5 p-3 border-end">
                          <h6 class="text-md fw-normal border-bottom pb-8">{{$patients[0]->name}}</h6>
                            <div class="border-bottom pb-8">
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
                            @php
                              // $doctors =  \App\Models\User::where('id',$appointments[0]->doctor_id)->get();
                            @endphp
                            <h6 class="text-md fw-normal mt-11 ">CONSULTANT DOCTOR</h6>
                            <div class="d-flex align-items-center">
                              <p class="mb-0 mx-1">Finding :</p> 
                              <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#emergency-finding">
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
                                        $doctor_name = app\Models\User::where('id',$visit->consult_doctor ??'')->get(['name']);
                                      @endphp
                                        <tr>
                                        <td>{{$visit->appointment_date}}</td>
                                        <td>{{$doctor_name[0]->name }}</td>
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
                                        {{-- <th scope="col" class="fw-medium">Medician Name</th> --}}
                                        <th scope="col" class="fw-medium">Dose</th>
                                        <th scope="col" class="fw-medium">Time</th>
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
                                        {{-- <th scope="col" class="fw-medium">Expected Date</th> --}}
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
                <div class="tab-pane fade" id="pills-Visits-emergency" role="tabpanel" aria-labelledby="pills-Visits-tab-emergency" tabindex="0">
                   <div class="row">
                    <div class="col-md-12 px-3">
                       <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Checkups</h6>
                        @can('Emergency Visit Add')
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-new-checkup"> <i class="ri-add-line"></i> New Checkup</button>
                        @endcan
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#emergency-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="emergency-visit-list" data-page-length='10'>
                          <thead>
                             <tr>
                              <th class="fw-medium ">Emergency Id</th>
                              <th class="fw-medium ">Appointment Date</th>
                              <th class="fw-medium ">Consultant</th>
                              <th class="fw-medium ">Reference</th>
                              <th class="fw-medium ">Symptoms</th>
                              <th class="fw-medium ">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>EM4456879</td>
                              <td>05/23/2025 12:53 PM</td>
                              <td>Dr. Niraj Kumar</td>
                              <td>Sunil Kumar</td>
                              <td>Cold</td>
                              <td>
                                  <button class="mx-1 bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#emergency-visit-view">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                  </button>
                                  <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle"  data-bs-toggle="modal" data-bs-target="#emergency-edit-checkup">
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
                <div class="tab-pane fade" id="pills-Medication-emergency" role="tabpanel" aria-labelledby="pills-Medication-tab-emergency" tabindex="0">
                  <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Medication</h6>
                        @can('Emergency Medication Add')
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-add-medication-dose" onclick="getVisitId(document.getElementById('patient_Id').value)"> <i class="ri-add-line"></i> Add Medication Dose</button>
                        @endcan
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#emergency-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table w-100" id="emergency-Med-medicineDoseList">
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
                             
                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-lab-emergency" role="tabpanel" aria-labelledby="pills-lab-tab-emergency" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Lab Investigations</h6>
                        @can('Emergency Lab Add')
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#emergency-add-lab"> <i class="ri-add-line"></i> Add Lab</button>
                        @endcan
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="emergancy-lab-reports-list" data-page-length='10'>
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

                                    </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-charges-emergency" role="tabpanel" aria-labelledby="pills-charges-tab-emergency" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 mb-11 d-flex justify-content-between align-items-center">
                          <h6 class="text-md fw-normal mb-0">Charges</h6>
                          @can('Emergency Charge Add')
                            <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-add-charges"> <i class="ri-add-line"></i> Add Charges</button>
                          @endcan
                        </div>
                      <div class="table-responsive">
                        <table class="table  striped-table w-100" id="emergancy-charges-list">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Name</th>
                              <th class="fw-medium">Amount</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-nurse-emergency" role="tabpanel" aria-labelledby="pills-nurse-tab-emergency" tabindex="0">
                    <div class="row">
                      <div class="col-md-12 px-3">
                        <div class="mb-2 mb-11 d-flex justify-content-between align-items-center">
                          <h6 class="text-md fw-normal mb-0">Nurse Note</h6>
                          @can('Emergency Nurse Note Add')
                            <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-nurse-note"> <i class="ri-add-line"></i> Add Nurse Note</button>
                          @endcan
                          <!-- <button class="btn btn-primary-600  btn-sm fw-medium" ><i class="ri-add-line"></i> Add Nurse Note</button> -->
                        </div>
                      </div>
                        <div class="table-responsive">
                        <table class="table striped-table w-100" id="emergencyNurse-noteList">
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
                <div class="tab-pane fade" id="pills-history-emergency" role="tabpanel" aria-labelledby="pills-history-tab-emergency" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Vital History</h6>
                        @can('Emergency Vital Add')
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#emergency-add-vital-history"> <i class="ri-add-line"></i> Add Vital History</button>
                        @endcan
                      </div>
                            <div class="table-responsive">
                        <table class="table striped-table w-100" id="emergencyVital-list">
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
                <div class="tab-pane fade" id="pills-bills-emergency" role="tabpanel" aria-labelledby="pills-bills-tab-emergency" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Bills Created History</h6>
                      </div>
                            <div class="table-responsive">
                        <table class="table striped-table w-100" id="emergencybill-list">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Title</th>
                              <th class="fw-medium">Amount</th>
                             </tr>
                          </thead>
                          <tbody>

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

<!--  Add medication Start -->
 <div class="modal fade" id="emergency-add-medication-dose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-add-medication-doseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-medication-doseLabel">Add Medication Dose</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="emergencyMed-form">
        <div class="modal-body">
          <div class="row gy-3">
             <div class="col-md-6">
              <input type="hidden" id="emergencyMedDoseId">
                  <label class="form-label fw-medium" for="emergencyMed-visitid">Visit ID</label> <sup class="text-danger">*</sup>
                    <select id="emergencyMed-visitid" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                        @foreach ($visitsData as $visit)
                        <option value="{{$visit->id}}">MDVI0{{$visit->id}}</option>
                        @endforeach
                    </select>
                    <div class="emergencyMed-visitid_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="emergencyMed-medCategory">Medicine Category</label> <sup class="text-danger">*</sup>
                    <select id="emergencyMed-medCategory" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" onchange="medicinelist(this.value,document.getElementById('emergencyMed-visitid').value)">
                        <option value="">Select</option>
                        @foreach ($medicineCategory as $medCategory)
                        <option value="{{$medCategory->id}}">{{$medCategory->name}}</option>
                        @endforeach
                    </select>
                    <div class="emergencyMed-medCategory_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="emergencyMed-medName">Medicine Name</label> <sup class="text-danger">*</sup>
                  <select id="emergencyMed-medName" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                  </select>
                  <div class="emergencyMed-medName_errorCls d-none"></div>
              </div>
                <div class="col-md-6">
                  <label class="form-label fw-medium" for="emergencyMed-dose">Dose</label> <sup class="text-danger">*</sup>
                  <input id="emergencyMed-dose" type="text" class="form-control form-control-sm" placeholder=" Add Medicine Doses" oninput="validateField(this.id,'select')">
                  <div class="emergencyMed-dose_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium">Remarks</label>
                  <input id="emergencyMed-remerks" type="text" class="form-control form-control-sm" placeholder=" Remarks">
              </div>
          </div>
        
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
           @can('Emergency Medication Add')
            <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyMedDoseSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          @endcan
          @can('Emergency Medication Edit')
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyMedDoseUpdate d-none" onclick="emergencyMedDoseUpdate(document.getElementById('emergencyMedDoseId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
          @endcan
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add medication end -->
<!-- Add add-lab Start -->
<div class="modal fade" id="emergency-add-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-add-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-add-labLabel">Add Test Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="emergencyLab-form">
        <div class="modal-body">
         <div class="row gy-3">
          <div class="col-md-4">
            <input type="hidden" id="emergencyLabID">
              <label class="form-label fw-medium" for="emergencyLab-testType">Test Type</label> <sup class="text-danger">*</sup>
                 <select id="emergencyLab-testType" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select');getTestName(this.value)"">
                       <option value="">Select</option>
                      @foreach ($testtypes as $testtype)
                       <option value="{{$testtype->id}}">{{$testtype->name}}</option>
                      @endforeach
                    </select>
                    <div class="emergencyLab-testType_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium" for="emergencyLab-testName">Test Name</label> <sup class="text-danger">*</sup>
                 <select id="emergencyLab-testName" class="form-select form-select-sm select2-cls" style="width: 100%" onchange="getTestDetails(this.value)" oninput="validateField(this.id,'select')">
                      <option value="">Select</option>
                      {{-- @foreach ($testnames as $testname)
                        <option value="{{$testname->id}}">{{$testname->name}}</option>
                      @endforeach --}}
                    </select>
                    <div class="emergencyLab-testName_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Short Name <sup class="text-danger">*</sup></label>
                <input id="emergencyLab-shortName" type="text" class="form-control form-control-sm" placeholder=" Short Name" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Amount</label>
                <input id="emergencyLab-amount" type="number" class="form-control form-control-sm" placeholder=" Test Amount" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Method</label>
                <input id="emergencyLab-method" type="text" class="form-control form-control-sm" placeholder=" Test Method">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Report Days</label>
                <input id="emergencyLab-reportDays" type="number" class="form-control form-control-sm" placeholder=" Test Report Days">
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
                        <input id="emergencyLab-testParameter" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="emergencyLab-testRefRange" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="emergencyLab-testUnit" type="text" class="form-control form-control-sm" >
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
            @can('Emergency Lab Add')
              <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyLabSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
            @endcan
            @can('Emergency Lab Edit')
              <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyLabUpdate d-none" onclick="emergencyLabsUpdate(document.getElementById('emergencyLabID').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
            @endcan
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Add add-lab end -->
<!-- lab-test-veiw start -->
<div class="modal fade" id="emergency-lab-test-veiw" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-lab-test-veiwLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-lab-test-veiwLabel">Lab Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="emergencyLabDataAppend"></div>

      </div>
    </div>
  </div>
</div>
<!-- lab-test-veiw end -->

<!-- Edit lab-detail Start -->
 <div class="modal fade" id="emergency-edit-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-edit-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-edit-labLabel">Edit Test Details</h6>
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
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit lab-detail end -->
<!-- Add charges Start -->
<div class="modal fade" id="emergency-add-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-add-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-add-chargesLabel"> Add Charges</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="emergencyCharge-form">
        <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                <input type="hidden" id="emergencyChargeId">
                <label class="form-label fw-medium" for="emergencyCharge-name">Name</label> <sup class="text-danger">*</sup>
                  <input id="emergencyCharge-name" type="text" class="form-control form-control-sm" placeholder="Charge Name" oninput="validateField(this.id,'input')">
                  <div class="emergencyCharge-name_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium" for="emergencyCharge-amount">Amount</label> <sup class="text-danger">*</sup>
                  <input id="emergencyCharge-amount" type="number" class="form-control form-control-sm" placeholder="Charge Amount" oninput="validateField(this.id,'amount')">
                  <div class="emergencyCharge-amount_errorCls d-none"></div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          @can('Emergency Charge Add')
            <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyChargeSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          @endcan
          @can('Emergency Charge Edit')
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyChargeUpdate d-none" onclick="emergencyChargeUpdate(document.getElementById('emergencyChargeId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
          @endcan
        </div>
    </form>
    </div>
  </div>
</div>
<!-- Add charges History end -->
<!-- edit charges Start -->
<div class="modal fade" id="emergency-edit-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-edit-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-edit-chargesLabel"> Add Charges</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row gy-3">
            <div class="col-md-3 mb-3">
               <label class="form-label fw-medium">Charges Type<sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2  ">
                      <option selected>Select</option>
                      <option>OPD</option>
                      <option>Procedures</option>
                      <option>Supplier</option>
                      <option>Operations</option>
                      <option>Other</option>
                  </select>
            </div>
            <div class="col-md-3 mb-3">
               <label class="form-label fw-medium">Charge Category<sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2  ">
                      <option selected>Select</option>
                      <option>Operation  Service</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
               <label class="form-label fw-medium">Charge Name<sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2  ">
                      <option selected>Select</option>
                      <option>	Intensive Care</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
               <label class="form-label fw-medium">Quantity<sup class="text-danger">*</sup></label>
                <input type="number" class="form-control form-control-sm" placeholder="1">
            </div>
          </div>
          <div class="row border-top gy-3 mt-2">
            <div class="col-md-5 my-3">
                  <table class="table table-sm">
                    <tbody><tr>
                      <td class="border-0" colspan="2">Total (₹)</td>
                      <td class="border-0 text-end fs-6">0</td>
                    </tr>
                    <tr>
                      <td class="border-0 align-middle">Discount (₹)</td>
                      <td class="border-0"><div class="d-flex align-items-center"><input class="form-control form-control-sm discount-value-field" type="text" placeholder="Discount"><span class="ms-1">%</span></div></td>
                      <td class="border-0 text-end fs-6">0</td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Taxes (₹)</td>
                      <td class="border-0 text-end fs-6">0</td>
                    </tr>
                    <tr>
                      <td class="border-0" colspan="2">Net Amount (₹)</td>
                      <td class="border-0 text-end fs-6">0</td>
                    </tr>
                  </tbody></table>
             </div>
             <div class="col-md-4 my-3">
               <label class="form-label fw-medium">Charges Note<sup class="text-danger">*</sup></label>
                <textarea type="text" class="form-control " placeholder="Charges Note" rows="3"></textarea>
             </div>
             <div class="col-md-3 my-3">
               <label class="form-label fw-medium">Date<sup class="text-danger">*</sup></label>
                 <div class=" position-relative">
                    <input class="form-control form-control-sm radius-8 bg-base expiry-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
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
<!-- edit charges History end -->

<!-- Add nurse note Start -->
<div class="modal fade" id="emergency-nurse-note" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-nurse-noteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-nurse-noteLabel"> Add Nurse Note</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="emergencyNurseNote-form">
        <div class="modal-body">
          <div class="row gy-3">
            <div class="col-md-12">
              <input type="hidden" id="emergencyNurseNoteId">
                <label class="form-label fw-medium" for="emergencyNurse-name">Nurse<sup class="text-danger">*</sup></label>
                    <select id="emergencyNurse-name" class="form-select form-select-sm select2-cls" style="width: 100%;" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                        @foreach ($nurseData as $nData)
                        <option value="{{$nData->id}}">{{$nData->name}}</option>
                        @endforeach
                    </select>
                    <div class="emergencyNurse-name_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-medium" for="emergencyNurse-note">Note</label> <sup class="text-danger">*</sup>
                <input id="emergencyNurse-note"  class="form-control" rows="1" placeholder="Note" oninput="validateField(this.id,'input')">
                <div class="emergencyNurse-note_errorCls d-none"></div>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-medium" for="emergencyNurse-comment">Comment</label> <sup class="text-danger">*</sup>
                <textarea id="emergencyNurse-comment"  class="form-control" rows="2" placeholder="Comment"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          @can('Emergency Nurse Note Add')
            <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyNurseNoteSubmit"> <i class="ri-checkbox-circle-line" oninput="validateField(this.id,'input')"></i> Save</button>
          @endcan
          @can('Emergency Nurse Note Edit')
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyNurseNoteUpdate d-none" onclick="emergencyNurseNoteUpdate(document.getElementById('emergencyNurseNoteId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
          @endcan
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add nurse note end -->
<!-- Add vital History Start -->
<div class="modal fade" id="emergency-add-vital-history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-add-vital-historyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-add-vital-historyLabel"> Add Vital</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="emergencyVital-form">
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
                      <input type="hidden" id="emergencyVitalId">
                      <td>
                        <input type="text" id="emergencyVital-name" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="text" id="emergencyVital-value" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="date" id="emergencyVital-date" class="form-control form-control-sm" placeholder="DD-MM-YYYY" required>
                      </td>
                    </tr>
                  </tbody>
                 </table>
      </div>
       <div class="modal-footer">
        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
        @can('Emergency Vital Add')
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyVItalSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
        @endcan
        @can('Emergency Vital Edit')
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyVItalUpdate d-none" onclick="emergencyVItalUpdate(document.getElementById('emergencyVitalId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        @endcan
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Add vital History end -->
<!--  opd new checkup Start -->
 <div class="modal fade" id="emergency-new-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-new-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-new-checkupLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="emergencyVisit-form">
      <div class="modal-body">
        <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row gy-3">
             <div class="col-md-12">
              <input type="hidden" id="emergencyVisitId">
                <table class="table table-borderless pharmacy-bill-detail-table w-75 ">
                     <tbody>
                      <input type="hidden" id="emergencyVisit-patientId" value="{{$patients[0]->id}}">
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
               <label class="form-label fw-medium" for="emergencyVisit-symptoms">Symptoms</label>
                <input type="text" id="emergencyVisit-symptoms" class="form-control form-control-sm" placeholder="Symptoms" value="" oninput="validateField(this.id,'input')">
                <div class="emergencyVisit-symptoms_errorCls d-none"></div>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium" for="emergencyVisit-previousMedIssue">Previous Medical Issue</label>
               <textarea id="emergencyVisit-previousMedIssue" class="form-control " rows="1" placeholder="Previous Medical Issue" oninput="validateField(this.id,'input')" value=""></textarea>
                <div class="emergencyVisit-previousMedIssue_errorCls d-none"></div>
             </div>
             <div class="col-md-12">
               <label class="form-label fw-medium">Note</label>
               <textarea  id="emergencyVisit-note" class="form-control " rows="2" placeholder="Note" value=""></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-admissionDate">Appointment Date</label> <sup class="text-danger">*</sup>
              <div class=" position-relative">
                    <input id="emergencyVisit-admissionDate" class="form-control radius-8 bg-base opd-add-admission-date flatpickr-input active" type="date" placeholder="DD/MM/YYYY" value="{{ $curr_date}}" oninput="validateField(this.id,'select')">
                </div>
                <div class="emergencyVisit-admissionDate_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
               <label class="form-label fw-medium" for="emergencyVisit-oldPatient">Old Patient</label> <sup class="text-danger">*</sup>
              <select id="emergencyVisit-oldPatient" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                 <option value="">Select</option>
                 <option value="1">Yes</option>
                 <option value="0">No</option>
              </select>
               <div class="emergencyVisit-oldPatient_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-consultDoctor"> Consultant Doctor</label> <sup class="text-danger">*</sup>
               <select id="emergencyVisit-consultDoctor" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                      <option value="">Select</option>
                      @foreach ($doctorData as $doctorName)
                      <option value="{{$doctorName->id}}">{{$doctorName->name}}</option>
                      @endforeach
              </select>
               <div class="emergencyVisit-consultDoctor_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-charge"> Applied Charge</label>(₹) <sup class="text-danger">*</sup>
               <input id="emergencyVisit-charge" type="number" class="form-control form-control-sm" placeholder="Applied Charge" value="" oninput="validateField(this.id,'amount');calculateAmount()">
                <div class="emergencyVisit-charge_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-discount"> Discount</label>% 
               <input id="emergencyVisit-discount" type="number" class="form-control form-control-sm" placeholder="Discount" value="" oninput="calculateAmount()">
                <div class="emergencyVisit-discount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-tax"> Tax</label>% 
               <input id="emergencyVisit-tax" type="number" class="form-control form-control-sm" placeholder="Discount" value=""  oninput="calculateAmount()">
                <div class="emergencyVisit-tax_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="emergencyVisit-amount"> Amount</label>(₹) <sup class="text-danger">*</sup>
               <input id="emergencyVisit-amount" type="number" class="form-control form-control-sm" placeholder="Amount" value="" readonly>
                <div class="emergencyVisit-amount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium" for="emergencyVisit-paymentMode"> Payment Mode</label> <sup class="text-danger">*</sup>
               <select id="emergencyVisit-paymentMode" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                <option value="">Select</option>
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="cheque">Cheque</option>
                <option value="other">Other</option>
              </select>
               <div class="emergencyVisit-paymentMode_errorCls d-none"></div>
            </div>
            {{-- <div class="col-md-6 mb-3" style="display: none1;" id="upi-reference-no">
              <label class="form-label fw-medium ">Reference Number</label>
              <input id="emergencyVisit-refNum" type="number" class="form-control form-control-sm" placeholder=" Enter payment reference number">
            </div> --}}
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium" for="emergencyVisit-paidAmount">Pay Amount</label> <sup class="text-danger">*</sup>
               <input id="emergencyVisit-paidAmount" type="number" class="form-control form-control-sm" placeholder="Pay Amount" oninput="checkEmergencyVisitPaidAmount()">
                <div class="emergencyVisit-paidAmount_errorCls d-none"></div>
            </div>
            <div class="col-md-6 mb-3 emergencyVisit-AlreadypaidAmountCls d-none">
             <label class="form-label fw-medium" for="ipdVisit-paidAmount">Paid Amount</label> 
               <input id="emergencyVisit-AlreadypaidAmount" type="number" class="form-control form-control-sm" placeholder="Paid Amount" readonly>
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
        @can('Emergency Visit Add')
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyVisitSubmit"><i class="ri-checkbox-circle-line"></i> Submit</button>
        @endcan
        @can('Emergency Visit Edit')
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 emergencyVisitUpdate d-none" onclick="emergencyVisitUpdate(document.getElementById('emergencyVisitId').value)"><i class="ri-checkbox-circle-line"></i> Update</button>
        @endcan
      </div>
    </form>
    </div>
  </div>
</div>
<!-- opd new checkup end -->

<!--  opd-visit-view Start -->
 <div class="modal fade" id="emergency-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergency-visit-viewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="emergency-visit-viewLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="emergencyVisitViewDataAppend"></div>
      </div>
    </div>
  </div>
</div>
<!-- opd visit view end -->
 <!--Alert IPD modal start -->
  <div class="modal fade" id="moveToIpdModel" tabindex="-1" role="dialog" aria-labelledby="moveToIpdModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Bed Number</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <form action="" id="emergency-ipdBedForm" class="needs-validation" novalidate="">
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="room_num">Bed Number</label>
                    {{-- <input type="hidden" id=opd-ipdRoom"> --}}
                   <select class="form-control form-control-sm" name="emergency-ipdBed" id="emergency-ipdBed" required>
                        <option value="">Select IPD Bed Number</option>
                        @foreach ($ipdAvailBeds as $ipdBed)
                        <option value="{{$ipdBed->id}}">{{$ipdBed->bed_no}}</option>
                        @endforeach
                    </select>   
                    <div class="invalid-feedback">
                            Select IPD Bed
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
 <!-- Alert IPD modal end-->
 <!--Alert IPD modal start -->
  <div class="modal fade" id="moveToIcuModel" tabindex="-1" role="dialog" aria-labelledby="moveToIcuModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Bed Number</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <form action="" id="emergency-icuBedForm" class="needs-validation" novalidate="">
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="">Bed Number</label>
                    {{-- <input type="hidden" id=opd-ipdRoom"> --}}
                   <select class="form-control form-control-sm" name="emergency-icuBed" id="emergency-icuBed" required>
                        <option value="">Select ICU Bed Number</option>
                        @foreach ($icuAvailBeds as $icuBed)
                        <option value="{{$icuBed->id}}">{{$icuBed->bed_no}}</option>
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
 <!-- Alert IPD modal end-->
  <!--Alert Discharge modal start -->
  <div class="modal fade" id="emergencyDischargeModel" tabindex="-1" role="dialog" aria-labelledby="moveToIcuModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <div class="modal-toggle-wrapper  text-start dark-sign-up">
          <div class="modal-header bg-primary-600 p-11">
             <h6 class="modal-title fw-normal text-md text-white userType-title">Due</h6>
                <button class="btn-close btn-custom py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <form action="" id="emergency-dischargeAmountForm" class="needs-validation" novalidate="">
                <div class="modal-body">
                <div class="row gy-3">
                  <div class="col-md-12">
                    <label class="form-label" for="emergencyBillAmount">Total Bill Amount</label>
                    <input class="form-control form-control-sm" id="emergencyBillAmount" type="text"
                       style="background-image: none;" readonly>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="emergencyPaidAmount">Paid Amount</label>
                    <input class="form-control form-control-sm" id="emergencyPaidAmount" type="text"
                        placeholder="Enter Pay Amount" style="background-image: none;" readonly>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="emergencyPayAmount"> Pay Amount</label>
                    <input class="form-control form-control-sm" id="emergencyPayAmount" type="text"
                        placeholder="Enter Pay Amount" style="background-image: none;">
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
 <!-- Alert DIscharge modal end-->
@endsection
@section('extra-js')
<script>
  // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y",
        });
    }
    getDatePicker('#emergencyVisit-admissionDate'); 

    $('#emergency-new-checkup').on('shown.bs.modal', function () {
        $('.select2-cls').select2({
            dropdownParent: $('#emergency-new-checkup')
        });
    });
    $('#emergency-add-medication-dose').on('shown.bs.modal', function () {
        $('.select2-cls').select2({
            dropdownParent: $('#emergency-add-medication-dose')
        });
    });
    $('#emergency-add-lab').on('shown.bs.modal', function () {
        $('.select2-cls').select2({
            dropdownParent: $('#emergency-add-lab')
        });
    });
     $('#emergency-nurse-note').on('shown.bs.modal', function () {
        $('.select2-cls').select2({
            dropdownParent: $('#emergency-nurse-note')
        });
    });
      const emergencyMedicineName = "{{route('common.getMedicineName')}}";
      const moveToIpdStatus = "{{route('emergency.moveToIpdStatus')}}";
      const moveToIcuStatus = "{{route('emergency.moveToIcuStatus')}}";
      const calculateDischargeAmountEmergency = "{{route('emergency.calculateDischargeAmountEmergency')}}";
      const submitRestEmergencyAmount = "{{route('emergency.submitRestEmergencyAmount')}}";
      const patientDischargeStatusE = "{{route('emergency.patientDischargeStatusE')}}";

    const emergencyVisitSubmit = "{{route('emergency-visit.emergencyVisitSubmit')}}";
    const viewEmergencyVisit = "{{route('emergency-visit.viewEmergencyVisit')}}";
    const getEmergencyVisitData = "{{route('emergency-visit.getEmergencyVisitData')}}";
    const getEmergencyVisitDetails = "{{route('emergency-visit.getEmergencyVisitData')}}";
    const emergencyVisitDataUpdate = "{{route('emergency-visit.emergencyVisitDataUpdate')}}";
    const emergencyVisitDataDelete = "{{route('emergency-visit.emergencyVisitDataDelete')}}";

    const emergencyVisitId = "{{route('emergency-med.emergencyVisitId')}}";
    const emergencyMedDataAdd = "{{route('emergency-med.emergencyMedDataAdd')}}";
    const viewEmergencyMedDose = "{{route('emergency-med.viewEmergencyMedDose')}}";
    const getEmergencyMedDoseDetails = "{{route('emergency-med.getEmergencyMedDoseDetails')}}";
    const emergencyMedDataUpdate = "{{route('emergency-med.emergencyMedDataUpdate')}}";
    const emergencyMedDoseDataDelete = "{{route('emergency-med.emergencyMedDoseDataDelete')}}";

    const getTestNameByTypeEmergency = "{{route('emergency-lab.getTestNameByTypeEmergency')}}";
    const getTestDetailsByIdEmergency = "{{route('emergency-lab.getTestDetailsByIdEmergency')}}";
    const emergencyLabSubmit = "{{route('emergency-lab.emergencyLabSubmit')}}";
    const viewEmergencyLabData = "{{route('emergency-lab.viewEmergencyLabData')}}";
    const getEmergencyLabData = "{{route('emergency-lab.getEmergencyLabData')}}";
    const getEmergencyLabDetails = "{{route('emergency-lab.getEmergencyLabDetails')}}";
    const emergencyLabUpdateData = "{{route('emergency-lab.emergencyLabUpdateData')}}";
    const emergencyLabDataDelete = "{{route('emergency-lab.emergencyLabDataDelete')}}";

     const emergencyChargeSubmit = "{{route('emergency-charge.emergencyChargeSubmit')}}";
     const viewEmergencyCharge = "{{route('emergency-charge.viewEmergencyCharge')}}";
     const getEmergencyChargeData = "{{route('emergency-charge.getEmergencyChargeData')}}";
     const emergencyChargeDataUpdate = "{{route('emergency-charge.emergencyChargeDataUpdate')}}";
     const emergencyChargeDataDelete = "{{route('emergency-charge.emergencyChargeDataDelete')}}";

    const emergencyNurseNoteSubmit = "{{route('emergency-nurse.emergencyNurseNoteSubmit')}}";
    const viewEmergencyNurseNote = "{{route('emergency-nurse.viewEmergencyNurseNote')}}";
    const getEmergencyNurseNoteData = "{{route('emergency-nurse.getEmergencyNurseNoteData')}}";
    const emergencyNurseNoteDataUpdate = "{{route('emergency-nurse.emergencyNurseNoteDataUpdate')}}";
    const emergencyNurseDataDelete = "{{route('emergency-nurse.emergencyNurseDataDelete')}}";

     const viewEmergencyVital = "{{route('emergency-vital.viewEmergencyVital')}}";
     const emergencyVItalSubmit = "{{route('emergency-vital.emergencyVItalSubmit')}}";
     const getEmergencyVitalData = "{{route('emergency-vital.getEmergencyVitalData')}}";
     const emergencyVitalDataUpdate = "{{route('emergency-vital.emergencyVitalDataUpdate')}}";
     const emergencyVitalDataDelete = "{{route('emergency-vital.emergencyVitalDataDelete')}}";

     const viewEmergencyBills = "{{route('emergency.viewEmergencyBills')}}";
</script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-visit.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-medication.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-lab.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-charge.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-nurse.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-vital.js')}}"></script>
  <script src="{{asset('backend/assets/js/custom/admin/emergency/emergency-details/emergency-details-bills.js')}}"></script>
@endsection