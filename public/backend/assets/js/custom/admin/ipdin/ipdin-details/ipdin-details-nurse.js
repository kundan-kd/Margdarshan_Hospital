function resetNurse(){
    $('#ipd-nurse-noteLabel').html('Add Nurse Note');
    $('#ipdNurseId').val('');
    $('#ipdNurse-name').val('');
    $('#ipdNurse-value').val('');
    $('#ipdNurse-date').val('');
    $('.ipdNurseSubmit').removeClass('d-none');
    $('.ipdNurseUpdate').addClass('d-none');
}
let table_nurse = $('#ipdNurse-noteList').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdNurseNote,
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
            name:'name',
            orderable:false,
            searchable:false
        },
        {
            data:'note',
            name:'note',
            orderable:false,
            searchable:false
        },
        {
            data:'comment',
            name:'comment',
            orderable:false,
            searchable:false
        },
        {
            data:'action',
            name:'action',
            orderable:false,
            searchable:false
        },
    ]
});
$('#ipdNurseNote-form').on('submit',function(e){
    e.preventDefault();
    let name_check = validateField('ipdNurse-name', 'select');
    let note_check = validateField('ipdNurse-note', 'input');
    if(name_check === true && note_check === true){  
       let patientId = $('#patient_Id').val();
       let nurseId = $('#ipdNurse-name').val();
       let note = $('#ipdNurse-note').val();
       let comment = $('#ipdNurse-comment').val();
        $.ajax({
            url:ipdNurseNoteSubmit,
            type:"POST",
            data:{
                patientId:patientId,nurseId:nurseId,note:note,comment:comment
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-nurse-note').modal('hide');
                    $('#ipdNurseNote-form')[0].reset();
                    $('#ipdNurse-noteList').DataTable().ajax.reload();
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
function ipdNurseNoteEdit(id){
     $.ajax({
        url: getIpdNurseNoteData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            if(response.success){
               let getData = response.data[0];
                $('#ipd-nurse-noteLabel').html('Edit Nurse Note');
                $('.ipdNurseNoteSubmit').addClass('d-none');
                $('.ipdNurseNoteUpdate').removeClass('d-none');
                $('#ipd-nurse-note').modal('show');
                $('#ipdNurseNoteId').val(id);
                $('#ipdNurse-name').val(getData.nurse_id);
                $('#ipdNurse-note').val(getData.note);
                $('#ipdNurse-comment').val(getData.comment);
            }
        }
    });
}
function ipdNurseNoteUpdate(id){
       let nameId = $('#ipdNurse-name').val();
       let note = $('#ipdNurse-note').val();
       let comment = $('#ipdNurse-comment').val();
        $.ajax({
            url:ipdNurseNoteDataUpdate,
            type:"POST",
            data:{
                id:id,nameId:nameId,note:note,comment:comment
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-nurse-note').modal('hide');
                    $('#ipdNurseNote-form')[0].reset();
                    $('#ipdNurse-noteList').DataTable().ajax.reload();
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
function ipdNurseNoteDelete(id){
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
                url:ipdNurseDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#ipdNurse-noteList').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}