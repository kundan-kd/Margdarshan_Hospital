
function resetLabTest(){
    $('#emergencyLabID').val('');
    $('#emergencyLab-testName').val('');
    $('#emergencyLab-testType').val('');
    $('#emergencyLab-method').val('');
    $('#emergencyLab-reportDays').val('');
    $('#emergencyLab-testParameter').val('');
    $('#emergencyLab-testRefRange').val('');
    $('#emergencyLab-testUnit').val('');
    $('.emergencyLabSubmit').removeClass('d-none');
    $('.emergencyLabUpdate').addClass('d-none');
}
let table_lab = $('#emergancy-lab-reports-list').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:viewEmergencyLabData,
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
$('#emergencyLab-form').on('submit',function(e){
    e.preventDefault();
    let testType_check = validateField('emergencyLab-testType', 'select');
    let testName_check = validateField('emergencyLab-testName', 'select');
    if(testType_check === true && testName_check === true){
        let patientId = $('#patient_Id').val();
        let testType = $('#emergencyLab-testType').val();
        let testName = $('#emergencyLab-testName').val();
        let method = $('#emergencyLab-method').val();
        let reportDays = $('#emergencyLab-reportDays').val();
        let testParameter = $('#emergencyLab-testParameter').val();
        let testRefRange = $('#emergencyLab-testRefRange').val();
        let testUnit = $('#emergencyLab-testUnit').val();
        $.ajax({
            url:emergencyLabSubmit,
            type:"POST",
            data:{
                patientId:patientId,testType:testType,testName:testName,method:method,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-lab').modal('hide');
                    $('#emergencyLab-form')[0].reset();
                     $('#emergancy-lab-reports-list').DataTable().ajax.reload();
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

function emergencyLabView(id){
    $.ajax({
        url:getEmergencyLabData,
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
                        $('.emergencyLabDataAppend').html(visit_lab_data);
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
function emergencyLabEdit(id){
$.ajax({
        url: getEmergencyLabDetails,
        type:"POST",
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{id:id},
        success:function(response){
            console.log(response);
            if(response.success){
               let getData = response.data[0];
                $('.emergencyLabSubmit').addClass('d-none');
                $('.emergencyLabUpdate').removeClass('d-none');
                $('#emergency-add-lab').modal('show');
                $('#emergencyLabID').val(id);
                $('#emergencyLab-testType').val(getData.test_type_id).change();
                $('#emergencyLab-testName').val(getData.test_name_id).change();
                $('#emergencyLab-method').val(getData.method).change();
                $('#emergencyLab-reportDays').val(getData.report_days);
                $('#emergencyLab-testParameter').val(getData.test_parameter);
                $('#emergencyLab-testRefRange').val(getData.test_ref_range);
                $('#emergencyLab-testUnit').val(getData.test_unit);
            }
        }
    });
}

function emergencyLabsUpdate(id){
    let testType_check = validateField('emergencyLab-testType', 'select');
    let testName_check = validateField('emergencyLab-testName', 'select');
    if(testType_check === true && testName_check === true){
       let testType = $('#emergencyLab-testType').val();
       let testName = $('#emergencyLab-testName').val();
       let method = $('#emergencyLab-method').val();
       let reportDays = $('#emergencyLab-reportDays').val();
       let testParameter = $('#emergencyLab-testParameter').val();
       let testRefRange = $('#emergencyLab-testRefRange').val();
       let testUnit = $('#emergencyLab-testUnit').val();
        $.ajax({
            url:emergencyLabUpdateData,
            type:"POST",
            data:{
                id:id,testType:testType,testName:testName,method:method,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
            },
            headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.success){
                    $('#emergency-add-lab').modal('hide');
                    $('#emergencyLab-form')[0].reset();
                     $('#emergancy-lab-reports-list').DataTable().ajax.reload();
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
function emergencyLabDelete(id){
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
                url:emergencyLabDataDelete,
                type:"POST",
                headers:{
                    'X-CSRF_TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(response){
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        $('#emergancy-lab-reports-list').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Error", "error");
                    }
                }
            });
        }
    });
}