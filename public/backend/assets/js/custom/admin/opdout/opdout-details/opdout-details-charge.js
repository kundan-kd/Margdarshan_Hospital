function resetCharge(){
    $('#opdOutChargeId').val('');
    $('#opdOutCharge-name').val('');
    $('#opdOutCharge-amount').val('');
    $('.opdOutChargeSubmit').removeClass('d-none');
    $('.opdOutChargeUpdate').addClass('d-none');
}
let table_charge = $('#opd-out-charges-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdOutCharge,
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
$('#opdOutCharge-form').on('submit',function(e){
    e.preventDefault();
    let name_check = validateField('opdOutCharge-name', 'input');
    let amount_check = validateField('opdOutCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let patientId = $('#patient_Id').val();
       let name = $('#opdOutCharge-name').val();
       let amount = $('#opdOutCharge-amount').val();
        $.ajax({
            url:opdOutChargeSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-charges').modal('hide');
                    $('#opdOutCharge-form')[0].reset();
                    $('#opd-out-charges-list').DataTable().ajax.reload();
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
function opdOutChargeEdit(id){
    $.ajax({
        url: getOpdOutChargeData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.opdOutChargeSubmit').addClass('d-none');
                $('.opdOutChargeUpdate').removeClass('d-none');
                $('#opd-add-charges').modal('show');
                $('#opdOutChargeId').val(id);
                $('#opdOutCharge-name').val(getData.title);
                $('#opdOutCharge-amount').val(getData.amount);
            }
        }
    });
}
function opdOutChargeUpdate(id){
    let name_check = validateField('opdOutCharge-name', 'input');
    let amount_check = validateField('opdOutCharge-amount', 'amount');
    if(name_check === true && amount_check === true){
       let name = $('#opdOutCharge-name').val();
       let amount = $('#opdOutCharge-amount').val();
        $.ajax({
            url:opdOutChargeDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,amount:amount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-charges').modal('hide');
                    $('#opdOutCharge-form')[0].reset();
                    $('#opd-out-charges-list').DataTable().ajax.reload();
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
function opdOutChargeDelete(id){
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
                url:opdOutChargeDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                         $('#opd-out-charges-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}