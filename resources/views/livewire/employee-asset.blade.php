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
                   <div class="profile">
    <div class="col m-0">
        <div class="row d-flex align-items-center">
            <!-- Search Input -->
            <div class="input-group4 d-flex align-items-center">
                <!-- Input Field with Profile Image -->
                <div class="position-relative">
                    @if($selectedEmployeeId && $selectedEmployeeFirstName)
                    <img 
        src="{{ $selectedEmployeeImage ? 'data:image/jpeg;base64,' . $selectedEmployeeImage : asset('images/user.jpg') }}" 
        alt="Profile Image" 
        class="profile-image-input"
        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;" 
    />
                    @else
                        <img 
                         src="{{ asset('images/user.jpg') }}" alt="Default Image"
                            
                            class="profile-image-input"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;"
                        />
                    @endif

               
                  <!-- Search Input Field -->
<input
    wire:model.debounce.500ms="searchTerm"
    aria-label="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    placeholder="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    type="text"
    class="form-control search-term"
    style="padding-left: 50px; padding-right: 35px;" 
/>

<!-- Display Close Icon if Employee is Selected -->
@if($selectedEmployeeId)
    <svg class="close-icon-person"  
         wire:click="removePerson('{{ $selectedEmployeeId }}')" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
         style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@else
    <!-- Display Search Icon if No Employee is Selected -->
    <i 
        class="bx bx-search search-icon position-absolute" width="20" height="20"
        wire:click="searchforEmployee"
        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
    </i>
@endif

                </div>
            </div>

            <!-- Conditional Display of Search Results -->
            @if($searchTerm)
                @if($employees->isEmpty())
                    <div class="search-container">
                        <p>No People Found</p>
                    </div>
                @else
                    <div class="search-container" style="max-height: 250px; overflow-y: auto;">
                        @foreach($employees as $employee)
                            @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
                                <label wire:click="selectEmployee('{{ $employee->emp_id }}')" style="cursor: pointer;">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <input 
                                                type="checkbox" 
                                                id="employee-{{ $employee->emp_id }}"
                                                wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"
                                                value="{{ $employee->emp_id }}"
                                                class="form-check-input custom-checkbox-information"
                                                {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                                        </div>
                                        <div class="col-auto">
                                            @if($employee->image && $employee->image !== 'null')
                                                <img class="profile-image" src="data:image/jpeg;base64,{{ $employee->image }}">
                                            @else
                                                <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h6 class="name" style="font-size: 12px; color: black;">
                                                {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }}
                                            </h6>
                                            <p style="font-size: 12px; color: grey;">(#{{ $employee->emp_id }})</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endif
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