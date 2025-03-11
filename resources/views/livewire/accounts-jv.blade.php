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
        The Payslip page displays the payslips of employees. This is the same view as seen by the employees on the Employee Self Service portal. The Lock & Publish button appears on this page if the Payslips are not yet released on the portal. Click this button to lock the Payroll and allow employees to view their Payslip
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


       
      
                 

 

</div>
</div>
<div class="row mt-2">
    <div class="col text-end" style="margin-left: -40px;">
        <select class="dropdown-salary bg-white px-3 py-1" wire:model="selectedMonth" wire:change="changeMonth">
            @foreach($options as $value => $label)
                <option value="{{ $value }}" style="background-color: #fff; color: #333; font-size: 13px;">
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3 p-0 d-flex">
    <div class="col-md-10 d-flex justify-content-end">
        <button class="cancel-btn" wire:click="cancelJV">
            Cancel JV
        </button>

        @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center mt-2">
                        <p class="d-flex align-items-center justify-content-center">
                            <span class="me-2">
                                <img src="{{ asset('images/icon-info.gif') }}" alt="Info Icon">
                            </span>
                            <span class="fw-bold mt-2">
                                Do you want to cancel the generated JV?
                            </span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn submit-btn" wire:click="closeModal">Cancel</button>
                        <button class="btn cancel-btn" wire:click="validateAndPublish">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-2 ml-4">
        <button class="submit-btn" wire:click="exportToExcel">
            Export JV
        </button>
    </div>
</div>

@if(isset($allEmployees) && isset($salaryDivisions) && !empty($salaryDivisions))
<div class="row">
    <p style="color:orange; font-weight:500; padding-left:70px">
        @if ($isCancelled)
        @if(!$showTable)
    <a href="#" class="text-danger" wire:click.prevent="showTable">
        Journal Voucher for {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
        cancelled on {{ \Carbon\Carbon::parse($cancelledAt)->format('d M Y H:i:s') }}
    </a>
@endif

        @elseif (!empty($selectedMonth))
            Generated on: {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
        @endif
    </p>
</div>



<!-- JV Details Table -->
@if($showTable)
<div class="d-flex justify-content-center mt-5">
    <table class="payroll-table text-center">
        <thead>
            <tr>
                <th>JV Item</th>
                <th>Account Code</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Employee Name</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = [
                    'basic' => 0,
                    'hra' => 0,
                    'conveyance' => 0,
                    'special_allowance' => 0,
                    'pf' => 0,
                    'employeer_pf' => 0,
                    'salary_payable' => 0,
                ];
            @endphp

            @foreach($allEmployees as $employee)
                @if(isset($salaryDivisions[$employee->emp_id]))
                    @php
                        $totals['basic'] += $salaryDivisions[$employee->emp_id]['basic'];
                        $totals['hra'] += $salaryDivisions[$employee->emp_id]['hra'];
                        $totals['conveyance'] += $salaryDivisions[$employee->emp_id]['conveyance'];
                        $totals['special_allowance'] += $salaryDivisions[$employee->emp_id]['special_allowance'];
                        $totals['pf'] += $salaryDivisions[$employee->emp_id]['pf'];
                        $totals['employeer_pf'] += $salaryDivisions[$employee->emp_id]['employeer_pf'];
                        $totals['salary_payable'] += $salaryDivisions[$employee->emp_id]['net_pay'];
                    @endphp
                @endif
            @endforeach

            <!-- Total Rows -->
            <tr><td>BASIC</td><td>BASIC</td><td>₹{{ number_format($totals['basic'], 2) }}</td><td>₹ 0.00</td><td></td></tr>
            <tr><td>HRA</td><td>HRA</td><td>₹{{ number_format($totals['hra'], 2) }}</td><td>₹ 0.00</td><td></td></tr>
            <tr><td>CONVEYANCE</td><td>CONVEYANCE</td><td>₹{{ number_format($totals['conveyance'], 2) }}</td><td>₹ 0.00</td><td></td></tr>
            <tr><td>SPECIAL ALLOWANCE</td><td>SPECIAL ALLOWANCE</td><td>₹{{ number_format($totals['special_allowance'], 2) }}</td><td>₹ 0.00</td><td></td></tr>
            <tr><td>PF</td><td>PF</td><td>₹ 0.00</td><td>₹{{ number_format($totals['pf'], 2) }}</td><td></td></tr>
            <tr><td>EMPLOYER PF</td><td>EMPLOYER PF</td><td>₹ 0.00</td><td>₹{{ number_format($totals['employeer_pf'], 2) }}</td><td></td></tr>
            <tr><td>SALARY PAYABLE</td><td>SALARY PAYABLE</td><td>₹ 0.00</td><td>₹{{ number_format($totals['salary_payable'], 2) }}</td><td></td></tr>
        </tbody>
    </table>
</div>
@endif
@endif










</div>

