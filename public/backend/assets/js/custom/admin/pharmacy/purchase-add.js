
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
// setMedicineCategory();

// function setMedicineCategory(){
//     $.ajax({
//         url:getCategoryDatas,
//         type:"GET",
//         success:function(response){
//             medicineCategory = [];
//             $('#purchaseAdd_category0').empty();
//             let categoryDropdown ='';
//             categoryDropdown +=`<option value="">Select</option>`;
//             response.categoryList.forEach(element =>{
//                 medicineCategory.push(element);
//                 categoryDropdown += ` <option value="${element.id}">${element.name}</option>`;
//             });
//             $('#purchaseAdd_category0').append(categoryDropdown);
//         }
//     });
// }
// console.log(medicineCategory);
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
                        $('.medicine-name-list').append(`<li class="list-group-item">No Data Found!</li>`);
                    } else {
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
            <td>${name}
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
            <td>${purchaseRate}
                <input type="hidden" name="purchaseAdd_purchaseRate[]" value="${purchaseRate}">
            </td>
            <td>${amount}
                <input type="hidden" name="purchaseAdd_amount[]" value="${amount}">
            </td>
            <td>${tax}
                <input type="hidden" name="purchaseAdd_tax[]" value="${tax}">
            </td>
            <td>
                <input type="hidden" name="purchaseAdd_taxAmount[]" value="${taxAmount}">
            </td>
            <td>
            <button type="button" class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRowPurchase(this)">
                <i class="ri-close-line"></i>
            </button>
        </td>
        </tr>`;
        $('.newRowAppend').parent().append(itemDatas); // Append properly to tbody
        
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
}
function getAmount(randNum){
    let qty = parseFloat($('#purchaseAdd_qty' + randNum).val()) || 0; // Convert to number, default to 0 if invalid
    let purchaseRate = parseFloat($('#purchaseAdd_purchaseRate' + randNum).val()) || 0;
    let amount = qty * purchaseRate;
    $('#purchaseAdd_amount' + randNum).val(amount);
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
    $('.purchaseAdd_netTotalAmt').html(newAmountWithTax.toFixed(2));
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
                    toastSuccessAlert('New Purchase added successfully');
                    setTimeout(function(){
                        window.location = '/purchase';
                    },2500);
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
        // let medicineDropdown1 = $("#purchaseAdd_name" + randNum);
        // medicineDropdown1.find("option:not(:first)").remove();
        // medicineCategory.forEach(element => {
        //     if(element.id == parseInt(id)){
        //         element.medicine.forEach(element_medicine => {
        //             medicineDropdown1.append(`<option value="${element_medicine.id}">${element_medicine.name}</option>`);
        //         });
        //     }
        // });
        // medicineDropdown1.trigger("change");
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