let table_patient = $('#ipd-in-patient-list').DataTable({
    // responsive:true,
    processing: true,
    serverSide:true,
    ajax:{
        url: viewPatientsIpd,
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
function resetAddPatient(){
    $('#ipd-addPatientForm')[0].reset();
    $('#ipdPatientId').val('');
    $('#ipd-patientBedNum').append('<option value="">Select Bed</option>');
    $('#ipd-add-patientLabel').html('Add IPD Patient');
    $('.ipdPatientSubmit').removeClass('d-none');
    $('.ipdPatientUpdate').addClass('d-none');
    $('.ipd-patientName_errorCls').addClass('d-none');
    $('.ipd-guardianName_errorCls').addClass('d-none');
    $('.ipd-patientBloodType_errorCls').addClass('d-none');
    $('.ipd-patientDOB_errorCls').addClass('d-none');
    $('.ipd-patientMStatus_errorCls').addClass('d-none');
    $('.ipd-patientMobile_errorCls').addClass('d-none');
    $('.ipd-patientAddess_errorCls').addClass('d-none');
}
function getBedData(id){
    $.ajax({
        url: getBedDataIpd,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            id:id
        },
        success:function(response){
            if(response.success){
                $('#ipd-patientBedNum').empty();
                $('#ipd-patientBedNum').append('<option value="">Select Bed</option>');
                if(response.bedData.length > 0){
                let usedBed = response.bedData[0];
                let bedType = response.bedType[0];
                $('#ipd-patientBedNum').append('<option selected value="'+usedBed.id+'">'+usedBed.bed_no+'</option>');
                $('#ipd-patientBedType').val(bedType.name);
                $('#ipd-patientBedFloor').val(usedBed.floor);
                $('#ipd-patientBedCharge').val(usedBed.amount);
                }
                $.each(response.data,function(key,value){
                    $('#ipd-patientBedNum').append('<option value="'+value.id+'">'+value.bed_no+'</option>');
                });
                
            }
        }
    });
}
function getBedDetails(id){
    $.ajax({
        url:getBedDetailsIpd,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                let getData = response.data[0];
                let bedtype = response.bedTypeName[0];
                $('#ipd-patientBedType').val(bedtype.name);
                $('#ipd-patientBedFloor').val(getData.floor);
                $('#ipd-patientBedCharge').val(getData.amount);
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
$('#ipd-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('ipd-patientName', 'input');
    let guardianName = validateField('ipd-guardianName', 'input');
    let patientBloodType = validateField('ipd-patientBloodType', 'select');
    let patientDOB = validateField('ipd-patientDOB', 'select');
    let patientMStatus = validateField('ipd-patientMStatus', 'select');     
    let patientMobile = validateField('ipd-patientMobile', 'mobile');
    let patientAddess = validateField('ipd-patientAddess', 'input');
    let bedNumId = validateField('ipd-patientBedNum', 'select');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
           
            let name = $('#ipd-patientName').val();
            let guardian_name = $('#ipd-guardianName').val();
            let gender = $('input[name="ipd-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#ipd-patientBloodType').val();
            let dob = $('#ipd-patientDOB').val();
            let mstatus = $('#ipd-patientMStatus').val();
            let mobile = $('#ipd-patientMobile').val();
            let address = $('#ipd-patientAddess').val();
            let alt_mobile = $('#ipd-patientAltMobile').val();
            let allergy = $('#ipd-patientAllergy').val();
            let bedNumId = $('#ipd-patientBedNum').val();
            $.ajax({
                url: addNewPatientIpd,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy,bedNumId:bedNumId
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New IPD Patient added successfully');
                        $('#ipd-add-patient').modal('hide');
                        $('#ipd-in-patient-list').DataTable().ajax.reload();
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
function ipdPatientEdit(id){
 $.ajax({
        url: getIpdPatientData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
           // console.log(response);
            if(response.success){
               let getData = response.data[0];
                $('#ipd-add-patientLabel').html('Edit Nurse Note');
                $('.ipdPatientSubmit').addClass('d-none');
                $('.ipdPatientUpdate').removeClass('d-none');
                $('#ipd-add-patient').modal('show');
                $('#ipdPatientId').val(id);
                $('#ipd-patientName').val(getData.name);
                $('#ipd-guardianName').val(getData.guardian_name);
                $('#ipd-patientBloodType').val(getData.bloodtype);
                $('#ipd-patientDOB').val(getData.dob);
                $('#ipd-patientMStatus').val(getData.marital_status);
                $('#ipd-patientMobile').val(getData.mobile);
                $('#ipd-patientAddess').val(getData.address);
                $('#ipd-patientAltMobile').val(getData.alt_mobile);
                $('#ipd-patientAllergy').val(getData.known_allergies);
                $('#ipd-patientBedNum').val(getData.bed_id);
            }
        }
    });
}
function ipdPatientUpdate(id){
     let patientName  = validateField('ipd-patientName', 'input');
    let guardianName = validateField('ipd-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('ipd-patientBloodType', 'select');
    let patientDOB = validateField('ipd-patientDOB', 'select');
    let patientMStatus = validateField('ipd-patientMStatus', 'select');     
    let patientMobile = validateField('ipd-patientMobile', 'mobile');
    let patientAddess = validateField('ipd-patientAddess', 'input');
    let bedNumId = validateField('ipd-patientBedNum', 'select');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
           
            let name = $('#ipd-patientName').val();
            let guardian_name = $('#ipd-guardianName').val();
            let gender = $('input[name="ipd-patientGender"]:checked').val(); // Corrected na
            let bloodtype = $('#ipd-patientBloodType').val();
            let dob = $('#ipd-patientDOB').val();
            let mstatus = $('#ipd-patientMStatus').val();
            let mobile = $('#ipd-patientMobile').val();
            let address = $('#ipd-patientAddess').val();
            let alt_mobile = $('#ipd-patientAltMobile').val();
            let allergy = $('#ipd-patientAllergy').val();
            let bedNumId = $('#ipd-patientBedNum').val();
            $.ajax({
                url: ipdPatientDataUpdate,
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
                        $('#ipd-add-patient').modal('hide');
                        $('#ipd-in-patient-list').DataTable().ajax.reload();
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
function ipdpatientDelete(id){
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
                url:ipdPatientDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#ipd-patient-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}
function ipdPatientUsingId(id){
    window.open('ipd-in-details/' + id, '_blank');
}