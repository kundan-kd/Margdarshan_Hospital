let table = $('#opd-report-table').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdReport,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: '+thrown);
        },
        data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        }
    },    
    columns:
    [
        {data:'opd_id',name:'opd_id'},
        {data:'name',name:'name'},
        {data:'doa',name:'doa'},
        {data:'dov',name:'dov'},
        {data:'consultant',name:'consultant'},
        {data:'fee',name:'fee'},
        {data:'p_status',name:'p_status'},
        {data:'a_status',name:'a_status'},
        {data:'action',name:'action',searchable:false,orderable:false},
    ]
});
function opdReportFilter(){
   table.ajax.reload();
}

function printAppointmentBill(id){
     window.open('/report/appointment-billing-print/'+id,'_blank');
}