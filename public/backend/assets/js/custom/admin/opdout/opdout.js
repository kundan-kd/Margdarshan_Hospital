
let table_opd_patients = $('#opd-out-list-table').DataTable({
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
        },
        data: function (d) {
            d.doctor_id = $('#opdoutDoctorId').val();  
            d.roomNum = $('#opdoutRoomNum').val();  
        }
    },
    columns: [
        { data: 'token', name: 'token' },
        { data: 'patient_name', name: 'patient_name' },
        { data: 'doctor', name: 'doctor' },
        { data: 'appointment_date', name: 'appointment_date' },
        { data: 'mobile', name: 'mobile' },
        { data: 'gender', name: 'gender' },
        { data: 'status', name: 'status' }
        // { data: 'action', name: 'action' }
    ]
});

function getListFilter(){
    $('#opd-out-list-table').DataTable().ajax.reload();
    console.log('hello');
}

function patientDetailsUsingToken(id){
     window.open('opd-out-details/' + id, '_blank');
}

