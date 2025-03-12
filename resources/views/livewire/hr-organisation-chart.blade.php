<div>
    <style>
         .button-container {
            display: flex;
            justify-content: flex-end; /* Aligns buttons to the start */
            float: right;
            width: 50%; /* Adjust this value to change the container width */
            margin: 20px auto; /* Centers the container and adds some margin */
           
        }
        .button-container button {
            border-radius:8px;
            border:1px solid rgb(2, 17, 79);
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 12px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .button-container button:last-child {
            margin-right: 0; /* Removes margin from the last button */
        }
        .assign-top-level-manager-button{
            border:1px solid  #0000FF;
            background-color: #fff;
            color:#0000FF;
        }
        .mass-transfer-button{
            border:1px solid  #0000FF;
            background-color: #fff;
            color:#0000FF;   
        }
        .assign-manager-button{
            border:1px solid  #0000FF;
            background-color: #0000FF;
            color:#fff;
            border-radius:8px;
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 16px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .sidebar {
            width: 250px;
            padding: 20px;
            background-color: #fff;
            position: fixed;
            top:150px;
            right: 15px;
            height: 100%;
            border:1px solid #ccc;
            border-radius:5px;
        }

.main-content {
    margin-right: 270px; /* Adjust this value to match the sidebar width + padding */
}
.drag-drop-container {
    width: 220px; /* Adjust the width as needed */
    height: 30px; /* Adjust the height as needed */
    background-color: #fff; /* Background color */ /* Border */
    padding: 10px; /* Padding inside the container */
    text-align: center; /* Center align the text */
    line-height: 50px; /* Center the text vertically */
    margin-top:20px;
    font-size: 10px;
    border:1px solid rgb(2, 17, 79);
    border-radius: 5px;
}
.search-container {
  position: relative;
  display: inline-block;
  margin-top:20px;
}

/* Search input styles */
.search-input {
  width: 220px; /* Adjust width as needed */
  height:30px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 20px; /* Adjust border radius as needed */
}

/* Search button styles */
.search-button {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  border: none;
  background: transparent;
  cursor: pointer;
}

/* Search icon styles (using Font Awesome for the search icon) */
.search-button i {
  color: #ccc;
  
}
.profile-containers {
    display: flex;
    align-items: center;
    padding: 10px;
    width: 200px; /* Adjust the width as needed */
    height: 80px; /* Adjust the height as needed */
    border: 1px dashed #ccc;
    margin-top:20px;
    margin-right:100px;
    border-radius: 8px;
    background-color: #f9f9f9; /* Optional: background color */
}

.profile-picture {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.zoom-controls {
    margin-bottom: 20px;
}

.zoom-controls button {
    margin: 5px;
    padding: 10px;
    font-size: 14px;
    cursor: pointer;
}
.profile-name,
.profile-job-title {
    font-size: 12px;
    margin: 5px 0;
}
.higher-authorities, .lower-authorities {
    display: flex;
    justify-content: center;
    gap: 20px; /* Adjust the spacing between nodes */
}

.emp-id {
            white-space: nowrap;
            font-size: 10px; /* Adjust font size as needed */
            color: #333; /* Adjust text color as needed */
        }
        .large-container {
            background-color: white;
            width: 100%; /* Adjust width as needed */
            padding: 20px;
            height: 100%;
            top:150px;
            overflow: auto;
            border-bottom: 1px solid #ccc;
        }
        .export-button {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #0000FF;
            color: white;
            cursor: pointer;
            margin-right: 20px; 
             /* Add some space between the search container and export button */
        }
        .normalTextSmall {
    color: var(--label-color);
    font-weight: 500;
    font-size: 0.65rem;
    text-align: start;
}
        .fa-filter {
                margin-left: 10px; 
                /* Space between funnel icon and previous element */
        }
        .toggle-button {
    position: relative;
    display: inline-flex;
    width: auto; /* Adjust width as needed */
    height: 40px; /* Adjust height as needed */
    border:1px solid #ccc; /* Background color of the toggle button */
    border-radius: 5px; /* Adjust border radius as needed */
    cursor: pointer;
    overflow: hidden; /* Hide the overflow */
    margin-right:20px;
}

.toggle-button span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height:40px;
    color: #333; /* Color of the arrows */
    font-size: 20px; /* Adjust font size as needed */
    
}

.vertical-arrow {
    background-color: rgb(2, 17, 79); /* Background color of the vertical arrow *//* Color of the vertical arrow */
    border-right: 1px solid #ccc; /* Optional: Add a border to separate the arrows */
}
.zoom-btn {
    margin: 5px;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    border: none;
    border-radius: 50%; /* Makes the buttons round */
    width: 40px;
    height: 40px;
    color: white;
    transition: background-color 0.3s;
}
.searchContainer {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 2px 0 5px 0 #ccc;
    padding: 12px 15px;
    width: 250px;
    margin-top: 15px;
    display:none;
}
.selected-employee-box {
            border: 1px solid #007bff;
            /* Border color */
            padding: 5px 10px;
            /* Adjust padding for a smaller box */
            border-radius: 5px;
            /* Rounded corners */
            font-size: 14px;
            /* Smaller font size */
            color: #333;
            /* Text color */
            width: 300px;
            /* Fixed width for rectangular shape */
            height: 50px;
            /* Fixed height for a smaller box */
            display: flex;
            /* Use flexbox for positioning */
            align-items: center;
            /* Center items vertically */
            justify-content: space-between;
            /* Space between items */
            margin-top: 10px;
            /* Adds some space from the top */
        }

        .selected-employee-box {
            margin-left: 15px;
            /* Space between circle and text */
        }

        
        



       

        .selected-employee-box .close-btn {
            position: absolute;
            /* Absolute positioning */
            right: 5px;
            /* Position from the right edge */
            top: 1px;
            /* Position from the top edge */
            background: transparent;
            /* Transparent background */
            border: none;
            /* Remove default border */
            cursor: pointer;
            /* Change cursor on hover */
        }
     

/* Background color for zoom in button */
.zoom-in-btn {
    background-color: #4CAF50; /* Green color */
}

.zoom-in-btn:hover {
    background-color: #45a049; /* Darker green when hovered */
}

/* Background color for zoom out button */
.zoom-out-btn {
    background-color: #f44336; /* Red color */
}

.zoom-out-btn:hover {
    background-color: #e53935; /* Darker red when hovered */
}

.horizontal-arrow {
    background-color: white; /* Background color of the horizontal arrow */
    color: blue; /* Color of the horizontal arrow */
}
.cancel-btn1{
    background-color: #fff;
    color:#306cc6 ;
    font-weight: 500;
    border-radius: 5px;
    padding: 6px 12px;
    font-size: 5px;
    border: none;
    border: 1px solid #306cc6;
}

.flowchart {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    transition: transform 0.3s ease;
}

.node {
    background-color: #f2f2f2;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    width: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.superadmin {
    border: 2px solid #D397F8;
    background-color: #E6E6FA;
    border-left: 10px solid #D397F8;
}


        .authorities-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .node.superadmin
        {
            padding-bottom: -10px;
       
        }
        .node.employee {
    margin-left: 5px; /* Adjust as needed */
    flex: 0 0 auto; /* Ensure elements don't shrink and maintain their width */
}
.employee {
    border: 2px solid #48D1CC;
    background-color: #F0F8FF;
    border-left: 10px solid #48D1CC;
}
.lines {
    position: absolute;
    top: 60%;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: pink;
}

.line {
    position: absolute;
    top: -50%; /* Adjust to center the line vertically */
    width: 1px;
    height: 100px; /* Adjust as needed */
    background: #ccc;
}
        .horizontal-line {
            width: 50px;
            height: 2px;
            background-color: black;
            margin: 0 auto;
        }
        .scroll-container {
                    max-height: 400px; /* Set the desired height */
                    overflow-y: auto;
                    padding-right: 10px; /* Optional: to add padding space for scrollbar */
        }
        .tooltip {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            white-space: nowrap;
            z-index: 1000;
        }
        .line-for-higher-authority {
    position: absolute;
    left: 50%; /* Center the line */
    transform: translateX(-50%); /* Adjust to center the line */
    width: 2px; /* Thickness of the line */
    height: 50px; /* Length of the line */
    background: #ccc; /* Color of the line */
    top: 90px; /* Position the line below the node */
}

        .profile-name:hover .tooltip {
            display: block;
        }
        .rectangle-container {
            background-color: #f8f9fa; /* Light grey background */
            color: #333; /* Darker text color */
            padding: 10px;
            border: 1px solid #ccc; /* Light grey border */
            border-radius: 5px; /* Slightly rounded corners */
            margin-top: 10px;
            width: 30px; /* Fixed width for the container */
        }
        .search-container {
        display: flex;
        align-items: center;
        border: 2px solid #ccc;
        border-radius: 20px;
        overflow: hidden;
        width: 300px;
    }

    .search-icon {
        background-color: #d0d7e5;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border-radius: 50%;
        width: 25px;
        height: 25px;
    }

    .search-input {
        border: none;
        padding: 10px;
        flex-grow: 1;
        outline: none;
    }

    .search-input::placeholder {
        color: #888;
    }
</style>
@if (session()->has('message'))
   <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    
        <div class="button-container">
          <button type="button"class="cancel-btn1"wire:click="assigntoplevelmanager">Assign Top Level Manager</button>
          <button type="button"class="cancel-btn1"wire:click="masstransfer">Mass&nbsp;&nbsp;Transfer</button>
          <button class="submit-btn"wire:click="openAssignManagerPopup">Assign&nbsp;&nbsp;Manager</button>
        </div>
   
        @if($massTransferDialog==true)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Mass Transfer</b></h5>
                                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeMassTransferDialog" style="background-color: white; height:10px;width:10px;" >
                                </button>
                            </div>
                            <div class="modal-body"style="max-height:1000px;overflow-y:auto">
                            
                                      
                            <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                            <label for="employeeType" wire:click="searchForManagerTransfer" style="cursor:pointer;">Transfer from:</label>
                                            <div class="searchContainer" style="<?php echo ($searchFromManagerTransfer == 1) ? 'display: block;' : ''; ?>">
                                                <!-- Content for the search container -->
                                                <div class="row mb-2 py-0 px-2">
                                                    <div class="row m-0 p-0 d-flex align-items-center justify-content-between">
                                                        <div class="col-md-10 p-0 m-0">
                                                            <div class="input-group">
                                                                <input wire:model="searchTerm" wire:change="updatesearchTerm" id="searchInput" type="text"
                                                                    class="form-control placeholder-small" placeholder="Search...." aria-label="Search"
                                                                    aria-describedby="basic-addon1">
                                                                <div class="input-group-append searchBtnBg d-flex align-items-center">
                                                                    <button type="button" class="search-btn">
                                                                        <i class="ph-magnifying-glass ms-2"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col m-0 p-0 d-flex justify-content-end">
                                                            <button wire:click="closeEmployeeBoxForMassTransfer" type="button" class="close rounded px-1 py-0"
                                                                aria-label="Close">
                                                                <span aria-hidden="true" class="closeIcon"><i class="ph-x"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Your Blade file -->
                                                <div class="scrollApplyingTO">
                                                    @if(!empty($managers) && $managers->isNotEmpty())
                                                    @foreach($managers as $employee)
                                                    <div class="d-flex gap-3 align-items-center" style="cursor: pointer;"
                                                        wire:click="updateselectedEmployeeForManager('{{ $employee->emp_id }}')">
                                                        @if($employee['image'] && $employee['image'] !== 'null')
                                                        <div class="employee-profile-image-container">
                                                            <img class="navProfileImg rounded-circle" src="data:image/jpeg;base64,{{ $employee->image }}">

                                                        </div>
                                                        @else
                                                        @if($employee['gender'] == 'Female')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/female-default.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @elseif($employee['gender'] == 'Male')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/male-default.png') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @else
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/user.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @endif
                                                        @endif
                                                        <div class="d-flex flex-column mt-2 mb-2">
                                                            <span class="ellipsis mb-0">{{ ucwords(strtolower($employee['first_name'])) }}
                                                                {{ ucwords(strtolower($employee['last_name'])) }}</span>
                                                            <span class="mb-0 normalTextSmall"> #{{ $employee['emp_id'] }} </span>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <p class="mb-0 normalTextValue text-muted m-auto text-center" style="font-size:12px;">No employees
                                                        found.</p>
                                                    @endif
                                                </div>
                                            
                                            </div>
                                            @if(!empty($selectedEmployeeId))
                                                
                                                            @php
                                                                function getRandomLateColor() {
                                                                    $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF', '#FFC5C5'];
                                                                            return $colors[array_rand($colors)];
                                                                    }
                                                            @endphp         
                                                            <div class="row m-0 p-0">
                                                                <div class="col p-0 m-0">
                                                                    @if($searchEmployee==0)
                                                                    <div class="selected-employee-box position-relative gap-4">
                                                                        <button type="button" class="close-btn-for-selected-employee" wire:click="clearSelectedEmployee">
                                                                            &times; <!-- This will render a cross (×) symbol -->
                                                                        </button>
                                                                            <div class="gap-1" style="display: flex; align-items: center;">
                                                                                <div class="thisCircle" style="border: 2px solid {{ getRandomLateColor() }};" data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">
                                                                                    <span class="initials">
                                                                                        {{ strtoupper(substr(trim($selectedEmployeeFirstName), 0, 1)) }}{{ strtoupper(substr(trim($selectedEmployeeLastName), 0, 1)) }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="employee-info">
                                                                                    <span class="employee-info-name"data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">{{ ucwords(strtolower($selectedEmployeeFirstName)) }}&nbsp;{{ ucwords(strtolower($selectedEmployeeLastName)) }}</span>
                                                                                    {{ $selectedEmployeeId }}
                                                                                </div>
                                                                            </div>    
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                    @endif
                                            </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4 mb-2">
                                                @if (!empty($SubordinateEmployees))
                                                    <div class="mt-2" style="width:100%;">
                                                        <table class="swipes-table mt-2 border">
                                                            <tr style="background-color: #f6fbfc;">
                                                                <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Name</th>
                                                                <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Number</th>
                                                            </tr>
                                                    
                                                            
                                                            
                                                            @foreach ($SubordinateEmployees as $emp)
                                                            <tr style="border:1px solid #ccc;">
                                                                <td style="width:50%;font-size: 10px; color:  #778899;text-align:start;padding:5px 10px;white-space:nowrap;">
                                                                <label class="custom-checkbox">
                                                                        <input type="checkbox" name="employeeCheckbox[]" class="employee-swipes-checkbox" wire:model="EmployeeId" wire:change="updateEmployeeId" value="{{ $emp->emp_id }}">
                                                                        <span class="checkmark"></span>
                                                                        {{ucwords(strtolower($emp->first_name))}} {{ucwords(strtolower($emp->last_name))}}
                                                                    </label> 
                                                                </td>
                                                                <td style="width:50%;font-size: 10px;color:#778899;text-align:start;padding:5px 32px">{{$emp->emp_id}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                 @else   
                                                  
                                                   <img src="{{ asset('images/no-reportee-image.png') }}" style="margin-top:50px;" height="400" width="400">
                                                 @endif  
                                                </div>   
                                            </div>
                                            <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                            <label for="employeeType" wire:click="searchforSecondEmployee" style="cursor:pointer;">Transfer to:</label>
                                            <div class="searchContainer" style="<?php echo ($searchSecondEmployee == 1) ? 'display: block;' : ''; ?>">
                                                <!-- Content for the search container -->
                                                <div class="row mb-2 py-0 px-2">
                                                    <div class="row m-0 p-0 d-flex align-items-center justify-content-between">
                                                        <div class="col-md-10 p-0 m-0">
                                                            <div class="input-group">
                                                                <input wire:model="searchTerm" wire:change="updatesearchTerm" id="searchInput" type="text"
                                                                    class="form-control placeholder-small" placeholder="Search...." aria-label="Search"
                                                                    aria-describedby="basic-addon1">
                                                                <div class="input-group-append searchBtnBg d-flex align-items-center">
                                                                    <button type="button" class="search-btn">
                                                                        <i class="ph-magnifying-glass ms-2"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col m-0 p-0 d-flex justify-content-end">
                                                            <button wire:click="closeEmployeeBoxForMassTransfer" type="button" class="close rounded px-1 py-0"
                                                                aria-label="Close">
                                                                <span aria-hidden="true" class="closeIcon"><i class="ph-x"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Your Blade file -->
                                                <div class="scrollApplyingTO">
                                                    @if(!empty($managers) && $managers->isNotEmpty())
                                                    @foreach($managers as $employee)
                                                    <div class="d-flex gap-3 align-items-center" style="cursor: pointer;"
                                                        wire:click="updateselectedEmployeeForManager2nd('{{ $employee->emp_id }}')">
                                                        @if($employee['image'] && $employee['image'] !== 'null')
                                                        <div class="employee-profile-image-container">
                                                            <img class="navProfileImg rounded-circle" src="data:image/jpeg;base64,{{ $employee->image }}">

                                                        </div>
                                                        @else
                                                        @if($employee['gender'] == 'Female')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/female-default.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @elseif($employee['gender'] == 'Male')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/male-default.png') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @else
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/user.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @endif
                                                        @endif
                                                        <div class="d-flex flex-column mt-2 mb-2">
                                                            <span class="ellipsis mb-0">{{ ucwords(strtolower($employee['first_name'])) }}
                                                                {{ ucwords(strtolower($employee['last_name'])) }}</span>
                                                            <span class="mb-0 normalTextSmall"> #{{ $employee['emp_id'] }} </span>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <p class="mb-0 normalTextValue text-muted m-auto text-center" style="font-size:12px;">No employees
                                                        found.</p>
                                                    @endif
                                                </div>
                                            
                                            </div>
                                            @if(!empty($selectedEmployeeIdFor2ndManager))
                                                
                                                            @php
                                                                function getRandomAbsentColor() {
                                                                    $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF', '#FFC5C5'];
                                                                            return $colors[array_rand($colors)];
                                                                    }
                                                            @endphp         
                                                            <div class="row m-0 p-0">
                                                                <div class="col p-0 m-0">
                                                                    @if($searchEmployee==0)
                                                                    <div class="selected-employee-box position-relative gap-4">
                                                                        <button type="button" class="close-btn-for-selected-employee" wire:click="clearSelectedEmployee">
                                                                            &times; <!-- This will render a cross (×) symbol -->
                                                                        </button>
                                                                            <div class="gap-1" style="display: flex; align-items: center;">
                                                                                <div class="thisCircle" style="border: 2px solid {{ getRandomAbsentColor() }};" data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstNameFor2ndManager)) }} {{ ucwords(strtolower($selectedEmployeeLastNameFor2ndManager)) }}">
                                                                                    <span class="initials">
                                                                                        {{ strtoupper(substr(trim($selectedEmployeeFirstNameFor2ndManager), 0, 1)) }}{{ strtoupper(substr(trim($selectedEmployeeLastNameFor2ndManager), 0, 1)) }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="employee-info">
                                                                                    <span class="employee-info-name"data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstNameFor2ndManager)) }} {{ ucwords(strtolower($selectedEmployeeLastNameFor2ndManager)) }}">{{ ucwords(strtolower($selectedEmployeeFirstNameFor2ndManager)) }}&nbsp;{{ ucwords(strtolower($selectedEmployeeLastNameFor2ndManager)) }}</span>
                                                                                    {{ $selectedEmployeeIdFor2ndManager }}
                                                                                </div>
                                                                            </div>    
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                    @endif
                                            </div>
                                            </div>                
                                            
                                          
                            </div>
                            <div class="modal-footer">
                                    <button type="button"class="approveBtn btn-primary"wire:click="closeMassTransferDialog">Cancel</button>
                                    <button type="button" class="rejectBtn"wire:click='checkMassTransfer'>Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div> 
        @endif
        @if($assignManagerPopup==true)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                                <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Assign Manager</b></h5>
                                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeAssignManager" style="background-color: white; height:10px;width:10px;" >
                                </button>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                            <div class="container">
                                        <div class="form-group">
                                            <label for="employeeDropdown">Select Employee</label>
                                            <select id="employeeDropdown" class="form-control" wire:model="selectedEmployee"wire:change="updateselectedEmployee">
                                                <option value="">Select Employee</option>
                                                @foreach ($unassigned_manager as $employee)
                                                
                                                    <option value="{{ $employee->emp_id }}">
                                                        {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }} ({{ $employee->emp_id }})
                                                    </option>
                                                
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($selectedEmployee)
                                        <label for="employeeDropdown">Assign Manager:</label>
                                        <select id="employeeDropdown" class="form-control" wire:model="selectedManager"wire:change="updateselectedManager">
                                                <option value="">Select Manager:</option>
                                                @foreach ($managers as $employee)
                                                
                                                    <option value="{{ $employee->manager_id}}">
                                                        {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }} ({{ $employee->manager_id }})
                                                    </option>
                                                
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                              
                            </div>
                            <div class="modal-footer">
                                    <button type="button"class="approveBtn btn-primary"wire:click="closeAssignManager">Cancel</button>
                                    <button type="button" class="rejectBtn"wire:click="check">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div>  
        @endif
        @if($assignTopLevelManager==true)
        <div class="modal"tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Assign Top Level Manager</h6>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                wire:click="close">
                                </button>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                                <div class="row">
                                    <div class="form-group col-md-4 mb-2">
                                            <label for="employeeType" wire:click="searchforEmployee" style="cursor:pointer;">Employee:</label>
                                            <div class="searchContainer" style="<?php echo ($searchEmployee == 1) ? 'display: block;' : ''; ?>">
                                                <!-- Content for the search container -->
                                                <div class="row mb-2 py-0 px-2">
                                                    <div class="row m-0 p-0 d-flex align-items-center justify-content-between">
                                                        <div class="col-md-10 p-0 m-0">
                                                            <div class="input-group">
                                                                <input wire:model="searchTerm" wire:change="updatesearchTerm" id="searchInput" type="text"
                                                                    class="form-control placeholder-small" placeholder="Search...." aria-label="Search"
                                                                    aria-describedby="basic-addon1">
                                                                <div class="input-group-append searchBtnBg d-flex align-items-center">
                                                                    <button type="button" class="search-btn">
                                                                        <i class="ph-magnifying-glass ms-2"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col m-0 p-0 d-flex justify-content-end">
                                                            <button wire:click="closeEmployeeBox" type="button" class="close rounded px-1 py-0"
                                                                aria-label="Close">
                                                                <span aria-hidden="true" class="closeIcon"><i class="ph-x"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Your Blade file -->
                                                <div class="scrollApplyingTO">
                                                    @if(!empty($managers) && $managers->isNotEmpty())
                                                    @foreach($managers as $employee)
                                                    <div class="d-flex gap-3 align-items-center" style="cursor: pointer;"
                                                        wire:click="updateselectedEmployeeForManager('{{ $employee->emp_id }}')">
                                                        @if($employee['image'] && $employee['image'] !== 'null')
                                                        <div class="employee-profile-image-container">
                                                            <img class="navProfileImg rounded-circle" src="data:image/jpeg;base64,{{ $employee->image }}">

                                                        </div>
                                                        @else
                                                        @if($employee['gender'] == 'Female')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/female-default.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @elseif($employee['gender'] == 'Male')
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/male-default.png') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @else
                                                        <div class="employee-profile-image-container">
                                                            <img src="{{ asset('images/user.jpg') }}"
                                                                class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                                                                alt="Default Image">
                                                        </div>
                                                        @endif
                                                        @endif
                                                        <div class="d-flex flex-column mt-2 mb-2">
                                                            <span class="ellipsis mb-0">{{ ucwords(strtolower($employee['first_name'])) }}
                                                                {{ ucwords(strtolower($employee['last_name'])) }}</span>
                                                            <span class="mb-0 normalTextSmall"> #{{ $employee['emp_id'] }} </span>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <p class="mb-0 normalTextValue text-muted m-auto text-center" style="font-size:12px;">No employees
                                                        found.</p>
                                                    @endif
                                                </div>
                                            
                                            </div>
                                            @if(!empty($selectedEmployeeId))
                                                
                                                            @php
                                                                function getRandomAbsentColor() {
                                                                    $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF', '#FFC5C5'];
                                                                            return $colors[array_rand($colors)];
                                                                    }
                                                            @endphp         
                                                            <div class="row m-0 p-0">
                                                                <div class="col p-0 m-0">
                                                                    @if($searchEmployee==0)
                                                                    <div class="selected-employee-box position-relative gap-4">
                                                                        <button type="button"class="close-btn-for-selected-employee" wire:click="clearSelectedEmployee">
                                                                            &times; <!-- This will render a cross (×) symbol -->
                                                                        </button>
                                                                            <div class="gap-1" style="display: flex; align-items: center;">
                                                                                <div class="thisCircle" style="border: 2px solid {{ getRandomAbsentColor() }};" data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">
                                                                                    <span class="initials">
                                                                                        {{ strtoupper(substr(trim($selectedEmployeeFirstName), 0, 1)) }}{{ strtoupper(substr(trim($selectedEmployeeLastName), 0, 1)) }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="employee-info">
                                                                                    <span class="employee-info-name"data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">{{ ucwords(strtolower($selectedEmployeeFirstName)) }}&nbsp;{{ ucwords(strtolower($selectedEmployeeLastName)) }}</span>
                                                                                    {{ $selectedEmployeeId }}
                                                                                </div>
                                                                            </div>    
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                    @endif
                                            </div>
                                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);"wire:click="closeAssignTopLevelManager">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div>



        @endif  
          
        <div class="container">
        <div class="large-container"style="display:flex;flex-direction:row;">
            <!-- Content inside the large container -->
              
                <div style="margin-left:680px;">
                        <label class="toggle-button">
                            <span class="vertical-arrow"style="color:white;">&#x21d5;</span> <!-- Vertical double-sided arrow -->
                            <span class="horizontal-arrow">&#x21d4;</span> <!-- Horizontal double-sided arrow -->
                        </label>
                       
                        
                        <button class="submit-btn" wire:click="exportContent">Export</button>
                        <i class="fa fa-filter"></i>
                </div>        
        </div>
                    
       @livewire('flowchart',['selectedEmployeeId'=>$selectedEmployeeId])
                     
        </div>
        
        
        
    </div>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<script>
    document.querySelector('.submit-btn').addEventListener('click', function () {
        const content = document.getElementById('contentToExport');
        
        // Use html2canvas to capture the content and export as PNG
        html2canvas(content).then(function (canvas) {
            const link = document.createElement('a');
            link.download = 'organisation-chart.png'; // PNG filename
            link.href = canvas.toDataURL("image/png"); // PNG data URL
            link.click(); // Trigger the download
        });
    });
</script>
</div>
