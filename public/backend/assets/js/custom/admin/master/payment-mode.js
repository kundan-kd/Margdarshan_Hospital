
// let table = new DataTable('#usertype-table');
let table = $('#paymentMode-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewPaymentModes,
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

$('.paymentMode-add').on('click',function(e){
    e.preventDefault();
    $('.paymentMode-title').html('Add Payment Mode');
    $('#paymentModeID').val('');
    $('#paymentModeName').val('');
    $('.paymentModeUpdate').addClass('d-none');
    $('.paymentModeSubmit').removeClass('d-none');
    });
// ------usertype add starts----
$('#paymentModeForm').on('submit',function(e){
   e.preventDefault();
    let id = $('#paymentModeID').val();
   let paymentmode = $('#paymentModeName').val();
   if(paymentmode == ''){
    $('#paymentModeName').focus();
   }else{
        if ($('.paymentModeUpdate').is(':visible')) {
            paymentModeUpdate(id); // Trigger update function when update btn is active
        } else {
            $.ajax({
                url: addPaymentMode,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{paymentmode:paymentmode},
                success:function(response){
                    if(response.success){
                        $('#addPaymentModeModel').modal('hide');
                        $('#paymentModeForm').removeClass('was-validated');
                        $('#paymentModeForm')[0].reset();
                        $('#paymentMode-table').DataTable().ajax.reload();
                        toastSuccessAlert('Payment Mode added successfully');
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
function paymentModeEdit(id){
$.ajax({
    url: getPaymentModeData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.paymentMode-title').html('Update Payment Mode');
            $('#paymentModeID').val(getData.id);
            $('#paymentModeName').val(getData.name);
            $('.paymentModeSubmit').addClass('d-none');
            $('.paymentModeUpdate').removeClass('d-none');
            $('#addPaymentModeModel').modal('show');
        }
    }

});
}

function paymentModeUpdate(id){
    let paymentmode = $('#paymentModeName').val();
    if(paymentmode == ''){
        $('#paymentModeName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updatePaymentModeData,
            type: "post",
            data: {
                id: id,
                paymentmode: paymentmode
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addPaymentModeModel').modal('hide');
                    $('#paymentModeForm').removeClass('was-validated');
                    $('#paymentModeForm')[0].reset();
                    $('#paymentMode-table').DataTable().ajax.reload();
                    toastSuccessAlert('Payment Mode updated successfully');
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
                $('#addPaymentModeModel').modal('hide');
                $('#paymentModeForm').removeClass('was-validated');
                $('#paymentModeForm')[0].reset();
                $('#paymentMode-table').DataTable().ajax.reload();
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

function paymentModeDelete(id){
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
                url: deletePaymentModeData,
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
                        $('#paymentMode-table').DataTable().ajax.reload();
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




