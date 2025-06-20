
// let table = new DataTable('#roomtype-table');
let table = $('#roomtype-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewRoomTypes,
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
            name:'name'
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

$('.roomType-add').on('click',function(e){
    e.preventDefault();
    $('.roomType-title').html('Add Room Type');
    $('#roomTypeNameID').val('');
    $('#roomTypeName').val('');
    $('.roomTypeUpdate').addClass('d-none');
    $('.roomTypeSubmit').removeClass('d-none');
    });
// ------roomtype add starts----
$('#addRoomTypeForm').on('submit',function(e){
   e.preventDefault();
   let roomtype = $('#roomTypeName').val();
   let id = $('#roomTypeNameID').val();
   if(roomtype == ''){
    $('#roomTypeName').focus();
   }else{
        if ($('.roomTypeUpdate').is(':visible')) {
            roomTypeUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addRoomType,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{roomtype:roomtype},
        success:function(response){
            if(response.success){
                $('#addRoomTypeModel').modal('hide');
                $('#addRoomTypeForm').removeClass('was-validated');
                $('#addRoomTypeForm')[0].reset();
                $('#roomtype-table').DataTable().ajax.reload();
                toastSuccessAlert('Room Type added successfully');
            }else if(response.already_found) {
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
// ------roomtype add ends----
// ------roomtype update starts ----
function roomTypeEdit(id){
$.ajax({
    url: getRoomTypeData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.roomType-title').html('Update room Type');
            $('#roomTypeNameID').val(getData.id);
            $('#roomTypeName').val(getData.name);
            $('.roomTypeSubmit').addClass('d-none');
            $('.roomTypeUpdate').removeClass('d-none');
            $('#addRoomTypeModel').modal('show');
        }
    }

});
}

function roomTypeUpdate(id){
    let roomtype = $('#roomTypeName').val();
    if(roomtype == ''){
        $('#roomTypeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateRoomTypeData,
            type: "post",
            data: {
                id: id,
                roomtype: roomtype
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    $('#addRoomTypeModel').modal('hide');
                    $('#addRoomTypeForm').removeClass('was-validated');
                    $('#addRoomTypeForm')[0].reset();
                    $('#roomtype-table').DataTable().ajax.reload();
                    toastSuccessAlert('Room Type updated successfully');
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
                $('#addRoomTypeModel').modal('hide');
                $('#addRoomTypeForm').removeClass('was-validated');
                $('#addRoomTypeForm')[0].reset();
                $('#roomtype-table').DataTable().ajax.reload();
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

function roomTypeDelete(id){
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
                url: deleteRoomTypeData,
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
                        $('#roomtype-table').DataTable().ajax.reload();
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




