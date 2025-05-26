<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Margdarsahn Hospital-Log In</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{asset('backend/assets/images/favicon.png')}}" sizes="16x16">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/remixicon.css')}}">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/bootstrap.min.css')}}">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/apexcharts.css')}}">
  <!-- Data Table css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/dataTables.min.css')}}">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor-katex.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor.atom-one-dark.min.css')}}">
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/editor.quill.snow.css')}}">
  <!-- Date picker css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/flatpickr.min.css')}}">
  <!-- Calendar css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/full-calendar.css')}}">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/jquery-jvectormap-2.0.5.css')}}">
  <!-- Popup css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/magnific-popup.css')}}">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/slick.css')}}">
  <!-- prism css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/prism.css')}}">
  <!-- file upload css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/file-upload.css')}}">
  
  <link rel="stylesheet" href="{{asset('backend/assets/css/lib/audioplayer.css')}}">
  <!-- main css -->
  <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">
  <style>
      .toggle-password {
                right: 25px;
                top: 30px;
       }
  </style>
</head>
  <body>
  {{-- toast alert included --}}
    @include('backend.alert.toast')

<section class="auth bg-base d-flex flex-wrap">  
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{asset('backend/assets/images/auth/auth-img.png')}}" alt="">
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="index.html" class="mb-40 max-w-290-px">
                    <img src="{{asset('backend/assets/images/logo.png')}}" alt="">
                </a>
                <h4 class="mb-12 fw-normal login-title1">Log In to your Account</h4>
                <p class="mb-32 text-secondary-light text-lg login-title2">Welcome back! please enter your detail</p>
            </div>
            {{-- login form starts --}}
            <form id="login-form" class="row g-3 needs-validation login-form-cls" novalidate >
                @csrf
                <div class="icon-field mb-10">
                    <input id="email" type="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Valid Email Id
                    </div>
                </div>
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <input id="password" type="password" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Password" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter Login Password
                        </div>
                        <div class="credentials-missmatch"></div>
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer position-absolute translate-middle-y text-secondary-light" data-toggle="#password"></span>
                </div>
                <div class="">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="javascript:void(0)" class="text-primary-600 fw-medium" onclick="forgotpass()">Forgot Password?</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32 submit_btn"> Log In</button>
                <button class="btn btn-primary w-100 spinn_btn" style="display:none;" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Please wait...</span>
                </button>
                @if(session('login-error'))
                    <p class="text-danger">{{ session('login-error') }}</p>
                @endif
            </form>
            <br>
            {{-- login form ends --}}
            {{-- forgot password email form starts --}}
            <form id="forgotpass-email-form" class="row g-3 needs-validation forgot-email-cls d-none" novalidate>
                @csrf
                <div class="icon-field mb-10">
                    <input id="forgot-email" type="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Enter Email ID" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Your Valid Email Id
                    </div>
                    <div class="email-error-cls"></div>
                </div>
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32 email_submit_btn">Submit</button>
                <button class="btn btn-primary w-100 email_spinn_btn" style="display:none;" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Please wait...</span>
                </button>
            </form>
             {{-- forgot password email form ends --}}
              {{-- forgot password OTP form starts --}}
            <form id="forgotpass-otp-form" class="row g-3 needs-validation forgot-otp-cls d-none" novalidate>
                @csrf
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <input id="otp" type="number" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Enter OTP" style="background-image: none;" required>
                        <div class="invalid-feedback">
                            Enter OTP Received On Email
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32 otp_submit_btn">Submit</button>
                <button class="btn btn-primary w-100 otp_spinn_btn" style="display:none;" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Please wait...</span>
                </button>
            </form>
            {{-- forgot password form OTP form ends --}}
            {{-- new password form starts --}}
            <form id="newpass-form" class="row g-3 needs-validation newpass-form-cls d-none" novalidate>
                @csrf
                <div class="icon-field mb-10">
                    <input id="new-pass" type="text" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Enter New Password" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter New Password
                    </div>
                </div>
                <div class="icon-field mb-20">
                    <input id="confirm-new-pass" type="text" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Confirm New Password" style="background-image: none;" required>
                    <div class="invalid-feedback">
                        Enter Confirm Password
                    </div>
                </div>
                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32 new_submit_btn">Submit</button>
                <button class="btn btn-primary w-100 new_spinn_btn" style="display:none;" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Please wait...</span>
                </button>
            </form>
            {{-- new password form ends --}}
        </div>
    </div>
</section>

  <!-- jQuery library js -->
  <script src="{{asset('backend/assets/js/lib/jquery-3.7.1.min.js')}}"></script>
  <!-- Bootstrap js -->
  <script src="{{asset('backend/assets/js/lib/bootstrap.bundle.min.js')}}"></script>
  <!-- Apex Chart js -->
  <script src="{{asset('backend/assets/js/lib/apexcharts.min.js')}}"></script>
  <!-- Data Table js -->
  <script src="{{asset('backend/assets/js/lib/dataTables.min.js')}}"></script>
  <!-- Iconify Font js -->
  <script src="{{asset('backend/assets/js/lib/iconify-icon.min.js')}}"></script>
  <!-- jQuery UI js -->
  <script src="{{asset('backend/assets/js/lib/jquery-ui.min.js')}}"></script>
  <!-- Vector Map js -->
  <script src="{{asset('backend/assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
  <script src="{{asset('backend/assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
  <!-- Popup js -->
  <script src="{{asset('backend/assets/js/lib/magnifc-popup.min.js')}}"></script>
  <!-- Slick Slider js -->
  <script src="{{asset('backend/assets/js/lib/slick.min.js')}}"></script>
  <!-- prism js -->
  <script src="{{asset('backend/assets/js/lib/prism.js')}}"></script>
  <!-- file upload js -->
  <script src="{{asset('backend/assets/js/lib/file-upload.js')}}"></script>
  <!-- audioplayer -->
  <script src="{{asset('backend/assets/js/lib/audioplayer.js')}}"></script>
  <!-- main js -->
  <script src="{{asset('backend/assets/js/app.js')}}"></script>
  {{-- route path for ajax url for auth.js --}}
  <script>
    const login = "{{route('auth.login')}}";
    const dashboard = "{{route('auth.dashboard')}}";
    const sendPasswordOtp = "{{route('auth.send-pass-otp')}}";
    const verifyPasswwordOtp = "{{route('auth.verify-pass-otp')}}";
    const updateNewPassword = "{{route('auth.new-pass-update')}}";
  </script>
  {{-----------external js added for page functions------------}}
  <script src="{{asset('backend/assets/js/custom/auth/auth.js')}}"></script>
</body>
</html>
