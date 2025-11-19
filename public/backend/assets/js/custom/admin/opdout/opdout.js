
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
        { data: 'appointment_id', name: 'appointment_id' },
        { data: 'appointment_date', name: 'appointment_date' },
        { data: 'token', name: 'token' },
        { data: 'patient_name', name: 'patient_name' },
        { data: 'gender', name: 'gender' },
        { data: 'mobile', name: 'mobile' },
        { data: 'doctor', name: 'doctor' },
        { data: 'room_no', name: 'room_no' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action',orderable:false,searchable:false }
    ],
    order: [[0, 'desc']], // Sort by appointment_id (first column) in descending order
    dom: 'Blfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            title: 'OPD Patient List',
            exportOptions: {
                columns: [0,1,2,3,4,5,6]
            },
            className: 'd-none', // Hide the button using a Bootstrap utility class or custom CSS
            attr: {
                id: 'hiddenExcelBtn' // Give it an ID so we can trigger it
            }
        }
    ]
});
$('#excelBtn').on('click', function () {
    $('#hiddenExcelBtn').click(); // Trigger the hidden DataTables button
});

function getListFilter(){
    $('#opd-out-list-table').DataTable().ajax.reload();
}

function patientDetailsUsingToken(id, patient_id) {
    window.location.href = 'opd-out-details/' + id + '/' + patient_id;
}

function summaryReport(id){
    window.open('/summary-report/' + id);
}

