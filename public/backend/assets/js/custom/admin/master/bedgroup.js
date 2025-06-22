
// let table = new DataTable('#usertype-table');
let table = $('#bedGroup-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewBedGroups,
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

$('.bedGroup-add').on('click',function(e){
    e.preventDefault();
    $('.bedGroup-title').html('Add User Type');
    $('#bedGroupNameID').val('');
    $('#bedGroupName').val('');
    $('.addBedGroupUpdate').addClass('d-none');
    $('.addBedGroupSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------usertype add starts----
$('#addBedGroupForm').on('submit',function(e){
   e.preventDefault();
   let bedGroup = $('#bedGroupName').val();
   let id = $('#bedGroupNameID').val();
   if(bedGroup == ''){
    $('#bedGroupName').focus();
   }else{
        if ($('.addBedGroupUpdate').is(':visible')) {
            bedGroupUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addBedGroup,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{bedGroup:bedGroup},
        success:function(response){
            if(response.success){
                $('#addBedGroupModel').modal('hide');
                $('#addBedGroupForm').removeClass('was-validated');
                $('#addBedGroupForm')[0].reset();
                $('#bedGroup-table').DataTable().ajax.reload();
                toastSuccessAlert(response.success);
            } else if(response.already_found) {
                toastErrorAlert(response.already_found);
            }else{
                toastErrorAlert("something went wrong!");
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
function bedGroupEdit(id){
$.ajax({
    url: getBedGroupData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.bedGroup-title').html('Edit Bed Type');
            $('#bedGroupNameID').val(getData.id);
            $('#bedGroupName').val(getData.name);
            $('.addBedGroupSubmit').addClass('d-none');
            $('.addBedGroupUpdate').removeClass('d-none');
            $('#addBedGroupModel').modal('show');
        }
    }

});
}

function bedGroupUpdate(id){
    let bedGroup = $('#bedGroupName').val();
    if(bedGroup == ''){
        $('#bedGroupName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateBedGroupData,
            type: "post",
            data: {
                id: id,
                bedGroup: bedGroup
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addBedGroupModel').modal('hide');
                    $('#addBedGroupForm').removeClass('was-validated');
                    $('#addBedGroupForm')[0].reset();
                    $('#bedGroup-table').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                } else if(response.already_found) {
                    toastErrorAlert(response.already_found);    
                }else{
                    toastErrorAlert("something went wrong!");
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
                $('#addBedGroupModel').modal('hide');
                $('#addBedGroupForm').removeClass('was-validated');
                $('#addBedGroupForm')[0].reset();
                $('#bedGroup-table').DataTable().ajax.reload();
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

function bedGroupDelete(id){
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
                url: deleteBedGroupData,
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
                        $('#bedGroup-table').DataTable().ajax.reload();
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




