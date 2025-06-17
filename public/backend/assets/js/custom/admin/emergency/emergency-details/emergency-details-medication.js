function resetMedication(){
    $('#emergencyMedDoseId').val('');
    $('#emergencyMed-visitid').val('');
    $('#emergencyMed-medCategory').val('');
    $('#emergencyMed-medName').val('');
    $('#emergencyMed-dose').val('');
    $('#emergencyMed-remerks').val('');
    $('.emergencyMedDoseSubmit').removeClass('d-none');
    $('.emergencyMedDoseUpdate').addClass('d-none');
}
let table_med_dose = $('#emergency-Med-medicineDoseList').DataTable({
     processing: true,
    serverSide:true,
    ajax:{
        url: viewEmergencyMedDose,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = patient_id;
        },
        error:function(xhr, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
         {
            data:'visit_id',
            name:'visit_id'
        },
        {
            data:'date',
            name:'date'
        },
        {
            data:'category',
            name:'category'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'dose',
            name:'dose',
            orderable: false,
            searchable: true
        },
        {
            data:'remarks',
            name:'remarks',
            orderable: false,
            searchable: true
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },

    ]
});

function medicinelist(id){
$.ajax({
    url:emergencyMedicineName,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        let medicineDetails = response.data;
        $('#emergencyMed-medName').empty();
            $('#emergencyMed-medName').append(`<option value="">Select</option>`);
        medicineDetails.forEach(function(medData){
            $('#emergencyMed-medName').append(`<option value="${medData.id}" >${medData.name}</option>`);
        });
    }
});
}
$('#emergencyMed-form').on('submit',function(e){
    e.preventDefault();
    let visitid_check  = validateField('emergencyMed-visitid', 'select');
    let medCategory_check  = validateField('emergencyMed-medCategory', 'select');
    let medName_check  = validateField('emergencyMed-medName', 'select');
    let dose_check  = validateField('emergencyMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let patientId = $('#patient_Id').val();
        let visitid = $('#emergencyMed-visitid').val();
        let medCategory = $('#emergencyMed-medCategory').val();
        let medName = $('#emergencyMed-medName').val();
        let dose = $('#emergencyMed-dose').val();
        let remerks = $('#emergencyMed-remerks').val();
        $.ajax({
            url:emergencyMedDataAdd,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{patientId:patientId,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#emergency-add-medication-dose').modal('hide');
                    $('#emergencyMed-form')[0].reset();
                    $('#emergency-Med-medicineDoseList').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
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
function emergencyMedDoseEdit(id){
 $.ajax({
        url: getEmergencyMedDoseDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.emergencyMedDoseSubmit').addClass('d-none');
                $('.emergencyMedDoseUpdate').removeClass('d-none');
                $('#emergency-add-medication-dose').modal('show');
                $('#emergencyMedDoseId').val(id);
                $('#emergencyMed-visitid').val(getData.visit_id).change();
                $('#emergencyMed-medCategory').val(getData.medicine_category_id).change();
                $('#emergencyMed-medName').val(getData.medicine_name_id).change();
                $('#emergencyMed-dose').val(getData.dose);
                $('#emergencyMed-remerks').val(getData.remarks);
            }
        }
    });
}
function emergencyMedDoseUpdate(id){
    let visitid_check  = validateField('emergencyMed-visitid', 'select');
    let medCategory_check  = validateField('emergencyMed-medCategory', 'select');
    let medName_check  = validateField('emergencyMed-medName', 'select');
    let dose_check  = validateField('emergencyMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let visitid = $('#emergencyMed-visitid').val();
        let medCategory = $('#emergencyMed-medCategory').val();
        let medName = $('#emergencyMed-medName').val();
        let dose = $('#emergencyMed-dose').val();
        let remerks = $('#emergencyMed-remerks').val();
        $.ajax({
            url:emergencyMedDataUpdate,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#emergency-add-medication-dose').modal('hide');
                    $('#emergencyMed-form')[0].reset();
                    $('#emergency-Med-medicineDoseList').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
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
}
function emergencyMedDoseDelete(id){
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
                url:emergencyMedDoseDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                    $('#emergency-Med-medicineDoseList').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}