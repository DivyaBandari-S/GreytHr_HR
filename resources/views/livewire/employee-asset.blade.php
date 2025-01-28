<div >
<style>
.filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    font-size: 10px;
   
}

.asset-dropdown {
    position: relative;
    display: inline-block;
    border-radius: 5px;
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    height: 30px;
    margin: 0;
    font-size: 10px;
    justify-content: center;
}

.asset-dropdown-toggle {
    display: flex;
    align-items: center;
    padding: 5px 5px;
    background-color: #ffffff;
    font-size: 10px;
    cursor: pointer;
    border-radius: 4px;
    width: auto; /* Adjust the width */
    justify-content: center;
    height: 30px;

}

.asset-dropdown-toggle i {
    margin-right: 8px;
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

.asset-dropdown-menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.asset-dropdown-menu ul li {
    padding: 5px;
    cursor: pointer;
}

.asset-dropdown-menu ul li:hover {
    background-color: #f0f0f0;
}

.asset-dropdown:hover .asset-dropdown-menu {
    display: block;
}

.search {
    width: 100%;
    padding: 5px;
    margin-bottom: 5px;
    font-size: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}



        .asset-dropdown-toggle:hover {
            background-color: #abcde8;
            color: white;
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


        /*asset table */
        /* Specific Table Styling */
.employee-requests-table {
    width: 80%;
    border-collapse: collapse;
    margin-bottom: 20px;
    align-items: center;
 
    justify-content: center;
    border-radius: 5px;
    margin-left: 40px;
   
}

/* Header Styling */
.employee-requests-table .table-header {
    background-color:#98cae0;
    font-size: 12px;
    font-weight: 500;
       border-radius: 5px;
    align-items: center;

}
.employee-requests-table .header-row {
    text-align: center;
    background-color:#98cae0;
    font-size: 12px;
    font-weight: 500;
    border-radius: 5px;
}
.employee-requests-table .header-column {
    padding: 10px;
    font-weight: 500;
    font-size: 12px;
    background-color:#E4E9F0;
    
    align-items: center;
    border: 1px solid #ddd;
  }


/* Body Styling */
.employee-requests-table .table-body {
    background-color: #fff;
}
.employee-requests-table .body-row {
   
    align-items: center;
    text-align: center;
}
.employee-requests-table .body-column {
    padding: 8px;
    font-size: 13px;
    justify-items: center;
}

/* Table Title */
.employee-requests-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
}

/* No Requests Styling */
.no-requests-title {
    font-size: 16px;
    color: red;
    margin: 10px 0;
}
.no-requests-message {
    font-size: 14px;
    color: #666;
    margin-top: 20px;
}

    </style>
<div class="row" style="margin-top:-20px;">
<ul class="nav custom-nav-tabs" role="tablist" >
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>
</div>

<div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center align-items-center"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
    The Assets page stores information about the Company's Assets that are given to the employees. Some common assets are laptops, mobile phones, and SIM cards. This information is beneficial while performing an inventory of all assets and during the Final Settlement process.
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
                 

                <div class="row justify-content-center align-items-center mt-2 "  >
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
        
 <div class="row">
 @if(!empty($selectedEmployeeId))
 <div class="row mt-2">
    
    <div class="col-md-10 " >
        <div class="newReq mt-2" style="align-items:end">
        <!-- Add Asset Button -->
    <button class="cancel-btn" style="margin-left:20px" wire:click="addAsset">Add Asset</button>
    <button wire:click="exportToExcel" class="cancel-btn" style="margin-left:20px">Export to Excel</button>
        </div>
    </div>
    </div>


    
@if($showAssetDialog)
<div class="modal fade show" tabindex="-1" role="dialog" style="display: block; overflow-y: auto;">
    <div class="modal-dialog modal-dialog-centered modal-lg align-items-center justify-content-center" role="document">
        <div class="modal-content" style="width: 90%;">
            <div class="modal-header helpdesk-modal align-items-center">
                <h5 class="modal-title helpdesk-title"><b>Assets</b></h5>
                <button type="button" class="btn-close" wire:click="$set('showAssetDialog', false)" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 500px; padding: 20px;">
            
                
                <!-- Form starts here -->
                @if($selectedEmployeeId)
                <form wire:submit.prevent="saveAsset" style="margin-top: 30px; font-size: 12px;align-items:center">

@csrf

<!-- Asset Type -->
<div class="row justify-content-center">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="assetType" class="col-cus-form-label cus-form-label">Asset Type</label>
                <select wire:model="asset_type" class="form-select form-control cus-form-label" id="assetType" required>
                    <option value="">Select Asset Type</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Keyboard">Keyboard</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Mouse">Mouse</option>
                </select>
            </div>
            @error('asset_type') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Asset Status -->
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="assetStatus" class="col-cus-form-label cus-form-label">Asset Status</label>
                <select wire:model="asset_status" class="form-select form-control cus-form-label" id="assetStatus" required>
                    <option value="">Select Asset Status</option>
                    <option value="Available">Available</option>
                    <option value="Damaged">Damaged</option>
                    <option value="Decommissioned">Decommissioned</option>
                    <option value="Issued">Issued</option>
                    <option value="Lost">Lost</option>
                    <option value="Returned">Returned</option>
                    <option value="Under Repair">Under Repair</option>
                </select>
            </div>
            @error('asset_status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Asset Details and Purchase Date -->
    <div class="row justify-content-center mt-2">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="assetDetails" class="col-cus-form-label cus-form-label">Asset Details</label>
                <input type="text" wire:model="asset_details" class="form-control cus-form-label" id="assetDetails" required>
            </div>
            @error('asset_details') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="purchaseDate" class="col-cus-form-label cus-form-label">Purchase Date</label>
                <input type="date" wire:model="purchase_date" class="form-control cus-form-label" id="purchaseDate" required>
            </div>
            @error('purchase_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Asset ID and Valid Till -->
  

    <!-- Original and Current Value -->
    <div class="row justify-content-center mt-2">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="originalValue" class="col-cus-form-label cus-form-label">Original Value</label>
                <input type="number" wire:model="original_value" class="form-control cus-form-label" id="originalValue" required>
            </div>
            @error('original_value') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="currentValue" class="col-cus-form-label cus-form-label">Current Value</label>
                <input type="number" wire:model="current_value" class="form-control cus-form-label" id="currentValue" required>
            </div>
            @error('current_value') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Warranty -->
<!-- Warranty -->
<div class="row justify-content-center mt-2 d-flex">
    <div class="col-md-4">
        <div class="mb-3">
          
            <div class="d-flex justify-content-start ml-3">
            <label for="warranty" class="col-cus-form-label cus-form-label">Warranty:</label>
                    <input type="radio" wire:model="warranty" class="form-check-input" id="warrantyYes" value="Yes">
                    <label class="form-check-label" for="warrantyYes">Yes</label>
               
                
                    <input type="radio" wire:model="warranty" class="form-check-input " id="warrantyNo" value="No" style="margin-left:5px">
                    <label class="form-check-label" for="warrantyNo">No</label>
               
            </div>
        </div>
        @error('warranty') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<!-- Brand -->
<div class="row justify-content-center mt-2">
    <div class="col-md-4">
        <div class="mb-3 d-flex align-items-start">
            <label for="brand" class="col-cus-form-label cus-form-label">Brand</label>
            <input type="text" wire:model="brand" class="form-control cus-form-label" id="brand">
        </div>
        @error('brand') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<!-- Model -->
<div class="row justify-content-center mt-2">
    <div class="col-md-4">
        <div class="mb-3 d-flex align-items-start">
            <label for="model" class="col-cus-form-label cus-form-label">Model</label>
            <input type="text" wire:model="model" class="form-control cus-form-label" id="model">
        </div>
        @error('model') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

<!-- Invoice No -->
<div class="row justify-content-center mt-2">
    <div class="col-md-4">
        <div class="mb-3 d-flex align-items-start">
            <label for="invoiceNo" class="col-cus-form-label cus-form-label">Invoice No</label>
            <input type="text" wire:model="invoice_no" class="form-control cus-form-label" id="invoiceNo">
        </div>
        @error('invoice_no') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>


    <!-- File Upload -->


    <!-- Remarks -->
    <div class="row justify-content-center mt-2">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="remarks" class="col-cus-form-label cus-form-label">Remarks</label>
                <textarea wire:model="remarks" class="form-control" id="remarks" style="font-size: 12px; width: 100%;" rows="3"></textarea>
            </div>
            @error('remarks') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>


<div class="modal-footer justify-content-center" style="padding: 10px;">
<button type="submit" class="submit-btn">
        {{ $asset_id ? 'Update Asset' : 'Create Asset' }}
    </button>
                <button wire:click="$set('showAssetDialog', false)" class="cancel-btn" type="button">Cancel</button>
            </div>
<!-- Submit Button -->

</form>

                @endif
            </div>
            
  
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>


@endif
   
  
    @if($requests->isNotEmpty() && !empty($selectedEmployeeId))
    @foreach((array)$selectedEmployeeId as $employeeId)
        {{-- Filter requests for the current employee --}}
        @php
            $employeeRequests = $requests->where('emp_id', $employeeId);
        @endphp

        {{-- Check if there are requests for the current employee --}}
        @if($employeeRequests->isNotEmpty())
        <div class="row justify-content-center align-items-center mt-2" >
            <div class="employee-requests-container d-flex justify-content-center align-items-center w-100">
                <table class="employee-requests-table table table-bordered table-striped align-items-center justify-content-center">
                    <thead class="table-header">
                        <tr class="header-row">
                            <th class="header-column">Employee ID</th>
                            <th class="header-column">Asset Type</th>
                            <th class="header-column">Asset ID</th>
                            <th class="header-column">Asset Status</th>
                            <th class="header-column">Asset Details</th>
                            <th class="header-column">Description</th>
                            <th class="header-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                    @foreach($employeeRequests as $request)
                        <tr class="body-row">
                            <td class="body-column">{{ $request->emp_id }}</td>
                            <td class="body-column">{{ $request->asset_type }}</td>
                            <td class="body-column">{{ $request->asset_id }}</td>
                            <td class="body-column">{{ $request->asset_status }}</td>
                            <td class="body-column">{{ $request->asset_details }}</td>
                            <td class="body-column">{{ $request->remarks }}</td>
                            <td class="body-column">
                                <i style="color:black; font-size:13px; cursor: pointer"
                                   class='fa fa-edit'
                                   title="Edit this record"
                                   wire:click="editAsset({{ $request->id }})">
                                </i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            {{-- No requests found for the current employee --}}
            <div class="no-requeasts d-flex justify-content-center align-items-center">
                <h3 class="no-requests-title">No requests found for Employee ID: {{ $employeeId }}</h3>
            </div>
        @endif
    @endforeach
@else
    {{-- No requests available --}}
    <p class="no-requests-message d-flex justify-content-center align-items-center">No requests available for this employee.</p>
@endif

  


   





@endif
 </div>






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