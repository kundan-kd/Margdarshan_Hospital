// function moveToEmergency(id){
//      Swal.fire({
//         title: "Are you sure to move to Emergency ?",
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
//                 url:moveToEmergencyStatus,
//                 type:"POST",
//                 headers:{
//                     'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
//                 },
//                 data:{id:id},
//                 success:function(response){
//                     if (response.success) {
//                         Swal.fire("Moved", response.success, "success");
//                         setTimeout(function(){
//                             window.open('/ipd-in');
//                         },2500);
//                     } else {
//                         Swal.fire("Error!", "Error", "error");
//                     }
//                 }
//             });
//         }
//     });
// }

$('#ipd-emergencyRoomForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#ipd-emergencyRoom').val();
    if(bed == ''){
         $('.needs-validation').addClass('was-validated');
    }else{
          Swal.fire({
        title: "Are you sure to move to Emergency ?",
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
                url:moveToEmergencyStatus,
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
$('#ipd-ipdBedForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#ipd-ipdBed').val();
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
                url:moveToIpdStatusFromIcu,
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
$('#ipd-icuBedForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let bed = $('#ipd-icuBed').val();
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
function ipdDischarge(id){
       window.open('/patient-discharge-bills/' + id);
}
function patientDischarge(id) {
    $.ajax({
        url: calculateDischargeAmount,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: id },
        success: function(response) {
            console.log(response);
            if (response.success) {
                $('#ipdBillAmount').val(response.bill_amount || 0);
                $('#ipdPaidAmount').val(response.received_amount || 0);
                const payAmount = (response.bill_amount - response.received_amount) || 0;
                $('#ipdPayAmount').val(payAmount);

                if (payAmount <= 0) {
                    $('#ipdDischargeModel').modal('hide');
                    processDischarge(id);
                }
            }
        },
        error: function(xhr, thrown) {
            console.error('Error:', thrown);
        }
    });
}

$('#ipd-dischargeAmountForm').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let billAmount = $('#ipdBillAmount').val();
    let paidAmount = $('#ipdPaidAmount').val();
    let payAmount = $('#ipdPayAmount').val();
    if(parseFloat(payAmount) > (parseFloat(billAmount) + parseFloat(paidAmount))){
        toastErrorAlert('Pay Amount excceds due amount');
        return;
    }
    $.ajax({ 
        url:submitRestIpdAmount,
        type:"POST",
        headers:{
            'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{patient_id:patient_id,payAmount:payAmount},
        success:function(response){
            console.log(response);
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
        title: "Confirm discharge from IPD?",
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
                url:patientDischargeStatus,
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
function resetAdvance(){
    $('#ipd-add-advanceLabel').html('Add Advance Amount');
    $('#ipdAdvanceId').val('');
    $('#ipdAdvance-amount').val('');
    $('#ipdAdvance-pmode').val('');
    $('.ipdAdvanceSubmit').removeClass('d-none');
    $('.ipdAdvanceUpdate').addClass('d-none');
}
let patientId = $('#patient_Id').val();
let table_advance = $('#ipd-advance-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdAdvance,
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
$('#ipdAdvance-form').on('submit',function(e){
    e.preventDefault();
    let patient_id = $('#patient_Id').val();
    let amount = validateField('ipdAdvance-amount', 'select');
    let pmode = validateField('ipdAdvance-pmode', 'select');
     if(amount === true && pmode === true){
       let amount = $('#ipdAdvance-amount').val();
       let pmode = $('#ipdAdvance-pmode').val();
        $.ajax({
            url:ipdAdvanceSubmit,
            type:"POST",
            data:{
                patientId:patient_id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-advance').modal('hide');
                    $('#ipdAdvance-form')[0].reset();
                    $('#ipd-advance-list').DataTable().ajax.reload();
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
function ipdAdvanceEdit(id){
    $.ajax({
        url: getIpdAdvanceData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('#ipd-add-advanceLabel').html('Edit Advance Amount');
                $('.ipdAdvanceSubmit').addClass('d-none');
                $('.ipdAdvanceUpdate').removeClass('d-none');
                $('#ipd-add-advance').modal('show');
                $('#ipdAdvanceId').val(id);
                $('#ipdAdvance-amount').val(getData.amount);
                $('#ipdAdvance-pmode').val(getData.payment_mode);
            }
        }
    });
}
function ipdAdvanceUpdate(id){
    let amount = validateField('ipdAdvance-amount', 'select');
    let pmode = validateField('ipdAdvance-pmode', 'select');
     if(amount === true && pmode === true){
        let amount = $('#ipdAdvance-amount').val();
       let pmode = $('#ipdAdvance-pmode').val();
        $.ajax({
            url:ipdAdvanceDataUpdate,
            type:"POST",
            data:{
                id:id,amount:amount,pmode:pmode
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-advance').modal('hide');
                    $('#ipdAdvance-form')[0].reset();
                    $('#ipd-advance-list').DataTable().ajax.reload();
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