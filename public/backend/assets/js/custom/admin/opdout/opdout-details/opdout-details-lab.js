// function addMoreTestName(){
//     let labTestNameAdd = '';
//     labTestNameAdd += `<tr class="add-lab-fieldGroup">
//                       <td>
//                         <select class="form-select form-select-sm select2  ">
//                           <option selected disabled>Select</option>
//                           <option value="1">RBC</option>
//                           <option value="2">Liver function test</option>
//                           <option value="3">TSH (Thyroid Stimulating Hormone)</option>
//                         </select>
//                       </td>
//                       <td>
//                         <input type="text" class="form-control form-control-sm" >
//                       </td>
//                       <td>
//                         <input type="text" class="form-control form-control-sm" >
//                       </td>
//                       <td>
//                         <button class="mx-1 w-32-px h-32-px fw-semibold bg-danger-focus text-danger-main rounded d-inline-flex align-items-center justify-content-center add-lab-remove">
//                             <i class="ri-close-line"></i>
//                         </button>
//                       </td>
//                     </tr>`;
//                     $('.appendMoreTestName').parent().append(labTestNameAdd);
// }
let table_lab = $('#opd-lab-reports-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdOutLabDetails,
        type:"POST",
        headers:{
           'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
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
            data:'test_type',
            name:'test_type'
        },
        {
            data:'test_name',
            name:'test_name'
        },
        {
            data:'report_date',
            name:'report_date'
        },
        {
            data:'action',
            name:'action',
            orderable:false,
            searchable:false
        },
    ]
});
$('#opdOutLab-form').on('submit',function(e){
    e.preventDefault();
    let testType_check = validateField('opdOutLab-testType', 'select');
    let testName_check = validateField('opdOutLab-testName', 'select');
    if(testType_check === true && testName_check === true){
        let patientId = $('#patient_Id').val();
       let testType = $('#opdOutLab-testType').val();
       let testName = $('#opdOutLab-testName').val();
       let method = $('#opdOutLab-method').val();
       let reportDays = $('#opdOutLab-reportDays').val();
       let testParameter = $('#opdOutLab-testParameter').val();
       let testRefRange = $('#opdOutLab-testRefRange').val();
       let testUnit = $('#opdOutLab-testUnit').val();
        $.ajax({
            url:opdOutLabSubmit,
            type:"POST",
            data:{
                patientId:patientId,testType:testType,testName:testName,method:method,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#opd-add-lab').modal('hide');
                    $('#opdOutLab-form')[0].reset();
                     $('#opd-lab-reports-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                }else if(response.error_validation){
                    console.log(response.error_validation);
                    toastWarningAlert(response.error_validation);
                }else{
                    toastErrorAlert('Something went wrong, please try again');
                }
            },
            error:function(xhr, status, error){
                console.log(xhr.respnseText);
                alert('An Error Occurred: '+error);
            }
            
        });
    }else{
        console.log("Please fill all required fields");
    }   
})
