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
    <div class="row justify-content-center mt-3 flex-column m-0" style="border-radius: 5px; font-size:12px; width:88%;">
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
            <img class="profile-image-selected" src="{{ $personData['image'] }}" alt="Employee Image">
           
           
            <p class="selected-name">
    @php
        // Split the name into parts
        $nameParts = explode(' ', $personData['name']);

        // Capitalize the first letter of the first name
        $firstName = isset($nameParts[0]) ? ucfirst(strtolower($nameParts[0])) : '';

        // Capitalize each part of the last name (all parts except the first)
        $lastNameParts = array_slice($nameParts, 1);
        $formattedLastName = implode(' ', array_map('ucfirst', array_map('strtolower', $lastNameParts)));
    @endphp
    {{ $firstName }} {{ $formattedLastName }}
</p>
            <svg class="close-icon-person"  
                 wire:click="removePerson('{{ $personData['emp_id'] }}')" 
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    @endforeach
</div>





               
       
    
        <div class="col-md-6 col-12"> 

@if($isNames)
<div class="col-md-6" style="border-radius: 5px; background-color: grey; padding: 8px; width: 330px; margin-top: 10px; height: 250px; overflow-y: auto;">
<div class="input-group4" style="display: flex; align-items: center; width: 100%;">

<input 
wire:model.debounce.500ms="searchTerm" placeholder="Search employees..."
style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" 
type="text" 
class="form-control" 
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
                        <img class="profile-image" src="{{ 'data:image/jpeg;base64,' . base64_encode($employee->image) }}" >
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
   

    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
<div class="container-fluid bg-white">
    <div class="row justify-content-center" style="margin-top:10px;width:80%">
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
         
            <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Cost Center</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px"><p style="color:blue">add</p></button>


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
                        <div class="col-md-10 mb-3" style="color: black; font-size: 12px;">{{$employee->emp_domain ??'-'}}</div>
                       
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
    <i wire:click="savedomainProfile('{{ $emp_id }}')" class="bx bx-save" style="cursor: pointer;"></i>
@else
    <i wire:click="editDomainProfile('{{ $emp_id }}')" class="bx bx-edit ml-auto" style="cursor: pointer;"></i>
@endif

            </i> 

             
           

         


             </div>
            
            </div>
          
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Grade</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -5px;margin-left:10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>






             </div>
        </div>
        </div>
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Designation</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
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
    <div class="row justify-content-center" style="margin-top:10px">
        <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
                    <i wire:click="cancellocationProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="savelocationProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editlocationProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-md-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
                    <i wire:click="cancelAddressProfile('{{ $employee->emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveAddressProfile('{{ $employee->emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editAddressProfile('{{ $employee->emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
             
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center " style="margin-top:10px">
    <div class="col-md-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
            <div class="bg-red p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
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
    <p>
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

    </div>
</div>
   


    </div>