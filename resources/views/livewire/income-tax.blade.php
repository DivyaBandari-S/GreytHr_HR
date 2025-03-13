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
                        <div class="row d-flex align-items-center">
    <div class="col-10">
        <p class="main-text mb-0">
        The <b>Income Tax </b>page displays the income tax component details of the selected employee and allows you to modify the same. You can also view the current Tax Regime selected for the employee. 

      Explore greytHR by Help-Doc, watching How-to Videos and FAQ.
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
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                    <div class="container d-flex justify-content-center align-items-center mt-2">
    <div class="card" style="width:95%;">
        <div class="card-header d-flex" style="background-color:#dbf0f9; color: black;border: 1px solid #ddd;height:auto;cursor:pointer" aria-expanded="false">
                    @if(!$showSearch)
        <div class="es-display empName" style="display: flex; ">
            <div class="es-display-name" style="margin-left: 5px; display: flex; align-items: center;">
            @if($selectedEmployeeId && $selectedEmployeeFirstName)
                    <img 
        src="{{ $selectedEmployeeImage ? 'data:image/jpeg;base64,' . $selectedEmployeeImage : asset('images/profile.png') }}" 
        alt="Profile Image" 
        class="profile-image-input"
        style="width: 30px; height: 30px; border-radius: 50%;" 
    />

               
                  <!-- Search Input Field -->
<span 
    
   
     class="es-employee-display ml-2"
    style="padding-left: 5px; cursor:pointer"  
>{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}</span>


@else
                <img src="{{ asset('images/profile.png') }}" alt="Default Image" class="profile-image-input" style="width: 30px; height: 30px; border-radius: 50%; margin-left: 5px;" />
                <span class="es-employee-display " style="font-size: 14px; font-weight: 500;cursor:pointer;margin-left:2px" wire:click="toggleSearch">select an employee...</span>
                <span class="empNo es-employee-no"></span>
                @endif
            </div>
            <div class="pull-left es-button">
    <button class="cancel-btn" wire:click="toggleSearch" style="border: 1px solid black; background: white; padding: 5px 10px; display: flex; align-items: center; justify-content: center; border-radius: 5px; gap: 5px;">

            <i class="fa fa-search" style="color: blue; font-size: 14px;"></i> <!-- Box Icon -->
  
        <span style=" color: blue;">Search</span>
    </button>
</div>


       
        </div>
    @else
        <!-- Search Input Group (Visible when search is clicked) -->
        <div class="col-md-3 mt-5">
            <div class="input-group mb-3" style="display: flex; align-items: center;height:30px">
                <!-- Dropdown icon on the left side -->
                <span class="input-group-text" id="basic-addon" style="background:#5bb75b; width: 30px; display: flex; justify-content: center; align-items: center;height:30px">
                    <button class="dropdown-toggle payroll" id="dropdownButton">
                        <!-- Box icon for dropdown -->
                    </button>
                </span>


        <input type="text" class="form-control" 
            wire:click="searchforEmployee"
         wire:model.debounce.600ms="searchTerm"
               aria-label="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    placeholder="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
            style="height: 30px; font-size: 12px;"
     
        >

        <!-- Close Button -->
        <button class="btn" 
            style="border: 1px solid silver; background:#5bb75b; width: 30px; height: 30px;
            display: flex; justify-content: center; align-items: center;" 
            wire:click="clearSelection" type="button">
            <i class="fa fa-times" style="color: white;"></i>
        </button>
    </div>

    <!-- Employee Dropdown -->
    @if($searchTerm && !$selectedEmployeeFirstName)
    @if(!isset($employees) || $employees->isEmpty())
        <div class="dropdown-menu show" style="display: block; font-size: 12px; padding: 8px;">
            <p class="m-0 text-muted">No People Found</p>
        </div>
    @else
            <div class="dropdown-menu show" style="display: block; max-height: 200px; overflow-y: auto; font-size: 12px;">
                @foreach($employees as $employee)
                    @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
                        <a class="dropdown-item employee-item" 
                            wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"
                            wire:key="emp-{{ $employee->emp_id }}">
                            {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }} ({{ $employee->emp_id }})
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    @endif
        </div>


    @endif
    <div class=" align-items-end">
    <div class="row" style="display: flex; align-items: flex-end;width:100%">
    <div style="width:80%" >
        <p style="font-size: 10px; color: grey;">INCOME TAX PROCESSED ON</p>
    </div>
   
</div>

<div class="row" style="display: flex; align-items: flex-end;">

    <div class="column">
        <p style="font-size: 10px; color: grey;">NEW TAX REGIME</p>
    </div>
</div>
    </div>




 
    </div>
    </div>
    </div>
    <div class="container mt-4">
    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#income">Income</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#prev-income">Income From Previous Employer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#exemptions">Exemptions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#perquisite">Perquisite</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#deductions">Deductions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#others">Others</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#house-property">House Property Income</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#regime">Regime</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#result">Result</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="income">
            <h4>Income</h4>
            <p>Content for Income tab.</p>
        </div>
        <div class="tab-pane fade" id="prev-income">
            <h4>Income From Previous Employer</h4>
            <p>Content for previous employer income tab.</p>
        </div>
        <div class="tab-pane fade" id="exemptions">
            <h4>Exemptions</h4>
            <p>Content for Exemptions tab.</p>
        </div>
        <div class="tab-pane fade" id="perquisite">
            <h4>Perquisite</h4>
            <p>Content for Perquisite tab.</p>
        </div>
        <div class="tab-pane fade" id="deductions">
            <h4>Deductions</h4>
            <p>Content for Deductions tab.</p>
        </div>
        <div class="tab-pane fade" id="others">
            <h4>Others</h4>
            <p>Content for Others tab.</p>
        </div>
        <div class="tab-pane fade" id="house-property">
            <h4>House Property Income</h4>
            <p>Content for House Property Income tab.</p>
        </div>
        <div class="tab-pane fade" id="regime">
            <h4>Regime</h4>
            <p>Content for Regime tab.</p>
        </div>
        <div class="tab-pane fade" id="result">
            <h4>Result</h4>
            <p>Content for Result tab.</p>
        </div>
    </div>
</div>


                    </div>
</div>
</div>
</div>