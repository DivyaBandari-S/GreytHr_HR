<div>
    <style>
        .dropdown-right
        {
            float: right;
            margin-right:10px;
        }
        .arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
        .dropdown-for-employee{
            position: relative; /* Position relative for dropdown positioning */
            display: inline-block; /* Align with other elements */
        }
        .dropdown-for-employee:hover {
               display: block; /* Show on hover */
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
    </style>    
                <div class="attendance-overview-help">
                    
                    <p>The <span style="font-weight:bold;">Employee Week Days</span> page allows you to override the weekdays for your employees. The page displays a list of your employees' overridden weekdays <br/> in a tabular format. Normally, Monday to Friday are the working days, and Saturday-Sunday is off days.<br/>
                    The <span style="font-weight:bold;">Add</span> button enables you to specify a working pattern for a week. The From and To date indicates the time period for which this pattern is applicable.</p>
                    <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    
                    <span class="hide-attendance-help-for-employee-week-day" style="margin-top:-70px;" wire:click="hideHelp">Hide Help</span>
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
    <div style="display:flex;flex-direction:row;justify-content:space-between; align-items:center;margin-left: 45px;">
        <div class="gap-4" style="display:flex;flex-direction:row;">
            <div class="dropdown-for-employee">
                        <button class="dropdown-button-for-employee"><span style="font-size:12px;">Employee: {{ ucfirst($selectedOption) }}</span> <span class="arrow-for-employee"></span></button>
                        <div class="dropdown-content-for-employee">
                            <a href="#" wire:click.prevent="updateSelected('all')">All</a>
                            <a href="#" wire:click.prevent="updateSelected('current')">Current</a>
                            <a href="#" wire:click.prevent="updateSelected('past')">Past</a>
                            <a href="#" wire:click.prevent="updateSelected('intern')">Intern</a>
                        </div>
            </div>
            
        </div>
         
    </div>
    <button style="border:1px solid blue;margin-right:15px;margin-top:-40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="downloadExcel">
                      <span style="font-size:12px;">Add</span>
        </button>       
</div>
