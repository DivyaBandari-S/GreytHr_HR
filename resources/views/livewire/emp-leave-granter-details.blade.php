<div class="main__body" style="overflow: auto; height: calc(100vh - 84px)">

    <div class="container-fluid px-1  rounded">

        <ul class="nav leave-grant-nav-tabs d-flex gap-3 py-1" id="myTab" role="tablist">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>

        <div class="tab-content" id="myTabContent">

            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                @if($showLeaveBalanceSummary)
                <div class="px-3 py-2">
                    <div class="row main-overview-help">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Leave Granter</span> page displays a summary of all leaves credited (granted) to employees for the current leave year. Click the icons present adjacent to each row to further manage the data. Leave is usually granted automatically as per schedule. However, you can also grant leave manually by clicking the <span class="msgHeighlighter">Grant Leave</span> button. </p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                </div>
                <div class="leave-granter px-3">
                    <div class="row m-0 p-0">
                        <div class="col-md-5"></div>
                        <div class="col-md-5"></div>
                        <div class="col-md-2  p-0">
                            <div class="date-picker d-flex justify-content-end">
                                <select id="selectedYear" wire:model="selectedYear" wire:change="updateDateRange" class="form-control" style="font-size: 14px;">
                                    <option value="" disabled>Select Year</option>
                                    @foreach($yearRange as $year)
                                    <option value="{{ $year }}">Jan {{ $year }} - Dec {{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex align-items-center m-0 p-0">
                        <div class="col-md-9 py-2 px-0">
                            <div class="filters">
                                <div class="dropdown">
                                    <select name="grantType" id="grantType" wire:model="grantType" wire:change="filterLeaveBalances">
                                        <option value="All">All</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                        <option value="Quarterly">Quarterly</option>
                                        <option value="Half yearly">Half yearly</option>
                                    </select>
                                </div>

                                <div class="dropdown">
                                    <select wire:model="leaveType" wire:change="filterLeaveBalances">
                                        <option value="All">All</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                    </select>
                                </div>

                                <div class="dropdown">
                                    <select wire:model="employmentType" wire:change="filterLeaveBalances">
                                        <option value="All">All</option>
                                        <option value="full-time">Full Time</option>
                                        <option value="part-time">Part Time</option>
                                        <option value="contract"> Contract</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 py-2 px-0">
                            <div class="d-flex justify-content-end">
                                <!--
                             <a href="/hr/user/grantLeave">Grant Leave</a> -->
                                <button class="submit-btn" wire:click="showGrantLeaveTab"> Grant Leave</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive rounded border leave-table-wrapper">
                        <table class="leave-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Employee No</th>
                                    <th>Employee Name</th>
                                    <th>Status</th>
                                    <th>Joining Date</th>
                                    <th>Days</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($groupedData && $groupedData->count())
                                @foreach($groupedData as $batchId => $leaveBalances)
                                <tr>
                                    <td colspan="8" class="p-0 m-0">
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <div class="accordion-header">
                                                    <div>
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $batchId }}" aria-expanded="false" aria-controls="flush-collapse-{{ $batchId }}">
                                                            <i class="fas fa-plus"></i>
                                                            <div class="accordionTitle d-flex flex-column">
                                                                <span class="spanText">Batch ID: <strong>{{ $batchId }}</strong></span>
                                                                <span class="spanText">Granted on: <strong>{{ \Carbon\Carbon::parse($leaveBalances->first()->created_at)->format('d M, Y') }}</strong></span>
                                                            </div>
                                                            <div class="accordionTitle d-flex flex-column">
                                                                <span class="spanText">Period: <strong>{{ $leaveBalances->first()->period }}</strong></span>
                                                                <span class="spanText">Periodicity: <strong>{{ $leaveBalances->first()->periodicity }}</strong></span>
                                                            </div>
                                                            <div class="accordionTitle d-flex flex-column">
                                                                <span class="spanText">Leave Type:
                                                                    <strong>
                                                                        @php
                                                                        $leaveTypes = json_decode($leaveBalances->first()->leave_policy_id);
                                                                        @endphp
                                                                        @if(is_array($leaveTypes))
                                                                        @foreach($leaveTypes as $leaveType)
                                                                        {{ $leaveType->leave_name }}
                                                                        @if (!$loop->last) | @endif
                                                                        @endforeach
                                                                        @else
                                                                        N/A
                                                                        @endif
                                                                    </strong>
                                                                </span>
                                                                <span class="spanText">Leave Scheme: <strong>{{ $leaveBalances->first()->leave_scheme }}</strong></span>
                                                            </div>
                                                            <div class="accordionTitle">
                                                                <span class="spanText">Headcount: <strong>{{ $leaveBalances->count() }}</strong></span>
                                                            </div>
                                                            <div class="accordionTitle">
                                                                <span class="spanText" style="cursor:pointer;" wire:click="deleteLeaveBalanceBatch({{ $batchId }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div id="flush-collapse-{{ $batchId }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body p-0">
                                                        <!-- Inner Table Without Repeating Headers -->
                                                        <table class="table">
                                                            <tbody>
                                                                @foreach($leaveBalances as $balance)
                                                                <tr>
                                                                    <td></td>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $balance->emp_id }}</td>
                                                                    <td> <span class="nameField">{{ ucwords(strtolower($balance->first_name)) }} {{ ucwords(strtolower($balance->last_name)) }}</span> </td>
                                                                    <td>{{ $balance->status }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($balance->hire_date)->format('d M, Y') }}</td>
                                                                    <td>
                                                                        @php
                                                                        // Decode the JSON string in the leave_policy_id field
                                                                        $leaveTypes = json_decode($balance->leave_policy_id);
                                                                        @endphp
                                                                        <span class="d-flex flex-column">
                                                                            @if(is_array($leaveTypes))
                                                                            @foreach($leaveTypes as $leaveType)
                                                                            {{ $leaveType->leave_policy_id }} | {{ $leaveType->grant_days }}
                                                                            @if (!$loop->last) <!-- Add a pipe separator between the items -->
                                                                            <br>
                                                                            @endif
                                                                            @endforeach
                                                                            @else
                                                                            N/A
                                                                            @endif
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <i class="fas fa-trash me-2" wire:click="deleteLeaveBalanceEmp({{ $balance->id }})" style="cursor:pointer;"></i>
                                                                        <i class="fa fa-pencil ms-2" wire:click="getLeaveBalanceEmp({{ $balance->id }})" style="cursor:pointer;"></i>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No data found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- //modal for confirm deletion -->
                @if($showConfirmDeletionBox)
                <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-white">
                                <h6 class="modal-title" id="logoutModalLabel" style="align-items: center;">
                                    @if($deletionType === 'employee_balance')
                                    Confirm Employee Balance Deletion
                                    @elseif($deletionType === 'batch')
                                    Confirm Batch Deletion
                                    @else
                                    Confirm Deletion
                                    @endif
                                </h6>
                            </div>
                            <div class="modal-body text-center" style="font-size: 14px;color:var(--main-heading-color);">
                                @if($deletionType === 'employee_balance')
                                Are you sure you want to delete this employee's leave balance?
                                @elseif($deletionType === 'batch')
                                Are you sure you want to delete this batch of leave balances?
                                @else
                                Are you sure you want to proceed with this deletion?
                                @endif
                            </div>
                            <div class="modal-footer d-flex gap-3 justify-content-center p-3">
                                <!-- Confirm Deletion Button -->
                                <button type="button" class="submit-btn mr-3"
                                    @if($deletionType==='employee_balance' )
                                    wire:click="deleteAnEmpBal({{ $idToDelete }})"
                                    @elseif($deletionType==='batch' )
                                    wire:click="confirmDeletion"
                                    @endif>
                                    Confirm
                                </button>
                                <!-- Cancel Button -->
                                <button type="button" class="cancel-btn" wire:click="cancelDeletion">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show"></div>
                @endif
                <!-- end of modal -->
                <!-- edit balance modal -->
                @if($showEditModal)
                <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-white">
                                <h6 class="modal-title" id="logoutModalLabel" style="align-items: center;">
                                    Update Leave Balance
                                </h6>
                            </div>
                            <div class="modal-body text-center" style="font-size: 14px; color: var(--main-heading-color);">
                                <form>
                                    <table class="leave-table">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox"></th>
                                                <th>Leave Name</th>
                                                <th>Grant Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($leavePolicyData as $index => $leave)
                                            <tr>
                                                <td>{{ $leave['leave_name'] }}</td>
                                                <td>
                                                    <input type="number" class="form-control" wire:model="leavePolicyData.{{ $index }}.grant_days" value="{{ $leave['grant_days'] }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                            <div class="modal-footer d-flex gap-3 justify-content-center p-3">
                                <!-- Confirm Deletion Button -->
                                <button type="button" class="submit-btn mr-3"

                                    wire:click="editLeaveBal">
                                    Confirm
                                </button>
                                <!-- Cancel Button -->
                                <button type="button" class="cancel-btn" wire:click="cancelDeletion">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show"></div>
                @endif
                @if($showActiveGrantLeave)
                <div class="grantLeaveTab px-3 py-2">
                    <div class="row g-2 mb-3 p-0">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <div class="date-picker p-0">
                                <!-- Start Year Dropdown -->
                                <select wire:model="selectedYear" wire:change="updateDateRange" class="form-control" style="font-size: 14px;">
                                    <option value="" disabled>Select Year</option>
                                    @foreach($yearRange as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="submit-btn">
                                <a class="btnAnchor" href="/hr/user/leavePolicySettings">Leave Settings</a>
                            </button>
                        </div>
                    </div>
                    <div>
                        <!-- Employee Selection -->
                        <div class="mb-3 row mt-2 d-flex align-items-center">
                            <div class="col-md-2">
                                <div class="form-check">
                                    <div>
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="selectAllEmployees"
                                            wire:model="selectAll"
                                            wire:click="toggleSelectAll">
                                        <label class="form-check-label" for="selectAllEmployees">
                                            Select All Employees
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10 ">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="form-check">
                                        <div>
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="selectAllNewEmployees"
                                                wire:model="selectAllNewJoin"
                                                wire:click="toggleSelectAll">
                                            <label class="form-check-label" for="selectAllNewEmployees">
                                                Select Newly Joined Employees
                                            </label>
                                        </div>
                                    </div>
                                    @if($showToSelect)
                                    <div>
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            id="selectEmpList"
                                            wire:click="openEmployeeList">
                                        <label for="selectEmpList">Select Employee From List</label>
                                    </div>
                                    @endif
                                    @if($showEmployeeSelectionList)
                                    <div class="position-relative">
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model="searchTerm"
                                            wire:keyup="loadEmployeeList" style="width:250px;">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif

                                        @if($showEmployeeSearch)
                                        <div class="selectEmp mb-3 bg-white d-flex flex-column position-absolute">
                                            <div class="d-flex justify-content-end">
                                                <span wire:click="closeContainer"><i class="fa fa-times-circle text-muted" style="color:#ccc;"></i></span>
                                            </div>
                                            <div>
                                                @if(count($employeeIds) > 0)
                                                @foreach($employeeIds as $emp_id => $emp_name)
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="employee_{{ $emp_id }}"
                                                        wire:model="selectedEmployees"
                                                        value="{{ $emp_id }}">
                                                    <label class="form-check-label" for="employee_{{ $emp_id }}">
                                                        {{ ucwords(strtolower($emp_name)) }}
                                                    </label>
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="text-center subTextValue py-2">No data found for your search query.</div>
                                                @endif
                                            </div>
                                        </div>

                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 mb-3 p-0">
                            <!-- Periodicity and Period -->
                            <div class="col-md-12 p-0">
                                <div class="fieldsWidth d-flex align-items-center gap-2">
                                    <div class="d-flex gap-2 align-items-center">
                                        <label for="periodicity">Periodicity</label>
                                        <select id="periodicity" wire:model="periodicity" wire:change="updatePeriodOptions" class="form-control ">
                                            <option value="Monthly">Monthly</option>
                                            <option value="Quarterly">Quarterly</option>
                                            <option value="Half yearly">Half yearly</option>
                                            <option value="Yearly">Yearly</option>
                                        </select>
                                    </div>

                                    <!-- Period Dropdown -->
                                    <div class="d-flex gap-2 align-items-center">
                                        <label for="period">Period</label>
                                        <select wire:model="period" class="form-control " id="period">
                                            @if($periodicity == 'Monthly')
                                            @foreach($months as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                            @endforeach
                                            @elseif($periodicity == 'Quarterly')
                                            @foreach($quarters as $quarter)
                                            <option value="{{ $quarter }}">{{ $quarter }}</option>
                                            @endforeach
                                            @elseif($periodicity == 'Half yearly')
                                            @foreach($halfYear as $half)
                                            <option value="{{ $half }}">{{ $half }}</option>
                                            @endforeach
                                            @elseif($periodicity == 'Yearly')
                                            @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($showFromTodates)
                        <div>
                            <label for="from_date">From Date</label>
                            <input type="date" id="from_date" wire:model="from_date" wire:change="calculateToDate">

                            <label for="to_date">To Date</label>
                            <input type="date" id="to_date" wire:model="to_date">
                        </div>

                        @endif
                        <!-- Leave Policies Table -->
                        <div>
                            <span class="subTextValue">Select Leave Policies</spa>
                                <div class="table-responsive">
                                    <table class="table pendingLeaveTable table-bordered ">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Select</th>
                                                <th>Leave Name</th>
                                                <th>Grant Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($leavePolicies && $leavePolicies->isNotEmpty())
                                            @foreach($leavePolicies as $policy)
                                            <tr class="trHover">
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" wire:model="selectedPolicyIds" value="{{ $policy->id }}" wire:change="checkLeaveNames">
                                                    </div>
                                                </td>
                                                <td>{{ $policy->leave_name }}</td>
                                                <td>{{ $policy->grant_days }}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="7" class="text-center"> No leave policies found at the moment. <br>
                                                    Don't worry! You can easily add a new leave policy by clicking the "Leave Settings" button.</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                        </div>

                        <!-- Grant Leave Button -->
                        <div class="d-flex justify-content-center">
                            <button wire:click="storeLeaveBalance" class="submit-btn">Grant Leave</button>
                        </div>
                    </div>
                </div>

                @endif

            </div>

            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                <div>
                    activity review
                </div>

            </div>

        </div>

    </div>

</div>