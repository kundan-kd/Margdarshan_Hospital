let table = $('#purchase-list-table').DataTable({
   processing: true,
    serverSide: true,

    ajax:{
        url:purchaseView,
        type:"GET",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr, error, thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        }
    },
    columns:[
        {
            data:'created_at',
            name:'created_at'
        },
        {
            data:'vendor',
            name:'vendor'
        },
        {
            data:'bill_no',
            name:'bill_no'
        },
        {
            data:'net_amount',
            name:'net_amount'
        },
        {
            data:'paid_amount',
            name:'paid_amount'
        },
        {
            data:'due_amount',
            name:'due_amount'
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },
    ]
});

function purchaseEdit(id){
     window.location.href = 'purchase-edit/' + id;
}

function purchaseDelete(id){
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
                url:deletePurchasedetails,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#purchase-list-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}
function purchaseDetails(id) {
      window.location.href = 'purchase-view/' + id;
}