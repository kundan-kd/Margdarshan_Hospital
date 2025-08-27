let purchaseListData = [];
getPurchaseData();
function getPurchaseData(){
    $.ajax({
        url:getPurchaseDatasAll,
        type:"GET",
        success:function(response){
            purchaseListData = [];
            let getData = #response.data;
            getData.forEach(element => {
               
                purchaseListData.push(element); 
            });
          
        }
    });
}
//  function fetchPurchaseData(){
//     purchaseListData.forEach(function(element){
//         // console.log(element);
//     });
//  }

// setMedicineCategory();

// function setMedicineCategory(){
//     $.ajax({
//         url:getBillingCategoryDatas,
//         type:"GET",
//         success:function(response){
//             console.log(response.categoryList);
//             medicineCategory = [];
//             $('#billingAdd-category0').empty();
//             let categoryDropdown ='';
//             categoryDropdown +=`<option value="">Select</option>`;
//             response.categoryList.forEach(element =>{
//                 if(element.billMedicine.length > 0){
//                     medicineCategory.push(element);
//                     categoryDropdown += ` <option value="${element.id}">${element.name}</option>`;
//                 }
//             });
//             $('#billingAdd-category0').append(categoryDropdown);
//         }
//     });
// }
function addNewRowBilling(){
    let category_id = $('#billingAdd-category0').val();
    if(category_id && category_id.length > 0) {
        let category_name = $('#billingAdd-category0 option:selected').text();
        let name_id = $('#billingAdd-name0').val();
        let name = $('#billingAdd-name0 option:selected').text();
        let batch_id = $('#billingAdd-batch0').val();
        let batch_name = $('#billingAdd-batch0 option:selected').text();
        let expiry = $('#billingAdd-expiry0').val();
        let qty = $('#billingAdd-qty0').val();
        let avlQty = $('#billingAdd-avlQty0').val();
        let salesPrice = $('#billingAdd-salesPrice0').val();
        let amount = $('#billingAdd-amount0').val();
        let tax = $('#billingAdd-tax0').val();
        let taxAmount = $('#billingAdd-taxAmount0').val();
        let itemDatas = '';
        itemDatas += `<tr>
            <td>${category_name}
                <input type="hidden" name="billingAdd-category[]" value="${category_id}">
            </td>
            <td>${name}
                <input type="hidden" name="billingAdd-name[]" value="${name_id}">
            </td>
            <td>${batch_name}
                <input type="hidden" name="billingAdd-batch[]" value="${batch_id}">
            </td>
            <td>${expiry}
                <input type="hidden" name="billingAdd-expiry[]" value="${expiry}">
            </td>
            <td>${qty}
                <input type="hidden" name="billingAdd-qty[]" value="${qty}">
            </td>
            <td>${avlQty}
                <input type="hidden" name="billingAdd-avlQty[]" value="${avlQty}">
            </td>
            <td>${salesPrice}
                <input type="hidden" name="billingAdd-salesPrice[]" value="${salesPrice}">
            </td>
            <td>${amount}
                <input type="hidden" name="billingAdd-amount[]" value="${amount}">
            </td>
            <td>${tax}
                <input type="hidden" name="billingAdd-tax[]" value="${tax}">
            </td>
            <td>
                <input type="hidden" name="billingAdd-taxAmount[]" value="${taxAmount}">
            </td>
            <td>
            <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowBilling(this)">
                <i class="ri-close-line"></i>
            </button>
        </td>
        </tr>`;
        $('.newRowAppendBilling').parent().append(itemDatas); // Append properly to tbody
        // / Clear inputs
        $('#billingAdd-category0').val('').change();
        $('#billingAdd-name0').val('').change();
        $('#billingAdd-batch0').val('').change();
        $('#billingAdd-expiry0').val('');
        $('#billingAdd-qty0').val('');
        $('#billingAdd-avlQty0').val('');
        $('#billingAdd-salesPrice0').val('');
        $('#billingAdd-amount0').val('');
        $('#billingAdd-tax0').val('');
        $('#billingAdd-taxAmount0').val('');
    }else{
         toastErrorAlert('Please add items to proceed');
    }
   

 }
// function addNewRowBilling22() {
//     let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
            
            
//             let newRowDataBilling = `<tr class="fieldGroup">
//                               <td>
//                                   <select id="billingAdd-category${rand}" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicine(this.value,${rand})">
//                                         <option value="" selected disabled>Select</option>`;
//                                     medicineCategory.forEach(element =>{
//                                      newRowDataBilling += ` <option value="${element.id}">${element.name}</option>`;
//                                     });
//                                 newRowDataBilling += ` </select>
//                               </td>
//                               <td>
//                                   <select id="billingAdd-name${rand}" name="billingAdd-name[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchDetails(this.value,${rand})">
//                                       <option selected disabled>Select</option>
//                                   </select>
//                               </td>
//                               <td>
//                                   <select id="billingAdd-batch${rand}" name="billingAdd-batch[]" class="form-select form-select-sm select2-cls w-100" onchange="getBatchExpiry(this.value,${rand})">
//                                       <option selected>Select</option>
//                                       <option value="1">Batch A</option>
//                                       <option value="2">Batch B</option>
//                                       <option value="3">Batch C</option>
//                                   </select>
//                               </td>
//                               <td>
//                                   <div class=" position-relative">
//                                       <input id="billingAdd-expiry${rand}" name="billingAdd-expiry[]" class="form-control radius-8 bg-base expiry-date${rand}"  type="text" placeholder="MM/YYYY" readonly>
                                      
//                                   </div>
//                               </td>
//                               <td>
//                                   <input id="billingAdd-qty${rand}" name="billingAdd-qty[]" class="form-control form-control-sm" type="number" placeholder="Quantity" oninput="getBillingAmount(${rand})">
//                               </td>
//                               <td>
//                                   <input id="billingAdd-avlQty${rand}" name="billingAdd-avlQty[]" type="number" class="form-control form-control-sm" placeholder="Avilable Qty" readonly>
//                               </td>
//                               <td>
//                                   <input id="billingAdd-salesPrice${rand}" name="billingAdd-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price" readonly>
//                               </td>
//                                <td>
//                                   <input id="billingAdd-amount${rand}" name="billingAdd-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount" readonly>
//                               </td>
//                               <td>
//                                   <input id="billingAdd-tax${rand}" name="billingAdd-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" readonly>
//                               </td>
//                                <td style="display: none;">
//                                   <input id="billingAdd-taxAmount${rand}" name="billingAdd-taxAmount[]" class="form-control form-control-sm" type="text">
//                               </td>
                             
//                               <td>
//                                     <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowBilling(this)">
//                                       <i class="ri-close-line"></i>
//                                   </button>
//                               </td>
//                           </tr>`;

//                 $('.newRowAppendBilling').parent().append(newRowDataBilling); // Append properly to tbody
//                 getDatePicker('.expiry-date'+ rand); 
//                 // Reinitialize Select2 for newly added row
//                 $('.select2-cls').select2();
//     //   }
//     // });
// }
 function removeRowBilling(x){
    x.closest("tr").remove(); // remove entire row with tr selector
     getBillingAmount();

}

// function getBillingMedicine(id,randNum){
//     let medicineDropdown1 = $("#billingAdd-name" + randNum);
//     medicineDropdown1.find("option:not(:first)").remove();
//     medicineCategory.forEach(element => {
//         if(element.id == parseInt(id)){
//             element.billMedicine.forEach(element_medicine => {
//                 medicineDropdown1.append(`<option value="${element_medicine.id}">${element_medicine.name}</option>`);
//             });
//         }
//     });
//     medicineDropdown1.trigger("change");
// //  $.ajax({
// //         url:getBillingMedicineNames,
// //         type:"GET",
// //         headers:{
// //             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
// //         },
// //         data:{id:id},
// //         success:function(response){
// //         let getData = response.data;
// //         let medicineDropdown1 = $("#billingAdd-name" + randNum); // Use the randNum to target the specific dropdown
// //         medicineDropdown1.find("option:not(:first)").remove(); // empity dropdown except first one
// //         getData.forEach(element => {
// //             medicineDropdown1.append(`<option value="${element.id}">${element.name}</option>`);
// //         });
// //         medicineDropdown1.trigger("change"); // Refresh Select2 dropdown
// //         }
// //     });
// }
function getBillingMedicine(id,randNum){
    // console.log(id,randNum);
    //  $.ajax({
    //     url:getBillingMedicineNames,
    //     type:"GET",
    //     headers:{
    //         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    //     },
    //     data:{id:id},
    //     success:function(response){
    //         console.log(response);
    //     let getData = response.data;
    //     let medicine_name = $("#billingAdd-name" + randNum); // Use the randNum to target the specific dropdown
    //     medicine_name.find("option:not(:first)").remove(); // empity dropdown except first one
    //     getData.forEach(element => {
    //         medicine_name.append(`<option value="${element.name_id}">${element.name_id}</option>`);
    //     });
    //     medicine_name.trigger("change"); // Refresh Select2 dropdown
    //     }
    // });


     let medicineDropdown1 = $("#billingAdd-name" + randNum);
    medicineDropdown1.find("option:not(:first)").remove();
    purchaseListData.forEach(element => {
        if(element.category_id == parseInt(id)){
            // element.billMedicine.forEach(element_medicine => {
                medicineDropdown1.append(`<option value="${element.name_id}">${element.name}</option>`);
            // });
        }
    });
}
function getBatchDetails(id,randNum){
    // $.ajax({
    //         url:getBatchNumbers,
    //         type:"GET",
    //         headers:{
    //             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    //         },
    //         data:{id:id},
    //         success:function(response){
    //             console.log(response);
    //         let getData = response.data;
    //         let batchDropdown2 = $("#billingAdd-batch" + randNum); // Use the randNum to target the specific dropdown
    //         batchDropdown2.find("option:not(:first)").remove(); // empity dropdown except first one
    //         getData.forEach(element => {
    //             batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
    //         });
    //         batchDropdown2.trigger("change"); // Refresh Select2 dropdown
    //         }
    // });

    if(id !== ''){
        // let category = $("#billingAdd-category" + randB).val();
        let batchDropdown2 = $("#billingAdd-batch" + randNum);
        batchDropdown2.find("option:not(:first)").remove();
        purchaseListData.forEach(element => {
            if(element.name_id == parseInt(id)){
                // element.billMedicine.forEach(element_medicine => {
                //     if(element_medicine.id == parseInt(id)){
                //         element_medicine.purchase.forEach(element_batch => {
                    // console.log(element);
                            batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
                        // });
                        // batchDropdown2.trigger("change"); 
                //     }
                // });
            }
        });
//     }
}
}
// function getBatchDetails(id,randB){
//     if(id !== ''){
//         let category = $("#billingAdd-category" + randB).val();
//         let batchDropdown2 = $("#billingAdd-batch" + randB);
//         batchDropdown2.find("option:not(:first)").remove();
//         medicineCategory.forEach(element => {
//             if(element.id == parseInt(category)){
//                 element.billMedicine.forEach(element_medicine => {
//                     if(element_medicine.id == parseInt(id)){
//                         element_medicine.purchase.forEach(element_batch => {
//                             batchDropdown2.append(`<option value="${element_batch.id}">${element_batch.batch_no}</option>`);
//                         });
//                         batchDropdown2.trigger("change"); 
//                     }
//                 });
//             }
//         });
//     }
//     // let medID = id;
//     // if(medID == null || medID == ''){
//     //     medID = 0;}
//     // $.ajax({
//     //         url:getBatchNumbers,
//     //         type:"GET",
//     //         headers:{
//     //             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//     //         },
//     //         data:{id:medID},
//     //         success:function(response){
//     //         let getData = response.data;
//     //         let batchDropdown2 = $("#billingAdd-batch" + randB); // Use the randNum to target the specific dropdown
//     //         batchDropdown2.find("option:not(:first)").remove(); // empity dropdown except first one
//     //         getData.forEach(element => {
//     //             batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
//     //         });
//     //         batchDropdown2.trigger("change"); // Refresh Select2 dropdown
//     //         }
//     // });
// }
function getBatchExpiry(id,randNum){
    if(id !== ''){
        // let category = $("#billingAdd-category" + randE).val();
        // let name = $("#billingAdd-name" + randE).val();
        // let batchDropdown2 = $("#billingAdd-batch" + randE).val();
        
        purchaseListData.forEach(element => {
            if(element.id == parseInt(id)){
                // element.billMedicine.forEach(element_medicine => {
                //     if(element_medicine.id == parseInt(name)){
                //         element_medicine.purchase.forEach(element_batch => {
                //             if(element_batch.id == parseInt(id)){
                                let avlQty = (parseFloat(element.qty) + parseFloat(element.return_qty)) - parseFloat(element.stock_out);
                                $("#billingAdd-expiry" + randNum).val(element.expiry); 
                                $("#billingAdd-avlQty" + randNum).val(avlQty); 
                                $("#billingAdd-salesPrice" + randNum).val(parseFloat(element.sales_price)); 
                                $("#billingAdd-tax" + randNum).val(parseFloat(element.tax));
                //             }
                //         });
                //     }
                // });
            }
        });
    }
}
// function getBatchExpiry(id,randE){
//     if(id !== ''){
//         let category = $("#billingAdd-category" + randE).val();
//         let name = $("#billingAdd-name" + randE).val();
//         let batchDropdown2 = $("#billingAdd-batch" + randE).val();
        
//         medicineCategory.forEach(element => {
//             if(element.id == parseInt(category)){
//                 element.billMedicine.forEach(element_medicine => {
//                     if(element_medicine.id == parseInt(name)){
//                         element_medicine.purchase.forEach(element_batch => {
//                             if(element_batch.id == parseInt(id)){
//                                 let avlQty = (parseFloat(element_batch.qty) + parseFloat(element_batch.return_qty)) - parseFloat(element_batch.stock_out);
//                                 $("#billingAdd-expiry" + randE).val(element_batch.expiry); 
//                                 $("#billingAdd-avlQty" + randE).val(avlQty); 
//                                 $("#billingAdd-salesPrice" + randE).val(parseFloat(element_batch.sales_price)); 
//                                 $("#billingAdd-tax" + randE).val(parseFloat(element_batch.tax));
//                             }
//                         });
//                     }
//                 });
//             }
//         });
//     }
//     // $.ajax({
//     //     url:getBatchExpiryDate,
//     //     type:"GET",
//     //     headers:{
//     //         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//     //     },
//     //     data:{id:id},
//     //     success:function(response){
//     //         if(response.data != ''){
//     //             let getData = response.data[0];
//     //             let avlQty = (parseFloat(getData.qty) + parseFloat(getData.return_qty)) - parseFloat(getData.stock_out);
//     //             $("#billingAdd-expiry" + randE).val(getData.expiry); 
//     //             $("#billingAdd-avlQty" + randE).val(avlQty); 
//     //             $("#billingAdd-salesPrice" + randE).val(getData.sales_price); 
//     //             $("#billingAdd-tax" + randE).val(getData.tax); 
//     //         }
//     //          getBillingAmount(randE);
//     //     }
//     // });
   
// }

function getBillingAmount(randA){
    let qty = parseFloat($("#billingAdd-qty" + randA).val());
    if(qty <= 0){
        qty = 0;
    }
    let avlQty =  parseFloat($("#billingAdd-avlQty" + randA).val()); 
    if(qty > avlQty){
        $("#billingAdd-qty"+randA).css({"border-color": "#ef4a00","border-width": "1px","border-style": "solid"});
        $('.billingAddSubmitBtn').prop('disabled',true);
         toastErrorAlert('Stock quantity exceeded limit.');
         return;
    }else{
        $("#billingAdd-qty"+randA).css("border-color","#d1d5db");
        $('.billingAddSubmitBtn').prop('disabled',false);
    }
    let salesPrice = parseFloat($("#billingAdd-salesPrice" + randA).val()) || 0;
    let tax = parseFloat($("#billingAdd-tax" + randA).val()) || 0;
    let amount = qty * salesPrice; // Calculate total amount before taxng tax
    $('#billingAdd-amount'+randA).val(amount) || 0;
    let currAmount = parseFloat($('#billingAdd-amount'+randA).val()) || 0;
    let currTaxAmount = (currAmount * tax)/100;
    $('#billingAdd-taxAmount'+randA).val(currTaxAmount) || 0;
    updateTotalBilling();
}

function updateTotalBilling() {
    // Calculate total amount and total tax amount
    let total_amount = $('input[name="billingAdd-amount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let total_tax_amount = $('input[name="billingAdd-taxAmount[]"]').map(function() { return parseFloat($(this).val()) || 0; }).get();
    let totalAmountSum = total_amount.reduce((acc, val) => acc + val, 0);
    let totalTaxAmountSum = total_tax_amount.reduce((acc, val) => acc + val, 0);
    // Update the UI with total amounts
    $('.billingAdd-totalAmount').html(totalAmountSum.toFixed(2)) ||0;
    $('.billingAdd-totalTax').html(totalTaxAmountSum.toFixed(2)) ||0;
    // Calculate net amount
    let totalNetAmount = totalAmountSum + totalTaxAmountSum;
    $('.billingAdd-totalNetAmount').html(Math.round(totalNetAmount) || 0);
    $('#billingAdd-payAmount').val(Math.round(totalNetAmount) || 0);
    // Calculate discount if applicable
    let discountPer = parseFloat($('#billingAdd-discountPer').val()) || 0;
    if (discountPer > 0) {
        let discountAmount = (totalAmountSum * discountPer) / 100;
        $('.billingAdd-discountAmount').html(discountAmount.toFixed(2) || 0);

        let tax_after_discount = (totalTaxAmountSum * discountPer) / 100;
        let total_tax_after_discount = totalTaxAmountSum - tax_after_discount;
        $('.billingAdd-totalTax').html(total_tax_after_discount.toFixed(2) || 0);

        let net_amount_after_discount = totalAmountSum - discountAmount + total_tax_after_discount;
        $('.billingAdd-totalNetAmount').html(Math.round(net_amount_after_discount) || 0);
        $('#billingAdd-payAmount').val(Math.round(net_amount_after_discount) || 0);
    } else {
        // If no discount, ensure discount amount is reset
        $('.billingAdd-discountAmount').html('0.00');
    }
}
function resetAddPatient(){
    $('#billingAdd-patientForm')[0].reset();
    $('.billingAdd-patientName_errorCls').addClass('d-none');
    $('.billingAdd-guardianName_errorCls').addClass('d-none');
    $('.billingAdd-patientBloodType_errorCls').addClass('d-none');
    $('.billingAdd-patientDOB_errorCls').addClass('d-none');
    $('.billingAdd-patientMStatus_errorCls').addClass('d-none');
    $('.billingAdd-patientMobile_errorCls').addClass('d-none');
    $('.billingAdd-patientAddess_errorCls').addClass('d-none');
}

$('#billingAdd-patientForm').on('submit',function(e){
     e.preventDefault();
    let patientName  = validateField('billingAdd-patientName', 'input'); 
    let patientMobile = validateField('billingAdd-patientMobile', 'mobile');
    let patientAddess = validateField('billingAdd-patientAddess', 'input');
        if(patientName === true && patientMobile === true && patientAddess === true){    
            let name = $('#billingAdd-patientName').val();
            let gender = $('input[name="billingAdd-patientGender"]:checked').val(); // Corrected na
            let mobile = $('#billingAdd-patientMobile').val();
            let address = $('#billingAdd-patientAddess').val();
            $.ajax({
                url: billingAddNewPatient,
                type:"POST",
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{
                name:name,gender:gender,mobile:mobile,address:address
                },
                success:function(response){
                    if(response.success){
                        toastSuccessAlert('New Patient added successfully');
                        $('#billingAdd-patientModal').modal('hide');
                        setTimeout(function(){
                            window.location.reload();
                        },1500);
                    }else{
                        console.log('error found');
                    }
                },
                error:function(xhr, status, error){
                    console.log(xhr.respnseText);
                    alert('An error occurred: '+error);
                }
            });
        }else{
            console.log("Please fill all required fields");
        }    
});


$('#billingAdd-Form').on('submit',function(e){
  e.preventDefault();
    let patientIDCheck  = validateField('billingAdd-patient', 'select');
    let payment_mode  = validateField('billingAdd-paymentMode', 'select');
    if(patientIDCheck == true && payment_mode == true){
 let category = $('input[name="billingAdd-category[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let name = $('input[name="billingAdd-name[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let batchNo = $('input[name="billingAdd-batch[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let expiry = $('input[name="billingAdd-expiry[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let qty = $('input[name="billingAdd-qty[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let salesPrice = $('input[name="billingAdd-salesPrice[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let taxPer = $('input[name="billingAdd-tax[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

let taxAmount = $('input[name="billingAdd-taxAmount[]"]').map(function() {
    return $(this).val() || 0;
}).get().filter(val => val !== null && val !== '');

let amount = $('input[name="billingAdd-amount[]"]').map(function() {
    return $(this).val();
}).get().filter(val => val !== null && val !== '');

        let billNo = $('.billingAdd-billNo').html();
        let patientID = $('#billingAdd-patient').val();
        let resDoctor = $('#billingAdd-resDoctor').val();
        let outDoctor = $('#billingAdd-outDoctor').val();
        let notes = $('#billingAdd-note').val();
        let totalAmount = parseFloat($('.billingAdd-totalAmount').html());
        let discountPer = $('#billingAdd-discountPer').val();
        let totalDiscountAmount = parseFloat($('.billingAdd-discountAmount').html());
        let totalTaxAmount = parseFloat($('.billingAdd-totalTax').html());
        let totalNetAmount = parseFloat($('.billingAdd-totalNetAmount').html());
        let paymentMode = $('#billingAdd-paymentMode').val();
        let txn = $('#billingAdd-txn').val();
        let payAmount = $('#billingAdd-payAmount').val();
        let dueAmount = totalNetAmount - payAmount;
        dueAmount = dueAmount.toFixed(2);
            $('.billingAddSubmitBtn').addClass('d-none');
        $('.billingAddSpinnBtn').removeClass('d-none');
        $.ajax({
            url:billingAddDatas,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{
                category:category,name:name,batchNo:batchNo,expiry:expiry,qty:qty,salesPrice:salesPrice,taxPer:taxPer,taxAmount:taxAmount,amount:amount,billNo:billNo,patientID:patientID,resDoctor:resDoctor,outDoctor:outDoctor,notes:notes,totalAmount:totalAmount,discountPer:discountPer,totalDiscountAmount:totalDiscountAmount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,txn:txn,payAmount:payAmount,dueAmount:dueAmount
            },
            success:function(response){
                if(response.success){
                    toastSuccessAlert('Billings done successfully');
                    setTimeout(function(){
                        window.location = '/billing';
                    },1500);
                }else{
                    toastErrorAlert('something error found');
                    $('.billingAddSubmitBtn').removeClass('d-none');
                    $('.billingAddSpinnBtn').addClass('d-none');
                }
            }
        });
    }else{
        console.log('Please fill all required fields');
    }
});
$('#billingAdd-paymentMode').on('change',function(e){
    e.preventDefault();
    let pmode = $('#billingAdd-paymentMode').val();
    if(pmode == 2){
        $('.pmode-billing').attr("colspan", "2");
        $('.pmodetxn_billing').addClass('d-none');
    }else{
        $('.pmode-billing').attr("colspan", "1");
        $('.pmodetxn_billing').removeClass('d-none');
    }
});
// console.log(purchaseListData);