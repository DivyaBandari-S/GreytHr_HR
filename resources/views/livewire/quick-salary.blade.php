<div>
<style>
    .table-container {
        display: flex;
        gap: 2px;
        align-items: flex-start;
        font-size: 12px;
    }

    /* First Table Styles */
    .custom-table-1 {
        width: 60%;
        border-collapse: collapse;
    }

    .custom-table-1 th, .custom-table-1 td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .custom-table-1 th {
        background-color: #98cae0;
    }

    /* Even and Odd row styling */
    .custom-table-1 tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .custom-table-1 tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Second Table Styles */
    .custom-scroll-container {
        overflow-x: auto;
        white-space: nowrap;
        position: relative;
    }
    .custom-scroll-container::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for Firefox */
    .custom-scroll-container {
        scrollbar-width: none;
    }

    .custom-table-2 {
        min-width: 1000px;
        border-collapse: collapse;
    }

    .custom-table-2 th, .custom-table-2 td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .custom-table-2 th {
        background-color: #98cae0;
    }

    /* Even and Odd row styling */
    .custom-table-2 tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .custom-table-2 tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
</style>

<div class="row justify-content-center mt-2" style="width:100%">
    <div class="col-md-11 custom-container d-flex flex-column">
        <div class="d-flex align-items-center mb-2">
            <p class="main-text mb-0" style="width:88%">
                After the Payroll for the selected month is run, greytHR provides a snapshot of employees' salary statements on the 
                <b>Quick Salary Statement</b> page. You can further customize the list and download it as a spreadsheet. 
            </p>
            <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
                {{ $showDetails ? 'Hide Details' : 'Info' }}
            </p>
        </div>

        @if ($showDetails)
            <div class="secondary-text">
                Explore HR Xpert by <span class="hide-text">Help-Doc</span>, watching How-to 
                <span class="hide-text">Videos</span> and <span class="hide-text">FAQ</span>
            </div>
        @endif
    </div>
</div>

<div class="row mt-5">
<div class="row mt-7 d-flex justify-content-end">
    <div class="col-md-9 text-end">
            <select class="dropdown-salary bg-white px-3 py-1" wire:model="selectedMonth" wire:change="changeMonth">
            @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }} style="background-color: #fff; color: #333; font-size: 13px;">
            {{ $label }}
        </option>
    @endforeach
            </select>
        </div>


    
</div>

<div class="row m-0">
    
    <div class="col-md-10 " >
        <div class="newReq mt-3" style="align-items:end">
       
        <button wire:click="export" class="cancel-btn">
        Export Excel
    </button>
        </div>
    </div>
    </div>

    <div>
    <!-- Month Selection Dropdown -->


    <div class="table-container mt-3">
    <!-- First Table: Employee Details -->
    <table class="custom-table-1">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($allSalaryDetails) && is_iterable($allSalaryDetails) && count($allSalaryDetails) > 0)
                @foreach ($allSalaryDetails as $index => $salary)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $salary->emp_id ?? 'N/A' }}</td>
                        <td>{{ $salary->first_name }} {{ $salary->last_name }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center font-bold">No data found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Scrollable Second Table: Salary Breakdown -->
    <div class="custom-scroll-container">
        <table class="custom-table-2">
            <thead>
                <tr>
                    <th>Joining Date</th>
                    <th>Days in Month</th>
                    <th>LOP Days</th>
                    <th>Basic</th>
                    <th>HRA</th>
                    <th>Conveyance</th>
                    <th>Special Allowance</th>
                    <th>Gross</th>
                    <th>PF</th>
                    <th>Income Tax</th>
                    <th>Pro Tax</th>
                    <th>Total Deductions</th>
                    <th>Net Pay</th>
                </tr>
            </thead>
            <tbody>
                
                @if (!empty($salaryData) && count($salaryData) > 0)
                    @foreach ($salaryData as $empId => $data)
                        <tr class="border">
                        @php
    $employee = !empty($allSalaryDetails) && is_iterable($allSalaryDetails)
        ? collect($allSalaryDetails)->firstWhere('emp_id', $empId)
        : null;
@endphp
<td class="border px-4 py-2 font-bold">
    {{ optional($employee)->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('d-m-y') : 'N/A' }}
</td>

<td class="border px-4 py-2 font-bold">
    {{ optional($employee)->total_working_days ?? 'N/A' }}
</td>
<td class="border px-4 py-2 font-bold">
    {{ optional($employee)->lop_days ?? 'N/A' }}
</td>
                            <td class="border px-4 py-2">{{ $data['salary']['basic'] ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $data['salary']['hra'] ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $data['salary']['conveyance'] ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $data['salary']['special_allowance'] ?? 0 }}</td>
                            <td class="border px-4 py-2 font-bold">{{ $data['salary']['gross'] ?? 0 }}</td>
                            <td class="border px-4 py-2 text-red-500">{{ $data['salary']['pf'] ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $data['salary']['esi'] ?? 0 }}</td>
                            <td class="border px-4 py-2 text-red-500">{{ $data['salary']['professional_tax'] ?? 0 }}</td>
                            <td class="border px-4 py-2">{{ $data['salary']['total_deductions'] ?? 0 }}</td>
                            <td class="border px-4 py-2 text-green-600 font-bold">{{ $data['salary']['net_pay'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13" class="text-center font-bold">No data found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>




</div>

</div>
</div>

