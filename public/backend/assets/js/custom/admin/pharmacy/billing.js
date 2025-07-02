let table = $('#billing-list-table').DataTable({
   processing: true,
    serverSide: true,

    ajax:{
        url:billingView,
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
            data:'patient',
            name:'patient'
        },
        {
            data:'created_at',
            name:'created_at'
        },
        {
            data:'bill_no',
            name:'bill_no'
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
            data:'net_amount',
            name:'net_amount'
        },
        {
            data:'paid_amount',
            name:'paid_amount'
        },
        {
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },
    ]
});

function billingEdit(id){
 window.open('billing-edit-page/' + id, '_blank');
}

function purchaseDetails(id) {
      window.open('billing-view/' + id, '_blank');
}
function printMedicineBill(id){
    window.open('medicine-bill-print/'+id,'_blank');
}