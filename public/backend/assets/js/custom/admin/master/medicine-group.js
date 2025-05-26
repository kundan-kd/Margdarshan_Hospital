
// let table = new DataTable('#usertype-table');
let table = $('#medicineGroup-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewMedicineGroup,
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

$('.medicineGroup-add').on('click',function(e){
    e.preventDefault();
    $('.medicineGroup-title').html('Add Medicine Group');
    $('#medicineGroupID').val('');
    $('#medicineGroupName').val('');
    $('.medicineGroupUpdate').addClass('d-none');
    $('.medicineGroupSubmit').removeClass('d-none');
    });

$('#medicineGroupForm').on('submit',function(e){
   e.preventDefault();
   let group = $('#medicineGroupName').val();
   if(group == ''){
    $('#medicineGroupName').focus();
   }else{
    $.ajax({
        url: addMedicineGroup,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{group:group},
        success:function(response){
            if(response.success){
                $('#addMedicineGroupModel').modal('hide');
                $('#medicineGroupForm').removeClass('was-validated');
                $('#medicineGroupForm')[0].reset();
                $('#medicineGroup-table').DataTable().ajax.reload();
                toastSuccessAlert('Medicine Group added successfully');
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
function medicineGroupEdit(id){
$.ajax({
    url: getMedicineGroupData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.medicineGroup-title').html('Update Medicine Group');
            $('#medicineGroupID').val(getData.id);
            $('#medicineGroupName').val(getData.name);
            $('.medicineGroupSubmit').addClass('d-none');
            $('.medicineGroupUpdate').removeClass('d-none');
            $('#addMedicineGroupModel').modal('show');
        }
    }
});
}

function medicineGroupUpdate(id){
    let group = $('#medicineGroupName').val();
    if(group == ''){
        $('#medicineGroupName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateMedicineGroupData,
            type: "post",
            data: {
                id: id,
                group: group
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addMedicineGroupModel').modal('hide');
                    $('#medicineGroupForm').removeClass('was-validated');
                    $('#medicineGroupForm')[0].reset();
                    $('#medicineGroup-table').DataTable().ajax.reload();
                    toastSuccessAlert('Modicine Group updated successfully');
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
                $('#addMedicineGroupModel').modal('hide');
                    $('#medicineGroupForm').removeClass('was-validated');
                    $('#medicineGroupForm')[0].reset();
                    $('#medicineGroup-table').DataTable().ajax.reload();
                    toastSuccessAlert('Modicine Group status updated successfully');
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

function medicineGroupDelete(id){
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
                url: deleteMedicineGroup,
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
                       $('#medicineGroup-table').DataTable().ajax.reload();
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




