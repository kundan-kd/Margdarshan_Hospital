let table = $('#medicine-create-table').DataTable({
     processing:true,
     serverSide:true,
     ajax:{
        url:medicineView,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,thrown){
           console.log(xhr.responseText);
           alert('Error: '+thrown);
        }
     },
     columns:[
        {
            data:'name',
            name:'name'
        },
        {
            data:'category',
            name:'category'
        },
        {
            data:'company',
            name:'company'
        },
        {
            data:'composition',
            name:'composition'
        },
        {
            data:'group',
            name:'group'
        },
        {
            data:'unit',
            name:'unit'
        },
        {
            data:'re_ordering_level',
            name:'re_ordering_level'
        },
        {
            data:'taxes',
            name:'taxes'
        },
        {
            data:'box_packing',
            name:'box_packing'
        },
        {
            data:'stock',
            name:'stock'
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        }
     ]
});

$('.createNewBtn').on('click',function(e){
    e.preventDefault();
    $('#createMed_form')[0].reset();
    $('#createMed_id').val('');
    $('#medician-list-addLabel').html('Add Medicine');
    $('.medicineUpdateBtn').addClass('d-none');
    $('.medicineAddBtn').removeClass('d-none');
});

function resetMedicineAdd(){
    $('#createMed_category').val('').trigger('change'); //reset select2 dropdown
    $('#createMed_company').val('').trigger('change'); //reset select2 dropdown
    $('#createMed_group').val('').trigger('change'); //reset select2 dropdown
    $('#createMed_unit').val('').trigger('change'); //reset select2 dropdown
    $('.createMed_name_errorCls').addClass('d-none');
    $('.createMed_category_errorCls').addClass('d-none');
    $('.createMed_company_errorCls').addClass('d-none');
    $('.createMed_group_errorCls').addClass('d-none');
    $('.createMed_unit_errorCls').addClass('d-none');
    $('.createMed_reOrderingLevel_errorCls').addClass('d-none');
    $('.createMed_rack_errorCls').addClass('d-none');
    $('.createMed_composition_errorCls').addClass('d-none');
    $('.createMed_taxes_errorCls').addClass('d-none');
    $('.createMed_boxPacking_errorCls').addClass('d-none');
}

$('#createMed_form').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    let createMed_name = validateField('createMed_name', 'Medicine select');
    let createMed_category = validateField('createMed_category', 'select');
    let createMed_company = validateField('createMed_company', 'select');
    let createMed_group = validateField('createMed_group', 'select');
    let createMed_unit = validateField('createMed_unit', 'select');
    let createMed_reOrderingLevel = validateField('createMed_reOrderingLevel', 'select');
    let createMed_taxes = validateField('createMed_taxes', 'select');
    let createMed_boxPacking = validateField('createMed_boxPacking', 'select');
    if(createMed_name === true && createMed_category === true && createMed_company === true && createMed_group === true && createMed_unit === true && createMed_reOrderingLevel === true && createMed_taxes === true && createMed_boxPacking === true){

    let category = $('#createMed_category').val();
    let company = $('#createMed_company').val();
    let group = $('#createMed_group').val();
    let unit = $('#createMed_unit').val();
    let re_order_level = $('#createMed_reOrderingLevel').val();
    let rack = $('#createMed_rack').val();
    let name = $('#createMed_name').val();
    //  let composition = $('#createMed_composition').val();
    //  let composition = '';
     let composition_array = $('select[name="createMed_composition[]"]').map(function(){return $(this).val();}).get();
    
    // console.log(composition_array);
    let taxes = $('#createMed_taxes').val();
    let box_pack = $('#createMed_boxPacking').val();
    let narration = $('#createMed_narration').val();
    $.ajax({
        url:medicineAdd,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            category:category,company:company,group:group,unit:unit,re_order_level:re_order_level,rack:rack,name:name,composition:composition_array,taxes:taxes,box_pack:box_pack,narration:narration
        },
        success:function(response){
        //   console.log(response);
          if(response.success){
             toastSuccessAlert('Medicine added successfully');
             $('#medician-list-add').modal('hide');
             $('#createMed_form')[0].reset();
             $('#createMed_form').removeClass('was-validated');
             $('#medicine-create-table').DataTable().ajax.reload();
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
function medicineEdit(id){
    resetMedicineAdd();
    $.ajax({
        url:getMedicineData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            let getData = response.data[0];
            console.log(getData);
           if(response.success){
            const arrayValue = getData.composition.split(','); // [1, 2, 3]
            $('#medician-list-add').modal('show');
            $('#medician-list-addLabel').html('Update Medicine');
            $('.medicineAddBtn').addClass('d-none');
            $('.medicineUpdateBtn').removeClass('d-none');
            $('#createMed_id').val(getData.id);
            $('#createMed_category').val(getData.category_id).trigger('change'); //set select2 dropdown value
            $('#createMed_company').val(getData.company_id).trigger('change'); //set select2 dropdown value
            $('#createMed_group').val(getData.group_id).trigger('change'); //set select2 dropdown value
            $('#createMed_unit').val(getData.unit_id).trigger('change'); //set select2 dropdown value
            $('#createMed_reOrderingLevel').val(getData.re_ordering_level);
            $('#createMed_rack').val(getData.rack);
            $('#createMed_name').val(getData.name);
            $('#createMed_composition').val(arrayValue).trigger('change'); ;
            $('#createMed_taxes').val(getData.taxes);
            $('#createMed_boxPacking').val(getData.box_packing);
            $('#createMed_narration').val(getData.narration);
           }
        }
    });
}
function medicineUpdate(id){
let createMed_name = validateField('createMed_name', 'Medicine select');
    let createMed_category = validateField('createMed_category', 'select');
    let createMed_company = validateField('createMed_company', 'select');
    let createMed_group = validateField('createMed_group', 'select');
    let createMed_unit = validateField('createMed_unit', 'select');
    let createMed_reOrderingLevel = validateField('createMed_reOrderingLevel', 'select');
    let createMed_taxes = validateField('createMed_taxes', 'select');
    let createMed_boxPacking = validateField('createMed_boxPacking', 'select');
    if(createMed_name === true && createMed_category === true && createMed_company === true && createMed_group === true && createMed_unit === true && createMed_reOrderingLevel === true && createMed_taxes === true && createMed_boxPacking === true){

        let category = $('#createMed_category').val();
        let company = $('#createMed_company').val();
        let group = $('#createMed_group').val();
        let unit = $('#createMed_unit').val();
        let re_order_level = $('#createMed_reOrderingLevel').val();
        let rack = $('#createMed_rack').val();
        let name = $('#createMed_name').val();
        let composition = $('#createMed_composition').val();
        let taxes = $('#createMed_taxes').val();
        let box_pack = $('#createMed_boxPacking').val();
        let narration = $('#createMed_narration').val();
        $.ajax({
            url:updateMedicineData,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                id:id,category:category,company:company,group:group,unit:unit,re_order_level:re_order_level,rack:rack,name:name,composition:composition,taxes:taxes,box_pack:box_pack,narration:narration
            },
            success:function(response){
                if(response.success){
                    $('#medician-list-add').modal('hide');
                    $('#createMed_form').removeClass('was-validated');
                    $('#createMed_form')[0].reset();
                    $('#medicine-create-table').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else{
                    toastErrorAlert(response.error_success);
                }
            }
        });
    }else{
    console.log('Please fill all mandatory fields');
    }    
}

function medicineDelete(id){
     Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            title: 'swal-title-custom'
          }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:deleteMedicineData,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#medicine-create-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}
function medicineDetails(id){
    window.open('medicine-view/'+id, '_blank');
}

