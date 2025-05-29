
// let table = new DataTable('#usertype-table');
let table = $('#vendor-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewVendor,
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
            data:'phone',
            name:'phone',
              orderable: false,
            searchable: false
        },
        {
            data:'email',
            name:'email',
              orderable: false,
            searchable: false
        },
        {
            data:'address',
            name:'address',
              orderable: false,
            searchable: false
        },
        {
            data:'gst_number',
            name:'gst_number',
              orderable: false,
            searchable: false
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

$('.vendor-add').on('click',function(e){
    e.preventDefault();
    $('.vendor-title').html('Add Vendor');
    $('#vendorID').val('');
    $('#vendorName').val('');
    $('.vendorUpdate').addClass('d-none');
    $('.vendorSubmit').removeClass('d-none');
    });

$('#vendorForm').on('submit',function(e){
   e.preventDefault();
   let id = $('#vendorID').val();
   let name = $('#vendorName').val();
   let phone = $('#vendorPhone').val();
   let email = $('#vendorEmail').val();
   let address = $('#vendorAddress').val();
   let gst = $('#vendorGST').val();
   if(name == '' || phone == '' || email == '' || address == '' || gst == ''){
    $('#vendorName').focus();
    $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
   }else{
        if ($('.vendorUpdate').is(':visible')) {
            vendorUpdate(id); // Trigger update function when update btn is active
        } else {
            $.ajax({
                url: addVendor,
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{name:name, phone:phone, email:email, address:address, gst:gst},
                success:function(response){
                    if(response.success){
                        $('#addVendorModel').modal('hide');
                        $('#vendorForm').removeClass('was-validated');
                        $('#vendorForm')[0].reset();
                        $('#vendor-table').DataTable().ajax.reload();
                        toastSuccessAlert('Vendor added successfully');
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

function vendorEdit(id){
$.ajax({
    url: getVendorData,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data:{id:id},
    success:function(response){
        if(response.success){
            getData = response.data[0];
            $('.vendor-title').html('Update Vendor');
            $('#vendorID').val(getData.id);
            $('#vendorName').val(getData.name);
            $('#vendorPhone').val(getData.phone);
            $('#vendorEmail').val(getData.email);
            $('#vendorAddress').val(getData.address);
            $('#vendorGST').val(getData.gst_number);
            $('.vendorSubmit').addClass('d-none');
            $('.vendorUpdate').removeClass('d-none');
            $('#addVendorModel').modal('show');
        }
    }
});
}

function vendorUpdate(id){
    let name = $('#vendorName').val();
    let phone = $('#vendorPhone').val();
    let email = $('#vendorEmail').val();
    let address = $('#vendorAddress').val();
    let gst = $('#vendorGST').val();
    if(name == '' || phone == '' || email == '' || address == '' || gst == ''){
    $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateVendorData,
            type: "post",
            data: {
                id: id,name:name, phone:phone, email:email, address:address, gst:gst
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addVendorModel').modal('hide');
                    $('#vendorForm').removeClass('was-validated');
                    $('#vendorForm')[0].reset();
                    $('#vendor-table').DataTable().ajax.reload();
                    toastSuccessAlert('Vendor updated successfully');
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
                   $('#addVendorModel').modal('hide');
                    $('#vendorForm').removeClass('was-validated');
                    $('#vendorForm')[0].reset();
                    $('#vendor-table').DataTable().ajax.reload();
                    toastSuccessAlert('Vendor status updated successfully');
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

function vendorDelete(id){
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
                url: deleteVendor,
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
                       $('#vendor-table').DataTable().ajax.reload();
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




