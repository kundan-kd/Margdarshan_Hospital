// function moveToIpd(id){
//      Swal.fire({
//         title: "Are you sure to move to IPD ?",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Yes, Move it!",
//         customClass: {
//             title: 'swal-title-custom'
//           }
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url:moveToIpdStatus,
//                 type:"POST",
//                 headers:{
//                     'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
//                 },
//                 data:{id:id},
//                 success:function(response){
//                     if (response.success) {
//                         Swal.fire("Moved", response.success, "success");
//                         setTimeout(function(){
//                             window.open('/emergency');
//                         },2500);
//                     } else {
//                         Swal.fire("Error!", "Error", "error");
//                     }
//                 }
//             });
//         }
//     });
// }

$('#emergency-ipdBedForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#emergency-ipdBed').val();
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
                            window.open('/emergency');
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
$('#emergency-icuBedForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#emergency-icuBed').val();
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



function patientDischargeE(id){

    // $.ajax({
    //     url: calculateDischargeAmountEmergency,
    //     type: "POST",
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     data: { id: id },
    //     success: function(response) {
    //         if (response.success) {
    //             $('#emergencyBillAmount').val(response.bill_amount || 0);
    //             $('#emergencyPaidAmount').val(response.received_amount || 0);
    //             const payAmount = (response.bill_amount - response.received_amount) || 0;
    //             $('#emergencyPayAmount').val(payAmount);

    //             if (payAmount <= 0) {
    //                 $('#emergencyDischargeModel').modal('hide');
    //                 processDischarge(id);
    //             }
    //         }
    //     },
    //     error: function(xhr, thrown) {
    //         console.error('Error:', thrown);
    //     }
    // });

    
}
// function dishargeEmergency(id){
//      window.open('emergency-bills/' + id);
// }
$('#emergency-dischargeAmountForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let billAmount = $('#emergencyBillAmount').val();
    let paidAmount = $('#emergencyPaidAmount').val();
    let payAmount = $('#emergencyPayAmount').val();
    
    if(parseFloat(payAmount) > (parseFloat(billAmount) + parseFloat(paidAmount))){
        toastErrorAlert('Pay Amount excceds due amount');
        console.log('Pay Amount excceds due amount');
        return;
    }
    $.ajax({ 
        url:submitRestEmergencyAmount,
        type:"POST",
        headers:{
            'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{patient_id:patient_id,payAmount:payAmount},
        success:function(response){
            if (response.success) {
               toastSuccessAlert(response.success);
               setTimeout(function(){
                    window.location.reload();
               },3000);
            }
        }
    });
})
function processDischarge(id){
    Swal.fire({
        title: "Confirm discharge from Emergency?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Discharge!",
        customClass: {
            title: 'swal-title-custom'
          }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:patientDischargeStatusE,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Discharged", response.success, "success");
                        setTimeout(function(){
                              location.reload();
                        },2000);
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}
function emergencyDischarge(id){
     window.open('/emergency-bills/' + id);
}
function resetAdvance(){
    $('#emergency-add-advanceLabel').html('Add Advance Amount');
    $('#emergencyAdvanceId').val('');
    $('#emergencyAdvance-amount').val('');
    $('#emergencyAdvance-pmode').val('');
    $('.emergencyAdvanceSubmit').removeClass('d-none');
    $('.emergencyAdvanceUpdate').addClass('d-none');
}
let patientId = $('#patient_Id').val();
let table_advance = $('#emergency-advance-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewEmergencyAdvance,
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
$('#emergencyAdvance-form').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let amount = validateField('emergencyAdvance-amount', 'select');
    let pmode = validateField('emergencyAdvance-pmode', 'select');
     if(amount === true && pmode === true){
       let amount = $('#emergencyAdvance-amount').val();
       let pmode = $('#emergencyAdvance-pmode').val();
        $.ajax({
            url:emergencyAdvanceSubmit,
            type:"POST",
            data:{
                patientId:patient_id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-advance').modal('hide');
                    $('#emergencyAdvance-form')[0].reset();
                    $('#emergency-advance-list').DataTable().ajax.reload();
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
function emergencyAdvanceEdit(id){
    $.ajax({
        url: getEmergencyAdvanceData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('#emergency-add-advanceLabel').html('Edit Advance Amount');
                $('.emergencyAdvanceSubmit').addClass('d-none');
                $('.emergencyAdvanceUpdate').removeClass('d-none');
                $('#emergency-add-advance').modal('show');
                $('#emergencyAdvanceId').val(id);
                $('#emergencyAdvance-amount').val(getData.amount);
                $('#emergencyAdvance-pmode').val(getData.payment_mode);
            }
        }
    });
}
function emergencyAdvanceUpdate(id){
    let amount = validateField('emergencyAdvance-amount', 'select');
    let pmode = validateField('emergencyAdvance-pmode', 'select');
     if(amount === true && pmode === true){
        let amount = $('#emergencyAdvance-amount').val();
       let pmode = $('#emergencyAdvance-pmode').val();
        $.ajax({
            url:emergencyAdvanceDataUpdate,
            type:"POST",
            data:{
                id:id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-advance').modal('hide');
                    $('#emergencyAdvance-form')[0].reset();
                    $('#emergency-advance-list').DataTable().ajax.reload();
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