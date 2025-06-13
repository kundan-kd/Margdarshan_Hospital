function resetVital(){
    $('#opdOutVitalId').val('');
    $('#opdOutVital-name').val('');
    $('#opdOutVital-value').val('');
    $('#opdOutVital-date').val('');
    $('.opdOurVItalSubmit').removeClass('d-none');
    $('.opdOurVItalUpdate').addClass('d-none');
}
let table_vital = $('#opdOutVital-list').DataTable({
       processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdOutVital,
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
$('#opdOutVital-form').on('submit',function(e){
    e.preventDefault();
       let patientId = $('#patient_Id').val();
       let name = $('#opdOutVital-name').val();
       let value = $('#opdOutVital-value').val();
       let date = $('#opdOutVital-date').val();
        $.ajax({
            url:opdOutVItalSubmit,
            type:"POST",
            data:{
                patientId:patientId,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-vital-history').modal('hide');
                    $('#opdOutVital-form')[0].reset();
                    $('#opdOutVital-list').DataTable().ajax.reload();
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
function opdOutVitalEdit(id){
     $.ajax({
        url: getOpdOutVitalData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.opdOurVItalSubmit').addClass('d-none');
                $('.opdOurVItalUpdate').removeClass('d-none');
                $('#opd-add-vital-history').modal('show');
                $('#opdOutVitalId').val(id);
                $('#opdOutVital-name').val(getData.name);
                $('#opdOutVital-value').val(getData.value);
                $('#opdOutVital-date').val(getData.date);
            }
        }
    });
}
function opdOurVItalUpdate(id){
       let name = $('#opdOutVital-name').val();
       let value = $('#opdOutVital-value').val();
       let date = $('#opdOutVital-date').val();
        $.ajax({
            url:opdOutVItalDataUpdate,
            type:"POST",
            data:{
                id:id,name:name,value:value,date:date
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-vital-history').modal('hide');
                    $('#opdOutVital-form')[0].reset();
                    $('#opdOutVital-list').DataTable().ajax.reload();
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
function opdOutVitalDelete(id){
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
                url:opdOutVitalDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#opdOutVital-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}