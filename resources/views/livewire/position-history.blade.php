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
        The Position History page displays the complete history of all changes and career progression of an employee. You can also Edit various position attributes of an employee, such as Designation, Department, Grade, Location, etc., on this page.
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
   

    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
<div class="container-fluid align-items-center justify-content-center ">
    
<div class="row justify-content-center personalProfile mt-5" >
        <div class="col-md-3 " >
            <div class=" p-3 mb-3 editInformation">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Division</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-5px;margin-left:10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="division" placeholder="Division">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->department??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    {{ isset($employeeDetails[$emp_id]->updated_at) ? \Carbon\Carbon::parse($employeeDetails[$emp_id]->updated_at)->format('d M Y') :'' }}
    </div>
    </div>


</div>
<i>           @if($editingProfile)
    <i wire:click="cancelProfile('{{ $emp_id }}')" class="bx bx-x me-1" style="cursor: pointer;"></i>
    <i wire:click="saveProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
@else
    <i wire:click="editProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
@endif

            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3 editInformation" >
         
            <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Cost Center</p>
          
    <button type="button" class="btn btn-sm ml-auto add-details" >
        <p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
                
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
            
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingDomainProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Domain" placeholder="Domain">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                    <div class="col-md-10 mb-3" style="font-size: 12px;">
    {{ is_array($employee->emp_domain) ? implode(', ', $employee->emp_domain) : ($employee->emp_domain ?? '-') }}
</div>

                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    <p class="main-text">  {{ isset($employee->updated_at) ? \Carbon\Carbon::parse($employee->updated_at)->format('d M Y') : '' }}</p>
    </div>
    </div>


</div>
<i style="margin-left:5px">             
@if($currentEditingDomainProfileId == $employee->emp_id)
    <i wire:click="cancelDomainProfile('{{ $emp_id }}')" class="bx bx-x" style="cursor: pointer;"></i>
    <i wire:click="saveDomainProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
@else
    <i wire:click="editDomainProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
@endif

            </i> 

             
           

         


             </div>
            
            </div>
          
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3 editInformation">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Grade</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -5px;margin-left:10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>






             </div>
        </div>
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3 editInformation" >
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Designation</p>
          
    <button type="button" class="btn btn-sm ml-auto" wire:click="open" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px;">
        <p style="color:blue">add</p></button>


             </div>
             @if($showDialog)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;overflow-y:auto;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header helpdesk-modal align-items-center">
                        <h5  class="modal-title helpdesk-title"><b>Add Designation</b></h5>

                        </button>
                    </div>
                    <div class="modal-body">
                    <form>
     <!-- Category Type Input (Label and Input in One Line) -->
     <div class="form-group row align-items-center ">
        <label for="categoryType" class="col-md-4 col-form-label " style="margin-left:70px">Category Type</label>
        <div class="col-md-4 mt-3">
        <p style="font-size:12px">Designation</p>
        </div>
    </div>

    <!-- Department Input (Label and Input in One Line) -->


    <!-- Category Dropdown (Label and Input in One Line) -->
    <div class="form-group row align-items-center">
        <label for="category" class="col-md-4 col-form-label "  style="margin-left:70px">Category</label>
        <div class="col-md-4 mt-2" style="font-size:12px">
            <select class="form-control" id="category" name="category" style="font-size:12px">
                <option value="" hidden>Select</option>
                <option value="Category1">Accounts Executive</option>
                <option value="Business Development Executive">Business Development Executive</option>
                <option value="CEO">CEO</option>
                <option value="CFO">CFO</option>
                <option value="Customer Service Manager">Customer Service Manager</option>
                <option value="Director">Director</option>
                <option value="Finance Manager">Finance Manager</option>
                <option value="HR Director">HR Director</option>
                <option value="HR Executive">HR Executive</option>
                <option value="HR Manager">HR Manager</option>
                <option value="Jr. Accounts Executive">Jr. Accounts Executive</option>
                <option value="Jr. HR Executive">Jr. HR Executive</option>
                <option value="Jr. Software Engineer">Jr. Software Engineer</option>
                <option value="Office Assistant">Office Assistant</option>
                <option value="Operations Director">Operations Director</option>
                <option value="Operations Manager">Operations Manager</option>
                <option value="Product Manager">Product Manager</option>
                <option value="Project Manager">Project Manager</option>
                <option value="Quality Assurance Manager">Quality Assurance Manager</option>
                <option value="Sales Director">Sales Director</option>
                <option value="Software Engineer">Software Engineer</option>
                <option value="Sr. Software Engineer">Sr. Software Engineer</option>
                <option value="Tech Lead">Tech Lead</option>
                <option value="Tele Caller">Tele Caller</option>
                <option value="Trainee">Trainee</option>
                <!-- Add more options as needed -->
            </select>
        </div>
    </div>

    <!-- Effective From Date Input (Label and Input in One Line) -->
    <div class="form-group row align-items-center" style="font-size:12px">
        <label for="effectiveFrom" class="col-md-4 col-form-label"  style="margin-left:70px">Effective From</label>
        <div class="col-md-4 mt-2">
            <input type="date" class="form-control" id="effectiveFrom" name="effectiveFrom"  style="font-size:12px">
        </div>
    </div>

    <!-- Effective To Date Input (Label and Input in One Line) -->
    <div class="form-group row align-items-center">
        <label for="effectiveTo" class="col-md-4 col-form-label "  style="margin-left:70px">Effective To</label>
        <div class="col-md-4 mt-2">
            <input type="date" class="form-control" id="effectiveTo" name="effectiveTo"  style="font-size:12px">
        </div>
    </div>
    <!-- Submit Button -->
    <div class="ml-0 p-0 mt-3 d-flex gap-3 justify-content-center">
                            <button wire:click="submitHR" class="submit-btn" type="button">Save</button>
                            <button wire:click="close" class="cancel-btn" type="button" style="border: 1px solid rgb(2, 17, 79);">Cancel</button>
                        </div>
</form>

         

                      
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingdesignationProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="designation" placeholder="designation">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-10 mb-3" style="color: black; font-size: 12px;">{{$employee->empPersonalInfo->designation ??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    <p class="main-text">  {{ isset($employee->empPersonalInfo->updated_at) ? \Carbon\Carbon::parse($employee->empPersonalInfo->updated_at)->format('d M Y') : '' }}</p>
    </div>
    </div>


</div>
<i style="margin-left: 5px">
    @if($currentEditingdesignationProfileId == $employee->emp_id)
        <i wire:click="canceldesignationProfile('{{ $emp_id }}')" class="bx bx-x" style="cursor: pointer;"></i>
        <i wire:click="savedesignationProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
    @else
        <i wire:click="editdesignationProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
    @endif
</i>


             
           

         


             </div>
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center personalProfile" >
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto;box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Location</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditinglocationProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Location" placeholder="Location">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">   {{ !empty($employee->job_location) ? $employee->job_location : '-' }}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    <p class="main-text"> {{ isset($employee->updated_at) ? \Carbon\Carbon::parse($employee->updated_at)->format('d M Y') : '-' }}</p>
    </div>
    </div>


</div>      
<i style="margin-left:5px">
        @if($currentEditinglocationProfileId == $employee->emp_id)
                    <i wire:click="cancellocationProfile('{{ $emp_id }}')" class="bx bx-x" style="cursor: pointer;"></i>
                    <i wire:click="savelocationProfile('{{ $emp_id }}')" class="bx bx-save"style="cursor: pointer;"></i>
                @else
                    <i wire:click="editlocationProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto;box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Department</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingDepPersonalProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="department" placeholder="Department">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->empDepartment->department ??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    <p class="main-text"> {{ isset($employee->updated_at) ? \Carbon\Carbon::parse($employee->updated_at)->format('d M Y') : '-' }}</p>
    </div>
    </div>


</div>
<i style="margin-left:5px">
    @if($currentEditingDepPersonalProfileId == $employee->emp_id)
        <i wire:click="cancelDepartmentProfile('{{ $emp_id }}')" class="bx bx-x me-3" style="cursor: pointer;"></i>
        <i wire:click="saveDepartmentProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
    @else
        <i wire:click="editDepartmentProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
    @endif
</i>


             
           

         


             </div>
            </div>
          
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto;box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Job Mode</p>
        
    <button type="button" class="btn btn-sm ml-auto align-items-end mb-3" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left: 10px;;margin-top:-5px">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingJobProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Jobmode" placeholder="Jobmode">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->job_mode ?? '-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                   <p class="main-text"> {{ isset($employee->updated_at) ? \Carbon\Carbon::parse($employee->updated_at)->format('d M Y') : '-' }}</p>
    </div>
    </div>


</div>
<i style="margin-left:5px">                 

    @if($currentEditingJobProfileId == $employee->emp_id)
        <i wire:click="cancelProfile('{{ $emp_id }}')" class="bx bx-x me-3" style="cursor: pointer;"></i>
        <i wire:click="saveProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
    @else
        <i wire:click="editProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
    @endif
</i>


             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Resident</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditingAddressProfileId == $employee->emp_id)
    <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
    <input style="font-size: 12px" type="text" class="form-control" wire:model="present_address" placeholder="Address">
    </div>
@else
    <div class="row m-0" style="margin-top: 10px;">
      

            <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">
            {{$employee->empPersonalInfo->present_address ?? '-'}}
            </div>
       

        
    </div>
@endif

                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                   <p class="main-text">{{ isset($employee->empPersonalInfo->updated_at) ? \Carbon\Carbon::parse($employee->empSubDepartment->updated_at)->format('d M Y') : '-' }}</p> 
    </div>
    </div>


</div>
<i style="margin-left:5px">              
      @if($currentEditingAddressProfileId == $employee->emp_id)
                    <i wire:click="cancelAddressProfile('{{ $employee->emp_id }}')" class="bx bx-x"style="cursor: pointer;"></i>
                    <i wire:click="saveAddressProfile('{{ $employee->emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editAddressProfile('{{ $employee->emp_id }}')" class="bx bx-edit ml-auto"  style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
             
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center  personalProfile" >
    <div class="col-md-3 ">
            <div class="editInformation bg-white p-3 mb-3" >
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Sub Department</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px"">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditingPersonalSubProfileId == $employee->emp_id)
    <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
    <input style="font-size: 12px" type="text" class="form-control" wire:model="subDepartment" placeholder="Sub Department">
    </div>
@else
    <div class="row m-0" style="margin-top: 10px;">
      

            <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">
            {{$employee->empSubDepartment->sub_department ?? '-'}}
            </div>
       

        
    </div>
@endif

                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                   <p class="main-text">{{ isset($employee->empSubDepartment->updated_at) ? \Carbon\Carbon::parse($employee->empSubDepartment->updated_at)->format('d M Y') : '-' }}</p> 
    </div>
    </div>


</div>
<i style="margin-left:5px">               
    @if($currentEditingPersonalSubProfileId == $employee->emp_id)
        <i wire:click="cancelSubDepartmentProfile('{{ $employee->emp_id }}')" class="bx bx-x me-3" style="cursor: pointer;"></i>
        <i wire:click="saveSubDepartmentProfile('{{ $employee->emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
    @else
        <i wire:click="editSubDepartmentProfile('{{ $employee->emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
    @endif
</i>


             
           

         


             </div>
             
             
            </div>
          
        </div>
        <div class="col-md-3 ">
            <div class="editInformation bg-white p-3 mb-3" >
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Company Name</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px"">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditingPersonalCompanyProfileId == $employee->emp_id)
    <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
        <input style="font-size: 12px" type="text" class="form-control" wire:model="companyname" placeholder="Company Name">
    </div>
@else
    <div class="row m-0" style="margin-top: 10px;">
      

            <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">
            {{$employee->empPersonalInfo->company_name ?? '-'}}
            </div>
       

        
    </div>
@endif

                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
                    <p class="main-text">  {{ isset($employee->empPersonalInfo->updated_at) ? \Carbon\Carbon::parse($employee->empPersonalInfo->updated_at)->format('d M Y') : '' }}</p>
    </div>
    </div>


</div>
<i style="margin-left:5px">   
    @if($currentEditingPersonalCompanyProfileId == $employee->emp_id)
        <i wire:click="cancelCompanyProfile('{{ $employee->emp_id }}')" class="bx bx-x me-3" style="cursor: pointer;"></i>
        <i wire:click="saveCompanyProfile('{{ $employee->emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
    @else
        <i wire:click="editCompanyProfile('{{ $employee->emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
    @endif
</i>
 

             
           

         


             </div>
             
             
            </div>
          
        </div>
        <div class="col-md-3  ">
            </div>
            <div class="col-md-3 ">
            </div>
    </div>
</div>


            </div>
            <div class="col-md-2"></div>
        </div>

       
    
            </div>
            <div class="col-md-2"></div>
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




    
    
    
            <br>
        
    
    
     
      
        </div>

    </div>
</div>
   


    </div>