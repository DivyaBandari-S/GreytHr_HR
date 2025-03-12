<div>
    <style>
        .bold-items {
            font-weight: bold;
        }

        .employeMain {
            padding: 20px;
        }

        .emp-sal-heading {
            font-size: 1.50rem;
        }

        .Employee-select-leave {
            font-weight: 500;
            font-size: var(--main-headings-font-size);
            color: var(--main-heading-color);
        }

        .Employee-details-hr {
            border: 1px solid rgb(80, 80, 218);
            align-items: center;
            border-radius: 30px;
            padding: 5px 10px;
            margin-bottom: 10px;
        }

        .Employee-details-img-details-hr {
            width: fit-content;
            align-items: center;
        }

        .Emp-name-leave-details {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 0px;
            color: var(--sub-heading-color);
        }

        .Emp-id-leave-details {
            font-size: 10px;
            color: var(--label-color);
            margin-bottom: 0px;
        }

        .analytic-view-all-search-bar {
            display: flex;
            padding: 10px 0px;
            justify-content: space-between;
            /* Adjust spacing between items */
            align-items: center;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .analytic-view-all-search-bar input[type="text"] {
            width: 200px;
            padding: 6px 28px 6px 10px;
            /* Adjust padding for right space */
            border: 1px solid #ccc;
            border-radius: 18px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            color: #666;
            pointer-events: none;
        }

        .custom-border {
            background-color: rgb(245, 246, 248);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid aliceblue;
            width: 260px;
            margin-left: 5px;
        }

        .profile-image {
            height: 32px;
            width: 32px;
            background-color: lightgray;
            border-radius: 50%;
        }

        .employeType,
        .searchEmploye {
            color: grey;
        }

        .employe_Type {
            border: none;
        }

        .search-bar {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .emp_sal_logo {
            display: flex;
            justify-content: center;
        }

        .emp_sal_img {
            width: 50%;
            height: 80%;
        }

        .emp-sal-table th,
        .emp-sal-table td {
            text-align: center;
            vertical-align: middle;
            background-color: #F8F8F8;
        }

        .emp-sal1-table th,
        .emp-sal1-table td {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 3px;
            font-size: 12px;
        }

        .emp-sal1-table th {
            font-size: 13px;
            background-color: #EBEFF7;
        }

        .emp-sal-table th {
            font-size: 13px;

        }

        .emp-sal-table td {
            font-size: 12px;
        }

        .arrow_icn {
            width: 10px;
            height: 10px;
        }

        .chart-container {
            /* position: relative; */
            width: 100%;
            height: 200px;
            margin-left: 15px
        }

        .ctc {
            font-size: 13px;
            font-weight: 500;
        }

        .month_ctc {
            font-size: 13px;
            font-weight: 500;
            color: #32CD32;
        }

        .salaryComponents-table {
            border-collapse: collapse;
            /* Ensures borders don't double up */
            width: 100%;
            /* margin: 10px;     */
        }

        .salaryComponents-table th,
        .salaryComponents-table td {
            border-radius: 5px;
            border: 1px solid silver;
            padding: 5px;
            /* text-align: left;     */
            font-size: 13px;
        }

        .salary-amount {
            text-align: right;
        }

        .salary-component {
            text-align: left;
        }

        .emp-datails-table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 5px;
        }

        .emp-datails-table td {

            border: 1px solid silver;
            padding: 5px;
            /* text-align: left;     */
            font-size: 13px;
            width: 50%;

        }


        .Employee_list tr th,
        .Employee_list tr td {
            padding: 5px;
            font-size: 10px;
            text-align: left;
        }

        .Employee_list thead tr {
            background-color: #eff5f7;
        }

        .Employee_list tbody tr:hover {
            background-color: #eff5f7;
        }

        .selected-row {
            background-color: #cce5ff;
            /* Light blue background */
        }



        @media (min-width: 360px) and (max-width: 550px) {
            .emp_sal_img {
                width: 87%;
                height: 64%;
            }

            .emp-sal-heading {
                font-size: 1.15rem;
            }

            .emp-sal-table-graph {
                display: flex;
                flex-direction: column;
                gap: 10px
            }

            .emp-sal-rev-table {
                display: flex;
                flex-direction: column;
                gap: 10px
            }

            .search-bar {
                width: 100%;
            }

            .emp-sal-table3 {
                margin-top: 15px;
            }

        }
    </style>
    <div class="employeMain">
        @if($isShowHelp)
        <div class="help-section d-flex" style="padding: 10px;font-size:13px;background-color:#f5feff">
            <div>
                <p class="m-0">The <span class="bold-items"> Employee Salary </span> page allows you to filter employees and view their <span class="bold-items"> Salary Revision History</span>. Select the employee and click <span class="bold-items"> Revise Salary</span> to revise the salary of employees.</p>
                <p  class="m-0">You can also <span class="bold-items">Compare the Salary</span> of an employee against their peers. Specify who the peers of an employee are by clicking on <span class="bold-items">Define Peers</span>. Click on <span class="bold-items"> Export Excel</span> to download the data in an Excel file. </p>

            </div>
            <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
        </div>
        @else
        <div style="padding:10px;background-color:var(--light);text-align:end">
            <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
        </div>
        @endif
    </div>
    @if($showPage1)
    <div class="employeMain">
        <div class="bg-white">
            <div class="row" style="padding: 10px;">
                <div class="col-md-8 col-12">
                    <p class="employee-leave-start-para">Start searching to see specific employee details here</p>
                    <div class="d-flex mb-2">
                        <label style="margin-top: 7px;" for="">Employee Type:</label>
                        <select class="Employee-select-leave form-select" style="border: none; margin-left: 10px;width:fit-content"
                            wire:model="employeeType" wire:change="filterEmployeeType">
                            <option value="all">All</option>
                            <option value="active">Current Employees</option>
                            <option value="non-active">Past Employees</option>
                        </select>
                    </div>
                    <div>
                        <div wire:click="searchFilter" style="cursor: pointer;">
                            <label>Search Employee</label>
                        </div>

                        @if($selectedEmployee)
                        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                            <div class="col-md-4 col-6">
                                <div class="d-flex Employee-details-hr">
                                    <div class="d-flex Employee-details-img-details-hr">
                                        @if ($selectedEmployeesDetails && $selectedEmployeesDetails->isNotEmpty())
                                        @php $selectedEmployee = $selectedEmployeesDetails->first(); @endphp
                                        @if ($selectedEmployee->image)
                                        <img src="data:image/jpeg;base64,{{ $selectedEmployee->image }}"
                                            alt="base" class="profile-image" />
                                        @else
                                        <!-- Gender-based default image -->
                                        @if ($selectedEmployee->gender == 'Male')
                                        <img class="profile-image"
                                            src="{{ asset('images/male-default.png') }}"
                                            alt="Default Male Image">
                                        @elseif ($selectedEmployee->gender == 'Female')
                                        <img class="profile-image"
                                            src="{{ asset('images/female-default.jpg') }}"
                                            alt="Default Female Image">
                                        @else
                                        <img class="profile-image" src="{{ asset('images/user.jpg') }}"
                                            alt="Default Image">
                                        @endif
                                        @endif
                                        <div style="margin-left: 15px; color: var(--label-color)">
                                            <p class="Emp-name-leave-details">
                                                {{ ucfirst(strtolower($selectedEmployee->first_name)) }}
                                                {{ ucfirst(strtolower($selectedEmployee->last_name)) }}
                                            </p>
                                            <p class="Emp-id-leave-details">{{ $selectedEmployee->emp_id }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div style="margin-left: auto;">
                                        <p style="margin-bottom: 0px; cursor:pointer; font-weight: 500; font-size:20px"
                                            wire:click="selectEmployee(null)">x</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($showSearch)
                        <div class="analytic-view-all-search-bar">
                            <div class="search-wrapper">
                                <input wire:click="searchFilter" wire:input="searchFilter"
                                    wire:model.debounce.500ms="search" type="text" placeholder="Search...">
                                <i class="search-icon bx bx-search"></i>
                            </div>
                        </div>
                        @endif

                        @if ($showContainer)
                        <div
                            style="background: white; padding: 10px; border: 1px solid black; border-radius: 5px; width: 310px; position: absolute; z-index: 1000;">
                            @if ($employees->isNotEmpty())
                            @foreach ($employees as $employee)
                            <div class="row custom-border"
                                style="display: flex; align-items: center; height: fit-content; cursor: pointer;"
                                wire:click="selectEmployee('{{ $employee->emp_id }}')">
                                <div class="col-3 text-center">
                                    @if ($employee->image)
                                    <img src="data:image/jpeg;base64,{{ $employee->image }}"
                                        alt="base" class="profile-image" />
                                    @else
                                    @if ($employee->gender == 'Male')
                                    <img class="profile-image"
                                        src="{{ asset('images/male-default.png') }}"
                                        alt="Default Male Image">
                                    @elseif ($employee->gender == 'Female')
                                    <img class="profile-image"
                                        src="{{ asset('images/female-default.jpg') }}"
                                        alt="Default Female Image">
                                    @else
                                    <img class="profile-image"
                                        src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                    @endif
                                    @endif
                                </div>
                                <div class="col-7">
                                    <div style="font-size: var(--normal-font-size); color: var(--label-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;"
                                        title="{{ $employee->first_name }} {{ $employee->last_name }}">
                                        {{ ucfirst(strtolower($employee->first_name)) }}
                                        {{ ucfirst(strtolower($employee->last_name)) }}
                                    </div>
                                    <div
                                        style="font-size: var(--normal-font-size); color: var(--label-color);">
                                        {{ $employee->emp_id }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p>No employees found.</p>
                            @endif
                        </div>
                        @else
                        @endif
                    </div>
                </div>
                <div class="col-4 emp_sal_logo">
                    <img class="mt- emp_sal_img" src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRLhNu6xKTL2EfK_OTYoaBuqM-Irlz3eB03SmNtbY2NIn8W0NOz" alt="">
                </div>
            </div>
        </div>
        @if($isNoDataFound)
        <div class="text-center">
            No Salary Revision Found
        </div>
        @endif

        @if($decryptedData)
        <div class="row bg-light emp-sal-rev-table p-0">
            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered bg-light emp-sal-table">
                        <thead class="">
                            <tr>
                                <th>Last Revision</th>
                                <th>Medium Revision Period</th>
                                <th>Max Revision Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$decryptedData[0]['time_gap'] }}</td>
                                <td>{{$decryptedData[0]['medium_revision_period'] }}</td>
                                <td>{{$decryptedData[0]['max_revision_period'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <button wire:click="showRevisedSalary" class="btn bg-primary text-white mb-2">Revise Salary</button>
            </div>
        </div>
        @endif

        <div class="row bg-light emp-sal-table-graph">
            @if($decryptedData)
            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered bg-light emp-sal-table">
                        <thead class="">
                            <tr>
                                <th>Effective Date</th>
                                <th>Payout Month</th>
                                <th>ANNUAL CTC</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($decryptedData as $revisionData)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('d M, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('M, Y') }}</td>
                                <td>
                                    <p style="font-weight: bold;margin-bottom:0px">Rs {{number_format( $revisionData['revised_ctc'],2) }}</p>
                                    @if ($loop->last)
                                    <p>0.00% (Rs 0.00)</p>
                                    @else

                                    <p>
                                        @if($revisionData['percentage_change_diff']>0)
                                        <img class="arrow_icn" src="{{ asset('images/up-arrow-icon.png') }}" alt="">
                                        <span class="month_ctc">+{{$revisionData['percentage_change_diff']}} </span>(Rs {{number_format($revisionData['difference_amount'])}})
                                        @elseif($revisionData['percentage_change_diff'] < 0)
                                            <img class="arrow_icn" style="height: 13px; width:13px" src="{{ asset('images/down-arrow-icon.png') }}" alt="">
                                            <span class="month_ctcup" style="color: red;">{{$revisionData['percentage_change_diff']}} </span>(Rs {{number_format($revisionData['difference_amount'])}})
                                            @else

                                            @endif
                                    </p>
                                    @endif

                                </td>
                                <td>{{$empDetails->job_role}}</td>
                                <td>{{$empDetails->department}}</td>
                                <td style="color:cornflowerblue;cursor:pointer" wire:click="showModal({{ $revisionData['revised_ctc'] }},'{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('d M, Y') }}','{{ $revisionData['remarks'] }}')"> View</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="col-12 col-md-4 ">
                <div class="chart-container">
                    <canvas class="salary_chart" id="salaryChart"></canvas>
                </div>
            </div>
        </div>

        @if($decryptedData)
        <div class="bg-light emp-sal-table3">
            <div class="row mb-3">
                <div class="col-6">
                    <h5>Peer Comparison</h5>
                </div>
                <div class="col-6">
                    <button wire:click="exportToExcel" class="btn bg-white text-primary border-primary float-end ms-2 mb-1">Export Excel</button>
                    <button wire:click="showPeersModal" class="btn bg-white text-primary border-primary float-end">Define Peers</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered emp-sal1-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Number</th>
                            <th>Employee Name</th>
                            <th>Experience</th>
                            <th>Designation</th>
                            <th>Last Revision</th>
                            <th>ANNUAL CTC</th>
                            <th>Prev ANNUAL CTC</th>
                            <th>Difference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($selectedEmployeesPeersData )
                        @foreach($selectedEmployeesPeersData as $index=> $employeePeer)
                        <tr>
                            <td>{{ $index +1}}</td>
                            <td>{{$employeePeer['emp_id']}}</td>
                            <td style="text-transform: capitalize;">{{$employeePeer['emp_name']}}</td>
                            <td>{{$employeePeer['experience']}}</td>
                            <td>{{$employeePeer['designation']}}</td>
                            <td>{{ \Carbon\Carbon::parse($employeePeer['revision_date'])->format('d M, Y') }}</td>
                            <td>Rs {{number_format($employeePeer['revised_ctc'],2)}}</td>
                            <td>Rs {{number_format($employeePeer['current_ctc'],2)}}</td>
                            <td>
                                @if($employeePeer['percentage_change'] >= 0)
                                + {{$employeePeer['percentage_change']}} (Rs {{number_format($employeePeer['difference_amount'],2)}})
                                @else
                                {{$employeePeer['percentage_change']}} (Rs {{number_format($employeePeer['difference_amount'],2)}})
                                @endif
                                @if($employeePeer['emp_id'] != $mainEmp_id)

                                <span><img wire:click="removePeersEmployee('{{$employeePeer['emp_id']}}')" style="cursor: pointer;height:15px ;width:15px;" src="{{ asset('images/remove-icon-black&white.png') }}" alt=""></span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endif
    @if($viewDetails)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header " style=" background-color:white">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center; color:black">Salary Revision Breakup Details
                    </h6>
                </div>
                <div class="modal-body  text-center align-center" style="font-size: 14px;color:black">
                    <div class="row">
                        <div class="col-md-6 col-12" style="text-align: left;">
                            <Label>Effective Date: <span style="font-weight: bold;color:black"> {{$effectiveDate}}</span></Label>
                        </div>
                        <div class="col-md-6 col-12" style="text-align: left;">
                            <Label>Payout Month: <span style="font-weight: bold;color:black"> {{\Carbon\Carbon::parse($effectiveDate)->format('M, Y') }}</span></Label>
                        </div>
                    </div>
                    <div class="mt-2" style="width: 100%;">
                        <table class="salaryComponents-table">
                            <tr>
                                <th class="salary-component">Components</th>
                                <th class="salary-amount">Amount (In Rs)</th>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL BASIC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['basic'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL DA</td>
                                <td class="salary-amount">0.00</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL HRA</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['hra'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL CONVEYANCE</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['conveyance'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL SPECIAL ALLOWANCE</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['special_allowance'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">MONTHLY CTC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['monthly_ctc'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">ANNUAL CTC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['annual_ctc'],2)}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-2 text-start align-start">
                        <label for="">Remarks: <span style="font-weight: bold;color:black">{{$remarks}}</span></label>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center p-3">

                    <button type="button" class="cancel-btn" wire:click="hideDetailsModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    @if($isPeersModal)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;max-height:500px">
            <div class="modal-content">
                <div class="modal-header " style=" background-color:white">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center; color:black">Add Peers
                    </h6>
                </div>
                <div class="modal-body  text-center align-center" style="font-size: 14px;color:black">
                    <div class="row">
                        <div class="col-md-6 d-flex" style="gap: 5px;">
                            <input wire:model="peerSearch" class="form-control" type="text">
                            <button type="button" class="btn btn-primary " wire:click="getEmployeesList" style="font-size: 10px;">Search</button>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="mt-2" style="width:100% ; max-height: 300px; overflow-y: auto; border: 1px solid silver;">
                        <table class="Employee_list" style="width:100%; border-collapse: collapse;">
                            <thead style="position: sticky; top: 0; background: white; z-index: 2; border-bottom: 2px solid silver;">
                                <tr>
                                    <th>Emp Id</th>
                                    <th>Name</th>
                                    <th>Email Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allEmployees as $employee)
                                <tr wire:click="toggleEmployee('{{ $employee['emp_id'] }}')"
                                    style="cursor: pointer;"
                                    wire:key="{{ $employee['emp_id'] }}"
                                    class="{{ in_array($employee['emp_id'], $selectedEmployees) ? 'selected-row' : '' }}">
                                    <td>{{ $employee['emp_id'] }}</td>
                                    <td style="text-transform: capitalize;">
                                        {{ ucfirst(strtolower($employee['first_name'])) }}
                                        {{ ucfirst(strtolower($employee['last_name'])) }}
                                    </td>
                                    <td>{{ $employee['email'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center p-3">
                    <button type="button" class="btn btn-primary" style="font-size: 10px;" wire:click="getSelectedEmployees" @if(count($selectedEmployees) <=0) disabled @endif>Add</button>

                    <button type="button" class="cancel-btn" wire:click="hidePeersModal" style="font-size:10px">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function formatDecimalInput(input) {
        let value = input.value;
        let cursorPosition = input.selectionStart;

        // Allow only numbers and one decimal point
        let formattedValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

        // Update the input value while preserving cursor position
        input.value = formattedValue;
        input.setSelectionRange(cursorPosition, cursorPosition);
    }
    document.addEventListener('livewire:init', () => {
        let salaryChart;
        console.log('entering script..');

        const ctx = document.getElementById('salaryChart');
        if (ctx) {
            ctx.style.display = 'none';
        }
        // Listen for the browser event from Livewire
        window.addEventListener('update-chart', (event) => {
            const chartData = event.detail[0].chartData;
            // Ensure the canvas exists
            const ctx = document.getElementById('salaryChart');
            console.log(' ctx..', ctx);
            if (!ctx) {
                console.error("Canvas element not found.");
                return;
            }
            const context = ctx.getContext('2d');

            ctx.style.display = 'block';
            // Destroy the previous chart instance if it exists
            if (salaryChart) {
                salaryChart.destroy();
            }
            // Create the chart
            salaryChart = new Chart(context, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revised Annual Salary',
                        data: chartData.data,
                        borderColor: 'blue',
                        borderWidth: 2,
                        tension: 0.3,
                    }, ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return `₹${value.toLocaleString()}`;
                                },
                            },
                        },
                    },
                },
            });
        });
    });
</script>
