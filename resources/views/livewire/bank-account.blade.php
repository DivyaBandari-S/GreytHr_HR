<div>
<div style="margin-top: -20px;">
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
  <div class="row justify-content-center"  >
                        <div class="col-md-11 custom-container d-flex flex-column">
                        <div class="row d-flex align-items-center mb-2">
    <div class="col-10">
        <p class="main-text mb-0">
        This page helps you add/edit your employees' bank, PF, and ESI details. The page displays various sections such as Bank Account, ESI Account, PF Account, and Labour Welfare Fund.
        </p>
    </div>
    <div class="col-2 text-end">
        <p class="hide-text mb-0" style="cursor: pointer;" wire:click="toggleDetails">
            {{ $showDetails ? 'Hide Details' : 'Info' }}
        </p>
    </div>
</div>


                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> 
</div>
@endif

                        </div>
                    </div>

                 

                    <div class="row justify-content-center align-items-center mt-2 "  >
                <div class="col-md-11 custom-container d-flex flex-column bg-white" >
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text mt-1">Employee Type:</p>
                       
                        <div class="dropdown mt-1">
    <button class="btn dropdown-toggle dp-info" type="button" data-bs-toggle="dropdown" style="font-size:12px">
     {{ ucfirst($selectedOption) }} 
    </button>
    <ul class="dropdown-menu" style="font-size:12px;">
        <li class="updated-dropdown">
            <a href="#" wire:click.prevent="updateSelected('all')" class="dropdown-item custom-info-item">All Employees</a>
        </li>
        <li class="updated-dropdown">
            <a href="#" wire:click.prevent="updateSelected('current')" class="dropdown-item custom-info-item">Current Employees</a>
        </li>
        <li class="updated-dropdown">
            <a href="#" wire:click.prevent="updateSelected('past')" class="dropdown-item custom-info-item">Resigned Employees</a>
        </li>
        <li class="updated-dropdown">
            <a href="#" wire:click.prevent="updateSelected('intern')" class="dropdown-item custom-info-item">Intern</a>
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
    type="text"  wire:click="searchforEmployee"
    class="form-control search-term"
    style="padding-left: 50px; padding-right: 35px;"   wire:click="searchforEmployee"
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
   
    </div>
    
    @if ($showSuccessMessage)
 
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
    style="font-size: 12px; padding: 5px 10px; display: flex; align-items: center; justify-content: space-between; width: 300px; margin: 0 auto;" 
    wire:poll.5s="closeMessage">
    Profile updated successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" 
        style="font-size: 8px;align-items:center;margin-top:-7px">
        &times;
    </button>
</div>


@endif

    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)


    <div class="row align-items-center ">
    <div class="card mx-auto mt-3" style="width: 90%; height: auto;">
    <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px; background:white;">
        <p class="mb-0" style="font-weight: 500;">Bank/PF/ESI</p>
        <div style="font-size: 14px;">
            <i>
                @if($currentEditingBankProfile == $employee->emp_id)
                    <i wire:click="cancelBankProfile('{{ $employee->emp_id }}')" class="bx bx-x me-1" style="cursor: pointer;"></i>
                    <i wire:click="saveBankProfile('{{ $employee->emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editBankProfile('{{ $employee->emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i>
        </div>
    </div>



   



        <!-- Mother's Address Field -->
  
        
 <div class="card-row">
        <!-- Bank Name Field -->
        <div class="col-md-3 edit-headings">Bank Name
            @if($currentEditingBankProfile == $employee->emp_id)
                <div class="mb-2">
                    <input type="text" class="form-control mt-1 input-width" wire:model="BankName" placeholder="Bank Name">
                </div>
            @else
                <div class="editprofile mb-3">{{ $employee->empBankDetails->bank_name ?? '-' }}</div>
            @endif
        </div>

        <!-- Bank Branch Field -->
        <div class="col-md-3 edit-headings">Bank Branch
            @if($currentEditingBankProfile == $employee->emp_id)
                <div class="mb-2">
                    <input type="text" class="form-control mt-1 input-width" wire:model="BankBranch" placeholder="Bank Branch">
                </div>
            @else
                <div class="editprofile mb-3">{{ $employee->empBankDetails->bank_branch ?? '-' }}</div>
            @endif
        </div>

        <!-- IFSC Code Field -->
        <div class="col-md-3 edit-headings">IFSC Code
            @if($currentEditingBankProfile == $employee->emp_id)
                <div class="mb-2">
                    <input type="text" class="form-control mt-1 input-width" wire:model="IFSCCode" placeholder="IFSC Code">
                </div>
            @else
                <div class="editprofile mb-3">{{ $employee->empBankDetails->ifsc_code ?? '-' }}</div>
            @endif
        </div>

        <!-- Bank Account Number Field -->
        <div class="col-md-3 edit-headings">Bank Account Number
            @if($currentEditingBankProfile == $employee->emp_id)
                <div class="mb-2">
                    <input type="text" class="form-control mt-1 input-width" wire:model="BankAccountNumber" placeholder="Bank Account Number">
                </div>
            @else
                <div class="editprofile mb-3">{{ $employee->empBankDetails->account_number ?? '-' }}</div>
            @endif
        </div>
    </div>

    <div class="card-row">
        <!-- Bank Address Field -->
        <div class="col-md-3 edit-headings">Bank Address
            @if($currentEditingBankProfile == $employee->emp_id)
                <div class="mb-2">
                    <input type="text" class="form-control mt-1 input-width" wire:model="BankAddress" placeholder="Bank Address">
                </div>
            @else
                <div class="editprofile mb-3">{{ $employee->empBankDetails->bank_address ?? '-' }}</div>
            @endif
        </div>
    </div>

</div>







     
    </div>
@endif

    @endforeach
@endif

</div>
<div class="tab-page" data-tab-page="in-review">
<div class="row mt-3 ml-3" style="font-size:12px">
      
      <div id="employee-container">
      @foreach($employeess as $employee)
  <span style="font-weight:600">
      Added New Employee: ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}
  </span>
  @if (!$loop->first)<br>@endif
  <p class="main-text">
      Hire Date: 
      @if($employee->hire_date)
          @php
              $hireDate = \Carbon\Carbon::parse($employee->hire_date);
          @endphp
          ({{ $hireDate->format('M d, Y') }})
      @else
          N/A
      @endif
  </p>
  @if (!$loop->first)<br>@endif
@endforeach

</div>





    
    
    
            <br>
        
    
    
     
                </div>
</div>
       </div> <!-- Tab buttons -->
