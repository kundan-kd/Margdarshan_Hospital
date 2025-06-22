
// let table = new DataTable('#usertype-table');
let table = $('#bedtype-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewBedTypes,
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

$('.bedtype-add').on('click',function(e){
    e.preventDefault();
    $('.bedtype-title').html('Add Bed Type');
    $('#bedtypeNameID').val('');
    $('#bedtypeName').val('');
    $('.addBedTypeUpdate').addClass('d-none');
    $('.addBedTypeSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------usertype add starts----
$('#addBedTypeForm').on('submit',function(e){
   e.preventDefault();
   let bedtype = $('#bedTypeName').val();
   let id = $('#bedTypeNameID').val();
   if(bedtype == ''){
    $('#bedTypeName').focus();
   }else{
        if ($('.addBedTypeUpdate').is(':visible')) {
            bedTypeUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addBedType,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{bedtype:bedtype},
        success:function(response){
            if(response.success){
                $('#addBedTypeModel').modal('hide');
                $('#addBedTypeForm').removeClass('was-validated');
                $('#addBedTypeForm')[0].reset();
                $('#bedtype-table').DataTable().ajax.reload();
                toastSuccessAlert(response.success);
            } else if(response.already_found) {
                toastErrorAlert(response.already_found);
            }else{
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
});
// ------usertype add ends----
// ------usertype update starts ----
function bedTypeEdit(id){
$.ajax({
    url: getBedTypeData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.bedtype-title').html('Edit Bed Type');
            $('#bedTypeNameID').val(getData.id);
            $('#bedTypeName').val(getData.name);
            $('.addBedTypeSubmit').addClass('d-none');
            $('.addBedTypeUpdate').removeClass('d-none');
            $('#addBedTypeModel').modal('show');
        }
    }

});
}

function bedTypeUpdate(id){
    let bedtype = $('#bedTypeName').val();
    if(bedtype == ''){
        $('#bedTypeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateBedTypeData,
            type: "post",
            data: {
                id: id,
                bedtype: bedtype
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addBedTypeModel').modal('hide');
                    $('#addBedTypeForm').removeClass('was-validated');
                    $('#addBedTypeForm')[0].reset();
                    $('#bedtype-table').DataTable().ajax.reload();
                    toastSuccessAlert('Bed Type updated successfully');
                } else if(response.already_found) {
                    toastErrorAlert(response.already_found);    
                }else{
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
                $('#addBedTypeModel').modal('hide');
                $('#addBedTypeForm').removeClass('was-validated');
                $('#addBedTypeForm')[0].reset();
                $('#bedtype-table').DataTable().ajax.reload();
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

function bedTypeDelete(id){
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
                url: deleteBedTypeData,
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
                        $('#bedtype-table').DataTable().ajax.reload();
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




