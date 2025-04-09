<div>
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
        .yellow-container {
            background-color: #FEEFAD;
            border:1px solid #F3CA52;
            border-radius: 5px;
            width:85%; /* You can adjust this to your desired width */
            height: auto; /* You can adjust this to your desired height */
            margin: -1px 10px; /* This centers the container horizontally */
            display: block; /* Ensures the container takes up the full width specified */
        }
        .yellow-container p{
            margin-top: 10px;
            padding-left: 20px;
            color:#DD761C;
        }
        .button-container {
            display: flex;
            justify-content: flex-end; /* Aligns buttons to the start */
            float: right;
            width: 50%; /* Adjust this value to change the container width */
            margin: 20px auto; /* Centers the container and adds some margin */
           
        }
        .button-container button {
            border-radius:8px;
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 16px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .export-to-excel-button{
            border:1px solid  #306cc6;
            background-color: #fff;
            color:#306cc6;
        }
        .add-employee-button{
            border:1px solid  #306cc6;
            background-color: #306cc6;
            color:#fff;
            border-radius:8px;
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 16px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .button-container button:last-child {
            margin-right: 0; /* Removes margin from the last button */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #d3dadf; /* Sets the background color of the table headers to pink */
            font-weight: 500;
            font-size: 12px;
            border-right:1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .custom-select {
                height: 1rem; /* Increase the height of the dropdown */
                padding: 0.5rem;
                font-size: 1.2rem; /* Increase the font size */
                width: 150px;
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
.dropdown-content-for-employee {
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
.attendance-overview-help-for-showhelp
{
    position: relative;
            width: 85%; /* Set your desired width */
            height: auto; /* Set your desired height */
            border-radius: 5px; /* Set your desired border-radius */
            
            padding: 10px; /* Add padding if needed */
            margin: 20px 10px; /* Add margin if needed */
           
}
    </style>
    @php
       $s1=0;
    @endphp
       
      
        @if($showHelp==false) 
        <div class="attendance-overview-help">
            <p style="font-size:13px;">The employee directory lists all employee in your organisation.<br/>Filters are available to select employees in a certain category(location,department, etc.) or by status.</p>
            <p style="font-size:13px;">Explore HR Expert by <a href="/knowledge-base"style="color: #1fb6ff;cursor:pointer;">&nbsp;FAQ&nbsp;</a>.</p>
            <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
        </div>    
        @else 
        <div class="attendance-overview-help-for-showhelp">
           <button style="font-size:14px;background-color: white; margin-top: -20px; float: right; color: #0000FF; border: 1px solid #ffff; border-radius: 5px; cursor: pointer; padding: 5px 20px;font-weight:bold;"wire:click="showhelp">Show&nbsp;&nbsp;Help</button>
        </div> 
        @endif    
      
      
   
     
      <div class="yellow-container">
           <p style="font-size:13px;">New! You can now customize your Employee Directory by adding columns,grouping and pivoting,etc.&nbsp;&nbsp;<span style="color: #DD761C;;cursor:pointer;">Go ahead and explore!</span><br/>Please note that we will soon remove the existing Employee Directory</p>
      </div>
      <div class="button-container">
         <button type="button"class="export-to-excel-button"wire:click="exportToExcel">Export to Excel</button>
         <a href="{{ route('add-employee-details') }}" class="add-employee-button">Add Employee</a>

      </div>
      <div style="margin-top:40px;display:flex;flex-direction:row;gap:5px;margin-left:15px;">
           
            <select name="employmentstatus" wire:model="selectedEmploymentStatus" wire:change="updateSelectedEmploymentStatus"

                style="width: 200px; padding-right: 30px;  border-radius: 5px; height: 35px;">

                  <option value="all">All</option>

                            <option value="probation">Probation</option>

                            <option value="confirmed">Confirmed</option>

                            <option value="consultant">Consultant</option>

                            <option value="interns">Interns</option>

                            <option value="resigned">Resigned</option>

                            <option value="active">Active</option>

                            <option value="new_joinee">New Joinee</option>

            </select>
            <select name="employmentfilter" wire:model="selectedEmploymentFilter" wire:change="updateselectedEmploymentFilter"
                style="width: 200px; padding-right: 30px;  border-radius: 5px; height: 35px;">
                <option value="all">All</option>
                <option value="current_employees">Current Employees</option>
                <option value="past_employees">Past Employees</option>
            </select>
            
           
      </div>
 
      
     
    <div class="table-container"style="max-height:300px;overflow-y:auto;width:100%; margin:0;padding:0 10px;">  
      <table id="employee-table">
        <thead style="position:sticky;top:0;">
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Joining Date</th>
                <th>Job Role</th>
                <th>Phone No</th>
                <th>Email</th>
                <th>Extension No</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @if(count($records)>0)    
        @foreach($records as $r1)    
            <tr>
                <td style="font-size:12px;">{{++$s1}}</td>
                <td style="font-size:12px;">{{$r1->emp_id}}</td>
                <td style="text-decoration:underline;font-size:12px;">{{ucwords(strtolower($r1->first_name))}}&nbsp;&nbsp;{{ucwords(strtolower($r1->last_name))}}</td>
                <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($r1->hire_date)->format('jS M, Y')}}</td>
                <td style="font-size:12px;">
                {{
                     preg_replace_callback('/\b\w+\b/', function ($matches) {
                              $word = $matches[0];
                               // Check if the word is a Roman numeral
                              if (preg_match('/^(?:M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})|v?i{0,3})$/', $word)) {
                                       return $word;
                               }
                                       return ucwords($word);
                               }, $r1->job_role)
                }}
                </td>
                <td style="font-size:12px;">{{ str_replace('-', '', $r1->emergency_contact) }}</td>
                <td style="font-size:12px;">{{$r1->email}}</td>
                <td style="font-size:12px;">-</td>
                <td style="font-size:12px;">{{ ucwords(strtolower($r1->employee_status)) }}</td>
            </tr>
        @endforeach   
        @else
          <tr>
            <td colspan="12"style="text-align:center;">No records found</td>
          </tr>   
        @endif     
            <!-- Add more rows as needed -->
        </tbody>
     </table>
     </div>
   
   
         
     
            </div>


