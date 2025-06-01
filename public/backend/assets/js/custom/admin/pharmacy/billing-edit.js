function addNewRowBillingEddit() {
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
      $.ajax({
        url:getBillingCategoryDatas,
        type:"GET",
        success:function(response){
            let getCategoryDataEdit = response.data;
    let newRowDataBillingEdit = `<tr class="fieldGroup">
                              <td>
                                  <select id="billingAdd-category${rand}" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicine(this.value,${rand})">
                                        <option value="" selected disabled>Select</option>`;
                    getCategoryDataEdit.forEach(element =>{
                        newRowDataBillingEdit += ` <option value="${element.id}">${element.name}</option>`;
                        });
                    newRowDataBillingEdit += ` </select>
                              </td>
                              <td>
                                  <select id="billingAdd-name${rand}" name="billingAdd-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetails(this.value,${rand})">
                                      <option selected disabled>Select</option>
                                  </select>
                              </td>
                              <td>
                                  <select id="billingAdd-batch${rand}" name="billingAdd-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiry(this.value,${rand})">
                                      <option selected>Select</option>
                                      <option value="1">Batch A</option>
                                      <option value="2">Batch B</option>
                                      <option value="3">Batch C</option>
                                  </select>
                              </td>
                              <td>
                                  <div class=" position-relative">
                                      <input id="billingAdd-expiry${rand}" name="billingAdd-expiry[]" class="form-control radius-8 bg-base expiry-date"  type="text" placeholder="00/00/0000">
                                      
                                  </div>
                              </td>
                              <td>
                                  <input id="billingAdd-qty${rand}" name="billingAdd-qty[]" name="billingAdd-name" class="form-control form-control-sm" type="number" placeholder="Quantity" oninput="getBillingAmount(${rand})">
                              </td>
                              <td>
                                  <input id="billingAdd-avlQty${rand}" name="billingAdd-avlQty[]" type="number" class="form-control form-control-sm" placeholder="Avilable Qty" readonly>
                              </td>
                              <td>
                                  <input id="billingAdd-salesPrice${rand}" name="billingAdd-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" readonly>
                              </td>
                              <td>
                                  <input id="billingAdd-tax${rand}" name="billingAdd-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" readonly>
                              </td>
                               <td style="display: none;">
                                  <input id="billingAdd-taxAmount${rand}" name="billingAdd-taxAmount[]" class="form-control form-control-sm" type="number" value="">
                              </td>
                              <td>
                                  <input id="billingAdd-amount${rand}" name="billingAdd-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" readonly>
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

function getBillingMedicineEdit(id,randNum){
 $.ajax({
        url:getBillingMedicineNames,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            // console.log(response);
        let getData = response.data;
        let medicineDropdown1 = $("#billingEdit-name" + randNum); // Use the randNum to target the specific dropdown
        medicineDropdown1.find("option:not(:first)").remove(); // empity dropdown except first one
        getData.forEach(element => {
            medicineDropdown1.append(`<option value="${element.id}">${element.name}</option>`);
        });
        medicineDropdown1.trigger("change"); // Refresh Select2 dropdown
        }
    });
}

function getBatchDetailsEdit(id,randB){
    let medID = id;
    if(medID == null || medID == ''){
        medID = 0;}
    $.ajax({
            url:getBatchNumbers,
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
        url:getBatchExpiryDate,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            // console.log(response);
        let getData = response.data[0];
        let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
        $("#billingEdit-expiry" + randE).val(getData.expiry); 
        $("#billingEdit-avlQty" + randE).val(avlQty); 
        $("#billingEdit-salesPrice" + randE).val(getData.sales_price); 
        $("#billingEdit-tax" + randE).val(getData.tax); 
        }
    });
}
 function getBillingMedicineSelectedEdit(id,randNum) {
        console.log('onload');
         let billingID = $('#billingEdit_id' + randNum).val();
        $.ajax({
            url: getBillingNamesSelectEdit,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: id, billingID: billingID },
            success: function (response) {
                console.log(response);
                let getData = response.data.nameData[0];
                let getNameID = getData.name_id;
                let getMedicineData = response.data.medicines;
                // console.log(getNameID);
                 let medicineDropdown = $("#billingEdit_name" + randNum);
                medicineDropdown.find("option:not(:first)").remove(); // empty dropdown except first one
                getMedicineData.forEach(element => {
                   medicineDropdown.append(`<option value="${element.id}"${element.id == getNameID ? 'selected':''}>${element.name}</option>`);
                });
                medicineDropdown.trigger("change"); // Refresh Select2 dropdown
            }
        });
    }

function getBillingAmountEdit(randA){
    let qty = $("#billingEdit-qty" + randA).val();
    if(qty <= 0){
        qty = 0;
    }
    let salesPrice = $("#billingEdit-salesPrice" + randA).val();
    let tax = $("#billingEdit-tax" + randA).val();
    let amount = qty * salesPrice; // Calculate total amount before taxng tax
    $('#billingEdit-amount'+randA).val(amount);
    let currAmount = $('#billingEdit-amount'+randA).val();
    let currTaxAmount = (currAmount * tax)/100;
    $('#billingEdit-taxAmount'+randA).val(currTaxAmount);
    updateTotalBillingEdit();

    
}
// function updateTotalAmount(){
//     let total_amount = $('input[name="billingAdd-amount[]"]').map(function(){return $(this).val();}).get();
//     let totalAmountSum = total_amount.map(Number).reduce((acc,val) => acc + val,0) ?? 0;
//     $('.billingAdd-totalAmount').html(totalAmountSum); // Update the total amount in the UI
//     let total_tax_amount = $('input[name="billingAdd-taxAmount[]"').map(function(){return $(this).val();}).get();
//     let totalTaxAmountSum = total_tax_amount.map(Number).reduce((acc,val) => acc + val,0) ?? 0;
//     $('.billingAdd-totalTax').html(totalTaxAmountSum);
//     let totalNetAmount = parseFloat(totalAmountSum) + parseFloat(totalTaxAmountSum);
//     $('.billingAdd-totalNetAmount').html(totalNetAmount);
// }

// function updateDiscount(){
//     let discountPer = $('#billingAdd-discountPer').val() || 0;
//     let total_amount = $('.billingAdd-totalAmount').html();
//     let total_tax_amount = $('.billingAdd-totalTax').html();
//     let discountAmount = (parseFloat(total_amount) * discountPer)/100; 
//     $('.billingAdd-discountAmount').html(discountAmount);
//     let tax_after_discount = (parseFloat(total_tax_amount) * discountPer)/100;
//     let total_tax_after_discount = total_tax_amount - tax_after_discount;
//     $('.billingAdd-totalTax').html(total_tax_after_discount.toFixed(2));
//     let net_amount_after_discount = parseFloat(total_amount) - discountAmount + total_tax_after_discount;
//      $('.billingAdd-totalNetAmount').html(net_amount_after_discount.toFixed(2));
// }
function updateTotalBillingEdit() {
    // Calculate total amount and total tax amount
    let total_amount = $('input[name="billingAdd-amount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let total_tax_amount = $('input[name="billingAdd-taxAmount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let totalAmountSum = total_amount.reduce((acc, val) => acc + val, 0);
    let totalTaxAmountSum = total_tax_amount.reduce((acc, val) => acc + val, 0);
    // Update the UI with total amounts
    $('.billingAdd-totalAmount').html(totalAmountSum.toFixed(2));
    $('.billingAdd-totalTax').html(totalTaxAmountSum.toFixed(2));
    // Calculate net amount
    let totalNetAmount = totalAmountSum + totalTaxAmountSum;
    $('.billingAdd-totalNetAmount').html(totalNetAmount.toFixed(2));
    // Calculate discount if applicable
    let discountPer = parseFloat($('#billingAdd-discountPer').val()) || 0;
    if (discountPer > 0) {
        let discountAmount = (totalAmountSum * discountPer) / 100;
        $('.billingAdd-discountAmount').html(discountAmount.toFixed(2));

        let tax_after_discount = (totalTaxAmountSum * discountPer) / 100;
        let total_tax_after_discount = totalTaxAmountSum - tax_after_discount;
        $('.billingAdd-totalTax').html(total_tax_after_discount.toFixed(2));

        let net_amount_after_discount = totalAmountSum - discountAmount + total_tax_after_discount;
        $('.billingAdd-totalNetAmount').html(net_amount_after_discount.toFixed(2));
    } else {
        // If no discount, ensure discount amount is reset
        $('.billingAdd-discountAmount').html('0.00');
    }
}
function resetAddPatientEdit(){
    $('#billingAdd-patientForm')[0].reset();
    $('.billingAdd-patientName_errorCls').addClass('d-none');
    $('.billingAdd-guardianName_errorCls').addClass('d-none');
    $('.billingAdd-patientBloodType_errorCls').addClass('d-none');
    $('.billingAdd-patientDOB_errorCls').addClass('d-none');
    $('.billingAdd-patientMStatus_errorCls').addClass('d-none');
    $('.billingAdd-patientMobile_errorCls').addClass('d-none');
    $('.billingAdd-patientAddess_errorCls').addClass('d-none');
}

// $('#billingAdd-patientForm').on('submit',function(e){
//      e.preventDefault();
//     let patientName  = validateField('billingAdd-patientName', 'input');
//     let guardianName = validateField('billingAdd-guardianName', 'input');
//     // let patientGender = validateField('patientGender', 'radio');
//     let patientBloodType = validateField('billingAdd-patientBloodType', 'select');
//     let patientDOB = validateField('billingAdd-patientDOB', 'select');
//     let patientMStatus = validateField('billingAdd-patientMStatus', 'select');     
//     let patientMobile = validateField('billingAdd-patientMobile', 'mobile');
//     let patientAddess = validateField('billingAdd-patientAddess', 'input');
//         if(patientName === true && guardianName === true && patientBloodType === true && patientDOB === true && patientMStatus === true && patientMobile === true && patientAddess === true){    
           
//             let name = $('#billingAdd-patientName').val();
//             let guardian_name = $('#billingAdd-guardianName').val();
//             let gender = $('input[name="billingAdd-patientGender"]:checked').val(); // Corrected na
//             let bloodtype = $('#billingAdd-patientBloodType').val();
//             let dob = $('#billingAdd-patientDOB').val();
//             let mstatus = $('#billingAdd-patientMStatus').val();
//             let mobile = $('#billingAdd-patientMobile').val();
//             let address = $('#billingAdd-patientAddess').val();
//             let alt_mobile = $('#billingAdd-patientAltMobile').val();
//             let allergy = $('#billingAdd-patientAllergy').val();
//             $.ajax({
//                 url: billingAddNewPatient,
//                 type:"POST",
//                 headers:{
//                     'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//                 },
//                 data:{
//                 name:name,guardian_name:guardian_name,gender:gender,bloodtype:bloodtype,dob:dob,mstatus:mstatus,mobile:mobile,address:address,alt_mobile:alt_mobile,allergy:allergy
//                 },
//                 success:function(response){
//                     if(response.success){
//                         toastSuccessAlert('New Patient added successfully');
//                         $('#billingAdd-patientModal').modal('hide');
//                         setTimeout(function(){
//                             window.location.reload();
//                         },1500);
//                     }else{
//                         console.log('error found');
//                     }
//                 },
//                 error:function(xhr, status, error){
//                     console.log(xhr.respnseText);
//                     alert('An error occurred: '+error);
//                 }
//             });
//         }else{
//             console.log("Please fill all required fields");
//         }    
// });

// $('#billingAdd-Form').on('submit',function(e){
//   e.preventDefault();
 
//   let patientIDCheck  = validateField('billingAdd-patient', 'select');
//   console.log(patientIDCheck);
//   if(patientIDCheck == false){
//     return;
//   }
//   let category = $('select[name="billingAdd-category[]"]').map(function(){return $(this).val();}).get();
//   let name = $('select[name="billingAdd-name[]"]').map(function(){return $(this).val();}).get();
//   let batchNo = $('select[name="billingAdd-batch[]"]').map(function(){return $(this).val();}).get();
//   let expiry = $('input[name="billingAdd-expiry[]"]').map(function(){return $(this).val();}).get();
//   let qty = $('input[name="billingAdd-qty[]"]').map(function(){return $(this).val();}).get();
//   let salesPrice = $('input[name="billingAdd-salesPrice[]"]').map(function(){return $(this).val();}).get();
//   let taxPer = $('input[name="billingAdd-tax[]"]').map(function(){return $(this).val();}).get();
//   let taxAmount = $('input[name="billingAdd-taxAmount[]"]').map(function(){return $(this).val();}).get();
//   let amount = $('input[name="billingAdd-amount[]"]').map(function(){return $(this).val();}).get();

//    let billNo = $('.billingAdd-billNo').html();
//   let patientID = $('#billingAdd-patient').val();
//   let resDoctor = $('#billingAdd-resDoctor').val();
//   let outDoctor = $('#billingAdd-outDoctor').val();
//   let notes = $('#billingAdd-note').val();
//   let totalAmount = parseFloat($('.billingAdd-totalAmount').html());
//   let discountPer = $('#billingAdd-discountPer').val();
//   let totalDiscountAmount = parseFloat($('.billingAdd-discountAmount').html());
//   let totalTaxAmount = parseFloat($('.billingAdd-totalTax').html());
//   let totalNetAmount = parseFloat($('.billingAdd-totalNetAmount').html());
//   let paymentMode = $('#billingAdd-paymentMode').val();
//   let payAmount = $('#billingAdd-payAmount').val();
//   let dueAmount = totalNetAmount - payAmount;
//   dueAmount = dueAmount.toFixed(2);
//   $.ajax({
//     url:billingAddDatas,
//     type:"POST",
//     headers:{
//         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//     },
//     data:{
//         category:category,name:name,batchNo:batchNo,expiry:expiry,qty:qty,salesPrice:salesPrice,taxPer:taxPer,taxAmount:taxAmount,amount:amount,billNo:billNo,patientID:patientID,resDoctor:resDoctor,outDoctor:outDoctor,notes:notes,totalAmount:totalAmount,discountPer:discountPer,totalDiscountAmount:totalDiscountAmount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,payAmount:payAmount,dueAmount:dueAmount
//     },
//     success:function(response){
//         console.log(response);
//         if(response.success){
//              toastSuccessAlert('Medicine Billings successfully done');
//         }else{
//              toastErrorAlert('something error found');
//         }
//     }
//   });
// });
