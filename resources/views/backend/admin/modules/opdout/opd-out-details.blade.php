@extends('backend.admin.layouts.main')
@section('title')
    OPD detalils
@endsection
@section('extra-css')
<style>
</style>
@endsection
@section('main-container')

<div class="dashboard-main-body">
  <input type="hidden" id="patient_Id" value="{{$patients[0]->id}}">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">OPD - Out Patient Details</h6>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <button type="button" class="btn btn-primary-600 fw-normal btn-sm d-flex align-items-center gap-2" onclick="moveToIpd({{$patients[0]->id}})"> <i class="ri-stethoscope-line"></i></i> Move to IPD</button>
          {{-- <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button> --}}
          {{-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#out-patient-ipd"><i class="ri-stethoscope-line"></i> Move to IPD</button> --}}
          {{-- <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line"></i> Export</button> --}}
      </div>
    </div>
    @php
      $curr_date = date('d/m/Y');
    @endphp
    <div class="card">
        <div class="card-body p-24">
            <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex w-100  " id="pills-tab" role="tablist">
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
                  <!--<li class="nav-item" role="presentation">-->
                  <!--  <button class="nav-link px-16 py-10 " id="pills-timeline-tab" data-bs-toggle="pill" data-bs-target="#pills-timeline" type="button" role="tab" aria-controls="pills-timeline" aria-selected="false">Timeline</button>-->
                  <!--</li>-->
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
                            {{-- <p class="mb-1">{{$doctors[0]->firstname}} {{$doctors[0]->lastname}}</p> --}}
                            <div class="d-flex align-items-center">
                              <p class="mb-0 mx-1">Finding :</p> 
                              <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#add-finding">
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
                                        $doctor_name = app\Models\User::where('id',$visit->consult_doctor)->get(['firstname','lastname']);
                                      @endphp
                                        <tr>
                                        <td>{{$visit->appointment_date}}</td>
                                        <td>{{$doctor_name[0]->firstname}} {{$doctor_name[0]->lastname}}</td>
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
                                        <th scope="col" class="fw-medium">Test Type</th>
                                        <th scope="col" class="fw-medium">Test Name</th>
                                        <th scope="col" class="fw-medium">Sample collect</th>
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-new-checkup" onclick="resetVisit()"> <i class="ri-add-line"></i> New Checkup</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="opd-out-visit-list" data-page-length='10'>
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
                            {{-- data appended here using datatable from opdout-details-visit.js --}}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-Medication" role="tabpanel" aria-labelledby="pills-Medication-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Medication</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#opd-add-medication-dose" onclick="resetMedication()"> <i class="ri-add-line"></i> Add Medication Dose</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table mb-0 w-100" id="opdOutMed-medicineDoseList">
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
                             {{-- <tr>
                              <td>04/02/2025 (Tue) <br> Created by : Super Admin (9001)</td>
                              <td>Alprovit</td>
                              <td>Alprovit</td>
                              <td>Alprovit</td>
                              <td>Alprovit</td>
                              <td>Alprovit</td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <span class="mx-11">1 CT </span>
                                  <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#opd-add-medication-dose">
                                    <i class="ri-add-line"></i>
                                  </button>
                                </div>
                              </td>
                             </tr> --}}
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#opd-add-lab" onclick="resetLabTest()"> <i class="ri-add-line"></i> Add Lab</button>
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="opd-lab-reports-list" data-page-length='10'>
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
                                      <tr>
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
                                      </tr>
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
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-add-charges" onclick="resetCharge()"> <i class="ri-add-line"></i> Add Charges</button>
                        </div>
                      <div class="table-responsive">
                        <table class="table  striped-table w-100" id="opd-out-charges-list">
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
                <div class="tab-pane fade" id="pills-timeline" role="tabpanel" aria-labelledby="pills-timeline-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 mb-11 d-flex align-items-center justify-content-between">
                        <h6 class="text-md fw-normal mb-0">Timeline</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#add-timeline"> <i class="ri-add-line"></i> Add Timeline</button>
                      </div>
                      <div class="timeline-container">
                            <!-- Timeline Section 1 -->
                            <div class="timeline-section">
                              <div class="timeline-date blue-marker">
                                <span class="bg-neutral-100 rounded text-nowrap fw-medium">05/28/2025 03:28 PM</span>
                              </div>
                              <div class="gap-4 pb-3">
                                <div class="card bg-neutral-100">
                                  <div class="card-body">
                                    <div class="border-bottom d-flex align-items-center justify-content-between">
                                      <h6 class="fw-normal mb-0 pb-11 text-lg">Take medicine after meal everyday .</h6>
                                      <div class=""><i class="ri-edit-2-line mx-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#edit-timeline"></i> <i class="ri-delete-bin-6-line cursor-pointer"></i></div>
                                    </div>
                                    <p class="mb-0 mt-11 text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo incidunt animi et itaque maxime expedita hic blanditiis laborum. Ducimus quae dolorem assumenda pariatur minus distinctio commodi, cumque sunt maiores iste suscipit! Corrupti, voluptatum rerum voluptates explicabo ut delectus quos nesciunt, maxime repellat quae aspernatur quibusdam tenetur natus, voluptate veniam qui? Aliquid, ipsum qui! Alias obcaecati iure maxime. Vero expedita enim nihil dignissimos optio reprehenderit necessitatibus tempore architecto alias excepturi ex commodi ipsam beatae fugit sint vitae blanditiis autem nulla, minus dolores. Earum assumenda cumque veniam, debitis excepturi maxime expedita porro sint, corrupti quaerat optio exercitationem tenetur aspernatur ut delectus quidem.</p>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Timeline Section 2 -->
                            <div class="timeline-section">
                              <div class="timeline-date blue-marker">
                                <span  class="bg-neutral-100  rounded text-nowrap fw-medium ">05/28/2025 03:28 PM</span>
                              </div>
                              <div class=" gap-4 pb-3">
                                <div class="card bg-neutral-100">
                                  <div class="card-body">
                                    <div class="border-bottom d-flex align-items-center justify-content-between">
                                      <h6 class="fw-normal mb-0 pb-11 text-lg">Take medicine after meal everyday .</h6>
                                      <div class=""><i class="ri-edit-2-line mx-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#edit-timeline"></i> <i class="ri-delete-bin-6-line cursor-pointer"></i></div>
                                    </div>
                                    <p class="mb-0 mt-11 text-sm">Take medicine after meal everyday .</p>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Timeline Section 3 -->
                            <div class="timeline-section">
                              <div class="timeline-date blue-marker">
                                <span  class="bg-neutral-100  rounded text-nowrap fw-medium"> 05/28/2025 03:28 PM</span>
                              </div>
                              <div class="gap-4 pb-3">
                                <div class="card bg-neutral-100">
                                  <div class="card-body">
                                    <div class="border-bottom d-flex align-items-center justify-content-between">
                                      <h6 class="fw-normal mb-0 pb-11 text-lg">Take medicine after meal everyday .</h6>
                                      <div class=""><i class="ri-edit-2-line mx-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#edit-timeline"></i> <i class="ri-delete-bin-6-line cursor-pointer"></i></div>
                                    </div>
                                    <p class="mb-0 mt-11 text-sm">Take medicine after meal everyday .</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-normal mb-0">Vital History</h6>
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-add-vital-history" onclick="resetVital()"> <i class="ri-add-line"></i> Add Vital History</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table w-100" id="opdOutVital-list">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Name</th>
                              <th class="fw-medium">Value</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
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
                             </tr>
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
<!-- Add vital History Start -->
<div class="modal fade" id="opd-add-vital-history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-vital-historyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-vital-historyLabel"> Add Vital</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="opdOutVital-form">
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
                      <input type="hidden" id="opdOutVitalId">
                      <td>
                        <input type="text" id="opdOutVital-name" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="text" id="opdOutVital-value" class="form-control form-control-sm" required>
                      </td>
                      <td>
                        <input type="date" id="opdOutVital-date" class="form-control form-control-sm" placeholder="DD-MM-YYYY" required>
                      </td>
                    </tr>
                  </tbody>
                 </table>
      </div>
       <div class="modal-footer">
        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOurVItalSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOurVItalUpdate d-none" onclick="opdOurVItalUpdate(document.getElementById('opdOutVitalId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Add vital History end -->
<!-- Add charges Start -->
<div class="modal fade" id="opd-add-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-chargesLabel"> Add Charges</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="opdOutCharge-form">
        <div class="modal-body">
          <div class="row">
              <div class="col-md-6">
                <input type="hidden" id="opdOutChargeId">
                <label class="form-label fw-medium" for="opdOutCharge-name">Name</label> <sup class="text-danger">*</sup>
                  <input id="opdOutCharge-name" type="text" class="form-control form-control-sm" placeholder="Charge Name" oninput="validateField(this.id,'input')">
                  <div class="opdOutCharge-name_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-medium" for="opdOutCharge-amount">Amount</label> <sup class="text-danger">*</sup>
                  <input id="opdOutCharge-amount" type="number" class="form-control form-control-sm" placeholder="Charge Amount" oninput="validateField(this.id,'amount')">
                  <div class="opdOutCharge-amount_errorCls d-none"></div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutChargeSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutChargeUpdate d-none" onclick="opdOutChargeUpdate(document.getElementById('opdOutChargeId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!-- Add charges History end -->
<!-- Add add-lab Start -->
<div class="modal fade" id="opd-add-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-labLabel">Add Test Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="opdOutLab-form">
        <div class="modal-body">
         <div class="row gy-3">
          <div class="col-md-4">
            <input type="hidden" id="opOutLabID">
              <label class="form-label fw-medium" for="opdOutLab-testType">Test Type</label> <sup class="text-danger">*</sup>
                 <select id="opdOutLab-testType" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                       <option value="">Select</option>
                      @foreach ($testtypes as $testtype)
                       <option value="{{$testtype->id}}">{{$testtype->name}}</option>
                      @endforeach
                    </select>
                    <div class="opdOutLab-testType_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium" for="opdOutLab-testName">Test Name</label> <sup class="text-danger">*</sup>
                 <select id="opdOutLab-testName" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                 
                        <option value="">Select</option>
                      @foreach ($testnames as $testname)
                        <option value="{{$testname->id}}">{{$testname->name}}</option>
                      @endforeach
                    </select>
                    <div class="opdOutLab-testName_errorCls d-none"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Short Name <sup class="text-danger">*</sup></label>
                <input id="opdOutLab-shortName" type="text" class="form-control form-control-sm" placeholder=" Short Name" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Amount</label>
                <input id="opdOutLab-amount" type="number" class="form-control form-control-sm" placeholder=" Test Amount" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Method</label>
                <input id="opdOutLab-method" type="text" class="form-control form-control-sm" placeholder=" Test Method">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Report Days</label>
                <input id="opdOutLab-reportDays" type="number" class="form-control form-control-sm" placeholder=" Test Report Days">
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
                        <input id="opdOutLab-testParameter" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="opdOutLab-testRefRange" type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input id="opdOutLab-testUnit" type="text" class="form-control form-control-sm" >
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
            <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutLabSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
            <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutLabUpdate d-none" onclick="opdOutLabUpdate(document.getElementById('opOutLabID').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- Add add-lab end -->

<!-- lab-test-veiw start -->
<div class="modal fade" id="opd-lab-test-veiw" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-lab-test-veiwLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-lab-test-veiwLabel">Lab Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="opdOutLabDataAppend"></div>
      </div>
    </div>
  </div>
</div>
<!-- lab-test-veiw end -->

<!--  Add medication Start -->
 <div class="modal fade" id="opd-add-medication-dose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-medication-doseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-medication-doseLabel">Add Medication Dose</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="opdOutMed-form">
        <div class="modal-body">
          <div class="row gy-3">
             <div class="col-md-6">
              <input type="hidden" id="opdOutMedDoseId">
                  <label class="form-label fw-medium" for="opdOutMed-visitid">Visit ID</label> <sup class="text-danger">*</sup>
                    <select id="opdOutMed-visitid" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                        @foreach ($visitsData as $visit)
                        <option value="{{$visit->id}}">MDVI0{{$visit->id}}</option>
                        @endforeach
                    </select>
                    <div class="opdOutMed-visitid_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="opdOutMed-medCategory">Medicine Category</label> <sup class="text-danger">*</sup>
                    <select id="opdOutMed-medCategory" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')" onchange="medicinelist(this.value)">
                        <option value="">Select</option>
                        @foreach ($medicineCategory as $medCategory)
                        <option value="{{$medCategory->id}}">{{$medCategory->name}}</option>
                        @endforeach
                    </select>
                    <div class="opdOutMed-medCategory_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium" for="opdOutMed-medName">Medicine Name</label> <sup class="text-danger">*</sup>
                  <select id="opdOutMed-medName" class="form-select form-select-sm select2-cls" style="width: 100%" oninput="validateField(this.id,'select')">
                        <option value="">Select</option>
                  </select>
                  <div class="opdOutMed-medName_errorCls d-none"></div>
              </div>
                <div class="col-md-6">
                  <label class="form-label fw-medium" for="opdOutMed-dose">Dose</label> <sup class="text-danger">*</sup>
                  <input id="opdOutMed-dose" type="text" class="form-control form-control-sm" placeholder=" Add Medicine Doses" oninput="validateField(this.id,'select')">
                  <div class="opdOutMed-dose_errorCls d-none"></div>
              </div>
              <div class="col-md-6">
                  <label class="form-label fw-medium">Remarks</label>
                  <input id="opdOutMed-remerks" type="text" class="form-control form-control-sm" placeholder=" Remarks">
              </div>
          </div>
        
        </div>
        <div class="modal-footer">
           <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutMedDoseSubmit"> <i class="ri-checkbox-circle-line"></i> Submit</button>
          <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutMedDoseUpdate d-none" onclick="opdOutMedDoseUpdate(document.getElementById('opdOutMedDoseId').value)"> <i class="ri-checkbox-circle-line"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add medication end -->

<!--  opd new checkup Start -->
 <div class="modal fade" id="opd-new-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-new-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-new-checkupLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" id="opdOutVisit-modelForm">
      <div class="modal-body">
        <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row gy-3">
             <div class="col-md-12">
              <input type="hidden" id="opdOutVisitId">
                <table class="table table-borderless pharmacy-bill-detail-table w-75 ">
                     <tbody>
                      <input type="hidden" id="opdOutVisit-patientId" value="{{$patients[0]->id}}">
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
               <label class="form-label fw-medium" for="opdOutVisit-symptoms">Symptoms</label>
                <input type="text" id="opdOutVisit-symptoms" class="form-control form-control-sm" placeholder="Symptoms" value="" oninput="validateField(this.id,'input')">
                <div class="opdOutVisit-symptoms_errorCls d-none"></div>
             </div>
             <div class="col-md-6">
               <label class="form-label fw-medium" for="opdOutVisit-previousMedIssue">Previous Medical Issue</label>
               <textarea id="opdOutVisit-previousMedIssue" class="form-control " rows="1" placeholder="Previous Medical Issue" oninput="validateField(this.id,'input')" value=""></textarea>
                <div class="opdOutVisit-previousMedIssue_errorCls d-none"></div>
             </div>
             <div class="col-md-12">
               <label class="form-label fw-medium">Note</label>
               <textarea  id="opdOutVisit-note" class="form-control " rows="2" placeholder="Note" value=""></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row gy-3">
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-admissionDate">Appointment Date</label>
              <div class=" position-relative">
                    <input id="opdOutVisit-admissionDate" class="form-control radius-8 bg-base opd-add-admission-date flatpickr-input active" type="text" value="{{ $curr_date}}" readonly="readonly">
                </div>
            </div>
            <div class="col-md-6">
               <label class="form-label fw-medium" for="opdOutVisit-oldPatient">Old Patient</label>
              <select id="opdOutVisit-oldPatient" class="form-select form-select-sm select2" oninput="validateField(this.id,'select')">
                 <option value="">Select</option>
                 <option value="1">Yes</option>
                 <option value="0">No</option>
              </select>
               <div class="opdOutVisit-oldPatient_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-consultDoctor"> Consultant Doctor</label> <sup class="text-danger">*</sup>
               <select id="opdOutVisit-consultDoctor" class="form-select form-select-sm select2" oninput="validateField(this.id,'select')">
                      <option value="">Select</option>
                      @foreach ($doctorData as $doctorName)
                      <option value="{{$doctorName->id}}">{{$doctorName->firstname}} {{$doctorName->lastname}}</option>
                      @endforeach
              </select>
               <div class="opdOutVisit-consultDoctor_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-charge"> Applied Charge</label>(₹) <sup class="text-danger">*</sup>
               <input id="opdOutVisit-charge" type="number" class="form-control form-control-sm" placeholder="Applied Charge" value="" oninput="validateField(this.id,'amount');calculateAmount()">
                <div class="opdOutVisit-charge_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-discount"> Discount</label>% <sup class="text-danger">*</sup>
               <input id="opdOutVisit-discount" type="number" class="form-control form-control-sm" placeholder="Discount" value="" oninput="calculateAmount()">
                <div class="opdOutVisit-discount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-tax"> Tax</label>% <sup class="text-danger">*</sup>
               <input id="opdOutVisit-tax" type="number" class="form-control form-control-sm" placeholder="Discount" value=""  oninput="calculateAmount()">
                <div class="opdOutVisit-tax_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium" for="opdOutVisit-amount"> Amount</label>(₹) <sup class="text-danger">*</sup>
               <input id="opdOutVisit-amount" type="number" class="form-control form-control-sm" placeholder="Amount" value="" readonly>
                <div class="opdOutVisit-amount_errorCls d-none"></div>
            </div>
            <div class="col-md-6">
             <label class="form-label fw-medium" for="opdOutVisit-paymentMode"> Payment Mode</label> <sup class="text-danger">*</sup>
               <select id="opdOutVisit-paymentMode" class="form-select form-select-sm" oninput="validateField(this.id,'select')">
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="cheque">Cheque</option>
                <option value="other">Other</option>
              </select>
               <div class="opdOutVisit-paymentMode_errorCls d-none"></div>
            </div>
            <div class="col-md-6 mb-3" style="display: none1;" id="upi-reference-no">
              <label class="form-label fw-medium ">Reference Number</label>
              <input id="opdOutVisit-refNum" type="number" class="form-control form-control-sm" placeholder=" Enter payment reference number">
            </div>
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium" for="opdOutVisit-paidAmount">Pay Amount</label> <sup class="text-danger">*</sup>
               <input id="opdOutVisit-paidAmount" type="number" class="form-control form-control-sm" placeholder="Paid Amount" oninput="validateField(this.id,'amount')">
                <div class="opdOutVisit-paidAmount_errorCls d-none"></div>
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
        <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutVisitSubmit"><i class="ri-checkbox-circle-line"></i> Submit</button>
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2 opdOutVisitUpdate d-none" onclick="opdOutVisitUpdate(document.getElementById('opdOutVisitId').value)"><i class="ri-checkbox-circle-line"></i> Update</button>
      </div>
    </div>
  </form>
  </div>
</div>
<!-- opd new checkup end -->
<!--  opd-visit-view Start -->
 <div class="modal fade" id="opd-out-visit-view" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-visit-viewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-visit-viewLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="opdOutVisitViewDataAppend"></div>
      </div>
    </div>
  </div>
</div>
<!-- opd visit view end -->
<!-- add timeline start -->
<div class="modal fade" id="add-timeline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-timelineLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="add-timelineLabel">Add Timeline</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row gy-3">
            <div class="col-md-12 ">
              <label class="form-label fw-medium">Title<sup class="text-danger">*</sup></label>
              <input type="text" class="form-control form-control-sm" placeholder="Enter title">
            </div>
            <div class="col-md-12 ">
              <label class="form-label fw-medium">Date<sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                </div>
            </div>
            <div class="col-md-12 ">
              <label class="form-label fw-medium">Description</label>
              <textarea type="text" class="form-control " placeholder="Description" rows="2"></textarea>
            </div>
            <div class="col-md-12">
               <label for="file-upload-name" class="mb-16 border border-neutral-600 fw-medium text-secondary-light w-100 p-1 rounded d-inline-flex align-items-center justify-content-center gap-2 bg-hover-neutral-200">
                      <i class="ri-upload-cloud-2-line text-xl"></i>
                      Click to upload 
                      <input type="file" class="form-control w-auto mt-24 form-control-lg" id="file-upload-name" multiple hidden>
                  </label>
                  <ul id="uploaded-img-names" class=""></ul>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- add timeline end -->

<!-- edit timeline start -->
<div class="modal fade" id="edit-timeline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="edit-timelineLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="edit-timelineLabel">Edit Timeline</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row gy-3">
            <div class="col-md-12">
              <label class="form-label fw-medium">Title<sup class="text-danger">*</sup></label>
              <input type="text" class="form-control form-control-sm" placeholder="Enter title">
            </div>
            <div class="col-md-12">
              <label class="form-label fw-medium">Date<sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                </div>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-medium">Description</label>
              <textarea type="text" class="form-control " placeholder="Description" rows="2"></textarea>
            </div>
            <div class="col-md-12">
               <label for="file-upload-name" class="mb-16 border border-neutral-600 fw-medium text-secondary-light w-100 p-1 rounded d-inline-flex align-items-center justify-content-center gap-2 bg-hover-neutral-200">
                      <i class="ri-upload-cloud-2-line text-xl"></i>
                      Click to upload 
                      <input type="file" class="form-control w-auto mt-24 form-control-lg" id="file-upload-name" multiple hidden>
                  </label>
                  <ul id="uploaded-img-names" class=""></ul>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('extra-js')

  <script>
// Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "d-m-Y",
        });
    }
    getDatePicker('#opdOutVital-date'); 
    getDatePicker('#opdOutVisit-admissionDate'); 
    // Flat pickr or date picker js 

$('#opd-add-medication-dose').on('shown.bs.modal', function () {
    $('.select2-cls').select2({
        dropdownParent: $('#opd-add-medication-dose')
    });
});

$('#opd-add-lab').on('shown.bs.modal', function () {
    $('.select2-cls').select2({
        dropdownParent: $('#opd-add-lab')
    });
});

  // const moveToIpdStatus = "{{route('opd-out.moveToIpdStatus')}}";
  const opdOutVisitMedicineName = "{{route('common.getMedicineName')}}";

  const opdOutVisitSubmit = "{{route('opd-out-visit.opdOutVisitSubmit')}}";
  const viewOptOutVisit = "{{route('opd-out-visit.viewOptOutVisit')}}";
  const getOpdOutVisitData = "{{route('opd-out-visit.getOpdOutVisitData')}}";
  const getOpdOutVisitDetails = "{{route('opd-out-visit.getOpdOutVisitData')}}";
  const opdOutVisitDataUpdate = "{{route('opd-out-visit.opdOutVisitDataUpdate')}}";
  const opdOutVisitDataDelete = "{{route('opd-out-visit.opdOutVisitDataDelete')}}";

  const opdOutMedDataAdd = "{{route('opd-out-med.opdOutMedDataAdd')}}";
  const viewOptOutMedDose = "{{route('opd-out-med.viewOptOutMedDose')}}";
  const getOpdOutMedDoseDetails = "{{route('opd-out-med.getOpdOutMedDoseDetails')}}";
  const opdOutMedDataUpdate = "{{route('opd-out-med.opdOutMedDataUpdate')}}";
  const opdOutMedDoseDataDelete = "{{route('opd-out-med.opdOutMedDoseDataDelete')}}";

  const opdOutLabSubmit = "{{route('opd-out-lab.opdOutLabSubmit')}}";
  const viewOpdOutLabDetails = "{{route('opd-out-lab.viewOpdOutLabDetails')}}";
  const getOpdOutLabData = "{{route('opd-out-lab.getOpdOutLabData')}}";
  const getOpdOutLabDetails = "{{route('opd-out-lab.getOpdOutLabDetails')}}";
  const opdOutLabUpdateData = "{{route('opd-out-lab.opdOutLabUpdateData')}}";
  const opdOutLabDataDelete = "{{route('opd-out-lab.opdOutLabDataDelete')}}";

  const opdOutChargeSubmit = "{{route('opd-out-charge.opdOutChargeSubmit')}}";
  const viewOpdOutCharge = "{{route('opd-out-charge.viewOpdOutCharge')}}";
  const getOpdOutChargeData = "{{route('opd-out-charge.getOpdOutChargeData')}}";
  const opdOutChargeDataUpdate = "{{route('opd-out-charge.opdOutChargeDataUpdate')}}";
  const opdOutChargeDataDelete = "{{route('opd-out-charge.opdOutChargeDataDelete')}}";

  const opdOutVItalSubmit = "{{route('opd-out-vital.opdOutVItalSubmit')}}";
  const viewOpdOutVital = "{{route('opd-out-vital.viewOpdOutVital')}}";
  const getOpdOutVitalData = "{{route('opd-out-vital.getOpdOutVitalData')}}";
  const opdOutVItalDataUpdate = "{{route('opd-out-vital.opdOutVItalDataUpdate')}}";
  const opdOutVitalDataDelete = "{{route('opd-out-vital.opdOutVitalDataDelete')}}";

</script>
 {{-----------external js files added for page functions------------}}
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-visit.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-medication.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-lab.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-charge.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-vital.js')}}"></script>
@endsection