// document.querySelectorAll('input[type="number"]').forEach(input => {
//     input.addEventListener('input', function() {
//         if (this.value <= 0) {
//             this.value = 0; // Reset to minimum allowed value
//         }
//     });
// });

$(document).ready(function() {
    $('.select2Edit-cls').select2(); // Initialize Select2 for existing elements
});
function addNewRowEdit(){
 let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
    let newRowDataEdit = `<tr class="fieldGroupCopy">
        <td>
         <input type="hidden" id="purchaseEdit_id${rand}" name="purchaseEdit_id[]" value="">
            <select id="purchaseEdit_category${rand}" name="purchaseEdit_category[]" class="form-select form-select-sm select2Edit-cls" style="width: 100%;" required>
                <option value="" selected disabled>Select</option>
                <option value="Syrup">Syrup</option>
                <option value="Injection">Injection</option>
                <option value="Capsule">Capsule</option>
                <option value="Tablet">Tablet</option>
                <option value="Ointment">Ointment</option>
            </select>
        </td>
        <td>
            <select id="purchaseEdit_name${rand}" name="purchaseEdit_name[]" class="form-select form-select-sm select2Edit-cls" style="width: 100%;" required>
                <option value="" selected disabled>Select</option>
                <option value="Paracitamol">Paracitamol</option>
                <option value="Azrithimycin">Azrithimycin</option>
                <option value="Aceloc">Aceloc</option>
                <option value="Calpol">Calpol</option>
                <option value="Metrogly">Metrogly</option>
                <option value="Oxalgin">Oxalgin</option>
                <option value="Metacin">Metacin</option>
            </select>
        </td>
        <td>
            <input id="purchaseEdit_batch${rand}" name="purchaseEdit_batch[]" class="form-control form-control-sm" type="text" placeholder="Batch No" required>
        </td>
        <td>
            <input id="purchaseEdit_expiry${rand}" name="purchaseEdit_expiry[]" class="form-control form-control-sm expiry-date" type="text" placeholder="Expiry Date" required>
        </td>
        <td>
            <input id="purchaseEdit_mrp${rand}" name="purchaseEdit_mrp[]" class="form-control form-control-sm" type="number" placeholder="MRP" required>
        </td>
        <td>
            <input id="purchaseEdit_salesPrice${rand}" name="purchaseEdit_salesPrice[]" class="form-control form-control-sm" type="number" placeholder="Sale Price" required>
        </td>
       
        <td>
            <input id="purchaseEdit_qty${rand}" name="purchaseEdit_qty[]" class="form-control form-control-sm" type="number" placeholder="Qty" oninput="getAmountEdit(${rand})" required>
        </td>
        <td>
            <input id="purchaseEdit_purchaseRate${rand}" name="purchaseEdit_purchaseRate[]" class="form-control form-control-sm" type="number" placeholder="Purchase Rate" oninput="getAmountEdit(${rand})" required>
        </td>
         <td>
            <input id="purchaseEdit_tax${rand}" name="purchaseEdit_tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" oninput="getTaxEdit(${rand})" required>
        </td>
        <td style="display: none;">
            <input id="purchaseEdit_taxAmount${rand}" name="purchaseEdit_taxAmount[]" type="number" class="form-control form-control-sm">
        </td>
        <td>
            <input id="purchaseEdit_amount${rand}" name="purchaseEdit_amount[]" class="form-control form-control-sm" type="number" placeholder="Amount" readonly>
        </td>
        <td>
            <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowEdit(this)">
                <i class="ri-close-line"></i>
            </button>
        </td>
    </tr>`;

    $('.newRowAppendEdit').parent().append(newRowDataEdit); // Append properly to tbody

    // Reinitialize Select2 for newly added row
    $('.select2Edit-cls').select2();
}
 function removeRowEdit(x){
    x.closest("tr").remove(); // remove entire row with tr selector

}

function updateAmountEdit(){
    let totalAmount = $('input[name="purchaseEdit_amount[]"]').map(function(){return $(this).val();}).get();
    let sumTotalAmount = totalAmount.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    $('.purchaseEdit_totalAmt').html(sumTotalAmount.toFixed(2));
    
    let discountPer = parseFloat($('#purchaseEdit_discount').val()) || 0;
    let totalDiscountAmount = (sumTotalAmount * discountPer) / 100;
     $('.purchaseEdit_discountAmt').html(totalDiscountAmount.toFixed(2));

    let totalTaxAmount = $('input[name="purchaseEdit_taxAmount[]"]').map(function(){return $(this).val();}).get();
    let sumTotalTaxAmount = totalTaxAmount.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    let taxfterDiscount = (sumTotalTaxAmount * discountPer) / 100;
    let totalTaxAfterDiscount = sumTotalTaxAmount - taxfterDiscount;
    $('.purchaseEdit_taxAmt').html(totalTaxAfterDiscount.toFixed(2));

    let netamount = sumTotalAmount - totalDiscountAmount + totalTaxAfterDiscount;
    $('.purchaseEdit_netTotalAmt').html(netamount.toFixed(2));

}
function getAmountEdit(randNum){
    let qty = parseFloat($('#purchaseEdit_qty' + randNum).val()) || 0; // Convert to number, default to 0 if invalid
    let purchaseRate = parseFloat($('#purchaseEdit_purchaseRate' + randNum).val()) || 0;
    let amount = qty * purchaseRate;
    $('#purchaseEdit_amount' + randNum).val(amount);
    getTaxEdit(randNum);
}

function getTaxEdit(randNum){
     let tax = parseFloat($('#purchaseEdit_tax' + randNum).val()) || 0;
    let amount = parseFloat($('#purchaseEdit_amount' + randNum).val()) || 0;
    let taxAmount = (amount * tax) / 100;
    $('#purchaseEdit_taxAmount'+randNum).val(taxAmount);
    updateAmountEdit();
}

$('#purchaseEdit_form').on('submit',function(e){
  e.preventDefault();
  let purchase_id = $('#purchaseEdit_purchase_id').val();
  let billNo = $('#purchaseEdit_billNo').val();
  let vendorID = $('#purchaseEdit_vendor').val();
  let id =  $('input[name="purchaseEdit_id[]"]').map(function(){return $(this).val();}).get();
  let category = $('select[name="purchaseEdit_category[]"]').map(function(){return $(this).val();}).get();
  let name = $('select[name="purchaseEdit_name[]"]').map(function(){return $(this).val();}).get();
  let batchNo = $('input[name="purchaseEdit_batch[]"]').map(function(){return $(this).val();}).get();
  let expiry = $('input[name="purchaseEdit_expiry[]"]').map(function(){return $(this).val();}).get();
  let mrp = $('input[name="purchaseEdit_mrp[]"]').map(function(){return $(this).val();}).get();
  let salesPrice = $('input[name="purchaseEdit_salesPrice[]"]').map(function(){return $(this).val();}).get();
  let tax = $('input[name="purchaseEdit_tax[]"]').map(function(){return $(this).val();}).get();
  let qty = $('input[name="purchaseEdit_qty[]"]').map(function(){return $(this).val();}).get();
  let purchaseRate = $('input[name="purchaseEdit_purchaseRate[]"]').map(function(){return $(this).val();}).get();
  let amount = $('input[name="purchaseEdit_amount[]"]').map(function(){return $(this).val();}).get();

  let naration = $('#purchaseAdd_naration').val();
  let totalAmount = parseFloat($('.purchaseEdit_totalAmt').html());
  let totalDiscountPer = parseFloat($('#purchaseEdit_discount').val());
  let totalDiscount = parseFloat($('.purchaseEdit_discountAmt').html());
  let totalTaxAmount = parseFloat($('.purchaseEdit_taxAmt').html());
  let totalNetAmount = parseFloat($('.purchaseEdit_netTotalAmt').html());
  let paymentMode = $('#purchaseEdit_paymentMode').val();
  let payAmount = $('#purchaseEdit_payAmount').val();
  let dueAmount = totalNetAmount - payAmount;
  dueAmount = dueAmount.toFixed(2);
  if(payAmount > totalNetAmount){
    $('.purchaseEdit_payAmount_cls').html('Pay amount exceeds net amount.').css('color','red');
    return;
  }
    $.ajax({
        url:purchaseUpdateDatas,
        type:"POST",
        headers:{
            'X_CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{
            purchase_id:purchase_id,id:id,billNo:billNo,vendorID:vendorID,category:category,name:name,batchNo:batchNo,expiry:expiry,mrp:mrp,salesPrice:salesPrice,tax:tax,qty:qty,purchaseRate:purchaseRate,amount:amount,naration:naration,totalAmount:totalAmount,totalDiscountPer:totalDiscountPer,totalDiscount:totalDiscount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,payAmount:payAmount,dueAmount:dueAmount
        },
        success:function(response){
            console.log(response);
            if(response.success){
                toastSuccessAlert('Purchase updated successfully');
            }else{
                toastErrorAlert('something error found');
            }
        }
    });
});

function deleteRowEdit(x){
 x.closest("tr").remove(); // remove entire row with tr selector
 updateAmountEdit();
}
