
// let table = new DataTable('#roomNum-table');
let table = $('#roomNum-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewRoomNums,
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
            data:'roomnum',
            name:'roomnum'
        },
        {
            data:'roomtype',
            name:'roomtype'
        },
        {
            data:'current_status',
            name:'current_status'
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

$('.roomNum-add').on('click',function(e){
    e.preventDefault();
    $('.roomNum-title').html('Add Room Type');
    $('#roomNumNameID').val('');
    $('#roomNumName').val('');
    $('.roomNumUpdate').addClass('d-none');
    $('.roomNumSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------roomNum add starts----
$('#addRoomNumForm').on('submit',function(e){
    e.preventDefault();
    let id = $('#roomNumID').val();
    let roomType = $('#roomType').val();
    let roomNum = $('#roomNum').val();
    if(roomType == '' || roomNum == ''){
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        if ($('.roomNumUpdate').is(':visible')) {
            roomNumUpdate(id); // Trigger update function
        } else {
            $.ajax({
                url: addRoomNum,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{roomType:roomType,roomNum:roomNum},
                success:function(response){
                    if(response.success){
                        $('#addRoomNumModel').modal('hide');
                        $('#addRoomNumForm').removeClass('was-validated');
                        $('#addRoomNumForm')[0].reset();
                        $('#roomNum-table').DataTable().ajax.reload();
                        toastSuccessAlert('Room Number added successfully');
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
// ------roomNum add ends----
// ------roomNum update starts ----
function roomNumEdit(id){
$.ajax({
    url: getRoomNumData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.roomNum-title').html('Update room Type');
            $('#roomNumID').val(getData.id);
            $('#roomType').val(getData.roomtype_id);
            $('#roomNum').val(getData.room_num);
            $('.roomNumSubmit').addClass('d-none');
            $('.roomNumUpdate').removeClass('d-none');
            $('#addRoomNumModel').modal('show');
        }
    }

});
}

function roomNumUpdate(id){
    let roomType = $('#roomType').val();
    let roomNum = $('#roomNum').val();
    if(roomType == '' || roomNum == ''){
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateRoomNumData,
            type: "POST",
            data: {
                id: id,
                roomType:roomType,roomNum:roomNum
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addRoomNumModel').modal('hide');
                    $('#addRoomNumForm').removeClass('was-validated');
                    $('#addRoomNumForm')[0].reset();
                    $('#roomNum-table').DataTable().ajax.reload();
                    toastSuccessAlert('Room Number updated successfully');
                }else if(response.already_found) {
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
                $('#addRoomNumModel').modal('hide');
                $('#addRoomNumForm').removeClass('was-validated');
                $('#addRoomNumForm')[0].reset();
                $('#roomNum-table').DataTable().ajax.reload();
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

function roomNumDelete(id){
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
                url: deleteRoomNumData,
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
                        $('#roomNum-table').DataTable().ajax.reload();
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




