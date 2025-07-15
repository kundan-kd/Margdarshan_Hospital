function singleLeadAssign(leadId){
    $('#leadId').val(leadId);
    $('#single-lead-assign-team').modal('show');
}
function singleLeadAssignSubmit(userId){
    let user_check = validateField('lead-center-teamMemberId','select');
    if(user_check == true){
        let leadId = $('#leadId').val();
        $('.singleLeadAssignSubmit').addClass('d-none');
        $('.singleLeadAssignSpinn').removeClass('d-none');
        $.ajax({
            url: assignSingleLead,
            type:"POST",
            data:{user_id:userId,lead_id:leadId},
            success:function(response){
                $('.singleLeadAssignSpinn').addClass('d-none');
                $('.singleLeadAssignSubmit').removeClass('d-none');
                if(response.success){
                    toastSuccessAlert(response.success);
                    $('#lead-center-lists').DataTable().ajax.reload();
                    $('#single-lead-assign-team').modal('hide');
                }else{
                    toastErrorAlert(response.error_success);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log('Please fill required field');
    } 
}
let table_lead_center_list = $('#lead-center-lists').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url:viewSingleAssignLeads,
        type:"POST",
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
            data:'mobile',
            name:'mobile'
        },
        {
            data:'source',
            name:'source'
        },
        {
            data:'address',
            name:'address',
            orderable: false,
            searchable: true
        },
        {
            data:'city',
            name:'city'
        },
        {
            data:'state',
            name:'state'
        },
        {
            data: 'pin',
            name: 'pin'
        },
        {
            data: 'assign_to',
            name: 'assign_to'
        },
        // {
        //     data: 'assign_date',
        //     name: 'assign_date'
        // },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});
function trashLead(id){
    Swal.fire({
        title: "Are you sure to move to Trash?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
        customClass: {
            title: 'swal-title-custom'
          }
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: trashLeadData,
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
                        $('#lead-center-lists').DataTable().ajax.reload();
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
function bulkAssign(){
     window.location.href='process-center-lead-center-bulk/';
}