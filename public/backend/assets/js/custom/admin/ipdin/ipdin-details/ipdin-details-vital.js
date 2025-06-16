function resetVital(){
    $('#ipdVitalId').val('');
    $('#ipdVital-name').val('');
    $('#ipdVital-value').val('');
    $('#ipdVital-date').val('');
    $('.ipdVItalSubmit').removeClass('d-none');
    $('.ipdVItalUpdate').addClass('d-none');
}
let table_vital = $('#ipdVital-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdVital,
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
$('#ipdVital-form').on('submit',function(e){
    e.preventDefault();
       let patientId = $('#patient_Id').val();
       let name = $('#ipdVital-name').val();
       let value = $('#ipdVital-value').val();
       let date = $('#ipdVital-date').val();
        $.ajax({
            url:ipdVItalSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-vital-history').modal('hide');
                    $('#ipdVital-form')[0].reset();
                    $('#ipdVital-list').DataTable().ajax.reload();
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
function ipdVitalEdit(id){
     $.ajax({
        url: getIpdVitalData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.ipdVItalSubmit').addClass('d-none');
                $('.ipdVItalUpdate').removeClass('d-none');
                $('#ipd-add-vital-history').modal('show');
                $('#ipdVitalId').val(id);
                $('#ipdVital-name').val(getData.name);
                $('#ipdVital-value').val(getData.value);
                $('#ipdVital-date').val(getData.date);
            }
        }
    });
}
function ipdVItalUpdate(id){
       let name = $('#ipdVital-name').val();
       let value = $('#ipdVital-value').val();
       let date = $('#ipdVital-date').val();
        $.ajax({
            url:ipdVItalDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-vital-history').modal('hide');
                    $('#ipdVital-form')[0].reset();
                    $('#ipdVital-list').DataTable().ajax.reload();
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
function ipdVitalDelete(id){
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
                url:ipdVitalDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#ipdVital-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}