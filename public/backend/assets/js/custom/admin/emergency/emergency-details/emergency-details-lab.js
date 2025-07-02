
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
function getTestName(testtype_id, testname_id) {
    $.ajax({
        url: getTestNameByTypeEmergency,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { id: testtype_id },
        success: function(response) {
            if (response.success) {
                let testNameData = response.data;
                let $testNameSelect = $('#emergencyLab-testName');
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
            url:getTestDetailsByIdEmergency,
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            data:{id:id},
            success:function(response){
                if(response.success){
                    let testData = response.data[0];
                    $('#emergencyLab-shortName').val(testData.s_name);
                    $('#emergencyLab-amount').val(testData.amount);
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
$('#emergencyLab-form').on('submit',function(e){
    e.preventDefault();
    let testType_check = validateField('emergencyLab-testType', 'select');
    let testName_check = validateField('emergencyLab-testName', 'select');
    if(testType_check === true && testName_check === true){
        let patientId = $('#patient_Id').val();
        let testType = $('#emergencyLab-testType').val();
        let testName = $('#emergencyLab-testName').val();
        let method = $('#emergencyLab-method').val();
        let amount = $('#emergencyLab-amount').val();
        let reportDays = $('#emergencyLab-reportDays').val();
        let testParameter = $('#emergencyLab-testParameter').val();
        let testRefRange = $('#emergencyLab-testRefRange').val();
        let testUnit = $('#emergencyLab-testUnit').val();
        $.ajax({
            url:emergencyLabSubmit,
            type:"POST",
            data:{
                patientId:patientId,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
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
            if(response.success){
                  const lab = response.data.labData;
                const patient = response.data.patientData;
                const testType = response.data.testType;
                const testName = response.data.testName;
                const testReports = response.testReport;
                let visit_lab_data = '';
                        visit_lab_data += ` <div class="row">
          <div class="col-md-12">
            <table class="table  table-borderless table-sm payment-pharmacy-table">
                <tbody>
                  <tr>
                      <th class="fw-medium">Bill No</th>
                        <td>PATH0${lab.id}</td>
                      <th class="fw-medium">Patient</th>
                      <td>${patient.name}</td>
                  </tr>
                  <tr>
                    <th class="fw-medium">Test Type</th>
                      <td>${testType.name}</td>
                      <th class="fw-medium">Test Name</th>
                      <td>${testName.name}</td>
                  </tr>
                  <tr>     
                      <th class="fw-medium">Short Name</th>
                      <td>${testName.s_name}</td>
                      <th class="fw-medium">Amount</th>
                      <td>${testName.amount}</td>
                  </tr>
                  <tr>           
                    <th class="fw-medium">Sample Collection</th>
                      <td>${lab.created_at}</td>
                      <th class="fw-medium">Report Date</th>
                      <td>${lab.report_days}</td>
                  </tr>
                  <tr>    
                      <th class="fw-medium">Test Method</th>
                      <td>${lab.method}</td>    
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
                   <th>Download</th>
                 </tr>
               </thead>
               <tbody>
                <tr>
                  <td></td>
                  <td>${lab.test_parameter}</td>
                  <td>${lab.test_ref_range}</td>
                  <td>${lab.test_unit}</td>
                  <td>`;
                    if (testReports.length > 0) {
                        testReports.forEach((report, index) => {
                            visit_lab_data += `<a href="${report.report_file_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-file-download-line"></i> PDF
                                </a>`;
                            });
                            } else {
                            visit_lab_data += `<tr><td colspan="5" class="text-center text-muted">No reports available.</td></tr>`;
                            }
                            visit_lab_data += `</td>
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
        let amount = $('#emergencyLab-amount').val();
       let reportDays = $('#emergencyLab-reportDays').val();
       let testParameter = $('#emergencyLab-testParameter').val();
       let testRefRange = $('#emergencyLab-testRefRange').val();
       let testUnit = $('#emergencyLab-testUnit').val();
        $.ajax({
            url:emergencyLabUpdateData,
            type:"POST",
            data:{
                id:id,testType:testType,testName:testName,method:method,amount:amount,reportDays:reportDays,testParameter:testParameter,testRefRange:testRefRange,testUnit:testUnit
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
function uploadPdf(id){
    $('#emergency-lab-report').modal('show');
    $('#emergencyLabIReportId').val(id);
}
$('#emergencyLabReport-form').on('submit',function(e){
    e.preventDefault();
        let lab_id = $('#emergencyLabIReportId').val();
        let title = $('#emergencyLabReport-title').val();
        let lab_pdf = $('#emergencyLabReport-file').prop('files')[0];
        if (title == '') {
        $('#emergencyLabReport-title').focus();
        } else {
        let formData = new FormData();
        formData.append('lab_id', lab_id);
        formData.append('patient_id', patient_id);
        formData.append('title', title);
        formData.append('lab_pdf', lab_pdf);
        $.ajax({
            url: labReportEmergencySubmit,
            type: "post",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    //  alert(response.success);
                    $('#emergency-lab-report').modal('hide');
                     $('#emergencyLabReport-form')[0].reset();
                    // $('#opd-lab-reports-list').DataTable().ajax.reload();
                    toastSuccessAlert(response.success);
                } else {
                    toastErrorAlert(response.error_success);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred: " + error);
            }
        });
    }
})
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