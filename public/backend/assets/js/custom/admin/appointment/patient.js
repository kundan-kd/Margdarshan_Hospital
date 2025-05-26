let table = $('#patient-table').DataTable({
    // responsive:true,
    processing: true,
    serverSide:true,
    ajax:{
        url: viewPatients,
        type:"POST",
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
            data:'patient_id',
            name:'patient_id'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'guardian_name',
            name:'guardian_name',
            orderable: false,
            searchable: true
        },
        {
            data:'gender',
            name:'gender'
        },
        {
            data:'bloodtype',
            name:'bloodtype',
            orderable: false,
            searchable: true
        },
        {
            data:'dob',
            name:'dob',
            orderable: false,
            searchable: true
        },
        {
            data:'mstatus',
            name:'mstatus'
        },
        {
            data:'mobile',
            name:'mobile',
            orderable: false,
            searchable: true
        },
        {
            data:'alt_mobile',
            name:'alt_mobile',
            orderable: false,   
            searchable: true
        },
        {
            data:'address',
            name:'address',
            orderable: false,   
            searchable: true
        },
        {
            data:'allergies',
            name:'allergies',
            orderable: false,
            searchable: true
        },
        // {
        //     data:'status',
        //     name:'status',
        //     orderable: true,
        //     searchable: true
        // },
        // {
        //     data:'action',
        //     name:'action',
        //     orderable: false,
        //     searchable: true
        // },

    ]
});

function  patientDelete(id){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            title: 'swal-title-custom'
            }
        
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deletePatientData,
                type: "POST",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#patient-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire("Error!", "An error occurred: " + error, "error");
                }
            });
        }
    });
}