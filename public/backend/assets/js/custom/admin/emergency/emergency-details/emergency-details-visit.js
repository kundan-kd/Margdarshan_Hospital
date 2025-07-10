function resetVisit(){
    $('#emergencyVisitId').val('');
    $('#emergencyVisit-symptoms').val('');
    $('#emergencyVisit-previousMedIssue').val('');
    $('#emergencyVisit-note').val('');
    $('#emergencyVisit-admissionDate').val('');
    $('#emergencyVisit-oldPatient').val('');
    $('#emergencyVisit-consultDoctor').val('');
    $('#emergencyVisit-charge').val('');
    $('#emergencyVisit-discount').val('');
    $('#emergencyVisit-tax').val('');
    $('#emergencyVisit-amount').val('');
    $('#emergencyVisit-paymentMode').val('');
    $('#emergencyVisit-refNum').val('');
    $('#emergencyVisit-paidAmount').val('');
    $('#emergencyVisit-AlreadypaidAmount').val('');
    $('.emergencyVisitSubmit').removeClass('d-none');
    $('.emergencyVisitUpdate').addClass('d-none');
    $('.emergencyVisit-AlreadypaidAmountCls').addClass('d-none');
}
function calculateAmount(){
    let charge = $('#emergencyVisit-charge').val() || 0;
    let discount = $('#emergencyVisit-discount').val() || 0;
    let tax = $('#emergencyVisit-tax').val() || 0;
    let discountAmount = (charge * discount)/100;
     let amountAftreDiscount = charge - discountAmount;
    let taxAmount = (amountAftreDiscount * tax)/100;
    let finelAmount = (charge - discountAmount) + taxAmount;
    $('#emergencyVisit-amount').val(finelAmount);
}
function checkEmergencyVisitPaidAmount(){
    let amount = $('#emergencyVisit-amount').val() || 0;
    let paidAmount = $('#emergencyVisit-paidAmount').val() || 0;
    let alreadypaidAmount = $('#emergencyVisit-AlreadypaidAmount').val() || 0;
    if((parseFloat(paidAmount) + parseFloat(alreadypaidAmount)) > amount){
        $('.emergencyVisitSubmit').prop('disabled', true);
        $('.emergencyVisitUpdate').prop('disabled', true);
        toastErrorAlert('Payment amount exceeds total amount');
    }else{
         $('.emergencyVisitSubmit').prop('disabled', false);
         $('.emergencyVisitUpdate').prop('disabled', false);
    }
}
$('#emergencyVisit-modelForm').on('submit',function(e){
 e.preventDefault();
    let consultDoctor_check = validateField('emergencyVisit-consultDoctor', 'select');
    let charge_check = validateField('emergencyVisit-charge', 'amount');
    if(consultDoctor_check === true && charge_check === true){ 
        let patientId = $('#patient_Id').val();
        let consultDoctor = $('#emergencyVisit-consultDoctor').val();
        let charge = $('#emergencyVisit-charge').val();
        let discount = $('#emergencyVisit-discount').val();
        let taxPer = $('#emergencyVisit-tax').val();
        let amount = $('#emergencyVisit-amount').val();
        let desc = $('#emergencyVisit-desc').val();
        $.ajax({
            url:emergencyVisitSubmit,
            type:"POST",
            data:{
                patientId:patientId,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,desc:desc
            },
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-new-checkup').modal('hide');
                    $('#emergencyVisit-modelForm')[0].reset();
                     $('#emergency-visit-list').DataTable().ajax.reload();
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
let table_emergency_visit = $('#emergency-visit-list').DataTable({
    processing: true,
    serverSide:true,
    ajax:{
        url: viewEmergencyVisit,
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
function emergencyVisitViewData(id){
    $.ajax({
        url:getEmergencyVisitData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            if(response.success){
                let patientData = response.data.emergencyVisitPatientData[0];
                let visitData = response.data.emergencyVisitData[0];
                let visit_view_data = '';
                        visit_view_data += `<div class="row">
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
                        $('.emergencyVisitViewDataAppend').html(visit_view_data);
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

function emergencyVisitEdit(id){
    $.ajax({
        url: getEmergencyVisitDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            // console.log(response);
            if(response.success){
               let visitData = response.data.emergencyVisitData[0];
               $('#emergency-new-checkup').modal('show');
                $('#emergencyVisitId').val(id);
                $('#emergencyVisit-consultDoctor').val(visitData.consult_doctor).prop('disabled',true);
                $('#emergencyVisit-charge').val(visitData.charge).prop('disabled',true);;
                $('#emergencyVisit-discount').val(visitData.discount).prop('disabled',true);;
                $('#emergencyVisit-tax').val(visitData.tax_per).prop('disabled',true);;
                $('#emergencyVisit-amount').val(visitData.amount).prop('disabled',true);;
                $('#emergencyVisit-desc').val(visitData.note);
                $('.emergencyVisitSubmit').addClass('d-none');
                $('.emergencyVisitUpdate').removeClass('d-none');
                $('.emergencyVisit-AlreadypaidAmountCls').removeClass('d-none');
            }
        }
    });
}
function emergencyVisitUpdate(id){
    let consultDoctor_check = validateField('emergencyVisit-consultDoctor', 'select');
    let charge_check = validateField('emergencyVisit-charge', 'amount');
    if(consultDoctor_check === true && charge_check === true){  
        let consultDoctor = $('#emergencyVisit-consultDoctor').val();
        let charge = $('#emergencyVisit-charge').val();
        let discount = $('#emergencyVisit-discount').val();
        let taxPer = $('#emergencyVisit-tax').val();
        let amount = $('#emergencyVisit-amount').val();
        let desc = $('#emergencyVisit-desc').val();
        $.ajax({
            url:emergencyVisitDataUpdate,
            type:"POST",
            data:{
               id:id,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,desc:desc
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-new-checkup').modal('hide');
                    $('#emergencyVisitId').val('');
                    $('#emergencyVisit-modelForm')[0].reset();
                    $('#emergency-visit-list').DataTable().ajax.reload();
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
function emergencyVisitDelete(id){
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
                url:emergencyVisitDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                          $('#emergency-visit-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}    