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
            data:'mstatus',
            name:'mstatus'
        },
        {
            data:'mobile',
            name:'mobile',
            orderable: false,
            searchable: true
        },
        {
            data:'alt_mobile',
            name:'alt_mobile',
            orderable: false,   
            searchable: true
        },
        {
            data:'address',
            name:'address',
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
            data:'action',
            name:'action',
            orderable: false,
            searchable: true
        }

    ],
    dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'OPD Patient List',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10] // Excludes index 11 (action)
                }
            }
        ]
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

$('#patient-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('patient-patientName', 'input');
    let guardianName = validateField('patient-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('patient-patientBloodType', 'select');
    let patientDOB = validateField('patient-patientDOB', 'select');
    let patientMStatus = validateField('patient-patientMStatus', 'select');     
    let patientMobile = validateField('patient-patientMobile', 'mobile');
    let patientAddess = validateField('patient-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
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
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('patient-patientBloodType', 'select');
    let patientDOB = validateField('patient-patientDOB', 'select');
    let patientMStatus = validateField('patient-patientMStatus', 'select');     
    let patientMobile = validateField('patient-patientMobile', 'mobile');
    let patientAddess = validateField('patient-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
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
                        toastSuccessAlert('New Patient added successfully');
                        $('#patient-add-patient').modal('hide');
                        $('#patient-table').DataTable().ajax.reload();
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