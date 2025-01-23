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
.higher-authorities,
.lower-authorities {
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
    color: rgb(2, 17, 79);
    font-weight: 500;
    border-radius: 5px;
    padding: 6px 12px;
    font-size: 5px;
    border: none;
}

.flowchart {
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
            border:2px solid #D397F8;
            background-color: #E6E6FA;
            border-left:10px solid #D397F8;
        }
        .authorities-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .node.employee {
    margin-left: 5px; /* Adjust as needed */
    flex: 0 0 auto; /* Ensure elements don't shrink and maintain their width */
}
        .employee {
           
            border:2px solid #48D1CC;
            background-color: #F0F8FF;
            border-left:10px solid #48D1CC;
        }
        .line {
            width: 1px;
            height: 10px;
            background:#ccc;
           
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
          <button type="button"class="cancel-btn1"wire:click="assigntoplevelmanager">Assign&nbsp;Top&nbsp;Level&nbsp;Manager</button>
          <button type="button"class="cancel-btn1"wire:click="masstransfer">Mass&nbsp;&nbsp;Transfer</button>
          <button class="submit-btn"wire:click="openAssignManagerPopup">Assign&nbsp;&nbsp;Manager</button>
        </div>
        @if($massTransferDialog==true)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                                <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Mass Transfer</b></h5>
                                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeMassTransferDialog" style="background-color: white; height:10px;width:10px;" >
                                </button>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                            
                            <label for="employeeDropdown">Select Manager:</label>
                                        <select id="employeeDropdown" class="form-control" wire:model="selectedMassTransferManager"wire:change="updateselectedMassTransferManager">
                                                <option value="">Select Manager:</option>
                                                @foreach ($managers as $employee)
                                                
                                                    <option value="{{ $employee->manager_id}}">
                                                        {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }} ({{ $employee->manager_id }})
                                                    </option>
                                                
                                                @endforeach
                                            </select>
                                            
                                            @if($selectedMassTransferManager)
                                              <p>List of people working under <span style="font-weight:500;">{{ucwords(strtolower($managerDetails->first_name))}}&nbsp;{{ucwords(strtolower($managerDetails->last_name))}}({{$managerDetails->emp_id}})</span></p>
                                              @foreach($employeeForMassTransfer as $employee)
                                                    <div class="node employee"style="margin-top:10px;">
                                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span class="profile-name">{{ucwords(strtolower($employee->first_name))}}&nbsp;{{ucwords(strtolower($employee->last_name))}}
                                                            <span class="tooltip">{{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}</span>
                                                            </span>
                                                            <span class="profile-job-title">{{$employee->job_title}}</span>
                                                            <span class="profile-job-title"><div class="emp-id">Emp ID-{{$employee->emp_id}}</div></span>
                                                        </div>
                                                    </div>
                                              @endforeach
                                            
                                              <label for="employeeDropdown">Assign New Manager:</label>
                                        <select id="employeeDropdown" class="form-control" wire:model="selectedMassTransferNewManager"wire:change="updateselectedMassTransferNewManager">
                                                <option value="">Select Manager:</option>
                                                @foreach ($newAssignManager as $employee)
                                                
                                                    <option value="{{ $employee->manager_id}}">
                                                        {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }} ({{ $employee->manager_id }})
                                                    </option>
                                                
                                                @endforeach
                                            </select>
                                              
                                            @endif
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
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                                <h6 class="modal-title" id="assigntoplevelmanagerModalLabel" style="color:#666;font-weight:600;">Assign Top Level Manager</h6>
                                <div style="width: 25px; height: 25px; border-radius: 50%; border:2px solid #666; display: flex; justify-content: center; align-items: center; position: relative;">
                                    <button type="button" class="btn-close btn-primary"aria-label="Close" wire:click="closeAssignTopLevelManager" style="height:10px;width:10px;" >
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                            <div class="form-group">
                                 <label style="font-size:12px;color:#666;font-weight:400;">Select Managers</label>
                                 <div id="manager-list" style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($managers as $m1)
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" value="{{ $m1->manager_id }}"wire:model="selectedManagers"wire:change="updateselectedManagers('{{$m1->manager_id}}')">
                                                        {{ ucwords(strtolower($m1->first_name)) }} {{ ucwords(strtolower($m1->last_name)) }} ({{ $m1->manager_id }})
                                                    </label>
                                                </div>
                                            @endforeach
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
                <div class="search-container">
                    <input type="text" placeholder="Search" class="search-input">
                    <button class="search-button"><i class="fa fa-search"></i></button>
                </div>
                <div style="margin-left:360px;">
                        <label class="toggle-button">
                            <span class="vertical-arrow"style="color:white;">&#x21d5;</span> <!-- Vertical double-sided arrow -->
                            <span class="horizontal-arrow">&#x21d4;</span> <!-- Horizontal double-sided arrow -->
                        </label>
                       
                            <button class="submit-btn">Export</button>
                       
                        <i class="fa fa-filter"></i>
                </div>        
        </div>
        @if($selected_higher_authorities1)
        @foreach ($selected_higher_authorities1 as $ha)
                       <div class="node superadmin">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                            <div style="display:flex;flex-direction:column;">
                                <span class="profile-name">{{ucwords(strtolower($ha->first_name))}}&nbsp;{{ucwords(strtolower($ha->last_name))}}
                                      <span class="tooltip">{{ ucwords(strtolower($ha->first_name)) }} {{ ucwords(strtolower($ha->last_name)) }}</span>
                                </span>
                                  
                                <span class="profile-job-title">{{$ha->job_title}}</span>
                                <span class="profile-job-title"><div class="emp-id">Emp ID-{{$ha->emp_id}}</div></span>
                           </div> 
                        </div>
                    @endforeach
        @endif            
        <div class="large-container"style="display:flex;flex-direction:row;">
            <!-- Content inside the large container -->
            <div class="zoom-controls">
                 <button wire:click="zoomIn" class="zoom-btn zoom-in-btn">+</button>
                 <button wire:click="zoomOut" class="zoom-btn zoom-out-btn">-</button>
            </div>        
            <div class="flowchart"style="transform: scale({{ $scale }});">
                <div class="higher-authorities">
                    @foreach ($HigherAuthorities as $ha)
                        <div class="node superadmin">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                            <div style="display:flex;flex-direction:column;">
                                <span class="profile-name">{{ ucwords(strtolower($ha->first_name)) }}&nbsp;{{ ucwords(strtolower($ha->last_name)) }}
                                    <span class="tooltip">{{ ucwords(strtolower($ha->first_name)) }} {{ ucwords(strtolower($ha->last_name)) }}</span>
                                </span>
                                <span class="profile-job-title">{{$ha->job_title}}</span>
                                <span class="profile-job-title"><div class="emp-id">Emp ID-{{$ha->emp_id}}</div></span>
                            </div> 
                        </div>
                    @endforeach
                </div>
                
                <div class="line"></div>
                
                <div class="lower-authorities">
                    @foreach ($lower_authorities as $la)
                        <div class="node employee">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                            <div style="display:flex;flex-direction:column;">
                                <span class="profile-name">{{ ucwords(strtolower($la->first_name)) }}&nbsp;{{ ucwords(strtolower($la->last_name)) }}
                                    <span class="tooltip">{{ ucwords(strtolower($la->first_name)) }} {{ ucwords(strtolower($la->last_name)) }}</span>
                                </span>
                                <span class="profile-job-title">{{$la->job_title}}</span>
                                <span class="profile-job-title"><div class="emp-id">Emp ID-{{$la->emp_id}}</div></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
                <div class="flowchart-left"style="margin-left:70px;">
                @if($selected_higher_authorities)
                    @foreach ($selected_higher_authorities as $ha)
                       <div class="node superadmin">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                            <div style="display:flex;flex-direction:column;">
                                <span class="profile-name">{{ucwords(strtolower($ha->first_name))}}&nbsp;{{ucwords(strtolower($ha->last_name))}}
                                    <span class="tooltip">{{ ucwords(strtolower($ha->first_name)) }} {{ ucwords(strtolower($ha->last_name)) }}</span>
                                </span>
                                <span class="profile-job-title">{{$ha->job_title}}</span>
                                <span class="profile-job-title"><div class="emp-id">Emp ID-{{$ha->emp_id}}</div></span>
                            </div> 
                        </div>
                    @endforeach  
                   
                    @foreach ($selected_lower_authorities as $la) 
                        
                            <div class="line"style="margin-left:110px;"></div>
                            <div class="node employee">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1YjmQy7iBycLxXrdwvrl38TG9G_LxSHC1eg&s" alt="Profile Picture" class="profile-picture">
                                <div style="display:flex;flex-direction:column;">
                                    <span class="profile-name">{{ucwords(strtolower($la->first_name))}}&nbsp;{{ucwords(strtolower($la->last_name))}}
                                             <span class="tooltip">{{ ucwords(strtolower($ha->first_name)) }} {{ ucwords(strtolower($ha->last_name)) }}</span>
                                    </span>
                                    <span class="profile-job-title">{{$la->job_title}}</span>
                                    <span class="profile-job-title"><div class="emp-id">Emp ID-{{$la->emp_id}}</div></span>
                                </div>
                            </div>
                          
                    @endforeach 
                   
                    @endif 
                    
                </div>

        </div>
                     
        </div>
        
        
        
    </div>  

</div>
