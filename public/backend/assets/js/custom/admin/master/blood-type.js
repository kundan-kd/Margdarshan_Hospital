
// let table = new DataTable('#usertype-table');
let table = $('#bloodType-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewBloodType,
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
            searchable: false
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});

$('.bloodType-add').on('click',function(e){
    e.preventDefault();
    $('.bloodType-title').html('Add Blood Type');
    $('#bloodTypeID').val('');
    $('#bloodTypeName').val('');
    $('.bloodTypeUpdate').addClass('d-none');
    $('.bloodTypeSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });

$('#bloodTypeForm').on('submit',function(e){
   e.preventDefault();
   let id = $('#bloodTypeID').val();
   let bloodType = $('#bloodTypeName').val();
   if(bloodType == ''){
    $('#bloodTypeName').focus();
   }else{
        if ($('.bloodTypeUpdate').is(':visible')) {
            bloodTypeUpdate(id); // Trigger update function when update btn is active
        } else {
            $.ajax({
                url: addBloodType,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{bloodType:bloodType},
                success:function(response){
                    if(response.success){
                        $('#addBloodTypeModel').modal('hide');
                        $('#bloodTypeForm').removeClass('was-validated');
                        $('#bloodTypeForm')[0].reset();
                        $('#bloodType-table').DataTable().ajax.reload();
                        toastSuccessAlert('Blood Type added successfully');
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

// // ------usertype update starts ----
function bloodTypeEdit(id){
$.ajax({
    url: getBloodTypeData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.bloodType-title').html('Update Blood Type');
            $('#bloodTypeID').val(getData.id);
            $('#bloodTypeName').val(getData.name);
            $('.bloodTypeSubmit').addClass('d-none');
            $('.bloodTypeUpdate').removeClass('d-none');
            $('#addBloodTypeModel').modal('show');
        }
    }
});
}

function bloodTypeUpdate(id){
    let bloodType = $('#bloodTypeName').val();
    if(bloodType == ''){
        $('#bloodTypeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateBloodTypeData,
            type: "post",
            data: {
                id: id,
                bloodType:bloodType
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addBloodTypeModel').modal('hide');
                    $('#bloodTypeForm').removeClass('was-validated');
                    $('#bloodTypeForm')[0].reset();
                    $('#bloodType-table').DataTable().ajax.reload();
                    toastSuccessAlert('Blood Type updated successfully');
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
                    $('#addBloodTypeModel').modal('hide');
                    $('#bloodTypeForm').removeClass('was-validated');
                    $('#bloodTypeForm')[0].reset();
                    $('#bloodType-table').DataTable().ajax.reload();
                    toastSuccessAlert('Blood Type updated successfully');
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

function bloodTypeDelete(id){
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
                url: deleteBloodType,
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
                       $('#bloodType-table').DataTable().ajax.reload();
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




