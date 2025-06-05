 function patientDetailsUsingToken(token){
 console.log('toltoken');
}

let patientDetail = '';
     patientDetail = `<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-medium mb-0">OPD - Out Patient Details</h6>
        <div class="btns">
          <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-clipboard-line"></i> Move to IPD</button>
          <button class="btn btn-warning-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Export</button>
      </div>
    </div>
    <div class="card">
        <!-- <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="text-lg fw-normal mb-0">OPD - Out Details</h6>
            <div class="btns">
                <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-clipboard-line"></i> Move to IPD</button>
                <button class="btn btn-warning-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Export</button>
            </div>
        </div> -->
        <div class="card-body p-24 pt-10">
            <ul class="nav bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16 w-100" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 active fw-medium" id="Overview-tab" data-bs-toggle="pill" data-bs-target="#pills-Overview" type="button" role="tab" aria-controls="pills-Overview" aria-selected="true">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 fw-medium" id="pills-Visits-tab" data-bs-toggle="pill" data-bs-target="#pills-Visits" type="button" role="tab" aria-controls="pills-Visits" aria-selected="false">Visits</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 fw-medium" id="pills-Medication-tab" data-bs-toggle="pill" data-bs-target="#pills-Medication" type="button" role="tab" aria-controls="pills-Medication" aria-selected="false">Medication</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link px-16 py-10 fw-medium" id="pills-lab-tab" data-bs-toggle="pill" data-bs-target="#pills-lab" type="button" role="tab" aria-controls="pills-lab" aria-selected="false">Lab Investigations</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 fw-medium" id="pills-charges-tab" data-bs-toggle="pill" data-bs-target="#pills-charges" type="button" role="tab" aria-controls="pills-charges" aria-selected="false">Charges</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 fw-medium" id="pills-timeline-tab" data-bs-toggle="pill" data-bs-target="#pills-timeline" type="button" role="tab" aria-controls="pills-timeline" aria-selected="false">Timeline</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 fw-medium" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button" role="tab" aria-controls="pills-history" aria-selected="false">Vital History</button>
                  </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-Overview" role="tabpanel" aria-labelledby="Overview-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-4 p-3 border-end">
                          <h6 class="text-md fw-normal border-bottom pb-8">NIRAJ KUMAR</h6>
                            <div class="border-bottom pb-8">
                                <p class="mb-1">Sex : M</p>
                                <p class="mb-1">DOB : 24/08/1992 (32)</p>
                                <p class="mb-1">Guardian Name : Abhimanuo Jindal</p>
                                <p class="mb-1">phone : +91 1122 334 455</p>
                                <div class="d-flex"><p class="mb-1">Bar Code :</p><span><img src="backend/uploads/images/barcode.jpg" style="width: 100px;"></span></div>
                            </div>
                            <h6 class="text-md fw-medium mt-11 ">CONSULTANT DOCTOR</h6>
                            <p class="mb-1">Mohan Kumar Gupta</p>
                            <div class="d-flex"><p class="mb-0 mx-1">Finding :</p> 
                              <div class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Add Finding">
                                <i class="ri-add-box-line"></i>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-8 p-3">
                            <div class="mb-5">
                              <h6 class="text-md fw-medium">VISITS</h6>
                                <div class="table-responsive">
                                  <table class="table basic-table mb-0">
                                    <thead>
                                       <tr>
                                        <th class="fw-normal">Appointment Date</th>
                                        <th class="fw-normal">Consultant</th>
                                       </tr>
                                    </thead>
                                    <tbody>
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
                                <div class="table-responsive">
                                  <table class="table basic-table mb-0">
                                    <thead>
                                       <tr>
                                        <th scope="col" class="fw-normal">Date</th>
                                        <th scope="col" class="fw-normal">Medician Name</th>
                                        <th scope="col" class="fw-normal">Dose</th>
                                        <th scope="col" class="fw-normal">Time</th>
                                        <th scope="col" class="fw-normal">Remark</th>
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
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="mb-5">
                                <h6 class="text-md fw-medium">LAB INVESTIGATIONS</h6>
                                <div class="table-responsive">
                                  <table class="table basic-table mb-0">
                                    <thead>
                                       <tr>
                                        <th scope="col" class="fw-normal">Test</th>
                                        <th scope="col" class="fw-normal">Labs</th>
                                        <th scope="col" class="fw-normal">Sample coll</th>
                                        <th scope="col" class="fw-normal">Expected Date</th>
                                       </tr>
                                    </thead>
                                    <tbody>
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
                        <h6 class="text-md fw-medium mb-0">Dr. Visits</h6>
                        <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Log Dr.</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table basic-table mb-0">
                          <thead>
                             <tr>
                              <th class="fw-normal w-25">Date</th>
                              <th class="fw-normal w-25">Time</th>
                              <th class="fw-normal w-25">Consultant</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025 (Tue)</td>
                              <td>02:00 PM</td>
                              <td></td>
                             </tr>
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
                        <h6 class="text-md fw-medium mb-0">Medication</h6>
                        <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Add</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table basic-table mb-0">
                          <thead>
                             <tr>
                              <th class="fw-normal w-25">Date</th>
                              <th class="fw-normal w-25">Medician Name</th>
                              <th class="fw-normal w-25">Dose</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025 (Tue) <br> Created by : Super Admin (9001)</td>
                              <td>Alprovit</td>
                              <td class="d-flex">1 CT 
                                <div class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Add Dose" aria-describedby="tooltip435618">
                                  <i class="ri-add-box-line mx-4"></i>
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
                        <h6 class="text-md fw-medium mb-0">Lab Investigations</h6>
                        <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Add</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table basic-table mb-0">
                          <thead>
                             <tr>
                              <th class="fw-normal">Date</th>
                              <th class="fw-normal">Tast Name</th>
                              <th class="fw-normal">Sample Collection</th>
                              <th class="fw-normal">Expected Date</th>
                              <th class="fw-normal">Report</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025 (Tue)</td>
                              <td>CBC</td>
                              <td>Done</td>
                              <td>03/02/2025</td>
                              <td>
                                <div class="w-25 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Add Report" aria-describedby="tooltip435618">
                                  <i class="ri-add-box-line mx-4"></i>
                                </div>
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
                      <div class="mb-2 mb-11">
                        <h6 class="text-md fw-normal mb-0">Charges</h6>
                      </div>
                      <div class="table-responsive">
                        <table class="table basic-table mb-0">
                          <thead>
                             <tr>
                              <th class="fw-normal">Date</th>
                              <th class="fw-normal">Charge Type</th>
                              <th class="fw-normal">Charge Category</th>
                              <th class="fw-normal">Applied Charge</th>
                              <th class="fw-normal">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>
                                <div class="w-25 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Print bill" aria-describedby="tooltip435618">
                                  <i class="ri-printer-line"></i>
                                </div>
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
                    <div class="col-md-12 px-3"></div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab" tabindex="0">
                  <div class="row">
                    <div class="col-md-12 px-3">
                      <div class="mb-2 d-flex justify-content-between align-items-center mb-11">
                        <h6 class="text-md fw-medium mb-0">Vital History</h6>
                        <button class="btn btn-primary-600  btn-sm fw-normal"><i class="ri-file-pdf-2-line"></i> Add</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table basic-table mb-0">
                          <thead>
                             <tr>
                              <th class="fw-normal">Date</th>
                              <th class="fw-normal">Pluse</th>
                              <th class="fw-normal">Temp</th>
                              <th class="fw-normal">BP</th>
                              <th class="fw-normal">Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                              <td>04/02/2025</td>
                              <td>55-60</td>
                              <td>24 Cel</td>
                              <td> 120/80 mm Hg</td>
                              <td>
                                <div class="w-25 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-title="Edit Vital" aria-describedby="tooltip435618">
                                  <i class="ri-edit-box-line"></i>
                                </div>
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
    </div>`;
    $('.patientDetailsData').html(patientDetail);
