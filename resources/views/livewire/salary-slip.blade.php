<div>

<div class="row" style="margin-top:-20px;width:100%">
<ul class="nav custom-nav-tabs" role="tablist" >
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>
</div>



<div>
    <ul class="nav nav-tabs mt-5" style="font-size: 12px;justify-content:start;padding-left:40px;width:80%">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'payslip' ? 'active' : '' }}" wire:click="setTab('payslip')">Payslip</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'reimbursement' ? 'active' : '' }}" wire:click="setTab('reimbursement')">Reimbursement Payslip</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'settlement' ? 'active' : '' }}" wire:click="setTab('settlement')">Settlement Payslip</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 're-settlement' ? 'active' : '' }}" wire:click="setTab('re-settlement')">Re-Settlement Payslip</a>
        </li>
    </ul>
    <div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center"  >
                        <div class="col-md-11 custom-container d-flex flex-column">
                        <div class="row d-flex align-items-center">
    <div class="col-10">
        <p class="main-text mb-0">
        The Payslips page enables you to generate Payslips for your employees. You can Download the Payslips in PDF format, or email the Payslips directly to your employees. In case you are using the Employee Self-Service or Employee Portal, you can release the payslips on the portal from the Published Info > Payslips page.
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
    <div class="col-md-10 d-flex justify-content-end mx-2">
            <select class="dropdown-salary bg-white px-3 py-1 mt-3" wire:model="selectedMonth" wire:change="changeMonth">
                @foreach($options as $value => $label)
                <option value="{{ $value }}" style="background-color: #fff; color: #333; font-size: 13px;">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-10 d-flex justify-content-end mx-2 " style="margin-top: 5px;">
        <button class="cancel-btn" wire:click="openRemarks">Add Remarks</button>
        @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Remarks</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Selected Employees:</strong></p>
                <ul>
              @if(!empty($employeeNames) && is_array($employeeNames))
              @foreach($employeeNames as $id => $fullName)
            <li>{{ $fullName }}</li>
        @endforeach
@else
    <p>No employees selected.</p>
@endif

                </ul>
                <textarea wire:model.defer="remarks" class="form-control" rows="3" placeholder="Enter remarks for all selected employees"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="cancel-btn" wire:click="closeModal">Close</button>
                <button type="button" class="submit-btn" wire:click="saveRemarks">Save</button>
            </div>
        </div>
    </div>
</div>


@endif
        </div>
    <div class="tab-content mt-1">
        @if ($activeTab == 'payslip')
        <div class="content" style="margin-left: 5px;padding-top:5px">
        <label>
            <input type="radio" wire:click="setSelectionType('all')" name="payslipType" value="all" 
                   {{ $selectionType == 'all' ? 'checked' : '' }}>
            All Employees
        </label>
        <label class="ms-3 p-3">
            <input type="radio" wire:click="setSelectionType('selected')" name="payslipType" value="selected" 
                   {{ $selectionType == 'selected' ? 'checked' : '' }}>
            Selected Employees
        </label>
        <label class="ms-3 p-3">
            <input type="radio" wire:click="setSelectionType('multiple')" name="payslipType" value="multiple" 
                   {{ $selectionType == 'multiple' ? 'checked' : '' }}>
            Multiple Payslips
        </label>
      
        @if ($selectionType == 'all')
   
            <p style="font-size: 12px;">if you select this option, payslips of all employees will be downloaded/mailed for the selected payroll.</p>
      

        <!-- Additional Options -->
        <div class="align-items-center">
          
        <div class="d-flex align-items-center justify-content-center">
      <label class="me-4 align-items-center ">
          <input type="radio" wire:click="setPayslipFormat('pdf')" name="payslipFormat" value="pdf"
                 {{ $payslipFormat == 'pdf' ? 'checked' : '' }}>
          Consolidated Payslip as PDF
      </label>
      
      <label>
          <input type="radio" wire:click="setPayslipFormat('zip')" name="payslipFormat" value="zip"
                 {{ $payslipFormat == 'zip' ? 'checked' : '' }}>
          Multiple Payslips as Zip
      </label>
  </div>
        </div>
        <div class="align-items-center mt-2">
      <div class="d-flex align-items-center justify-content-center">
      <div class="align-items-center mt-2">
    <div class="d-flex align-items-center justify-content-center">
        <button class="cancel-btn" wire:click.prevent="downloadPayslips">
            <i class="fas fa-download" style="color: blue;"></i> Download
        </button>
        <button class="cancel-btn " style="margin-left: 5px;" wire:click="sendPayslipallEmails">Send Mail</button>
    </div>
</div>


</div>
</div>
    @endif
    @if ($selectionType == 'selected')
    
    <div class="table-responsive">
    <table class="payroll-table table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll">
            </th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Remarks</th>
            <th>Payslip Released</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if(empty($allEmployees))  
            <!-- Show this row only if there are no employees -->
            <tr>
                <td colspan="6" class="text-center">No Data Exists</td>
            </tr>
        @else
            @php $dataFound = false; @endphp
            @foreach($allEmployees as $employee)
                @if(isset($salaryDivisions[$employee->emp_id]))
                    @php $dataFound = true; @endphp
                    <tr>
                        <td>
                            <input type="checkbox" wire:model="selectedEmployees" value="{{ $employee->emp_id }}">
                        </td>
                        <td>{{ $employee->emp_id }}</td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>

                        @php
                            $remarks = $salaryDivisions[$employee->emp_id]['remarks'] ?? 'No Remarks';
                            $isPayslip = $salaryDivisions[$employee->emp_id]['is_payslip'] ?? 0;
                        @endphp

                        <td>{{ $remarks }}</td>
                        <td>{{ $isPayslip == 1 ? 'Yes' : 'No' }}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach

            @if(!$dataFound)
                <tr>
                    <td colspan="6" class="text-center">No Data Exists</td>
                </tr>
            @endif
        @endif
    </tbody>
</table>


</div>

            <div class="align-items-center mt-5">
          
          <div class="d-flex align-items-center justify-content-center">
      <label class="me-4 align-items-center ">
          <input type="radio" wire:click="setPayslipFormat('pdf')" name="payslipFormat" value="pdf"
                 {{ $payslipFormat == 'pdf' ? 'checked' : '' }}>
          Consolidated Payslip as PDF
      </label>
      
      <label>
          <input type="radio" wire:click="setPayslipFormat('zip')" name="payslipFormat" value="zip"
                 {{ $payslipFormat == 'zip' ? 'checked' : '' }}>
          Multiple Payslips as Zip
      </label>
  </div>
      </div>
      <div class="align-items-center mt-2">
      <div class="d-flex align-items-center justify-content-center">
      <div class="align-items-center mt-2">
    <div class="d-flex align-items-center justify-content-center">
        <button class="cancel-btn" wire:click.prevent="downloadPayslips">
            <i class="fas fa-download" style="color: blue;"></i> Download
        </button>
        <button class="cancel-btn " style="margin-left: 5px;" wire:click="sendPayslipEmails">Send Mail</button>
    </div>
</div>


</div>
</div>
    </div>
@endif
@if ($selectionType == 'multiple')
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
        @if(!empty($selectedEmployeeId ))
        <div class="mt-3">
        <div class="form-check">
        <input type="radio" id="sixMonths" class="form-check-input" wire:model="filterType" value="6months">
        <label for="sixMonths" class="form-check-label">Last 6 Months</label>
    </div>

    <div class="form-check">
        <input type="radio" id="threeMonths" class="form-check-input" wire:model="filterType" value="3months">
        <label for="threeMonths" class="form-check-label">Last 3 Months</label>
    </div>

    <div class="form-check">
        <input type="radio" id="customRange" class="form-check-input" wire:model="filterType" value="custom">
        <label for="customRange" class="form-check-label">Custom Date Range</label>
    </div>
     
    </div>


    @if($filterType === 'custom')
    <div class="row mt-2">
        <div class="col-md-4">
        <label>From Date:</label>
        <input type="date" wire:model="startDate" class="form-control">

        </div>
        
        <div class="col-md-4">

        <label >To Date:</label>
        <input type="date" wire:model="endDate" class="form-control ">
        </div>
    </div>
@endif
 


@if($startDate && $endDate)
    <div class="alert alert-info mt-3" style="font-size: 12px;">
        Payslips available for 
        <b>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('F Y') }}</b> to 
        <b>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('F Y') }}</b> 
        for {{ ucfirst(strtolower($selectedEmployeeFirstName)) }} 
        {{ ucfirst(strtolower($selectedEmployeeLastName)) }}.
    </div>
@endif


    <div class="align-items-center mt-2">
      <div class="d-flex align-items-center justify-content-center">
      <div class="align-items-center mt-2">
    <div class="d-flex align-items-center justify-content-center">
        <button wire:click="multipledownloadPayslips" class="submit-btn mt-3">
        <i class="fas fa-download" style="color: blue;"></i>
Download
        </button>
        </div>
        </div>
        </div>
        </div>



@endif

    </div>
    @endif

        @elseif ($activeTab == 'reimbursement')
            <div>💰 Reimbursement Payslip Content Here...</div>
        @elseif ($activeTab == 'settlement')
            <div>✅ Settlement Payslip Content Here...</div>
        @elseif ($activeTab == 're-settlement')
            <div>🔄 Re-Settlement Payslip Content Here...</div>
        @endif
    </div>
</div>
</div>
<script>
    function toggleDateInput(show) {
        document.getElementById('dateInputs').style.display = show ? 'block' : 'none';
    }
</script>
</div>