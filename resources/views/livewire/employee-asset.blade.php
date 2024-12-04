<div >
<style>
        .filter-container {
            display: flex;
        justify-content: flex-start; /* Align items to the start */
        align-items: center;
        gap: 2px; /* Reduce the gap between dropdowns */
        flex-wrap: wrap;
        margin: 10px auto;
        padding: 10px;
      

        border-radius: 8px;
        }
        .asset-dropdown {
            position: relative;
            margin: 5px;
        }
        .asset-dropdown-toggle {
            display: inline-block;
            padding: 10px 20px;
            background-color:white;
            font-size: 12px;
         margin-left: 80px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .asset-dropdown-toggle:hover {
            background-color: #0056b3;
        }
        .asset-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 200px;
        }
        .asset-dropdown:hover .asset-dropdown-menu {
            display: block;
        }
        .asset-dropdown-menu ul {
            list-style: none;
            padding: 10px;
            margin: 0;
        }
        .asset-dropdown-menu li {
            padding: 8px 10px;
            cursor: pointer;
        }
        .asset-dropdown-menu li:hover {
            background-color: #f1f1f1;
        }
        .asset-search{
            width: calc(100% - 20px);
            margin: 0 auto 10px;
            margin-top: 10px;
           margin-left: 10px;
           height:30px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
<div class="main__body" >
<ul class="nav nav-tabs custom-nav-tabs" role="tablist" style="margin-top:67px">
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>

<div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
    View and manage soft copies of an employee's documents from the Employee Documents page. Documents available under the Documents tab can be Education Documents, Address Proof Documents, Previous employment-related documents, etc. Click Add Documents to add new documents. 
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-9 custom-container d-flex flex-column bg-white" >
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text mt-1">Employee Type:</p>
                       
                        <div class="dropdown">
                        <button class="btn btn dropdown-toggle dp-info" type="button" data-bs-toggle="dropdown" style="font-size:12px">
    {{ ucfirst($selectedOption) }} Employees
    <span class="arrow-for-employee"></span><span class="caret"></span>
</button>

    <span class="caret"></span></button>
    <ul class="dropdown-menu" style="font-size:12px; ">
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('all')" class="dropdown-item custom-info-item">All Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('current')" class="dropdown-item custom-info-item">Current Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('past')" class="dropdown-item custom-info-item">Resigned Employees</a>
    </li>
    <li class="updated-drodown" >
        <a href="#" wire:click.prevent="updateSelected('intern')" class="dropdown-item custom-info-item">Intern </a>
    </li>
</ul>

  </div>
         

                      
                    </div>
                 
                    <div class="profile" >
    <div class="col m-0">
     
    <div class="row d-flex align-items-center">
    <p class="main-text "  style="cursor:pointer" wire:click="NamesSearch">
        Search Employee:
    </p>
    @foreach($selectedPeopleData as $personData)
    <span class="selected-person d-flex align-items-center">

        <img class="profile-image-selected" src="data:image/jpeg;base64,{{ $personData['image'] ?? '-' }}">

        <p class="selected-name mb-0">
        @php

        // Split the name into parts (excluding emp_id)
        $nameParts = explode(' ', $personData['name']);

        // Capitalize the first letter of the first name
        $firstName = isset($nameParts[0]) ? ucfirst(strtolower($nameParts[0])) : '';

        // Get the last name parts, excluding the first name and emp_id
        $lastNameParts = array_filter($nameParts, function($part) {
            return !preg_match('/#\(.+\)/', $part); // Exclude emp_id in the form #(EMP_ID)
        });

        // Capitalize each part of the last name
        $formattedLastName = implode(' ', array_map('ucfirst', array_map('strtolower', $lastNameParts)));

        // Combine first name and formatted last name
        $fullName = trim( ' ' . $formattedLastName);

        // Extract emp_id (already in uppercase) from the name string
        preg_match('/#\((.*)\)/', $personData['name'], $matches);
        $empId = isset($matches[1]) ? $matches[1] : '';
    @endphp

    <!-- Display the formatted name and uppercase emp_id -->
    <div>
        <strong>{{ $fullName }}</strong> @if($empId) (#{{ strtoupper($empId) }}) @endif
    </div>
 

<!-- Display only the full name -->
        </p>

        <p class="emp-id mb-0" style="font-size: 12px; color: white;">
            (#{{ strtoupper(e((string) $personData['emp_id'])) }}) <!-- Display emp_id separately -->
        </p>

        <svg class="close-icon-person"  
             wire:click="removePerson('{{ e((string) $personData['emp_id']) }}')" 
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
            <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </span>
@endforeach











</div>





               
       
    
        <div class="col-md-6 col-12"> 

@if($isNames)
<div class="col-md-6 search-bar" >
<div class="input-group4" >

<input 
wire:model.debounce.500ms="searchTerm" placeholder="Search employees..."

type="text" 
class="form-control search-term" 
placeholder="Search for Emp.Name or ID" 
aria-label="Search" 
aria-describedby="basic-addon1"
/>

<div class="input-group-append" style="display: flex; align-items: center;">

<button 
  
 wire:click="searchforEmployee"  wire:model.debounce.500ms="searchTerm"  style="<?php echo ($searchEmployee) ? 'display: block;' : ''; ?>" 
    class="search-btn" 
    type="button" >
    <i class='bx bx-search' style="color: white;"></i>
</button>

<button 
    wire:click="closePeoples"   
    type="button" 
    class="close-btn rounded px-1 py-0" 
    aria-label="Close" 
   
>
    <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
</button>

</div>
</div>
<div>


<!-- Display the Search Results -->
@if ($peopleData && $peopleData->isEmpty())
                                    <div class="search-container">
                                        No People Found
                                    </div>
                                    @else
                                    @if(count($employees) > 0)
                                    @if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif



                                    @foreach($employees as $employee)

    @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
        <label wire:click="selectPerson('{{ $employee->emp_id }}')" class="search-container">
            <div class="row align-items-center">
                <div class="col-auto"> 
                    <input type="checkbox" id="employee-{{ $employee->emp_id }}" 
                           wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"  
                           wire:model="selectedPeople" 
                           value="{{ $employee->emp_id }}" class="form-check-input custom-checkbox-information"
                           {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                </div>
                <div class="col-auto">
                    @if($employee->image && $employee->image !== 'null')
                    
                        <img class="profile-image"  src="data:image/jpeg;base64,{{($people->image ??'-') }}" >
                    @else
                        @if($employee->gender == "Male")
                            <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                        @elseif($employee->gender == "Female")
                            <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                        @else
                            <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                        @endif
                    @endif
                </div>

                <div class="col">
                    <h6 class="name" class="mb-0" style="font-size: 12px; color: white;">
                        @php
                            // Capitalize the first letter of the first name
                            $formattedFirstName = ucfirst(strtolower($employee->first_name));

                            // Capitalize each part of the last name
                            $lastNameParts = explode(' ', strtolower($employee->last_name));
                            $formattedLastName = implode(' ', array_map('ucfirst', $lastNameParts));
                        @endphp
                        {{ $formattedFirstName }} {{ $formattedLastName }}
                    </h6>
                    <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $employee->emp_id }})</p>
                </div>
            </div>
        </label>
    @endif
@endforeach
@endif

@endif


</div>





</div>
@endif
</div> 
 
    </div>
</div>

                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="{{ asset('images/employeeleave.png') }}"  alt="Employee Image" style="height: 180px; width:280px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>
        
 
    @if(!empty($selectedPeople))
    <div class="row mt-3 p-0 justify-content-center">
    <div class="filter-container">
        <!-- Status Dropdown -->
        <div class="asset-dropdown">
            <a class="asset-dropdown-toggle"  data-bs-toggle="dropdown">Status: All     <span class="caret"></span></a>
            <div class="asset-dropdown-menu">
                <input class="search asset-search" type="text" placeholder="Search Status">
                <ul>
                    <li>All</li>
                    <li>Available</li>
                    <li>Damaged</li>
                    <li>Decommissioned</li>
                    <li>Issued</li>
                    <li>Lost</li>
                    <li>Returned</li>
                    <li>Under Repair</li>
                </ul>
            </div>
        </div>

        <!-- Asset Group Dropdown -->
        <div class="asset-dropdown">
            <a class="asset-dropdown-toggle">Asset Group: All <i class="caret"></i></a>
            <div class="asset-dropdown-menu">
                <input class="search" type="text" placeholder="Search Asset Group">
                <ul>
                    <li>All</li>
                    <li>Admin Asset</li>
                    <li>IT Asset</li>
                </ul>
            </div>
        </div>

        <!-- Asset Type Dropdown -->
        <div class="asset-dropdown">
            <a class="asset-dropdown-toggle">Asset Type: All <i class="caret"></i></a>
            <div class="asset-dropdown-menu">
                <input class="search" type="text" placeholder="Search Asset Type">
                <ul>
                    <li>All</li>
                    <li>Data Card</li>
                    <li>Laptop</li>
                    <li>Pen Drive</li>
                </ul>
            </div>
        </div>

        <!-- Active Status Dropdown -->
        <div class="asset-dropdown">
            <a class="asset-dropdown-toggle">Active: Active <i class="caret"></i></a>
            <div class="asset-dropdown-menu">
                <input class="search" type="text" placeholder="Search Active Status">
                <ul>
                    <li>All</li>
                    <li>Active</li>
                    <li>Inactive</li>
                </ul>
            </div>
        </div>

        <!-- Employee Dropdown -->
        <div class="asset-dropdown">
            <a class="asset-dropdown-toggle">Employee: All <i class="caret"></i></a>
            <div class="asset-dropdown-menu">
                <input class="search" type="text" placeholder="Type employee name or number">
                <ul>
                    <li>All</li>
                    <li>Employee 1</li>
                    <li>Employee 2</li>
                </ul>
            </div>
        </div>
    </div>

    </div>
    
    @if($employee)

   
<form wire:submit.prevent="create" style="margin-top: 10px; font-size: 12px;align-items:center">

        @csrf

        <!-- Asset Type -->
        <div class="row justify-content-center">
            <div class="col-md-3 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetType" class="col-cus-form-label cus-form-label ">Asset Type</label>
                    <select wire:model="asset_type" class="form-select form-control cus-form-label" id="assetType"  >
                        <option value="">Asset Type</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Keyboard">Keyboard</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Mouse">Mouse</option>
                    </select>
                 
                </div>
                @error('asset_type') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>
<div class="col-md-1">


</div>
            <!-- Asset Status -->
            <div class="col-md-3  align-items-end">
                <div class="mb-3 d-flex align-items-end">
                    <label for="assetStatus" class="col-cus-form-label cus-form-label mb-2">Asset Status</label>
                    <select wire:model="asset_status" class="form-select form-control cus-form-label" id="assetStatus" >
                        <option value="">Asset Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                @error('asset_status') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Details and Issue Date -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-3">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetDetails" class="col-cus-form-label cus-form-label">Asset Details</label>
                    <input type="text" class="form-control cus-form-label" id="assetDetails"  wire:model="asset_details">
                </div>
                @error('asset_details') <span class="text-danger mb-6">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-1">


</div>
            <div class="col-md-3">
                <div class="mb-3 d-flex align-items-center">
                    <label for="issueDate" class="col-cus-form-label cus-form-label">Issue Date</label>
                    <input type="date" wire:model="issue_date" class="form-control cus-form-label" id="issueDate" >
                </div>
                @error('issue_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset ID and Valid Till -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-3 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetID" class="col-cus-form-label cus-form-label">Asset ID</label>
                    <input type="text" wire:model="asset_id" class="form-control cus-form-label" id="assetID" >
                </div>
                @error('asset_id') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-1">


</div>

            <div class="col-md-3 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="validTill" class="col-cus-form-label cus-form-label">Valid Till</label>
                    <input type="date" wire:model="valid_till" class="form-control cus-form-label" id="validTill" >
                </div>
                @error('valid_till') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Value and Returned On -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-3 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetValue" class="col-cus-form-label cus-form-label">Asset Value</label>
                    <input type="text" wire:model="asset_value" class="form-control cus-form-label" id="assetValue"  style="font-size: 12px;">
                </div>
                @error('asset_value') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-1">


</div>

            <div class="col-md-3  ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="returnedOn" class="col-cus-form-label cus-form-label">Returned On</label>
                    <input type="date" wire:model="returned_on" class="form-control cus-form-label" id="returnedOn"  style="font-size: 12px;">
                </div>
                @error('returned_on') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Remarks -->
        <div class="row justify-content-center mt-2">
    <div class="col-md-3">
        <div class="mb-3 d-flex align-items-start">
            <label for="remarks" class="col-cus-form-label cus-form-label">Remarks</label>
            <textarea wire:model="remarks" class="form-control" id="remarks" style="font-size: 12px; width: 400px;"></textarea>
        </div>
        @error('remarks') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3">

    </div>
    <div class="col-md-1">


</div>
</div>
     

        <!-- Submit Button -->
        <div class="row justify-content-center">
            <div class="col-md-6 mr-auto">
                <button type="submit" class="submit-btn" >Submit</button>
            </div>
        </div>
    </form>

<!-- Form ends here -->
@endif


@endif





                    </div>
              
           

          
                    <div class="tab-page" data-tab-page="in-review">
        <div class="row mt-3 ml-3" style="font-size:12px">
      
        <div id="employee-container">


</div>





      
      
      
              <br>
          
      
      
       
                  </div>
      
        </div>

    </div>

</div>


    </div>