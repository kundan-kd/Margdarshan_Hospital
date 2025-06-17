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
    $('.emergencyVisitSubmit').removeClass('d-none');
    $('.emergencyVisitUpdate').addClass('d-none');
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
$('#emergencyVisit-form').on('submit',function(e){
 e.preventDefault();
    let symptoms_check  = validateField('emergencyVisit-symptoms', 'input');
    let previousMedIssue_check  = validateField('emergencyVisit-previousMedIssue', 'input');
    let oldPatient_check  = validateField('emergencyVisit-oldPatient', 'select');
    let consultDoctor_check = validateField('emergencyVisit-consultDoctor', 'select');
    let charge_check = validateField('emergencyVisit-charge', 'amount');
    let amount_check = validateField('emergencyVisit-amount', 'amount');
    let paymentMode_check = validateField('emergencyVisit-paymentMode', 'select');
    let paidAmount_check = validateField('emergencyVisit-paidAmount', 'amount');
    if(symptoms_check === true && previousMedIssue_check === true && oldPatient_check === true && consultDoctor_check === true && charge_check === true  && amount_check === true && paymentMode_check === true && paidAmount_check === true){ 
        let patientId = $('#patient_Id').val();
        let symptoms = $('#emergencyVisit-symptoms').val();
        let previousMedIssue = $('#emergencyVisit-previousMedIssue').val();
        let note = $('#emergencyVisit-note').val();
        let appointment_date = $('#emergencyVisit-admissionDate').val();
        let oldPatient = $('#emergencyVisit-oldPatient').val();
        let consultDoctor = $('#emergencyVisit-consultDoctor').val();
        let charge = $('#emergencyVisit-charge').val();
        let discount = $('#emergencyVisit-discount').val();
        let taxPer = $('#emergencyVisit-tax').val();
        let amount = $('#emergencyVisit-amount').val();
        let paymentMode = $('#emergencyVisit-paymentMode').val();
        let refNum = $('#emergencyVisit-emergencyVisit-refNum').val();
        let paidAmount = $('#emergencyVisit-paidAmount').val();
        $.ajax({
            url:emergencyVisitSubmit,
            type:"POST",
            data:{
                patientId:patientId,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,appointment_date:appointment_date,oldPatient:oldPatient,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-new-checkup').modal('hide');
                    $('#emergencyVisit-form')[0].reset();
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
                $('#emergencyVisit-symptoms').val(visitData.symptoms);
                $('#emergencyVisit-previousMedIssue').val(visitData.previous_med_issue);
                $('#emergencyVisit-note').val(visitData.note);
                $('#emergencyVisit-admissionDate').val(visitData.appointment_date);
                $('#emergencyVisit-oldPatient').val(visitData.old_patient);
                $('emergencyVisit-reference').val(visitData.appointment_date);
                $('#emergencyVisit-consultDoctor').val(visitData.consult_doctor);
                $('#emergencyVisit-charge').val(visitData.charge);
                $('#emergencyVisit-discount').val(visitData.discount);
                $('#emergencyVisit-tax').val(visitData.tax_per);
                $('#emergencyVisit-amount').val(visitData.amount);
                $('#emergencyVisit-paymentMode').val(visitData.payment_mode);
                $('#emergencyVisit-refNum').val(visitData.ref_num);
                $('#emergencyVisit-paidAmount').val(visitData.paid_amount);
                $('.emergencyVisitSubmit').addClass('d-none');
                $('.emergencyVisitUpdate').removeClass('d-none');
            }
        }
    });
}
function emergencyVisitUpdate(id){
    let symptoms_check  = validateField('emergencyVisit-symptoms', 'input');
    let previousMedIssue_check  = validateField('emergencyVisit-previousMedIssue', 'input');
    let oldPatient_check  = validateField('emergencyVisit-oldPatient', 'select');
    let consultDoctor_check = validateField('emergencyVisit-consultDoctor', 'select');
    let charge_check = validateField('emergencyVisit-charge', 'amount');
    let tax_check = validateField('emergencyVisit-tax', 'amount');
    let amount_check = validateField('emergencyVisit-amount', 'amount');
    let paymentMode_check = validateField('emergencyVisit-paymentMode', 'select');
    let paidAmount_check = validateField('emergencyVisit-paidAmount', 'amount');
    if(symptoms_check === true && previousMedIssue_check === true && oldPatient_check === true && consultDoctor_check === true && charge_check === true && tax_check === true && amount_check === true && paymentMode_check === true && paidAmount_check === true){  
        let symptoms = $('#emergencyVisit-symptoms').val();
        let previousMedIssue = $('#emergencyVisit-previousMedIssue').val();
        let note = $('#emergencyVisit-note').val();
        let appointment_date = $('#emergencyVisit-admissionDate').val();
        let oldPatient = $('#emergencyVisit-oldPatient').val();
        let consultDoctor = $('#emergencyVisit-consultDoctor').val();
        let charge = $('#emergencyVisit-charge').val();
        let discount = $('#emergencyVisit-discount').val();
        let taxPer = $('#emergencyVisit-tax').val();
        let amount = $('#emergencyVisit-amount').val();
        let paymentMode = $('#emergencyVisit-paymentMode').val();
        let refNum = $('#emergencyVisit-emergencyVisit-refNum').val();
        let paidAmount = $('#emergencyVisit-paidAmount').val();
        $.ajax({
            url:emergencyVisitDataUpdate,
            type:"POST",
            data:{
               id:id,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,appointment_date:appointment_date,oldPatient:oldPatient,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
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