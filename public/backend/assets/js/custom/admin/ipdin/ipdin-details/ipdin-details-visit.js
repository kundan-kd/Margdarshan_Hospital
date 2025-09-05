function resetVisit(){
    $('#ipdVisitId').val('');
    $('#ipdVisit-symptoms').val('');
    $('#ipdVisit-previousMedIssue').val('');
    $('#ipdVisit-note').val('');
    $('#ipdVisit-admissionDate').val('');
    $('#ipdVisit-oldPatient').val('');
    $('#ipdVisit-consultDoctor').val('');
    $('#ipdVisit-charge').val('');
    $('#ipdVisit-discount').val('');
    $('#ipdVisit-tax').val('');
    $('#ipdVisit-amount').val('');
    $('#ipdVisit-paymentMode').val('');
    $('#ipdVisit-refNum').val('');
    $('#ipdVisit-paidAmount').val('');
    $('#ipdVisit-AlreadypaidAmount').val('');
    $('.ipdVisitSubmit').removeClass('d-none');
    $('.ipdVisitUpdate').addClass('d-none');
    $('.ipdVisit-AlreadypaidAmountCls').addClass('d-none');
}
function calculateAmount(){
    let charge = $('#ipdVisit-charge').val() || 0;
    let discount = $('#ipdVisit-discount').val() || 0;
    let tax = $('#ipdVisit-tax').val() || 0;
    let discountAmount = (charge * discount)/100;
     let amountAftreDiscount = charge - discountAmount;
    let taxAmount = (amountAftreDiscount * tax)/100;
    let finelAmount = (charge - discountAmount) + taxAmount;
    $('#ipdVisit-amount').val(finelAmount);
}
function checkIpdVisitPaidAmount(){
    let amount = $('#ipdVisit-amount').val() || 0;
    let paidAmount = $('#ipdVisit-paidAmount').val() || 0;
    let alreadypaidAmount = $('#ipdVisit-AlreadypaidAmount').val() || 0;
    if((parseFloat(paidAmount) + parseFloat(alreadypaidAmount)) > amount){
        $('.ipdVisitSubmit').prop('disabled', true);
        $('.ipdVisitUpdate').prop('disabled', true);
        toastErrorAlert('Payment amount exceeds total amount');
    }else{
         $('.ipdVisitSubmit').prop('disabled', false);
         $('.ipdVisitUpdate').prop('disabled', false);
    }
}
$('#ipdVisit-modelForm').on('submit',function(e){
 e.preventDefault();
    let consultDoctor_check = validateField('ipdVisit-consultDoctor', 'select');
    let charge_check = validateField('ipdVisit-charge', 'amount');
    if(consultDoctor_check === true && charge_check === true){ 
        let patientId = $('#patient_Id').val();
        let consultDoctor = $('#ipdVisit-consultDoctor').val();
        let charge = $('#ipdVisit-charge').val();
        let discount = $('#ipdVisit-discount').val();
        let taxPer = $('#ipdVisit-tax').val();
        let amount = $('#ipdVisit-amount').val();
        let desc = $('#ipdVisit-desc').val();
        $.ajax({
            url:ipdVisitSubmit,
            type:"POST",
            data:{
                patientId:patientId,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,desc:desc
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-new-checkup').modal('hide');
                    $('#ipdVisit-modelForm')[0].reset();
                     $('#ipd-in-visit-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
                    console.log(response.error_validation);
                    toastWarningAlert(response.error_validation);
                }else{
                    toastErrorAlert('Something went wrong, please try again');
                }
            },
            error:function(xhr, status, error){
                console.log(xhr.respnseText);
                alert('An Error Occurred: '+error);
            }
            
        });
    }else{
        console.log("Please fill all required fields");
    }    
});

let patient_id = $('#patient_Id').val();
let table_visit = $('#ipd-in-visit-list').DataTable({
    processing: true,
    serverSide:true,
    ajax:{
        url: viewIpdVisit,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = patient_id;
        },
        error:function(xhr, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
        {
            data:'visit_id',
            name:'visit_id'
        },
        {
            data:'type',
            name:'type'
        },
        {
            data:'admit_id',
            name:'admit_id'
        },
        {
            data:'visit_date',
            name:'visit_date'
        },
        {
            data:'doctor',
            name:'doctor'
        },
        {
            data:'desc',
            name:'desc',
            orderable: false,
            searchable: false
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: true
        },
    ]
});
function ipdVisitViewData(id){
    $.ajax({
        url:getIpdVisitData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                let patientData = response.data.ipdVisitPatientData[0];
                let visitData = response.data.ipdVisitData[0];
                let visit_view_data = '';
                        visit_view_data += `  <div class="row">
                            <div class="col-md-12">
                                <table class="table  table-borderless table-sm payment-pharmacy-table">
                                <tbody>
                                <tr>
                                    <th class="fw-medium">Patient ID</th>
                                    <td>${patientData.patient_id}</td>
                                     <th class="fw-medium">Visit ID</th>
                                    <td>MDVI0${visitData.id}</td>
                                </tr>
                                <tr>
                                    <th class="fw-medium">Patient Name</th>
                                    <td>${patientData.name}</td>
                                     <th class="fw-medium">Guardian Name</th>
                                <td>${patientData.guardian_name}</td>
                                </tr>
                                <tr>
                                <th class="fw-medium">Gender</th>
                                    <td>${patientData.gender}</td>
                                     <th class="fw-medium">DOB</th>
                                    <td>${patientData.dob}</td>
                                </tr>
                               
                                <tr>     
                                    <th class="fw-medium">Phone</th>
                                    <td>${patientData.mobile}</td>
                                     <th class="fw-medium">Consult Doctor</th>
                                    <td>${visitData.consultDoctor}</td>
                                </tr>
                                
                                <tr>    
                                    <th class="fw-medium">Blood Type</th>
                                    <td>${patientData.bloodtype}</td>   
                                    <th class="fw-medium">Marital Status</th>
                                    <td>${patientData.marital_status}</td> 
                                </tr>
                                <tr>         
                                    
                                    <th class="fw-medium">Notes</th>
                                    <td>${visitData.note}</td>    
                                </tr>
                            </tbody>
                            </table>
                            </div>
                        </div>`;   
                        $('.ipdVisitViewDataAppend').html(visit_view_data);
            }else{
                alert('error');
            }
        },
        error:function(xhr,thrown){
            console.log(xhr.respnseText);
            alert('Error: '+thrown );
        }
    });
   
}

function ipdVisitEdit(id){
    $.ajax({
        url: getIpdVisitDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let visitData = response.data.ipdVisitData[0];
                $('#ipd-new-checkup').modal('show');
                $('#ipdVisitId').val(id);
                $('#ipdVisit-consultDoctor').val(visitData.consult_doctor).prop('disabled',true);
                $('#ipdVisit-charge').val(visitData.charge).prop('disabled',true);;
                $('#ipdVisit-discount').val(visitData.discount).prop('disabled',true);;
                $('#ipdVisit-tax').val(visitData.tax_per).prop('disabled',true);;
                $('#ipdVisit-amount').val(visitData.amount).prop('disabled',true);;
                $('#ipdVisit-desc').val(visitData.note);
                $('.ipdVisitSubmit').addClass('d-none');
                $('.ipdVisitUpdate').removeClass('d-none');
                $('.ipdVisit-AlreadypaidAmountCls').removeClass('d-none');
            }
        }
    });
}
function ipdVisitUpdate(id){
    let consultDoctor_check = validateField('ipdVisit-consultDoctor', 'select');
    let charge_check = validateField('ipdVisit-charge', 'amount');
    if(consultDoctor_check === true && charge_check === true){  
        let consultDoctor = $('#ipdVisit-consultDoctor').val();
        let charge = $('#ipdVisit-charge').val();
        let discount = $('#ipdVisit-discount').val();
        let taxPer = $('#ipdVisit-tax').val();
        let amount = $('#ipdVisit-amount').val();
        let desc = $('#ipdVisit-desc').val();
        $.ajax({
            url:ipdVisitDataUpdate,
            type:"POST",
            data:{
               id:id,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,desc:desc
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-new-checkup').modal('hide');
                    $('#ipdVisitId').val('');
                    $('#ipdVisit-modelForm')[0].reset();
                    $('#ipd-in-visit-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_success){
                    toastErrorAlert(response.error_success);
                }else{
                    toastErrorAlert('Something went wrong, please try again');
                }
                },
                error:function(xhr, status, error){
                    console.log(xhr.respnseText);
                    alert('An Error Occurred: '+error);
                }
            
        });
    }else{
        console.log("Please fill all required fields");
    } 
}
function ipdVisitDelete(id){
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
                url:ipdVisitDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                          $('#ipd-in-visit-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}    
$('#patient-history-list').DataTable();