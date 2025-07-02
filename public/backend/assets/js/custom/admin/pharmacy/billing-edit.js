function addNewRowBillingEdit() {
    $('.expity-select-status').html(1);
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
      $.ajax({
        url:getBillingCategoryDataEdit,
        type:"GET",
        success:function(response){
            let getCategoryDataEdit = response.data;
            let newRowDataBillingEdit = `<tr class="fieldGroup">
                              <td>
                              <input type="hidden" id="billingEdit_id${rand}" name="billingEdit_id[]" value="">
                                  <select id="billingEdit-category${rand}" name="billingEdit-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicineEdit(this.value,${rand})">
                                        <option value="">Select</option>`;
                    getCategoryDataEdit.forEach(element =>{
                        newRowDataBillingEdit += ` <option value="${element.id}">${element.name}</option>`;
                        });
                    newRowDataBillingEdit += ` </select>
                              </td>
                              <td>
                                  <select id="billingEdit-name${rand}" name="billingEdit-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetailsEdit(this.value,${rand})">
                                      <option value="" >Select</option>
                                  </select>
                              </td>
                              <td>
                                  <select id="billingEdit-batch${rand}" name="billingEdit-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiryEdit(this.value,${rand})">
                                      <option value="">Select</option>
                                  </select>
                              </td>
                              <td>
                                  <div class=" position-relative">
                                      <input id="billingEdit-expiry${rand}" name="billingEdit-expiry[]" class="form-control radius-8 bg-base expiry-date"  type="text" placeholder="00/00/0000" readonly>
                                  </div>
                              </td>
                              <td>
                                  <input id="billingEdit-qty${rand}" name="billingEdit-qty[]" name="billingEdit-name" class="form-control form-control-sm" type="number" placeholder="Quantity" oninput="getBillingAmountEdit(${rand})">
                              </td>
                              <td>
                                  <input id="billingEdit-avlQty${rand}" name="billingEdit-avlQty[]" type="number" class="form-control form-control-sm" placeholder="Avilable Qty" readonly>
                              </td>
                              <td>
                                  <input id="billingEdit-salesPrice${rand}" name="billingEdit-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" readonly>
                              </td>
                              <td>
                                  <input id="billingEdit-tax${rand}" name="billingEdit-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" readonly>
                              </td>
                               <td style="display: none;">
                                  <input id="billingEdit-taxAmount${rand}" name="billingEdit-taxAmount[]" class="form-control form-control-sm" type="number" value="">
                              </td>
                              <td>
                                  <input id="billingEdit-amount${rand}" name="billingEdit-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" readonly>
                              </td>
                              <td>
                                    <button type="button" class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowBillingEdit(this)">
                                      <i class="ri-close-line"></i>
                                  </button>
                              </td>
                          </tr>`;

    $('.newRowAppendBillingEdit').parent().append(newRowDataBillingEdit); // Append properly to tbody

    // Reinitialize Select2 for newly added row
    $('.select2-cls').select2();
       }
    });
}
 function removeRowBillingEdit(x){
    x.closest("tr").remove(); // remove entire row with tr selector
     getBillingAmountEdit();
}

function getBillingMedicineEdit(id,randN){
 $.ajax({
        url:getBillingMedicineNameEdit,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id,billingItemID:randN},
        success:function(response){
        let getData = response.data;
        let billingEditName = $("#billingEdit-name" + randN); // Use the randNum to target the specific dropdown
        if(response.billingItem != ''){
            let billingItem = response.billingItem[0];
            billingEditName.find("option:not(:first)").remove(); // empity dropdown except first one
            getData.forEach(element => {
            billingEditName.append(`<option value="${element.id}" ${element.id == billingItem.name_id ? 'selected':''}>${element.name}</option>`);
            });
        }else{
            billingEditName.find("option:not(:first)").remove(); // empity dropdown except first one
            getData.forEach(element => {
            billingEditName.append(`<option value="${element.id}">${element.name}</option>`);
            });
        }
        billingEditName.trigger("change"); // Refresh Select2 dropdown
        }
    });
}

function getBatchDetailsEdit(id,randB){
    // console.log(id);
    // console.log(randB);
    let expiry_select_status = $('.expity-select-status').html();
    let medID = id;
    if(medID == null || medID == ''){
        medID = 0;}
    $.ajax({
            url:getBatchNumberEdit,
            type:"GET",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:medID},
            success:function(response){
               // console.log(response);
            let getData = response.data;
             let getBillingData = response.billingData[0];
            let batchDropdown2 = $("#billingEdit-batch" + randB); // Use the randNum to target the specific dropdown
            batchDropdown2.find("option:not(:first)").remove(); // empity dropdown except first one
            getData.forEach(element => {
                if(expiry_select_status == 1){
                    batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
                }else{
                    batchDropdown2.append(`<option value="${element.id}" ${element.id == getBillingData.batch_no ? 'selected':''}>${element.batch_no}</option>`);
                }
            });
            batchDropdown2.trigger("change"); // Refresh Select2 dropdown
            }
    });
   // $('.expity-select-status').html(0)
}

// function getBatchExpiryEdit(id,randE){
//     $.ajax({
//         url:getBatchExpiryDateEdit,
//         type:"GET",
//         headers:{
//             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//         },
//         data:{id:id},
//         success:function(response){
//         let getData = response.data[0];
//         let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
//         $("#billingEdit-expiry" + randE).val(getData.expiry); 
//         $("#billingEdit-avlQty" + randE).val(avlQty); 
//         $("#billingEdit-salesPrice" + randE).val(getData.sales_price); 
//         $("#billingEdit-tax" + randE).val(getData.tax); 
//         }
//     });
// }
 function getBillingMedicineSelectedEdit(catValue,randNum) {
        let billingID = $('#billingEdit_id' + randNum).val();
        $.ajax({
            url: getBillingNamesSelectEdit,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { catValue: catValue, billingID: billingID },
            success: function (response) {
                let getMedicineData = response.data.medicines;
                let getItemsData = response.data.itemsData[0];
                let batchData = response.data.batchDetails;
                // console.log(getItemsData);
                let billingEditName = $("#billingEdit-name" + randNum);
                billingEditName.find("option:not(:first)").remove(); // empty dropdown except first one
                getMedicineData.forEach(element => {
                   billingEditName.append(`<option value="${element.id}"${element.id == getItemsData.name_id ? 'selected':''}>${element.name}</option>`);
                });
                billingEditName.trigger("change"); // Refresh Select2 dropdown

                let billingEditBatch = $("#billingEdit-batch" + randNum);
                billingEditBatch.find("option:not(:first)").remove(); // empty dropdown except first one
                batchData.forEach(element => {
                   billingEditBatch.append(`<option value="${element.id}"${element.id == getItemsData.batch_no ? 'selected':''}>${element.batch_no}</option>`);
                });
                billingEditBatch.trigger("change"); // Refresh Select2 dropdown
            }
        });
    }

    function getBatchExpiryEdit(batchValue,randNum){

        $.ajax({
            url:getBatchExpiryDateEdit,
            type:"GET",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:batchValue},
            success:function(response){
                if(response.data != ''){
                    let  getData = response.data[0];
                    let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
                    $("#billingEdit-expiry" + randNum).val(getData.expiry); 
                    // $("#billingEdit-qty" + randNum).val(getData.qty); 
                    $("#billingEdit-avlQty" + randNum).val(avlQty); 
                    $("#billingEdit-salesPrice" + randNum).val(getData.sales_price); 
                    $("#billingEdit-tax" + randNum).val(getData.tax); 
                    $("#billingEdit-amount" + randNum).val(getData.amount); 
                    }
                    getBillingAmountEdit(randNum);
                }
            });
    }
function getBillingAmountEdit(randQ){
    let qty = parseFloat($("#billingEdit-qty" + randQ).val());
    if(qty <= 0){
        qty = 0;
    }
    let avlQty =  parseFloat($("#billingEdit-avlQty" + randQ).val()); 
    if(qty > avlQty){
        $("#billingEdit-qty"+randQ).css({"border-color": "#ef4a00","border-width": "1px","border-style": "solid"});
        $('.billingEditSubmitBtn').prop('disabled',true);
         toastErrorAlert('Stock quantity exceeded limit.');
         return;
    }else{
         $("#billingEdit-qty"+randQ).css("border-color","#d1d5db");
        $('.billingEditSubmitBtn').prop('disabled',false);

    }
    let salesPrice = $("#billingEdit-salesPrice" + randQ).val();
    let tax = $("#billingEdit-tax" + randQ).val();
    let amount = qty * salesPrice; // Calculate total amount before taxng tax
    $('#billingEdit-amount'+randQ).val(amount);
    let currAmount = $('#billingEdit-amount'+randQ).val();
    let currTaxAmount = (currAmount * tax)/100;
    $('#billingEdit-taxAmount'+randQ).val(currTaxAmount);
    updateTotalBillingEdit();
}

function updateTotalBillingEdit() {
    // Calculate total amount and total tax amount
    let total_amount = $('input[name="billingEdit-amount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let total_tax_amount = $('input[name="billingEdit-taxAmount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let totalAmountSum = total_amount.reduce((acc, val) => acc + val, 0);
    let totalTaxAmountSum = total_tax_amount.reduce((acc, val) => acc + val, 0);
    // Update the UI with total amounts
    $('.billingEdit-totalAmount').html(totalAmountSum.toFixed(2));
    $('.billingEdit-totalTax').html(totalTaxAmountSum.toFixed(2));
    // Calculate net amount
    let totalNetAmount = totalAmountSum + totalTaxAmountSum;
    $('.billingEdit-totalNetAmount').html(totalNetAmount.toFixed(2));
    // Calculate discount if applicable
    let discountPer = parseFloat($('#billingEdit-discountPer').val()) || 0;
    if (discountPer > 0) {
        let discountAmount = (totalAmountSum * discountPer) / 100;
        $('.billingEdit-discountAmount').html(discountAmount.toFixed(2));

        let tax_after_discount = (totalTaxAmountSum * discountPer) / 100;
        let total_tax_after_discount = totalTaxAmountSum - tax_after_discount;
        $('.billingEdit-totalTax').html(total_tax_after_discount.toFixed(2));

        let net_amount_after_discount = totalAmountSum - discountAmount + total_tax_after_discount;
        $('.billingEdit-totalNetAmount').html(net_amount_after_discount.toFixed(2));
    } else {
        // If no discount, ensure discount amount is reset
        $('.billingEdit-discountAmount').html('0.00');
    }
}

$('#billingEdit-Form').on('submit',function(e){
  e.preventDefault();

  let patientIDCheck  = validateField('billingEdit-patient', 'select');
  if(patientIDCheck == false){
    return;
  }
  $('.billingEditSubmitBtn').addClass('d-none');
  $('.billingEditSpinnBtn').removeClass('d-none');
  let billing_id = $('#billingEdit_billing_id').val();
  let editID = $('input[name="billingEdit_id[]"]').map(function(){return $(this).val();}).get();
  let category = $('select[name="billingEdit-category[]"]').map(function(){return $(this).val();}).get();
  let name = $('select[name="billingEdit-name[]"]').map(function(){return $(this).val();}).get();
  let batchNo = $('select[name="billingEdit-batch[]"]').map(function(){return $(this).val();}).get();
  let expiry = $('input[name="billingEdit-expiry[]"]').map(function(){return $(this).val();}).get();
  let qty = $('input[name="billingEdit-qty[]"]').map(function(){return $(this).val();}).get();
  let salesPrice = $('input[name="billingEdit-salesPrice[]"]').map(function(){return $(this).val();}).get();
  let taxPer = $('input[name="billingEdit-tax[]"]').map(function(){return $(this).val();}).get();
  let taxAmount = $('input[name="billingEdit-taxAmount[]"]').map(function(){return $(this).val();}).get();
  let amount = $('input[name="billingEdit-amount[]"]').map(function(){return $(this).val();}).get();

  let billNo = $('.billingEdit-billNo').html();
  let patientID = $('#billingEdit-patient').val();
  let resDoctor = $('#billingEdit-resDoctor').val();
  let outDoctor = $('#billingEdit-outDoctor').val();
  let notes = $('#billingEdit-note').val();
  let totalAmount = parseFloat($('.billingEdit-totalAmount').html());
  let discountPer = $('#billingEdit-discountPer').val();
  let totalDiscountAmount = parseFloat($('.billingEdit-discountAmount').html());
  let totalTaxAmount = parseFloat($('.billingEdit-totalTax').html());
  let totalNetAmount = parseFloat($('.billingEdit-totalNetAmount').html());
  let paymentMode = $('#billingEdit-paymentMode').val();
  let payAmount = $('#billingEdit-payAmount').val();
//   let dueAmount = totalNetAmount - payAmount;
//   dueAmount = dueAmount.toFixed(2);
  $.ajax({
    url:billingEditDatas,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{
        billing_id:billing_id,editID:editID,category:category,name:name,batchNo:batchNo,expiry:expiry,qty:qty,salesPrice:salesPrice,taxPer:taxPer,taxAmount:taxAmount,amount:amount,billNo:billNo,patientID:patientID,resDoctor:resDoctor,outDoctor:outDoctor,notes:notes,totalAmount:totalAmount,discountPer:discountPer,totalDiscountAmount:totalDiscountAmount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,payAmount:payAmount
    },
    success:function(response){
        if(response.success){
             toastSuccessAlert('Billing updated successfully');
             window.location.reload();
        }else{
             toastErrorAlert('something error found');
             $('.billingEditSpinnBtn').addClass('d-none');
             $('.billingEditSubmitBtn').removeClass('d-none');
        }
    }
  });
});

// function checkBillingPayAmount(payAmount){
//  let dueAmount =  parseFloat($('.billingEdit-totalDueAmount').html()) || 0;
//  dueAmount = parseFloat(dueAmount);
// if (payAmount > dueAmount) {
//     $('.billingEdit-payAmount-error').removeClass('d-none').html('Pay Amount Exceeded Due Amount').addClass('text-danger');
//     $('#billingEdit-payAmount').val(dueAmount); // Reset to max possible value
//     return;
// } else {
//     $('.billingEdit-payAmount-error').addClass('d-none');
// }
// }

function checkBillingPayAmount(billing_id,amount){
    let totalNetAmount = parseFloat($('.billingEdit-totalNetAmount').html());
    $.ajax({
        url:getBillingData,
        type:"POST",
        headers:{
            'X_CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:billing_id},
        success:function(response){
            let paid_amount = response.data[0].paid_amount;
            if(totalNetAmount - (parseFloat(amount) + parseFloat(paid_amount)) < 0){
                $('.billingEdit-payAmount-error').removeClass('d-none').html('Pay amount exceeds due amount.').css('color','red');
                $('.billingEditSubmitBtn').prop('disabled',true);
                return;
            }else{
                $('.billingEdit-payAmount-error').addClass('d-none').html('');
                $('.billingEditSubmitBtn').prop('disabled',false);
            }
        }
    });
}


