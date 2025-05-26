
// let table = new DataTable('#usertype-table');
let table = $('#unit-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewUnit,
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
            data:'unit',
            name:'unit'
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

$('.unit-add').on('click',function(e){
    e.preventDefault();
    $('.unit-title').html('Add Unit');
    $('#unitID').val('');
    $('#unitName').val('');
    $('#unit').val('');
    $('.unitUpdate').addClass('d-none');
    $('.unitSubmit').removeClass('d-none');
    });

$('#unitForm').on('submit',function(e){
   e.preventDefault();
   let unitname = $('#unitName').val();
   let unit = $('#unit').val();
   if(unitname == ''){
    $('#unitName').focus();
   }else if(unit == ''){
        $('#unit').focus();
   }else{
    $.ajax({
        url: addUnit,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{unitname:unitname,unit:unit},
        success:function(response){
            if(response.success){
                $('#addUnitModel').modal('hide');
                $('#unitForm').removeClass('was-validated');
                $('#unitForm')[0].reset();
                $('#unit-table').DataTable().ajax.reload();
                toastSuccessAlert('Unit added successfully');
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
function unitEdit(id){
$.ajax({
    url: getUnitData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.unit-title').html('Update Unit');
            $('#unitID').val(getData.id);
            $('#unitName').val(getData.name);
            $('#unit').val(getData.unit);
            $('.unitSubmit').addClass('d-none');
            $('.unitUpdate').removeClass('d-none');
            $('#addUnitModel').modal('show');
        }
    }
});
}

function unitUpdate(id){
    let unitname = $('#unitName').val();
    let unit = $('#unit').val();
    if(unitname == ''){
        $('#unitName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else if(unit == ''){
        $('#unit').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }
    else{
        $.ajax({
            url: updateUnitData,
            type: "post",
            data: {
                id: id,
                unitname:unitname,
                unit:unit
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addUnitModel').modal('hide');
                    $('#unitForm').removeClass('was-validated');
                    $('#unitForm')[0].reset();
                    $('#unit-table').DataTable().ajax.reload();
                    toastSuccessAlert('Unit updated successfully');
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
                    $('#addUnitModel').modal('hide');
                    $('#unitForm').removeClass('was-validated');
                    $('#unitForm')[0].reset();
                    $('#unit-table').DataTable().ajax.reload();
                    toastSuccessAlert('Unit status updated successfully');
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

function unitDelete(id){
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
                url: deleteUnit,
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
                       $('#unit-table').DataTable().ajax.reload();
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




