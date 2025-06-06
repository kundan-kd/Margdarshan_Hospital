
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
        { data: 'token', name: 'token' },
        { data: 'doctor', name: 'doctor' },
        { data: 'appointment_date', name: 'appointment_date' },
        { data: 'mobile', name: 'mobile' },
        { data: 'gender', name: 'gender' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action' }
    ]
});

function getListFilter(){
    $('#opd-out-list-table').ajax.reload();
}

function patientDetailsUsingToken(id){
     window.open('opd-out-details/' + id, '_blank');
}

