function medicinelist(id){
$.ajax({
    url:opdOutVisitMedicineName,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        console.log(response);
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
    let medCategory_check  = validateField('opdOutMed-medCategory', 'select');
    let medName_check  = validateField('opdOutMed-medName', 'select');
    let dose_check  = validateField('opdOutMed-dose', 'select');
    if(medCategory_check === true && medName_check === true && dose_check === true){
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
            data:{medCategory:medCategory,medName:medName,dose:dose,remerks:remerks},
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