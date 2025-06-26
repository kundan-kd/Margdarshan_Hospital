
 function reopenAppointment(){
   $('#add-appointment').modal('show');
 }
let table = $('#appointment-book-table').DataTable({
    // responsive:true,
    processing: true,
    serverSide:true,
    ajax:{
        url: viewAppointments,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr, error, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
         {
            data:'patient_id',
            name:'patient_id'
        },
        {
            data:'patient_name',
            name:'patient_name'
        },
        {
            data:'appointment_date',
            name:'appointment_date'
        },
        {
            data:'mobile',
            name:'mobile'
        },
        {
            data:'gender',
            name:'gender'
        },
        {
            data:'doctor',
            name:'doctor'
        },
        {
            data:'fee',
            name:'fee'
        },
        {
            data:'status',
            name:'status',
            orderable: false,
            searchable: false
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },

    ]
});

$('#addPatientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('patientName', 'input');
    let guardianName = validateField('guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('patientBloodType', 'select');
    let patientDOB = validateField('patientDOB', 'select');
    let patientMStatus = validateField('patientMStatus', 'select');     
    let patientMobile = validateField('patientMobile', 'mobile');
    let patientAddess = validateField('patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
            let name = $('#patientName').val();
            let guardian_name = $('#guardianName').val();
            let gender = $('input[name="patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#patientBloodType').val();
            let dob = $('#patientDOB').val();
            let mstatus = $('#patientMStatus').val();
            let mobile = $('#patientMobile').val();
            let address = $('#patientAddess').val();
            let alt_mobile = $('#patientAltMobile').val();
            let allergy = $('#patientAllergy').val();
            $.ajax({
                url: addNewPatient,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New Patient added successfully');
                        $('#add-patient').modal('hide');
                        $('#add-appointment').modal('show');
                    }else{
                        console.log('error found');
                    }
                },
                error:function(xhr, status, error){
                    console.log(xhr.respnseText);
                    alert('An error occurred: '+error);
                }
            });
        }else{
            console.log("Please fill all required fields");
        }    
});
function resetAppointmentForm(){
    $('#appointmentForm')[0].reset();
    $('.patient-name').removeClass('d-none');
    $('.patient-notfound').removeClass('d-none');
    $('#add-appointmentLabel').html('Book Appointment');
    $('.appointmentUpdateBtn').addClass('d-none');
    $('.appointmentSubmitBtn').removeClass('d-none');
    $('.itemSearchInput_errorCls').addClass('d-none');
    $('.departmentAppt_errorCls').addClass('d-none');
    $('.doctorAppt_errorCls').addClass('d-none');
    $('.dateAppt_errorCls').addClass('d-none');
    $('.paymentModeAppt_errorCls').addClass('d-none');
    $('#departmentAppt').val('').trigger('change');
    $('#doctorAppt').val('').trigger('change');
    $('#paymentModeAppt').val('').trigger('change');
}
function resetAddPatient(){
    $('#addPatientForm')[0].reset();
    $('.patientName_errorCls').addClass('d-none');
    $('.guardianName_errorCls').addClass('d-none');
    $('.patientBloodType_errorCls').addClass('d-none');
    $('.patientDOB_errorCls').addClass('d-none');
    $('.patientMStatus_errorCls').addClass('d-none');
    $('.patientMobile_errorCls').addClass('d-none');
    $('.patientAddess_errorCls').addClass('d-none');
}

function getPatientData(x) {
    validateField('itemSearchInput','input');
    let nameLength = x.length;
    if(nameLength < 3){
        $('.patient-name-list').addClass('d-none');
        $('#patientNameAppt').val('');
    }else{
        $('.patient-name-list').removeClass('d-none');
         $('.patient-name').empty();
        $.ajax({
            url: searchPatient, // Ensure this is a valid endpoint
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { name: x },
            success: function(response) {
                let getData = response.data;
               
                if(getData == null || getData == ''){
                     $('.patient-name').append(` <li class="list-group-item">No Data Found!</li>`);
                }else{
                        getData.forEach(element => {
                        $('.patient-name').append(` <li class="list-group-item" data-patient-id=${element.id}>${element.name} (${element.patient_id})</li>`);
                    });
                }
             
            }
        });
    }
}
// run function getPatientDetails on click of patient name list
$(document).on('click', '.patient-name li', function() {
    let patientId = $(this).data('patient-id'); // Get the clicked patient's ID
    if(patientId != undefined){
      getPatientDetails(patientId); // Pass the ID to the function
    }
});

function getPatientDetails(id){
    $.ajax({
        url: getPatient, // Ensure this is a valid endpoint
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id:id },
        success: function(response) {
            if(response.success){
                let getData = response.data[0];
                let idd = 'MHPT'+ getData.id;
                $('#patientNameAppt').val(getData.name);
                $('#patientNameApptID').val(getData.id); //appended patient id for insert in book appointment table
                $('.patient-name-list').addClass('d-none');
            }
        
        }
    });
}

function getDocRoomNum(id){
    $.ajax({
        url: getDoctorData, // Ensure this is a valid endpoint
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id:id },
        success: function(response) {
            if(response.data !=''){
                if(response.success){
                    $('#roomNumAppt').val(response.roomNum[0].room_num);
                    $('#roomNumApptId').val(response.roomNum[0].id); // Store room number ID for later use
                    $('#opd_fee').val(response.data[0].fee);
                }
            }
        
        }
    });
}
function getDoctorAdded(id){
    setTimeout(function(){

    
    $.ajax({
        url: getDoctorAddedData, // Ensure this is a valid endpoint
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id:id },
        success: function(response) {
            if(response.success){
               $('#doctorAppt').append(`<option selected value="${response.data[0].doctor_id}">${response.doctorData[0].name}</option>`);
               
                $('#roomNumAppt').val(response.roomNum[0].room_num);
                $('#opd_fee').val(response.doctorData[0].fee);

            }
        
        }
    });
    },700);
}
function getDoctor(){
    let departmentID = $('#departmentAppt').val();
    if(departmentID != ''){
        $.ajax({
            url: getDoctorList,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { departmentID: departmentID },
            success: function(response) {
                if(response.success){
                    $('#doctorAppt').empty();
                    $('#doctorAppt').append('<option value="">Select Doctor</option>');
                    response.data.forEach(function(doctor) {
                        $('#doctorAppt').append(`<option value="${doctor.id}">${doctor.name}</option>`);
                    });
                    $('#doctorAppt').trigger('change'); // Refresh Select2 dropdown
                }else{
                    console.log('No doctors found for this department');
                }
            },
            error:function(xhr, status, error){
                console.log(xhr.respnseText);
                alert('An Error Occurred: '+error);
            }
        });
    }else{
        $('#doctorAppt').empty();
        $('#doctorAppt').append('<option value="">Select Department First</option>');
        $('#doctorAppt').trigger('change'); // Refresh Select2 dropdown
    }
}
function opdPatientUsingId(id){
     window.open('opd-out-details/' + id, '_blank');
}
$('#appointmentForm').on('submit',function(e){
  e.preventDefault();
  let itemSearchInput = validateField('itemSearchInput', 'input');
  let depertmentAppt = validateField('departmentAppt', 'select');
  let doctorAppt = validateField('doctorAppt', 'select');
  let paymentModeAppt = validateField('paymentModeAppt', 'select');
  let dateAppt = validateField('dateAppt', 'select');
  if(itemSearchInput === true && depertmentAppt === true && doctorAppt === true && paymentModeAppt && true && dateAppt === true){
    let patientID = $('#patientNameApptID').val();
    let name = $('#patientNameAppt').val();
    let departmentID = $('#departmentAppt').val();
    let doctorID = $('#doctorAppt').val();
    let date = $('#dateAppt').val();
    let pmode = $('#paymentModeAppt').val();
    let rnum = $('#roomNumApptId').val();
    let fee = $('#opd_fee').val();
        $.ajax({
            url: appointmentBook,
            type: "POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{
                patientID:patientID,name:name,departmentID:departmentID,doctorID:doctorID,date:date,pmode:pmode,rnum:rnum,fee:fee
            },
            success:function(response){
                if(response.success){
                    $('#add-appointment').modal('hide');
                    $('#appointmentForm')[0].reset();
                    $('.patient-name').addClass('d-none');
                    $('.patient-notfound').addClass('d-none');
                    toastSuccessAlert('Appointment Booked successfully');
                    $('#appointment-book-table').DataTable().ajax.reload();
                }else if(response.error_validation){
                    console.log(response.error_validation);
                    toastWarningAlert(response.error_validation);
                }else{
                    toastErrorAlert('Something went wrong, please try again');
                }
            },
            error:function(xhr, status, error){
                console.log(xhr.respnseText);
                alert('An Error Occurred: '+error);
            }
        });

    }else{
        console.log("Please fill all required fields");
    }
});

function appointmentEdit(id){
    $.ajax({
        url: getAppointmentData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                getData = response.data[0];
                $('#add-appointment').modal('show');
                $('#patientNameApptID').val(id);
                $('#add-appointmentLabel').html('Edit Appointment');
                $('.appointmentSubmitBtn').addClass('d-none');
                $('.appointmentUpdateBtn').removeClass('d-none');
                $('.itemSearchInput_errorCls').addClass('d-none');
                $('.departmentAppt_errorCls').addClass('d-none');
                $('.doctorAppt_errorCls').addClass('d-none');
                $('.dateAppt_errorCls').addClass('d-none');
                $('.paymentModeAppt_errorCls').addClass('d-none');
                $('#patient-search').val(getData.patient_name);
                $('#searchPatientID').val(getData.patient_name);
                $('#patientNameAppt').val(getData.patient_name);
                $('#departmentAppt').val(getData.department_id).trigger('change');
                // $('#doctorAppt').val(getData.doctor_id).trigger('change');
                $('#dateAppt').val(getData.appointment_date);
                $('#paymentModeAppt').val(getData.payment_mode).trigger('change');
                // $('#roomNumAppt').val(getData.room_number);
                // $('#opd_fee').val(getData.fee);
            }
        }
    });
}

function updateAppointment(id){
let name = validateField('patientNameAppt', 'input');
let depertmentAppt = validateField('departmentAppt', 'select');
  let doctorAppt = validateField('doctorAppt', 'select');
  let paymentModeAppt = validateField('paymentModeAppt', 'select');
  let dateAppt = validateField('dateAppt', 'select');
  if(name === true && depertmentAppt === true && doctorAppt === true && paymentModeAppt && true && dateAppt === true){

    let patientsearch = $('#patient-search').val();
    let patientsearchGet = $('#searchPatientID').val();
    let name = $('#patientNameAppt').val();
    let departmentID = $('#departmentAppt').val();
    let doctorID = $('#doctorAppt').val();
    let aDate = $('#dateAppt').val();
    let pmode = $('#paymentModeAppt').val();
  
        $.ajax({
            url: updateAppointmentData,
            type: "post",
            data: {
                id:id,name:name,departmentID:departmentID,doctorID:doctorID,aDate:aDate,pmode:pmode
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#add-appointment').modal('hide');
                    $('#appointmentForm')[0].reset();
                    $('#appointmentForm').removeClass('was-validated');
                    toastSuccessAlert('Appointment Updated successfully');
                    $('#appointment-book-table').DataTable().ajax.reload();
                } else {
                    alert("error");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log("Please fill all required fields");
    }
}
function appointmenttDelete(id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            title: 'swal-title-custom',
            popup: 'swal-popup-custom',
            content: 'swal-content-custom'
            }
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteAppointmentData,
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#appointment-book-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire("Error!", "An error occurred: " + error, "error");
                }
            });
        }
    });
}
