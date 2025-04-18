<div>
        
<style>
        .report-name {
            font-weight: bold;
        }

        .ytd-links {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        .btn-group {
            display: flex;
        }

        .btn {
            padding: 10px 20px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #e0e0e0;
        }

        .btn.active {
            background-color: rgb(2, 17, 79);
            color: #fff;
        }

        .text-capitalize {
            text-transform: capitalize;
            font-size: 12px;
        }

        /* Optional: Add some margin between buttons */
        .btn+.btn {
            margin-left: -1px;
            /* Adjust to remove double border */
        }



        /* Content styles */
        .content {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .content.active {
            display: block;
        }

        .centered-image {
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .header_items {
            width: 150px;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 500;
            color: grey;

        }

        .header_items,
        td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: end;
            border-bottom: none;
            font-size: 12px;
        }

        .onest-td {
            background-color: white;
        }

        .table-rows:hover,
        .table-rows:hover .onest-td {
            background-color: #eaf0f6;
        }

        .ytd-columns {
            background-color: white;
        }

        .ytd-rows:hover,
        .ytd-rows:hover .ytd-columns {
            background-color: #eaf0f6;
        }
    </style>
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
        The YTD Summary page displays a summary of the earnings, deductions, and net pay of an employee for the selected year. 
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
                    </div>
                    @if(!empty($selectedEmployeeId && $selectedEmployeeFirstName))
                    <div class="row mt-7 d-flex justify-content-end">
    <div class="col-md-3 text-end"> <!-- Aligns the select box to the right -->
        <form method="GET" action="" class="m-0">
            <select name="financial_year" id="financial_year" class="form-control"
                wire:model="selectedFinancialYear" wire:change="SelectedFinancialYear">
                @foreach ($financialYears as $year)
                    <option value="{{ $year['start_date'] }}|{{ $year['end_date'] }}">
                        {{ $year['label'] }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="col-md-4 d-flex text-end" >
        @foreach($allSalaryDetails as $salary)
            <div>
            <button wire:click="downloadytd" class="cancel-btn pdf-download btn-primary px-3 rounded" style="display: inline-block;">
                <i class="fas fa-download"></i> Download
            </button>
            </div>
        @endforeach
    </div>
</div>

@foreach((array)$selectedEmployeeId as $employeeId)
                      
                      <!-- Blade Template -->
                      <table class="table centered-table custom-table-bg mt-3" style="width:90%; font-size:12px;background:none;border-radius:5px; border: 1px solid #ddd;">
                      <tbody class="payslip-body" style="border-radius:5px; border: 1px solid #ddd;">
                        <tr class="payslip-row" style="border-bottom:1px solid  #c5bfbf">
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">Employee No:</span> {{ $employeeDetails->emp_id }}
                          </td>
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">Name:</span> {{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }}
                          </td>
                        </tr>
                        <tr class="payslip-row" style="border-bottom:1px solid  #c5bfbf">
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">Bank:</span> {{ $empBankDetails->bank_name ?? 'N/A' }}
                          </td>
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">Bank Account No:</span> {{ $empBankDetails->account_number ?? 'N/A' }}
                          </td>
                        </tr>
                        <tr class="payslip-row" style="border-bottom:1px solid  #c5bfbf">
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">Joining Date:</span> {{ \Carbon\Carbon::parse($employeeDetails->hire_date)->format('d M Y') }}
                          </td>
                          <td class="data" style="width:50%;">
                            <span style="color:#778899;">PF No:</span> {{ $employeeDetails->pf_no ?? 'N/A' }}
                          </td>
                        </tr>
                      </tbody>
                      
                      </table>
                      <div class="row mt-5" style="margin-left:60px">
                      <div class="col-md-10 p-0">

<div class="table-responsive " style="overflow-x: auto; border: 1px solid #c5bfbf !important  ; border-radius: 5px; background-color: none;">
    <table style="border-collapse: collapse; width:100% ">
        <thead style="width:100%">
      
            <tr style="border-bottom: 1px solid #c5bfbf !important;border-top: 1px solid #c5bfbf !important; background-color: #98cae0">

                <th colspan="2" style="position: sticky; left: 0; background-color: #98cae0; z-index: 2;"> </th>
                <th class="header_items" colspan="2">Employee Contribution </th>
                <th class="header_items" colspan="2">Employer's Contribution </th>



            </tr>
            <tr style="border-bottom: 1px solid #c5bfbf !important;border-top: 1px solid #c5bfbf !important; background-color: #98cae0;border-bottom:1px solid  #c5bfbf;">
                <th class="header_items sticky-col" style="position: sticky; left: 0; background-color: #98cae0;; z-index: 2; width: 100px; text-align: left;border-right:1px solid  #c5bfbf;">Month</th>
                <th class="header_items sticky-col" style="position: sticky; left: 100px; background-color: #98cae0;; z-index: 2; width: 100px;border-right:1px solid  #c5bfbf;">Earnings In ₹.</th>
                <th class="header_items" style="width: 100px;text-align: center;border-right:1px solid  #c5bfbf;">PF</th>
                <th class="header_items" style="width: 100px;text-align: center;border-right:1px solid  #c5bfbf;">VPF</th>
                <th class="header_items" style="width: 100px;text-align: center;border-right:1px solid  #c5bfbf;">PF 2024</th>
                <th class="header_items" style="width: 100px;text-align: center;border-right:1px solid  #c5bfbf;">Pension Fund</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pfData as $month=> $data)
            <tr class="table-rows" style="background:none;border-bottom:1px solid  #c5bfbf;">
                <td class="onest-td" style="width: 100px;text-align:left; position: sticky; left: 0;  z-index: 2;background:none;border-right:1px solid  #c5bfbf;">{{\Carbon\Carbon::parse($month)->format('M Y') }}</td>
                <td class="onest-td" style="width: 100px;text-align:center;border-right:1px solid #c5bfbf !important;position: sticky; left: 100px;  z-index: 2;background:none;border-right:1px solid  #c5bfbf;">{{$data['basic']}}</td>
                <td style="width: 100px;background:none; text-align: center;border-right:1px solid  #c5bfbf;">{{$data['pf']}}</td>
                <td style="width: 100px;background:none; text-align: center;border-right:1px solid  #c5bfbf;">{{$data['vpf']}}</td>
                <td style="width: 100px;background:none; text-align: center;border-right:1px solid  #c5bfbf;">{{$data['employeer_pf']}}</td>
                <td style="width: 100px;background:none; text-align: center;border-right:1px solid  #c5bfbf;">{{$data['employeer_pension']}}</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #c5bfbf !important;border-bottom: 1px solid #c5bfbf !important;background-color:#eaf0f6">
                <td style="width: 100px;text-align:left; position: sticky; left: 0; background-color: #eaf0f6; z-index: 2;border-right:1px solid  #c5bfbf;">Total</td>
                <td style="width: 100px;text-align:center;border-right:1px solid #c5bfbf !important;position: sticky; left: 100px; background-color: #eaf0f6; z-index: 2;">{{$pftotals['basic']}}</td>
                <td style="width: 100px; text-align: center;border-right:1px solid  #c5bfbf;">{{$pftotals['pf']}}</td>
                <td style="width: 100px; text-align: center;border-right:1px solid  #c5bfbf;">{{$pftotals['vpf']}}</td>
                <td style="width: 100px; text-align: center;border-right:1px solid  #c5bfbf;">{{$pftotals['employeer_pf']}}</td>
                <td style="width: 100px; text-align: center;border-right:1px solid  #c5bfbf;">{{$pftotals['employeer_pension']}}</td>
            </tr>



            <!-- Add more rows here as needed -->
        </tbody>
    </table>
</div>
</div>
                      </div>
    
                      @endforeach
                      @endif
</div>
