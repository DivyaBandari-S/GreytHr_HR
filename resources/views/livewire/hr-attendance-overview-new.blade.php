<div>

    <div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
             .attendance-overview-help {
                position: relative;
                width: 85%; /* Set your desired width */
                height: auto; /* Set your desired height */
                border-radius: 5px; /* Set your desired border-radius */
                border: 1px solid #ccc; /* Add border if needed */
                padding: 10px; /* Add padding if needed */
                margin: 20px 10px; /* Add margin if needed */
                background-color: #f3faff; /* Set background color if needed */
                font-size: 12px;
            }
            .hide-attendance-help {
                margin-top:40px;
                position: absolute;
                bottom: 80px;
                right: 10px;
                color: #0000FF;
                font-weight:500;
                cursor: pointer;
            }
            .with-white-background {
            background-color: white;
            border-radius: 5px;
            width: 85%; /* Set your desired width */
            height: auto;
            padding: 20px;
            margin: 20px 10px; /* Set white background color for the row */
        }
        .custom-list {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }
    
    .custom-list li {
        padding-left: 20px;
        position: relative;
        margin-bottom: 8px;
        line-height: 1.5;
    }
    .dropdown-container {
        width: 100%; /* Ensure the container spans full width */
        text-align: right; /* Align content to the right */
    border-radius: 5px;
    padding: 5px;
    margin-left:930px;
   
}
.dropdown-container {
    display: inline-block;
    height: 40px;
    width: auto;

 
}
    .custom-list li::before {
        content: '\2022'; /* Bullet character */
        color: #3498db; /* Change the color as needed */
        font-size: 3em;
        position: absolute;
        left: 0;
        top: 50%; /* Adjust the top property to move the bullet upwards */
        transform: translateY(-50%); /* Center the bullet vertically */
    }
    .dropdown-right
            {
                float: right;
            }
    .custom-list li:nth-child(2)::before {
        color: #2ecc71; /* Change the color for the second item */
    }
    
    .custom-list li:nth-child(3)::before {
        color: #e74c3c; /* Change the color for the third item */
    }
    .month-with-year
    {
        display: inline-block;
        width: 50px; /* Adjust width as needed */
        margin: 10px;
    }
        </style>    
        @if($showHelp==false)
          
               <div class="attendance-overview-help">
                    
                    <p>On the<span style="font-weight: bold;"> Attendance Overview</span> page,get an overview of your teams' attendance information. Get quick answers to questions<br/> such as Number of work hours completed by the team in a month,Summary of work hours,Who is in,and Access card details.</p>
                    <p>To view frequently asked questions <span style="color: #1fb6ff;cursor:pointer;"> click here</span>.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
                </div>
        @else
          
       
        <button style="background-color: white; margin-top: -20px; float: right; color: #0000FF; border: 1px solid #ffff; border-radius: 5px; cursor: pointer; padding: 10px 20px;font-weight:bold;"wire:click="showhelp">Show&nbsp;&nbsp;Help</button>
           
        @endif
        <div class="dropdown-container">
                <select class="dropdown-right" wire:model="selectedYear" wire:change="updateSelectedYear">
        <option value="{{ $previousYear }}">
            {{ \Carbon\Carbon::createFromDate($previousYear, 1, 1)->format('M') }} {{ $previousYear }} - 
            {{ \Carbon\Carbon::createFromDate($previousYear, 12, 1)->format('M') }} {{ $previousYear }}
        </option>
        <option value="{{ $currentYear }}">
            {{ \Carbon\Carbon::createFromDate($currentYear, 1, 1)->format('M') }} {{ $currentYear }} - 
            {{ \Carbon\Carbon::createFromDate($currentYear, 12, 1)->format('M') }} {{ $currentYear }}
        </option>
        <option value="{{ $nextYear }}">
            {{ \Carbon\Carbon::createFromDate($nextYear, 1, 1)->format('M') }} {{ $nextYear }} - 
            {{ \Carbon\Carbon::createFromDate($nextYear, 12, 1)->format('M') }} {{ $nextYear }}
        </option>
    </select>
    </div>
    <div class="row with-white-background">
    <div class="d-flex flex-wrap">
        @foreach($monthNumbers as $month)
            
            <div class="month-with-year mx-2 text-center"style="cursor:pointer;"@if($month == $monthNumber) style="background-color: blue;color:white;border-radius:5px;padding:5px;" @endif>
                <div><span style="padding-left:2px;">{{ $month }}</span></div> <!-- Month number -->
                <div><span style="font-size:12px;">{{ $selectedYear }}</span></div> <!-- Current year -->
            </div>

        @endforeach
    </div>
    
    </div>
    <div class="row with-white-background">
    <h4 style="text-align:left; font-weight:600;color: rgba(103,122,142,1); padding-bottom: 5px;font-size:14px;">Regularization Details</h4>
    @if(count($regularisations)>0)
    @foreach($regularisations as $r)
    @php
    $regularisationEntries = json_decode($r->regularisation_entries, true);
    $numberOfEntries = count($regularisationEntries);
    $firstItem = reset($regularisationEntries); // Get the first item
    $lastItem = end($regularisationEntries); // Get the last item
    @endphp
    @foreach($regularisationEntries as $r1)
    @if(empty($r1['date']))
    @php
    $numberOfEntries-=1;
    @endphp
    @endif
 
    @endforeach
    @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
 
            </div>
    @elseif (session()->has('success'))
           <div class="alert alert-danger">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
 
            </div>
    @endif
 
 
    <div class="accordion bg-white border mb-3 rounded">
        <div class="accordion-heading rounded">
 
            <div class="accordion-title p-2 rounded d-flex align-items-center justify-content-between">
 
                <!-- Display leave details here based on $leaveRequest -->
 
                <div class="accordion-content col">
 
                    <span style="color: #778899; font-size: 12px; font-weight: 500;">{{ucwords(strtolower($r->employee->first_name))}}&nbsp;{{ucwords(strtolower($r->employee->last_name))}}</span><br>
 
                    <span style="color: #36454F; font-size: 10px; font-weight: 500;">{{$r->emp_id}}</span>
 
                </div>
 
 
 
                <div class="accordion-content col">
 
                    <span style="color: #778899; font-size: 12px; font-weight: 500;">No. of Days</span><br>
 
                    <span style="color: #36454F; font-size: 12px; font-weight: 500;">
                        {{$numberOfEntries}}
                    </span>
 
                </div>
 
 
                <!-- Add other details based on your leave request structure -->
 
 
 
                <div class="arrow-btn"wire:click="toggleActiveAccordion({{ $r->id }})"style="color:{{ $openAccordionForActive === $r->id ? '#3a9efd' : '#778899' }};border:1px solid {{ $openAccordionForActive === $r->id ? '#3a9efd' : '#778899' }}">
                <i class="fa fa-angle-{{ $openAccordionForActive === $r->id ? 'up' : 'down' }}"style="color:{{ $openAccordionForActive === $r->id ? '#3a9efd' : '#778899' }}"></i>
                </div>
 
            </div>
 
        </div>
        <div class="accordion-body m-0 p-0"style="display:{{ $openAccordionForActive === $r->id ? 'block' : 'none' }} ">
 
            <div style="width:100%; height:1px; border-bottom:1px solid #ccc;"></div>
 
            <div class="content px-4 py-2">
 
                <span style="color: #778899; font-size: 12px; font-weight: 500;">Dates Applied:</span>
 
                <span style="font-size: 11px;">
                    @if($r->regularisation_entries_count>1)
                    <span style="font-size: 11px; font-weight: 500;"></span>
 
                    {{ date('(d', strtotime($firstItem['date'])) }} -
 
                    <span style="font-size: 11px; font-weight: 500;"></span>
 
                    @if (!empty($lastItem['date']))
                    {{ date('d)', strtotime($lastItem['date'])) }} {{ date('M Y', strtotime($lastItem['date'])) }}
                    @endif
                    @else
                    {{ date('d', strtotime($firstItem['date'])) }} {{ date('M Y', strtotime($lastItem['date'])) }}
                    @endif
 
                </span>
 
            </div>
 
 
 
            <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>
 
            <div style="display:flex; flex-direction:row; justify-content:space-between;">
 
                <div class="content mb-2 mt-0 px-4">
 
                    <span style="color: #778899; font-size: 12px; font-weight: 500;">Applied on:</span>
 
                    <span style="color: #333; font-size:12px; font-weight: 500;">{{ \Carbon\Carbon::parse($r->created_at)->format('d M, Y') }}
                    </span>
 
                </div>
 
                <div class="content mb-2 px-4 d-flex gap-2">
                    <a href="{{ route('review-pending-regularisation-for-hr', ['id' => $r->id,'emp_id' => $r->emp_id]) }}" style="color:rgb(2,17,79);font-size:12px;margin-top:3px;">View Details</a>
                    <button class="rejectBtn"wire:click="openRejectModal">Reject</button>
                    <button class="approveBtn"wire:click="openApproveModal">Approve</button>
                </div>
                @if($openRejectPopupModal==true)
                <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                <h5 class="modal-title" id="rejectModalTitle"style="color:#778899;">Reject Request</h5>
                                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeRejectModal" style="background-color: #f5f5f5;border-radius:20px;font-size:12px;border:2px solid #778899;height:15px;width:15px;" >
                                </button>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                                    <p style="font-size:14px;">Are you sure you want to reject this application?</p>
                                    <div class="form-group">
                                            <label for="remarks" style="font-size:12px;color:#666;font-weight:400;">Remarks</label>
                                            <input type="text" class="form-control placeholder-small" id="remarks" placeholder="Enter reason here" wire:model="remarks" style="height: 100px; padding-bottom: 70px;">
                                    </div>
 
                            </div>
                            <div class="modal-footer">
                                <button type="button"class="approveBtn"wire:click="reject({{$r->id}})">Confirm</button>
                                <button type="button"class="rejectBtn"wire:click="closeRejectModal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div>
                @endif
                @if($openApprovePopupModal==true)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                        <h5 class="modal-title" id="approveModalTitle"style="color:#778899;">Approve Request</h5>
                                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeApproveModal" style="background-color: #f5f5f5;font-size:12px;border-radius:20px;border:2px solid #778899;height:15px;width:15px;" >
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height:300px;overflow-y:auto">
                                            <p style="font-size:14px;">Are you sure you want to approve this application?</p>
                                            <div class="form-group">
                                                    <label for="remarks" style="font-size:12px;color:#666;font-weight:400;">Remarks</label>
                                                    <input type="text" class="form-control placeholder-small" id="remarks" placeholder="Enter reason here" wire:model="remarks" style="height: 100px; padding-bottom: 70px;">
                                            </div>
 
                                    </div>
                                    <div class="modal-footer">
                                            <button type="button"class="approveBtn"wire:click="approve({{$r->id}})">Confirm</button>
                                            <button type="button"class="rejectBtn"wire:click="closeApproveModal">Cancel</button>
                                           
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                @endif
            </div>
        </div>
 
 
 
    </div>
      @endforeach
    @else
            <div class="d-flex flex-column justify-content-center bg-white rounded border text-center">
                            <img src="/images/pending.png" alt="Pending Image" style="width:55%; margin:0 auto;">
                            <p style="color:#969ea9; font-size:12px; font-weight:400; ">Hey, you have no regularization records to view
                            </p>
            </div>
    @endif
    </div>
    <div class="row with-white-background">
                           <div class="col-sm-4"style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-right:-40px;">
                                <h4 style="text-align:left; font-weight:600;color: rgba(103,122,142,1);border-bottom: 1px solid #dddddd; padding-bottom: 5px;font-size:14px;">Summary</h4>
                                <ul style="list-style-type: none; padding: 0; margin-top: 20px; color: rgba(103,122,142,1); font-size: 14px;">
                                        <li style="margin-bottom: 10px;">
                                            <strong>Average Work Hours:</strong> {{ $averageWorkHours ?? 'N/A' }}
                                        </li>
                                        <li style="margin-bottom: 10px;">
                                            <strong>Number of Absent Days:</strong> {{ $absentDays ?? 'N/A' }}
                                        </li>
                                        <li style="margin-bottom: 10px;">
                                            <strong>Holidays in the Month:</strong> {{ $holidaysInMonth ?? 'N/A' }}
                                        </li>
                                    </ul>
                            </div>
                            <div class="col-sm-6"style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-left:60px;">
                                <h4 style="text-align:left; font-weight:600;color: rgba(103,122,142,1);border-bottom: 1px solid #dddddd; padding-bottom: 5px;font-size:14px;">Work Hours Summary</h4>
                                @livewire('work-hours-chart')
                                
                            </div>
    </div>
        <div class="row with-white-background">
        <!-- Heading above the row -->
       
                <div class="col-sm-12">
                    
                        <h4 style="text-align:left;font-weight:600;font-size:14px;">Access Card Details
                                                        <button style="border: 1px solid #0000FF; color: #0000FF; float:right; background-color: transparent; padding: 5px; border-radius: 5px; font-size: 13px;margin-top:-10px;"wire:click="downloadexcelForNotAssigned">
                                                           <i class="fas fa-download"></i>  Download  
                                                        </button>
                        </h4>
                </div>
                
    
                <div class="row"style="justify-content:space-between;">
                            <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;">
                                        <h4 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);font-size:14px;">Not Assigned</h4>
                    <!-- Table for the first container goes here -->
                                                    <div style="overflow-y:auto;max-height:210px;overflow-x:hidden;">
                                                        <table class="table">
                                                            <tbody style="overflow-y:auto;background-color:pink;">
                                                            @foreach($totalemployees as $te)    
                                                            <tr>
                                                                <td>
                                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNWoZhTRyuOwc2TBBgSHMzxK1Oj4KQInvuMBCSGeMJCNnGoRaH_RExpbQ5RaMJPxibMjQ&usqp=CAU" alt="User Icon" style="width: 30px; height: 30px; border-radius: 50%;margin-top:10px;">
    
                                                                </td>
                                                                <td style="vertical-align: middle;padding-right:220px;">
                                                                    <span style="color: rgba(103,122,142,1);font-size:10px;">{{ ucwords(strtolower($te->first_name))}}&nbsp;{{ucwords(strtolower($te->last_name))}}  <br/>({{$te->emp_id}})</span><!-- Replace with the actual name -->
                                                                </td>
                        
                                                            </tr>   
                                                            @endforeach
                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>   
                            </div>
                <!-- Second Container -->
                            <div class="col-sm-6"style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-right:-40px;">
                                <h4 style="text-align:left; font-weight:600;color: rgba(103,122,142,1);border-bottom: 1px solid #dddddd; padding-bottom: 5px;font-size:14px;">Validity Expired</h4>
                                <div style="text-align: center; margin-top: 100px;">
                                    <p style="color: rgba(103,122,142,1);font-size:14px;">No records found</p>
                                </div>
                                <!-- Content for the second container goes here -->
                            </div>
                </div>
        </div>
    </div>
    <div class="row with-white-background"style="background-color:white;">
        <!-- Heading above the row -->
        
                
                <div class="row"style="justify-content:space-between;">
                <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;">
                                        <h6 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);">Who's In?</h6>
                    <!-- Table for the first container goes here -->
                           <div style="display:flex;flex-direction:row;justify-content:space-between">
                                      

                          

                        <!-- <select name="date" id="date" class="form-select"wire:model="selectedDateForDropdown"wire:change="updateSelectedDateForDropdown">
                            <option value="{{ $today }}">{{ $today }}</option>
                            <option value="{{ $yesterday }}">{{ $yesterday }}</option>
                            <option value="{{ $dayBeforeYesterday }}">{{ $dayBeforeYesterday }}</option>
                        </select> -->

   
                                               
                           </div>   
                           
                           
                    <canvas id="employeeStatusChart" width="150" height="150"></canvas>                               
                </div>
              
                <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-right:-40px;">
                                                        <button style="border: 1px solid #0000FF; color: #0000FF; float:right; font-weight:bold;background-color: transparent; padding: 5px; border-radius: 5px; font-size: 13px;"wire:click="downloadexcelForAttendanceType">
                                                           <i class="fas fa-download"></i>  Download  
                                                        </button>
                                        <h4 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);font-size:14px;">Attendance Type</h4>
                                        
                    <!-- Table for the first container goes here -->
                           <div style="display:flex;flex-direction:row;justify-content:space-between">
                                                        <!-- <div class="input-group" style="width: 200px;">
                                                                <select class="form-control">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="fas fa-caret-down"></i>
                                                                            </span>
                                                                        </div>
                                                                    <option value="all">Date: 14 Feb 2024</option>
                                                                    
                                                                </select>
                                                                
                                                        </div>    -->
                                            <!-- <div class="input-group" style="width: 200px;">
                                                        <select class="form-control">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-caret-down"></i>
                                                                    </span>
                                                                </div>
                                                            <option value="all">Category: All</option>
                                                         
                                                        </select>
                                                        
                                            </div>   -->
                                               
                           </div>   
                           {{$absentemployeescount}}
                           <canvas id="employeeAttendanceTypeChart" width="150" height="150"></canvas>    
         
                           <p style="color: blue; cursor: pointer;"wire:click="openSelector">+2 More</p>
                          
                </div>
        </div>
        <!-- Trigger for Modal -->
    
    
    <!-- Modal -->
    
    @if ($openshiftselectorforcheck==true)
            <div class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <b>ERFGHJBKN</b>
                            </h5>
                            <button type="button" class="btn-close btn-primary" data-dismiss="modal"
                                aria-label="Close" wire:click="closeAllAbsentEmployees">
                            </button>
                        </div>
                        <div class="modal-body" style="max-height:300px;overflow-y:auto">
                            <div class="team-leave d-flex flex-row gap-3">
                                HII PRANITA AND KUMAR
                                <canvas id="employeeAttendanceTypeChartmodal" width="30" height="30"></canvas>    
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif
 
    </div> 
    
  <script>
    var ctx = document.getElementById('employeeStatusChart').getContext('2d');
var employeeStatusChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
            'Not Yet In: {{$absentemployeescount}}',
            'On Time: {{$earlyemployeescount}}',
            'Late In: {{$lateemployeescount}}'
        ],
        datasets: [{
            data: [{{$absentemployeescount}}, {{$earlyemployeescount}}, {{$lateemployeescount}}],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', // Color for "Not Yet In"
                'rgba(75, 192, 192, 0.2)',  // Color for "On Time"
                'rgba(255, 159, 64, 0.2)'   // Color for "Late In"
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true, // Allow resizing to custom dimensions
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});
var ctx = document.getElementById('employeeAttendanceTypeChart').getContext('2d');
var employeeAttendanceTypeChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
            'Mobile Sign In: {{$mobileEmployeeCount}}',
            'Web Sign In: {{$laptopEmployeeCount}}',
            'Astra: 0'
        ],
        datasets: [{
            data: [{{$mobileEmployeeCount}}, {{$laptopEmployeeCount}}, 0],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', // Color for "Not Yet In"
                'rgba(75, 192, 192, 0.2)',  // Color for "On Time"
                'rgba(255, 159, 64, 0.2)'   // Color for "Late In"
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true, // Allow resizing to custom dimensions
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});
var ctx = document.getElementById('employeeAttendanceTypeChartmodal').getContext('2d');
var employeeAttendanceTypeChartmodal = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [
            'Mobile Sign In: {{$mobileEmployeeCount}}',
            'Web Sign In: {{$laptopEmployeeCount}}',
            'Astra: 0'
        ],
        datasets: [{
            data: [{{$mobileEmployeeCount}}, {{$laptopEmployeeCount}}, 0],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', // Color for "Not Yet In"
                'rgba(75, 192, 192, 0.2)',  // Color for "On Time"
                'rgba(255, 159, 64, 0.2)'   // Color for "Late In"
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true, // Allow resizing to custom dimensions
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});
  </script>
  
    </div>
    
