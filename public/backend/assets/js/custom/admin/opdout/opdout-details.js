$('#opdOutVisit-modelForm').on('submit',function(e){
 e.preventDefault();
 let patientId = $('#opdOutVisit-patientId').val();
//  let symptomType = $('#opdOutVisit-symptomType').val();
 let symptoms = $('#opdOutVisit-symptomTitle').val();
//  let symptomDesc = $('#opdOutVisit-symptomDesc').val();
 let previousMedIssue = $('#opdOutVisit-previousMedIssue').val();
 let note = $('#opdOutVisit-note').val();
 let admissionDate = $('#opdOutVisit-admissionDate').val();
//  let cases = $('#opdOutVisit-case').val();
//  let casuality = $('#opdOutVisit-casuality').val();
 let oldPatient = $('#opdOutVisit-oldPatient').val();
 let reference = $('#opdOutVisit-reference').val();
 let consultDoctor = $('#opdOutVisit-consultDoctor').val();
 let chargeCategory = $('#opdOutVisit-chargeCategory').val();
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
        patientId:patientId,symptoms:symptoms,previousMedIssue:previousMedIssue,note:note,admissionDate:admissionDate,oldPatient:oldPatient,reference:reference,consultDoctor:consultDoctor,chargeCategory:chargeCategory,charge:charge,discount:discount,taxPer:taxPer,amount:amount,paymentMode:paymentMode,refNum:refNum,paidAmount:paidAmount
    },
    headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(response){
        console.log(response);
         if(response.success){
                    $('#opd-new-checkup').modal('hide');
                    $('#opdOutVisit-modelForm')[0].reset();
                    // $('.patient-name').addClass('d-none');
                    // $('.patient-notfound').addClass('d-none');
                    toastSuccessAlert(response.success);
                    // $('#appointment-book-table').DataTable().ajax.reload();
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
});