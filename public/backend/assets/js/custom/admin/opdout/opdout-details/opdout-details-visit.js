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
    $('.edit-hide').removeClass('d-none');
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
    let consultDoctor_check = validateField('opdOutVisit-consultDoctor', 'select');
    let charge_check = validateField('opdOutVisit-charge', 'amount');
    let visit_check = validateField('opdOutVisit-visitDate', 'select');
    if(consultDoctor_check === true && charge_check === true  && visit_check === true){ 
        let patientId = $('#patient_Id').val();
        let consultDoctor = $('#opdOutVisit-consultDoctor').val();
        let charge = $('#opdOutVisit-charge').val();
        let discount = $('#opdOutVisit-discount').val();
        let taxPer = $('#opdOutVisit-tax').val();
        let amount = $('#opdOutVisit-amount').val();
        let visit = $('#opdOutVisit-visitDate').val();
        let desc = $('#opdOutVisit-desc').val();
        $.ajax({
            url:opdOutVisitSubmit,
            type:"POST",
            data:{
                patientId:patientId,consultDoctor:consultDoctor,charge:charge,discount:discount,taxPer:taxPer,amount:amount,visit:visit,desc:desc
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
            name:'visit_id'
        },
        {
            data:'appointment_date',
            name:'appointment_date'
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
            data:'paid_amount',
            name:'paid_amount'
        }

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
                $('#opdOutVisit-consultDoctor').val(visitData.consultDoctor);
                $('#opdOutVisit-visit').val(visitData.visited_date);
                $('#opdOutVisit-desc').val(visitData.note);
                $('.edit-hide').addClass('d-none1');
                $('.opdOutVisitSubmit').addClass('d-none');
                $('.opdOutVisitUpdate').removeClass('d-none');
            }
        }
    });
}
function opdOutVisitUpdate(id){
    let consultDoctor_check = validateField('opdOutVisit-consultDoctor', 'select');
    let visit_check = validateField('opdOutVisit-visitDate', 'select');
    if(consultDoctor_check === true && visit_check === true ){  
        let consultDoctor = $('#opdOutVisit-consultDoctor').val();
        let visit = $('#opdOutVisit-opdOutVisit-visitDate').val();
        let desc = $('#opdOutVisit-desc').val();
        $.ajax({
            url:opdOutVisitDataUpdate,
            type:"POST",
            data:{
               id:id,consultDoctor:consultDoctor,visit:visit,desc:desc
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