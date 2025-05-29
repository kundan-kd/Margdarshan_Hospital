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

    // Reinitialize Select2 for newly added row
    $('.select2-cls').select2();
       }
    });
}
 function removeRowBilling(x){
    x.closest("tr").remove(); // remove entire row with tr selector
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
            // console.log(response);
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
                console.log(response);
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
            console.log(response);
        let getData = response.data[0];
        let avlQty = getData.qty - getData.stock_out; // Calculate available quantity
        $("#billingAdd-expiry" + randE).val(getData.expiry); 
        $("#billingAdd-avlQty" + randE).val(avlQty); 
        $("#billingAdd-salesPrice" + randE).val(getData.sales_price); 
        $("#billingAdd-tax" + randE).val(getData.tax); 
        $("#billingAdd-amount" + randE).val(getData.amount); 
        }
    });
}
function getBillingAmount(randA){
    let qty = $("#billingAdd-qty" + randA).val();
    let salesPrice = $("#billingAdd-salesPrice" + randA).val();
    let tax = $("#billingAdd-tax" + randA).val();
    let totalAmount = ((qty * salesPrice).toFixed(2)); // Calculate total amount before tax
    let totalTax = (((totalAmount * tax) / 100).toFixed(2)); // Calculate tax amount
    let totalNetAmount = parseFloat(totalAmount) + parseFloat(totalTax); // Calculate total amount including tax
    $('.billingAdd-totalAmount').html(totalAmount); // Update the total amount in the UI
    $('.billingAdd-totalTax').html(totalTax); // Update the total amount in the UI
    $('.billingAdd-totalNetAmount').html(totalNetAmount); // Update the total amount in the UI
}