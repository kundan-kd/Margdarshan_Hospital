  @extends('backend.admin.layouts.main')
  @section('title')
  my profile
  @endsection
  @section('main-container')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
  <h6 class="fw-normal mb-0">View Profile</h6>

</div>

        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                    <img src="{{asset('backend/assets/images/user-grid-bg1.png')}}" alt="" class="w-100 object-fit-cover">
                    <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                        <div class="text-center border border-top-0 border-start-0 border-end-0">
                          <img src="{{asset('backend/assets/images/user-grid/user-grid-img14.png')}}" alt="" class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                            <h6 class="mb-0 mt-16">{{$user->name}}</h6>
                            <span class="text-secondary-light mb-16">{{$user->email}}</span>
                        </div>
                        <div class="mt-24">
                            <h6 class="text-xl mb-16">Personal Info</h6>
                            <ul>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user->name}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user->email}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Phone Number</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user->mobile}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Department</span>
                                    <span class="w-70 text-secondary-light fw-medium">: {{$user->department_id}}</span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Designation</span>
                                    <span class="w-70 text-secondary-light fw-medium">: Admin</span>
                                </li>
                                <li class="d-flex align-items-center gap-1">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Bio</span>
                                    <span class="w-70 text-secondary-light fw-medium">: An admin oversees operations, manages staff and resources, ensures efficiency, implements policies, and maintains seamless workflows for organizational success.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile 
                              </button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab" aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Change Password 
                              </button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                              <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                                Notification Settings
                              </button>
                            </li> --}}
                        </ul>

                        <div class="tab-content" id="pills-tabContent">   
                            <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab" tabindex="0">
                                <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                <!-- Upload Image Start -->
                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                            <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                                <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Upload Image End -->
                                <form action="#">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Name <span class="text-danger-600">*</span></label>
                                                <input type="text" class="form-control radius-8" id="name" placeholder="Enter Full Name" value="{{$user->name}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                                <input type="email" class="form-control radius-8" id="email" placeholder="Enter email address" value="{{$user->email}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                                <input type="email" class="form-control radius-8" id="number" placeholder="Enter phone number" value="{{$user->mobile}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="depart" class="form-label fw-semibold text-primary-light text-sm mb-8">Department <span class="text-danger-600">*</span> </label>
                                                <select class="form-control radius-8 form-select" id="depart">
                                                <option value="cardiology">Cardiology</option>
                                                <option value="neurology">Neurology</option>
                                                <option value="orthopedics">Orthopedics</option>
                                                <option value="pediatrics">Pediatrics</option>
                                                <option value="radiology">Radiology</option>
                                                <option value="dermatology">Dermatology</option>
                                                <option value="psychiatry">Psychiatry</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="desig" class="form-label fw-semibold text-primary-light text-sm mb-8">Designation <span class="text-danger-600">*</span> </label>
                                                <select class="form-control radius-8 form-select" id="desig">
                                                   <option value="doctor">Doctor</option>
                                                    <option value="nurse">Nurse</option>
                                                    <option value="administrator">Administrator</option>
                                                    <option value="technician">Technician</option>
                                                    <option value="receptionist">Receptionist</option>
                                                    <option value="pharmacist">Pharmacist</option>
                                                    <option value="therapist">Therapist</option>    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-20">
                                                <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                                                <textarea name="#0" class="form-control radius-8" id="desc" placeholder="Write description..." ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8"> 
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8" disabled> 
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" aria-labelledby="pills-change-passwork-tab" tabindex="0">
                                <div class="mb-20">
                                    <label for="your-password" class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8" id="your-password" placeholder="Enter New Password*">
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                    </div>
                                </div>
                                <div class="mb-20">
                                    <label for="confirm-password" class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed Password <span class="text-danger-600">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control radius-8" id="confirm-password" placeholder="Confirm Password*">
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-password"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
                                <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company News</span>
                                        <input class="form-check-input" type="checkbox" role="switch" id="companzNew">
                                    </div>
                                </div>
                                <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <label for="pushNotifcation" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Push Notification</span>
                                        <input class="form-check-input" type="checkbox" role="switch" id="pushNotifcation" checked>
                                    </div>
                                </div>
                                <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <label for="weeklyLetters" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Weekly News Letters</span>
                                        <input class="form-check-input" type="checkbox" role="switch" id="weeklyLetters" checked>
                                    </div>
                                </div>
                                <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <label for="meetUp" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Meetups Near you</span>
                                        <input class="form-check-input" type="checkbox" role="switch" id="meetUp">
                                    </div>
                                </div>
                                <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                    <label for="orderNotification" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                    <div class="d-flex align-items-center gap-3 justify-content-between">
                                        <span class="form-check-label line-height-1 fw-medium text-secondary-light">Orders Notifications</span>
                                        <input class="form-check-input" type="checkbox" role="switch" id="orderNotification" checked>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    @endsection