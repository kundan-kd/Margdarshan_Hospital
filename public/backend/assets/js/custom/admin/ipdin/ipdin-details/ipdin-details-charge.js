function resetCharge(){
    $('#ipdChargeId').val('');
    $('#ipdCharge-name').val('');
    $('#ipdCharge-amount').val('');
    $('.ipdChargeSubmit').removeClass('d-none');
    $('.ipdChargeUpdate').addClass('d-none');
}
let table_charge = $('#ipd-charges-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdCharge,
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
$('#ipdCharge-form').on('submit',function(e){
    e.preventDefault();
    let name_check = validateField('ipdCharge-name', 'input');
    let amount_check = validateField('ipdCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let patientId = $('#patient_Id').val();
       let name = $('#ipdCharge-name').val();
       let amount = $('#ipdCharge-amount').val();
        $.ajax({
            url:ipdChargeSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-charges').modal('hide');
                    $('#ipdCharge-form')[0].reset();
                    $('#ipd-charges-list').DataTable().ajax.reload();
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
function ipdChargeEdit(id){
    $.ajax({
        url: getIpdChargeData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.ipdChargeSubmit').addClass('d-none');
                $('.ipdChargeUpdate').removeClass('d-none');
                $('#ipd-add-charges').modal('show');
                $('#ipdChargeId').val(id);
                $('#ipdCharge-name').val(getData.title);
                $('#ipdCharge-amount').val(getData.amount);
            }
        }
    });
}
function ipdChargeUpdate(id){
    let name_check = validateField('ipdCharge-name', 'input');
    let amount_check = validateField('ipdCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let name = $('#ipdCharge-name').val();
       let amount = $('#ipdCharge-amount').val();
        $.ajax({
            url:ipdChargeDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-charges').modal('hide');
                    $('#ipdCharge-form')[0].reset();
                    $('#ipd-charges-list').DataTable().ajax.reload();
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
function ipdChargeDelete(id){
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
                url:ipdChargeDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                         $('#ipd-charges-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}