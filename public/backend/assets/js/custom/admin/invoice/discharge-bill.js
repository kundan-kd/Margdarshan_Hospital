function getDiscountAmount(disPer){
    let total_amount = parseFloat($('.bill-totalAmount').html());
    let paid_amount = parseFloat($('.bill-totalPaidAmount').html());
    let discount_amount = (total_amount * disPer || 0)/100;
    let net_amount = total_amount - discount_amount;
    let due_amount = net_amount - paid_amount;
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
    
    $.ajax({
        url: payBillAmount,
        type: "POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{patientId:id,amount:pay_amount},
        success:function(response){
            console.log(response);
        }
    });
}