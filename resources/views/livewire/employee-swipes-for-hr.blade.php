<div>
<div class="position-absolute" wire:loading
        wire:target="updateDate,downloadFileforSwipes">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>
        </div>
    </div>
<style>

.sidebar {
        position: fixed;
        top: 0;
        right: -350px; /* Initially hidden */
        width: 350px;
        height: 100%;
        background: white;
        box-shadow: -2px 0 5px rgba(0,0,0,0.2);
        transition: right 0.3s ease-in-out;
        padding: 20px;
        z-index: 1050;
        border-radius: 10px 0 0 10px;
    }
    .sidebar.active {
        right: 0;
    }
    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
    }
    .sidebar-content {
        margin-top: 15px;
    }
    .button-container {
        display: flex;
        justify-content: center; /* Centers buttons horizontally */
        align-items: center; /* Aligns buttons vertically */
        gap: 5px; /* Reduces the space between buttons */
        margin-top: 20px; /* Adds spacing from elements above */
    }

    .apply-btn, .reset-btn {
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        border: none;
    }

    .apply-btn {
        background-color: #306cc6;
        color: white;
    }

    .reset-btn {
        border:1px solid #306cc6;
        color: #306cc6;
        background-color: #fff;
       
    }
    .category-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px; /* Reduce vertical gap */
        color: #333;
    }

    .custom-dropdown {
        width: 100%;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 0;
    }
    .button-container-for-employee-swipes {
    display: flex;
    justify-content: center;
    padding: 10px 10px;
    margin:10px;
    margin-top:30px;
    /* margin-right: 250px; */
    /* Adjust as needed for alignment */
}
.container-employee-swipes {
    display: flex;
    justify-content: space-between;
}

.employee-swipes-fields {
    display: flex;
    flex-direction: row;
}

.dropdown-container1-employee-swipes-for-download-and-filter {
    display: flex;
    flex-direction: row;
    margin-top: -5px;
}

.container-employee-swipes-right {
    background-color: #fff;
    height: 100vh;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-left: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.search-input-employee-swipes {
    border: none;
    position: relative;
}

.search-input-employee-swipes input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
    font-size: 13px;
    background-color: #fff;
}

.search-icon-employee-swipes {
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    color: var(--label-color);
}

.search-icon-employee-swipes::before {
    font-size: 16px;
}

.employees-swipe-table-scrollbar {
    overflow-y: scroll;
    padding-right: 10px;
    max-height: 300px;
}

.employees-swipe-table-scrollbar::-webkit-scrollbar {
    width: 5px;
    margin-right: 10px;
}

.employees-swipe-table-scrollbar::-webkit-scrollbar-thumb {
    background-color: #888;
}

.employees-swipe-table-scrollbar::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.employee-swipes-table table {
    border-collapse: collapse;
    margin: 0;
    padding: 0;
    width: 100%;
    height: auto;
}

.employee-swipes-table thead {
    background-color:  #306cc6;;
    color: #fff;
}

.employee-swipes-table th,
td {
    padding: 10px;
    /* width: 100%; */
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.employee-swipes-table th {
    font-weight: bold;
    font-size: var(--normal-font-size);
}

.dropdown-container1-employee-swipes input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
    font-size: var(--normal-font-size);
}

.employee-swipes-table td {
    font-size: var(--normal-font-size);
}

.green-section-employee-swipes {
    height: 150px;
    padding: 0;
    margin: 0;
    width: 100%;
    background-color: #ecffdc;
    position: relative;
}

.shift-roster-download-and-dropdown-for-attendance-muster {
    margin-left: 375px;
}

.green-section-employee-swipes p {
    font-size: 11px;
    margin-bottom: 0 !important;
    color: var(--label-color);
}

.record-not-found-who-is-in {
    text-align: center;
    font-size: var(--normal-font-size);
}

.dropdown-container1-employee-swipes-for-date-type input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
    font-size: var(--normal-font-size);
}

.dropdown-container1-employee-swipes-for-date-type:hover {
    display: block;
}

.dropdown-container1-employee-swipes-for-date-type {
    margin-left: 10px;
    position: relative;
}

.dropdown-container1-employee-swipes-for-date-type input[type="date"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    height: 30px;
    font-size: var(--normal-font-size);
}

.dropdown-container1-employee-swipes:hover .dropdown-content1-employee-swipes {
    display: block;
}

.dropdown-container1-employee-swipes {
    margin-left: 10px;
    position: relative;
    margin-top: 15px;
}

.dropdown-container1-employee-swipes input[type="date"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    height: 30px;
    font-size: var(--normal-font-size);
}

.button2 {
    margin-top: 30px;
    border: 1px solid #ddd;
    height: 30px;
    background-color: #fff;
    color: #666;
}

.dropdown-btn1::after {
    content: "\25BE";
    /* Unicode character for a down-pointing triangle */
    font-size: var(--normal-font-size);
    /* Adjust the size of the arrow */
    margin-left: 5px;
    /* Add some spacing between the text and arrow */
}

.dropdown-btn1 {
    padding: 5px 15px;
    background-color: #fff;
    color: black;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 180px;
    cursor: pointer;
    position: relative;
    /* Add relative positioning for the arrow */
}

.container-employee-swipes-right-image {
    width: 3em;
    margin-top: 10px;
    border-radius: 50%;
}

.dropdown-content1-employee-swipes {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    max-height: 200px;
    /* Set the maximum height for scrollable content */
    overflow-y: scroll;
    /* Enable vertical scrolling if content exceeds max height */
}

.dropdown-content1-employee-swipes a {
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    color: var(--main-heading-color);
}

.dropdown-content1-employee-swipes a:hover {
    background-color: #007bff;
    color: #fff;
}

.web-sign-in-box-employee-swipes {
    position: absolute;
    top: 30px;
    left: 30px;
    background-color: white;
    padding: 10px;
    border-radius: 5px;
}

.green-section-employee-swipes h6 {
    margin-top: 10px;
    font-size: var(--normal-font-size);
    color: #000;
}

.search-text::placeholder {
    font-size: 14px;
    /* Adjust the font size as needed */
    color: var(--label-color);
    /* Placeholder text color */
}

.employee-swipes-table-container {
    border-bottom: 1px solid #ccc;
}

.employee-swipes-name-and-id {
    white-space: nowrap;
    font-size: var(--normal-font-size);
    padding-left: 10px;
}

.employee-swipes-checkbox {
    margin-left: -5px;
    margin-top: -20px;
    height: 10px;
}

.employee-swipes-emp-id {
    font-size: 10px;
    margin-left: 6px;

}

.employee-swipes-swipe-details-for-signed-employees {
    white-space: nowrap;
    padding-left: 12px;
}

.employee-swipes-swipe-date {
    font-size: 10px;
}

.empty-text {
    padding-left: 12px;
}

.swipe-details-who-is-in {
    color: #000;
    font-weight: 500;
    font-size: 14px;
}

.swipe-details-who-is-in-horizontal-row {
    border-top: 1px solid #666;
    margin-top: -5px;
}

.swipe-deatils-title {
    font-size: var(--normal-font-size);
    color: var(--label-color);
    margin-bottom: 0;
}

.swipe-details-description {
    font-size: var(--normal-font-size);
    font-weight: 500;
}

.dropdown-container1-employee-swipes-for-search-employee input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
    font-size: var(--normal-font-size);
}

.dropdown-container1-employee-swipes-for-search-employee {
    display: block;
}

.dropdown-container1-employee-swipes-for-search-employee {
    margin-left: 10px;
    position: relative;
}

.dropdown-container1-employee-swipes-for-search-employee input[type="date"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    height: 30px;
    font-size: var(--normal-font-size);
}

    .my-button {
            margin: 0px;
            font-size: 0.8rem;
            background-color: #FFFFFF;
            border: 1px solid #a3b2c7;
            /* font-size: 20px;
        height: 50px; */
            padding: 8px 30px;
        }
 .my-button.active-button {
            background-color: #306cc6;
            color: #FFFFFF;
            border-color: #306cc6;
        }
 .my-button.active-button:hover {
            background-color: #306cc6;
            color: #FFFFFF;
            border-color: #306cc6;
        }
        .apply-button {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            transition: border-color 0.3s, color 0.3s;
            /* Smooth transition effect */
        }

        .apply-button:hover {
            border-color: #306cc6;
            /* Change the border color to green on hover */
            color: #306cc6;
            /* Change the text color to green on hover */
        }
.apply-button:active {
            background-color: rgb(2, 17, 79);
            /* Change background color to green when clicked */
            color: #FFFFFF;
            border-color: rgb(2, 17, 79);
            /* Change text color to white when clicked */
        }
        .pending-button:hover {
            border-color: rgb(2, 17, 79);
            /* Change the border color to green on hover */
            color: rgb(2, 17, 79);
            /* Change the text color to green on hover */
        }
.pending-button:active {
            background-color: rgb(2, 17, 79);
            /* Change background color to green when clicked */
            color: #FFFFFF;
            border-color: rgb(2, 17, 79);
            /* Change text color to white when clicked */
        }
        .custom-radio-class {
    vertical-align: middle;
    margin-bottom: 12px;
}
.history-button {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        .door-sign-box {
    background-color: #fff; /* Light gray background */
    color: green; /* Dark text */
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    display: inline-block;
    margin-top: 5px;
    margin-left: 10px;
}
</style>
    <body>
        <div>
            <div class="employee-swipes-fields d-flex align-items-center">
                <div class="dropdown-container1-employee-swipes"style="margin-top:-5px;">
                    <label for="start_date" style="color: #666;font-size:12px;">Select Date<span style="color: red;">*</span>:</label><br />
                    <input type="date" style="font-size: 12px;" id="start_date" wire:model="startDate" wire:change="updateDate"  max="{{ now()->toDateString() }}">
                      
                </div>
               
                
                <div class="dropdown-container1-employee-swipes-for-search-employee">
                    <label for="dateType" style="color: #666;font-size:12px;">Employee Search</label><br />

                    <div class="search-input-employee-swipes">
                        <div class="search-container-for-employee-swipes" style="position: relative;">
                            <i class="fa fa-search search-icon-employee-swipes" aria-hidden="true" style="cursor:pointer;" wire:click="searchEmployee"></i>
                            <input wire:model="search" type="text" placeholder="Search Employee" class="search-text">

                        </div>

                    </div>
                </div>
                <div class="dropdown-container1-employee-swipes-for-download-and-filter d-flex">
                    <div class="dropdown-container1-employee-swipes">

                        <button type="btn" class="button2" data-toggle="modal" data-target="#exampleModalCenter"style="padding:5px;border-radius:5px;">
                            <i class="fa-solid fa-download" wire:click="downloadFileforSwipes"></i>
                        </button>

                    </div>
                    <div class="dropdown-container1-employee-swipes">

                        <button type="button" class="button2" data-toggle="modal" data-target="#exampleModalCenter" style="margin-top:30px;border-radius:5px;padding:5px;">
                            <i class="fa-icon fas fa-filter" style="color:#666"wire:click="toggleSidebar"></i>
                        </button>
                        <div class="sidebar {{ $isOpen ? 'active' : '' }}">
                                    <div class="sidebar-header">
                                        <h6>Apply Filter</h6>
                                        <button wire:click="closeSidebar" class="close-btn">×</button>
                                    </div>

                                    <!-- Filter Section -->
                                    <div class="sidebar-content">
                                            
                                          
                                            <label for="designation" class="designation-label">Designation:</label>
                                            <select wire:model="selectedDesignation" wire:change="updateselectedDesignation" class="custom-dropdown">
                                                <option value="">All</option>
                                                <option value="software_engineer">Software Engineer</option>
                                                <option value="senior_software_engineer">Sr. Software Engineer</option>
                                                <option value="team_lead">Team Lead</option>
                                                <option value="sales_head">Sales Head</option>
                                            </select>
                                           
                                            <label for="department" class="department-label">Department:</label>
                                            <select wire:model="selectedDepartment" wire:change="updateselectedDepartment" class="custom-dropdown">
                                                <option value="">All</option>
                                                <option value="information_technology">Information Techonology</option>
                                                <option value="business_development">Business Development</option>
                                                <option value="operations">Operations</option>
                                                <option value="innovation">Innovation</option>
                                                <option value="infrastructure">Infrastructure</option>
                                                <option value="human_resources">Human Resource</option>
                                            </select>
                                           
                                            <label for="location" class="location-label">Location:</label>
                                            <select wire:model="selectedLocation" wire:change="updateselectedLocation" class="custom-dropdown">
                                                <option value="Hyderabad">Hyderabad</option>
                                                <option value="Udaipur">Udaipur</option>
                                                <option value="Mumbai">Mumbai</option>
                                                <option value="Remote">Remote</option>
                                            </select>
                                            <label for="swipe_status" class="swipe-status-label">Swipe Status:</label>
                                            <select wire:model="selectedSwipeStatus" wire:change="updateselectedSwipeStatus" class="custom-dropdown">
                                               @if($isPending==1&&$defaultApply==0) 

                                                <option value="All">All</option>
                                                <option value="mobile_sign_in">Mobile Sign In</option>
                                                <option value="web_sign_in">Web Sign In</option>
                                              @endif  
                                                @if($isApply == 1 && $defaultApply == 1)
                                                   <option value="door">Door Sign In</option>
                                                @endif
                                            </select>
                                            
                                    </div>
                                    <div class="button-container">
                                            <button wire:click="applyFilter" class="apply-btn">Apply</button>
                                            <button wire:click="resetSidebar" class="reset-btn">Reset</button>
                                    </div>
                                </div>

                    </div>
                    <div class="button-container-for-employee-swipes">
                        <button class="my-button apply-button" style="background-color:{{ ($isApply == 1 && $defaultApply == 1) ? ' #306cc6;' : '#fff' }};color: {{ ($isApply == 1 && $defaultApply == 1) ? '#fff' : 'initial' }};" wire:click="viewDoorSwipeButton"><span style="font-size:10px;">View Door Swipe Data</span></button>
                        <button class="my-button history-button" style="background-color:{{ ($isPending==1&&$defaultApply==0) ? ' #306cc6;' : '#fff' }};color: {{ ($isPending==1&&$defaultApply==0) ? '#fff' : 'initial' }};" wire:click="viewWebsignInButton"><span style="font-size:10px;">View Web Sign-In Data</span></button>
                    </div>
                </div>
            </div>

    <div class="row m-0 p-0  mt-4" >
        <div class="col-md-9 mb-4" >
           <div class="bg-white border rounded">
             <div class="table-responsive bg-white rounded p-0 m-0"style="max-height: 500px;">
                <table class="employee-swipes-table  bg-white" style="width: 100%;padding-right:10px;">
                                        <thead style="position: sticky;top: 0px;left:0px;">
                                            <tr>
                                                <th>Employee&nbsp;Name</th>
                                                <th>Swipe&nbsp;Time&nbsp;&&nbsp;Date</th>
                                                <th>Shift</th>
                                                <th>In/Out</th>
                                                <th>Received&nbsp;On</th>
                                                <th>Door/Address</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                
                                                @if($isApply==1&&$defaultApply==1)
                                                
                                                    @if(count($SignedInEmployees)>0)
                                                        @foreach ($SignedInEmployees as $swipe)
                                                            @foreach($swipe['swipe_log'] as $index =>$log)
                                                                <tr>
                                                                        <td class="employee-swipes-name-and-id">
                                                                                <input type="radio" name="selectedEmployee" value="{{ $swipe['employee']->emp_id }}-{{ $loop->index }}-{{ \Carbon\Carbon::parse($log->logDate)->format('H:i:s') }}-{{ $log->Direction }}" class="radio-button custom-radio-class"wire:model="selectedEmployeeId"
                        wire:change="handleEmployeeSelection" />
                                                                            <span style="width:100px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-toggle="tooltip"
                                                                                    data-placement="top" title="{{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}">
                                                                                    {{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}
                                                                            </span>
                                                                            <br />
                                                                            <span class="text-muted employee-swipes-emp-id">#{{$swipe['employee']->emp_id}}</span>
                                                                        </td>
                                                                        <td>
                                                                                {{ \Carbon\Carbon::parse($log->logDate)->format('H:i:s') }}<br />
                                                                                <span class="text-muted employee-swipes-swipe-date">
                                                                                    {{ \Carbon\Carbon::parse($log->logDate)->format('jS F Y') }}
                                                                                </span>
                                                                        </td>
                                                                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($swipe['employee']->shift_start_time)->format('H:i a') }} to {{ \Carbon\Carbon::parse($swipe['employee']->shift_end_time)->format('H:i a') }}</td>
                                                                        <td style="text-transform:uppercase;">{{$log->Direction}}</td>
                                                                        <td> {{ \Carbon\Carbon::parse($log->logDate)->format('H:i:s') }}<br /> <span class="text-muted employee-swipes-swipe-date"style="white-space:nowrap;"> {{ \Carbon\Carbon::parse($log->logDate)->format('jS F Y') }}</span></td>
                                                                        <td style="white-space:nowrap;">
                                                                                @if ($log->Direction === 'in')
                                                                                    Door Swipe In
                                                                                @else
                                                                                    Door Swipe Out
                                                                                @endif
                                                                        </td>
                                                                        
                                                                </tr>
                                                            @endforeach    
                                                        @endforeach
                                                        

                                                    @else
                                                       <tr>
                                                           <td colspan="12" class="text-center">Employee Swipe Data Not found</td>
                                                       </tr>    
                                                    @endif
                                                @elseif($isPending==1&&$defaultApply==0)
                                            
                                                    @if(count($SignedInEmployees))
                                                        @foreach($SignedInEmployees as $swipe)
                                                        @foreach($swipe['swipe_log'] as $index => $log)
                                                                <tr>
                                                                
                                                                <td class="employee-swipes-name-and-id">
                                                                                <input type="radio" name="selectedEmployee" value="{{ $swipe['employee']->emp_id }}-{{ $loop->index }}-{{ $log->swipe_time }}-{{ $log->in_or_out }}-{{ $log->sign_in_device }}" class="radio-button custom-radio-class"wire:model="selectedWebEmployeeId"
                        wire:change="handleEmployeeWebSelection" />
                                                                            <span style="width:100px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" data-toggle="tooltip"
                                                                                    data-placement="top" title="{{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}">
                                                                                    {{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}
                                                                            </span>
                                                                            <br />
                                                                            <span class="text-muted employee-swipes-emp-id">#{{$swipe['employee']->emp_id}}</span>
                                                                        </td>
                                                                
                                                                    <!-- Swipe Log Details -->
                                                                    <td>
                                                                        {{ $log->swipe_time }}<br />
                                                                        <span class="text-muted employee-swipes-swipe-date">
                                                                            {{ \Carbon\Carbon::parse($log->created_at)->format('jS F, Y') }}
                                                                        </span>
                                                                        
                                                                    </td>

                                                                    <!-- Shift Details -->
                                                                    <td style="white-space:nowrap;">
                                                                        {{ \Carbon\Carbon::parse($swipe['employee']->shift_start_time)->format('H:i a') }} 
                                                                        to 
                                                                        {{ \Carbon\Carbon::parse($swipe['employee']->shift_end_time)->format('H:i a') }}
                                                                    </td>

                                                                    <!-- Sign In/Out Type -->
                                                                    <td>
                                                                        @if ($log->in_or_out === 'IN')
                                                                            IN
                                                                        @else
                                                                            OUT
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $log->swipe_time }}<br />
                                                                        <span class="text-muted employee-swipes-swipe-date">
                                                                            {{ \Carbon\Carbon::parse($log->created_at)->format('jS F, Y') }}
                                                                        </span>
                                                                        
                                                                    </td>
                                                                    <td style="white-space:nowrap;">
                                                                            
                                                                            @if ($log->in_or_out === 'IN' && ($log->sign_in_device==='Laptop/Desktop'||$log->sign_in_device==='Laptop'))
                                                                                Web Sign In  
                                                                            @elseif ($log->in_or_out === 'IN' && $log->sign_in_device==='Mobile')
                                                                                Mobile Sign In  
                                                                            @elseif ($log->in_or_out === 'OUT' && ($log->sign_in_device==='Laptop/Desktop'||$log->sign_in_device==='Laptop'))
                                                                                Web Sign Out  
                                                                            @elseif ($log->in_or_out === 'OUT' && $log->sign_in_device==='Mobile')
                                                                                Mobile Sign Out  
                                                                            @elseif ($log->in_or_out === 'IN')
                                                                                Web Sign In
                                                                            @elseif ($log->in_or_out === 'OUT')
                                                                                Web Sign Out
                                                                            @endif    
                                                                    </td>
                                                                    
                                                                </tr>
                                                            @endforeach  
                                                        @endforeach 
                                                    @else
                                                    <tr>
                                                      <td colspan="12"class="text-center">Employee Swipe Record Not found</td>
                                                    </tr>  
                                                    @endif  
                                                
                                                
                                                @endif

                                                </tbody>
                                        </table>
                        </div>
                    </div>
                </div>
                <div class="green-and-white-section-for-employee-swipes col-md-3 p-0 bg-white rounded border"style="margin-left:-8px;height: fit-content;">
                    
                    <div class="green-section-employee-swipes p-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/2055/2055568.png"
                            class="container-employee-swipes-right-image">
                       
                        
                                @if($doorSignIn=='in')
                                <div class="door-sign-box">
                                     Door Sign In
                                </div>     
                                
                                @elseif($doorSignIn=='out')
                                <div class="door-sign-box">
                                     Door Sign Out
                                </div> 
                                    
                                @elseif($webSwipeDirection=='OUT'&& $signInDevice=='Laptop/Desktop')
                                <div class="door-sign-box">
                                     Web Sign Out
                                </div>   
                                @elseif($webSwipeDirection=='IN'&& $signInDevice=='Laptop/Desktop')
                                <div class="door-sign-box">
                                     Web Sign In
                                </div> 
                                @elseif($webSwipeDirection=='OUT'&& $signInDevice=='Mobile')
                                <div class="door-sign-box">
                                     Mobile Sign Out
                                </div>   
                                @elseif($webSwipeDirection=='IN'&& $signInDevice=='Mobile')
                                <div class="door-sign-box">
                                     Mobile Sign In
                                </div>
                                @endif   
                        
                        <h6>Swipe-in Time</h6>
                        @if($swipeTime)
                          <p>{{$swipeTime}}</p>
                        @elseif($doorSwipeTime) 
                          <p>{{$doorSwipeTime}}</p>
                        @else 
                          <p>Not Swiped Yet</p>
                        @endif  
                       
                    </div>
                    <h2 class="swipe-details-who-is-in p-2">Swipe Details</h2>
                    <hr class="swipe-details-who-is-in-horizontal-row">
                    <div class="p-2">
                        <p class="swipe-deatils-title">Device Name</p>
                        <p class="swipe-details-description">
                           @if(!empty($webDeviceName))

                              {{$webDeviceName}}
                           @else   
                              -
                            @endif  
                        </p>
                        <p class="swipe-deatils-title">Access Card</p>
                        <p class="swipe-details-description">
                            @if (!empty($accessCardDetails))
                                {{$accessCardDetails}}
                            @else
                                -
                            @endif
                        </p>
                        <p class="swipe-deatils-title">Door/Address</p>

                        <p class="swipe-details-description">-</p>

                        <p class="swipe-deatils-title">Remarks</p>
                        <p class="swipe-details-description">-</p>
                        <p class="swipe-deatils-title">Device ID</p>
                        <p class="swipe-details-description">
                            @if (!empty($deviceId))
                                {{$deviceId}}
                            @elseif(!empty($webDeviceId))
                                {{$webDeviceId}}
                            @else
                                -
                            @endif
                        </p>
                        <p class="swipe-deatils-title">Location Details</p>
                        <p class="swipe-details-description">-</p>

                    </div>
                </div>
            </div>

        </div>


    </body>
</div>
