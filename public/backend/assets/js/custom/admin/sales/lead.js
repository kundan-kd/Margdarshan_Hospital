$('#lead-addForm').on('submit',function(e){
    e.preventDefault();
    let name = $('#lead-name').val();
    let mobile = $('#lead-mobile').val();
    let source = $('#lead-source').val();
    let address = $('#lead-address').val();
    let city = $('#lead-city').val();
    let state = $('#lead-state').val();
    let pin = $('#lead-pin').val();
     if(name == '' || mobile == '' || source =='' || address =='' || city =='' || state =='' || pin ==''){
        $('.needs-validation').addClass('was-validated');
    }else{
        $('.leadSubmit').addClass('d-none');
        $('.leadSpinn').removeClass('d-none');
        $.ajax({
            url: addLead,
            type:"POST",
            data:{
                name:name,mobile:mobile,source:source,address:address,city:city,state:state,pin:pin
            },
            success:function(response){
                if(response.success){
                    toastSuccessAlert(response.success);
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else if(response.error_validation){
                    toastWarningAlert(response.success);
                    $('.leadSpinn').addClass('d-none');
                    $('.leadSubmit').removeClass('d-none');
                }else if(response.error_success){
                    toastErrorAlert(response.error_success);
                    $('.leadSpinn').addClass('d-none');
                    $('.leadSubmit').removeClass('d-none');
                }else{
                    toastSuccessAlert('something went wrong');
                    $('.leadSpinn').addClass('d-none');
                    $('.leadSubmit').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
});
 function clearBulkInput(){
    $('#lead-nameAppend').val('');
    $('#lead-mobileAppend').val('');
    $('#lead-sourceAppend').val('');
    $('#lead-addressAppend').val('');
    $('#lead-cityAppend').val('');
    $('#lead-stateAppend').val('');
    $('#lead-pinAppend').val('');
 }
$('#lead-appendForm').on('submit',function(e){
    e.preventDefault();
    let name = $('#lead-nameAppend').val();
    let mobile = $('#lead-mobileAppend').val();
    let source = $('#lead-sourceAppend').val();
    let address = $('#lead-addressAppend').val();
    let city = $('#lead-cityAppend').val();
    let state = $('#lead-stateAppend').val();
    let pin = $('#lead-pinAppend').val();
    if(name == '' || mobile == '' || source =='' || address =='' || city =='' || state =='' || pin ==''){
        $('.needs-validation').addClass('was-validated');
    }else{
        let content = '';
            content += `<tr>
            <td><input type="text" name="bulkLead-name[]" class="form-control form-control-sm" style="width: 100px;" value="${name}"></td>
            <td><input type="text" name="bulkLead-mobile[]" class="form-control form-control-sm" style="width: 100px;" value="${mobile}"></td>
            <td><input type="text" name="bulkLead-source[]" class="form-control form-control-sm" style="width: 100px;" value="${source}"></td>
            <td><input type="text" name="bulkLead-address[]" class="form-control form-control-sm" style="width: 100px;" value="${address}"></td>
            <td><input type="text" name="bulkLead-city[]" class="form-control form-control-sm" style="width: 100px;" value="${city}"></td>
            <td><input type="text" name="bulkLead-state[]" class="form-control form-control-sm" style="width: 100px;" value="${state}"></td>
            <td><input type="text" name="bulkLead-pin[]" class="form-control form-control-sm" style="width: 100px;" value="${pin}"></td>
            <td>
                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" onclick="removeLeadRow(this)">
                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
            </td>
            </tr>`;
        $('.appendLeadData').append(content);   
        $('.bulkLeadSubmit').removeClass('d-none');   
        clearBulkInput();
        $('.needs-validation').removeClass('was-validated');    
    }    
});
function removeLeadRow(x){
    $(x).closest('tr').remove();
}
function submitBulkLead(){
 let name = $('input[name="bulkLead-name[]"').map(function(){return $(this).val()}).get();
 let mobile = $('input[name="bulkLead-mobile[]"').map(function(){return $(this).val()}).get();
 let source = $('input[name="bulkLead-source[]"').map(function(){return $(this).val()}).get();
 let address = $('input[name="bulkLead-address[]"').map(function(){return $(this).val()}).get();
 let city = $('input[name="bulkLead-city[]"').map(function(){return $(this).val()}).get();
 let state = $('input[name="bulkLead-state[]"').map(function(){return $(this).val()}).get();
 let pin = $('input[name="bulkLead-pin[]"').map(function(){return $(this).val()}).get();
    if(name.length === 0){
        toastErrorAlert('No data found');
        return;
    }else{
        $('.bulkLeadSubmit').addClass('d-none');
        $('.bulkLeadSpinn').removeClass('d-none');
        $.ajax({
            url: addBulkLead,
            type:"POST",
            data:{
                name:name,mobile:mobile,source:source,address:address,city:city,state:state,pin:pin
            },
            success:function(response){
                if(response.success){
                    toastSuccessAlert(response.success);
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else if(response.error_validation){
                    toastWarningAlert(response.success);
                    $('.bulkLeadSpinn').addClass('d-none');
                    $('.bulkLeadSubmit').removeClass('d-none');
                }else if(response.error_success){
                    toastErrorAlert(response.error_success);
                    $('.bulkLeadSpinn').addClass('d-none');
                    $('.bulkLeadSubmit').removeClass('d-none');
                }else{
                    toastSuccessAlert('something went wrong');
                    $('.bulkLeadSpinn').addClass('d-none');
                    $('.bulkLeadSubmit').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
}

let table_trash_list = $('#lead-trash-lists').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url:viewTrashLead,
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
        {
            data: 'deleted_at',
            name: 'deleted_at'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});
function unTrashLead(id){
     Swal.fire({
        title: "Are you sure to restore from Trash?",
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
                url: restoreLeadData,
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire("Restored!", response.success, "success");
                        $('#lead-trash-lists').DataTable().ajax.reload();
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