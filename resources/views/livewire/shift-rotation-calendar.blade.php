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
            width: 81%;
            display: flex;
            justify-content: space-between;
            /* Add spacing between heading and icons */
        }

        .calendar-heading-container h5 {
            font-size: 0.975rem;
            color: black;
            font-weight: 500;
        }

   </style>
<div class="attendance-overview-help">
                   
                   <p>The <span style="font-weight:bold;">Shift Rotation Calendar</span> page enables you to set up the Shift Calendar (also known as the production plan).To do so, click a <br/>day in the calendar and make the required changes to the shift in the table that appears to the right. Changes made to the shift<br/> calendar affect the attendance of employee(s). </p>
                   <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                   <span class="hide-attendance-help"wire:click="hideHelp"style="bottom:100px;">Hide Help</span>
   </div>
   <div class="form-group d-flex align-items-center" style="max-width: 300px; margin: auto; gap: 10px;">
    <label for="shift_rotation_plan" class="form-label" style="font-weight: bold; color: #555; margin-bottom: 0;">
        Shift Rotation Plan
    </label>
    <select id="shift_rotation_plan" name="shift_rotation_plan" 
            class="form-select shadow-sm rounded border-primary flex-grow-1" 
            style="font-size: 14px; padding: 10px; background-color: #f8f9fa; color: #333;" 
            wire:model="shiftRotationPlan">
        <option value="weekly" disabled selected>weekly</option>
        <option value="bi-weekly">Bi-Weekly</option>
        <option value="monthly">Monthly</option>
        <option value="custom">Custom</option>
    </select>
</div>


   <div class="col-12 col-md-7 m-0 p-1 calendar custom-scrollbar"style="margin-left:120px;">
               <div style="margin-left:40px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="calendar-heading-container">
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
                                    <div>

                                       
                                                <div style="margin:-3px;height: 45px;display: flex; justify-content: center; align-items: center;position: relative;">
                                                        
                                                         <span style="position: absolute; left: 2px;top:2px;color:{{$day['isCurrentMonth']==true ? '#3b4452':'#778899'}}">{{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}</span>
                                                        
                                                        
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
                </div>

                
                
                </div>
            </div>
</div>
