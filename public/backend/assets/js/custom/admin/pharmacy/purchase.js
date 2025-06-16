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
            data:'bill_no',
            name:'bill_no'
        },
        {
            data:'net_amount',
            name:'net_amount'
        },
        {
            data:'discount',
            name:'discount'
        },
        {
            data:'total',
            name:'total'
        },
        {
            data:'paid_amount',
            name:'paid_amount'
        },
        {
            data:'due',
            name:'due'
        },
        {
            data:'naration',
            name:'naration'
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
     window.open('purchase-edit/' + id, '_blank');
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
      window.open('purchase-view/' + id, '_blank');
    // let url = pruchaseViewIndex.replace(':id', id); // Replace placeholder with actual ID
    // window.open(url, '_blank'); // Open page in new tab
}