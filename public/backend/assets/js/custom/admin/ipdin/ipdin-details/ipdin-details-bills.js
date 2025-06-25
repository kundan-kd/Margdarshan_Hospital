let table_bills = $('#ipdbill-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdBills,
        type:"POST",
        headers:{
           'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = $('#patient_Id').val();
        },
        error:function(xhr,thrown){
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
            data:'name',
            name:'name'
        },
        {
            data:'amount',
            name:'amount',
            searchable:false,
            orderable:false

        }
    ]
});