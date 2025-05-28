// // OLd Method of appending can be conplex over time
// $(document).ready(function () {
//     var maxGroup = 100;
//     var rowCount = 0; // Track the row count

//     $('.select2').select2({ width: 'resolve' });

//     $(".addMore").click(function () {
//         if ($('.fieldGroup').length < maxGroup) {
//             rowCount++; // Increment row count

//             var $clone = $(".fieldGroupCopy").clone().removeClass("fieldGroupCopy").addClass("fieldGroup").show();

//             // Remove unnecessary Select2-generated DOM
//             $clone.find('.select2').removeClass("select2-hidden-accessible").next('.select2').remove();

//             // Assign unique IDs to each input/select
//             $clone.find("select, input").each(function () {
//                 var oldId = $(this).attr("id");
//                 if (oldId) {
//                     $(this).attr("id", oldId + "_" + rowCount); // Append rowCount to ID
//                 }
//             });

//             // Append the modified clone
//             $(".pharmacy-purchase-bill-table").append($clone);

//             // Re-initialize Select2 for newly created selects
//             $clone.find('.select2').select2({ width: 'resolve' });

//         } else {
//             alert('Maximum ' + maxGroup + ' groups are allowed.');
//         }
//     });

//     $("body").on("click", ".remove", function () {
//         $(this).closest(".fieldGroup").remove();
//     });
// });

 



function addNewRow() {
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
    $('.randNumNew').html(rand);
    $.ajax({
        url:getCategoryDatas,
        type:"GET",
        success:function(response){
            let getCategoryData = response.data;
          
    let newRowData = `<tr class="fieldGroupCopy">
        <td>
            <select id="purchaseAdd_category${rand}" name="purchaseAdd_category[]" class="form-select form-select-sm select2-cls" style="width: 100%;" onchange="getPurchaseMedicine(this.value,${rand})" required>
                <option value="" selected disabled>Select</option>`;
                  getCategoryData.forEach(element =>{
                     newRowData += ` <option value="${element.id}">${element.name}</option>`;
                     });
            newRowData += ` </select>
        </td>
        <td>
            <select id="purchaseAdd_name${rand}" name="purchaseAdd_name[]" class="form-select form-select-sm select2-cls" style="width: 100%;" required>
                <option value="" selected disabled>Select</option>
                <!-- Options will be populated dynamically based on category selection -->
            </select>
        </td>
        <td>
            <input id="purchaseAdd_batch${rand}" name="purchaseAdd_batch[]" class="form-control form-control-sm" type="text" placeholder="Batch No" required>
        </td>
        <td>
            <input id="purchaseAdd_expiry${rand}" name="purchaseAdd_expiry[]" class="form-control form-control-sm expiry-date" type="text" placeholder="Expiry Date" required>
        </td>
        <td>
            <input id="purchaseAdd_mrp${rand}" name="purchaseAdd_mrp[]" class="form-control form-control-sm" type="number" placeholder="MRP" required>
        </td>
        <td>
            <input id="purchaseAdd_salesPrice${rand}" name="purchaseAdd_salesPrice[]" class="form-control form-control-sm" type="number" placeholder="Sale Price" required>
        </td>
       
        <td>
            <input id="purchaseAdd_qty${rand}" name="purchaseAdd_qty[]" class="form-control form-control-sm" type="number" placeholder="Qty" oninput="getAmount(${rand})" required>
        </td>
        <td>
            <input id="purchaseAdd_purchaseRate${rand}" name="purchaseAdd_purchaseRate[]" class="form-control form-control-sm" type="number" placeholder="Purchase Rate" oninput="getAmount(${rand})" required>
        </td>
         <td>
            <input id="purchaseAdd_tax${rand}" name="purchaseAdd_tax[]" class="form-control form-control-sm" type="number" placeholder="Tax" oninput="getTax(${rand})" required>
        </td>
        <td>
            <input id="purchaseAdd_amount${rand}" name="purchaseAdd_amount[]" class="form-control form-control-sm" type="number" placeholder="Amount" readonly>
        </td>
        <td>
            <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center remove" onclick="removeRow(this)">
                <i class="ri-close-line"></i>
            </button>
        </td>
    </tr>`;
    $('.newRowAppend').parent().append(newRowData); // Append properly to tbody
   $('.select2-cls').select2();
        }
    });


    // Reinitialize Select2 for newly added row
    
}
 function removeRow(x){
    x.closest("tr").remove(); // remove entire row with tr selector
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
    let netamount = sumAmount - totalDiscount + totalTax;
    $('.purchaseAdd_netTotalAmt').html(netamount.toFixed(2));
    getTax(randNum);
}
//let totalAmountAfterDiscount = 0;

  
function getDiscount(disc) {
    let totalAmount = parseFloat($('.purchaseAdd_totalAmt').html()) || 0;
    let discountPer = parseFloat(disc) || 0;
    let totalDiscount = (totalAmount * discountPer) / 100;
    let totalTax = parseFloat($('.sumTaxAmountCls').html()) || 0;
    let totalTaxAfterDiscount = totalTax - ((totalTax * discountPer) / 100);
    let netAmountAfterDiscount = totalAmount - totalDiscount + totalTaxAfterDiscount;
    $('.purchaseAdd_discountAmt').html(totalDiscount.toFixed(2));
    $('.purchaseAdd_taxAmt').html(totalTaxAfterDiscount.toFixed(2));
    $('.purchaseAdd_netTotalAmt').html(netAmountAfterDiscount.toFixed(2));


}
let totalTaxAmount = [];
function getTax(randNum){
    let tax = parseFloat($('#purchaseAdd_tax' + randNum).val()) || 0;
    let amount = parseFloat($('#purchaseAdd_amount' + randNum).val()) || 0;
    let taxAmount = (amount * tax) / 100;
   // Attempt to find an existing tax entry for the given randNum
    const existingItem = totalTaxAmount.find(item => item.rand === randNum);
    if (existingItem) {
        // If an entry is found, update its tax amount
        existingItem.value = taxAmount;
    } else {
        // If no entry exists, add a new object with randNum and taxAmount
        totalTaxAmount.push({ rand: randNum, value: taxAmount });
    }
    // Calculate the total tax amount across all stored entries
    let sumTaxAmount = totalTaxAmount.reduce((acc, item) => acc + item.value, 0);
    $('.sumTaxAmountCls').html(sumTaxAmount); // appended to hdden class for further use
    $('.purchaseAdd_taxAmt').html(sumTaxAmount.toFixed(2));
    let totalAmount = parseFloat($('.purchaseAdd_totalAmt').html()) || 0;
    let discountPer = parseFloat($('#purchaseAdd_discount').val()) || 0;
    let totalDiscount = (totalAmount * discountPer) / 100;
    let netAmount = totalAmount - totalDiscount + sumTaxAmount;
    $('.purchaseAdd_discountAmt').html(totalDiscount.toFixed(2));
    $('.purchaseAdd_netTotalAmt').html(netAmount.toFixed(2));

}


$('#purchaseAdd_form').on('submit',function(e){
  e.preventDefault();
  let billNo = $('#purchaseAdd_billNo').val();
  let vendorID = $('#purchaseAdd_vendor').val();

  let category = $('select[name="purchaseAdd_category[]"]').map(function(){return $(this).val();}).get();
  let name = $('select[name="purchaseAdd_name[]"]').map(function(){return $(this).val();}).get();
  let batchNo = $('input[name="purchaseAdd_batch[]"]').map(function(){return $(this).val();}).get();
  let expiry = $('input[name="purchaseAdd_expiry[]"]').map(function(){return $(this).val();}).get();
  let mrp = $('input[name="purchaseAdd_mrp[]"]').map(function(){return $(this).val();}).get();
  let salesPrice = $('input[name="purchaseAdd_salesPrice[]"]').map(function(){return $(this).val();}).get();
  let tax = $('input[name="purchaseAdd_tax[]"]').map(function(){return $(this).val();}).get();
  let qty = $('input[name="purchaseAdd_qty[]"]').map(function(){return $(this).val();}).get();
  let purchaseRate = $('input[name="purchaseAdd_purchaseRate[]"]').map(function(){return $(this).val();}).get();
  let amount = $('input[name="purchaseAdd_amount[]"]').map(function(){return $(this).val();}).get();

  let naration = $('#purchaseAdd_naration').val();
  let totalAmount = parseFloat($('.purchaseAdd_totalAmt').html());
  let totalDiscountPer = parseFloat($('#purchaseAdd_discount').val());
  let totalDiscount = parseFloat($('.purchaseAdd_discountAmt').html()) || 0;
  let totalTaxAmount = parseFloat($('.purchaseAdd_taxAmt').html());
  let totalNetAmount = parseFloat($('.purchaseAdd_netTotalAmt').html());
  let paymentMode = $('#purchaseAdd_paymentMode').val();
  let payAmount = $('#purchaseAdd_payAmount').val();
  let dueAmount = totalNetAmount - payAmount;
  dueAmount = dueAmount.toFixed(2);
  $.ajax({
    url:purchaseAddDatas,
    type:"POST",
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    data:{
        billNo:billNo,vendorID:vendorID,category:category,name:name,batchNo:batchNo,expiry:expiry,mrp:mrp,salesPrice:salesPrice,tax:tax,qty:qty,purchaseRate:purchaseRate,amount:amount,naration:naration,totalAmount:totalAmount,totalDiscountPer:totalDiscountPer,totalDiscount:totalDiscount,totalTaxAmount:totalTaxAmount,totalNetAmount:totalNetAmount,paymentMode:paymentMode,payAmount:payAmount,dueAmount:dueAmount
    },
    success:function(response){
        console.log(response);
        if(response.success){
             toastSuccessAlert('New Purchase added successfully');
        }else{
             toastErrorAlert('something error found');
        }
    }
  });
});



  // Flat pickr or date picker js 
    function getDatePicker (receiveID) {
        flatpickr(receiveID, {
            dateFormat: "m/d/Y ",
        });
    }
    getDatePicker('.expiry-date'); 
    // let randNumNew = $('.randNumNew').html();
    // console.log(randNumNew);
    // getDatePicker('#purchaseAdd_expiry'+randNumNew); 
 
    function getPurchaseMedicine(id,randNum){
        console.log(id, randNum);
         $.ajax({
        url:getPurchaseNames,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
        let getData = response.data;
        let medicineDropdown1 = $("#purchaseAdd_name" + randNum); // Use the randNum to target the specific dropdown
        medicineDropdown1.find("option:not(:first)").remove(); // empity dropdown except first one
        getData.forEach(element => {
            medicineDropdown1.append(`<option value="${element.id}">${element.name}</option>`);
        });
        medicineDropdown1.trigger("change"); // Refresh Select2 dropdown
        }
    });
    }
