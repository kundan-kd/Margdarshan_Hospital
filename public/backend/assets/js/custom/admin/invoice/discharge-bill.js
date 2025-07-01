function getDiscountAmount(disPer){
    let total_amount = parseFloat($('.bill-totalAmount').html());
    let paid_amount = parseFloat($('.bill-totalPaidAmount').html());
    let discount_amount = (total_amount * disPer || 0)/100;
    let net_amount = total_amount - discount_amount;
    let due_amount = net_amount - paid_amount;
    // if(due_amount <= 0){
    //     $('.billPrintBtn').removeClass('d-none');
    // }else{
    //     $('.billPrintBtn').addClass('d-none');
    // }
    $('.bill-discountAmount').html(discount_amount);
    $('.bill-totalNetAmount').html(net_amount);
    $('.bill-totalDueAmount').html(due_amount);
    let pay_amount = $('#billAdd-payAmount').val();
    checkPayAmount(pay_amount);
}
function checkPayAmount(amount){
    let due_amount = parseFloat($('.bill-totalDueAmount').html());
    if(amount > due_amount){
        $('.bill-payAmount-error').removeClass('d-none').html('Pay amount exceeds due amount').addClass('text-danger');
        $('.billAddSubmitBtn').prop('disabled',true);
    }else{
        $('.bill-payAmount-error').html('').addClass('d-none');
        $('.billAddSubmitBtn').prop('disabled',false);
    }
}
function billAmountSubmit(id){
    let pay_amount = $('#billAdd-payAmount').val();
    let pmode = $('#billAdd-paymentMode').val();
    let type = "EMERGENCY";
    let discount_amount = parseFloat($('.bill-discountAmount').html() || 0);
    if(pmode ==''){
        $('#billAdd-paymentMode').focus();
    }else if(pay_amount == '') {
        $('#billAdd-payAmount').focus();
    }else{
        $.ajax({
            url: payBillAmount,
            type: "POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{patientId:id,amount:pay_amount,pmode:pmode,type:type,discount_amount:discount_amount},
            success:function(response){
                if(response.success){
                    toastSuccessAlert(response.success);
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }else if(response.error_validation){
                    toastWarningAlert(response.error_validation);
                }else{
                    toastErrorAlert(response.error_success);
                }
            },
            error:function(xhr, status, error){
                console.log(xhr.respnseText);
                alert('An error occurred: '+error);
            }
        });
    }    
}
function billPrint(id){
     window.open('/discharge-bill-print/' + id +'_blank');
}

