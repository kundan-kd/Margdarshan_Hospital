
let table = $('#opd-out-list-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url:viewOpdOut,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
           },
        error: function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: ' + thrown);
        }
    },
    columns: [
        { data: 'patient_name', name: 'patient_name' },
        { data: 'token', name: 'token' },
        { data: 'department_id', name: 'department_id' }
    ]
});