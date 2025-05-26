
// let table = new DataTable('#usertype-table');
let table = $('#usertype-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewUserTypes,
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

$('.userType-add').on('click',function(e){
    e.preventDefault();
    $('.userType-title').html('Add User Type');
    $('#userTypeNameID').val('');
    $('#userTypeName').val('');
    $('.addUserTypeUpdate').addClass('d-none');
    $('.addUserTypeSubmit').removeClass('d-none');
    });
// ------usertype add starts----
$('#addUserTypeForm').on('submit',function(e){
   e.preventDefault();
   let usertype = $('#userTypeName').val();
   let id = $('#userTypeNameID').val();
   if(usertype == ''){
    $('#userTypeName').focus();
   }else{
        if ($('.addUserTypeUpdate').is(':visible')) {
            userTypeUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addUserType,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{usertype:usertype},
        success:function(response){
            console.log(response);
            if(response.success){
                $('#addUserTypeModel').modal('hide');
                $('#addUserTypeForm').removeClass('was-validated');
                $('#addUserTypeForm')[0].reset();
                $('#usertype-table').DataTable().ajax.reload();
                toastSuccessAlert('User Type added successfully');
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
// ------usertype add ends----
// ------usertype update starts ----
function userTypeEdit(id){
$.ajax({
    url: getUserTypeData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        console.log(response);
        if(response.success){
            getData = response.data[0];
            console.log(getData);
            $('.userType-title').html('Update User Type');
            $('#userTypeNameID').val(getData.id);
            $('#userTypeName').val(getData.name);
            $('.addUserTypeSubmit').addClass('d-none');
            $('.addUserTypeUpdate').removeClass('d-none');
            $('#addUserTypeModel').modal('show');
        }
    }

});
}

function userTypeUpdate(id){
    let usertype = $('#userTypeName').val();
    if(usertype == ''){
        $('#userTypeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateUserTypeData,
            type: "post",
            data: {
                id: id,
                usertype: usertype
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addUserTypeModel').modal('hide');
                    $('#addUserTypeForm').removeClass('was-validated');
                    $('#addUserTypeForm')[0].reset();
                    $('#usertype-table').DataTable().ajax.reload();
                    toastSuccessAlert('User Type updated successfully');
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
                $('#addUserTypeModel').modal('hide');
                $('#addUserTypeForm').removeClass('was-validated');
                $('#addUserTypeForm')[0].reset();
                $('#usertype-table').DataTable().ajax.reload();
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

function userTypeDelete(id){
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
                url: deleteUserTypeData,
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
                        $('#usertype-table').DataTable().ajax.reload();
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




