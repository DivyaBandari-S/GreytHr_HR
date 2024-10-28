<div >

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
   
    </div>
    


    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
<div class="row" style="margin:0 auto;width:100%;justify-content:center;align-items:center;margin-left:120px">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link tab-doc {{ $activeTab1 === 'tab1' ? 'active' : '' }}" wire:click.prevent="switchTab('tab1')" href="#">Documents</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  tab-doc {{ $activeTab1 === 'tab2' ? 'active' : '' }}" wire:click.prevent="switchTab('tab2')" href="#">Letters</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  tab-doc {{ $activeTab1 === 'tab3' ? 'active' : '' }}" wire:click.prevent="switchTab('tab3')" href="#">Payslip</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  tab-doc{{ $activeTab1 === 'tab4' ? 'active' : '' }}" wire:click.prevent="switchTab('tab4')" href="#">Forms</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  tab-doc{{ $activeTab1 === 'tab5' ? 'active' : '' }}" wire:click.prevent="switchTab('tab5')" href="#">Company Policies</a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content">
        @if($activeTab1 === 'tab1')
            <div class="tab-pane fade show active">
            <div class="container mt-3">
                <div class="row justify-content-center">
                <div class="row mt-3">
    <!-- First Dropdown -->
<!-- First Dropdown -->
<div class="col-md-3">
<div class="dropdown">

<select class="custom-select-doc" name="category" onchange="this.form.submit()">
    <option value="All">All</option>
    <option value="Accounts & Statutory">Accounts & Statutory</option>
    <option value="Address" selected>Address</option>
    <option value="Background Verification">Background Verification</option>
    <option value="Education">Education</option>
    <option value="Experience">Experience</option>
    <option value="Joining Kit">Joining Kit</option>
    <option value="Previous Employment">Previous Employment</option>
    <option value="Projects">Projects</option>
    <option value="Qualification">Qualification</option>
    <option value="Vaccination Certificate">Vaccination Certificate</option>
</select>

            </div>
</div>

<!-- Second Dropdown -->
<div class="col-md-2 ml-2">
<div class="dropdown">

<select class="custom-select-doc" name="view" onchange="this.form.submit()" >

                        <option value="All" selected>All</option>
                        <option value="Published">Published</option>
                        <option value="Unpublished" >Unpublished</option>
                       
                    </select>
            </div>
</div>

    <div class="col-md-4 ">
        <button class="btn btn-primary " style="font-size:12px;" wire:click="addDocs">Add Documents</button>
    </div>
    <div class=" mt-2 bg-white d-flex align-items-center ">
                    <div class="d-flex ms-auto">
                        @if($showDocDialog)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;width:90% ">
                            <div class="modal-dialog modal-dialog-centered" role="document" >

                                <div class="modal-content">
                                    <div class="modal-header" style="background:white;">
                <p style="font-size:10px;color:black" class="modal-title" id="myModalLabel">You can upload employee documents, such as certificates, awards, or other qualifications.

Note: Your uploads are automatically private. Select the "Publish to Employee Portal" checkbox so that employees can view it.</p>
            
            </div>
                                 
                                <form method="POST" action="/upload-document" enctype="multipart/form-data">
                                <div class="form-group emp-doc">
    <label for="employeeId">Employee</label>
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
    <input type="text" class="form-control emp-doc-form" id="employeeId" name="employeeId" readonly 
           value="{{ $fullName }}@if($empId) (#{{ strtoupper($empId) }}) @endif">
</div>


    <div class="form-group emp-doc">
        <label for="documentName">Document Name</label>
        <input type="text" class="form-control emp-doc-form" id="documentName" name="documentName" placeholder="Please enter the Document Name" required>
        <small class="text-danger" style="display: none;">Please enter the Document Name</small>
    </div>

    <div class="form-group emp-doc">
        <label for="category">Category</label>
        <select class="form-control emp-doc-form" id="category" name="category" required>
            <option value="" disabled selected>Select a category</option>
            <option value="Accounts & Statutory">Accounts & Statutory</option>
            <option value="Address">Address</option>
            <option value="Background Verification">Background Verification</option>
            <option value="Education">Education</option>
            <option value="Experience">Experience</option>
            <option value="Joining Kit">Joining Kit</option>
            <option value="Previous Employment">Previous Employment</option>
            <option value="Projects">Projects</option>
            <option value="Qualification">Qualification</option>
            <option value="Vaccination Certificate">Vaccination Certificate</option>
        </select>
        <small class="text-danger" style="display: none;">Please select a Category</small>
    </div>

    <div class="form-group emp-doc">
        <label for="description">Description</label>
        <textarea class="form-control emp-doc-form" id="description" name="description" placeholder="Enter document description (optional)"></textarea>
    </div>

    <div class="form-group emp-doc mt-2">
        <label for="file ">File</label>
        <input type="file" id="file" name="file" class="hidden-input" accept=".pdf,.xls,.xlsx,.doc,.docx,.txt,.ppt,.pptx,.gif,.jpg,.png" required>

<!-- Visible upload button -->
<label for="file" class="upload-button">
        <i class="bx bx-upload upload-icon"></i> 
        <span style="color: blue;">Upload File</span>
    </label>
        
        <small class="text-danger" style="display: none;">Please select a file</small>
        <p class="form-text text-muted" style="font-size:8px">Note: Only PDF, XLS, XLSX, DOC, DOCX, TXT, PPT, PPTX, GIF, JPG, PNG files are accepted.</p>
    </div>


    <div class="form-group form-check ml-5">
        <input type="checkbox" class="form-check-input" id="publishToPortal" name="publishToPortal" >
        <label class="form-check-label" for="publishToPortal">Publish to Employee Portal</label>
    </div>

    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
</form>





                                </div>
                            </div>
                        </div>




                        <div class="modal-backdrop fade show"></div>
                        @endif
                    </div>
                </div>
</div>
</div>


                      
               
    <div class="alert alert-info d-flex align-items-center mt-5" role="alert" style="width:60%">
        <p class="main-text mb-0">There are no documents available!</p>
    </div>

                        <div id="details" style="display:none;">
                         
                        </div>
                  
                </div>
            </div>
            @elseif($activeTab1 === 'tab2')
    <div class="tab-pane fade show active">



    <div class="requests-container"> <!-- Container for the list of requests -->
                         
                                    @foreach($requests as $request)
                                        @if($request->emp_id === $emp_id) <!-- Only show requests for the current employee -->
                                            <div class="request-item"> 
                                                <p class="main-text">{{  $request->letter_type }} :</p>
                                                <p class="main-text">Employee ID:  {{ $request->emp_id }}</p>
                                         
                                                <p class="main-text">Priority:{{ $request->priority }}</p>  
                                                <p class="main-text"> Reason: {{ $request->reason  }} </p> 
                                                <p class="main-text">Status:  {{ $request->status }}</p>
                                            </div>
                                        <!-- Optional separator between requests -->
                                        @else
                                        <p>No letter requests found for employee ID:</p>
                                        @endif
                                    @endforeach
                              
                            </div>








    </div>


        @elseif($activeTab1 === 'tab3')
            <div class="tab-pane fade show active">
                <h3>Content for Tab 3</h3>
                <p>This is the content for the third tab.</p>
            </div>
        @elseif($activeTab1 === 'tab4')
            <div class="tab-pane fade show active">
                <h3>Content for Tab 4</h3>
                <p>This is the content for the fourth tab.</p>
            </div>
        @elseif($activeTab1 === 'tab5')
            <div class="tab-pane fade show active">
                <h3>Content for Tab 5</h3>
                <p>This is the content for the fifth tab.</p>
            </div>
        @endif
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

<script>
    function selectOption(option) {
        console.log("Selected: " + option);
        // You can implement further logic here, such as submitting a form or updating the UI.
    }
</script>



    </div>