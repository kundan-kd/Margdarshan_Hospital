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
// let patient_id = $('#patient_Id').val();
function resetLabTest(){
    $('#opdOutLabID').val('');
    $('#opdOutLab-testName').val('');
    $('#opdOutLab-testType').val('');
    $('#opdOutLab-method').val('');
    $('#opdOutLab-reportDays').val('');
    $('#opdOutLab-testParameter').val('');
    $('#opdOutLab-testRefRange').val('');
    $('#opdOutLab-testUnit').val('');
    $('.opdOutLabSubmit').removeClass('d-none');
    $('.opdOutLabUpdate').addClass('d-none');
}
let table_lab = $('#opd-lab-reports-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewOpdOutLabDetails,
        type:"POST",
        headers:{
           'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data: function(d){
            d.patient_id = patient_id;
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
function getTestName(testtype_id, testname_id) {
    $.ajax({
        url: getTestNameByTypeOpd,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: testtype_id },
        success: function(response) {
            if (response.success) {
                let testNameData = response.data;
                let $testNameSelect = $('#opdOutLab-testName');
                $testNameSelect.empty();
                $testNameSelect.append('<option value="">Select Test Name</option>');
                $.each(testNameData, function(index, item) {
                    let isSelected = item.id == testname_id ? 'selected' : '';
                    $testNameSelect.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
                });
            } else {
                console.log('No test names found for this type');
            }
        },
        error: function(xhr, thrown) {
            console.log(xhr.responseText);
            alert('Error: ' + thrown);
        }
    });
}

function getTestDetails(id){
        $.ajax({
            url:getTestDetailsByIdOpd,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id},
            success:function(response){
                if(response.success){
                    let testData = response.data[0];
                    $('#opdOutLab-shortName').val(testData.s_name);
                    $('#opdOutLab-amount').val(testData.amount);
                }else{
                    console.log('No test details found for this test');
                }
            },
            error:function(xhr,thrown){
                console.log(xhr.responseText);
                alert('Error: '+thrown);
            }
        });
}
$('#opdOutLab-form').on('submit',function(e){
    e.preventDefault();
    let testType_check = validateField('opdOutLab-testType', 'select');
    let testName_check = validateField('opdOutLab-testName', 'select');
    if(testType_check === true && testName_check === true){
        let patientId = $('#patient_Id').val();
       let testType = $('#opdOutLab-testType').val();
       let testName = $('#opdOutLab-testName').val();
       let method = $('#opdOutLab-method').val();
       let amount = $('#opdOutLab-amount').val();
       let reportDays = $('#opdOutLab-reportDays').val();
       let testParameter = $('#opdOutLab-testParameter').val();
       let testRefRange = $('#opdOutLab-testRefRange').val();
       let testUnit = $('#opdOutLab-testUnit').val();
        $.ajax({
            url:opdOutLabSubmit,
            type:"POST",
            data:{
                patientId:patientId,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
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
function opdOutLabView(id){
    $.ajax({
        url:getOpdOutLabData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
                let getLabData = response.data.labData[0];
                let getpatientData = response.data.patientData[0];
                let getTestType = response.data.testType[0];
                let getTestName = response.data.testName[0];
                let visit_lab_data = '';
                        visit_lab_data += ` <div class="row">
          <div class="col-md-12">
            <table class="table  table-borderless table-sm payment-pharmacy-table">
                <tbody>
                  <tr>
                      <th class="fw-medium">Bill No</th>
                        <td>PATH0${getLabData.id}</td>
                      <th class="fw-medium">Patient</th>
                      <td>${getpatientData.name}</td>
                  </tr>
                  <tr>
                    <th class="fw-medium">Test Type</th>
                      <td>${getTestType.name}</td>
                      <th class="fw-medium">Test Name</th>
                      <td>${getTestName.name}</td>
                  </tr>
                  <tr>     
                      <th class="fw-medium">Short Name</th>
                      <td>${getTestName.s_name}</td>
                      <th class="fw-medium">Amount</th>
                      <td>${getTestName.amount}</td>
                  </tr>
                  <tr>           
                    <th class="fw-medium">Sample Collection</th>
                      <td>${getLabData.created_at}</td>
                      <th class="fw-medium">Report Date</th>
                      <td>${getLabData.report_days}</td>
                  </tr>
                  <tr>    
                      <th class="fw-medium">Test Method</th>
                      <td>${getLabData.method}</td>    
                  </tr>
                </tbody>
            </table>
          </div>
        </div>
        <table class="table  table-borderless table-sm payment-pharmacy-table">
               <thead>
                 <tr>
                   <th>#</th>
                   <th class="fw-medium">Test Parameter Name</th>
                   <th class="fw-medium text-nowrap">Report Value</th>
                   <th class="fw-medium">Report Reference</th>
                 </tr>
               </thead>
               <tbody>
                <tr>
                  <td></td>
                  <td>${getLabData.test_parameter}</td>
                  <td>${getLabData.test_ref_range}</td>
                  <td>${getLabData.test_unit}</td>
                </tr>
               </tbody>
        </table>`;   
                        $('.opdOutLabDataAppend').html(visit_lab_data);
            }else{
                alert('error');
            }
        },
        error:function(xhr,thrown){
            console.log(xhr.respnseText);
            alert('Error: '+thrown );
        }
    });
}
function opdOutLabEdit(id){
$.ajax({
        url: getOpdOutLabDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.opdOutLabSubmit').addClass('d-none');
                $('.opdOutLabUpdate').removeClass('d-none');
                $('#opd-add-lab').modal('show');
                $('#opOutLabID').val(id);
                $('#opdOutLab-testType').val(getData.test_type_id).change();
                $('#opdOutLab-testName').val(getData.test_name_id).change();
                $('#opdOutLab-method').val(getData.method).change();
                $('#opdOutLab-reportDays').val(getData.report_days);
                $('#opdOutLab-testParameter').val(getData.test_parameter);
                $('#opdOutLab-testRefRange').val(getData.test_ref_range);
                $('#opdOutLab-testUnit').val(getData.test_unit);
            }
        }
    });
}

function opdOutLabUpdate(id){
    let testType_check = validateField('opdOutLab-testType', 'select');
    let testName_check = validateField('opdOutLab-testName', 'select');
    if(testType_check === true && testName_check === true){
       let testType = $('#opdOutLab-testType').val();
       let testName = $('#opdOutLab-testName').val();
       let method = $('#opdOutLab-method').val();
       let amount = $('#opdOutLab-amount').val();
       let reportDays = $('#opdOutLab-reportDays').val();
       let testParameter = $('#opdOutLab-testParameter').val();
       let testRefRange = $('#opdOutLab-testRefRange').val();
       let testUnit = $('#opdOutLab-testUnit').val();
        $.ajax({
            url:opdOutLabUpdateData,
            type:"POST",
            data:{
                id:id,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
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
}
function opdOutLabDelete(id){
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
                url:opdOutLabDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#opd-lab-reports-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}