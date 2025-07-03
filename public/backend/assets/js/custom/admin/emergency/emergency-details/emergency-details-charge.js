function resetCharge(){
    $('#emergencyChargeId').val('');
    $('#emergencyCharge-name').val('');
    $('#emergencyCharge-amount').val('');
    $('.emergencyChargeSubmit').removeClass('d-none');
    $('.emergencyChargeUpdate').addClass('d-none');
}
let table_charge = $('#emergancy-charges-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewEmergencyCharge,
        type:"POST",
        headers:{
           'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = patient_id;
        },
        error:function(xhr,thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
        {
            data:'created_at',
            name:'created_at'
        },
        {
            data:'title',
            name:'title'
        },
        {
            data:'desc',
            name:'desc'
        },
        {
            data:'amount',
            name:'amount'
        },
        {
            data:'action',
            name:'action',
            orderable:false,
            searchable:false
        },
    ]
});
$('#emergencyCharge-form').on('submit',function(e){
    e.preventDefault();
    let name_check = validateField('emergencyCharge-name', 'input');
    let amount_check = validateField('emergencyCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let patientId = $('#patient_Id').val();
       let name = $('#emergencyCharge-name').val();
       let amount = $('#emergencyCharge-amount').val();
        $.ajax({
            url:emergencyChargeSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-charges').modal('hide');
                    $('#emergencyCharge-form')[0].reset();
                    $('#emergancy-charges-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
                    console.log(response.error_validation);
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
function emergencyChargeEdit(id){
    $.ajax({
        url: getEmergencyChargeData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.emergencyChargeSubmit').addClass('d-none');
                $('.emergencyChargeUpdate').removeClass('d-none');
                $('#emergency-add-charges').modal('show');
                $('#emergencyChargeId').val(id);
                $('#emergencyCharge-name').val(getData.title);
                $('#emergencyCharge-amount').val(getData.amount);
            }
        }
    });
}
function emergencyChargeUpdate(id){
    let name_check = validateField('emergencyCharge-name', 'input');
    let amount_check = validateField('emergencyCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let name = $('#emergencyCharge-name').val();
       let amount = $('#emergencyCharge-amount').val();
        $.ajax({
            url:emergencyChargeDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-charges').modal('hide');
                    $('#emergencyCharge-form')[0].reset();
                    $('#emergancy-charges-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
                    console.log(response.error_validation);
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
function emergencyChargeDelete(id){
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
                url:emergencyChargeDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                         $('#emergancy-charges-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}