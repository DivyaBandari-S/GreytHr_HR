<div >

<div class="main__body">
       <div class="tab-container">
       <div class="tab-pane">
                    <button
                        type="button"
                        data-tab-pane="active"
                        class="tab-pane-item active"
                        onclick="tabToggle()">
                        <span class="tab-pane-item-title">01</span>
                        <span class="tab-pane-item-subtitle">main</span>
                    </button>
                    <button
                        type="button"
                        data-tab-pane="in-review"
                        class="tab-pane-item after"
                        onclick="tabToggle()">
                        <span class="tab-pane-item-title">02</span>
                        <span class="tab-pane-item-subtitle">Activity</span>
                    </button>
                   
                </div>

<!-- Tab Content -->
<div class="tab-page active" data-tab-page="active">
<div class="row justify-content-center"  >
                        <div class="col-md-8 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
        This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore greytHR by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-8 custom-container d-flex flex-column bg-white">
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text">Employee Type:</p>
                        <p  class="edit-heading ml-2">Current Employees</p>
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
                // Split the name into parts
                $nameParts = explode(' ', $personData['name']);

                // Capitalize the first letter of the first name
                $firstName = isset($nameParts[0]) ? ucfirst(strtolower($nameParts[0])) : '';

                // Get the last name parts (excluding emp_id)
                $lastNameParts = array_slice($nameParts, 1); // Get all parts after the first name

                // Capitalize each part of the last name
                $formattedLastName = implode(' ', array_map('ucfirst', array_map('strtolower', $lastNameParts)));

                // Combine first and last names
                $fullName = trim($firstName . ' ' . $formattedLastName);
            @endphp
            {{ $fullName }} <!-- Display only the full name -->
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
                                 
                                    @foreach($peopleData as $employee)
    @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
        <label wire:click="selectPerson('{{ $employee->emp_id }}')" class="search-container">
            <div class="row align-items-center">
                <div class="col-auto"> 
                    <input type="checkbox" id="employee-{{ $employee->emp_id }}" 
                           wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"  
                           wire:model="selectedPeople" 
                           value="{{ $employee->emp_id }}" 
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
        <img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTrb080MeuXwgT6ZB-x7qWZ3i_xQks-9xsRz5F9wWIyKbEEbGzL" alt="Employee Image" style="height: 180px; width:300px;align-items:end">
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


    <div class="row align-items-center bg-white">
    <div class="card mx-auto mt-3" style="width: 70%; height: auto;">
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
</div>





    </div>