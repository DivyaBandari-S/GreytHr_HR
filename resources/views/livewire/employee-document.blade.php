<div>

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
        This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.
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
    Employee: {{ ucfirst($selectedOption) }} 
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

   
    @if(!empty($selectedPeopleNames))


    @foreach($selectedPeopleNames as $emp_id => $selectedPersonName)
        @if(isset($employeeDetails[$emp_id]))
        <div class="container mt-3" style="margin-left:20px">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">Main</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">Documents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="letters-tab" data-toggle="tab" href="#letters" role="tab" aria-controls="letters" aria-selected="false">Letters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="payslip-tab" data-toggle="tab" href="#payslip" role="tab" aria-controls="payslip" aria-selected="false">Payslip</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <div class="row justify-content-center">
                <div class="row mt-3">
    <!-- First Dropdown -->
    <div class="col-md-2">
        <div class="dropdown">
            <button class="btn btn dropdown-toggle" type="button" id="category1Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:5px;border:1px solid black;font-size:12px">
                Category: All
            </button>
            <div class="dropdown-menu" aria-labelledby="category1Dropdown">
                <a class="dropdown-item" href="#">Option 1</a>
                <a class="dropdown-item" href="#">Option 2</a>
                <a class="dropdown-item" href="#">Option 3</a>
            </div>
        </div>
    </div>

    <!-- Second Dropdown -->
    <div class="col-md-2">
        <div class="dropdown">
            <button class="btn btn dropdown-toggle" type="button" id="category2Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:5px;border:1px solid black;font-size:12px">
               Filters : All
            </button>
            <div class="dropdown-menu" aria-labelledby="category2Dropdown">
                <a class="dropdown-item" href="#">Option 1</a>
                <a class="dropdown-item" href="#">Option 2</a>
                <a class="dropdown-item" href="#">Option 3</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary" style="font-size:12px">Add Documents</button>
    </div>
</div>
</div>

                <div class="col-md-10 custom-container d-flex flex-column mt-5">
                      
                    <div class="d-flex align-items-center mb-2">
                            <p class="main-text mb-0">There are no documents available!</p>
               
                        </div>
                        <div id="details" style="display:none;">
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Activity.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Documents.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="letters" role="tabpanel" aria-labelledby="letters-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Letters.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="payslip" role="tabpanel" aria-labelledby="payslip-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Payslip.</p>
            </div>
        </div>
    </div>
</div>

            </div>
        

       
    
            </div>
            <div class="col-md-2"></div>
        </div>

        @endif
        @endforeach
    </div>
    @endif

</div>


        </div>
        <script>
    $(document).ready(function () {
        $('#main-tab').tab('show');
    });

    function toggleDetails() {
        var details = document.getElementById("details");
        if (details.style.display === "none") {
            details.style.display = "block";
        } else {
            details.style.display = "none";
        }
    }
    
</script>

</div>
