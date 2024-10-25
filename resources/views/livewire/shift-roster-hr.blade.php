<div>
     <style>
         .attendance-overview-help {
                position: relative;
                width: 95%; /* Set your desired width */
                height: auto; /* Set your desired height */
                border-radius: 5px; /* Set your desired border-radius */
                border: 1px solid #ccc; /* Add border if needed */
                padding: 10px; /* Add padding if needed */
                margin: 20px 10px; /* Add margin if needed */
                background-color: #f3faff; /* Set background color if needed */
                font-size: 12px;
            }
            .hide-attendance-help {
                margin-top:50px;
                position: absolute;
                bottom: 80px;
                right: 10px;
                color: #0000FF;
                font-weight:500;
                cursor: pointer;
            }
            .custom-dropdown {
    position: relative;
    display: inline-block;
    width: fit-content;
}
.dropdown-right-for-month {
    appearance: none; /* Hide the native dropdown arrow */
    -webkit-appearance: none; /* Hide the arrow in Safari */
    -moz-appearance: none; /* Hide the arrow in Firefox */
    padding: 10px 40px 10px 15px; /* Padding to make space for the arrow */
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: white;
    font-size: 12px;
}
.dropdown-right-for-month:hover {
    border-color: #a9a9a9; /* Darker border on hover */
}

.dropdown-right-for-month:focus {
    outline: none; /* Remove focus outline */
    border-color: #007bff; /* Blue border on focus */
}
.dropdown-for-employee{
    position: relative; /* Position relative for dropdown positioning */
    display: inline-block; /* Align with other elements */
}
.dropdown-for-employee:hover .dropdown-content-for-employee {
    display: block; /* Show on hover */
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
.dropdown-content-for-employee a {
    color: #333; /* Text color */
    padding: 12px 16px; /* Space around items */
    text-decoration: none; /* No underline */
    display: block; /* Block display */
}
.dropdown-content-for-employee a:hover {
    background-color: #f1f1f1; /* Light gray background on hover */
}
.dropdown-button-for-employee {
    font-size: 12px;
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

.Attendance {
    border: 1px solid #ccc;
    background: #ebf5ff;
    overflow-y: hidden;
    margin-left: 600px;
    padding: 0;
    max-width: 48%;
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: #dce0e5;
    height: auto;
    margin-top: -220px;
}
.summary {
    border: 1px solid #ccc;
    background: #ebf5ff;
    padding: 0;
    height: 210px;
    margin: 10px;
}
 
        .summary .table tbody tr td:last-child {
            border-right: none;
            background: #f2f2f2;
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
.arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
.summary .table tbody tr td:last-child {
            border-right: none;
            background: #f2f2f2;
        }

     </style>
     @php
         $isHoliday=0;
        $daysInMonth=cal_days_in_month(CAL_GREGORIAN, $this->selectedMonth, $this->currentYear);
     @endphp
     <div class="attendance-overview-help">
                
                    <div style="margin-top:10px;">
                            <p>The<span style="font-weight: bold;"> Shift Roster </span>gives you an overview of the existing planned shifts of the employees. The changes made on this page reflect throughout the <span style="font-weight:bold;">Attendance </span>module.</p>
                            <p>Explore greytHR by<span style="color: #1fb6ff;cursor:pointer;"> Help-Doc, </span> To watch the video on Shift Override, <span style="color: #1fb6ff;cursor:pointer;">click here,</span> and to watch the video on managing employee shifts,<span style="color: #1fb6ff;cursor:pointer;"> click here</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    </div>        
                    <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
                
        </div>
        <div style="display:flex;flex-direction:row;justify-content:space-between; align-items:center;">
        <div class="gap-2"style="display:flex;flex-direction:row;">
            
            <div class="custom-dropdown">
                    <select class="dropdown-right-for-month" style="margin-top:42px; margin-left:10px;" wire:model="selectedMonth" wire:change="updateselectedMonth">
                        @foreach($months as $month)
                            <option value="{{ $month['value'] }}">
                                {{ $month['name'] }} {{ $currentYear }}
                            </option>
                        @endforeach 
                    </select>
                    <span class="arrow-for-employee1"></span>
            </div>
            
           <div class="dropdown-for-employee">
                    <button class="dropdown-button-for-employee"><span style="font-size:12px;">Employee: {{$selectedOption}}</span> <span class="arrow-for-employee"></span></button>
                    <div class="dropdown-content-for-employee">
                        <a href="#" wire:click.prevent="updateSelected('All')">All</a>
                        <a href="#" wire:click.prevent="updateSelected('Current')">Current</a>
                        <a href="#" wire:click.prevent="updateSelected('Past')">Past</a>
                        <a href="#" wire:click.prevent="updateSelected('Intern')">Intern</a>
                    </div>
          </div>

    <!-- Display the selected employee type dynamically -->
   

    <!-- Display the selected employee type dynamically -->
            
        </div>
        <div class="gap-2" style="display:flex;flex-direction:row;">
                <button style="border:1px solid blue;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:12px;"wire:click="downloadExcelForShiftRoster">
                    <span styl="font-size:12px;">Export Excel</span>
                </button>
                <button style="border:1px solid blue;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;" wire:click="refresh">
                   <i class="ph-arrow-clockwise"></i>
                </button>
        </div>        
        </div>
        <div class="m-0 mt-3 row">
           <div class="summary col-md-6">
           <table class="table">
            <thead>
                <tr>
                    <th style="padding:12px 10px;background:#ebf5ff;color:#778899;font-weight:500;font-size:0.825rem;white-space:nowrap;">
                        Employee No</th>
                    <th style="padding:12px 10px;background:#ebf5ff;color:#778899;font-weight:500;font-size:0.8255rem;white-space:nowrap;">
                    Employee Name</th>
                    <th style="padding:12px 10px;background:#ebf5ff;color:#778899;font-weight:500;font-size:0.8255rem;">
                    Working Days</th>
                    <th style="padding:12px 10px;background:#ebf5ff;color:#778899;font-weight:500;font-size:0.8255rem;">
                    OFF</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>

            <tbody>
                <!-- Add table rows and data for Summary -->


                



               @foreach ($employees as $emp)
                <tr>
                    <td style="padding:5px 5px;font-size:12px;text-align:center;padding-right:25px;">{{$emp->emp_id}}</td>
                    <td style="padding:5px 5px;max-width: 10px;font-weight:400; overflow: hidden;text-align:center;padding-right:25px; text-overflow: ellipsis; white-space: nowrap;font-size:12px;"data-toggle="tooltip"
                    data-placement="top" title="{{ ucwords(strtolower($emp->first_name)) }} {{ ucwords(strtolower($emp->last_name)) }}">
                    {{ucwords(strtolower($emp->first_name))}}&nbsp;{{ucwords(strtolower($emp->last_name))}}<br /><span class="text-muted" style="font-size:11px;"data-toggle="tooltip"
                    data-placement="top" title="{{$emp->job_role}},{{$emp->job_location}}">{{$emp->job_role,}},{{$emp->job_location}}

                        </span>
                    </td>




                    @php
                      
                       $dateCount=0;
                    @endphp
                    @for ($i = 1; $i <= $daysInMonth; $i++) 
                          @php 
                             $timestamp=mktime(0, 0, 0, $selectedMonth, $i, $currentYear); 
                             $dayName=date('D', $timestamp); // Get the abbreviated day name (e.g., Sun, Mon) 
                             $fullDate=date('Y-m-d', $timestamp); // Full date in 'YYYY-MM-DD' format 
                          @endphp 
                          @if($dayName==='Sat' || $dayName==='Sun' ) 
                                @php 
                                   $dateCount+=1; 
                                @endphp 
                          @endif 
                    @endfor 
                          
                    @php 
                        $noofregulardays=$daysInMonth-$dateCount-$count_of_holiday 
                    @endphp
                    <td style="padding:5px 5px;font-size:12px;text-align:center;padding-right:15px;">{{$noofregulardays}}</td>
                    
                    <td style="padding:5px 5px;font-size:12px;text-align:center;">{{$dateCount}}</td>
                </tr>
                @endforeach

                

                <!-- Add more rows as needed -->
            </tbody>

        </table>
           </div>
           <div class="Attendance col-md-9">


<table class="table">
   

    <thead>
        <tr>

            @for ($i = 1; $i <= $daysInMonth; $i++) 
                  @php 
                     $timestamp=mktime(0, 0, 0, $selectedMonth, $i, $currentYear); 
                     $dayName=date('D', $timestamp); // Get the abbreviated day name (e.g., Sun, Mon) 
                     $fullDate=date('Y-m-d', $timestamp); // Full date in 'YYYY-MM-DD' format 
                  @endphp 
                <th style="padding: 8px 10px;background:#ebf5ff; color:#778899; font-weight:500; text-align:center;">
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

        


       @foreach($employees as $emp)
        <tr style="background-color:#fff;">
            @if($emp->employee_status=='active')
                @for ($i = 1; $i <= $daysInMonth; $i++)
                
                        @php
                            $timestamp=mktime(0, 0, 0, $selectedMonth, $i, $currentYear);
                            $dayName=date('D', $timestamp); // Get the abbreviated day name (e.g., Sun, Mon)
                            $fullDate=date('Y-m-d', $timestamp); // Full date in 'YYYY-MM-DD' format
                        @endphp
                  
                        <td style="padding:2px 5px;background-color: {{ in_array($dayName, ['Sat', 'Sun']) ? '#f2f2f2' : '#fff' }};">
                            @foreach($holiday as $h)

                                        @if($h->date==$fullDate)

                                            @php
                                                $isHoliday=1;
                                                break;
                                            @endphp
                                        @endif

                                @endforeach





                @if ($dayName === 'Sat' || $dayName === 'Sun')
                <p style="font-weight:500;padding:3px;font-size:12px;"data-toggle="tooltip"
                data-placement="top" title="Off Day">O</p>
  
                @elseif($isHoliday==1)
                        <p style="font-weight:500;padding:3px;font-size:12px;text-align:start;"data-toggle="tooltip"
                        data-placement="top" title="Holiday">H</p>

                
                @else

                    <p style="font-weight:500;padding:4px;font-size:12px;"data-toggle="tooltip"
                    data-placement="top" title="General Shift">GS</p>
                @endif

                </td>


                
                

                       @php
                          $isHoliday=0;

                        @endphp
                @endfor
                @else
                   <td colspan="35"style="padding:11px;text-align:center;color:#f66;background-color:#fcf0f0;">Shift Details Not Available</td>

                @endif
           
        </tr>
        @endforeach

        

    </tbody>
</table>
</div>
        </div>
</div>
