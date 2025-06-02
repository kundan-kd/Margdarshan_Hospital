function addNewRowBillingEdit() {
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
      $.ajax({
        url:getBillingCategoryDataEdit,
        type:"GET",
        success:function(response){
            let getCategoryDataEdit = response.data;
            let newRowDataBillingEdit = `<tr class="fieldGroup">
                              <td>
                                  <select id="billingEdit-category${rand}" name="billingEdit-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicineEdit(this.value,${rand})">
                                        <option value="" selected disabled>Select</option>`;
                    getCategoryDataEdit.forEach(element =>{
                        newRowDataBillingEdit += ` <option value="${element.id}">${element.name}</option>`;
                        });
                    newRowDataBillingEdit += ` </select>
                              </td>
                              <td>
                                  <select id="billingEdit-name${rand}" name="billingEdit-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetailsEdit(this.value,${rand})">
                                      <option value="" selected disabled>Select</option>
                                  </select>
                              </td>
                              <td>
                                  <select id="billingEdit-batch${rand}" name="billingEdit-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiryEdit(this.value,${rand})">
                                      <option value="" selected>Select</option>
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
                                    <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowBilling(this)">
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
        data:{id:id},
        success:function(response){
            // console.log(response);
        let getData = response.data;
        let billingEditName = $("#billingEdit-name" + randN); // Use the randNum to target the specific dropdown
        billingEditName.find("option:not(:first)").remove(); // empity dropdown except first one
        getData.forEach(element => {
            console.log(element);
           billingEditName.append(`<option value="${element.id}">${element.name}</option>`);
        });
        billingEditName.trigger("change"); // Refresh Select2 dropdown
        }
    });
}

function getBatchDetailsEdit(id,randB){
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
            let getData = response.data;
            let batchDropdown2 = $("#billingEdit-batch" + randB); // Use the randNum to target the specific dropdown
            batchDropdown2.find("option:not(:first)").remove(); // empity dropdown except first one
            getData.forEach(element => {
                batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
            });
            batchDropdown2.trigger("change"); // Refresh Select2 dropdown
            }
    });
}

function getBatchExpiryEdit(id,randE){
    $.ajax({
        url:getBatchExpiryDateEdit,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
        let getData = response.data[0];
        let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
        $("#billingEdit-expiry" + randE).val(getData.expiry); 
        $("#billingEdit-avlQty" + randE).val(avlQty); 
        $("#billingEdit-salesPrice" + randE).val(getData.sales_price); 
        $("#billingEdit-tax" + randE).val(getData.tax); 
        }
    });
}
 function getBillingMedicineSelectedEdit(catValue,randNum) {
        let billingID = $('#billingEdit_id' + randNum).val();
      //  console.log('catId--'+catId);
       // console.log('billingid--'+billingID);
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
                let getData = response.data[0];
                console.log(getData);
                let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
                $("#billingEdit-expiry" + randNum).val(getData.expiry); 
                $("#billingEdit-qty" + randNum).val(getData.qty); 
                $("#billingEdit-avlQty" + randNum).val(avlQty); 
                $("#billingEdit-salesPrice" + randNum).val(getData.sales_price); 
                $("#billingEdit-tax" + randNum).val(getData.tax); 
                $("#billingEdit-amount" + randNum).val(getData.amount); 
                }
            });
    }
function getBillingAmountEdit(randQ){
    let qty = $("#billingEdit-qty" + randQ).val();
    if(qty <= 0){
        qty = 0;
    }
    let avlQty =  $("#billingEdit-avlQty" + randQ).val(); 
    if(qty > avlQty){
        $("#billingEdit-qty"+randQ).css({"border-color": "#ef4a00","border-width": "1px","border-style": "solid"});
         toastErrorAlert('Stock quantity exceeded limit.');
         return;
    }else{
         $("#billingEdit-qty"+randQ).css("border-color","#d1d5db");
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
// function resetAddPatientEdit(){
//     $('#billingAdd-patientForm')[0].reset();
//     $('.billingAdd-patientName_errorCls').addClass('d-none');
//     $('.billingAdd-guardianName_errorCls').addClass('d-none');
//     $('.billingAdd-patientBloodType_errorCls').addClass('d-none');
//     $('.billingAdd-patientDOB_errorCls').addClass('d-none');
//     $('.billingAdd-patientMStatus_errorCls').addClass('d-none');
//     $('.billingAdd-patientMobile_errorCls').addClass('d-none');
//     $('.billingAdd-patientAddess_errorCls').addClass('d-none');
// }
