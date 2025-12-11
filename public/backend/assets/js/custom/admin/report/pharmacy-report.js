let expiry_table = $('#expiry-report-table').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url: getExpiryData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        },
        error:function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert("Error: "+thrown);
        }
    },    
        columns:[
            {
                data:'group',
                name:'group'
            },
            {
                data:'name',
                name:'name'
            },
            {
                data:'batch',
                name:'batch'
            },
            {
                data:'qty',
                name:'qty'
            },
            {
                data:'expiry',
                name:'expiry'
            }
        ]
});

