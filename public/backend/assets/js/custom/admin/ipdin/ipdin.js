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
        data: function (d) {
            d.patientType = $('#patientType').val();  
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
            data:'department',
            name:'department'
        },
        {
            data:'bed_no',
            name:'bed_no'
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
            data:'entry_type',
            name:'entry_type'
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
            name:'created_at',
            orderable: false,
            searchable: true
        },
        // {
        //     data:'allergies',
        //     name:'allergies',
        //     orderable: false,
        //     searchable: true
        // },
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
    dom: 'Blfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'IPD Patient List',
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
    
function getPatientListFilter(){
    $('#ipd-in-patient-list').DataTable().ajax.reload();
}
function resetAddPatient(){
    $('#ipd-addPatientForm')[0].reset();
    $('#ipdPatientId').val('');
    $('#ipd-patientBedNum').append('<option value="">Select Bed</option>');
    $('#ipd-add-patientLabel').html('Add IPD Patient');
    $('.ipdPatientSubmit').removeClass('d-none');
    $('.ipdPatientUpdate').addClass('d-none');
    $('.ipd-patientName_errorCls').addClass('d-none');
    $('.ipd-guardianName_errorCls').addClass('d-none');
    $('.ipd-entryType_errorCls').addClass('d-none');
    $('.ipd-patientBloodType_errorCls').addClass('d-none');
    $('.ipd-patientDOB_errorCls').addClass('d-none');
    $('.ipd-patientMStatus_errorCls').addClass('d-none');
    $('.ipd-patientMobile_errorCls').addClass('d-none');
    $('.ipd-patientAddess_errorCls').addClass('d-none');
    $('.ipd-patientBedNum_errorCls').addClass('d-none');
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
    if(id != ''){
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

}
function getPatientDetails(mobile){
    if(mobile.length >= 10){
        // console.log(mobile);
        $('.patient-data-list').empty();
        $('.patient-data-list').removeClass('d-none');
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
                //     $('.patient-data-list').append(`<li class="list-group-item">No Data Found!</li>`);
                // } else {
                //     const addedIds = new Set();
                //     getData.forEach(element => {
                //         if (!addedIds.has(element.id)) {
                //             $('.patient-data-list').append(
                //                 `<li class="list-group-item" data-patient-id="${element.id}">${element.name} (${element.patient_id})</li>`
                //             );
                //             addedIds.add(element.id);
                //         }
                //     });
                // }
                if (!getData) {
                    $('.patient-data-list').append(`<li class="list-group-item">No Data Found!</li>`);
                } else {
                    $('.patient-data-list').append(
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
        $('.patient-data-list').empty();
        $('.patient-data-list').addClass('d-none');
        // console.log('10 Digit number required!');
    }
}
$(document).on('click', '.patient-data-list li', function() {
    let patientId = $(this).data('patient-id'); // Get the clicked patient's ID
    if(patientId != undefined){
        // $('#itemSearchInput').val('');
        fillPatientFields(patientId); // Pass the ID to the function
    }
});
function fillPatientFields(id){
    $.ajax({
        url: fillPatientData, // Ensure this is a valid endpoint
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id:id },
        success: function(response) {
             console.log(response);
            if(response.success){
                $('.patient-data-list').addClass('d-none');
                let getData = response.data[0];
                $('#ipdPatientId').val(getData.id);
                $('#ipd-patientName').val(getData.name);
                $('#ipd-guardianName').val(getData.guardian_name);
                $('#ipd-entryType').val(getData.entryType).change();
                $('#ipd-patientBloodType').val(getData.bloodtype).change();
                $('#ipd-patientDOB').val(getData.dob);
                $('#ipd-patientMStatus').val(getData.marital_status).change();
                $('#ipd-patientMobile').val(getData.mobile);
                $('#ipd-patientAddess').val(getData.address);
                $('#ipd-patientAltMobile').val(getData.alt_mobile);
                $('#ipd-patientAllergy').val(getData.known_allergies);
                $('#ipd-patientBedNum').val('').change();
                $('input[name="ipd-patientGender"]').each(function() {
                if ($(this).val() === getData.gender) {
                    $(this).prop('checked', true);
                }
                });
            }
        }
    });
}
$(document).on('click','#ipd-add-patient .modal-body',function(){
    $('.patient-data-list').addClass('d-none');
});
$('#ipd-addPatientForm').on('submit',function(e){
     e.preventDefault();
    let id = $('#ipdPatientId').val();
    let patientName  = validateField('ipd-patientName', 'input');
    let guardianName = validateField('ipd-guardianName', 'input');
    let patientEntryType = validateField('ipd-entryType', 'select');
    let patientDOB = validateField('ipd-patientDOB', 'select');
    let patientMStatus = validateField('ipd-patientMStatus', 'select');     
    let patientMobile = validateField('ipd-patientMobile', 'mobile');
    let patientAddess = validateField('ipd-patientAddess', 'input');
    let bedNumId = validateField('ipd-patientBedNum', 'select');
        if(patientName === true && guardianName === true && patientEntryType == true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
            $('.ipdPatientSubmit').addClass('d-none'); 
            $('.ipdPatientSpinn').removeClass('d-none'); 
            let name = $('#ipd-patientName').val();
            let guardian_name = $('#ipd-guardianName').val();
            let gender = $('input[name="ipd-patientGender"]:checked').val(); // Corrected na
            let entry_type = $('#ipd-entryType').val();
            let bloodtype = $('#ipd-patientBloodType').val();
            let dob = $('#ipd-patientDOB').val();
            let mstatus = $('#ipd-patientMStatus').val();
            let mobile = $('#ipd-patientMobile').val();
            let address = $('#ipd-patientAddess').val();
            let consultDoctor = $('#ipd-consultDoctor').val();
            let referPerson = $('#ipd-referPerson').val();
            let alt_mobile = $('#ipd-patientAltMobile').val();
            let allergy = $('#ipd-patientAllergy').val();
            let bedNumId = $('#ipd-patientBedNum').val();
            if ($('.ipdPatientUpdate').is(':visible')) {
            ipdPatientUpdate(id); // Trigger update function when update btn is active
            } else {
                $.ajax({
                    url: addNewPatientIpd,
                    type:"POST",
                    headers:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    data:{id:id,
                    name:name,guardian_name:guardian_name,gender:gender,entry_type:entry_type,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,consultDoctor:consultDoctor,referPerson:referPerson,alt_mobile:alt_mobile,allergy:allergy,bedNumId:bedNumId
                    },
                    success:function(response){
                        if(response.success){
                            toastSuccessAlert(response.success);
                            $('#ipdPatientId').val('');
                            $('#ipd-add-patient').modal('hide');
                            $('#ipd-in-patient-list').DataTable().ajax.reload();
                            $('.ipdPatientSpinn').addClass('d-none'); 
                            $('.ipdPatientSubmit').removeClass('d-none'); 
                            admissionForm(response.data); //print patient form
                        }else if(response.error_success){
                            toastErrorAlert(response.error_success);
                            $('.ipdPatientSpinn').addClass('d-none'); 
                            $('.ipdPatientSubmit').removeClass('d-none'); 
                        }else if(response.error_validation){
                            toastWarningAlert(response.error_validation);
                            $('.ipdPatientSpinn').addClass('d-none'); 
                            $('.ipdPatientSubmit').removeClass('d-none'); 
                        }else if(response.previous_admitted){
                            toastErrorAlert(response.previous_admitted);
                            $('.ipdPatientSpinn').addClass('d-none'); 
                            $('.ipdPatientSubmit').removeClass('d-none'); 
                        }else if(response.discharge_form_generate_issue){
                            toastErrorAlert(response.discharge_form_generate_issue);
                            $('.ipdPatientSpinn').addClass('d-none'); 
                            $('.ipdPatientSubmit').removeClass('d-none'); 
                        }else{
                             toastErrorAlert('something went wrong!');
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
    function printBill(id,admit_id){
         window.open('/discharge-bill-print/' + id + '/' + admit_id);
    }
function ipdPatientEdit(id){
 $.ajax({
        url: getIpdPatientData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('#ipd-add-patientLabel').html('Edit IPD Patient');
                $('.ipdPatientSubmit').addClass('d-none');
                $('.ipdPatientUpdate').removeClass('d-none');
                $('#ipd-add-patient').modal('show');
                $('#ipdPatientId').val(id);
                $('#ipd-patientName').val(getData.name);
                $('#ipd-guardianName').val(getData.guardian_name);
                $('#ipd-entryType').val(getData.entry_type).change();
                $('#ipd-patientBloodType').val(getData.bloodtype);
                $('#ipd-patientDOB').val(getData.dob);
                $('#ipd-patientMStatus').val(getData.marital_status);
                $('#ipd-patientMobile').val(getData.mobile);
                $('#ipd-patientAddess').val(getData.address);
                $('#ipd-consultDoctor').val(getData.attended_doctor_id);
                $('#ipd-referPerson').val(getData.reference_person);
                $('#ipd-patientAltMobile').val(getData.alt_mobile);
                $('#ipd-patientAllergy').val(getData.known_allergies);
                $('#ipd-patientBedNum').val(getData.bed_id);
                $('input[name="ipd-patientGender"]').each(function() {
                if ($(this).val() === getData.gender) {
                    $(this).prop('checked', true);
                }
        });

            }
        }
    });
}
function ipdPatientUpdate(id){
    let patientName  = validateField('ipd-patientName', 'input');
    let guardianName = validateField('ipd-guardianName', 'input');
    let patientEntryType = validateField('ipd-entryType', 'select');
    let patientDOB = validateField('ipd-patientDOB', 'select');
    let patientMStatus = validateField('ipd-patientMStatus', 'select');     
    let patientMobile = validateField('ipd-patientMobile', 'mobile');
    let patientAddess = validateField('ipd-patientAddess', 'input');
    let bedNumId = validateField('ipd-patientBedNum', 'select');
        if(patientName === true && guardianName === true && patientEntryType == true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true && bedNumId === true){    
            $('.ipdPatientUpdate').addClass('d-none'); 
            $('.ipdPatientSpinn').removeClass('d-none'); 
            let name = $('#ipd-patientName').val();
            let guardian_name = $('#ipd-guardianName').val();
            let gender = $('input[name="ipd-patientGender"]:checked').val(); // Corrected na
            let entry_type = $('#ipd-entryType').val();
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
                id:id,name:name,guardian_name:guardian_name,gender:gender,entry_type:entry_type,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy,bedNumId:bedNumId
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert(response.success);
                        $('#ipdPatientId').val('');
                        $('#ipd-add-patient').modal('hide');
                        $('#ipd-in-patient-list').DataTable().ajax.reload();
                        $('.ipdPatientSpinn').addClass('d-none'); 
                        $('.ipdPatientUpdate').removeClass('d-none'); 
                    }else{
                        toastErrorAlert(response.error_success);
                        $('.ipdPatientSpinn').addClass('d-none'); 
                        $('.ipdPatientUpdate').removeClass('d-none'); 
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
    window.location.href= 'ipd-in-details/' + id;
}
function admissionForm(id){
    window.open('/admission-form-print/' + id);
}
function dischargeForm(id){
    window.open('/discharge-form-print/' + id);
}
