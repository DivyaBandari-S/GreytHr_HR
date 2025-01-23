<div>


    <style>
         
            .dropdown-right
            {
                float: right;
            }
            .down-arrow-ign-attendance-info-attendance-muster {
    width: 0;
    height: 0;
    /* border-left: 20px solid transparent; */
    border-right: 17px solid transparent;
    border-bottom: 17px solid #677a8e;
    margin-right: 5px;
}

.search-bar-attendance-muster {
    display: flex;
    padding: 0;
    justify-content: start;
    width: 250px; /* Adjust width as needed */
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
}
/* Styling for the input */
.search-bar-attendance-muster input[type="search"] {
    flex: 1;
    padding: 5px;
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
}
/* Styling for the search icon */
.search-bar-attendance-muster::after {
    content: "\f002"; /* Unicode for the search icon (font-awesome) */
    font-size: 16px;
    padding: 5px;
    color: #999; /* Icon color */
    cursor: pointer;
}

/* Styling for the search icon (optional) */
.search-bar-attendance-muster input[type="search"]::placeholder {
    color: #999; /* Placeholder color */
}

.search-bar-attendance-muster
    input[type="search"]::-webkit-search-cancel-button {
    display: none; /* Hide cancel button on Chrome */
}
.summary-attendance-muster {
    border: 1px solid #ccc;
    background: #ebf5ff;
    padding: 0;
}
.legendsIcon {
            padding: 1px 6px;
            font-weight: 500;
        }
        .absentIcon {
            border: 1px solid #6c757d;
        }
.Attendance-attendance-muster {
    border: 1px solid #ccc;
    background: #ebf5ff;
    padding: 0;
    max-width: 800px;
    overflow-x: auto;
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #dce0e5; /* For Firefox */
}

/* For Webkit-based browsers (Chrome, Safari, Edge) */
.Attendance-attendance-muster::-webkit-scrollbar {
    width: 2px; /* Width of the scrollbar */
    height: 8px;
}

/* Track (the area where the scrollbar sits) */
.Attendance-attendance-muster::-webkit-scrollbar-track {
    background: #fff; /* Background color of the track */
}

/* Handle (the draggable part of the scrollbar) */
.Attendance-attendance-muster::-webkit-scrollbar-thumb {
    background: #dce0e5; /* Color of the scrollbar handle */
    border-radius: 2px; /* Border radius of the handle */
}

/* Handle on hover */
.Attendance-attendance-muster::-webkit-scrollbar-thumb:hover {
    background: #dce0e5; /* Color of the scrollbar handle on hover */
}

.Attendance-attendance-muster th,
.Attendance-attendance-muster td {
    width: auto;
    white-space: nowrap; /* Prevent content from wrapping */
}
.table {
    background: #fff;
    margin: 0;
}

td {
    font-size: 0.795rem;
}
/* .table tbody td {
    border-right: 1px solid #d5d5d5;
} */

/* Remove right border for the last cell in each row to avoid extra border */
.summary-attendance-muster.table tbody tr td:last-child {
    border-right: none;
    background: #f2f2f2;
}
.summary, .Attendance {
        margin: 0; /* Remove any default margin */
        padding: 0; /* Add some padding if needed */
    }
.Attendance-attendance-muster .table tbody tr td:last-child {
    border-right: none;
}
.Attendance
{
    max-width: 800px;
    overflow-x: auto;
    scrollbar-width: thin;
}
.dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-button {
        padding: 10px;
        background-color: #f1f1f1;
        border: none;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropdown-button {
        background-color: #ddd;
    }
    .dropdown-container {
        width: 100%; /* Ensure the container spans full width */
        text-align: right; /* Align content to the right */
    border-radius: 5px;
    padding: 5px;
    margin-left:930px;
   
}

.dropdown-right {
    appearance: none; /* Removes the default arrow */
    background-color: white; /* White background */
    border: 1px solid #d1d1d1; /* Light border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px 15px; /* Space around the text */
    font-size: 12px; /* Font size */
    font-family: Arial, sans-serif; /* Font family */
    color: #333; /* Text color */
    cursor: pointer; /* Pointer on hover */
    width: 200px; /* Width of the dropdown */
    padding-right: 30px; /* Space for the arrow */
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"%3E%3Cpath fill="%23000" d="M0 0L2 2L4 0z"/%3E%3C/svg%3E'); /* Arrow pointing down */
    background-repeat: no-repeat;
    background-position: center right 8px; /* Position the arrow at the bottom */
    background-size: 12px; /* Adjust the size of the arrow */
    margin-left: 10px; /* Align the dropdown */
    float: right;
    margin-top: 5px;
}

.dropdown-right:hover {
    border-color: #a9a9a9;
}

.dropdown-right:focus {
    outline: none;
    border-color: #007bff;
}

.dropdown-right option {
    padding: 8px;
}

/* Styling the container to give similar height and width */
.dropdown-container {
    display: inline-block;
    height: 40px;
    width: auto;

 
}
.dropdown-right-for-month {
    appearance: none; /* Hide the native dropdown arrow */
    -webkit-appearance: none; /* Hide the arrow in Safari */
    -moz-appearance: none; /* Hide the arrow in Firefox */
    padding: 10px 40px 10px 15px; /* Padding to make space for the arrow */
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: white;
    font-size: 14px;
}
.arrow-for-employee1{
    position: absolute;
    right: 15px;
    margin-top:65px;
    transform: translateY(-50%);
    width: 11px;
    height: 11px;
    pointer-events: none;
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"%3E%3Cpath fill="%23000" d="M0 0L2 2L4 0z"/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-size: contain;
}

.dropdown-right-for-month:hover {
    border-color: #a9a9a9; /* Darker border on hover */
}

.dropdown-right-for-month:focus {
    outline: none; /* Remove focus outline */
    border-color: #007bff; /* Blue border on focus */
}

/* Optional: Set margin for positioning */
select {
    margin-top: 40px;
    margin-left: 10px;
}
.dropdown-for-employee{
    position: relative; /* Position relative for dropdown positioning */
    display: inline-block; /* Align with other elements */
}
.dropdown-button-for-employee {
    background-color: white; /* White background */
    color: #333; /* Text color */
    padding: 10px 15px; /* Space around text */
    border: 1px solid #d1d1d1; /* Light border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer on hover */
    display: flex; /* Flexbox for alignment */
    align-items: center; /* Center items vertically */
    margin-top: 40px;
}
.arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
.dropdown-content-for-employee {
    display: none; /* Hidden by default */
    position: absolute; /* Positioned absolutely */
    background-color: white; /* White background */
    min-width: 160px; /* Minimum width */
    border: 1px solid #d1d1d1; /* Light border */
    z-index: 1; /* On top of other elements */
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Shadow for dropdown */
}
.dropdown-for-employee:hover .dropdown-content-for-employee {
    display: block; /* Show on hover */
}
.dropdown-content-for-employee a {
    color: #333; /* Text color */
    padding: 12px 16px; /* Space around items */
    text-decoration: none; /* No underline */
    display: block; /* Block display */
}

/* Change background on hover */
.dropdown-content-for-employee a:hover {
    background-color: #f1f1f1; /* Light gray background on hover */
}
.custom-dropdown {
    position: relative;
    display: inline-block;
    width: fit-content;
}
.summary
{
    margin-top: 12px;
}
.Attendance
{
    margin-top: 12px;
    flex:1;
}

    </style>
    @php
      $present=0;
      $isHoliday=0;
      $leaveTake=0;
    @endphp
        <div class="attendance-overview-help">
                @if($showHelp)
                    <div style="margin-top:10px;">
                            <p>The<span style="font-weight: bold;"> Attendance Muster</span> page enables you to view monthly attendance data of employees and information specific to type of leave available and other holiday information.Click a particular day and type the associated letter as displayed in the legend apperaring at the bottom of the page.</p>
                            <p>Explore greytHR by<span style="color: #1fb6ff;cursor:pointer;"> Help-Doc</span> ,watching <span style="color: #1fb6ff;cursor:pointer;"> How-to Videos</span> and<span style="color: #1fb6ff;cursor:pointer;"> FAQ</span>.</p>
                    </div>        
                    <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
                @else    
                          sdfghjbkm,./
                @endif
        </div>
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

        <div style="display:flex;flex-direction:row;justify-content:space-between; align-items:center;margin-left: 15px;">
        <div class="gap-4" style="display:flex;flex-direction:row;">
            
            <div class="custom-dropdown">
                    <select class="dropdown-content-for-employee" style="font-size:12px;margin-top:40px; margin-left:10px;" wire:model="selectedMonth" wire:change="updateselectedMonth">
                        @foreach($months as $month)
                            <option value="{{ $month['value'] }}">
                                {{ $month['name'] }} {{ $selectedYear }}
                            </option>
                        @endforeach
                    </select>
                    <span class="arrow-for-employee1"></span>
            </div>
            
           <div class="dropdown-for-employee">
                    <button class="dropdown-button-for-employee"><span style="font-size:12px;">Employee: {{ ucfirst($selectedOption) }}</span> <span class="arrow-for-employee"></span></button>
                    <div class="dropdown-content-for-employee">
                        <a href="#" wire:click.prevent="updateSelected('all')">All</a>
                        <a href="#" wire:click.prevent="updateSelected('current')">Current</a>
                        <a href="#" wire:click.prevent="updateSelected('past')">Past</a>
                        <a href="#" wire:click.prevent="updateSelected('intern')">Intern</a>
                    </div>
          </div>

    <!-- Display the selected employee type dynamically -->
   

    <!-- Display the selected employee type dynamically -->
            
        </div>
        
    <button style="border:1px solid blue;margin-right:40px;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;"wire:click="downloadExcel">
        <span style="font-size:12px;">Export Excel</span>
    </button>
    
</div>
    <div class="m-3 mt-4 row d-flex" style="margin-top:20px;">
    <div class="summary col-md-3">
        
        <table class="table">
            <thead>
                <tr>
                    <th style="width:75%;background:#ebf5ff;color:#778899;font-weight:500;line-height:2;font-size:0.825rem;border-right:1px solid #ddd;">
                        Employee Name</th>
                    
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>
            @foreach ($employees as $emp)
            
           
           
                <!-- Add table rows and data for Summary -->
                
              
                
                <tr style="border-right: 1px solid #ddd;height:30px;margin-top:20px;">

                    <td  style="max-width: 200px;font-weight:400; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ucwords(strtolower($emp->first_name))}}&nbsp;{{ucwords(strtolower($emp->last_name))}}<span class="text-muted">(#{{$emp->emp_id}})</span><br /><span class="text-muted" style="font-size:11px;">{{$emp->job_role}},{{$emp->job_location}}</span>
                    </td>
                   
                  
                    

                   
                    

                   
                    


                </tr>
               
                
                
                
                @endforeach   
                <!-- Add more rows as needed -->
            </tbody>

        </table>
    </div>
    <div class="Attendance col-md-9">
        
        
        
        <table class="table">
            @php
            // Get current month and year
            $currentMonth =$selectedMonth;
            // Total number of days in the current month
            
            // Set a default value if $attendanceYear is not set
            $currentYear = $selectedYear;
            

            // Total number of days in the current month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

            @endphp

            <thead>
                <tr>

                    @for ($i = 1; $i <= $daysInMonth; $i++)
                        @php
                        $timestamp=mktime(0, 0, 0, $currentMonth, $i, $selectedYear);
                        $dayName=date('D', $timestamp); // Get the abbreviated day name (e.g., Sun, Mon)
                        $fullDate=date('Y-m-d', $timestamp); // Full date in 'YYYY-MM-DD' format
                        @endphp
                        <th style="width:75%; background:#ebf5ff; color:#778899; font-weight:500; text-align:center;">
                        <div style="font-size:0.825rem;line-height:0.8;font-weight:500;">
                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div style="margin-top:-5px; font-size:0.625rem;margin-top:1px;">{{ $dayName }}</div>
                        </th>

                        @endfor
                </tr>
            </thead>

            <tbody>
                <!-- Add table rows and data for Attendance -->
                




                

                @php
                $currentYear = $selectedYear;
                @endphp
                @foreach($employees as $emp)
                <tr style="height:57px;;background-color:#fff;">
                    @php
                    // Get the current day of the month
                    $currentYear =$selectedYear;
                    $currentDay = $daysInMonth;

                    // Check if $attendanceYear is greater than the current year
                    
                    @endphp

                    @for ($i = 1; $i <= $currentDay; $i++)
                        @php
                        $timestamp=mktime(0, 0, 0, $selectedMonth, $i, $selectedYear);
                        $dayName=date('D', $timestamp); // Get the abbreviated day name (e.g., Sun, Mon)
                        $fullDate=date('Y-m-d', $timestamp); // Full date in 'YYYY-MM-DD' format
                        @endphp
                        <td style="border-right: 1px solid #d5d5d5;">


                        @foreach ($DistinctDatesMap as $empId => $distinctDates)
                                                 @if($empId==$emp->emp_id)

                                                          @php

                                                                        foreach ($distinctDates as $distinctDate) {

                                                                        // Extract date part from created_at and distinctDate

                                                                        $createdAtDate = date('Y-m-d', strtotime($emp->created_at));


                                                                        // Your logic for each distinct date
                                                                        if ($distinctDate === $fullDate) {

                                                                        $present=1;

                                                                        }
                                                                        }
                                                                    @endphp

                                                            @endif
                                                            @endforeach
                                                            @foreach($holiday as $h)

                        @if($h==$fullDate)

                        @php
                        $isHoliday=1;
                        break;
                        @endphp
                        @endif

                        @endforeach
                        
                        @foreach($ApprovedLeaveRequests1 as $empId => $leaveDetails)


                        @if($empId==$emp->emp_id)
                        <p>
                            @php
                            foreach ($leaveDetails['dates'] as $date)
                            {
                            if($date == $fullDate)
                            {
                            $leaveTake=1;

                            }
                            }
                            @endphp
                        </p>

                        @endif

                        @endforeach


                        

                        
                       

                        
                        @if($fullDate <= $todaysdate)
                        
                        @if ($dayName === 'Sat' || $dayName === 'Sun')
                        <p style="color:#666;font-weight:500;">OFF</p>
                        @elseif($emp->employee_status=='resigned'||$emp->employee_status=='terminated')
                        <p class="me-2 mb-0">
                             <span class="legendsIcon absentIcon">?</span>
                        </p>
                        @elseif($isHoliday==1)
                        <p style=" color:#666;font-weight:500;">H</p>
                        @elseif($leaveTake==1)
                        <p style=" color:#666;font-weight:500;">L</p>
                        @elseif($present==1)
                        <p style=" color:#666;font-weight:500;">P</p>


                        @else

                        <p style=" color: #666;font-weight:500;">A</p>
                        @endif 
                        @else
                          <p style="text-align:center;font-weight:500;">-</p>
                        @endif

                        </td>

                       @php
                         $present=0;
                         $isHoliday=0;
                         $leaveTake=0;
                       @endphp

                        @endfor

                      



                        </tr>
                       @endforeach
                        
                        
                        
                      

            </tbody>
        </table>
    </div>
</div>
</div>
