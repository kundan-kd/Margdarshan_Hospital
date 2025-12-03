  @extends('backend.admin.layouts.main')
  @section('title')
  Dashboard
  @endsection
  @section('main-container')
  <div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-normal mb-0">Dashboard</h6>
    </div>
    <div class="row gy-3">
        @can('Dashboard Appointments')
        <div class="col-md-3">
          <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                  <p class="fw-medium text-primary-light mb-1">Total Patients</p>
                  <h6 class="mb-0">{{$tot_patients ?? 0}}</h6>
                </div>
                <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                  <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
                </div>
              </div>
            </div>
          </div><!-- card end -->
        </div>
        <div class="col-md-3">
          <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                  <p class="fw-medium text-primary-light mb-1">Appointments</p>
                  <h6 class="mb-0">{{$appointments ?? 0}}</h6>
                </div>
                <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                  <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
                </div>
              </div>
            </div>
          </div><!-- card end -->
        </div>
        @endcan
        @can('Dashboard OPD')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">OPD - Out Patient</p>
                <h6 class="mb-0">{{$appointments ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard IPD')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">IPD - In Patient</p>
                <h6 class="mb-0">{{$ipd_patients ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">ICU - In Patient</p>
                <h6 class="mb-0">{{$icu_patients ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Emergency')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Emergency Patient</p>
                <h6 class="mb-0">{{$emergency_patients ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Doctors')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Doctors</p>
                <h6 class="mb-0">{{$doctors ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Appointments')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Lab Test</p>
                <h6 class="mb-0">{{$total_lab_report ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Report Generated</p>
                <h6 class="mb-0">{{$report_generated ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Total Bill')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-5 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Bill Amount</p>
                <h6 class="mb-0"> ₹ {{$total_income ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Today Pharmacy Bill')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Today Pharmacy Bill</p>
                <h6 class="mb-0">₹ {{$today_pharmacy_bill ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Total Pharmacy Bill')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-5 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Pharmacy Bill</p>
                <h6 class="mb-0">₹ {{$total_pharmacy_bill ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
              <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
          </div>
        </div><!-- card end -->
      </div>
      @endcan
      @can('Dashboard Leads Data')
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Leads</p>
                <h6 class="mb-0">{{$leads ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            {{-- <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +6%</span> 
              Last month
            </p> --}}
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Conversion</p>
                <h6 class="mb-0">{{$convertedLeads ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                <!-- <iconify-icon icon="fa-solid:award" class="text-white text-2xl mb-0"></iconify-icon> -->
              <i class="ri-loop-left-line text-white text-2xl mb-0"></i>
              </div>
            </div>
            {{-- <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +6%</span> 
              Last month
            </p> --}}
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Assigned Lead</p>
                <h6 class="mb-0">{{$assignLeads ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="fluent:people-20-filled" class="text-white text-2xl mb-0"></iconify-icon>
              </div>
            </div>
            {{-- <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +200</span>  -->
              TLD month
            </p> --}}
          </div>
        </div><!-- card end -->
      </div>
      <div class="col-md-3">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
          <div class="card-body p-20">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
              <div>
                <p class="fw-medium text-primary-light mb-1">Unassigned Lead</p>
                <h6 class="mb-0">{{$UnAssignLeads ?? 0}}</h6>
              </div>
              <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                <!-- <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon> -->
                <i class="ri-admin-fill text-white text-2xl mb-0"></i>
              </div>
            </div>
            {{-- <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
              <!-- <span class="d-inline-flex align-items-center gap-1 text-success-main"><iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon> +$20,000</span>  -->
              TLD month
            </p> --}}
          </div>
        </div><!-- card end -->
      </div>
      </div>
      <div class="row">
      <div class="col-md-6">
        <h6 class="fw-normal text-md mt-3">Due Followup</h6>
        <div class="table-responsive scroll-sm" style="max-height: 500px; overflow-y: auto;">
                    <table class="table bordered-table sm-table mb-0">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Mobile</th>
                          <th scope="col">Followup date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($duefollowup as $dfollow)
                        <tr>
                          <td>{{$dfollow->name}}</td>
                          <td>{{$dfollow->mobile}}</td>
                          <td>
                            {{ $dfollow->next_followup_date ? date('d/m/Y', strtotime($dfollow->next_followup_date)) : 'NA' }}
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
      </div>
      <div class="col-md-6">
        <h6 class="fw-normal text-md mt-3">Today Followup</h6>
        <div class="table-responsive scroll-sm" style="max-height: 500px; overflow-y: auto;">
            <table class="table bordered-table sm-table mb-0">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">Followup date</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($todayFollowup as $tfollow)
                <tr>
                  <td>{{$tfollow->name}}</td>
                  <td>{{$tfollow->mobile}}</td>
                  <td>
                    {{ $tfollow->next_followup_date ? date('d/m/Y', strtotime($tfollow->next_followup_date)) : 'NA' }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
      </div>
      </div>
      @endcan
    </div>
  <!--</div>-->
</div>
@endsection
  