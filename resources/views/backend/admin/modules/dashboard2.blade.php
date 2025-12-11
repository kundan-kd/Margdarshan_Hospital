@extends('backend.admin.layouts.main')

@section('title', 'Dashboard')

@section('main-container')
<div class="dashboard-main-body">

    {{-- Breadcrumb --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Dashboard</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Hospital</li>
        </ul>
    </div>

    <div class="row gy-4">

        {{-- Doctor-wise Consultation --}}
        <div class="col-md-12">
            <div class="card h-100 p-0">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Doctor-wise Consultation</h6>
                </div>
                <div class="card-body p-24">
                    <div id="columnGroupBarChart"></div>
                </div>
            </div>
        </div>

        {{-- Appointment vs Walk-in Analysis --}}
        <div class="col-md-3">
            <div class="card h-100 radius-8 border-0">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold text-lg mb-2">Appointment vs Walk-in Analysis</h6>
                </div>

                <div class="card-body p-24">
                    <div class="position-relative">
                        <div id="statisticsDonutChart" class="mt-36"></div>
                    </div>

                    <ul class="row gy-4 mt-3">
                        <li class="col-6 text-center">
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <span class="w-12-px h-8-px rounded-pill bg-success-600"></span>
                                <span class="text-secondary-light text-sm">Walk-in</span>
                            </div>
                            <h6 class="text-primary-light fw-bold mb-0 walkin-appointment">00</h6>
                        </li>

                        <li class="col-6 text-center">
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <span class="w-12-px h-8-px rounded-pill bg-warning-600"></span>
                                <span class="text-secondary-light text-sm">Total Appointments</span>
                            </div>
                            <h6 class="text-primary-light fw-bold mb-0 total-appointment">00</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Main Stats Section --}}
        <div class="col-xxxl-9">
            <div class="row gy-4">

                {{-- Revenue & Collection --}}
                <div class="col-xxl-4 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-success-100 text-success-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-wallet-3-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        ₹<span class="total-revenue">00</span> /
                                        ₹<span class="total-collected">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">Revenue / Collection</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- New / Repeat Patients --}}
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-6">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-cyan-100 text-cyan-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <span class="new-patient">00</span> /
                                        <span class="repeat-patient">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">New Patients / Repeat Patients</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Emergency Added / Moved --}}
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-4">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-lilac-100 text-lilac-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-award-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <span class="emergency-added">00</span> /
                                        <span class="emergency-moved">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">Emergency Added / Moved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ICU Beds --}}
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-primary-100 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <span class="icu-beds">00</span> /
                                        <span class="icu-occupied-beds">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">ICU Beds / Occupied</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- IPD Beds --}}
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-primary-100 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <span class="ipd-beds">00</span> /
                                        <span class="ipd-occupied-beds">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">IPD Beds / Occupied</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Average Stay --}}
                <div class="col-xxl-3 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-primary-100 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">{{ $average_stay }}</h6>
                                    <span class="text-secondary-light text-sm">Average Length of Stay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lab Tests --}}
                <div class="col-xxl-4 col-xl-4 col-sm-6">
                    <div class="card p-3 shadow-2 radius-8 h-100 bg-gradient-end-1">
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center gap-2 mb-8">
                                <span class="w-48-px h-48-px bg-primary-100 text-primary-600 rounded-circle d-flex justify-content-center align-items-center">
                                    <i class="ri-group-fill"></i>
                                </span>
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <span class="lab-today">00</span> /
                                        <span class="lab-weekly">00</span> /
                                        <span class="lab-monthly">00</span>
                                    </h6>
                                    <span class="text-secondary-light text-sm">Lab Test Today / Weekly / Monthly</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- row --}}
        </div> {{-- col-xxxl-9 --}}

    </div> {{-- row main --}}
</div> {{-- dashboard-main-body --}}
@endsection

@section('extra-js')
  <!-- Apex Chart js -->
  <script src="{{asset('backend/assets/js/lib/apexcharts.min.js')}}"></script>
  {{-- <script src="{{asset('backend/assets/js/columnChartPageChart.js')}}"></script> --}}
<script>

   // ================================ Column with Group Label chart Start ================================ 
   let consultData = @json($consultantCount);
   let doctorNames = consultData.map(item => item.doctor_name);
   let patientCount = consultData.map(item => item.patient_count);
    var options = {
      series: [{
          name: "Patient Attended",
          data: patientCount
      }],
      chart: {
          type: 'bar',
          height: 264,
          toolbar: {
              show: false
          }
      },
      plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 8,
            columnWidth: 10,
            borderRadiusApplication: 'end', // 'around', 'end'
            borderRadiusWhenStacked: 'last', // 'all', 'last'
            columnWidth: '23%',
            endingShape: 'rounded',
          }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
          type: 'gradient',
          colors: ['#487FFF'], // Set the starting color (top color) here
          gradient: {
              shade: 'light', // Gradient shading type
              type: 'vertical',  // Gradient direction (vertical)
              shadeIntensity: 0.5, // Intensity of the gradient shading
              gradientToColors: ['#487FFF'], // Bottom gradient color (with transparency)
              inverseColors: false, // Do not invert colors
              opacityFrom: 1, // Starting opacity
              opacityTo: 1,  // Ending opacity
              stops: [0, 100],
          },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      xaxis: {
          type: 'category',
          categories: doctorNames
      },
      yaxis: {
          labels: {
              formatter: function (value) {
                  return value;
              }
          }
      },
      tooltip: {
          y: {
              formatter: function (value) {
                  return value;
              }
          }
      }
    };

    var chart = new ApexCharts(document.querySelector("#columnGroupBarChart"), options);
    chart.render();
  // ================================ Column with Group Label chart End ================================ 





     // ===================== Average Enrollment Rate Start =============================== 
     function createChartTwo(chartId, color1, color2) {
        var options = {
            series: [{
                name: 'New Patient',
                data: [48, 35, 55, 32, 48, 30, 55, 50, 57]
            }, {
                name: 'Old Patient',
                data: [12, 20, 15, 26, 22, 60, 40, 48, 25]
            }],
            legend: {
                show: false 
            },
            chart: {
                type: 'area',
                width: '100%',
                height: 270,
                toolbar: {
                    show: false
                },
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: [color1, color2], // Use two colors for the lines
                lineCap: 'round'
            },
            grid: {
                show: true,
                borderColor: '#D1D5DB',
                strokeDashArray: 1,
                position: 'back',
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                row: {
                    colors: undefined,
                    opacity: 0.5
                },
                column: {
                    colors: undefined,
                    opacity: 0.5
                },
                padding: {
                    top: -20,
                    right: 0,
                    bottom: -10,
                    left: 0
                },
            },
            colors: [color1, color2], // Set color for series
            fill: {
                type: 'gradient',
                colors: [color1, color2], // Use two colors for the gradient
                // gradient: {
                //     shade: 'light',
                //     type: 'vertical',
                //     shadeIntensity: 0.5,
                //     gradientToColors: [`${color1}`, `${color2}00`], // Bottom gradient colors with transparency
                //     inverseColors: false,
                //     opacityFrom: .6,
                //     opacityTo: 0.3,
                //     stops: [0, 100],
                // },
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [undefined, `${color2}00`], // Apply transparency to both colors
                    inverseColors: false,
                    opacityFrom: [0.4, 0.6], // Starting opacity for both colors
                    opacityTo: [0.3, 0.3], // Ending opacity for both colors
                    stops: [0, 100],
                },
            },
            markers: {
                colors: [color1, color2], // Use two colors for the markers
                strokeWidth: 3,
                size: 0,
                hover: {
                    size: 10
                }
            },
            xaxis: {
                labels: {
                    show: false
                },
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                tooltip: {
                    enabled: false
                },
                labels: {
                    formatter: function (value) {
                        return value;
                    },
                    style: {
                        fontSize: "14px"
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                    return "$" + value + "k";
                    },
                    style: {
                    fontSize: "14px"
                    }
                },
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart.render();
    }

    createChartTwo('enrollmentChart', '#487FFF', '#FF9F29');
    // ===================== Average Enrollment Rate End =============================== 
    
    
  // ================================ User Activities Donut chart End ================================ 
  let appoinrmentsCount = @json($appoinrmentsCount);
  // Access values
    let totalAppointment = appoinrmentsCount.total_appointment;
    let walkIn = appoinrmentsCount.walk_in;
    $('.total-appointment').text(totalAppointment);
    $('.walkin-appointment').text(walkIn);

var options = { 
    series: [totalAppointment, walkIn], 
      colors: ['#FF9F29', '#45B369'],
      labels: ['Appointments', 'Walk-in'] ,
      legend: {
          show: false 
      },
      chart: {
        type: 'donut',    
        height: 260,
        sparkline: {
          enabled: true // Remove whitespace
        },
        margin: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
        padding: {
          top: 0,
          right: 0,
          bottom: 0,
          left: 0
        }
      },
      stroke: {
        width: 0,
      },
      dataLabels: {
        enabled: false
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }],
    };

    var chart = new ApexCharts(document.querySelector("#statisticsDonutChart"), options);
    chart.render();
  // ================================ User Activities Donut chart End ================================ 

  
    // ================================ Client Payment Status chart End ================================ 
    var options = {
      series: [{
        name: 'Net Profit',
        data: [44, 100, 40, 56, 30, 58, 50]
      }, {
        name: 'Free Cash',
        data: [60, 120, 60, 90, 50, 95, 90]
      }],
      colors: ['#45B369', '#FF9F29'],
      labels: ['Active', 'New', 'Total'] ,
      
      legend: {
          show: false 
      },
      chart: {
        type: 'bar',
        height: 260,
        toolbar: {
          show: false
        },
      },
      grid: {
          show: true,
          borderColor: '#D1D5DB',
          strokeDashArray: 4, // Use a number for dashed style
          position: 'back',
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: 8,
        },
      },
      dataLabels: {
        enabled: false
      },
      states: {
        hover: {
        filter: {
            type: 'none'
            }
        }
    },
      stroke: {
        show: true,
        width: 0,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat', 'Sun'],
      },
      fill: {
        opacity: 1,
        width: 18,
      },
    };

    var chart = new ApexCharts(document.querySelector("#paymentStatusChart"), options);
    chart.render();
  // ================================ Client Payment Status chart End ================================ 

    // ================================= Multiple Radial Bar Chart Start =============================
    var options = {
        series: [80, 40, 10],
        chart: {
            height: 300,
            type: 'radialBar',
        },
        colors: ['#3D7FF9', '#ff9f29', '#16a34a'], 
        stroke: {
            lineCap: 'round',
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '10%',  // Adjust this value to control the bar width
                },
                dataLabels: {
                    name: {
                        fontSize: '16px',
                    },
                    value: {
                        fontSize: '16px',
                    },
                    // total: {
                    //     show: true,
                    //     formatter: function (w) {
                    //         return '82%'
                    //     }
                    // }
                },
                track: {
                    margin: 20, // Space between the bars
                }
            }
        },
        labels: ['Cardiology', 'Psychiatry', 'Pediatrics'],
    };

    var chart = new ApexCharts(document.querySelector("#radialMultipleBar"), options);
    chart.render();
    // ================================= Multiple Radial Bar Chart End =============================

    let revenue = @json($totalRevenue);
    let pending = revenue.total_amount - revenue.received_amount;
    $('.total-revenue').text(revenue.total_amount);
    $('.total-collected').text(revenue.received_amount);
    $('.pending-amount').text(pending);


    let patientVisitCount = @json($patientCounts);
    $('.new-patient').text(patientVisitCount.new_patient);
    $('.repeat-patient').text(patientVisitCount.repeat_patient);

    let emergencyPatients = @json($emergencyPatients);
    $('.emergency-added').text(emergencyPatients.admission);
    $('.emergency-moved').text(emergencyPatients.moved);

    let icuBeds = @json($icuBeds);
    $('.icu-beds').text(icuBeds.total);
    $('.icu-occupied-beds').text(icuBeds.occupied);

    let ipdBeds = @json($ipdBeds);
    $('.ipd-beds').text(ipdBeds.total);
    $('.ipd-occupied-beds').text(ipdBeds.occupied);

    let labtest = @json($lab_count);
    $('.lab-today').text(labtest.today_lab);
    $('.lab-weekly').text(labtest.weekly_lab);
    $('.lab-monthly').text(labtest.monthly_lab);









</script>
@endsection
