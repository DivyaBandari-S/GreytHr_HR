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
        View and update Previous Employment history of an employee, on the Previous Employment page. This information is helpful while bringing in changes related to Bonus, Promotion, and so on. 
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
  
 <div class="row">
 @if(!empty($selectedEmployeeId))
 <div class="row mt-5 justify-content-center align-items-center">
 <!-- First Column (White Background) -->
<div class="col-md-5 bg-white justify-content-between align-items-center p-3 shadow" style="border-radius: 8px;">
<div class="row mb-3">
    <!-- Title and Button in One Row -->
    <div class="col d-flex justify-content-between align-items-center">
        <h4 class="mb-0" style="font-size: 16px; font-weight: bold;">Previous Employment</h4>
        <button class="cancel-btn" wire:click="addPrevious" style="font-size: 12px;">Add</button>
    </div>
</div>



    <!-- Conditional card rendering: If there are no requests, use a card with a fixed height of 70px. -->
  <div class="row">
  @foreach($requests as $request)
            <div class="row">
              
                   
            <h8 style="font-size: 12px;">{{ $request->company_name }}</h8>

<p style="font-size: 12px;margin-top:2px"><strong>Employment Duration:</strong>

    {{ \Carbon\Carbon::parse($request->from_date)->format('d M Y') }} - 
    {{ \Carbon\Carbon::parse($request->to_date)->format('d M Y') }} 
    (Rel Exp: {{ $request->years_of_experience }}Y {{ $request->months_of_experience }}M)
</p>

<p style="font-size: 12px;"><strong>PF Member ID:</strong> {{ $request->pf_member_id ?? 'N/A' }}</p>
<p style="font-size: 12px;"><strong>Last Drawn Salary:</strong> {{ $request->last_drawn_salary ?? 'N/A' }}</p>
<p style="font-size: 12px;"><strong>Reason For Leaving:</strong> {{ $request->leaving_reason ?? 'N/A' }}</p>

                
            </div>
        @endforeach
  </div>
      
   
</div>

@if($showPreviousDialog)
<div class="modal fade show" tabindex="-1" role="dialog" style="display: block; overflow-y: auto;">
    <div class="modal-dialog modal-dialog-centered modal-lg align-items-center " role="document">
        <div class="modal-content" style="width: 90%;">
            <div class="modal-header helpdesk-modal align-items-center">
                <h5 class="modal-title helpdesk-title"><b>Experience Details</b></h5>
                <button type="button" class="btn-close"  wire:click="close" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 500px; padding: 20px;">
            <form wire:submit.prevent="saveExperience">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" wire:model.lazy="company_name" class="form-control">
                @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Designation</label>
                <input type="text" wire:model.lazy="designation" class="form-control">
                @error('designation') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <div class="form-group">
                <label>From Date</label>
                <input type="date" wire:model.lazy="from_date" wire:change="calculateYearsAndMonths" class="form-control">
                @error('from_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>To Date</label>
                <input type="date" wire:model.lazy="to_date" wire:change="calculateYearsAndMonths" class="form-control">
                @error('to_date') <span class="text-danger">{{ $message }}</span> @enderror
  
            </div>
        </div>
   
        <div class="col-md-5">
            <div class="row">
            <label>Years of Experience</label>
            <div class="col-md-3">
            <div class="form-group">
             
         
             <input type="text" wire:model.lazy="years_of_experience" class="form-control" readonly wire:poll>

         </div>
         <span>Years</span>
            </div>
            <div class="col-md-3">
            <div class="form-group">
             
         
             <input type="text" wire:model.lazy="months_of_experience" class="form-control" readonly wire:poll>

         </div>
         <span>Months</span>
            </div>
            </div>
         
        
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-md-4">
            <div class="form-group">
                <label>Nature of Duties</label>
                <textarea wire:model.lazy="nature_of_duties" class="form-control"></textarea>
                @error('nature_of_duties') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Leaving Reason</label>
                <textarea wire:model.lazy="leaving_reason" class="form-control"></textarea>
                @error('leaving_reason') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <div class="form-group">
                <label>PF Member ID</label>
                <input type="text" wire:model.lazy="pf_member_id" class="form-control">
                @error('pf_member_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Last Drawn Salary : ₹</label>
                <input type="number"  wire:model.lazy="last_drawn_salary" class="form-control">
                @error('last_drawn_salary') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>


            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>

@endif


    <!-- Second Column (Grey Background) -->
    <div class="col-md-5 "  style="margin-left:5px;margin-top:-40px">
        <div class="row " >
        <div class="row text-center ">
        <div class="d-flex justify-content-center">
        <p style="font-size: 12px;">Total Experience</p>
        <p style="font-size: 12px; margin-left:80px">Relevant Experience</p>
    </div>
</div>
@if($requests->isEmpty())
    <div class="row justify-content-center">
        <!-- Total Experience -->
        <div class="col-md-5 p-1 text-center rounded">
            <div style="width: 100%; height: 30px; border-radius: 8px; background: rgb(222, 220, 220);">
                <p style="font-size: 14px; font-weight: bold; margin-top: 5px;"></p>
            </div>
        </div>

        <!-- Relevant Experience -->
        <div class="col-md-5 p-1 text-center rounded">
            <div style="width: 100%; height: 30px; border-radius: 8px; background: rgb(222, 220, 220);">
                <p style="font-size: 14px; font-weight: bold; margin-top: 5px;"></p>
            </div>
        </div>
    </div>
@else
    @foreach ($requests as $request)
        <div class="row justify-content-center">
            <!-- Total Experience -->
            <div class="col-md-5 p-1 text-center rounded">
                <div style="width: 100%; height: 30px; border-radius: 8px; background: rgb(222, 220, 220);">
                    <p style="font-size: 14px; font-weight: bold; margin-top: 5px;">
                        {{ $request->years_of_experience }} {{ $request->years_of_experience == 1 ? 'Year' : 'Years' }}
                        {{ $request->months_of_experience }} {{ $request->months_of_experience == 1 ? 'Month' : 'Months' }}
                    </p>
                </div>
            </div>

            <!-- Relevant Experience -->
            <div class="col-md-5 p-1 text-center rounded">
                <div style="width: 100%; height: 30px; border-radius: 8px; background: rgb(222, 220, 220);">
                    <p style="font-size: 14px; font-weight: bold; margin-top: 5px;">
                        {{ $request->years_of_experience }} {{ $request->years_of_experience == 1 ? 'Year' : 'Years' }}
                        {{ $request->months_of_experience }} {{ $request->months_of_experience == 1 ? 'Month' : 'Months' }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@endif





        </div>
        <div class="row " >
        <div class="row text-center ">
    <div class="col-md-8 ">
        <p style="font-size: 12px;">Other Experience &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
    </div>
</div>

<div class="row justify-content-center">
    <!-- Total Experience -->
    <div class="col-md-5 p-1 text-center rounded">
        <div style="width: 100%; height: 30px; border-radius: 8px; background:rgb(222, 220, 220);"></div>
    </div>
    <div class="col-md-5 p-1 text-center rounded">
       
    </div>

</div>


        </div>
        <!-- Content here -->
    </div>
</div> 

 @endif
 </div>
 <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to calculate experience when From Date or To Date is changed
        document.getElementById('from_date').addEventListener('change', calculateExperience);
        document.getElementById('to_date').addEventListener('change', calculateExperience);

        function calculateExperience() {
            let fromDate = new Date(document.getElementById('from_date').value);
            let toDate = new Date(document.getElementById('to_date').value);

            // Ensure the From Date and To Date are valid and that From Date is not after To Date
            if (!isNaN(fromDate) && !isNaN(toDate) && fromDate <= toDate) {
                let years = toDate.getFullYear() - fromDate.getFullYear();
                let months = toDate.getMonth() - fromDate.getMonth();

                // Adjust years and months if months are negative
                if (months < 0) {
                    years--;
                    months += 12;
                }

                // Set the calculated experience directly to the input field
                document.getElementById('years_experience').value = years + " Years " + months + " Months";
            } else {
                document.getElementById('years_experience').value = ""; // Reset value if dates are invalid
            }
        }
    });
</script>


</div>
