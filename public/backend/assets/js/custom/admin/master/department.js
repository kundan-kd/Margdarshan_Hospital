
// let table = new DataTable('#usertype-table');
let table = $('#department-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewDepartments,
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

$('.department-add').on('click',function(e){
    e.preventDefault();
    $('.department-title').html('Add User Type');
    $('#departmentID').val('');
    $('#departmentName').val('');
    $('.departmentUpdate').addClass('d-none');
    $('.departmentSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------usertype add starts----
$('#departmentForm').on('submit',function(e){
   e.preventDefault();
   let id = $('#departmentID').val();
   let department = $('#departmentName').val();
   if(department == ''){
    $('#departmentName').focus();
   }else{
      if ($('.departmentUpdate').is(':visible')) {
            departmentUpdate(id); // Trigger update function when update btn is active
        } else {
            $.ajax({
                url: addDepartment,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{department:department},
                success:function(response){
                    if(response.success){
                        $('#addDepartmentModel').modal('hide');
                        $('#departmentForm').removeClass('was-validated');
                        $('#departmentForm')[0].reset();
                        $('#department-table').DataTable().ajax.reload();
                        toastSuccessAlert('Department added successfully');
                    } else if(response.already_found) {
                        toastErrorAlert(response.already_found);    
                    }else{
                        alert('error found!');
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
function departmentEdit(id){
$.ajax({
    url: getDepartmentData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
      //  console.log(response);
        if(response.success){
            getData = response.data[0];
           // console.log(getData);
            $('.department-title').html('Update Department');
            $('#departmentID').val(getData.id);
            $('#departmentName').val(getData.name);
            $('.departmentSubmit').addClass('d-none');
            $('.departmentUpdate').removeClass('d-none');
            $('#addDepartmentModel').modal('show');
        }
    }
});
}

function departmentUpdate(id){
    let department = $('#departmentName').val();
    if(department == ''){
        $('#departmentName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateDepartmentData,
            type: "post",
            data: {
                id: id,
                department: department
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addDepartmentModel').modal('hide');
                    $('#departmentForm').removeClass('was-validated');
                    $('#departmentForm')[0].reset();
                    $('#department-table').DataTable().ajax.reload();
                    toastSuccessAlert('Department updated successfully');
                } else if(response.already_found) {
                    toastErrorAlert(response.already_found);    
                } else {
                    toastErrorAlert('Something error found');
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
                $('#addDepartmentModel').modal('hide');
                $('#departmentForm').removeClass('was-validated');
                $('#departmentForm')[0].reset();
                $('#department-table').DataTable().ajax.reload();
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

function departmentDelete(id){
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
                url: deleteDepertmentData,
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
                        $('#department-table').DataTable().ajax.reload();
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




