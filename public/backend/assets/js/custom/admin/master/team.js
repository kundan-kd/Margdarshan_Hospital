
// let table = new DataTable('#team-table');
let table = $('#team-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewteams,
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

$('.team-add').on('click',function(e){
    e.preventDefault();
    $('.team-title').html('Add User Type');
    $('#teamID').val('');
    $('#teamName').val('');
    $('.addTeamUpdate').addClass('d-none');
    $('.addTeamSubmit').removeClass('d-none');
    $('.needs-validation').removeClass('was-validated');
    });
// ------team add starts----
$('#addTeamForm').on('submit',function(e){
   e.preventDefault();
   let team = $('#teamName').val();
   let id = $('#teamID').val();
   if(team == ''){
    $('#teamName').focus();
   }else{
        if ($('.addTeamUpdate').is(':visible')) {
            teamUpdate(id); // Trigger update function
        } else {
    $.ajax({
        url: addTeam,
        method:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{team:team},
        success:function(response){
            // console.log(response);
            if(response.success){
                $('#addTeamModel').modal('hide');
                $('#addTeamForm').removeClass('was-validated');
                $('#addTeamForm')[0].reset();
                $('#team-table').DataTable().ajax.reload();
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
// ------team add ends----
// ------team update starts ----
function teamEdit(id){
$.ajax({
    url: getteamData,
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
            $('.team-title').html('Update User Type');
            $('#teamID').val(getData.id);
            $('#teamName').val(getData.name);
            $('.addTeamSubmit').addClass('d-none');
            $('.addTeamUpdate').removeClass('d-none');
            $('#addTeamModel').modal('show');
        }
    }

});
}

function teamUpdate(id){
    let team = $('#teamName').val();
    if(team == ''){
        $('#teamName').focus();
        $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
    }else{
        $.ajax({
            url: updateTeamData,
            type: "post",
            data: {
                id: id,
                team: team
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addTeamModel').modal('hide');
                    $('#addTeamForm').removeClass('was-validated');
                    $('#addTeamForm')[0].reset();
                    $('#team-table').DataTable().ajax.reload();
                    toastSuccessAlert('Team updated successfully');
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
                $('#addTeamModel').modal('hide');
                $('#addTeamForm').removeClass('was-validated');
                $('#addTeamForm')[0].reset();
                $('#team-table').DataTable().ajax.reload();
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

function teamDelete(id){
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
                url: deleteteamData,
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
                        $('#team-table').DataTable().ajax.reload();
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




