<div>
   <style> 
      .table-1 tbody td {
            width: 75px;
            height: 80px;
            border-color: #c5cdd4;
            font-weight: 500;
            font-size: 13px;
            /* Adjust font size as needed */
            vertical-align: top;
            position: relative;
            text-align: left;
        }

        .table-1 thead {
            border: none;
        }

        .table-1 th {
            text-align: center;
            /* Center days of the week */
            height: 15px;
            border: none;
            color: #778899;
            font-size: 12px;
        }
        .nav-btn {
    height: 30px; /* Set the desired height */
    width: 30px; /* Set the desired width */
    border: none; /* Optional: remove default button border */
    border-radius: 4px; /* Optional: add rounded corners */
    background-color: #f0f0f0; /* Optional: set a background color */
    color: #333; /* Button text color */
    font-size: 16px; /* Text size inside the button */
    font-weight: bold; /* Make the text bold */
    text-align: center; /* Center align the text */
    cursor: pointer; /* Show pointer cursor on hover */
    line-height: 30px; /* Align text vertically */
    margin: 0 5px; /* Add space between buttons */
}

.nav-btn:hover {
    background-color: #d9d9d9; /* Optional: change background on hover */
}


        .table-1 {
            overflow-x: hidden;
        }
        .calendar-heading-container {
            border:1px solid #778899;
            padding: 10px 10px;
            display: flex;
            justify-content: space-between;
            /* Add spacing between heading and icons */
        }
        .calendar-body
        {
            width:100%;
        }

        .calendar-heading-container h5 {
            font-size: 0.975rem;
            color: black;
            font-weight: 500;
        }

        .dropdown-for-employee{
    position: relative; /* Position relative for dropdown positioning */
    display: inline-block; /* Align with other elements */
    margin-top: -40px;
}
.dropdown-for-employee:hover .dropdown-content-for-employee {
    display: block; /* Show on hover */
}
.dropdown-button-for-employee:hover
{}
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
.arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
.full-width
{
    width:100%;
}
.partial-width
{
    width:88%;
}
.employee-container {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 14px;
                color: #333;
                background-color: #d9edf7;
                border:1px solid #bce8f1;
                color: #3a87ad;
        }
   </style>
<div class="attendance-overview-help">
                   
                   <p>The <span style="font-weight:bold;">Shift Rotation Calendar</span> page enables you to set up the Shift Calendar (also known as the production plan).To do so, click a <br/>day in the calendar and make the required changes to the shift in the table that appears to the right. Changes made to the shift<br/> calendar affect the attendance of employee(s). </p>
                   <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                   <span class="hide-attendance-help"wire:click="hideHelp"style="bottom:100px;">Hide Help</span>
   </div>
   <div class="form-group d-flex align-items-center" style="max-width: 300px; margin: auto; gap: 10px;">
    <label for="shift_rotation_plan" class="form-label" style="font-weight: bold; color: #555; margin-bottom: 0;">
        Shift Rotation <br/>Plan
    </label>
    <div class="dropdown-for-employee">
                    <button class="dropdown-button-for-employee"><span style="font-size:12px;">{{$shiftRotationPlan}}</span> <span class="arrow-for-employee"></span></button>
                    <div class="dropdown-content-for-employee">
                        <a href="#" wire:click.prevent="updateSelected('General')">General</a>
                        <a href="#" wire:click.prevent="updateSelected('Afternoon')">Afternoon</a>
                        <a href="#" wire:click.prevent="updateSelected('Evening')">Evening</a>
                       
                    </div>
          </div>
</div>


   <div class="col-12 col-md-7 m-0 p-1 calendar custom-scrollbar"style="margin-left:120px;">
               <div style="margin-left:40px;margin-top:50px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="calendar-heading-container  {{ $shiftRotationPlan === 'General' ? 'full-width' : 'partial-width' }}">
                        <button wire:click="beforeMonth" class="nav-btn">&lt;</button>
                        <p style="font-size: 14px;color:black;font-weight:500;margin-bottom:0;">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
                        <button wire:click="nextMonth" class="nav-btn">&gt;</button>
                    </div>
                </div>
                <!-- Calendar -->
                <div class="table-responsive">
                    <table class="table-1 table-bordered">
                        <thead class="calender-header">
                            <tr>
                                <th class="text">Sun</th>
                                <th class="text">Mon</th>
                                <th class="text">Tue</th>
                                <th class="text">Wed</th>
                                <th class="text">Thu</th>
                                <th class="text">Fri</th>
                                <th class="text">Sat</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                            @if(!empty($calendar))
                            @foreach($calendar as $week)
                            <tr>
                                @foreach($week as $day)
                                @php
                                $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $day['day']);

                                $formattedDate = $carbonDate->format('Y-m-d');
                                $formattedDate1 = $carbonDate->format('Y-m-d');
                                $isCurrentMonth = $day['isCurrentMonth'];
                                $isWeekend = in_array($carbonDate->dayOfWeek, [0, 6]); // 0 for Sunday, 6 for Saturday
                                
                                @endphp


                                
    <td class="attendance-calendar-date">
                                    <div wire:click="selectDate('{{$day['date']}}')">

                                       
                                                <div style="margin:-3px;height: 45px;display: flex; justify-content: center; align-items: center;position: relative;">
                                                        
                                                         <span style="position: absolute; left: 2px;top:2px;color:{{$day['isCurrentMonth']==true ? '#3b4452':'#778899'}}">  {{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}</span>
                                                        
                                                        
                                                </div>
                                                <div style="margin:-3px;height: 45px;display: flex; justify-content: center; align-items: center;position: relative;">
                                                   <span style="text-align:center; color:{{$day['isCurrentMonth']== true ? '#3b4452':'#7f8fa4'}} ; padding-right:30px; margin-left: 37px;white-space: nowrap;padding-bottom:50px;"title=" {{ $shiftRotationPlan === 'General' ? 'General' : '' }}"> {{ $shiftRotationPlan === 'General' ? 'GEN' : '' }}</span>
                                                </div>                                                            

                                          
                                       
                                            
                                                

                                       
                                        


                                        

                                        

                                        
                                        
                                    </div>
                                    
                                    </td>

                                    @endforeach
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                    @else
                    <p>No calendar data available</p>
                    @endif
                    @if($openDatePopUp)
                        <div style="position: absolute;top: 600px;left: 750px; width: 400px; height:230px;background: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2); padding: 20px; z-index: 999;">
                            <div class="employee-container"style="margin-left:86px;">
                                <span id="employeeName">{{\Carbon\Carbon::parse($selectedDate)->format('jS F Y')}}</span>
                            </div>
                            <div class="d-flex flex-column align-items-center gap-4" style="margin-top: 10px; max-width: 300px;">
                            
                            <div class="form-group d-flex align-items-center" style="max-width: 300px; margin: auto; gap: 10px;">
                                <label for="shift_rotation_plan" class="form-label" style="font-weight: bold; color: #555; margin-bottom: 0;">
                                    Shift Type
                                </label>
                                <div class="dropdown-for-employee">
                                                <button class="dropdown-button-for-employee"><span style="font-size:12px;">{{$selectedShiftRotationPlan}}</span> <span class="arrow-for-employee"></span></button>
                                                <div class="dropdown-content-for-employee">
                                                    <a href="#" wire:click.prevent="updateSelectedShiftType('General')">General</a>
                                                    <a href="#" wire:click.prevent="updateSelectedShiftType('Afternoon')">Afternoon</a>
                                                    <a href="#" wire:click.prevent="updateSelectedShiftType('Evening')">Evening</a>
                                                
                                                </div>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center w-100">
                                <label for="day_type" class="form-label" style="font-weight: bold; color: #555; margin-bottom: 0; flex: 1;">
                                    Day Type
                                </label>
                                <div class="dropdown-for-employee" style="position: relative; flex: 2;">
                                    <button class="dropdown-button-for-employee w-100" style="text-align: left; display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border: 1px solid #ddd; background-color: #fff; border-radius: 4px; cursor: pointer;">
                                        <span style="font-size: 14px;">{{$dayType}}</span>
                                        <span class="arrow-for-employee"></span>
                                    </button>
                                    <div class="dropdown-content-for-employee" style="position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000; width: 100%;">
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Regular')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Regular</a>
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Rest Day')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Rest Day</a>
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Off Day')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Off Day</a>
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Holiday')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Holiday</a>
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Half Day')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Half Day</a>
                                        <a href="#" wire:click.prevent="updateSelectedDayType('Plant ShutDown')" style="display: block; padding: 8px 12px; font-size: 14px; text-decoration: none; color: #333;">Plant ShutDown</a>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="savePopup" style="display: block; margin: -2px 20px 0; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Save</button>
                        </div>
                    @endif
                </div>

                
                
                </div>
                
            </div>
            
</div>
