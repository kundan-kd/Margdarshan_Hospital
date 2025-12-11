let table = $('#icu-report-table').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:getIcuReport,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        },
        error:function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error:- '+thrown);
        }
    },
    columns:[
        {
            data:'ip_no',
            name:'ip_no'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'doa',
            name:'doa'
        },
        {
            data:'vital',
            name:'vital'
        },
        {
            data:'range',
            name:'range'
        },
        {
            data:'date',
            name:'date'
        },
        {
            data:'created_at',
            name:'created_at'
        }
    ]
});