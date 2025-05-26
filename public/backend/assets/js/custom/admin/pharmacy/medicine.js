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
})

$('#createMed_form').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    let form = $(this);
    if (!form[0].checkValidity()) {
        form.addClass('was-validated'); // Apply Bootstrap validation styles to restrict page ajax request till all mandatory fields are filled
        return;
    }
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
        url:medicineAdd,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            category:category,company:company,group:group,unit:unit,re_order_level:re_order_level,rack:rack,name:name,composition:composition,taxes:taxes,box_pack:box_pack,narration:narration
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
});
function medicineEdit(id){
    $.ajax({
        url:getMedicineData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
           getData = response.data[0];
           if(response.success){
            $('#medician-list-add').modal('show');
            $('#medician-list-addLabel').html('Update Medicine');
            $('.medicineAddBtn').addClass('d-none');
            $('.medicineUpdateBtn').removeClass('d-none');
            $('#createMed_id').val(getData.id);
            $('#createMed_category').val(getData.category);
            $('#createMed_company').val(getData.company);
            $('#createMed_group').val(getData.group);
            $('#createMed_unit').val(getData.unit);
            $('#createMed_reOrderingLevel').val(getData.re_ordering_level);
            $('#createMed_rack').val(getData.rack);
            $('#createMed_name').val(getData.name);
            $('#createMed_composition').val(getData.composition);
            $('#createMed_taxes').val(getData.taxes);
            $('#createMed_boxPacking').val(getData.box_packing);
            $('#createMed_narration').val(getData.narration);
           }
        }
    });
}
function medicineUpdate(id){
    let form = $('#createMed_form');
    if(!form[0].checkValidity()){
        form.addClass('was-validated');
        return;
    } //if all required fields are filled then ajax call other wise return.
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