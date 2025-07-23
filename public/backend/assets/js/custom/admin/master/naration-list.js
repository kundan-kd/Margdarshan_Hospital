
// let table = new DataTable('#narationList-table');
let table = $('#narationList-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewNarationLists,
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

$('.narationList-add').on('click',function(e){
    e.preventDefault();
    $('.narationList-title').html('Add Naration Title');
    $('#narationID').val('');
    $('#naration').val('');
    $('.narationListUpdate').addClass('d-none');
    $('.narationListSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------narationList add starts----
$('#addNarationListForm').on('submit',function(e){
   e.preventDefault();
   let narationList = $('#naration').val();
   let id = $('#narationID').val();
   if(narationList == ''){
    $('#naration').focus();
   }else{
        if ($('.narationListUpdate').is(':visible')) {
            narationListUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addNarationList,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{narationList:narationList},
        success:function(response){
            // console.log(response);
            if(response.success){
                $('#addNarationListModel').modal('hide');
                $('#addNarationListForm').removeClass('was-validated');
                $('#addNarationListForm')[0].reset();
                $('#narationList-table').DataTable().ajax.reload();
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
// ------narationList add ends----
// ------narationList update starts ----
function narationListEdit(id){
$.ajax({
    url: getNarationListData,
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
            $('.narationList-title').html('Update Narration');
            $('#narationID').val(getData.id);
            $('#naration').val(getData.naration);
            $('.narationListSubmit').addClass('d-none');
            $('.narationListUpdate').removeClass('d-none');
            $('#addNarationListModel').modal('show');
        }
    }

});
}

function narationListUpdate(id){
    let narationList = $('#naration').val();
    if(narationList == ''){
        $('#naration').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateNarationListData,
            type: "post",
            data: {
                id: id,
                narationList: narationList
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addNarationListModel').modal('hide');
                    $('#addNarationListForm').removeClass('was-validated');
                    $('#addNarationListForm')[0].reset();
                    $('#narationList-table').DataTable().ajax.reload();
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
                $('#addNarationListModel').modal('hide');
                $('#addNarationListForm').removeClass('was-validated');
                $('#addNarationListForm')[0].reset();
                $('#narationList-table').DataTable().ajax.reload();
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



