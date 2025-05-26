 //-----Password Show Hide Js Start--------
 function initializePasswordToggle(toggleSelector) {
    $(toggleSelector).on('click', function() {
        $(this).toggleClass("ri-eye-off-line");
        var input = $($(this).attr("data-toggle"));
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
}
// Call the function
initializePasswordToggle('.toggle-password');
//------Password Show Hide Js End-----------

//------bootstrap form validation js start---------
(() => {
    'use strict'
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
  
        form.classList.add('was-validated')
      }, false)
    })
  })()
//--------bootstrap form validation js end----------
//--------login form process start--------------
$('#login-form').on('submit',function(e){
    e.preventDefault();
     $('.credentials-missmatch').addClass('d-none');
    let email = $('#email').val();
    let password = $('#password').val();
    if (this.checkValidity() === false) {
        e.stopPropagation();
    } else {
        $('.submit_btn').hide();
        $('.spinn_btn').show();
        $.ajax({
            url:login,
            type:"POST",
            headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{email:email,
                password:password},
            
            success:function(response){
                $('.spinn_btn').hide();
                $('.submit_btn').show();
                if (response.success) {
                    window.location.href = dashboard;
                }else if(response.error_success){
                    $('#login-form').removeClass('was-validated');
                    $('.credentials-missmatch').removeClass('d-none').html(response.error_success).addClass('text-danger');
                }else{
                    $('.toast-alert-error-msg').html('something went wrong');
                    var toastElement = document.getElementById('liveToastErrorAlert');
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                }
            }
        });
    }
    
});
//------login form process end--------------

function forgotpass(){
$('.login-form-cls').addClass('d-none');
$('.forgot-email-cls').removeClass('d-none');
$('.login-title1').html('Password Recovery');
$('.login-title2').addClass('d-none');
}

//-------forgot password email submit form starts-----
$('#forgotpass-email-form').on('submit',function(e){
    e.preventDefault();
     $('.email-error-cls').addClass('d-none');
    let email = $('#forgot-email').val();
    if(this.checkValidity() === false) {
        e.stopPropagation();
    }else{
        $('.email_submit_btn').hide();
        $('.email_spinn_btn').show();
        $.ajax({
            url: sendPasswordOtp,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{
                email:email
            },
            success:function(response){
                $('.email_spinn_btn').hide();
                $('.email_submit_btn').show();
                if(response.success){
                $('.forgot-email-cls').addClass('d-none');
                $('.forgot-otp-cls').removeClass('d-none');
                 $('.toast-alert-success-msg').html('OTP sent to your email successfully');
                    var toastElement = document.getElementById('liveToastAlert');
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                }else if(response.error_success){
                      $('#forgotpass-email-form').removeClass('was-validated');
                    $('.email-error-cls').removeClass('d-none').html(response.error_success).addClass('text-danger');
                }else{
                    $('.toast-alert-error-msg').html('something went wrong');
                    var toastElement = document.getElementById('liveToastErrorAlert');
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                }
            }
        });
    }
});
//-------forgot password email submit form ends-----
//-------forgot password OTP submit form starts-----
$('#forgotpass-otp-form').on('submit',function(e){
    e.preventDefault();
    let email = $('#forgot-email').val();
    let otp = $('#otp').val();
    if(otp == ''){
      $('#otp').focus();
    }else{
        $('.otp_submit_btn').hide();
        $('.otp_spinn_btn').show();
        $.ajax({
            url: verifyPasswwordOtp,
            method: "POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
               },
            data: {
                email: email,
                otp: otp
            },
            success:function(response) {
                console.log(response);
                if (response.success) {
                    setTimeout(function(){
                    $('.otp_spinn_btn').hide();
                    $('.otp_submit_btn').show();
                        $('.forgot-email-cls').addClass('d-none');
                        $('.forgot-otp-cls').addClass('d-none');
                        $('.newpass-form-cls').removeClass('d-none');
                    },500);
                }else{
                    setTimeout(function(){
                        $('.otp_spinn_btn').hide();
                        $('.otp_submit_btn').show();
                    },1000);
                }
            }
        });
    }
  });
//-------forgot password OTP submit form ends-----
//-------new password submit form starts-----
$('#newpass-form').on('submit',function(e){
    e.preventDefault();
    let email = $('#forgot-email').val();
    let npass = $('#new-pass').val();
    let cnpass = $('#confirm-new-pass').val();
    if(npass == ''){
      $('#new-pass').focus();
    }else if(cnpass == ''){
      $('#confirm-new-pass').focus();
    }else{
        $('.new_submit_btn').hide();
        $('.new_spinn_btn').show();
        $.ajax({
            url: updateNewPassword,
            method: "POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
               },
            data: {
                email: email,
                pass: npass,
                cpass: cnpass
            },
            success: function(response) {
                if (response.success) {
                    setTimeout(function(){
                    $('.new_spinn_btn').hide();
                    $('.new_submit_btn').show();
                    $('.login-title1').html('Log In to your Account');
                    $('.login-title2').removeClass('d-none');
                    $('.newpass-form-cls').addClass('d-none');
                    $('.login-form-cls').removeClass('d-none');
                },500);
                } else {
                    setTimeout(function(){
                    $('.new_spinn_btn').hide();
                    $('.new_submit_btn').show();
                },1000);
                }
            }
        });
    }
  });
//-------new password submit form ends-----