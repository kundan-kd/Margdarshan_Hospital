function resetMedication(){
    $('#opdOutMedDoseId').val('');
    $('#opdOutMed-visitid').val('');
    $('#opdOutMed-medCategory').val('');
    $('#opdOutMed-medName').val('');
    $('#opdOutMed-dose').val('');
    $('#opdOutMed-remerks').val('');
    $('.opdOutMedDoseSubmit').removeClass('d-none');
    $('.opdOutMedDoseUpdate').addClass('d-none');
}
let table_med_dose = $('#opdOutMed-medicineDoseList').DataTable({
     processing: true,
    serverSide:true,
    ajax:{
        url: viewOptOutMedDose,
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
    url:opdOutVisitMedicineName,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        let medicineDetails = response.data;
        $('#opdOutMed-medName').empty();
            $('#opdOutMed-medName').append(`<option value="">Select</option>`);
        medicineDetails.forEach(function(medData){
            $('#opdOutMed-medName').append(`<option value="${medData.id}" >${medData.name}</option>`);
        });
    }
});
}
$('#opdOutMed-form').on('submit',function(e){
    e.preventDefault();
    let visitid_check  = validateField('opdOutMed-visitid', 'select');
    let medCategory_check  = validateField('opdOutMed-medCategory', 'select');
    let medName_check  = validateField('opdOutMed-medName', 'select');
    let dose_check  = validateField('opdOutMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let patientId = $('#patient_Id').val();
        let visitid = $('#opdOutMed-visitid').val();
        let medCategory = $('#opdOutMed-medCategory').val();
        let medName = $('#opdOutMed-medName').val();
        let dose = $('#opdOutMed-dose').val();
        let remerks = $('#opdOutMed-remerks').val();
        $.ajax({
            url:opdOutMedDataAdd,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{patientId:patientId,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#opd-add-medication-dose').modal('hide');
                    $('#opdOutMed-form')[0].reset();
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
function opdOutMedDoseEdit(id){
 $.ajax({
        url: getOpdOutMedDoseDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.opdOutMedDoseSubmit').addClass('d-none');
                $('.opdOutMedDoseUpdate').removeClass('d-none');
                $('#opd-add-medication-dose').modal('show');
                $('#opdOutMedDoseId').val(id);
                $('#opdOutMed-visitid').val(getData.visit_id).change();
                $('#opdOutMed-medCategory').val(getData.medicine_category_id).change();
                $('#opdOutMed-medName').val(getData.medicine_name_id).change();
                $('#opdOutMed-dose').val(getData.dose);
                $('#opdOutMed-remerks').val(getData.remarks);
            }
        }
    });
}
function opdOutMedDoseUpdate(id){
    let visitid_check  = validateField('opdOutMed-visitid', 'select');
    let medCategory_check  = validateField('opdOutMed-medCategory', 'select');
    let medName_check  = validateField('opdOutMed-medName', 'select');
    let dose_check  = validateField('opdOutMed-dose', 'select');
    if(visitid_check === true && medCategory_check === true && medName_check === true && dose_check === true){
        let visitid = $('#opdOutMed-visitid').val();
        let medCategory = $('#opdOutMed-medCategory').val();
        let medName = $('#opdOutMed-medName').val();
        let dose = $('#opdOutMed-dose').val();
        let remerks = $('#opdOutMed-remerks').val();
        $.ajax({
            url:opdOutMedDataUpdate,
            type:"POST",
            headers:{
             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id,visitid:visitid,medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
            success:function(response){
                if(response.success){
                    $('#opd-add-medication-dose').modal('hide');
                    $('#opdOutMed-form')[0].reset();
                    $('#opdOutMed-medicineDoseList').DataTable().ajax.reload();
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
function opdOutMedDoseDelete(id){
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
                url:opdOutMedDoseDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#opdOutMed-medicineDoseList').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}