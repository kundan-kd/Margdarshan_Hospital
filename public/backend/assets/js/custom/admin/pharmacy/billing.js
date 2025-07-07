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
            data:'created_at',
            name:'created_at'
        },
        {
            data:'patient_id',
            name:'patient_id'
        },
        {
            data:'patient',
            name:'patient'
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
            data:'action',
            name:'action',
            orderable: false,
            searchable: false
        },
    ]
});

function billingEdit(id){
    window.location.href = 'billing-edit-page/' + id;
}

function purchaseDetails(id) {
      window.location.href = 'billing-view/' + id;

}
function printMedicineBill(id){
    window.open('medicine-bill-print/'+id,'_blank');
}