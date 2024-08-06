<div >
<body>
    @if($show=='true')
    @livewire('add-employee-details')
    @endif
<div class="container-fluid px-1  rounded">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Welcome</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Dashboard</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                <div class="row m-0 mb-4">
                    <div class="col-md-7 m-auto " style="text-align: center;line-height:2;">
                       <h4 style="background: linear-gradient(90deg, #0F3057, #045575); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome Bandari Divya, your dashboard is ready!</h4>
                        <p class="p-0 " style="color: #565656;font-size:12px;">Congratulations! Your affiliate dashboard is now ready for use. You have access to view profiles, requests, and job details. Get started and make the most out of your affiliate opportunities.</p>
                    </div>
                    <div class="col-md-5">
                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 100%;">
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-md-8">
                        <div class="row m-0">
                            <div class="col-md-4 mb-4">
                                <div class="row m-0 avaPosCard">
                                    <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">
                                        <div class="col-8 m-0 p-0" style="text-align: start;">
                                            <h4 class="countOfNum m-0 p-0">24</h4>
                                        </div>
                                        <div class="col-4 p-0" >
                                            <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                        </div>
                                    </div>
                                    <p class="p-0 subHeading">Available Position</p>
                                    <span class="p-0 pb-3" style="color: #778899;font-size:12px;">4 Urgently needed</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="row m-0 jobOpenCard">
                                    <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">
                                    <div class="col-8 m-0 p-0" style="text-align: start;">
                                        <h4 class=" countOfNum m-0 p-0">4</h4>
                                    </div>
                                    <div class="col-4 p-0 " >
                                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                    </div>
                                    </div>
                                    <p class="p-0 subHeading">Job Open</p>
                                    <span class="p-0 pb-3" style="color: #778899;font-size:12px;">4 Active hiring</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="row m-0 newEpmCard">
                                    <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">
                                    <div class="col-8 p-0 m-0" style="text-align: start;">
                                        <h4 class="countOfNum m-0">{{$totalNewEmployeeCount}}</h4>
                                    </div>
                                    <div class="col-4 p-0 " style="text-align: right">
                                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                    </div>
                                    </div>
                                    <p class="p-0  subHeading">New Employees</p>
                                    <span class="p-0 pb-3" style="color: #778899;font-size:12px;">{{$departmentCount}} Department</span>
                                </div>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-6 mb-4 ">
                                <div class="row m-0 totalEmpCard">
                                    <div class="col-8  p-0 pb-5 pt-3">
                                        <p class="totalEmpText">Total Employees</p>
                                        <h4 class="countOfNum">{{ $totalEmployeeCount }}+</h4>
                                    </div>
                                    <div class="col-4 p-0 pb-5 pt-3" style="text-align: right">
                                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="row m-0 hrReqCard">
                                    <div class="col-8 p-0 pb-5 pt-3">
                                        <p class="totalEmpText">HR Requests</p>
                                        <h4 class="countOfNum">15</h4>
                                    </div>
                                    <div class="col-4 p-0 pb-5 pt-3" style="text-align: right">
                                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ms-3 me-3 annocmentCard">
                            <h6 class="mt-3 announcemnt" >Announcement</h6>
                            <div class="m-0 mb-3 row totalEmpCard" style="padding: 10px 0px;">
                                <div class="col-8">
                                    <p class="m-0">Outing schedule for every departement</p>
                                    <p class="m-0 text-time">5 Minutes ago</p>
                                </div>
                                <div class="col-4 m-auto" style="text-align: right">
                                    <a href="#">View</a>
                                </div>
                            </div>
                            <div class="m-0 mb-3 row hrReqCard" style="padding: 10px 0px;">
                                <div class="col-8">
                                    <p class="m-0">Outing schedule for every departement</p>
                                    <p class="m-0 text-time">5 Minutes ago</p>
                                </div>
                                <div class="col-4 m-auto" style="text-align: right">
                                    <a href="#">View</a>
                                </div>
                            </div>
                            <div class="m-0 mb-3 row newEpmCard" style="padding: 10px 0px;">
                                <div class="col-8">
                                    <p class="m-0">Outing schedule for every departement</p>
                                    <p class="m-0 text-gray">5 Minutes ago</p>
                                </div>
                                <div class="col-4 m-auto" style="text-align: right">
                                    <a href="#">View</a>
                                </div>
                            </div>
                        </div>

                        <div class="row ms-3 me-3">
                            <h6 class="mt-4 p-0 mx-2 announcemnt" style="text-align: start;">Help Links</h6>
                            <div class="m-0 p-0 " style="text-align: start;">
                                <span class="helpChip">Attune Global Community</span>
                                <span class="helpChip">Statutory Compliances</span>
                                <span class="helpChip">How to Videos</span>
                                <span class="helpChip">Attune Global Knowledge Center</span>
                                <span class="helpChip">Resource Center</span>
                                <span class="helpChip">Attune Global Academy</span>
                                <span class="helpChip">Attune Global FM</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row m-0 mb-4 annocmentCard" style="padding: 10px 15px;">
                            <div class="p-0 m-0 d-flex justify-content-between align-items-center mb-4">
                                <div style="text-align: start;">
                                    <h6 class="m-0 pt-1" >Recently Activity</h6>
                                </div>
                                <div >
                                    <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">
                                </div>
                            </div>
                            <div class="mt-2 p-0" style="text-align: center;">
                            <p class="p-0 m-0" style="font-size: 14px;">You Posted a New Job</p>
                            <p class="p-0 pb-3" style="color: #778899; font-size: 12px;">10.40 AM, Fri 10 Dec 2023</p>
                            </div>
                            <button class="btn btn-primary" style="background: #02114f;font-size:12px;">See All Activity</button>
                        </div>

                        <div class="row m-0 mb-4 annocmentCard">
                            <h6 class="mt-3 announcemnt">Upcoming Schedule</h6>
                            <p>Priority</p>
                            <div class="m-0 mb-3 row totalEmpCard" style="padding: 10px 0px;">
                                <div class="col-9">
                                    <p class="m-0">Review candidate applications</p>
                                    <p class="m-0 text-time">Today - 11.30 AM</p>
                                </div>
                                <div class="col-3 m-auto" style="text-align: right">
                                    <a href="#">Action</a>
                                </div>
                            </div>
                            <p>Other</p>
                            <div class="m-0 mb-3 row hrReqCard" style="padding: 10px 0px;">
                                <div class="col-8">
                                    <p class="m-0">Outing schedule for every departement</p>
                                    <p class="m-0 text-time">Today - 11.30 AM</p>
                                </div>
                                <div class="col-4 m-auto" style="text-align: right">
                                    <a href="#">Action</a>
                                </div>
                            </div>
                            <div class="m-0 mb-3 row newEpmCard" style="padding: 10px 0px;">
                                <div class="col-8">
                                    <p class="m-0">Outing schedule for every departement</p>
                                    <p class="m-0 text-time">Today - 11.30 AM</p>
                                </div>
                                <div class="col-4 m-auto" style="text-align: right">
                                    <a href="#">Action</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">
                <div class="bg-white p-1 d-flex flex-row justify-content-between  mb-2">
                    <div class="col-8 d-flex flex-row justify-content-between mt-2 mb-4" style="text-align:start;">
                        <div class="col d-flex flex-column" style="padding: 10px 20px;border-radius: 5px;background:#ffe2c3; color:rgb(2, 17, 79); margin-right: 7px;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                            <p class="font-weight-500" style="font-size:13px;">Add Employee</p>
                            <a class="text-decoration-none" href="{{ route('add-employee-details') }}">
                                <i class="fa-solid fa-user-plus" style="display:flex;justify-content:center;cursor:pointer;"></i>
                            </a>

                        </div>
                        <div class="col d-flex flex-column" style="padding: 10px 20px;border-radius: 5px;background:#c3e0ff; color:rgb(2, 17, 79); margin-right: 7px;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                            <p class="font-weight-500" style="font-size:13px;">Update Employee</p>
                            <a class="text-decoration-none" href="{{ route('update-employee-details') }}">
                                <i class="fa-solid fa-user-plus" style="display:flex;justify-content:center;cursor:pointer;"></i>
                            </a>
                        </div>
                        <div class="col d-flex flex-column" style="padding: 10px 20px;border-radius: 5px;background:#e2c3ff; color:rgb(2, 17, 79);box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
                            <p class="font-weight-500" style="font-size:13px;">Add Payroll</p>
                        </div>
                    </div>
                    <div class="col-4 p-2">
                        <div class="rounded" style="border:1px solid #ccc;background:#fffaea;">

                        </div>
                    </div>
                </div>
                <div class="row m-0 mt-4 mb-4">
                    <div class="col-md-6 mb-4">
                        <div style="position: relative; width:100%">
                            <canvas id="yearsInServiceChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div style="position: relative; width:100%">
                            <canvas id="additionAndAttrition"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div style="position: relative; width:100%">
                            <canvas id="emplCountByLocation"></canvas>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="table-responsive p-4" style="background: white">
                            <h4 style="color: #02114f; font-weight: 600">Top 5 Leave Takers</h4>
                            <p>01 Oct 2023 to 31 Jan 2024</p>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Emp No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>XSS-0237</td>
                                        <td>Sai Kinnarn Kotha</td>
                                        <td>40</td>
                                    </tr>
                                    <tr>
                                        <td>XSS-0987</td>
                                        <td>Thornton</td>
                                        <td>13</td>
                                    </tr>
                                    <tr>
                                        <td>XSS-1267</td>
                                        <td>Larry the Bird</td>
                                        <td>34</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4 mx-2 bg-white border rounded">
                       <div class="chart-title" >
                            <p style="margin: 0;">Gender Distribution - Current Employees</p>
                        </div>
                        <div class="chart-container bg-white d-flex align-items-center justify-content-center mt-4" style="position: relative; margin-bottom:20px;">
                           <canvas id="genderChart" style="width: 300px; height: 300px;"></canvas>
                        </div>
                        <div style="display:flex;justify-content:end;flex-direction:column;align-items:end;">
                           <p style="font-size: 12px;color:#778899;">Male Employees :  <span style="font-size: 12px;color:#000;font-weight:500;">{{ $maleCount }}</span> </p>
                           <p style="font-size: 12px;color:#778899;">Female Employees :  <span style="font-size: 12px;color:#000;font-weight:500;">{{ $femaleCount }}</span> </p>
                           <p style="font-size: 12px;color:#778899;">Total Employees :  <span style="font-size: 12px;color:#000;font-weight:500;">{{ $totalEmployeeCount }}</span> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<script>
  const ctx1 = document.getElementById('yearsInServiceChart');
  const plugin = {
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart, args, options) => {
        const {ctx} = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = options.color || '#99ffff';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
  };

  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: 'Years',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Years in Service Distribution',
            },
            customCanvasBackgroundColor: {
                color: 'white',
            }
        },
      scales: {
        y: {
            title: {
            display: true,
            text: 'Employee',
          },
          beginAtZero: true
        },
        x: {
            title: {
            display: true,
            text: 'Years',
          }
        }
      }
    },
    plugins: [plugin],
  });

  const ctx2 = document.getElementById('additionAndAttrition');

  new Chart(ctx2, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [
        {
            label: 'Joined',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        },
        {
            label: 'Resigned',
            data: [3, 19, 12, 15, 12, 3],
            borderWidth: 1
        }
     ]
    },
    options: {
        plugins: {
            title: {
        display: true,
        text: 'Addition & Attribution (Feb-2023 to Jan-2024)',
      },
            customCanvasBackgroundColor: {
                color: 'white',
            }
        },
      scales: {
        y: {
            title: {
            display: true,
            text: 'Employees',
          },
          beginAtZero: true
        },
        x: {
            title: {
            display: true,
            text: 'Months',
          }
        }
      }
    },
    plugins: [plugin],
  });

  const ctx3 = document.getElementById('emplCountByLocation');

  new Chart(ctx3, {
    type: 'bar',
    data: {
      labels: ['Hyderabad', 'Udaipur', 'Bhubneswar', 'Bangalore'],
      datasets: [{
        label: 'Count',
        data: [12, 19, 3, 5],
        borderWidth: 1
      }]
    },
    options: {
        indexAxis: 'y',
        elements: {
            bar: {
                borderWidth: 3,
            }
        },
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            },
            title: {
                display: true,
                text: 'Employee Count By Location',
            },
            customCanvasBackgroundColor: {
                color: 'white',
            }
        },
    },
    plugins: [plugin],
  });

  const ctx4 = document.getElementById('genderChart');
    new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($data),
                backgroundColor: Object.values(@json($colors)),
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false // Hide default title
                },
                customCanvasBackgroundColor: {
                    color: 'white',
                }
            },
        },
    });
</script>

</body>
</html>
</div>
