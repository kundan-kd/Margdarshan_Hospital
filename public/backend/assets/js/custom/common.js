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
function validateField(id,inputType) {
    let fieldID = $("#" + id); // Get the ID as a string
    let fieldValue = fieldID.val(); // Get the value
    let error = $("." + id + "_errorCls"); // Dynamically get the error element
    error.removeClass('d-none');
    let label = document.querySelector(`label[for="${id}"]`);
    let field_txt = label ? label.textContent : ""; // Handle label presence
    if (fieldValue == "") {
        if (error.length) {
            error.html(field_txt + " is required").addClass("is_invalid");
        }
        return false;
    } else if (inputType == "mobile") { // Validate mobile number
        let isValid = /^[0-9]{10}$/.test(fieldValue);
        if (!isValid) {
            error.html("Must be 10 digits").addClass("is_invalid");
            return false;
        } else {
            error.html("");
            return true;
        }
    } else if (inputType == "select") { // Validate dropdown
      if (fieldValue != "") {
          error.html("");
          return true;
      } else {
          error.html("This field is required").addClass("is_invalid");
          return false;
      }
      }else if (inputType == "email") {
            let isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fieldValue);
            if (!isValid) {
                error.text("Invalid email format").addClass("is_invalid");
                return false;
            } else {
                fieldID.removeClass("is_field_invalid");
                error.html("");
                return true;
            }
        } else if (inputType == "pin") {
            let isValid = /^[0-9]{6}$/.test(fieldValue);
            if (!isValid) {
                error.text("Must be 6 digits").addClass("is_invalid");
                return false;
            } else {
                error.html("");
                return true;
            }
        } else if (inputType == "amount") {
            if (fieldValue != undefined && fieldValue != null) {
                let valueLength = fieldValue.length;
                if (valueLength < 1) {
                    error.text("Input Minimum 1 digit").addClass("is_invalid");
                    return false;
                }else if(fieldValue <= 0){
                    error.text("Amount Should be greater then 0").addClass("is_invalid");
                    return false;
                } else {
                    error.html("");
                    return true;
                }
            }
        } else {
            if (fieldValue != undefined && fieldValue != null) {
                let valueLength = fieldValue.length;
                if (valueLength < 3) {
                    error.text("Input Minimum 3 characters").addClass("is_invalid");
                    return false;
                } else {
                    error.html("");
                    return true;
                }
            }
        }

          return true; // Default return for valid input
      }
// Select2 Applied for search and dropdown similtanously
 $('.select2-cls').select2();
//  Select2 Applied for search and dropdown ends


// toast success alert start---------
function toastSuccessAlert(message){
  $('.toast-alert-success-msg').html('');
  $('.toast-alert-success-msg').html(message);
  var toastElement = document.getElementById('liveToastAlert');
  var toast = new bootstrap.Toast(toastElement);
  toast.show();
}
// toast alert ends---------
// toast warning alert start---------
function toastWarningAlert(message){
  $('.toast-alert-warning-msg').html('');
  $('.toast-alert-warning-msg').html(message);
  var toastElement = document.getElementById('liveToastWarningAlert');
  var toast = new bootstrap.Toast(toastElement);
  toast.show();
}
// toast alert ends---------
// toast failed alert start---------
function toastErrorAlert(message){
  $('.toast-alert-error-msg').html('');
  $('.toast-alert-error-msg').html(message);
  var toastElement = document.getElementById('liveToastErrorAlert');
  var toast = new bootstrap.Toast(toastElement);
  toast.show();
}
// toast alert ends---------

// Auto logout after 10 seconds inactivity
    // let idleTime = 0;

    // function resetTimer() {
    //     idleTime = 0; // Reset inactivity timer
    // }

    // function startIdleTimer() {
    //     idleTime++;
    //     if (idleTime >= 10) { // 10 seconds inactivity
    //         window.location.href = "/logout"; // Redirect to logout
    //     }
    // }

    // // Detect user activity
    // document.addEventListener("mousemove", resetTimer);
    // document.addEventListener("keypress", resetTimer);
    // document.addEventListener("click", resetTimer);

    // // Start the timer every second
    // setInterval(startIdleTimer, 1000);
// Auto logout after 10 seconds inactivity ends