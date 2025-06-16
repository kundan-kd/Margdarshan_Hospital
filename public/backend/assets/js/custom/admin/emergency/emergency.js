let table_patient_emergency = $('#emergency-patient-list').DataTable({
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
            data:'allergies',
            name:'allergies',
            orderable: false,
            searchable: true
        },
        {
            data:'status',
            name:'status',
            orderable: true,
            searchable: true
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: true
        },
    ]
});

function resetPatient(){
    $('#emergency-add-patientLabel').html('Add Patient');
    $('#emergencyPatientId').val('');
    $('.emergencyPatientUpdate').addClass('d-none');
    $('.emergencyPatientSubmit').removeClass('d-none');
}
$('#emergency-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('emergency-patientName', 'input');
    let guardianName = validateField('emergency-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('emergency-patientBloodType', 'select');
    let patientDOB = validateField('emergency-patientDOB', 'select');
    let patientMStatus = validateField('emergency-patientMStatus', 'select');     
    let patientMobile = validateField('emergency-patientMobile', 'mobile');
    let patientAddess = validateField('emergency-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
            let name = $('#emergency-patientName').val();
            let guardian_name = $('#emergency-guardianName').val();
            let gender = $('input[name="emergency-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#emergency-patientBloodType').val();
            let dob = $('#emergency-patientDOB').val();
            let mstatus = $('#emergency-patientMStatus').val();
            let mobile = $('#emergency-patientMobile').val();
            let address = $('#emergency-patientAddess').val();
            let alt_mobile = $('#emergency-patientAltMobile').val();
            let allergy = $('#emergency-patientAllergy').val();
            $.ajax({
                url: addPatient,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New emergency Patient added successfully');
                        $('#emergency-add-patient').modal('hide');
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
function emergencyPatientEdit(id){
 $.ajax({
        url: getEmergencyPatientData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            if(response.success){
               let getData = response.data[0];
                $('#emergency-add-patientLabel').html('Edit Nurse Note');
                $('.emergencyPatientSubmit').addClass('d-none');
                $('.emergencyPatientUpdate').removeClass('d-none');
                $('#emergency-add-patient').modal('show');
                $('#emergencyPatientId').val(id);
                $('#emergency-patientName').val(getData.name);
                $('#emergency-guardianName').val(getData.guardian_name);
                $('#emergency-patientBloodType').val(getData.bloodtype);
                $('#emergency-patientDOB').val(getData.dob);
                $('#emergency-patientMStatus').val(getData.marital_status);
                $('#emergency-patientMobile').val(getData.mobile);
                $('#emergency-patientAddess').val(getData.address);
                $('#emergency-patientAltMobile').val(getData.alt_mobile);
                $('#emergency-patientAllergy').val(getData.known_allergies);
            }
        }
    });
}
function emergencyPatientUpdate(id){
     let patientName  = validateField('emergency-patientName', 'input');
    let guardianName = validateField('emergency-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('emergency-patientBloodType', 'select');
    let patientDOB = validateField('emergency-patientDOB', 'select');
    let patientMStatus = validateField('emergency-patientMStatus', 'select');     
    let patientMobile = validateField('emergency-patientMobile', 'mobile');
    let patientAddess = validateField('emergency-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
            let name = $('#emergency-patientName').val();
            let guardian_name = $('#emergency-guardianName').val();
            let gender = $('input[name="emergency-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#emergency-patientBloodType').val();
            let dob = $('#emergency-patientDOB').val();
            let mstatus = $('#emergency-patientMStatus').val();
            let mobile = $('#emergency-patientMobile').val();
            let address = $('#emergency-patientAddess').val();
            let alt_mobile = $('#emergency-patientAltMobile').val();
            let allergy = $('#emergency-patientAllergy').val();
            $.ajax({
                url: emergencyPatientDataUpdate,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                id:id,name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert(response.success);
                        $('#emergency-add-patient').modal('hide');
                    }else{
                       toastErrorAlert(response.error_success);
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
function emergencyPatientDelete(id){
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
                url:emergencyPatientDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#emergency-patient-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}
function emergencyPatientUsingId(id){
    window.open('emergency-in-details/' + id, '_blank');
}