
// let table = new DataTable('#TestName-table');
let table = $('#testname-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewTestNames,
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
            data:'testname',
            name:'testname'
        },
        {
            data:'sname',
            name:'sname'
        },
        {
            data:'testtype',
            name:'testtype'
        },
        {
            data:'amount',
            name:'amount'
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

$('.TestName-add').on('click',function(e){
    e.preventDefault();
    $('.testName-title').html('Add Test Name');
    $('#testNameNameID').val('');
    $('#testNameName').val('');
    $('.addTestNameUpdate').addClass('d-none');
    $('.addTestNameSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------TestName add starts----
$('#addTestNameForm').on('submit',function(e){
   e.preventDefault();
   let id = $('#testNameID').val();
   let testType_id = $('#testType_id').val();
   let testName = $('#testName').val();
   let testShortName = $('#testShortName').val();
   let testAmount = $('#testAmount').val();
   if(testName == '' || testType_id == '' || testShortName == '' || testAmount == '' ){
    $('#needs-validation').addClass('was-validated'); //added bootstrap class for form validation
   }else{
        if ($('.addTestNameUpdate').is(':visible')) {
            testNameUpdate(id); // Trigger update function
        } else {
            $.ajax({
                url: addTestName,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{testType_id:testType_id,testName:testName,testShortName:testShortName,testAmount:testAmount},
                success:function(response){
                    // console.log(response);
                    if(response.success){
                        $('#addTestNameModel').modal('hide');
                        $('#addTestNameForm').removeClass('was-validated');
                        $('#addTestNameForm')[0].reset();
                        $('#testname-table').DataTable().ajax.reload();
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
// ------TestName add ends----
// ------TestName update starts ----
function testNameEdit(id){
$.ajax({
    url: getTestNameData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
         console.log(response);
        if(response.success){
            getData = response.data[0];
            // console.log(getData);
            $('.TestName-title').html('Update Test Name');
            $('#testNameID').val(getData.id);
            $('#testType_id').val(getData.test_type_id);
            $('#testName').val(getData.name);
            $('#testShortName').val(getData.s_name);
            $('#testAmount').val(getData.amount);
            $('.addTestNameSubmit').addClass('d-none');
            $('.addTestNameUpdate').removeClass('d-none');
            $('#addTestNameModel').modal('show');
        }
    }

});
}

function testNameUpdate(id){
   let testType_id = $('#testType_id').val();
   let testName = $('#testName').val();
   let testShortName = $('#testShortName').val();
   let testAmount = $('#testAmount').val();
   if(testName == '' || testType_id == '' || testShortName == '' || testAmount == '' ){
    $('#needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateTestNameData,
            type: "post",
            data: {id:id,testType_id:testType_id,testName:testName,testShortName:testShortName,testAmount:testAmount},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addTestNameModel').modal('hide');
                    $('#addTestNameForm').removeClass('was-validated');
                    $('#addTestNameForm')[0].reset();
                    $('#testname-table').DataTable().ajax.reload();
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
                $('#addTestNameModel').modal('hide');
                $('#addTestNameForm').removeClass('was-validated');
                $('#addTestNameForm')[0].reset();
                $('#TestName-table').DataTable().ajax.reload();
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

function testNameDelete(id){
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
                url: deleteTestNameData,
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
                        $('#testname-table').DataTable().ajax.reload();
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




