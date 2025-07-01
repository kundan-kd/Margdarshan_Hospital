function resetAdvance(){
    $('#opd-add-advanceLabel').html('Add Advance Amount');
    $('#opdOutAdvanceId').val('');
    $('#opdOutAdvance-amount').val('');
    $('#opdOutAdvance-pmode').val('');
    $('.opdOutAdvanceSubmit').removeClass('d-none');
    $('.opdOutAdvanceUpdate').addClass('d-none');
}
$('#opd-ipdRoomForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#opd-ipdRoom').val();
    if(bed == ''){
         $('.needs-validation').addClass('was-validated');
    }else{
        Swal.fire({
        title: "Are you sure to move to IPD ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Move it!",
        customClass: {
            title: 'swal-title-custom'
          }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:moveToIpdStatus,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:patient_id,bed_id:bed},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Moved", response.success, "success");
                        setTimeout(function(){
                            window.open('/ipd-in');
                        },2500);
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
    }
})
$('#opd-icuRoomForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#opd-icuRoom').val();
    if(bed == ''){
         $('.needs-validation').addClass('was-validated');
    }else{
        Swal.fire({
        title: "Are you sure to move to ICU ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Move it!",
        customClass: {
            title: 'swal-title-custom'
          }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:moveToIcuStatus,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:patient_id,bed_id:bed},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Moved", response.success, "success");
                        setTimeout(function(){
                            window.open('/ipd-in');
                        },2500);
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
    }
})

function generateBar(x){
    console.log(x);
}
let patientId = $('#patient_Id').val();
let table_advance = $('#opd-out-advance-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdOutAdvance,
        type:"POST",
        headers:{
           'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = patientId;
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
            data:'amount',
            name:'amount'
        },
        {
            data:'pmode',
            name:'pmode'
        },
        {
            data:'action',
            name:'action',
            orderable:false,
            searchable:false
        },
    ]
});
$('#opdOutAdvance-form').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let amount = validateField('opdOutAdvance-amount', 'select');
    let pmode = validateField('opdOutAdvance-pmode', 'select');
     if(amount === true && pmode === true){
       let amount = $('#opdOutAdvance-amount').val();
       let pmode = $('#opdOutAdvance-pmode').val();
        $.ajax({
            url:opdOutAdvanceSubmit,
            type:"POST",
            data:{
                patientId:patient_id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-advance').modal('hide');
                    $('#opdOutAdvance-form')[0].reset();
                    $('#opd-out-advance-list').DataTable().ajax.reload();
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
function opdOutAdvanceEdit(id){
    $.ajax({
        url: getOpdOutAdvanceData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('#opd-add-advanceLabel').html('Edit Advance Amount');
                $('.opdOutAdvanceSubmit').addClass('d-none');
                $('.opdOutAdvanceUpdate').removeClass('d-none');
                $('#opd-add-advance').modal('show');
                $('#opdOutAdvanceId').val(id);
                $('#opdOutAdvance-amount').val(getData.amount);
                $('#opdOutAdvance-pmode').val(getData.payment_mode);
            }
        }
    });
}
function opdOutAdvanceUpdate(id){
    let amount = validateField('opdOutAdvance-amount', 'select');
    let pmode = validateField('opdOutAdvance-pmode', 'select');
     if(amount === true && pmode === true){
        let amount = $('#opdOutAdvance-amount').val();
       let pmode = $('#opdOutAdvance-pmode').val();
        $.ajax({
            url:opdOutAdvanceDataUpdate,
            type:"POST",
            data:{
                id:id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-advance').modal('hide');
                    $('#opdOutAdvance-form')[0].reset();
                    $('#opd-out-advance-list').DataTable().ajax.reload();
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