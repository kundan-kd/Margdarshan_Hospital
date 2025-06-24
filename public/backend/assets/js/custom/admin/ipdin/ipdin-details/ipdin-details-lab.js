
function resetLabTest(){
    $('#ipdLabID').val('');
    $('#ipdLab-testName').val('');
    $('#ipdLab-testType').val('');
    $('#ipdLab-method').val('');
    $('#ipdLab-reportDays').val('');
    $('#ipdLab-testParameter').val('');
    $('#ipdLab-testRefRange').val('');
    $('#ipdLab-testUnit').val('');
    $('.ipdLabSubmit').removeClass('d-none');
    $('.ipdLabUpdate').addClass('d-none');
}
let table_lab = $('#ipd-lab-reports-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewIpdLabDetails,
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
        url: getTestNameByType,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: testtype_id },
        success: function(response) {
            if (response.success) {
                let testNameData = response.data;
                let $testNameSelect = $('#ipdLab-testName');
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
            url:getTestDetailsById,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id},
            success:function(response){
                if(response.success){
                    let testData = response.data[0];
                    $('#ipdLab-shortName').val(testData.s_name);
                    $('#ipdLab-amount').val(testData.amount);
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
$('#ipdLab-form').on('submit',function(e){
    e.preventDefault();
    let testType_check = validateField('ipdLab-testType', 'select');
    let testName_check = validateField('ipdLab-testName', 'select');
    if(testType_check === true && testName_check === true){
        let patientId = $('#patient_Id').val();
       let testType = $('#ipdLab-testType').val();
       let testName = $('#ipdLab-testName').val();
       let method = $('#ipdLab-method').val();
       let amount = $('#ipdLab-amount').val();
       let reportDays = $('#ipdLab-reportDays').val();
       let testParameter = $('#ipdLab-testParameter').val();
       let testRefRange = $('#ipdLab-testRefRange').val();
       let testUnit = $('#ipdLab-testUnit').val();
        $.ajax({
            url:ipdLabSubmit,
            type:"POST",
            data:{
                patientId:patientId,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-lab').modal('hide');
                    $('#ipdLab-form')[0].reset();
                     $('#ipd-lab-reports-list').DataTable().ajax.reload();
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
function ipdLabView(id){
    $.ajax({
        url:getIpdLabData,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
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
                        $('.ipdLabDataAppend').html(visit_lab_data);
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
function ipdLabEdit(id){
$.ajax({
        url: getIpdLabDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            if(response.success){
               let getData = response.data[0];
                $('.ipdLabSubmit').addClass('d-none');
                $('.ipdLabUpdate').removeClass('d-none');
                $('#ipd-add-lab').modal('show');
                $('#ipdLabID').val(id);
                $('#ipdLab-testType').val(getData.test_type_id).change();
                $('#ipdLab-testName').val(getData.test_name_id).change();
                $('#ipdLab-method').val(getData.method).change();
                $('#ipdLab-reportDays').val(getData.report_days);
                $('#ipdLab-testParameter').val(getData.test_parameter);
                $('#ipdLab-testRefRange').val(getData.test_ref_range);
                $('#ipdLab-testUnit').val(getData.test_unit);
            }
        }
    });
}

function ipdLabUpdate(id){
    let testType_check = validateField('ipdLab-testType', 'select');
    let testName_check = validateField('ipdLab-testName', 'select');
    if(testType_check === true && testName_check === true){
       let testType = $('#ipdLab-testType').val();
       let testName = $('#ipdLab-testName').val();
       let method = $('#ipdLab-method').val();
       let amount = $('#ipdLab-amount').val();
       let reportDays = $('#ipdLab-reportDays').val();
       let testParameter = $('#ipdLab-testParameter').val();
       let testRefRange = $('#ipdLab-testRefRange').val();
       let testUnit = $('#ipdLab-testUnit').val();
        $.ajax({
            url:ipdLabUpdateData,
            type:"POST",
            data:{
                id:id,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#ipd-add-lab').modal('hide');
                    $('#ipdLab-form')[0].reset();
                     $('#ipd-lab-reports-list').DataTable().ajax.reload();
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
function ipdLabDelete(id){
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
                url:ipdLabDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#ipd-lab-reports-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}