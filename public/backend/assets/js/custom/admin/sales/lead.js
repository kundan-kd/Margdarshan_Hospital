$('#lead-addForm').on('submit',function(e){
    e.preventDefault();
    let name = $('#lead-name').val();
    let mobile = $('#lead-mobile').val();
    let source = $('#lead-source').val();
    let address = $('#lead-address').val();
    let city = $('#lead-city').val();
    let state = $('#lead-state').val();
    let pin = $('#lead-pin').val();
    let team = $('#lead-team').val();
    $('.leadSubmit').addClass('d-none');
    $('.leadSpinn').removeClass('d-none');
    $.ajax({
        url: addLead,
        type:"POST",
        data:{
            name:name,mobile:mobile,source:source,address:address,city:city,state:state,pin:pin,team:team
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
        }
    });
});
 function clearBulkInput(){
    $('#lead-nameAppend').val('');
    $('#lead-mobileAppend').val('');
    $('#lead-sourceAppend').val('');
    $('#lead-addressAppend').val('');
    $('#lead-cityAppend').val('');
    $('#lead-stateAppend').val('');
    $('#lead-pinAppend').val('');
    $('#lead-teamAppend').val('');
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
    let team = $('#lead-teamAppend').val();
    if(name == '' || mobile == '' || source =='' || address =='' || city =='' || state =='' || pin =='' || team ==''){
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
            <td><input type="text" name="bulkLead-team[]" class="form-control form-control-sm" style="width: 100px;" value="${team}"></td>
            <td>
                <button class="mx-1 bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle" data-bs-toggle="modal" data-bs-target="#assign-team">
                <i class="ri-team-line" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-success" data-bs-title="Assign to"></i>
                </button>
                <button class="mx-1 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-inline-flex justify-content-center align-items-center rounded-circle">
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

function submitBulkLead(){
 let name = $('input[name="bulkLead-name[]"').map(function(){return $(this).val()}).get();
 let mobile = $('input[name="bulkLead-mobile[]"').map(function(){return $(this).val()}).get();
 let source = $('input[name="bulkLead-source[]"').map(function(){return $(this).val()}).get();
 let address = $('input[name="bulkLead-address[]"').map(function(){return $(this).val()}).get();
 let city = $('input[name="bulkLead-city[]"').map(function(){return $(this).val()}).get();
 let state = $('input[name="bulkLead-state[]"').map(function(){return $(this).val()}).get();
 let pin = $('input[name="bulkLead-pin[]"').map(function(){return $(this).val()}).get();
 let team = $('input[name="bulkLead-team[]"').map(function(){return $(this).val()}).get();

    $('.bulkLeadSubmit').addClass('d-none');
    $('.bulkLeadSpinn').removeClass('d-none');
    $.ajax({
        url: addBulkLead,
        type:"POST",
        data:{
            name:name,mobile:mobile,source:source,address:address,city:city,state:state,pin:pin,team:team
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
        }
    });
}

let table_lead_list = $('#lead-lists').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url:viewLeads,
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
            data: 'team',
            name: 'team'
        },
    ]
});