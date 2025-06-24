function resetMedication(){
    $('#ipdMedDoseId').val('');
    $('#ipdMed-visitid').val('');
    $('#ipdMed-medCategory').val('');
    $('#ipdMed-medName').val('');
    $('#ipdMed-dose').val('');
    $('#ipdMed-remerks').val('');
    $('.ipdMedDoseSubmit').removeClass('d-none');
    $('.ipdMedDoseUpdate').addClass('d-none');
}
let table_med_dose = $('#ipd-Med-medicineDoseList').DataTable({
     processing: true,
    serverSide:true,
    ajax:{
        url: viewIpdMedDose,
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
function getVisitId(id){
    $.ajax({
        url:ipdVisitId,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            let visitDetails = response.data;
            $('#ipdMed-visitid').empty();
            $('#ipdMed-visitid').append(`<option value="">Select</option>`);
            visitDetails.forEach(function(visitData){
                $('#ipdMed-visitid').append(`<option value="${visitData.id}" >MDVI0${visitData.id}</option>`);
            });
        }
    });
}
function medicinelist(medicine_cat_id,visit_id){
    setTimeout(function(){
        $.ajax({
            url:ipdVisitMedicineName,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:medicine_cat_id,visit_id:visit_id},
            success:function(response){
                let medicineDetails = response.data;
                let medicineName = response.medicineNameId[0];
                $('#ipdMed-medName').empty();
                    $('#ipdMed-medName').append(`<option value="">Select</option>`);
                medicineDetails.forEach(function(medData){
                    $('#ipdMed-medName').append(`<option value="${medData.id}" ${medData.id == medicineName.medicine_name_id ? 'selected':''} >${medData.name}</option>`);
                });
            }
        });
    },200);    
}
$('#ipdMed-form').on('submit',function(e){
    e.preventDefault();
    let visitid_check  = validateField('ipdMed-visitid', 'select');
    let medCategory_check  = validateField('ipdMed-medCategory', 'select');
    let medName_check  = validateField('ipdMed-medName', 'select');
    let dose_check  = validateField('ipdMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let patientId = $('#patient_Id').val();
        let visitid = $('#ipdMed-visitid').val();
        let medCategory = $('#ipdMed-medCategory').val();
        let medName = $('#ipdMed-medName').val();
        let dose = $('#ipdMed-dose').val();
        let remerks = $('#ipdMed-remerks').val();
        $.ajax({
            url:ipdMedDataAdd,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{patientId:patientId,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#ipd-add-medication-dose').modal('hide');
                    $('#ipdMed-form')[0].reset();
                    $('#ipd-Med-medicineDoseList').DataTable().ajax.reload();
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
function ipdMedDoseEdit(id){
 $.ajax({
        url: getIpdMedDoseDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.ipdMedDoseSubmit').addClass('d-none');
                $('.ipdMedDoseUpdate').removeClass('d-none');
                $('#ipd-add-medication-dose').modal('show');
                $('#ipdMedDoseId').val(id);
                $('#ipdMed-visitid').val(getData.visit_id).change();
                $('#ipdMed-medCategory').val(getData.medicine_category_id).change();
                $('#ipdMed-medName').val(getData.medicine_name_id).change();
                $('#ipdMed-dose').val(getData.dose);
                $('#ipdMed-remerks').val(getData.remarks);
            }
        }
    });
}
function ipdMedDoseUpdate(id){
    let visitid_check  = validateField('ipdMed-visitid', 'select');
    let medCategory_check  = validateField('ipdMed-medCategory', 'select');
    let medName_check  = validateField('ipdMed-medName', 'select');
    let dose_check  = validateField('ipdMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let visitid = $('#ipdMed-visitid').val();
        let medCategory = $('#ipdMed-medCategory').val();
        let medName = $('#ipdMed-medName').val();
        let dose = $('#ipdMed-dose').val();
        let remerks = $('#ipdMed-remerks').val();
        $.ajax({
            url:ipdMedDataUpdate,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#ipd-add-medication-dose').modal('hide');
                    $('#ipdMed-form')[0].reset();
                    $('#ipd-Med-medicineDoseList').DataTable().ajax.reload();
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
function ipdMedDoseDelete(id){
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
                url:ipdMedDoseDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                    $('#ipd-Med-medicineDoseList').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}