function resetVital(){
    $('#emergencyVitalId').val('');
    $('#emergencyVital-name').val('');
    $('#emergencyVital-value').val('');
    $('#emergencyVital-date').val('');
    $('.emergencyVItalSubmit').removeClass('d-none');
    $('.emergencyVItalUpdate').addClass('d-none');
}
let table_vital = $('#emergencyVital-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewEmergencyVital,
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
            data:'date',
            name:'date'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'value',
            name:'value'
        },
        {
            data:'action',
            name:'action',
            orderable:false,
            searchable:false
        },
    ]
});
$('#emergencyVital-form').on('submit',function(e){
    e.preventDefault();
       let patientId = $('#patient_Id').val();
       let name = $('#emergencyVital-name').val();
       let value = $('#emergencyVital-value').val();
       let date = $('#emergencyVital-date').val();
        $.ajax({
            url:emergencyVItalSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-vital-history').modal('hide');
                    $('#emergencyVital-form')[0].reset();
                    $('#emergencyVital-list').DataTable().ajax.reload();
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
});
function emergencyVitalEdit(id){
     $.ajax({
        url: getEmergencyVitalData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.emergencyVItalSubmit').addClass('d-none');
                $('.emergencyVItalUpdate').removeClass('d-none');
                $('#emergency-add-vital-history').modal('show');
                $('#emergencyVitalId').val(id);
                $('#emergencyVital-name').val(getData.name);
                $('#emergencyVital-value').val(getData.value);
                $('#emergencyVital-date').val(getData.date);
            }
        }
    });
}
function emergencyVItalUpdate(id){
       let name = $('#emergencyVital-name').val();
       let value = $('#emergencyVital-value').val();
       let date = $('#emergencyVital-date').val();
        $.ajax({
            url:emergencyVitalDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-vital-history').modal('hide');
                    $('#emergencyVital-form')[0].reset();
                    $('#emergencyVital-list').DataTable().ajax.reload();
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
}
function emergencyVitalDelete(id){
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
                url:emergencyVitalDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#emergencyVital-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}