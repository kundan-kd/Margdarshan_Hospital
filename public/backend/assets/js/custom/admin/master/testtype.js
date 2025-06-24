
// let table = new DataTable('#testtype-table');
let table = $('#testtype-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewTestTypes,
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

$('.testtype-add').on('click',function(e){
    e.preventDefault();
    $('.testType-title').html('Add test Type');
    $('#testTypeNameID').val('');
    $('#testTypeName').val('');
    $('.addTestTypeUpdate').addClass('d-none');
    $('.addTestTypeSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------testtype add starts----
$('#addTestTypeForm').on('submit',function(e){
   e.preventDefault();
   let testtype = $('#testTypeName').val();
   let id = $('#testTypeNameID').val();
   if(testtype == ''){
    $('#testTypeName').focus();
   }else{
        if ($('.addTestTypeUpdate').is(':visible')) {
            testTypeUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addTestType,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{testtype:testtype},
        success:function(response){
            // console.log(response);
            if(response.success){
                $('#addTestTypeModel').modal('hide');
                $('#addTestTypeForm').removeClass('was-validated');
                $('#addTestTypeForm')[0].reset();
                $('#testtype-table').DataTable().ajax.reload();
                toastSuccessAlert('Test Type added successfully');
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
// ------testtype add ends----
// ------testtype update starts ----
function testTypeEdit(id){
$.ajax({
    url: getTestTypeData,
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
            $('.testType-title').html('Update test Type');
            $('#testTypeNameID').val(getData.id);
            $('#testTypeName').val(getData.name);
            $('.addTestTypeSubmit').addClass('d-none');
            $('.addTestTypeUpdate').removeClass('d-none');
            $('#addTestTypeModel').modal('show');
        }
    }

});
}

function testTypeUpdate(id){
    let testtype = $('#testTypeName').val();
    if(testtype == ''){
        $('#testTypeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateTestTypeData,
            type: "post",
            data: {
                id: id,
                testtype: testtype
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addTestTypeModel').modal('hide');
                    $('#addTestTypeForm').removeClass('was-validated');
                    $('#addTestTypeForm')[0].reset();
                    $('#testtype-table').DataTable().ajax.reload();
                    toastSuccessAlert('Test Type updated successfully');
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
                $('#addTestTypeModel').modal('hide');
                $('#addTestTypeForm').removeClass('was-validated');
                $('#addTestTypeForm')[0].reset();
                $('#testtype-table').DataTable().ajax.reload();
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

function testTypeDelete(id){
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
                url: deleteTestTypeData,
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
                        $('#testtype-table').DataTable().ajax.reload();
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




