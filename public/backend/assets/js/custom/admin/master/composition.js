
// let table = new DataTable('#composition-table');
let table = $('#composition-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewCompositions,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
           },
        error: function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: ' + thrown);
        }
    },
    columns:[
        {
            data:'name',
            name:'name',
            orderable: true,
            searchable: true
        },
        {
            data:'status',
            name:'status',
            orderable: false,
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});

$('.composition-add').on('click',function(e){
    e.preventDefault();
    $('.composition-title').html('Add Composition');
    $('#compositionNameID').val('');
    $('#compositionName').val('');
    $('.addcompositionUpdate').addClass('d-none');
    $('.addcompositionSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------composition add starts----
$('#addcompositionForm').on('submit',function(e){
   e.preventDefault();
   let composition = $('#compositionName').val();
   let id = $('#compositionID').val();
   if(composition == ''){
    $('#compositionName').focus();
   }else{
        if ($('.addcompositionUpdate').is(':visible')) {
            compositionUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addComposition,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{composition:composition},
        success:function(response){
            // console.log(response);
            if(response.success){
                $('#addcompositionModel').modal('hide');
                $('#addcompositionForm').removeClass('was-validated');
                $('#addcompositionForm')[0].reset();
                $('#composition-table').DataTable().ajax.reload();
                toastSuccessAlert(response.success);
            } else if(response.already_found) {
                toastErrorAlert(response.already_found);    
            }else{
                toastErrorAlert('error found!');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred: " + error);
        }
    });
        }
   }
});
// ------composition add ends----
// ------composition update starts ----
function compositionEdit(id){
$.ajax({
    url: getCompositionData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        // console.log(response);
        if(response.success){
            getData = response.data[0];
            // console.log(getData);
            $('.composition-title').html('Update Composition');
            $('#compositionID').val(getData.id);
            $('#compositionName').val(getData.name);
            $('.addcompositionSubmit').addClass('d-none');
            $('.addcompositionUpdate').removeClass('d-none');
            $('#addcompositionModel').modal('show');
        }
    }

});
}

function compositionUpdate(id){
    let composition = $('#compositionName').val();
    if(composition == ''){
        $('#compositionName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateCompositionData,
            type: "post",
            data: {
                id: id,
                composition: composition
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addcompositionModel').modal('hide');
                    $('#addcompositionForm').removeClass('was-validated');
                    $('#addcompositionForm')[0].reset();
                    $('#composition-table').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                } else if(response.already_found) {
                    toastErrorAlert(response.already_found);
                } else {
                    toastErrorAlert("error");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
}

function statusSwitch(id){
    $.ajax({
        url: statusUpdate,
        type: "POST",
        data: {
            id: id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#addcompositionModel').modal('hide');
                $('#addcompositionForm').removeClass('was-validated');
                $('#addcompositionForm')[0].reset();
                $('#composition-table').DataTable().ajax.reload();
                toastSuccessAlert('Status changed successfully');
            } else {
                toastErrorAlert("Error");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred: " + error);
        }
    });
}

function compositionDelete(id){
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
                url: deletecompositionData,
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#composition-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire("Error!", "An error occurred: " + error, "error");
                }
            });
        }
    });
}




