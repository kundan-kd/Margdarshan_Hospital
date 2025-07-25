let table = $('#patient-table').DataTable({
    // responsive:true,
    processing: true,
    serverSide:true,
    ajax:{
        url: viewPatients,
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
            data:'name',
            name:'name'
        },
         {
            data:'type',
            name:'type'
        },
        {
            data:'guardian_name',
            name:'guardian_name',
            orderable: false,
            searchable: true
        },
        {
            data:'gender',
            name:'gender'
        },
        {
            data:'bloodtype',
            name:'bloodtype',
            orderable: false,
            searchable: true
        },
        {
            data:'dob',
            name:'dob',
            orderable: false,
            searchable: true
        },
        {
            data:'mobile',
            name:'mobile',
            orderable: false,
            searchable: true
        },
        {
            data:'created_at',
            name:'created_at'
        },
        {
            data:'curr_status',
            name:'curr_status',
            orderable: false,
            searchable: true
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: true
        }

    ],
 dom: 'Blfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'All Patient List',
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9,10]
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

function resetPatientAddPatient(){
    $('#patient-addPatientForm')[0].reset();
    $('#patient-add-appointmentLabel').html('Add Patient');
    $('#patient-patientId').val('');
    $('.patient-patientName_errorCls').addClass('d-none');
    $('.patient-guardianName_errorCls').addClass('d-none');
    $('.patient-patientBloodType_errorCls').addClass('d-none');
    $('.patient-patientDOB_errorCls').addClass('d-none');
    $('.patient-patientMStatus_errorCls').addClass('d-none');
    $('.patient-patientMobile_errorCls').addClass('d-none');
    $('.patient-patientAddess_errorCls').addClass('d-none');
    $('.patientAddPatientUpdate').addClass('d-none');
    $('.patientAddPatientSubmit').removeClass('d-none');
}
function getPatientDetailsOpd(mobile){
    if(mobile.length >= 10){
        // console.log(mobile);
        $('.patient-data-list-opd').empty();
        $('.patient-data-list-opd').removeClass('d-none');
        $.ajax({
            url: getPatientDataUsingMobile,
            type:"POST",
            headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{mobile:mobile},
             success: function(response) {
                const getData = response.data;
                // if (!getData || getData.length === 0) { 
                //     $('.patient-data-list-opd').append(`<li class="list-group-item">No Data Found!</li>`);
                // } else {
                //     const addedIds = new Set();
                //     getData.forEach(element => {
                //         if (!addedIds.has(element.id)) {
                //             $('.patient-data-list-opd').append(
                //                 `<li class="list-group-item" data-patient-id="${element.id}">${element.name} (${element.patient_id})</li>`
                //             );
                //             addedIds.add(element.id);
                //         }
                //     });
                // }
                if (!getData) {
                    $('.patient-data-list-opd').append(`<li class="list-group-item">No Data Found!</li>`);
                } else {
                    $('.patient-data-list-opd').append(
                        `<li class="list-group-item" data-patient-id="${getData.id}">${getData.name} (${getData.patient_id})</li>`
                    );
                }
            },
            error:function(xhr,error){
                console.log(xhr.responseText);
                alert('An error occured: '+error);
            }
        });
    }else{
        $('.patient-data-list-opd').empty();
        $('.patient-data-list-opd').addClass('d-none');
        // console.log('10 Digit number required!');
    }
}
$(document).on('click', '.patient-data-list-opd li', function() {
    let patientId = $(this).data('patient-id'); // Get the clicked patient's ID
    if(patientId != undefined){
        // $('#itemSearchInput').val('');
        fillPatientFieldsOpd(patientId); // Pass the ID to the function
    }
});
function fillPatientFieldsOpd(id){
    $.ajax({
        url: fillPatientData, // Ensure this is a valid endpoint
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id:id },
        success: function(response) {
            if(response.success){
                $('.patient-data-list-opd').addClass('d-none');
                let getData = response.data[0];
                $('#patient-PatientId').val(getData.id);
                $('#patient-patientName').val(getData.name);
                $('#patient-guardianName').val(getData.guardian_name);
                $('#patient-patientBloodType').val(getData.bloodtype).change();
                $('#patient-patientDOB').val(getData.dob);
                $('#patient-patientMStatus').val(getData.marital_status).change();
                $('#patient-patientMobile').val(getData.mobile);
                $('#patient-patientAddess').val(getData.address);
                $('#patient-patientAllergy').val(getData.known_allergies);
                $('input[name="patient-patientGender"]').each(function() {
                if ($(this).val() === getData.gender) {
                    $(this).prop('checked', true);
                }
                });
        
            }
        
    }
    });
}
$(document).on('click','#patient-add-patient .modal-body',function(){
    $('.patient-data-list-opd').addClass('d-none');
});
$('#patient-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let id = $('#patient-patientId').val(); 
    let patientName  = validateField('patient-patientName', 'input');
    let guardianName = validateField('patient-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('patient-patientBloodType', 'select');
    let patientDOB = validateField('patient-patientDOB', 'select');
    let patientMStatus = validateField('patient-patientMStatus', 'select');     
    let patientMobile = validateField('patient-patientMobile', 'mobile');
    let patientAddess = validateField('patient-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
            $('.patientAddPatientSubmit').addClass('d-none'); 
            $('.patientAddPatientSpinn').removeClass('d-none');
            let name = $('#patient-patientName').val();
            let guardian_name = $('#patient-guardianName').val();
            let gender = $('input[name="patient-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#patient-patientBloodType').val();
            let dob = $('#patient-patientDOB').val();
            let mstatus = $('#patient-patientMStatus').val();
            let mobile = $('#patient-patientMobile').val();
            let address = $('#patient-patientAddess').val();
            let alt_mobile = $('#patient-patientAltMobile').val();
            let allergy = $('#patient-patientAllergy').val();
            if ($('.patientAddPatientUpdate').is(':visible')) {
            patientAddPatientUpdate(id); // Trigger update function when update btn is active
            } else {
                $.ajax({
                    url: patientAddNewPatient,
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
                            $('#patient-add-patient').modal('hide');
                            $('#patient-table').DataTable().ajax.reload();
                            $('.patientAddPatientSpinn').addClass('d-none'); 
                            $('.patientAddPatientSubmit').removeClass('d-none'); 
                        }else if(response.alreadyFound){
                            toastErrorAlert(response.alreadyFound);
                            $('.patientAddPatientSpinn').addClass('d-none'); 
                            $('.patientAddPatientSubmit').removeClass('d-none'); 
                        }else{
                            console.log('error found');
                        }
                    },
                    error:function(xhr, status, error){
                        console.log(xhr.respnseText);
                        alert('An error occurred: '+error);
                    }
                });
            }
        }else{
            console.log("Please fill all required fields");
        }    
});
function patientNewEdit(id){
     $.ajax({
        url: newPatientData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('#patient-add-appointmentLabel').html('Edit OPD Patient');
                $('.patientAddPatientSubmit').addClass('d-none');
                $('.patientAddPatientUpdate').removeClass('d-none');
                $('#patient-add-patient').modal('show');
                $('#patient-patientId').val(id);
                $('#patient-patientName').val(getData.name);
                $('#patient-guardianName').val(getData.guardian_name);
                $('#patient-patientBloodType').val(getData.bloodtype);
                $('#patient-patientDOB').val(getData.dob);
                $('#patient-patientMStatus').val(getData.marital_status);
                $('#patient-patientMobile').val(getData.mobile);
                $('#patient-patientAddess').val(getData.address);
                $('#patient-patientAltMobile').val(getData.alt_mobile);
                $('#patient-patientAllergy').val(getData.known_allergies);

                $('input[name="patient-patientGender"]').each(function() {
                if ($(this).val() === getData.gender) {
                    $(this).prop('checked', true);
                }
        });

            }
        }
    });
}
function patientAddPatientUpdate(id){
    let patientName  = validateField('patient-patientName', 'input');
    let guardianName = validateField('patient-guardianName', 'input');
    let patientBloodType = validateField('patient-patientBloodType', 'select');
    let patientDOB = validateField('patient-patientDOB', 'select');
    let patientMStatus = validateField('patient-patientMStatus', 'select');     
    let patientMobile = validateField('patient-patientMobile', 'mobile');
    let patientAddess = validateField('patient-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
            $('.patientAddPatientUpdate').addClass('d-none'); 
            $('.patientAddPatientSpinn').removeClass('d-none'); 
            let name = $('#patient-patientName').val();
            let guardian_name = $('#patient-guardianName').val();
            let gender = $('input[name="patient-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#patient-patientBloodType').val();
            let dob = $('#patient-patientDOB').val();
            let mstatus = $('#patient-patientMStatus').val();
            let mobile = $('#patient-patientMobile').val();
            let address = $('#patient-patientAddess').val();
            let alt_mobile = $('#patient-patientAltMobile').val();
            let allergy = $('#patient-patientAllergy').val();
            $.ajax({
                url: patientAddNewPatientDataUpdate,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                id:id,name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('Patient updated successfully');
                        $('#patient-add-patient').modal('hide');
                        $('#patient-table').DataTable().ajax.reload();
                        $('.patientAddPatientSpinn').addClass('d-none'); 
                        $('.patientAddPatientUpdate').removeClass('d-none'); 
                    }else{
                         toastErrorAlert('error found');
                        $('.patientAddPatientSpinn').addClass('d-none'); 
                        $('.patientAddPatientUpdate').removeClass('d-none'); 
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
}
function  patientNewDelete(id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            title: 'swal-title-custom'
            }
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deletePatientData,
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
                        $('#patient-table').DataTable().ajax.reload();
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