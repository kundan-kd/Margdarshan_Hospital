let content = '';
    content =`<div class="card basic-data-table">
                <div class="card-body">
                    <table class="table bordered-table mb-0" id="opd-out-table" data-page-length='10'>
                        <thead>
                            <tr >
                                <th scope="col" class="fw-medium align-items-left">SL.No.</th>
                                <th scope="col" class="fw-medium align-items-left">Token no.</th>
                                <th scope="col" class="fw-medium align-items-left">Doctor</th>
                                <th scope="col" class="fw-medium align-items-left">Room</th>
                                <th scope="col" class="fw-medium align-items-left">Appointment Date</th>
                                <th scope="col" class="fw-medium align-items-left">Phone</th>
                                <th scope="col" class="fw-medium align-items-left">Gender</th>
                                <th scope="col" class="fw-medium align-items-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>`;
    $('.opd-out-details').html(content);

    $(document).ready(function() {
    let table = $('#opd-out-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: viewOpdOut, // Ensure this variable contains the correct route
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(d) {
                d.doctor_id = $('#opdoutDoctorId').val();
                d.roomNum = $('#opdoutRoomNum').val();
            },
            error: function(xhr, error, thrown) {
                console.log(xhr.responseText);
                alert('Error: ' + thrown);
            }
        },
        columns: [
            { data: 'token', name: 'token' },
            { data: 'doctor', name: 'doctor'  },
            { data: 'room_number', name: 'room_number'},
            { data: 'appointment_date', name: 'appointment_date'},
            { data: 'mobile', name: 'mobile'},
            { data: 'gender', name: 'gender'},
            { data: 'status', name: 'status'}
        ]
        ,
        // dom: 'Bfrtip',
        // buttons: [
        //     {
        //         extend: 'excelHtml5',
        //         text: 'Export to Excel',
        //         exportOptions: {
        //             columns: ':visible' // Ensures only visible columns are exported
        //         }
        //     }
        // ]
    });
});

 function getListFilter(){
    $('#opd-out-table').DataTable().ajax.reload();
 }

 