
  // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "m/Y",
            plugins: [
                        new monthSelectPlugin({
                            shorthand: true,  
                            dateFormat: "m/Y",  
                            altFormat: "F Y"    
                        })
                    ]
        });
    }
    getDatePicker('.expiry-date'); 

let medicineCategory = [];
function getMedicineNames(catId,search){
    if(search.length > 3){
        if(catId == '' || catId == null){
        toastErrorAlert('Kindly select a category to continue.');
        return;
        }
    $('.name-loader').removeClass('d-none');
    $('.medicine-name-list').empty();
        $.ajax({
            url:getMedicineData,
            type:"POST",
            data:{catId:catId,search:search},
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success:function(response){
                $('.name-loader').addClass('d-none');
                if(response.success){
                    const getData = response.data;
                    if (!getData || getData.length === 0) {
                        $('.add-new-medicine').removeClass('d-none');
                        $('.medicine-name-list').empty();
                        $('.medicine-name-list').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>No Data Found!</span>
                        </li>
                        `);

                    } else {
                        $('.add-new-medicine').addClass('d-none');
                        $('.medicine-name-list').empty();
                        getData.forEach(function(element){
                                $('.medicine-name-list').append(
                                `<li class="list-group-item" data-name-id="${element.id}" data-taxes="${element.taxes}" data-name="${element.name}">${element.name}</li>`
                            );
                        })
                    
                    }
                }
            }
        });
    }else{
        $('.add-new-medicine').addClass('d-none');
        $('.medicine-name-list').empty();
    }
}
$(document).on('click', '.medicine-name-list li', function() {
    let medicineId = $(this).data('name-id'); // Get the clicked patient's ID
    let name = $(this).data('name'); // Get the clicked patient's ID
    let tax = $(this).data('taxes'); // Get the clicked patient's ID
    if(medicineId != undefined){
    $('#purchaseAdd_name0').val(name);
    $('#purchaseAdd_nameId0').val(medicineId);
    $('#purchaseAdd_tax0').val(Math.round(tax));
    $('.medicine-name-list').empty();
    }
});
function addNewRow(){
    let category_id = $('#purchaseAdd_category0').val();
    if (category_id && category_id.length > 0) {
        let category_name = $('#purchaseAdd_category0 option:selected').text();
        let name_id = $('#purchaseAdd_nameId0').val();
        let name = $('#purchaseAdd_name0').val();
        let batch = $('#purchaseAdd_batch0').val();
        let expiry = $('#purchaseAdd_expiry0').val();
        let mrp = $('#purchaseAdd_mrp0').val();
        let salesPrice = $('#purchaseAdd_salesPrice0').val();
        let qty = $('#purchaseAdd_qty0').val();
        let purchaseRate = $('#purchaseAdd_purchaseRate0').val();
        let amount = $('#purchaseAdd_amount0').val();
        let tax = $('#purchaseAdd_tax0').val();
        let taxAmount = $('#purchaseAdd_taxAmount0').val();
        let itemDatas = '';
        itemDatas += `<tr>
            <td>${category_name}
                <input type="hidden" name="purchaseAdd_category[]" value="${category_id}">
            </td>
            <td style="width: 250px;">${name}
                <input type="hidden" name="purchaseAdd_name[]" value="${name_id}">
            </td>
            <td>${batch}
                <input type="hidden" name="purchaseAdd_batch[]" value="${batch}">
            </td>
            <td>${expiry}
                <input type="hidden" name="purchaseAdd_expiry[]" value="${expiry}">
            </td>
            <td>${mrp}
                <input type="hidden" name="purchaseAdd_mrp[]" value="${mrp}">
            </td>
            <td>${salesPrice}
                <input type="hidden" name="purchaseAdd_salesPrice[]" value="${salesPrice}">
            </td>
            <td>${qty}
                <input type="hidden" name="purchaseAdd_qty[]" value="${qty}">
            </td>
            <td>${amount}
                <input type="hidden" name="purchaseAdd_amount[]" value="${amount}">
            </td>
            <td style="width: 50px;">${tax}
                <input type="hidden" name="purchaseAdd_tax[]" value="${tax}">
            </td>
            <td>${purchaseRate}
                <input type="hidden" name="purchaseAdd_purchaseRate[]" value="${purchaseRate}">
            </td>
            <td style="display:none;">
                <input type="hidden" name="purchaseAdd_taxAmount[]" value="${taxAmount}">
            </td>
            <td>
            <button type="button" class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowPurchase(this)">
                <i class="ri-close-line"></i>
            </button>
        </td>
        </tr>`;
        $('.newRowAppend').parent().append(itemDatas); // Append properly to tbody
        $('.purchaseAddBtn').css('border','none');
        $('#purchaseAdd_category0').val('').change();
        $('#purchaseAdd_name0').val('').change();
        $('#purchaseAdd_batch0').val('');
        $('#purchaseAdd_expiry0').val('');
        $('#purchaseAdd_mrp0').val('');
        $('#purchaseAdd_salesPrice0').val('');
        $('#purchaseAdd_qty0').val('');
        $('#purchaseAdd_purchaseRate0').val('');
        $('#purchaseAdd_amount0').val('');
        $('#purchaseAdd_tax0').val('');
        $('#purchaseAdd_taxAmount0').val('');
        // manage tax amount when cancel added data
        let taxAmount2 = $('input[name="purchaseAdd_taxAmount[]"]').map(function(){return $(this).val();}).get();
        let totalTaxAmount11 = taxAmount2.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
        let totAmount = $('.purchaseAdd_totalAmt').html();
        let newAmountWithTax = parseFloat(totalTaxAmount11) + parseFloat(totAmount);
        $('.purchaseAdd_taxAmt').html(totalTaxAmount11.toFixed(2));
        $('.purchaseAdd_netTotalAmt').html(newAmountWithTax.toFixed(2));
    }else{
        toastErrorAlert('Please add items to proceed');
    }
   
 }

function removeRowPurchase(x){
    x.closest("tr").remove(); // remove entire row with tr selector
    let taxAmount2 = $('input[name="purchaseAdd_taxAmount[]"]').map(function(){return $(this).val();}).get();
    let totalTaxAmount11 = taxAmount2.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    let totAmount = $('.purchaseAdd_totalAmt').html();
    let newAmountWithTax = parseFloat(totalTaxAmount11) + parseFloat(totAmount);
    $('.purchaseAdd_taxAmt').html(totalTaxAmount11.toFixed(2));
    $('.purchaseAdd_netTotalAmt').html(newAmountWithTax.toFixed(2));
    let tax = $('#purchaseAdd_discount').val();

    let totalAmount = $('input[name="purchaseAdd_amount[]"]').map(function(){return $(this).val();}).get();
    let sumAmount = totalAmount.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    $('.purchaseAdd_totalAmt').html(sumAmount.toFixed(2));
    getDiscount(tax);
}
function getAmount(randNum){
    let qty = parseFloat($('#purchaseAdd_qty' + randNum).val()) || 0; // Convert to number, default to 0 if invalid
    let qtyAmount = parseFloat($('#purchaseAdd_amount' + randNum).val()) || 0;
    let tax = parseFloat($('#purchaseAdd_tax' + randNum).val()) || 0;
    let taxAmt = (qtyAmount * tax)/100;
    let purchaseRate = ((qtyAmount + taxAmt)/qty);
    $('#purchaseAdd_purchaseRate' + randNum).val(purchaseRate.toFixed(2));
    let totalAmount = $('input[name="purchaseAdd_amount[]"]').map(function(){return $(this).val();}).get();
    let sumAmount = totalAmount.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    let discountPer = parseFloat($('#purchaseAdd_discount').val()) || 0;
    let totalDiscount = (sumAmount * discountPer) / 100;
    let totalTax = parseFloat($('.purchaseAdd_taxAmt').html()) || 0;
    $('.purchaseAdd_totalAmt').html(sumAmount.toFixed(2));
    $('.purchaseAdd_discountAmt').html(totalDiscount.toFixed(2));
    $('.purchaseAdd_taxAmt').html(totalTax.toFixed(2));
    getTax(randNum);
}
function getDiscount(disc) {
    let totalAmount = parseFloat($('.purchaseAdd_totalAmt').html()) || 0;
    let discountPer = parseFloat(disc) || 0;
    let totalDiscount = (totalAmount * discountPer) / 100;
    let totalTax = parseFloat($('.purchaseAdd_taxAmt').html()) || 0;
    let totalTaxAfterDiscount = totalTax - ((totalTax * discountPer) / 100);
    let netAmountAfterDiscount = (totalAmount - totalDiscount) + totalTaxAfterDiscount;
    $('.purchaseAdd_discountAmt').html(totalDiscount.toFixed(2));
    $('.purchaseAdd_taxAmt').html(totalTaxAfterDiscount.toFixed(2));
    $('.purchaseAdd_netTotalAmt').html(Math.round(netAmountAfterDiscount));


}
let totalTaxAmount = [];
function getTax(randNum){
    let tax = parseFloat($('#purchaseAdd_tax' + randNum).val()) || 0;
    let amount = parseFloat($('#purchaseAdd_amount' + randNum).val()) || 0;
    let taxAmount = (amount * tax) / 100;
    $('#purchaseAdd_taxAmount' + randNum).val(taxAmount);
    let taxAmount2 = $('input[name="purchaseAdd_taxAmount[]"]').map(function(){return $(this).val();}).get();
    let totalTaxAmount11 = taxAmount2.map(Number).reduce((acc, val) => acc + val, 0); // convert string into number then array sum
    let totAmount = $('.purchaseAdd_totalAmt').html();
    let newAmountWithTax = parseFloat(totalTaxAmount11) + parseFloat(totAmount);
    $('.purchaseAdd_taxAmt').html(totalTaxAmount11.toFixed(2));
    $('.purchaseAdd_netTotalAmt').html(Math.round(newAmountWithTax));
}
function checkPayAmountPurchaseAdd(netAmount,amount){
    if(parseFloat(netAmount) < parseFloat(amount)){
        $('.purchaseAdd_payAmount_cls').html('Pay amount exceeds net amount.').css('color','red');
            $('.purchaseAddSubmitBtn').prop('disabled',true);
            return;
        }else{
            $('.purchaseAdd_payAmount_cls').html('');
            $('.purchaseAddSubmitBtn').prop('disabled',false);
    }
}

$('#purchaseAdd_form').on('submit',function(e){
    e.preventDefault();
    let billNo_check = validateField('purchaseAdd_billNo', 'input');
    let vendorID_check = validateField('purchaseAdd_vendor', 'select');
    let purchase_date_check = validateField('purchaseAdd_Date', 'select');
    if(billNo_check == true && vendorID_check == true && purchase_date_check == true){
        $('.purchaseAddSubmitBtn').addClass('d-none');
        $('.purchaseAddSpinnBtn').removeClass('d-none');
        let billNo = $('#purchaseAdd_billNo').val();
        let vendorID = $('#purchaseAdd_vendor').val();
        let purchase_date = $('#purchaseAdd_Date').val();
        let category = $('input[name="purchaseAdd_category[]"]').map(function(){return $(this).val();}).get();
        let name = $('input[name="purchaseAdd_name[]"]').map(function(){return $(this).val();}).get();
        let batchNo = $('input[name="purchaseAdd_batch[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let expiry = $('input[name="purchaseAdd_expiry[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let mrp = $('input[name="purchaseAdd_mrp[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let salesPrice = $('input[name="purchaseAdd_salesPrice[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let tax = $('input[name="purchaseAdd_tax[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let qty = $('input[name="purchaseAdd_qty[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let purchaseRate = $('input[name="purchaseAdd_purchaseRate[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        let amount = $('input[name="purchaseAdd_amount[]"]').map(function(){return $(this).val();}).get().filter(val => val !== null && val !== '');
        if(category == '' || name == '' || batchNo =='' || qty =='' || amount ==''){
            toastErrorAlert('Kindly add items before submit');
            $('.purchaseAddSpinnBtn').addClass('d-none');
            $('.purchaseAddSubmitBtn').removeClass('d-none');
            $('.purchaseAddBtn').css('border','1px solid red');
            return;
        }
        let naration = $('#purchaseAdd_naration').val();
        let totalAmount = parseFloat($('.purchaseAdd_totalAmt').html());
        let totalDiscountPer = parseFloat($('#purchaseAdd_discount').val()) || 0;
        let totalDiscount = parseFloat($('.purchaseAdd_discountAmt').html()) || 0;
        let totalTaxAmount = parseFloat($('.purchaseAdd_taxAmt').html());
        let totalNetAmount = parseFloat($('.purchaseAdd_netTotalAmt').html());
        let paymentMode = $('#purchaseAdd_paymentMode').val();
        let txn = $('#purchaseAdd-txn').val();
        let payAmount = $('#purchaseAdd_payAmount').val() || 0;
        let dueAmount = totalNetAmount - payAmount;
        dueAmount = dueAmount.toFixed(2);
        $.ajax({
            url:purchaseAddDatas,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{
                billNo:billNo,vendorID:vendorID,purchase_date:purchase_date,category:category,name:name,batchNo:batchNo,expiry:expiry,mrp:mrp,salesPrice:salesPrice,tax:tax,qty:qty,purchaseRate:purchaseRate,amount:amount,naration:naration,totalAmount:totalAmount,totalDiscountPer:totalDiscountPer,totalDiscount:totalDiscount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,txn:txn,payAmount:payAmount,dueAmount:dueAmount
            },
            success:function(response){
                if(response.success){
                    $('.purchaseAddBtn').css('border','none');
                    toastSuccessAlert('New Purchase added successfully');
                    setTimeout(function(){
                        window.location = '/purchase';
                    },2500);
                }else if(response.error_validation){
                    toastErrorAlert('Please fill all required fields');
                }else{
                    toastErrorAlert('something error found');
                    $('.purchaseAddSpinnBtn').addClass('d-none');
                    $('.purchaseAddSubmitBtn').removeClass('d-none');
                }
            }
        });
    }else{
        console.log("Please fill all required fields");
    }   
});
    function getPurchaseMedicine(id,randNum){
        $('.add-new-medicine').addClass('d-none');
        $('.medicine-name-list').empty();
        $('#purchaseAdd_name0').val('');
        $('#newMedicineAdd').on('hidden.bs.modal', function () {
            $('#purchaseAdd_name0').focus();
        });
    }
    function getTaxValue(id,randNum){
        let category = $("#purchaseAdd_category" + randNum).val();
        medicineCategory.forEach(element => {
            if(element.id == parseInt(category)){
                element.medicine.forEach(element_medicine => {
                    if(element_medicine.id == parseInt(id)){
                        $('#purchaseAdd_tax'+randNum).val(Math.round(element_medicine.taxes));
                    }
                });
            }
        });
    }
$('#purchaseAdd_paymentMode').on('change',function(e){
    e.preventDefault();
    let pmode = $('#purchaseAdd_paymentMode').val();
    if(pmode == 'Cash'){
        $('.pmode').attr("colspan", "2");
        $('.pmodeTxn').addClass('d-none');
    }else{
        $('.pmode').attr("colspan", "1");
        $('.pmodeTxn').removeClass('d-none');
    }
});
function resetNewMedicineAdd(){
    $('#newMed_category').val('').trigger('change'); //reset select2 dropdown
    $('#newMed_company').val('').trigger('change'); //reset select2 dropdown
    $('#newMed_unit').val('').trigger('change'); //reset select2 dropdown
    $('.newMed_name_errorCls').addClass('d-none');
    $('.newMed_category_errorCls').addClass('d-none');
    $('.newMed_company_errorCls').addClass('d-none');
    $('.newMed_group_errorCls').addClass('d-none');
    $('.newMed_unit_errorCls').addClass('d-none');
    $('.newMed_reOrderingLevel_errorCls').addClass('d-none');
    $('.newMed_rack_errorCls').addClass('d-none');
    $('.newMed_composition_errorCls').addClass('d-none');
    $('.newMed_hsn_errorCls').addClass('d-none');
    $('.newMed_taxes_errorCls').addClass('d-none');
    $('.newMed_boxPacking_errorCls').addClass('d-none');
}
$('#newMedicineAdd_form').on('submit',function(e){
    e.preventDefault();
    let createMed_name = validateField('newMed_name', 'Medicine select');
    let createMed_category = validateField('newMed_category', 'select');
    let createMed_company = validateField('newMed_company', 'select');
    let createMed_unit = validateField('newMed_unit', 'select');
    let createMed_reOrderingLevel = validateField('newMed_reOrderingLevel', 'select');
    let createMed_hsn = validateField('newMed_hsn', 'input');
    let createMed_taxes = validateField('newMed_taxes', 'select');
    let createMed_boxPacking = validateField('newMed_boxPacking', 'select');
    if(createMed_name === true && createMed_category === true && createMed_company === true && createMed_unit === true && createMed_reOrderingLevel === true && createMed_hsn === true && createMed_taxes === true && createMed_boxPacking === true){

    let category = $('#newMed_category').val();
    let company = $('#newMed_company').val();
    let unit = $('#newMed_unit').val();
    let re_order_level = $('#newMed_reOrderingLevel').val();
    let rack = $('#newMed_rack').val();
    let name = $('#newMed_name').val();
    let composition_array = $('select[name="newMed_composition[]"]').map(function(){return $(this).val();}).get();
    let hsn = $('#newMed_hsn').val();
    let taxes = $('#newMed_taxes').val();
    let box_pack = $('#newMed_boxPacking').val();
    let narration = $('#newMed_narration').val();
    $.ajax({
        url:newMedicineAdd,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            category:category,company:company,unit:unit,re_order_level:re_order_level,rack:rack,name:name,composition:composition_array,hsn:hsn,taxes:taxes,box_pack:box_pack,narration:narration
        },
        success:function(response){
            let getData = response.data;
            if(response.success){
                toastSuccessAlert(response.success);
                $('.medicine-name-list').empty();
                $('#purchaseAdd_category0').val(response.data.category_id).change();
                // Directly fill the medicine name and ID into the input fields
                $('#purchaseAdd_name0').val(getData.medicine_name);
                $('#purchaseAdd_nameId0').val(getData.medicine_id);
                $('.add-new-medicine').addClass('d-none');
                $('#purchaseAdd_tax0').val(getData.taxes);
                // Hide the modal and reset the form
                $('#newMedicineAdd').modal('hide');
                $('#newMedicineAdd_form')[0].reset();
                // Reset Select2 dropdowns inside the modal
                $('#newMed_category').val('').trigger('change');
                $('#newMed_company').val('').trigger('change');
                $('#newMed_unit').val('').trigger('change');
                $('#newMed_composition').val('').trigger('change');

                $('#newMedicineAdd').on('hidden.bs.modal', function () {
                    $('#purchaseAdd_batch0').focus();
                });
            }else if(response.error_validation){
                toastWarningAlert(response.error_validation);
            }else{
                toastErrorAlert('something error found');
            }
        },
        error:function(xhr, error, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    });
}else{
    console.log('Please fill all mandatory fields');
}
});

