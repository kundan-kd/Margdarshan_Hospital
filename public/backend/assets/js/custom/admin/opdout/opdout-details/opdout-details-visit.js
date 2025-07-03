function resetVisit(){
    $('#opdOutVisitId').val('');
    $('#opdOutVisit-symptoms').val('');
    $('#opdOutVisit-previousMedIssue').val('');
    $('#opdOutVisit-note').val('');
    $('#opdOutVisit-admissionDate').val('');
    $('#opdOutVisit-oldPatient').val('');
    $('#opdOutVisit-consultDoctor').val('');
    $('#opdOutVisit-charge').val('');
    $('#opdOutVisit-discount').val('');
    $('#opdOutVisit-tax').val('');
    $('#opdOutVisit-amount').val('');
    $('#opdOutVisit-paymentMode').val('');
    $('#opdOutVisit-refNum').val('');
    $('#opdOutVisit-paidAmount').val('');
    $('.opdOutVisitSubmit').removeClass('d-none');
    $('.opdOutVisitUpdate').addClass('d-none');
}
function calculateAmount(){
    let charge = $('#opdOutVisit-charge').val() || 0;
    let discount = $('#opdOutVisit-discount').val() || 0;
    let tax = $('#opdOutVisit-tax').val() || 0;
    let discountAmount = (charge * discount)/100;
     let amountAftreDiscount = charge - discountAmount;
    let taxAmount = (amountAftreDiscount * tax)/100;
    let finelAmount = (charge - discountAmount) + taxAmount;
    $('#opdOutVisit-amount').val(finelAmount);
}
function checkOpdVisitPaidAmount(){
    let amount = $('#opdOutVisit-amount').val() || 0;
    let paidAmount = $('#opdOutVisit-paidAmount').val() || 0;
    let alreadypaidAmount = $('#opdOutVisit-AlreadypaidAmount').val() || 0;
    if((parseFloat(paidAmount) + parseFloat(alreadypaidAmount)) > amount){
        $('.opdOutVisitSubmit').prop('disabled', true);
        $('.opdOutVisitUpdate').prop('disabled', true);
        toastErrorAlert('Payment amount exceeds total amount');
    }else{
         $('.opdOutVisitSubmit').prop('disabled', false);
         $('.opdOutVisitUpdate').prop('disabled', false);
    }
}
$('#opdOutVisit-modelForm').on('submit',function(e){
 e.preventDefault();
    let symptoms_check  = validateField('opdOutVisit-symptoms', 'input');
    let previousMedIssue_check  = validateField('opdOutVisit-previousMedIssue', 'select');
    let oldPatient_check  = validateField('opdOutVisit-oldPatient', 'select');
    let consultDoctor_check = validateField('opdOutVisit-consultDoctor', 'select');
    let charge_check = validateField('opdOutVisit-charge', 'amount');
    let amount_check = validateField('opdOutVisit-amount', 'amount');
    let paymentMode_check = validateField('opdOutVisit-paymentMode', 'select');
    let paidAmount_check = validateField('opdOutVisit-paidAmount', 'amount');
    if(symptoms_check === true && previousMedIssue_check === true && oldPatient_check === true && consultDoctor_check === true && charge_check === true  && amount_check === true && paymentMode_check === true && paidAmount_check === true){ 
        let patientId = $('#patient_Id').val();
        let symptoms = $('#opdOutVisit-symptoms').val();
        let previousMedIssue = $('#opdOutVisit-previousMedIssue').val();
        let note = $('#opdOutVisit-note').val();
        let appointment_date = $('#opdOutVisit-admissionDate').val();
        let oldPatient = $('#opdOutVisit-oldPatient').val();
        let consultDoctor = $('#opdOutVisit-consultDoctor').val();
        let charge = $('#opdOutVisit-charge').val();
        let discount = $('#opdOutVisit-discount').val();
        let taxPer = $('#opdOutVisit-tax').val();
        let amount = $('#opdOutVisit-amount').val();
        let paymentMode = $('#opdOutVisit-paymentMode').val();
        let refNum = $('#opdOutVisit-opdOutVisit-refNum').val();
        let paidAmount = $('#opdOutVisit-paidAmount').val();
        $.ajax({
            url:opdOutVisitSubmit,
            type:"POST",
            data:{
                patientId:patientId,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,appointment_date:appointment_date,oldPatient:oldPatient,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-new-checkup').modal('hide');
                    $('#opdOutVisit-modelForm')[0].reset();
                     $('#opd-out-visit-list').DataTable().ajax.reload();
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
let table_visit = $('#opd-out-visit-list').DataTable({
    processing: true,
    serverSide:true,
    ajax:{
        url: viewOptOutVisit,
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
            name:'visit_id',
            orderable: false,
            searchable: true
        },
        {
            data:'appointment_date',
            name:'appointment_date',
            orderable: false,
            searchable: true
        },
        {
            data:'doctor',
            name:'doctor',
            orderable: true,
            searchable: true
        },
        {
            data:'symptons',
            name:'symptons',
            orderable: true,
            searchable: true
        },
         {
            data:'status',
            name:'status',
            orderable: true,
            searchable: true
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: true
        },

    ]
});
function opdOutVisitViewData(id){
    $.ajax({
        url:getOpdOutVisitData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                let patientData = response.data.outVisitPatientData[0];
                let visitData = response.data.outVisitData[0];
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
                                    <th class="fw-medium">Appointment Date</th>
                                    <td>${visitData.appointment_date}</td>
                                </tr>
                                <tr>
                                <th class="fw-medium">Guardian Name</th>
                                    <td>${patientData.guardian_name}</td>
                                    <th class="fw-medium">Symptons</th>
                                    <td>${visitData.symptoms}</td>
                                </tr>
                                <tr>
                                <th class="fw-medium">Gender</th>
                                    <td>${patientData.gender}</td>
                                    <th class="fw-medium">Previous Health Issue</th>
                                    <td>${visitData.previousMedIssue}</td>
                                </tr>
                                <tr>           
                                <th class="fw-medium">DOB</th>
                                    <td>${patientData.dob}</td>
                                    <th class="fw-medium">Old Patient</th>
                                    <td>${visitData.oldPatient}</td>
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
                                     <th class="fw-medium">Known Allergies</th>
                                    <td>${visitData.known_allergies}</td>  
                                </tr>
                                <tr>         
                                    <th class="fw-medium">Marital Status</th>
                                    <td>${patientData.marital_status}</td> 
                                    <th class="fw-medium">Notes</th>
                                    <td>${visitData.note}</td>    
                                </tr>
                            </tbody>
                            </table>
                            </div>
                        </div>`;   
                        $('.opdOutVisitViewDataAppend').html(visit_view_data);
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

function opdOutVisitEdit(id){
    $.ajax({
        url: getOpdOutVisitDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let visitData = response.data.outVisitData[0];
                $('#opd-new-checkup').modal('show');
                $('#opdOutVisitId').val(id);
                $('#opdOutVisit-symptoms').val(visitData.symptoms);
                $('#opdOutVisit-previousMedIssue').val(visitData.previousMedIssue);
                $('#opdOutVisit-note').val(visitData.note);
                $('#opdOutVisit-admissionDate').val(visitData.appointment_date);
                $('#opdOutVisit-oldPatient').val(visitData.oldPatient);
                $('opdOutVisit-reference').val(visitData.appointment_date);
                $('#opdOutVisit-consultDoctor').val(visitData.consultDoctor);
                $('#opdOutVisit-charge').val(visitData.charge);
                $('#opdOutVisit-discount').val(visitData.discount);
                $('#opdOutVisit-tax').val(visitData.taxPer);
                $('#opdOutVisit-amount').val(visitData.amount);
                $('#opdOutVisit-paymentMode').val(visitData.paymentMode);
                $('#opdOutVisit-refNum').val(visitData.refNum);
                $('#opdOutVisit-paidAmount').val(visitData.paidAmount);
                $('.opdOutVisitSubmit').addClass('d-none');
                $('.opdOutVisitUpdate').removeClass('d-none');
            }
        }
    });
}
function opdOutVisitUpdate(id){
    let symptoms_check  = validateField('opdOutVisit-symptoms', 'input');
    let previousMedIssue_check  = validateField('opdOutVisit-previousMedIssue', 'select');
    let oldPatient_check  = validateField('opdOutVisit-oldPatient', 'select');
    let consultDoctor_check = validateField('opdOutVisit-consultDoctor', 'select');
    let charge_check = validateField('opdOutVisit-charge', 'amount');
    let tax_check = validateField('opdOutVisit-tax', 'amount');
    let amount_check = validateField('opdOutVisit-amount', 'amount');
    let paymentMode_check = validateField('opdOutVisit-paymentMode', 'select');
    let paidAmount_check = validateField('opdOutVisit-paidAmount', 'amount');
    if(symptoms_check === true && previousMedIssue_check === true && oldPatient_check === true && consultDoctor_check === true && charge_check === true && tax_check === true && amount_check === true && paymentMode_check === true && paidAmount_check === true){  
        let symptoms = $('#opdOutVisit-symptoms').val();
        let previousMedIssue = $('#opdOutVisit-previousMedIssue').val();
        let note = $('#opdOutVisit-note').val();
        let appointment_date = $('#opdOutVisit-admissionDate').val();
        let oldPatient = $('#opdOutVisit-oldPatient').val();
        let consultDoctor = $('#opdOutVisit-consultDoctor').val();
        let charge = $('#opdOutVisit-charge').val();
        let discount = $('#opdOutVisit-discount').val();
        let taxPer = $('#opdOutVisit-tax').val();
        let amount = $('#opdOutVisit-amount').val();
        let paymentMode = $('#opdOutVisit-paymentMode').val();
        let refNum = $('#opdOutVisit-opdOutVisit-refNum').val();
        let paidAmount = $('#opdOutVisit-paidAmount').val();
        $.ajax({
            url:opdOutVisitDataUpdate,
            type:"POST",
            data:{
               id:id,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,appointment_date:appointment_date,oldPatient:oldPatient,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-new-checkup').modal('hide');
                    $('#opdOutVisitId').val('');
                    $('#opdOutVisit-modelForm')[0].reset();
                    $('#opd-out-visit-list').DataTable().ajax.reload();
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
function opdOutVisitDelete(id){
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
                url:opdOutVisitDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                          $('#opd-out-visit-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}