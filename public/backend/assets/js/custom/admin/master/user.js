
// let table = new DataTable('#user-table');
let table = $('#user-table').DataTable({
    // "order": [[0, "desc"]], // Sort column in descending order
    processing: true,
    serverSide: true,
    ajax:{
        url:viewUsers,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
           },
        error: function(xhr,error,thrown){
            console.log(xhr.responseText);
            alert('Error: ' + thrown);
        }
    },
    columns:[
        {
            data:'staff_id',
            name:'staff_id'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'mobile',
            name:'mobile'
        },
        {
            data:'email',
            name:'email'
        },
        {
            data:'doj',
            name:'doj'
        },
        {
            data:'department',
            name:'department'
        },
        {
            data:'usertype',
            name:'usertype'
        },
        {
            data:'status',
            name:'status',
            orderable: false,
            searchable: false
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
});

$('.user-add').on('click',function(e){
    e.preventDefault();
    $('.user-title').html('Create user');
    $('#userID').val('');
    $('#addUser-form')[0].reset();
    $('.userAddUpdate').addClass('d-none');
    $('.userAddSubmit').removeClass('d-none');
    $('.opd-cls').addClass('d-none');
    });
function checkOPD(){
    let userType = $('#user-userType').val();
    if( userType == 2){
        $('.opd-cls').removeClass('d-none');
    }else{
        $('.opd-cls').addClass('d-none');
    }
    opdRoomData();
}
function opdRoomData(id){
    $.ajax({
        url: getOPDRoom,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            id:id
        },
        success:function(response){
            console.log(response);
            if(response.success){
                $('#user-opdRoom').empty();
                $('#user-opdRoom').append('<option value="">Select OPD Room</option>');
                if(response.roomData.length > 0){
                let usedRoom = response.roomData[0];
                $('#user-opdRoom').append('<option selected value="'+usedRoom.id+'">'+usedRoom.room_num+'</option>');
                }
                $.each(response.data,function(key,value){
                    $('#user-opdRoom').append('<option value="'+value.id+'">'+value.room_num+'</option>');
                });
            }
        }
    });
}
// ------user add starts----
$('#addUser-form').on('submit',function(e){
   e.preventDefault();
   let department_check = validateField('user-departmentId','select');
   let usertype_check = validateField('user-userType','select');
   let bloodtype_check = validateField('user-bloodType','select');
   let name_check = validateField('user-name','input');
   let fname_check = validateField('user-fname','input');
   let manme_check = validateField('user-mname','input');
   let dob_check = validateField('user-dob','select');
   let doj_check = validateField('user-doj','select');
   let pan_check = validateField('user-pan','input');
   let adhar_check = validateField('user-adhar','input');
   let email_check = validateField('user-email','email');
   let mobile_check = validateField('user-mobile','mobile');
   let pass_check = validateField('user-pass','input');
   let cpass_check = validateField('user-cpass','input');
   if(department_check == true && usertype_check == true && bloodtype_check == true && name_check == true && fname_check == true && manme_check == true && dob_check == true && doj_check == true && pan_check == true && adhar_check == true && email_check == true && mobile_check == true && pass_check == true && cpass_check == true){
    let id = $('#userID').val();
    let departmentID = $('#user-departmentId').val();
    let userType = $('#user-userType').val();
    let fee = $('#user-fee').val();
    let opdRoom = $('#user-opdRoom').val();
    let bloodType = $('#user-bloodType').val();
    let name = $('#user-name').val();
    let fname = $('#user-fname').val();
    let mname = $('#user-mname').val();
    let dob = $('#user-dob').val();
    let doj = $('#user-doj').val();
    let pan = $('#user-pan').val();
    let adhar = $('#user-adhar').val();
    let email = $('#user-email').val();
    let mobile = $('#user-mobile').val();
    let pass = $('#user-pass').val();
    let cpass = $('#user-cpass').val();
        if(cpass != pass){
            toastErrorAlert('Password not matched!');
            return;
        }else{
            if ($('.userAddUpdate').is(':visible')) {
                userUpdate(id); // Trigger update function
            } else {
                $.ajax({
                    url: addUser,
                    method:"POST",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{departmentID:departmentID,userType:userType,fee:fee,opdRoom:opdRoom,bloodType:bloodType,name:name,fname:fname,mname:mname,dob:dob,doj:doj,pan:pan,adhar:adhar,email:email,mobile:mobile,pass:pass},
                    success:function(response){
                        if(response.success){
                            $('#add-user').modal('hide');
                            $('#addUser-form')[0].reset();
                            $('#user-table').DataTable().ajax.reload();
                            toastSuccessAlert('user added successfully');
                        } else if(response.already_found) {
                            toastErrorAlert(response.already_found);    
                        }else{
                            toastErrorAlert('error found!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("An error occurred: " + error);
                    }
                });
            }
        }
    }else{
        console.log("Please fill all required fields");
    }    
   
});
// ------user add ends----
// ------user update starts ----
function userEdit(id){
    $.ajax({
        url: getUserData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
          //  console.log(response);
            if(response.success){
                getData = response.data[0];
                getRoom = response.roomData[0];
             //   console.log(getRoom);
                $('.user-title').html('Update User');
                $('#userId').val(getData.id);
                $('#user-departmentId').val(getData.department_id);
                $('#user-userType').val(getData.usertype_id);
                $('#user-bloodType').val(getData.bloodtype_id);
                $('#user-opdRoom').val(getData.room_number);
                $('#user-fee').val(getData.fee);
                $('#user-name').val(getData.name);
                $('#user-fname').val(getData.fname);
                $('#user-mname').val(getData.mname);
                $('#user-dob').val(getData.dob);
                $('#user-doj').val(getData.doj);
                $('#user-pan').val(getData.pan);
                $('#user-adhar').val(getData.adhar);
                $('#user-email').val(getData.email);
                $('#user-mobile').val(getData.mobile);
                $('#user-pass').val(getData.plain_password);
                $('#user-cpass').val(getData.plain_password);
                $('#add-user').modal('show');
                $('.userAddSubmit').addClass('d-none');  
                $('.userAddUpdate').removeClass('d-none');  
                if(getData.usertype_id == 2){
                    $('.opd-cls').removeClass('d-none');
                    // $('#user-opdRoom').append('<option value="">Newwwww</option>');
                    
                    // $('#user-opdRoom').append('<option value="'+getRoom.id+'">'+getRoom.room_num+'</option>');
                    // $('#user-opdRoom').trigger('change'); // Notify Select2 of the update
                }else{
                    $('.opd-cls').addClass('d-none');
                }
            }
        }

    });
}
function userAddUpdate() {
    let id = $('#userId').val();
    let departmentID = $('#user-departmentId').val();
    let userType = $('#user-userType').val();
    let bloodType = $('#user-bloodType').val();
    let fee = $('#user-fee').val();
    let opdRoom = $('#user-opdRoom').val();
    let name = $('#user-name').val();
    let fname = $('#user-fname').val();
    let mname = $('#user-mname').val();
    let dob = $('#user-dob').val();
    let doj = $('#user-doj').val();
    let pan = $('#user-pan').val();
    let adhar = $('#user-adhar').val();
    let email = $('#user-email').val();
    let mobile = $('#user-mobile').val();
    let pass = $('#user-pass').val();
    let cpass = $('#user-cpass').val();

    if(cpass != pass){
        toastErrorAlert('Password not matched!');
        return;
    }else{
        $.ajax({
            url: updateUser,
            method:"POST",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id,departmentID:departmentID,userType:userType,fee:fee,opdRoom:opdRoom,bloodType:bloodType,name:name,fname:fname,mname:mname,dob:dob,doj:doj,pan:pan,adhar:adhar,email:email,mobile:mobile,pass:pass},
            success:function(response){
                if(response.success){
                    $('#add-user').modal('hide');
                    $('#addUser-form')[0].reset();
                    $('#user-table').DataTable().ajax.reload();
                    toastSuccessAlert('User updated successfully');
                }else{
                    toastErrorAlert('error found!');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
}


// function statusSwitch(id){
//     $.ajax({
//         url: statusUpdate,
//         type: "POST",
//         data: {
//             id: id
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             if(response.success){
//                     $('#adduserModel').modal('hide');
//                     $('#adduserForm').removeClass('was-validated');
//                     $('#adduserForm')[0].reset();
//                     $('#user-table').DataTable().ajax.reload();
//                     toastSuccessAlert('Status updated successfully');
//                 }else{
//                     toastErrorAlert('error found!');
//                 }
//         },
//         error: function(xhr, status, error) {
//             console.error(xhr.responseText);
//             alert("An error occurred: " + error);
//         }
//     });
// }

function userDelete(id){
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
                url: deleteUserData,
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
                        $('#user-table').DataTable().ajax.reload();
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




