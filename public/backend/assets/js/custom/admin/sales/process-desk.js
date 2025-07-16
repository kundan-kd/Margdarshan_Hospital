let table_process_desk_list = $('#process-desk-table').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url:viewProcessDeskLeads,
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
            data:'address',
            name:'address',
            orderable: false,
            searchable: true
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
            data: 'naration',
            name: 'naration'
        },
        {
            data: 'follow_up',
            name: 'follow_up'
        },
        {
            data: 'lead_status',
            name: 'lead_status'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});

function addNaration(id){
    $('#processDesk-leadId').val(id);
    $('#processDesk-naration').val('');
    $('.narationSpinn').addClass('d-none');
    $('.narationSubmit').removeClass('d-none');
    $('#add-narration').modal('show');
}
function narationSubmit(lead_id){
    let naration_check = validateField('processDesk-naration','input');
    if(naration_check == true){
        let naration = $('#processDesk-naration').val();
        $('.narationSubmit').addClass('d-none');
        $('.narationSpinn').removeClass('d-none');
        $.ajax({
            url:narationAdd,
            type: "POST",
            data:{lead_id:lead_id,naration:naration},
            success:function(response){
                console.log(response);
                if(response.success){
                    $('#add-narration').modal('hide');
                    toastSuccessAlert(response.success);
                    $('#process-desk-table').DataTable().ajax.reload();
                }else{
                    toastErrorAlert(response.error_success);
                    $('.narationSpinn').addClass('d-none');
                    $('.narationSubmit').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log('Please fill required fields');
    }
}
function getPrevNarations(id){
    $.ajax({
        url:getNarationData,
        type:"POST",
        data:{id:id},
        success: function(response) {
            let getData = response.data;
            let i = 1;
            let tableBody = $('#narationHistory tbody');
            tableBody.empty(); // Optional: clear existing rows if needed
            let formatter = new Intl.DateTimeFormat('en-IN', {
                timeZone: 'Asia/Kolkata',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            getData.forEach(function(element) {
                let readableDate = formatter.format(new Date(element.created_at));
                let row = `
                    <tr>
                        <td>${i}</td>
                        <td>${element.naration}</td>
                         <td>${readableDate}</td>
                    </tr>
                `;
                tableBody.append(row);
                i++;
            });
        },
        error:function(xhr,error){
            console.log(xhr.responseText);
            alert("An error occured: "+error);
        }
    });
}
function transferTo(id){
    $('#leadTransfer-leadId').val(id);
    $('#leadTransfer-teamId').val('');
    $('#leadTransfer-reason').val('');
    $('.transterToSpinn').addClass('d-none');
    $('.transterToSubmit').removeClass('d-none');
    $('#transfer-to').modal('show');
}
function transferToSubmit(lead_id){
    let team_check = validateField('leadTransfer-teamId','select');
    let reason_check = validateField('leadTransfer-reason','input');
    if(team_check == true && reason_check == true){
        let team_id = $('#leadTransfer-teamId').val();
        let reason = $('#leadTransfer-reason').val();
        $('.transterToSubmit').addClass('d-none');
        $('.transterToSpinn').removeClass('d-none');
        $.ajax({
            url:tranferToDataSubmit,
            type:"POST",
            data:{lead_id:lead_id,team_id:team_id,reason:reason},
            success:function(response){
                if(response.success){
                    $('#transfer-to').modal('hide');
                    toastSuccessAlert(response.success);
                    $('#process-desk-table').DataTable().ajax.reload();
                    $('.transterToSpinn').addClass('d-none');
                    $('.transterToSubmit').removeClass('d-none');
                }else{
                    toastErrorAlert(response.error_success);
                    $('.transterToSpinn').addClass('d-none');
                    $('.transterToSubmit').removeClass('d-none');
                }                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log('Please fill all required fields');
    }
}
function followup(id){
    $('#followup-leadId').val(id);
    $('#followup-date').val('');
    $('#add-nextFollowUp').modal('show');
}
function followupSubmit(id){
    let date_check = validateField('followup-date','select');
    if(date_check == true){
        let fdate = $('#followup-date').val();
        $('.followupSubmit').addClass('d-none');
        $('.followupSpinn').removeClass('d-none');
        $.ajax({
            url:followupDateSubmit,
            type:"POST",
            data:{lead_id:id,fdate:fdate},
            success:function(response){
                if(response.success){
                    $('#add-nextFollowUp').modal('hide');
                    toastSuccessAlert(response.success);
                    $('#process-desk-table').DataTable().ajax.reload();
                    $('.followupSpinn').addClass('d-none');
                    $('.followupSubmit').removeClass('d-none');
                }else{
                    toastErrorAlert(response.error_success);
                    $('.followupSpinn').addClass('d-none');
                    $('.followupSubmit').removeClass('d-none');
                }                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }else{
        console.log('Please choose any date');
    }
}
function deleteLead(id){
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
                url: deleteLeadData,
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
                        $('#process-desk-table').DataTable().ajax.reload();
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