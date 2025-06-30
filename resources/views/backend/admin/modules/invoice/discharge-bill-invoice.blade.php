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
 <div class="card " >
            <div class=" border radius-8" style="min-height: 100vh;">
              <div class="p-20 d-flex flex-wrap justify-content-between gap-3 border-bottom">
                <div>
                  <h3 class="text-xl fw-normal">Invoice #4257</h3>
                  <p class="mb-1 text-sm">Date Issued: 19/06/2025</p>
                  <p class="mb-1 text-sm">Appointment ID : #APPO1234</p>
                  <p class="mb-1 text-sm">Checkup No : 85</p>
                </div>
                <div>
                  <img src="{{asset('backend/assets/images/logo.png')}}" alt="invoice logo"  class="mb-8" style="width: 180px;height: auto;">
                  <p class="mb-1 text-sm fw-medium">Patna,Bihar</p>
                  <p class="mb-0 text-sm fw-medium">example@gmail.com, <br> +91 1122 334 455</p>
                </div>
              </div>
              <div class="py-28 px-20 ">
               <h6 class="text-xl fw-normal">Booking Details</h6>
                <table class="table  table-borderless table-sm payment-pharmacy-table">
                  <tbody>
                <tr> 
                    <th class="fw-medium text-sm text-neutral-600">Appointment No</th>
                    <td class="text-sm text-neutral-600">APO123456</td>    
                    <th class="fw-medium text-sm text-neutral-600">Appointment Date:</th>
                    <td class="text-sm text-neutral-600"> 19/06/2025 </td>
                    
                </tr>
                <tr>
                    <th class="fw-medium text-sm text-neutral-600">Patient Name</th>
                    <td class="text-sm text-neutral-600">Rahul Kumar</td>
                    <th class="fw-medium text-sm text-neutral-600">Appointment Priority</th>
                    <td class="text-sm text-neutral-600">Normal</td>
                </tr>
                <tr>
                   <th class="fw-medium text-sm text-neutral-600">Age</th>
                    <td class="text-sm text-neutral-600">24 Years</td>
                    <th class="fw-medium text-sm text-neutral-600">Gender</th>
                    <td class="text-sm text-neutral-600">Male</td>
                </tr>
                <tr>
                    <th class="fw-medium text-sm text-neutral-600">Email</th>
                    <td class="text-sm text-neutral-600">aman@gmail.com</td>
                   <th class="fw-medium text-sm text-neutral-600">Phone</th>
                    <td class="text-sm text-neutral-600">+91 1234567890</td> 
                </tr>
                <tr>     
                    <th class="fw-medium text-sm text-neutral-600">Appointment Date:</th>
                    <td class="text-sm text-neutral-600"> 19/06/2025 </td>
                    <th class="fw-medium text-sm text-neutral-600">Appointment Priority</th>
                    <td class="text-sm text-neutral-600">Normal</td>
                </tr>
                <tr> 
                    <th class="fw-medium text-sm text-neutral-600">Doctor</th>
                    <td class="text-sm text-neutral-600">Aman Kumar</td>    
                    <th class="fw-medium text-sm text-neutral-600">Department</th>
                    <td class="text-sm text-neutral-600">OT</td>             
                </tr>
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">Shift</th>
                    <td class="text-sm text-neutral-600">Morning</td>
                    <th class="fw-medium text-sm text-neutral-600">Slot</th>
                    <td class="text-sm text-neutral-600">6:00 AM - 9:00 AM</td>
                </tr>
                  <tr>    
                     <th class="fw-medium text-sm text-neutral-600">Payment Mode</th>
                    <td class="text-sm text-neutral-600">Online</td>    
                    <th class="fw-medium text-sm text-neutral-600">Collected By</th>
                    <td class="text-sm text-neutral-600">Super Admin</td>  
                </tr>
                <tr>    
                     <th class="fw-medium text-sm text-neutral-600">Status</th>
                    <td class="text-sm text-neutral-600">Approved</td>    
                    <th class="fw-medium text-sm text-neutral-600">Address</th>
                    <td class="text-sm text-neutral-600">Patna</td>  
                </tr>
            </tbody>
            </table>

                <div class="mt-24">
                  <div class="table-responsive scroll-sm">
                    <h6 class="text-xl fw-normal">Payment Details</h6>
                    <table class="table bordered-table text-sm">
                      <thead>
                        <tr>
                          <th scope="col" class="text-sm">Transaction ID </th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm">Source</th>
                          <th scope="col" class="text-sm text-end">Amount (₹)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>TRANS123456</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td>Online</td>
                          <td class="text-end">2000.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="d-flex flex-wrap justify-content-between gap-3">
                    <div>
                      <p class="text-sm mb-0"><span class="text-primary-light fw-semibold">Sales By :</span>  Mohan Kumar</p>
                      <p class="text-sm mb-0">Thanks for your visit</p>
                    </div>
                    <div>
                      <table class="text-sm">
                        <tbody>
                          <tr>
                            <td class="pe-64 fw-medium pb-1">Subtotal:</td>
                            <td class="pe-11 text-end pb-1">2000.00</td>
                          </tr>
                          <tr>
                            <td class="pe-64 fw-medium pb-1">Discount:</td>
                            <td class="pe-11 text-end pb-1">0.00</td>
                          </tr>
                          <tr>
                            <td class="pe-64 fw-medium pb-1 ">Tax (%)</td>
                             <td class="pe-11 text-end pb-1">5.00</td>
                          </tr>
                          <tr>
                            <td class="pe-64 fw-medium pb-1">Total (₹) </td>
                            <td class="pe-11 text-end pb-1">2050.00</td>
                          </tr>
                             <tr>
                            <td class="pe-64 fw-medium pb-1">Paid Amount (₹) </td>
                            <td class="pe-11 text-end pb-1">1050.00 </td>
                          </tr>
                           <tr>
                            <td class="pe-64 fw-medium pb-1">Refund Amount (₹)</td>
                            <td class="pe-11 text-end pb-1">0.00</td>
                          </tr>
                          <tr>
                            <td class="pe-64 fw-medium pb-1">Due Amount (₹)</td>
                            <td class="pe-11 text-end pb-1">1000.00  </td>
                          </tr>
                     
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                {{-- <div class="mt-64">
                  <p class="text-center text-secondary-light text-sm fw-semibold">Thank you for Visit !</p>
                </div> --}}

                <div class="d-flex flex-wrap justify-content-between align-items-end mt-50">
                    <div class="text-sm border-top d-inline-block px-12">Signature of Customer</div> 
                   <div class="text-sm border-top d-inline-block px-12">Signature of Authorized</div> 
                </div>
              </div>
            </div>
          </div>
@endsection