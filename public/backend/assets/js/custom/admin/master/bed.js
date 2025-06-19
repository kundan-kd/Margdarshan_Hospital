
// let table = new DataTable('#bed-table');
let table = $('#bed-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewBeds,
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
            data:'bedNumber',
            name:'bedNumber',
            orderable: true,
            searchable: true
        },
        {
            data:'bedGroup',
            name:'bedGroup',
            orderable: true,
            searchable: true
        },
        {
            data:'bedType',
            name:'bedType',
            orderable: true,
            searchable: true
        },
        {
            data:'bedFloor',
            name:'bedFloor',
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

$('.bed-add').on('click',function(e){
    e.preventDefault();
    $('.bed-title').html('Create Bed');
    $('#bedID').val('');
    $('#bedNumber').val('');
    $('#bedGroup').val('');
    $('#bedType').val('');
    $('#bedFloor').val('');
    $('.addBedUpdate').addClass('d-none');
    $('.addBedSubmit').removeClass('d-none');
    });
// ------bed add starts----
$('#addBedForm').on('submit',function(e){
   e.preventDefault();
   let id = $('#bedID').val();
   let bedNumber = $('#bedNumber').val();
   let bedGroup = $('#bedGroup').val();
   let bedType = $('#bedType').val();
   let bedFloor = $('#bedFloor').val();
    if(bedNumber == '' || bedGroup == '' || bedType == '' || bedFloor == ''){
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        if ($('.addBedUpdate').is(':visible')) {
            bedUpdate(id); // Trigger update function
        } else {
            $.ajax({
                url: addBed,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{bedNumber:bedNumber,bedGroup:bedGroup,bedType:bedType,bedFloor:bedFloor},
                success:function(response){
                    if(response.success){
                        $('#addBedModel').modal('hide');
                        $('#addBedForm').removeClass('was-validated');
                        $('#addBedForm')[0].reset();
                        $('#bed-table').DataTable().ajax.reload();
                        toastSuccessAlert('Bed added successfully');
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
// ------bed add ends----
// ------bed update starts ----
function bedEdit(id){
$.ajax({
    url: getBedData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.bed-title').html('Update Bed');
            $('#bedID').val(getData.id);
            $('#bedNumber').val(getData.bed_no);
            $('#bedGroup').val(getData.bed_group_id);
            $('#bedType').val(getData.bed_type_id);
            $('#bedFloor').val(getData.floor);
            $('.addBedSubmit').addClass('d-none');
            $('.addBedUpdate').removeClass('d-none');
            $('#addBedModel').modal('show');
        }
    }

});
}

function bedUpdate(id){
    let bedNumber = $('#bedNumber').val();
    let bedGroup = $('#bedGroup').val();
    let bedType = $('#bedType').val();
    let bedFloor = $('#bedFloor').val();
    if(bedNumber == '' || bedGroup == '' || bedType == '' || bedFloor == ''){
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateBedData,
            type: "POST",
            data: {
                id:id,bedNumber:bedNumber,bedGroup:bedGroup,bedType:bedType,bedFloor:bedFloor},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success){
                    $('#addBedModel').modal('hide');
                    $('#addBedForm').removeClass('was-validated');
                    $('#addBedForm')[0].reset();
                    $('#bed-table').DataTable().ajax.reload();
                    toastSuccessAlert('Bed updated successfully');
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
            if(response.success){
                    $('#addBedModel').modal('hide');
                    $('#addBedForm').removeClass('was-validated');
                    $('#addBedForm')[0].reset();
                    $('#bed-table').DataTable().ajax.reload();
                    toastSuccessAlert('Status updated successfully');
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

function bedDelete(id){
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
                url: deleteBedData,
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
                        $('#bed-table').DataTable().ajax.reload();
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




