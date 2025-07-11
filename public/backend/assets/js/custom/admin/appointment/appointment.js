
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
            data:'paid_status',
            name:'paid_status'
        },
        {
            data:'status',
            name:'status'
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },

    ],
     dom: 'Blfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'Appointment List',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            },
            className: 'd-none', // Hide the button using a Bootstrap utility class or custom CSS
            attr: {
                id: 'hiddenExcelBtn' // Give it an ID so we can trigger it
            }
        }
    ]
});
$('#excelBtn').on('click', function () {
    $('#hiddenExcelBtn').click(); // Trigger the hidden DataTables button
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
            $('.patientSubmit').addClass('d-none'); 
            $('.patientSpinn').removeClass('d-none');
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
                        $('.patientSpinn').addClass('d-none');
                        $('.patientSubmit').removeClass('d-none'); 
                    }else{
                        console.log('error found');
                        $('.patientSpinn').addClass('d-none');
                        $('.patientSubmit').removeClass('d-none'); 
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
    validateField('itemSearchInput', 'input');
    const nameLength = x.length;

    if (nameLength < 3) {
        $('.patient-name-list').addClass('d-none');
        $('#patientNameAppt').val('');
        $('.patient-name-list').empty();
    } else {
        $('.patient-name-list').removeClass('d-none');
        $('.patient-name-list').empty();

        $.ajax({
            url: searchPatient,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { name: x },
            success: function(response) {
                const getData = response.data;

                if (!getData || getData.length === 0) { 
                    $('.patient-name-list').append(`<li class="list-group-item">No Data Found!</li>`);
                } else {
                    const addedIds = new Set();
                    getData.forEach(element => {
                        if (!addedIds.has(element.id)) {
                            $('.patient-name-list').append(
                                `<li class="list-group-item" data-patient-id="${element.id}">${element.name} (${element.patient_id})</li>`
                            );
                            addedIds.add(element.id);
                        }
                    });
                }
            }
        });
    }
}

// function getPatientData(x) {
//     validateField('itemSearchInput','input');
//     let nameLength = x.length;
//     if(nameLength < 3){
//         $('.patient-name-list').addClass('d-none');
//         $('#patientNameAppt').val('');
//     }else{
//         $('.patient-name-list').removeClass('d-none');
//          $('.patient-name').empty();
//         $.ajax({
//             url: searchPatient, // Ensure this is a valid endpoint
//             type: "POST",
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             data: { name: x },
//             success: function(response) {
//                 let getData = response.data;
               
//                 if(getData == null || getData == ''){
//                      $('.patient-name').append(` <li class="list-group-item">No Data Found!</li>`);
//                 }else{
//                         getData.forEach(element => {
//                         $('.patient-name').append(` <li class="list-group-item" data-patient-id=${element.id}>${element.name} (${element.patient_id})</li>`);
//                     });
//                 }
             
//             }
//         });
//     }
// }
// run function getPatientDetails on click of patient name list
$(document).on('click', '.patient-name-list li', function() {
    let patientId = $(this).data('patient-id'); // Get the clicked patient's ID
    if(patientId != undefined){
        $('#itemSearchInput').val('');
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
                $('#itemSearchInput').val(getData.name+ ' ('+getData.patient_id+')');
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
function printAppointmentBill(id){
     window.open('appointment-bill-print/'+id,'_blank');
}
$('#appointmentForm').on('submit',function(e){
  e.preventDefault();
  let id = $('#patientNameApptID').val();
  let itemSearchInput = validateField('itemSearchInput', 'input');
  let depertmentAppt = validateField('departmentAppt', 'select');
  let doctorAppt = validateField('doctorAppt', 'select');
//   let paymentModeAppt = validateField('paymentModeAppt', 'select');
  let dateAppt = validateField('dateAppt', 'select');
  if(itemSearchInput === true && depertmentAppt === true && doctorAppt === true && true && dateAppt === true){
    $('.appointmentSubmitBtn').addClass('d-none'); 
    $('.appointmentSpinn').removeClass('d-none');
    let patientID = $('#patientNameApptID').val();
    let name = $('#patientNameAppt').val();
    let departmentID = $('#departmentAppt').val();
    let doctorID = $('#doctorAppt').val();
    let date = $('#dateAppt').val();
    // let pmode = $('#paymentModeAppt').val();
    let rnum = $('#roomNumApptId').val();
    let fee = $('#opd_fee').val();
    if ($('.appointmentUpdateBtn').is(':visible')) {
            updateAppointment(id); // Trigger update function when update btn is active
            } else {
                $.ajax({
                    url: appointmentBook,
                    type: "POST",
                    headers:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        patientID:patientID,name:name,departmentID:departmentID,doctorID:doctorID,date:date,rnum:rnum,fee:fee
                    },
                    success:function(response){
                        if(response.success){
                            $('#add-appointment').modal('hide');
                            $('#appointmentForm')[0].reset();
                            $('.patient-name').addClass('d-none');
                            $('.patient-notfound').addClass('d-none');
                            toastSuccessAlert('Appointment Booked successfully');
                            $('#appointment-book-table').DataTable().ajax.reload();
                            $('.appointmentSpinn').addClass('d-none');
                            $('.appointmentSubmitBtn').removeClass('d-none');
                        }else if(response.error_validation){
                            console.log(response.error_validation);
                            toastWarningAlert(response.error_validation);
                            $('.appointmentSpinn').addClass('d-none');
                            $('.appointmentSubmitBtn').removeClass('d-none');
                        }else{
                            toastErrorAlert('Something went wrong, please try again');
                            $('.appointmentSpinn').addClass('d-none');
                            $('.appointmentSubmitBtn').removeClass('d-none');
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(xhr.respnseText);
                        alert('An Error Occurred: '+error);
                    }
                });
            }

    }else{
        console.log("Please fill all required fields");
    }
});

// function appointmentEdit(id){
//     $.ajax({
//         url: getAppointmentData,
//         type:"POST",
//         headers:{
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data:{id:id},
//         success:function(response){
//             if(response.success){
//                 getData = response.data[0];
//                 $('#add-appointment').modal('show');
//                 $('#patientNameApptID').val(id);
//                 $('#add-appointmentLabel').html('Edit Appointment');
//                 $('.appointmentSubmitBtn').addClass('d-none');
//                 $('.appointmentUpdateBtn').removeClass('d-none');
//                 $('.itemSearchInput_errorCls').addClass('d-none');
//                 $('.departmentAppt_errorCls').addClass('d-none');
//                 $('.doctorAppt_errorCls').addClass('d-none');
//                 $('.dateAppt_errorCls').addClass('d-none');
//                 // $('.paymentModeAppt_errorCls').addClass('d-none');
//                 $('#patient-search').val(getData.patient_name);
//                 $('#searchPatientID').val(getData.patient_name);
//                 $('#patientNameAppt').val(getData.patient_name);
//                 $('#departmentAppt').val(getData.department_id).trigger('change');
//                 // $('#doctorAppt').val(getData.doctor_id).trigger('change');
//                 $('#dateAppt').val(getData.appointment_date);
//                 $('#paymentModeAppt').val(getData.payment_mode).trigger('change');
//                 // $('#roomNumAppt').val(getData.room_number);
//                 // $('#opd_fee').val(getData.fee);
//             }
//         }
//     });
// }
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
                $('#appointment-edit-modal').modal('show');
                $('#apptEdit_id').val(id);
                $('#apptEditPaymentAmt').val(getData.fee);
            }
        }
    });
}

function updatePayment(id){
let pay_amount = $('#apptEditPaymentAmt').val();
let payment_mode = $('#apptEditPaymentMode').val();
$('.apptEditSubmit').addClass('d-none')
$('.apptEditSpinn').removeClass('d-none');
        $.ajax({
            url: updateAppointmentData,
            type: "POST",
            data: {
                id:id,pay_amount:pay_amount,payment_mode:payment_mode
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#appointment-edit-modal').modal('hide');
                    toastSuccessAlert(response.success);
                    $('#appointment-book-table').DataTable().ajax.reload();
                    $('.apptEditSpinn').addClass('d-none');
                    $('.apptEditSubmit').removeClass('d-none')
                } else {
                    toastErrorAlert(response.error_success);
                    $('.apptEditSpinn').addClass('d-none');
                    $('.apptEditSubmit').removeClass('d-none')
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
}
function visitEdit(id){
    $('#appointment-visit-modal').modal('show');
    $('#apptVisit_id').val(id);
}
function updateVisit(id){
    let visit_date_check = validateField('apptVisitDate', 'select');
    if(visit_date_check == true){
    let visit_date = $('#apptVisitDate').val();
    $('.apptVisitSubmit').addClass('d-none')
    $('.apptVisitSpinn').removeClass('d-none');
        $.ajax({
            url: updateAppointmentVisitData,
            type: "POST",
            data: {
                id:id,visit_date:visit_date
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#appointment-visit-modal').modal('hide');
                    toastSuccessAlert(response.success);
                    $('#appointment-book-table').DataTable().ajax.reload();
                    $('.apptVisitSpinn').addClass('d-none');
                    $('.apptVisitSubmit').removeClass('d-none')
                } else {
                    toastErrorAlert(response.error_success);
                    $('.apptVisitSpinn').addClass('d-none');
                    $('.apptVisitSubmit').removeClass('d-none')
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log('Please fill mandatory fields');
    }
}

function deleteReason(id){
    $('#appointment-delete-modal').modal('show');
    $('#appt_id').val(id);
}
function reasonSubmitDelete(id){
     let reason = $('#apptdeleteReason').val();
     if(reason == ''){
         $('#apptdeleteReason').focus();
     }else{
        $.ajax({
        url: deleteAppointmentData,
        type: "POST",
        data: {
            id: id,reason:reason
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#appointment-delete-modal').modal('hide');
                $('#appointment-book-table').DataTable().ajax.reload();
                toastSuccessAlert(response.success);
            } else {
                toastErrorAlert(reason.error_success);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire("Error!", "An error occurred: " + error, "error");
        }
        });
     }
     
}
