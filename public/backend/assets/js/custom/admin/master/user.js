
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
            data:'name',
            name:'name',
            orderable: true,
            searchable: true
        },
        {
            data:'mobile',
            name:'mobile',
            orderable: true,
            searchable: true
        },
        {
            data:'email',
            name:'email',
            orderable: true,
            searchable: true
        },
        {
            data:'doj',
            name:'doj',
            orderable: true,
            searchable: true
        },
        {
            data:'department',
            name:'department',
            orderable: true,
            searchable: true
        },
        {
            data:'usertype',
            name:'usertype',
            orderable: true,
            searchable: true
        },
        {
            data:'status',
            name:'status',
            orderable: false,
            searchable: true
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
    $('#userNumber').val('');
    $('#userGroup').val('');
    $('#userType').val('');
    $('#userFloor').val('');
    $('.adduserUpdate').addClass('d-none');
    $('.adduserSubmit').removeClass('d-none');
    });
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
                        data:{departmentID:departmentID,userType:userType,bloodType:bloodType,name:name,fname:fname,manme:mname,dob:dob,doj:doj,pan:pan,adhar:adhar,email:email,mobile:mobile,pass},
                        success:function(response){
                            if(response.success){
                                $('#add-user').modal('hide');
                                $('#addUser-form')[0].reset();
                                $('#user-table').DataTable().ajax.reload();
                                toastSuccessAlert('user added successfully');
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
// function userEdit(id){
// $.ajax({
//     url: getuserData,
//     type:"POST",
//     headers:{
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     },
//     data:{id:id},
//     success:function(response){
//         if(response.success){
//             getData = response.data[0];
//             $('.user-title').html('Update user');
//             $('#userID').val(getData.id);
//             $('#userNumber').val(getData.user_no);
//             $('#userGroup').val(getData.user_group_id);
//             $('#userType').val(getData.user_type_id);
//             $('#userFloor').val(getData.floor);
//             $('.adduserSubmit').addClass('d-none');
//             $('.adduserUpdate').removeClass('d-none');
//             $('#adduserModel').modal('show');
//         }
//     }

// });
// }

// function userUpdate(id){
//     let userNumber = $('#userNumber').val();
//     let userGroup = $('#userGroup').val();
//     let userType = $('#userType').val();
//     let userFloor = $('#userFloor').val();
//     if(userNumber == '' || userGroup == '' || userType == '' || userFloor == ''){
//         $('.needs-validation').addClass('was-validated'); //added bootstrap class for form validation
//     }else{
//         $.ajax({
//             url: updateuserData,
//             type: "POST",
//             data: {
//                 id:id,userNumber:userNumber,userGroup:userGroup,userType:userType,userFloor:userFloor},
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             success: function(response) {
//                 if(response.success){
//                     $('#adduserModel').modal('hide');
//                     $('#adduserForm').removeClass('was-validated');
//                     $('#adduserForm')[0].reset();
//                     $('#user-table').DataTable().ajax.reload();
//                     toastSuccessAlert('user updated successfully');
//                 }else{
//                     toastErrorAlert('error found!');
//                 }
//             },
//             error: function(xhr, status, error) {
//                 console.error(xhr.responseText);
//                 alert("An error occurred: " + error);
//             }
//         });
//     }
// }

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

// function userDelete(id){
//     Swal.fire({
//         title: "Are you sure?",
//         text: "You won't be able to revert this!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Yes, delete it!",
//         customClass: {
//             title: 'swal-title-custom'
//           }
        
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: deleteuserData,
//                 type: "POST",
//                 data: {
//                     id: id
//                 },
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 },
//                 success: function(response) {
//                     if (response.success) {
//                         Swal.fire("Deleted!", response.success, "success");
//                         $('#user-table').DataTable().ajax.reload();
//                     } else {
//                         Swal.fire("Error!", "Error", "error");
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     console.error(xhr.responseText);
//                     Swal.fire("Error!", "An error occurred: " + error, "error");
//                 }
//             });
//         }
//     });
// }




