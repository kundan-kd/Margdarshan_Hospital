
let table_lead_list = $('#lead-bulk-lists').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url:viewBulkAssignLeads,
        type:"POST",
        error: function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: ' + thrown);
        }
    },
    columns:[
        {
            data:'select',
            name:'select',
            orderable: false,
            searchable: false
        },
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
    ]
});

function bulkLeadAssignData(){
    let user_id = $('#lead-center-userID').val();
    let lead_id = $('input[name="leadId[]"]:checked').map(function(){return $(this).val()}).get();
    if(user_id == ''){
        $('#lead-center-userID').focus();
        toastErrorAlert('Please select team member');
    }else if(lead_id.length === 0){
        toastErrorAlert('Please tick at least one lead to assign');
    }else{
        $('.leadAssignSubmit').addClass('d-none');
        $('.leadAssignSpinn').removeClass('d-none');
        $.ajax({
            url: assignBulkLeads,
            type:"POST",
            data:{user_id:user_id,lead_id:lead_id},
            success:function(response){
                $('.leadAssignSpinn').addClass('d-none');
                $('.leadAssignSubmit').removeClass('d-none');
                if(response.success){
                     toastSuccessAlert(response.success);
                    $('#lead-bulk-lists').DataTable().ajax.reload();
                }else{
                    toastErrorAlert(response.error_success);
                    
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
}