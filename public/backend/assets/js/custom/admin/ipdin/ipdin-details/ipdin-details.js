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

function patientDischarge(id){
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
