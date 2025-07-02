  // Flat pickr or date picker js 
    // function getDatePicker (receiveID) {
    //     flatpickr(receiveID, {
    //         dateFormat: "m/Y",
    //         plugins: [
    //                     new monthSelectPlugin({
    //                         shorthand: true,  
    //                         dateFormat: "m/Y",  
    //                         altFormat: "F Y"    
    //                     })
    //                 ]
    //     });
    // }
    // getDatePicker('.expiry-date'); 

function addNewRowBilling() {
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
      $.ajax({
        url:getBillingCategoryDatas,
        type:"GET",
        success:function(response){
            let getCategoryData = response.data;
    let newRowDataBilling = `<tr class="fieldGroup">
                              <td>
                                  <select id="billingAdd-category${rand}" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100" onchange="getBillingMedicine(this.value,${rand})">
                                        <option value="" selected disabled>Select</option>`;
                    getCategoryData.forEach(element =>{
                        newRowDataBilling += ` <option value="${element.id}">${element.name}</option>`;
                        });
                    newRowDataBilling += ` </select>
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
                                      <input id="billingAdd-expiry${rand}" name="billingAdd-expiry[]" class="form-control radius-8 bg-base expiry-date${rand}"  type="text" placeholder="MM/YYYY" readonly>
                                      
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

    $('.newRowAppendBilling').parent().append(newRowDataBilling); // Append properly to tbody
    getDatePicker('.expiry-date'+ rand); 
    // Reinitialize Select2 for newly added row
    $('.select2-cls').select2();
       }
    });
}
 function removeRowBilling(x){
    x.closest("tr").remove(); // remove entire row with tr selector
     getBillingAmount();

}

function getBillingMedicine(id,randNum){
 $.ajax({
        url:getBillingMedicineNames,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
        let getData = response.data;
        let medicineDropdown1 = $("#billingAdd-name" + randNum); // Use the randNum to target the specific dropdown
        medicineDropdown1.find("option:not(:first)").remove(); // empity dropdown except first one
        getData.forEach(element => {
            medicineDropdown1.append(`<option value="${element.id}">${element.name}</option>`);
        });
        medicineDropdown1.trigger("change"); // Refresh Select2 dropdown
        }
    });
}

function getBatchDetails(id,randB){
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
            let batchDropdown2 = $("#billingAdd-batch" + randB); // Use the randNum to target the specific dropdown
            batchDropdown2.find("option:not(:first)").remove(); // empity dropdown except first one
            getData.forEach(element => {
                batchDropdown2.append(`<option value="${element.id}">${element.batch_no}</option>`);
            });
            batchDropdown2.trigger("change"); // Refresh Select2 dropdown
            }
    });
}

function getBatchExpiry(id,randE){
    $.ajax({
        url:getBatchExpiryDate,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.data != ''){
                let getData = response.data[0];
                let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
                $("#billingAdd-expiry" + randE).val(getData.expiry); 
                $("#billingAdd-avlQty" + randE).val(avlQty); 
                $("#billingAdd-salesPrice" + randE).val(getData.sales_price); 
                $("#billingAdd-tax" + randE).val(getData.tax); 
            }
             getBillingAmount(randE);
        }
    });
   
}
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
    let salesPrice = $("#billingAdd-salesPrice" + randA).val() ||0;
    let tax = $("#billingAdd-tax" + randA).val() ||0;
    let amount = qty * salesPrice; // Calculate total amount before taxng tax
    $('#billingAdd-amount'+randA).val(amount) ||0;
    let currAmount = $('#billingAdd-amount'+randA).val() ||0;
    let currTaxAmount = (currAmount * tax)/100;
    $('#billingAdd-taxAmount'+randA).val(currTaxAmount) ||0;
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
    // Calculate discount if applicable
    let discountPer = parseFloat($('#billingAdd-discountPer').val()) || 0;
    if (discountPer > 0) {
        let discountAmount = (totalAmountSum * discountPer) / 100;
        $('.billingAdd-discountAmount').html(discountAmount.toFixed(2));

        let tax_after_discount = (totalTaxAmountSum * discountPer) / 100;
        let total_tax_after_discount = totalTaxAmountSum - tax_after_discount;
        $('.billingAdd-totalTax').html(total_tax_after_discount.toFixed(2));

        let net_amount_after_discount = totalAmountSum - discountAmount + total_tax_after_discount;
        $('.billingAdd-totalNetAmount').html(Math.round(net_amount_after_discount) || 0);
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
            // let guardian_name = $('#billingAdd-guardianName').val();
            let gender = $('input[name="billingAdd-patientGender"]:checked').val(); // Corrected na
            // let bloodtype = $('#billingAdd-patientBloodType').val();
            // let dob = $('#billingAdd-patientDOB').val();
            // let mstatus = $('#billingAdd-patientMStatus').val();
            let mobile = $('#billingAdd-patientMobile').val();
            let address = $('#billingAdd-patientAddess').val();
            // let alt_mobile = $('#billingAdd-patientAltMobile').val();
            // let allergy = $('#billingAdd-patientAllergy').val();
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
  if(patientIDCheck == false){
    return;
  }
  $('.billingAddSubmitBtn').addClass('d-none');
  $('.billingAddSpinnBtn').removeClass('d-none');
  let category = $('select[name="billingAdd-category[]"]').map(function(){return $(this).val();}).get();
  let name = $('select[name="billingAdd-name[]"]').map(function(){return $(this).val();}).get();
  let batchNo = $('select[name="billingAdd-batch[]"]').map(function(){return $(this).val();}).get();
  let expiry = $('input[name="billingAdd-expiry[]"]').map(function(){return $(this).val();}).get();
  let qty = $('input[name="billingAdd-qty[]"]').map(function(){return $(this).val();}).get();
  let salesPrice = $('input[name="billingAdd-salesPrice[]"]').map(function(){return $(this).val();}).get();
  let taxPer = $('input[name="billingAdd-tax[]"]').map(function(){return $(this).val();}).get();
  let taxAmount = $('input[name="billingAdd-taxAmount[]"]').map(function(){return $(this).val();}).get();
  let amount = $('input[name="billingAdd-amount[]"]').map(function(){return $(this).val();}).get();

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
  let payAmount = $('#billingAdd-payAmount').val();
  let dueAmount = totalNetAmount - payAmount;
  dueAmount = dueAmount.toFixed(2);
  $.ajax({
    url:billingAddDatas,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{
        category:category,name:name,batchNo:batchNo,expiry:expiry,qty:qty,salesPrice:salesPrice,taxPer:taxPer,taxAmount:taxAmount,amount:amount,billNo:billNo,patientID:patientID,resDoctor:resDoctor,outDoctor:outDoctor,notes:notes,totalAmount:totalAmount,discountPer:discountPer,totalDiscountAmount:totalDiscountAmount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,payAmount:payAmount,dueAmount:dueAmount
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
});
