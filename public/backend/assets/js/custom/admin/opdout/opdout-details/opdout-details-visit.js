$('#opdOutVisit-modelForm').on('submit',function(e){
 e.preventDefault();
    let symptoms_check  = validateField('opdOutVisit-symptoms', 'input');
    let previousMedIssue_check  = validateField('opdOutVisit-previousMedIssue', 'input');
    let oldPatient_check  = validateField('opdOutVisit-oldPatient', 'select');
    let consultDoctor_check = validateField('opdOutVisit-consultDoctor', 'select');
    let charge_check = validateField('opdOutVisit-charge', 'amount');
    let tax_check = validateField('opdOutVisit-tax', 'amount');
    let amount_check = validateField('opdOutVisit-amount', 'amount');
    let paymentMode_check = validateField('opdOutVisit-paymentMode', 'select');
    let paidAmount_check = validateField('opdOutVisit-paidAmount', 'amount');
    if(symptoms_check === true && previousMedIssue_check === true && oldPatient_check === true && consultDoctor_check === true && charge_check === true && tax_check === true && amount_check === true && paymentMode_check === true && paidAmount_check === true){    
        let patientId = $('#opdOutVisit-patientId').val();
        let symptoms = $('#opdOutVisit-symptoms').val();
        let previousMedIssue = $('#opdOutVisit-previousMedIssue').val();
        let note = $('#opdOutVisit-note').val();
        let admissionDate = $('#opdOutVisit-admissionDate').val();
        let oldPatient = $('#opdOutVisit-oldPatient').val();
        let reference = $('#opdOutVisit-reference').val();
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
                patientId:patientId,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,admissionDate:admissionDate,oldPatient:oldPatient,reference:reference,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-new-checkup').modal('hide');
                    $('#opdOutVisit-modelForm')[0].reset();
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

let table_visit = $('#opd-out-visit-list').DataTable({
    processing: true,
    serverSide:true,
    ajax:{
        url: viewOptOutVisit,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
        {
            data:'opd_id',
            name:'opd_id',
            orderable: true,
            searchable: true
        },
        {
            data:'appointment_date',
            name:'appointment_date',
            orderable: true,
            searchable: true
        },
        {
            data:'doctor',
            name:'doctor',
            orderable: false,
            searchable: true
        },
        {
            data:'reference',
            name:'reference',
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
            console.log(response);
            if(response.success){
                let visit_view_data = '';
                        visit_view_data += `  <div class="row">
                            <div class="col-md-12">
                                <table class="table  table-borderless table-sm payment-pharmacy-table">
                                <tbody>
                                <tr>
                                    <th class="fw-medium">Case ID</th>
                                    <td>1234</td>
                                    <th class="fw-medium">OPD No</th>
                                    <td>OPD4125</td>
                                </tr>
                                <tr>
                                    <th class="fw-medium">Recheckup ID</th>
                                    <td>RE123456</td>
                                    <th class="fw-medium">Appointment Date</th>
                                    <td>20/05/2025</td>
                                </tr>
                                <tr>
                                <th class="fw-medium">Patient Name</th>
                                    <td>Sunil Kumar</td>
                                    <th class="fw-medium">Guardian Name</th>
                                    <td>Gurav Bhatiya</td>
                                </tr>
                                <tr>
                                <th class="fw-medium">Gender</th>
                                    <td>Male</td>
                                    <th class="fw-medium">Marital Status</th>
                                    <td>Singal</td>
                                </tr>
                                <tr>           
                                <th class="fw-medium">Age</th>
                                    <td>8 Year 5 Month 17 Days (As Of Date )</td>
                                    <th class="fw-medium">Blood Group</th>
                                    <td>B+</td>
                                </tr>
                                <tr>     
                                    <th class="fw-medium">Phone</th>
                                    <td>+91 2233 445 566</td>
                                    <th class="fw-medium">Email</th>
                                    <td>sunil@gmail.com</td>
                                </tr>
                                
                                <tr>    
                                    <th class="fw-medium">Known Allergies</th>
                                    <td>unknown</td>    
                                    <th class="fw-medium">Case</th>
                                    <td>2200</td>  
                                </tr>
                                <tr>         
                                    <th class="fw-medium">Consultant Doctor</th>
                                    <td>Mohan Kumar Gupta (9008)</td>  
                                    <th class="fw-medium">Reference</th>
                                    <td>Anil kumar</td> 
                                </tr>
                                <tr>
                                <th class="fw-medium">Symptoms</th>
                                <td class="text-sm">Cramps and injuries </td>
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
    })
   
}