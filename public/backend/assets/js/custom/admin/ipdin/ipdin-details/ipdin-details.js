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