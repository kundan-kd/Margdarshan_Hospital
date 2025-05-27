function addNewRowBilling() {
    let rand = Math.floor(Math.random() * 100000); // Generate a unique random number
    let newRowDataBilling = `<tr class="fieldGroup">
                              <td>
                                  <select id="billingAdd-category${rand}" name="billingAdd-category[]" class="form-select form-select-sm select2-cls w-100">
                                      <option selected disabled>Select</option>
                                      <option value="1">cat A</option>
                                      <option value="2">Cat B</option>
                                      <option value="3">Cat C</option>
                                  </select>
                              </td>
                              <td>
                                  <select id="billingAdd-name${rand}" name="billingAdd-name[]" class="form-select form-select-sm select2-cls w-100">
                                      <option selected disabled>Select</option>
                                      <option value="1">Azethromicne</option>
                                      <option value="2">Paracitamol</option>
                                      <option value="3">Lisinopril</option>
                                      <option value="4">Amlodipine</option>
                                  </select>
                              </td>
                              <td>
                                  <select id="billingAdd-batch${rand}" name="billingAdd-batch[]" class="form-select form-select-sm select2-cls w-100">
                                      <option selected>Select</option>
                                      <option value="1">Batch A</option>
                                      <option value="2">Batch B</option>
                                      <option value="3">Batch C</option>
                                  </select>
                              </td>
                              <td>
                                  <div class=" position-relative">
                                      <input id="billingAdd-expiry${rand}" name="billingAdd-expiry[]" class="form-control radius-8 bg-base expiry-date"  type="text" placeholder="12/2024">
                                      <span class="position-absolute end-0 top-50 translate-middle-y me-12 line-height-1"><iconify-icon icon="solar:calendar-linear" class="icon text-lg"></iconify-icon></span>
                                  </div>
                              </td>
                              <td>
                                  <input id="billingAdd-qty${rand}" name="billingAdd-qty[]" name="billingAdd-name" class="form-control form-control-sm" type="number" placeholder="Quantity">
                              </td>
                              <td>
                                  <input id="billingAdd-avlQty${rand}" name="billingAdd-avlQty[]" type="number" class="form-control form-control-sm" placeholder="Avilable Qty">
                              </td>
                              <td>
                                  <input id="billingAdd-salesPrice${rand}" name="billingAdd-salesPrice[]" type="number" class="form-control form-control-sm" placeholder="Sales Price">
                              </td>
                              <td>
                                  <input id="billingAdd-tax${rand}" name="billingAdd-tax[]" class="form-control form-control-sm" type="number" placeholder="Tax">
                              </td>
                              <td>
                                  <input id="billingAdd-amount${rand}" name="billingAdd-amount[]" type="number" class="form-control form-control-sm" placeholder="Amount">
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
 function removeRowBilling(x){
    x.closest("tr").remove(); // remove entire row with tr selector
}

function getMedicine(id){
    $.ajax({
        url:getMedicineNames,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
        let getData = response.data;
        let medicineDropdown = $("#billingAdd-name0"); 
        medicineDropdown.find("option:not(:first)").remove(); // empity dropdown except first one
        getData.forEach(element => {
            medicineDropdown.append(`<option value="${element.id}">${element.name}</option>`);
        });
        medicineDropdown.trigger("change"); // Refresh Select2 dropdown
        }
    });
}