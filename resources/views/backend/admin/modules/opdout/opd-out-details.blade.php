@extends('backend.admin.layouts.main')
@section('title')
    OPD - Out Details
@endsection
@section('extra-css')
<style>
</style>
@endsection
@section('main-container')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">OPD - Out Patient Details</h6>
        <div class="d-flex flex-wrap align-items-center gap-2">
          <button type="button" class="btn btn-primary-600 fw-normal btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#out-patient-ipd"> <i class="ri-stethoscope-line"></i></i> Move to IPD</button>
          {{-- <button type="button" class="btn btn-warning-600 fw-normal btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button> --}}
          <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#out-patient-ipd"><i class="ri-stethoscope-line"></i> Move to IPD</button>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line"></i> Export</button> -->
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
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 " id="pills-timeline-tab" data-bs-toggle="pill" data-bs-target="#pills-timeline" type="button" role="tab" aria-controls="pills-timeline" aria-selected="false">Timeline</button>
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
                              $doctors =  \App\Models\User::where('id',$appointments[0]->doctor_id)->get();
                            @endphp
                            <h6 class="text-md fw-medium mt-11 border-bottom pb-8">CONSULTANT DOCTOR</h6>
                            <p class="mb-1">{{$doctors[0]->firstname}} {{$doctors[0]->lastname}}</p>
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
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Niraj Kumar</td>
                                       </tr>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Niraj Kumar</td>
                                       </tr>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Niraj Kumar</td>
                                       </tr>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Niraj Kumar</td>
                                       </tr>
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
                                        <th scope="col" class="fw-medium">Time</th>
                                        <th scope="col" class="fw-medium">Remark</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Alprovit</td>
                                        <td>1 CT</td>
                                        <td>02:00 PM</td>
                                        <td>Non</td>
                                       </tr>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Alprovit</td>
                                        <td>1 CT</td>
                                        <td>02:00 PM</td>
                                        <td>Non</td>
                                       </tr>
                                       <tr>
                                        <td>04/02/2025</td>
                                        <td>Alprovit</td>
                                        <td>1 CT</td>
                                        <td>02:00 PM</td>
                                        <td>Non</td>
                                       </tr>
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
                                        <th scope="col" class="fw-medium">Expected Date</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                        <td>CBC</td>
                                        <td>Lal Path</td>
                                        <td>Done</td>
                                        <td>05/02/2025 (Wed)</td>
                                       </tr>
                                       <tr>
                                        <td>CBC</td>
                                        <td>Lal Path</td>
                                        <td>Done</td>
                                        <td>05/02/2025 (Wed)</td>
                                       </tr>
                                       <tr>
                                        <td>CBC</td>
                                        <td>Lal Path</td>
                                        <td>Done</td>
                                        <td>05/02/2025 (Wed)</td>
                                       </tr>
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-new-checkup"> <i class="ri-add-line"></i> New Checkup</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="opd-out-visit-list" data-page-length='10'>
                          <thead>
                             <tr>
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#opd-add-medication-dose"> <i class="ri-add-line"></i> Add Medication Dose</button>
                        <!-- <button class="btn btn-primary-600  btn-sm fw-medium" data-bs-toggle="modal" data-bs-target="#ipd-add-medication"><i class="ri-add-line"></i> Add Medication</button> -->
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table mb-0 table-sm" id="opdOutVisit-medicineDoseList">
                          <thead>
                             <tr>
                              <th class="fw-medium w-25">Date</th>
                              <th class="fw-medium w-25">Medician Name</th>
                              <th class="fw-medium w-25">Dose</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025 (Tue) <br> Created by : Super Admin (9001)</td>
                              <td>Alprovit</td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <span class="mx-11">1 CT </span>
                                  <button class=" mx-1 w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#opd-add-medication-dose">
                                    <i class="ri-add-line"></i>
                                  </button>
                                </div>
                              </td>
                             </tr>
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"  data-bs-toggle="modal" data-bs-target="#opd-add-lab"> <i class="ri-add-line"></i> Add Lab</button>
                      </div>
                      <div class="card basic-data-table">
                            <table class="table bordered-table mb-0 w-100" id="opd-lab-reports-list" data-page-length='10'>
                                  <thead>
                                    <tr >
                                      <th scope="col" class="fw-medium">Tast Name</th>
                                      <th scope="col" class="fw-medium">Lab</th>
                                      <th scope="col" class="fw-medium">Date</th>
                                      <th scope="col" class="fw-medium">Sample Collection</th>
                                      <th scope="col" class="fw-medium">Expected Date</th>
                                      <th scope="col" class="fw-medium">Approved By</th>
                                      <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td ><span class="text-nowrap">Abodoman X-ray <br> (AX)</span> </td>
                                          <td>Pathology</td>
                                          <td>18/05/2025</td>
                                          <td>Sunil Kumar (9876) <br> <span class="text-nowrap">Pathology Center : In-House Pathology Lab</span><br> 19/05/2025</td>
                                          <td>22/05/2025</td>
                                          <td>Rakesh Kumar <br> 22/05/2025</td>
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
                          <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-add-charges"> <i class="ri-add-line"></i> Add Charges</button>
                        </div>
                      <div class="table-responsive">
                        <table class="table  striped-table mb-0 table-sm">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium">Charge Type</th>
                              <th class="fw-medium">Charge Category</th>
                              <th class="fw-medium">Applied Charge</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>05/04/2023</td>
                              <td>OPD</td>
                              <td>Intensive Care Units</td>
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
                        <button type="button" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#opd-add-vital-history"> <i class="ri-add-line"></i> Add Vital History</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table striped-table mb-0 table-sm">
                          <thead>
                             <tr>
                              <th class="fw-medium">Date</th>
                              <th class="fw-medium"> Height <br>(1 - 200 Centimeters)</th>
                             <th class="fw-medium">Weight <br>(0 - 150 Kilograms)</th>
                              <th class="fw-medium">Pluse <br>(70 - 100 Beats per)</th>
                              <th class="fw-medium">	Temperature <br>(95.8 - 99.3 Fahrenheit )</th>
                              <th class="fw-medium">BP <br>(90/60 - 140/90 mmHg)</th>
                              <th class="fw-medium">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025</td>
                              <td>150 ( 03:00 PM)</td>
                              <td>80kg ( 12:55 PM) </td>
                              <td>55-60</td>
                              <td>24 Cel</td>
                              <td> 120/80 mm Hg</td>
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
                      <td>
                        <select class="form-control form-control-sm" >
                              <option value="">Select</option>
                              <option value="3">Pulse  (70 -   100  Beats per)</option>
                              <option value="4">Temperature (95.8  -  99.3 Fahrenheit )</option>
                              <option value="5">BP (90/60  -  140/90 mmHg)</option>
                          </select>
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-vital-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                    <tr class="add-vital-fieldGroupCopy" style="display: none;">
                      <td>
                        <select class="form-control form-control-sm" >
                              <option value="">Select</option>
                              <option value="3">Pulse  (70 -   100  Beats per)</option>
                              <option value="4">Temperature (95.8  -  99.3 Fahrenheit )</option>
                              <option value="5">BP (90/60  -  140/90 mmHg)</option>
                          </select>
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <input type="text" class="form-control form-control-sm" >
                      </td>
                      <td>
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-vital-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                 </table>
                 <button class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center add-vital-addMore">
                      <i class="ri-add-line"></i> Add
                  </button>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Add vital History end -->

<!-- edit vital History Start -->
<div class="modal fade" id="opd-edit-vital-history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-edit-vital-historyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-edit-vital-historyLabel"> Edit Vital</h6>
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
                              <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="05/22/2025 03:55 PM" readonly="readonly">
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
                              <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
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
                              <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
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
                              <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
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
                              <input class="form-control radius-8 bg-base medication-date flatpickr-input active" type="text" placeholder="04/22/2025 03:55 PM" readonly="readonly">
                              <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                 </table>
        </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- edit vital History end -->

<!-- Add charges Start -->
<div class="modal fade" id="opd-add-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-chargesLabel"> Add Charges</h6>
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
                    <input class="form-control radius-8 bg-base expiry-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
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
<!-- Add charges History end -->

<!-- edit charges Start -->
<div class="modal fade" id="opd-edit-charges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-edit-chargesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-edit-chargesLabel"> Edit Charges</h6>
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
                    <input class="form-control radius-8 bg-base expiry-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
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

<!-- Add add-lab Start -->
<div class="modal fade" id="opd-add-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-add-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-add-labLabel">Add Test Details</h6>
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
                 <table class="pharmacy-purchase-bill-table table table-hover mb-11 add-test-feilds add-lab-table">
                   <thead>
                          <tr class="border-bottom">
                            <th class="text-nowrap text-neutral-700">Test Parameter Name <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Reference Range <sup class="text-danger">*</sup></th>
                            <th class="text-nowrap text-neutral-700">Unit <sup class="text-danger">*</sup></th>
                          </tr>
                  </thead>
                  <tbody>
                    <tr class="add-lab-fieldGroup">
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
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-lab-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                    <tr class="add-lab-fieldGroupCopy" style="display: none;">
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
                        <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-lab-remove">
                            <i class="ri-close-line"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                 </table>
                 <button class="mx-1 fw-normal w-60-px h-32-px bg-primary-light text-primary-600 rounded d-inline-flex align-items-center justify-content-center add-lab-addMore">
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
<!-- Add add-lab end -->

<!-- lab-test-veiw start -->
<div class="modal fade" id="opd-lab-test-veiw" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-lab-test-veiwLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-lab-test-veiwLabel">Abodoman X-ray (AX)</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr>
                    <th class="fw-medium">Bill No</th>
                    <td>	PATH65497</td>
                    <th class="fw-medium">Patient</th>
                    <td>Aman Kumar (1234)</td>
                </tr>
                <tr>
                   <th class="fw-medium">Approve Date</th>
                    <td>22/05/2025</td>
                    <th class="fw-medium">Report Collection Date</th>
                    <td>05/12/2025</td>
                </tr>
                <tr>     
                    <th class="fw-medium">Test Name</th>
                    <td>Abodoman X-ray (AX)</td>
                    <th class="fw-medium">Expected Date</th>
                    <td>22/05/2025</td>
                </tr>
                <tr>           
                   <th class="fw-medium">Collection By</th>
                    <td>Sunil Kumar (9876)</td>
                    <th class="fw-medium">Pathology Center</th>
                    <td>In-House Pathology Lab</td>
                </tr>
                <tr>    
                     <th class="fw-medium">Case ID</th>
                    <td>7144</td>    
                    <th class="fw-medium">Approved By</th>
                    <td>Rakesh Kumar</td>  
                </tr>
            </tbody>
        </table>
          </div>
        </div>
        <h6 class="fw-medium text-md text-center mt-5" >Abodoman X-ray  (AX)</h6>
        <table class="table  table-borderless table-sm payment-pharmacy-table">
               <thead>
                 <tr>
                   <th>#</th>
                   <th class="fw-medium">Test Parameter Name</th>
                   <th class="fw-medium text-nowrap">Report Value</th>
                   <th class="fw-medium">Report Reference</th>
                 </tr>
               </thead>
               <tbody>
                <tr>
                  <td>1</td>
                  <td>Liver Function Test <br><span class="text-xs">Description: Liver function tests (LFTs or LFs), also referred to as a hepatic panel, are groups of blood tests ... ranges are given, these will vary depending on age, gender and his/her health, ethnicity, method of analysis, and units of measurement.</span> </td>
                  <td>25 (U/L)</td>
                  <td class="text-nowrap">7 to 55 units per liter (U/L)</td>
                </tr>
               </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- lab-test-veiw end -->

<!-- Edit lab-detail Start -->
 <div class="modal fade" id="opd-edit-lab" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-edit-labLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-edit-labLabel">Edit Test Details</h6>
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
          <button type="submit" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Submit</button>
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

<!--  opd edit checkup Start -->
 <div class="modal fade" id="opd-edit-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-edit-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-edit-checkupLabel">Edit Patient Details</h6>
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
              <label class="form-label fw-medium">Appointment Date<sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control radius-8 bg-base opd-add-admission-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
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

<!--  opd new checkup Start -->
 {{-- <div class="modal fade" id="opd-new-checkup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="opd-new-checkupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header p-11 bg-primary-500">
        <h6 class="modal-title fw-normal text-md text-white" id="opd-new-checkupLabel">Patient Details</h6>
        <button type="button" class="btn-close text-sm btn-custom" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row">
        <div class="col-md-6 pt-3">
          <div class="row">
             <table class="table table-borderless pharmacy-bill-detail-table w-75 mb-5">
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
             <div class="col-md-6 mb-3">
               <label class="form-label fw-medium">Symptoms Type</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Cough</option>
              </select>
             </div>
             <div class="col-md-6 mb-3">
               <label class="form-label fw-medium">Symptoms Title</label>
               <select class="form-select form-select-sm  " >
                 <option selected>Select</option>
              </select>
             </div>
             <div class="col-md-6 mb-3">
               <label class="form-label fw-medium">Symptoms Description</label>
               <textarea  class="form-control " rows="1" placeholder="Symptoms Description"></textarea>
             </div>
             <div class="col-md-6 mb-3">
               <label class="form-label fw-medium">Previous Medical Issue</label>
               <textarea  class="form-control " rows="1" placeholder="Previous Medical Issue"></textarea>
             </div>
             <div class="col-md-12 mb-3">
               <label class="form-label fw-medium">Note</label>
               <textarea  class="form-control " rows="2" placeholder="Note"></textarea>
             </div>
          </div>
        </div>
        <div class="col-md-6 bg-info-50 pt-3">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Admission Date <sup class="text-danger">*</sup></label>
              <div class=" position-relative">
                    <input class="form-control radius-8 bg-base opd-add-admission-date flatpickr-input active" type="text" placeholder="12/2024" readonly="readonly">
                    <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Case</label>
              <input type="text" class="form-control form-control-sm" placeholder="Case">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Casualty</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
               <label class="form-label fw-medium">Old Patient</label>
              <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
            <!-- <div class="col-md-6 mb-3">
             <label class="form-label fw-medium"> Credit Limit (₹) <sup class="text-danger">*</sup></label>
              <input type="number" class="form-control form-control-sm" placeholder="200000">
            </div> -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Reference</label>
              <input type="text" class="form-control form-control-sm" placeholder="Reference">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Consultant Doctor <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
                 <option value="1">Sunil Kumar (1234)</option>
                 <option value="1">Manoj Gupta (2224)</option>
                 <option value="1">Arjun Kumar (2234)</option>
                 <option value="1">Suraj Kumar (9234)</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
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
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Charge <sup class="text-danger">*</sup></label>
               <select class="form-select form-select-sm select2" >
                 <option selected>Select</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Applied Charge (₹) <sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Applied Charge">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Discount %<sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Discount ">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Tax %<sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Discount ">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Amount (₹) <sup class="text-danger">*</sup></label>
               <input type="number" class="form-control form-control-sm" placeholder="Amount ">
            </div>
            <div class="col-md-6 mb-3">
             <label class="form-label fw-medium"> Payment Mode</label>
               <select class="form-select form-select-sm" id="payment-method">
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="cheque">Cheque</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3" style="display: none;" id="upi">
              <label class="form-label fw-medium ">UPI</label>
              <select class="form-select form-select-sm" id="upi-number">
                <option selected="">Select</option>
                <option value="upi-reference-number">Google Pay</option>
                <option value="upi-reference-number">Phone Pay</option>
                <option value="upi-reference-number">Airtel Pay</option>
              </select> 
            </div>
            
            <div class="col-md-6 mb-3" style="display: none;" id="card">
              <label class="form-label fw-medium ">Card Number</label>
              <input type="number" class="form-control form-control-sm" placeholder="Enter Card Number">
            </div>
            <div class="col-md-6 mb-3 cheque" style="display: none;">
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
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium"> Live Consultation</label>
               <select class="form-select form-select-sm select2" >
                 <option selected>No</option>
                 <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary-600  btn-sm fw-normal mx-2"> <i class="ri-checkbox-circle-line"></i> Save</button>
      </div>
    </div>
  </div>
</div> --}}
<!-- opd new checkup end -->

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
  const opdOutVisitMedicineName = "{{route('common.getMedicineName')}}";

  const opdOutVisitSubmit = "{{route('opd-out-visit.opdOutVisitSubmit')}}";
  const viewOptOutVisit = "{{route('opd-out-visit.viewOptOutVisit')}}";
  const getOpdOutVisitData = "{{route('opd-out-visit.getOpdOutVisitData')}}";
  const getOpdOutVisitDetails = "{{route('opd-out-visit.getOpdOutVisitData')}}";
  const opdOutVisitDataUpdate = "{{route('opd-out-visit.opdOutVisitDataUpdate')}}";
  const opdOutVisitDataDelete = "{{route('opd-out-visit.opdOutVisitDataDelete')}}";

  const opdOutMedDataAdd = "{{route('opd-out-med.opdOutMedDataAdd')}}";
  //  -- select2 js library included for dropdown search and select box.. other method for implenting used due to boostrap conflicts--
  window.addEventListener('load', () => {
    $('.select2-cls').select2({
    dropdownParent: $('#opd-add-medication-dose')
    });
  });
</script>
 {{-----------external js files added for page functions------------}}
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-visit.js')}}"></script>
    <script src="{{asset('backend/assets/js/custom/admin/opdout/opdout-details/opdout-details-medication.js')}}"></script>
@endsection