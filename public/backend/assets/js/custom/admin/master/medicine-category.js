
// let table = new DataTable('#usertype-table');
let table = $('#medicineCategory-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewMedicineCategory,
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

$('.medicineCategory-add').on('click',function(e){
    e.preventDefault();
    $('.medicineCategory-title').html('Add Medicine Category');
    $('#medicineCategoryID').val('');
    $('#medicineCategoryName').val('');
    $('.medicineCategoryUpdate').addClass('d-none');
    $('.medicineCategorySubmit').removeClass('d-none');
    });

$('#medicineCategoryForm').on('submit',function(e){
   e.preventDefault();
   let category = $('#medicineCategoryName').val();
   if(category == ''){
    $('#medicineCategoryName').focus();
   }else{
    $.ajax({
        url: addMedicineCategory,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{category:category},
        success:function(response){
            if(response.success){
                $('#addMedicineCategoryModel').modal('hide');
                $('#medicineCategoryForm').removeClass('was-validated');
                $('#medicineCategoryForm')[0].reset();
                $('#medicineCategory-table').DataTable().ajax.reload();
                toastSuccessAlert('Medicine category added successfully');
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
function medicineCategoryEdit(id){
$.ajax({
    url: getMedicineCategoryData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.medicineCategory-title').html('Update Medicine Category');
            $('#medicineCategoryID').val(getData.id);
            $('#medicineCategoryName').val(getData.name);
            $('.medicineCategorySubmit').addClass('d-none');
            $('.medicineCategoryUpdate').removeClass('d-none');
            $('#addMedicineCategoryModel').modal('show');
        }
    }
});
}

function medicineCategoryUpdate(id){
    let category = $('#medicineCategoryName').val();
    if(category == ''){
        $('#medicineCategoryName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateMedicineCategoryData,
            type: "post",
            data: {
                id: id,
                category: category
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addMedicineCategoryModel').modal('hide');
                    $('#medicineCategoryForm').removeClass('was-validated');
                    $('#medicineCategoryForm')[0].reset();
                    $('#medicineCategory-table').DataTable().ajax.reload();
                    toastSuccessAlert('Modicine Category updated successfully');
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
                $('#addMedicineCategoryModel').modal('hide');
                $('#medicineCategoryForm').removeClass('was-validated');
                $('#medicineCategoryForm')[0].reset();
                $('#medicineCategory-table').DataTable().ajax.reload();
                toastSuccessAlert('Modicine Category updated successfully');
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

function medicineCategoryDelete(id){
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
                url: deleteMedicineCategory,
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
                       $('#medicineCategory-table').DataTable().ajax.reload();
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




