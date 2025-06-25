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
            data:'bed_no',
            name:'bed_no'
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
    ],
    dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Emergency Patient List',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8] // Excludes index 11 (action)
                }
            }
        ]
});

// function resetPatient(){
//     $('#emergency-add-patientLabel').html('Add Patient');
//     $('#emergencyPatientId').val('');
//     $('.emergencyPatientUpdate').addClass('d-none');
//     $('.emergencyPatientSubmit').removeClass('d-none');
// }
function resetAddPatient(){
    $('#emergency-addPatientForm')[0].reset();
    $('#emergencyPatientId').val('');
    $('#emergency-patientBedNum').append('<option value="">Select Bed</option>');
    $('#emergency-add-patientLabel').html('Add Emergency Patient');
    $('.emergencyPatientSubmit').removeClass('d-none');
    $('.emergencyPatientUpdate').addClass('d-none');
    $('.emergency-patientName_errorCls').addClass('d-none');
    $('.emergency-guardianName_errorCls').addClass('d-none');
    $('.emergency-patientBloodType_errorCls').addClass('d-none');
    $('.emergency-patientDOB_errorCls').addClass('d-none');
    $('.emergency-patientMStatus_errorCls').addClass('d-none');
    $('.emergency-patientMobile_errorCls').addClass('d-none');
    $('.emergency-patientAddess_errorCls').addClass('d-none');
}
function getBedDataEmergency(id){
      $.ajax({
        url: getBedDatasEmergency,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            id:id
        },
        success:function(response){
            if(response.success){
                $('#emergency-patientBedNum').empty();
                $('#emergency-patientBedNum').append('<option value="">Select Bed</option>');
                if(response.bedData.length > 0){
                let usedBed = response.bedData[0];
                let bedType = response.bedType[0];
                $('#emergency-patientBedNum').append('<option selected value="'+usedBed.id+'">'+usedBed.bed_no+'</option>');
                $('#emergency-patientBedType').val(bedType.name);
                $('#emergency-patientBedFloor').val(usedBed.floor);
                $('#emergency-patientBedCharge').val(usedBed.amount);
                }
                $.each(response.data,function(key,value){
                    $('#emergency-patientBedNum').append('<option value="'+value.id+'">'+value.bed_no+'</option>');
                });
                
            }
        }
    });
}
function getBedDetails(id){
     $.ajax({
        url:getBedDetailsEmergency,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                let getData = response.data[0];
                let bedtype = response.bedTypeName[0];
                $('#emergency-patientBedType').val(bedtype.name);
                $('#emergency-patientBedFloor').val(getData.floor);
                $('#emergency-patientBedCharge').val(getData.amount);
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
    let bedNumId = validateField('emergency-patientBedNum', 'select');

        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
           
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
             let bedNumId = $('#emergency-patientBedNum').val();
            $.ajax({
                url: addPatient,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy,bedNumId:bedNumId
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New emergency Patient added successfully');
                        $('#emergency-add-patient').modal('hide');
                        $('#emergency-patient-list').DataTable().ajax.reload();
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
                $('#emergency-add-patientLabel').html('Edit Emergency Patient');
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
                $('#emergency-patientBedNum').val(getData.bed_id);
                $('input[name="emergency-patientGender"]').each(function() {
                if ($(this).val() === getData.gender) {
                    $(this).prop('checked', true);
                }
        });

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
        let bedNumId = validateField('emergency-patientBedNum', 'select');

        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
           
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
            let bedNumId = $('#emergency-patientBedNum').val();

            $.ajax({
                url: emergencyPatientDataUpdate,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                id:id,name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy,bedNumId:bedNumId
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert(response.success);
                        $('#emergency-add-patient').modal('hide');
                         $('#emergency-patient-list').DataTable().ajax.reload();
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
    window.open('emergency-details/' + id, '_blank');
}