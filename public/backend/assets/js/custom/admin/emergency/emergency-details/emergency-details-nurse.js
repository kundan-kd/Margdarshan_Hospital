function resetNurse(){
    $('#emergency-nurse-noteLabel').html('Add Nurse Note');
    $('#emergencyNurseId').val('');
    $('#emergencyNurse-name').val('');
    $('#emergencyNurse-value').val('');
    $('#emergencyNurse-date').val('');
    $('.emergencyNurseSubmit').removeClass('d-none');
    $('.emergencyNurseUpdate').addClass('d-none');
}
let table_nurse = $('#emergencyNurse-noteList').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewEmergencyNurseNote,
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
$('#emergencyNurseNote-form').on('submit',function(e){
    e.preventDefault();
    let name_check = validateField('emergencyNurse-name', 'select');
    let note_check = validateField('emergencyNurse-note', 'input');
    if(name_check === true && note_check === true){  
       let patientId = $('#patient_Id').val();
       let nurseId = $('#emergencyNurse-name').val();
       let note = $('#emergencyNurse-note').val();
       let comment = $('#emergencyNurse-comment').val();
        $.ajax({
            url:emergencyNurseNoteSubmit,
            type:"POST",
            data:{
                patientId:patientId,nurseId:nurseId,note:note,comment:comment
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-nurse-note').modal('hide');
                    $('#emergencyNurseNote-form')[0].reset();
                    $('#emergencyNurse-noteList').DataTable().ajax.reload();
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
function emergencyNurseNoteEdit(id){
     $.ajax({
        url: getEmergencyNurseNoteData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            if(response.success){
               let getData = response.data[0];
                $('#emergency-nurse-noteLabel').html('Edit Nurse Note');
                $('.emergencyNurseNoteSubmit').addClass('d-none');
                $('.emergencyNurseNoteUpdate').removeClass('d-none');
                $('#emergency-nurse-note').modal('show');
                $('#emergencyNurseNoteId').val(id);
                $('#emergencyNurse-name').val(getData.nurse_id);
                $('#emergencyNurse-note').val(getData.note);
                $('#emergencyNurse-comment').val(getData.comment);
            }
        }
    });
}
function emergencyNurseNoteUpdate(id){
       let nameId = $('#emergencyNurse-name').val();
       let note = $('#emergencyNurse-note').val();
       let comment = $('#emergencyNurse-comment').val();
        $.ajax({
            url:emergencyNurseNoteDataUpdate,
            type:"POST",
            data:{
                id:id,nameId:nameId,note:note,comment:comment
            },
            headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-nurse-note').modal('hide');
                    $('#emergencyNurseNote-form')[0].reset();
                    $('#emergencyNurse-noteList').DataTable().ajax.reload();
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
function emergencyNurseNoteDelete(id){
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
                url:emergencyNurseDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#emergencyNurse-noteList').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}