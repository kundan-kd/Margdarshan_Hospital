@extends('backend.admin.layouts.main')
@section('title')
Lead Analytics
@endsection
@section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-normal mb-0">Analytics</h6>
      <!-- <div class="d-flex flex-wrap align-items-center gap-2">
          <a href="create-bill.html" class="btn btn-primary-600 fw-normal  btn-sm d-flex align-items-center gap-1"> <i class="ri-add-line"></i> Create Bill</a>
          <button type="button" class="btn btn-warning-600 fw-normal  btn-sm d-flex align-items-center gap-2"> <i class="ri-file-pdf-2-line"></i> Export</button>
      </div> -->
      <!-- <div class="btns">
          <a class="btn btn-primary-600  btn-sm fw-medium mx-11" href="create-bill.html"><i class="ri-add-line mx-4 "></i>Create Bill</a>
          <button class="btn btn-warning-600  btn-sm fw-medium"><i class="ri-file-pdf-2-line mx-4 "></i> Export</button>
      </div> -->
  </div>
     <div class="row gy-3">
        <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Leads</p>
                <h6 class="mb-0">600</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +6%</span> 
              Last month
            </p>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Conversion</p>
                <h6 class="mb-0">6</h6>
              </div>
              <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                <!-- <iconify-icon icon="fa-solid:award" class="text-white text-2xl mb-0"></iconify-icon> -->
               <i class="ri-loop-left-line text-white text-2xl mb-0"></i>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +6%</span> 
              Last month
            </p>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Assigned Lead</p>
                <h6 class="mb-0">60</h6>
              </div>
              <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="fluent:people-20-filled" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +200</span>  -->
              TLD month
            </p>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Unassigned Lead</p>
                <h6 class="mb-0">10</h6>
              </div>
              <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                <!-- <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon> -->
                <i class="ri-admin-fill text-white text-2xl mb-0"></i>
              </div>
            </div>
            <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$20,000</span>  -->
              TLD month
            </p>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-6">
        <h6 class="fw-normal text-md mt-3">Due Followup</h6>
        <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Followup date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                           <td>Rahul</td>
                           <td>+91 1122334455</td>
                           <td>05/06/2025</td>
                        </tr>
                       <tr>
                           <td>Mukesh</td>
                           <td>+91 1122334455</td>
                           <td>08/06/2025</td>
                        </tr>
                          <tr>
                           <td>Anil</td>
                           <td>+91 1122334455</td>
                           <td>10/06/2025</td>
                        </tr>
                          <tr>
                           <td>Sumit</td>
                           <td>+91 1122334455</td>
                           <td>12/06/2025</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
      </div>
      <div class="col-md-6">
        <h6 class="fw-normal text-md mt-3">Today Followup</h6>
        <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Followup date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                           <td>Mohit</td>
                           <td>+91 1122334455</td>
                           <td>08/07/2025</td>
                        </tr>
                       <tr>
                           <td>Arjun</td>
                           <td>+91 1122334455</td>
                           <td>08/07/2025</td>
                        </tr>
                          <tr>
                           <td>Sanjay</td>
                           <td>+91 1122334455</td>
                           <td>08/07/2025</td>
                        </tr>
                          <tr>
                           <td>Sumita</td>
                           <td>+91 1122334455</td>
                           <td>08/07/2025</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
      </div>
     </div>
  </div>
@endsection
