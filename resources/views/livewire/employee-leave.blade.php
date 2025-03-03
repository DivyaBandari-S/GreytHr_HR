<div>
    <style>
        /* Calendar->Information->Employee leave styles start  */

        .employee-leave-hr-main {
            margin: 10px;
        }

        .employee-leave-searchrow {
            background-color: white;
            border-radius: 2px;
        }

        .employee-leave-start-para {
            color: #2d2b2b;
            font-weight: 600;
            font-size: 15px;
        }

        .employee-search-hr {
            padding: 5px 10px;
        }

        .Employee-select-leave {
            font-weight: 500;
            font-size: var(--main-headings-font-size);
            color: var(--main-heading-color);
        }

        .search-employee-type-leave {
            align-items: center;
            padding-bottom: 10px;
            display: flex;
            margin-right: 5px;
            color: var(--label-color);
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

        .Employee-leave-table-header {
            border-bottom: 1px solid rgb(199, 196, 196);
            height: fit-content;
        }

        .leave-balance-lave-types {
            width: fit-content;
            align-items: end;
            margin-top: 0px;
        }

        .leave-balance-lave-types button {
            padding: 0px 10px;
            font-size: 13px;
            color: var(--main-button-color);
            border: 0px;
            background-color: white;
            height: max-content;
            height: 30px;
        }

        .Leave-balance-buttons {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .Leave-balance-buttons button {
            color: var(--main-button-color);
            background-color: white;
            border: 0.5px solid var(--main-button-color);
            height: fit-content;
            font-size: 13px;
            font-weight: 500;
            border-radius: 5px;
        }

        .Employee-leave-table-maindiv {
            border: 0.5px solid rgb(199, 196, 196);
            padding: 0px;
        }

        .modal-title {
            color: #fff;
            font-weight: 600;
            font-size: var(--home-headings-font-size);
        }

        .modal-header {
            background-color: var(--main-button-color);
            color: white;
            height: 50px;
        }

        .btn-close {
            background-color: white;
            height: 7px;
            font-size: var(--normal-font-size);
            width: 7px;
        }

        .placeholder-small {
            /* color: var(--label-color); Placeholder text color */
            font-size: var(--normal-font-size);
            font-family: "Montserrat", sans-serif;
            font-weight: normal;
        }

        .error-text {
            font-size: var(--normal-font-size);
        }

        .task-follower-filter-container {
            margin-bottom: 10px;
        }

        .task-input-group-container {
            width: 230px;
        }

        .task-follower-search-container {
            width: 250px;
            padding-left: 10px;
        }

        .task-search-input {
            font-size: var(--normal-font-size);
            border-radius: 5px 0 0 5px;
            height: 32px;
        }

        .task-search-btn {
            height: 32px;
            width: 40px;
            position: relative;
            border-radius: 0 5px 5px 0;
            background-color: rgb(2, 17, 79);
            color: #fff;
            border: none;
            margin-right: 10px;
        }

        .task-search-icon {
            position: absolute;
            top: 9px;
            left: 11px;
            color: #fff;
        }

        .task-follower-close-icon {
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            background-color: rgb(2, 17, 79);
            padding: 6px 10px;
            border-radius: 5px;
        }

        .profile-image {
            height: 32px;

            width: 32px;

            background-color: lightgray;

            border-radius: 50%;
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

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            /* padding: 1rem; */
        }

        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: var(--main-button-color) var(--main-button-color) #fff var(--main-button-color);
            color: var(--main-button-color);
        }

        .nav-tabs .nav-link {
            font-size: var(--sub-headings-font-size);
            color: black;
        }

        .nav-tabs .nav-link.folder-active {
            font-size: var(--sub-headings-font-size);
            color: var(--main-button-color);
        }

        .nav-link {
            border: 1px solid transparent;
        }

        .list-group-item {
            padding: 0.5rem 1rem;
            color: var(--main-button-color);
            font-size: var(--sub-headings-font-size);
        }

        .analytic-view-all-search-bar {
            display: flex;
            padding: 20px 0px;
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

        .task-date-range-picker {
            display: flex;
            justify-content: flex-end;
            margin-left: 10px;
        }

        .task-custom-select-width {
            width: 170px;
            font-size: var(--normal-font-size);
            padding: 7px;
        }
    </style>
    <div style="margin: 10px;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeButton === 'Main' ? 'active' : '' }}"
                    wire:click="$set('activeButton', 'Main')" id="all-tab" data-bs-toggle="tab" href="#all"
                    role="tab" aria-controls="all" aria-selected="true">Main</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeButton === 'Activity' ? 'active' : '' }}"
                    wire:click="$set('activeButton', 'Activity')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                    role="tab" aria-controls="starred" aria-selected="false">Activity</a>
            </li>
            <li class="nav-item ms-auto">
                <a class="nav-link folder-active" href="#">Select</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show {{ $activeButton === 'Main' ? 'active' : '' }}" id="all" role="tabpanel"
            aria-labelledby="all-tab">
            <div style="margin: 10px;">
                @if ($showHelp == false)
                <div class="row main-overview-help">
                    <div class="col-md-11 col-10 d-flex flex-column">
                        <p class="main-overview-text">The Employee Leave page enables you to view the leave
                            information of employees. Select an employee and click each tab to view information
                            about a particular type of leave. Change the year to view leave data for a different
                            period.</p>
                        <p class="main-overview-text">Explore greytHR by <span class="main-overview-highlited-text">
                                Help-Doc</span>, watching<span class="main-overview-highlited-text"> How-to
                                Videos</span>
                            and<span class="main-overview-highlited-text"> FAQ</span>.</p>
                    </div>
                    <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                        <span wire:click="hideHelp">Hide Help</span>
                    </div>
                </div>
                @else
                <div class="row main-overview-help">
                    <div class="col-11 d-flex flex-column">
                        <p class="main-overview-text">The Employee Leave page enables you to view the leave
                            information of employees. Select an employee and click each tab to view information
                            about a particular type of leave. Change the year to view leave data for a different
                            period.</p>

                    </div>
                    <div class="hide-main-overview-help col-1">
                        <span wire:click="showhelp">Show Help</span>
                    </div>
                </div>
                @endif
            </div>

            <div class="row" style="background-color: #fff; margin: 10px; padding: 10px 5px;">

                <div class="col-md-8 col-12">
                    <p class="employee-leave-start-para">Start searching to see specific employee details here</p>
                    <div class="d-flex mb-2">
                        <label style="margin-top: 7px;" for="">Employee Type:</label>
                        <select class="Employee-select-leave" style="border: none; margin-left: 10px;"
                            wire:model="employeeType" wire:change="filterEmployeeType">
                            <option value="active">Current Employees</option>
                            <option value="non-active">Past Employees</option>
                        </select>
                        <div class="form-group task-date-range-picker">
                            <select class="form-select task-custom-select-width" wire:model.defer="filterPeriodValue"
                                wire:change="filterPeriodChanged">
                                <option value="this_year">Jan {{ date('Y') }} - Dec {{ date('Y') }}</option>
                                <option value="current_year">Jan {{ date('Y') - 1 }} - Dec {{ date('Y') - 1 }}</option>
                            </select>

                        </div>

                    </div>
                    <div>
                        <div wire:click="searchFilter" style="cursor: pointer;">
                            <label>Search Employee</label>
                        </div>
                        @if ($selectedEmployee)
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
                            style="background: white; padding: 10px; border: 1px solid black; border-radius: 5px; width: 310px; position: absolute; z-index: 100; max-height:250px;overflow-y:auto;">
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
                        @endif





                    </div>
                </div>
                <div class="col-md-4 col-12 text-center">
                    <img src="{{ asset('images/employeeleave.png') }}" alt=""
                        style="width: 100%; height: 150px; max-width: 100%;">
                </div>
            </div>
            <div style="margin: 20px 8px 0px 8px; background: #fff;">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'Overview' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'Overview')" id="Overview-tab" data-bs-toggle="tab"
                            href="#Overview" role="tab" aria-controls="Overview"
                            aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'Loss Of Pay' ? 'active' : '' }}" wire:click.prevent="showLeaveType('Loss Of Pay')"
                            id="LOP-tab" data-bs-toggle="tab" href="#LOP" role="tab" aria-controls="LOP"
                            aria-selected="false">LOP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'ML' ? 'active' : '' }}"
                            wire:click="showMarriageLeave" id="ML-tab" data-bs-toggle="tab" href="#ML"
                            role="tab" aria-controls="ML" aria-selected="false">ML</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'Casual Leave' ? 'active' : '' }}"
                            wire:click.prevent="showLeaveType('Casual Leave')" id="CL-tab" data-bs-toggle="tab" href="#CL"
                            role="tab" aria-controls="CL" aria-selected="false">CL</a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'EL' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'EL')" id="EL-tab" data-bs-toggle="tab" href="#EL"
                            role="tab" aria-controls="EL" aria-selected="false">EL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'Sick Leave' ? 'active' : '' }}" wire:click.prevent="showLeaveType('Sick Leave')"
                            id="SL-tab" data-bs-toggle="tab" href="#SL" role="tab" aria-controls="SL"
                            aria-selected="false">SL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'MAL' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'MAL')" id="MAL-tab" data-bs-toggle="tab" href="#MAL"
                            role="tab" aria-controls="MAL" aria-selected="false">MAL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'All' ? 'active' : '' }}"
                            wire:click="$set('activeTab', 'All')" id="All-tab" data-bs-toggle="tab" href="#All"
                            role="tab" aria-controls="All" aria-selected="false">All</a>
                    </li>
                    <li class="nav-item ms-auto">
                        <div class="d-flex gap-2 align-items-center">
                            <button class="cancel-btn" type="button">
                                Post Leave Transaction
                            </button>
                            <button class="cancel-btn" type="button">
                                <a href="/hr/user/post-leave-request">
                                    Apply on Behalf
                                </a>
                            </button>
                            <button class="cancel-btn" type="button"
                                wire:click="$set('showExportModal', true)">
                                Download
                            </button>
                        </div>
                    </li>

                </ul>
            </div>
            <!-- Modal -->
            @if($showExportModal)
            <div class="modal" id="exportModal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="background-color: white;">
                        <div class="modal-header">
                            <h5 class="modal-title">Export Leave Transactions</h5>
                            <button type="button" class="btn-close" wire:click="$set('showExportModal', false)" aria-label="Close"
                                data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="form-group row mb-2">
                                <label for="fromDate" class="col-md-4 col-form-label text-end">From</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control placeholder-small" wire:model="fromDate" wire:change="updateFromDate" id="fromDate">
                                    @error('fromDate') <span class="text-danger error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="toDate" class="col-md-4 col-form-label text-end">To</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control placeholder-small" wire:model="toDate" wire:change="updateToDate" id="toDate">
                                    @error('toDate') <span class="text-danger error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="leaveType" class="col-md-4 col-form-label text-end">Leave Type</label>
                                <div class="col-md-6">
                                    <select class="form-control placeholder-small" wire:model="selectedLeaveType" wire:change="updateSelectedLeaveType" id="leaveType">
                                        <option value="">Select Leave Type</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Loss Of Pay">Loss Of Pay</option>
                                    </select>
                                    @error('selectedLeaveType') <span class="text-danger error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="transactionType" class="col-md-4 col-form-label text-end">Leave Transaction</label>
                                <div class="col-md-6">
                                    <select class="form-control placeholder-small" wire:model="selectedTransactionType" wire:change="updateSelectedTransactionType" id="transactionType">
                                        <option value="">Select Transaction Type</option>
                                        <option value="Availed">Availed</option>
                                        <option value="Withdrawn">Withdrawn</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                    @error('selectedTransactionType') <span class="text-danger error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="orderBy" class="col-md-4 col-form-label text-end">Order By</label>
                                <div class="col-md-6">
                                    <select class="form-control placeholder-small" wire:model="orderBy" id="orderBy">
                                        <option value="date">Date</option>
                                        <option value="leaveType">Leave Type</option>
                                        <option value="transactionType">Transaction Type</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-md-4 text-end">Generate as</label>
                                <div class="col-md-6 row justify-content-start">
                                    <label class="radio-inline mr-2 col-md-6">
                                        <input type="radio" wire:model="exportFormat" value="pdf"> <span>PDF</span>
                                    </label>
                                    <label class="radio-inline col-md-6">
                                        <input type="radio" wire:model="exportFormat" value="excel"> Excel
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showExportModal', false)">Close</button>
                            <button type="button" class="btn btn-primary" wire:click="generateReport">Generate</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-backdrop fade show blurred-backdrop"></div>
            @endif


            <div class="tab-content" style="margin: 0px 8px;" id="myTabContent">
                <div class="tab-pane fade show {{ $activeTab === 'Overview' ? 'active' : '' }}" id="Overview"
                    role="tabpanel" aria-labelledby="Overview-tab" style="overflow-x: hidden;">
                    <div class="row  p-3">
                        <div class="col-md-1"></div>
                        <div class="col-md-9 Employee-leave-table-maindiv">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Scheme Name<span
                                    style="font-size: var(--normal-font-size); color: var(--label-color);font-weight: 400;">
                                    -
                                    General Scheme</span> </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Code</th>
                                        <th>Leave Type</th>
                                        <th>O/B</th>
                                        <th>Granted</th>
                                        <th>Availed</th>
                                        <th>Applied</th>
                                        <th>Leave</th>
                                        <th>Lapsed</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($selectedEmployee))
                                    @foreach ($leaveData as $empId => $data)
                                    <tr wire:click="showLeaveType('Loss Of Pay')"
                                        style="cursor: pointer;font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>LOP</td>
                                        <td>Loss Of Pay</td>
                                        <td></td>
                                        <td>
                                            {{-- {{ isset($data['leaveBalances']['lossOfPayPerYear']) ? $data['leaveBalances']['lossOfPayPerYear'] : '-' }} --}}
                                        </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr wire:click="selectLeaveType('ML')"
                                        style="cursor: pointer; font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #fff;">
                                        <td>ML</td>
                                        <td>Maternity Leave</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr wire:click="showLeaveType('Casual Leave')"
                                        style=" cursor: pointer; font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>CL</td>
                                        <td>Casual Leave</td>
                                        <td></td>
                                        <td>{{ $data['leaveBalances']['casualLeavePerYear'] ?? '-' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $data['leaveBalances']['casualLeaveBalance'] ?? '-' }}</td>
                                    </tr>
                                    <tr wire:click="selectLeaveType('EL')"
                                        style="cursor: pointer; font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center;  background-color: #fff;">
                                        <td>EL</td>
                                        <td>Earned Leave</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr wire:click="showLeaveType('Sick Leave')"
                                        style="cursor: pointer; font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>SL</td>
                                        <td>Sick Leave</td>
                                        <td></td>
                                        <td>{{ $data['leaveBalances']['sickLeavePerYear'] ?? '-' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $data['leaveBalances']['sickLeaveBalance'] ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                                style="width: 150px; height:150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>


                            </table>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'Loss Of Pay' ? 'show active' : '' }}" id="LOP"
                    role="tabpanel" aria-labelledby="LOP-tab">
                    <div class="row  p-3">
                        <div class="col-md-6">
                            <p
                                style="font-size: var(--headings-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Loss Of Pay - LOP
                            </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Type</th>
                                        <th>Posted</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (collect($leaveRequests)->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Transactions"
                                                style="width: 150px; height: 150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach ($leaveRequests as $request)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; background-color: #fff;">
                                        <td>{{ $request->leave_type }}</td>
                                        <td>{{ $request->created_at->format('d M, Y') }}</td>
                                        <td>{{ $request->from_date->format('d M, Y') }}
                                            <br>{{ $request->from_session }}
                                        </td>
                                        <td>{{ $request->to_date->format('d M, Y') }}
                                            <br>{{ $request->to_session }}
                                        </td>
                                        <td>{{ $request->from_date->diffInDays($request->to_date) + 1 }}</td>
                                        <!-- Adjust based on your logic -->
                                        <td>{{ $request->reason }}</td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Summary

                            </p>
                            <canvas id="leaveChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'ML' ? 'show active' : '' }}" id="ML"
                    role="tabpanel" aria-labelledby="ML-tab">
                    <div class="row  p-3">
                        <div class="col-md-1"></div>
                        <div class="col-md-9 Employee-leave-table-maindiv">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Scheme Name<span
                                    style="font-size: var(--normal-font-size); color: var(--label-color);font-weight: 400;">
                                    -
                                    General Scheme</span> </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Code</th>
                                        <th>Leave Type</th>
                                        <th>O/B</th>
                                        <th>Granted</th>
                                        <th>Availed</th>
                                        <th>Applied</th>
                                        <th>Leave</th>
                                        <th>Lapsed</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($selectedEmployee))
                                    @foreach ($leaveData as $empId => $data)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #fff;">
                                        <td>ML</td>
                                        <td>Maternity Leave</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                                style="width: 150px; height:150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>


                            </table>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'Casual Leave' ? 'show active' : '' }}" id="CL"
                    role="tabpanel" aria-labelledby="CL-tab">
                    <div class="row  p-3">
                        <div class="col-md-6">
                            <p
                                style="font-size: var(--headings-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Casual Leave - CL
                            </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Type</th>
                                        <th>Posted</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (collect($leaveRequests)->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Transactions"
                                                style="width: 150px; height: 150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach ($leaveRequests as $request)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; background-color: #fff;">
                                        <td>{{ $request->leave_type }}</td>
                                        <td>{{ $request->created_at->format('d M, Y') }}</td>
                                        <td>{{ $request->from_date->format('d M, Y') }}
                                            <br>{{ $request->from_session }}
                                        </td>
                                        <td>{{ $request->to_date->format('d M, Y') }}
                                            <br>{{ $request->to_session }}
                                        </td>
                                        <td>{{ $request->from_date->diffInDays($request->to_date) + 1 }}</td>
                                        <!-- Adjust based on your logic -->
                                        <td>{{ $request->reason }}</td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Summary

                            </p>
                            <canvas id="leaveChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade {{ $activeTab === 'EL' ? 'show active' : '' }}" id="EL" role="tabpanel"
                    aria-labelledby="EL-tab">
                    <div class="row  p-3">
                        <div class="col-md-1"></div>
                        <div class="col-md-9 Employee-leave-table-maindiv">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Scheme Name<span
                                    style="font-size: var(--normal-font-size); color: var(--label-color);font-weight: 400;">
                                    -
                                    General Scheme</span> </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Code</th>
                                        <th>Leave Type</th>
                                        <th>O/B</th>
                                        <th>Granted</th>
                                        <th>Availed</th>
                                        <th>Applied</th>
                                        <th>Leave</th>
                                        <th>Lapsed</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($selectedEmployee))
                                    @foreach ($leaveData as $empId => $data)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center;  background-color: #fff;">
                                        <td>EL</td>
                                        <td>Earned Leave</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                                style="width: 150px; height:150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>


                            </table>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'Sick Leave' ? 'show active' : '' }}" id="SL" role="tabpanel"
                    aria-labelledby="SL-tab">
                    <div class="row  p-3">
                        <div class="col-md-6">
                            <p
                                style="font-size: var(--headings-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Sick Leave - SL
                            </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Type</th>
                                        <th>Posted</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (collect($leaveRequests)->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Transactions"
                                                style="width: 150px; height: 150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach ($leaveRequests as $request)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color); text-align: center; background-color: #fff;">
                                        <td>{{ $request->leave_type }}</td>
                                        <td>{{ $request->created_at->format('d M, Y') }}</td>
                                        <td>{{ $request->from_date->format('d M, Y') }}
                                            <br>{{ $request->from_session }}
                                        </td>
                                        <td>{{ $request->to_date->format('d M, Y') }}
                                            <br>{{ $request->to_session }}
                                        </td>
                                        <td>{{ $request->from_date->diffInDays($request->to_date) + 1 }}</td>
                                        <!-- Adjust based on your logic -->
                                        <td>{{ $request->reason }}</td>

                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Summary

                            </p>
                            <canvas id="leaveChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'MAL' ? 'show active' : '' }}" id="MAL"
                    role="tabpanel" aria-labelledby="MAL-tab">
                    <div class="row  p-3">
                        <div class="col-md-1"></div>
                        <div class="col-md-9 Employee-leave-table-maindiv">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Scheme Name<span
                                    style="font-size: var(--normal-font-size); color: var(--label-color);font-weight: 400;">
                                    -
                                    General Scheme</span> </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Code</th>
                                        <th>Leave Type</th>
                                        <th>O/B</th>
                                        <th>Granted</th>
                                        <th>Availed</th>
                                        <th>Applied</th>
                                        <th>Leave</th>
                                        <th>Lapsed</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($selectedEmployee))
                                    @foreach ($leaveData as $empId => $data)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #fff;">
                                        <td>MAL</td>
                                        <td>Marraiage Leave</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                                style="width: 150px; height:150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>


                            </table>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'All' ? 'show active' : '' }}" id="All"
                    role="tabpanel" aria-labelledby="All-tab">
                    <div class="row  p-3">
                        <div class="col-md-1"></div>
                        <div class="col-md-9 Employee-leave-table-maindiv">
                            <p
                                style="font-size: var(--normal-font-size); font-weight: 600; padding: 5px 5px; margin: 0px;">
                                Scheme Name<span
                                    style="font-size: var(--normal-font-size); color: var(--label-color);font-weight: 400;">
                                    -
                                    General Scheme</span> </p>
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead style="background-color: rgb(182, 179, 179); color: white;">
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; padding: 5px;">
                                        <th style="padding: 10px;">Code</th>
                                        <th>Leave Type</th>
                                        <th>O/B</th>
                                        <th>Granted</th>
                                        <th>Availed</th>
                                        <th>Applied</th>
                                        <th>Leave</th>
                                        <th>Lapsed</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @if (!empty($selectedEmployee))
                                    @foreach ($leaveData as $empId => $data)
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>LOP</td>
                                        <td>Loss Of Pay</td>
                                        <td>0</td>
                                        <td>{{ $data['leaveBalances']['lossOfPayPerYear'] }}</td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #fff;">
                                        <td>ML</td>
                                        <td>Maternity Leave</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>CL</td>
                                        <td>Casual Leave</td>
                                        <td>0</td>
                                        <td>{{ $data['leaveBalances']['casualLeavePerYear'] }}</td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>{{ $data['leaveBalances']['casualLeaveBalance'] }}</td>
                                    </tr>
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center;  background-color: #fff;">
                                        <td>EL</td>
                                        <td>Earned Leave</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                    <tr
                                        style="font-size: var(--normal-font-size); color: var(--main-heading-color);text-align: center; background-color: #f8f9fa;">
                                        <td>SL</td>
                                        <td>Sick Leave</td>
                                        <td>0</td>
                                        <td>{{ $data['leaveBalances']['sickLeavePerYear'] }}</td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>{{ $data['leaveBalances']['sickLeaveBalance'] }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                                style="width: 150px; height:150px;">
                                            <p style="font-size: 15px; color: gray;">No Data Found.</p>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>


                            </table>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('leaveChart').getContext('2d');
        let leaveChart;

        function renderChart(monthlyCounts) {
            if (leaveChart) {
                leaveChart.destroy(); // Destroy the previous instance of the chart
            }

            leaveChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                        'Nov', 'Dec'
                    ],
                    datasets: [{
                        label: 'Leave Requests',
                        data: monthlyCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            // Add more colors as needed...
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            // Add more border colors as needed...
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initial render with empty data
        renderChart(Array(12).fill(0));

        // Listen for the update-chart event
        window.addEventListener('update-chart', event => {
            const monthlyCounts = event.detail.monthlyCounts;
            console.log(monthlyCounts); // Check if the data is being received correctly
            renderChart(monthlyCounts);
        });
    });
</script>