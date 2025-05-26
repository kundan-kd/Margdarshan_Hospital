
// let table = new DataTable('#usertype-table');
let table = $('#company-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewCompany,
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

$('.company-add').on('click',function(e){
    e.preventDefault();
    $('.company-title').html('Add Company');
    $('#companyID').val('');
    $('#companyName').val('');
    $('.companyUpdate').addClass('d-none');
    $('.companySubmit').removeClass('d-none');
    });

$('#companyForm').on('submit',function(e){
   e.preventDefault();
   let company = $('#companyName').val();
   if(company == ''){
    $('#companyName').focus();
   }else{
    $.ajax({
        url: addCompany,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{company:company},
        success:function(response){
            if(response.success){
                $('#addCompanyModel').modal('hide');
                $('#companyForm').removeClass('was-validated');
                $('#companyForm')[0].reset();
                $('#company-table').DataTable().ajax.reload();
                toastSuccessAlert('Company added successfully');
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
});

// // ------usertype update starts ----
function companyEdit(id){
$.ajax({
    url: getCompanyData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.company-title').html('Update Company');
            $('#companyID').val(getData.id);
            $('#companyName').val(getData.name);
            $('.companySubmit').addClass('d-none');
            $('.companyUpdate').removeClass('d-none');
            $('#addCompanyModel').modal('show');
        }
    }
});
}

function companyUpdate(id){
    let company = $('#companyName').val();
    if(company == ''){
        $('#companyName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateCompanyData,
            type: "post",
            data: {
                id: id,
                company:company
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addCompanyModel').modal('hide');
                    $('#companyForm').removeClass('was-validated');
                    $('#companyForm')[0].reset();
                    $('#company-table').DataTable().ajax.reload();
                    toastSuccessAlert('Company updated successfully');
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
                 $('#addCompanyModel').modal('hide');
                    $('#companyForm').removeClass('was-validated');
                    $('#companyForm')[0].reset();
                    $('#company-table').DataTable().ajax.reload();
                    toastSuccessAlert('Company status updated successfully');
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

function companyDelete(id){
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
                url: deleteCompany,
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
                       $('#company-table').DataTable().ajax.reload();
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




