<div>



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
    <div class="row justify-content-center"  >
                        <div class="col-md-11 custom-container d-flex flex-column">
                        <div class="row d-flex align-items-center">
    <div class="col-10">
        <p class="main-text mb-0">
        The CTC Payslip page displays the payslips of employees as per their annual CTC. 
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
                    <div class="row mt-3" style="margin-left:20px">
                    @if(!$showSearch)
        <div class="es-display empName" style="display: flex; align-items: center;">
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
                        <i class="bi bi-box" ></i> <!-- Box icon for dropdown -->
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
                    </div>
                    @if(!empty($selectedEmployeeId && $selectedEmployeeFirstName))
                    <div class="row mt-3 p-0 d-flex ">
    <div class="col-md-10 d-flex justify-content-end">
    @foreach($allSalaryDetails as $salary)
    <div>
    <button class="cancel-btn" wire:click.prevent="downloadPdf('{{ $salary->month_of_sal }}')" id="printBtn">
        <i class="fas fa-print" style="color: blue;"></i> Print
    </button>

    <div id="payslipContainer" style="display: none;"></div>


</div>

        @endforeach
    </div>
    <div class="col-md-2 ml-4">
            <select class="dropdown-salary bg-white px-3 py-1" wire:model="selectedMonth" wire:change="changeMonth">
                @foreach($options as $value => $label)
                <option value="{{ $value }}" style="background-color: #fff; color: #333; font-size: 13px;">{{ $label }}</option>
                @endforeach
            </select>
        </div>
</div>


    @foreach((array)$selectedEmployeeId as $employeeId)
                              @if(!empty($salaryDivisions))
<!-- Blade Template -->
<table class="table centered-table custom-table-bg mt-3" style="width:90%; font-size:12px;background:none;border-radius:5px">
<tbody class="payslip-body" style="border-radius:5px">
  <tr class="payslip-row">
    <td class="data" style="width:50%;">
      <span style="color:#778899;">Employee No:</span> {{ $employeeDetails->emp_id }}
    </td>
    <td class="data" style="width:50%;">
      <span style="color:#778899;">Name:</span> {{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }}
    </td>
  </tr>
  <tr class="payslip-row">
    <td class="data" style="width:50%;">
      <span style="color:#778899;">Bank:</span> {{ $empBankDetails->bank_name ?? 'N/A' }}
    </td>
    <td class="data" style="width:50%;">
      <span style="color:#778899;">Bank Account No:</span> {{ $empBankDetails->account_number ?? 'N/A' }}
    </td>
  </tr>
  <tr class="payslip-row">
    <td class="data" style="width:50%;">
      <span style="color:#778899;">Joining Date:</span> {{ \Carbon\Carbon::parse($employeeDetails->hire_date)->format('d M Y') }}
    </td>
    <td class="data" style="width:50%;">
      <span style="color:#778899;">PF No:</span> {{ $employeeDetails->pf_no ?? 'N/A' }}
    </td>
  </tr>
</tbody>

</table>



<br>
<table class="table table-bordered earnings-table" style="background:none;width:50%">
  <thead  class="pay-slips" style="background:#98cae0">
    <tr class="earnings-header" style="background:none">
      <th class="col-earnings">component</th>
      <th class="col-inr text-end">Rs.</th>
     
    </tr>
  </thead>
  <tbody>
 
    <tr class="earnings-row">
      <td class="earnings-label">FULL BASIC</td>
      <td class="earnings-value">₹{{number_format($salaryDivisions['basic'],2)}}</td>

    </tr>
    <tr class="earnings-row">
      <td class="earnings-label">FULL HRA</td>
      <td class="earnings-value">{{ number_format($salaryDivisions['hra'], 2) }}</td>

    </tr>
    <tr class="earnings-row">
      <td class="earnings-label">FULL CONVEYANCE</td>
      <td class="earnings-value">₹{{ number_format($salaryDivisions['conveyance'], 2) }}</td>

    </tr>
    <tr class="earnings-row">
      <td class="earnings-label">FULL MEDICAL ALLOWANCE</td>
      <td class="earnings-value">₹{{ number_format($salaryDivisions['medical_allowance'], 2) }}</td>

    </tr>
    <tr class="earnings-row">
      <td class="earnings-label">FULL SPECIAL ALLOWANCE</td>
      <td class="earnings-value">₹{{ number_format($salaryDivisions['special_allowance'], 2) }}</td>
    
    </tr>
    <tr class="earnings-row">
      <td class="earnings-label"><strong> ANNUAL CTC</strong></td>
      <td class="earnings-value"><strong><?php
$earnings = $salaryDivisions['earnings'];  // Assuming this holds the earnings value
$annualEarnings = $earnings * 12;
echo "₹" . number_format($annualEarnings);
?></strong></td>

    </tr>
   
  </tbody>
</table>



@else
<div class="d-flex justify-content-center align-items-center" style="width: 100%;  margin: auto;margin-top:5px">
    <div class="alert alert-info text-center" style="width: 80%;">Salary is on hold for this month</div>

</div>
@endif





  

        @endforeach
        @endif
</div>
</div>

</div>