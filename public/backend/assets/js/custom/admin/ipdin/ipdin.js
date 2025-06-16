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

$('#ipd-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('ipd-patientName', 'input');
    let guardianName = validateField('ipd-guardianName', 'input');
    // let patientGender = validateField('patientGender', 'radio');
    let patientBloodType = validateField('ipd-patientBloodType', 'select');
    let patientDOB = validateField('ipd-patientDOB', 'select');
    let patientMStatus = validateField('ipd-patientMStatus', 'select');     
    let patientMobile = validateField('ipd-patientMobile', 'mobile');
    let patientAddess = validateField('ipd-patientAddess', 'input');
        if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
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
            $.ajax({
                url: addNewPatientIpd,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New IPD Patient added successfully');
                        $('#ipd-add-patient').modal('hide');
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
function ipdPatientUsingId(id){
    window.open('ipd-in-details/' + id, '_blank');
}