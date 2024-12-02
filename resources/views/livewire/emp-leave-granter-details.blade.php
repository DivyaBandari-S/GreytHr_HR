<div>

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
                        <div class="date-picker d-flex justify-content-end p-0">
                            <input type="text" value="Jan 2024 - Dec 2024" readonly />
                        </div>
                    </div>
                    <div class="row d-flex align-items-center m-0 p-0">
                        <div class="col-md-9 py-2 px-0">
                            <div class="filters">
                                <div class="custom-dropdown">
                                    <div class="dropdown-selected">Grant Type: All</div>
                                    <div class="dropdown-options">
                                        <div class="dropdown-option">Grant Type: All</div>
                                        <div class="dropdown-option">Grant Type: Monthly</div>
                                        <div class="dropdown-option">Grant Type: Yearly</div>
                                    </div>
                                </div>

                                <div class="custom-dropdown">
                                    <div class="dropdown-selected">Leave Type: All</div>
                                    <div class="dropdown-options">
                                        <div class="dropdown-option">Leave Type: All</div>
                                        <div class="dropdown-option">Leave Type: Sick Leave</div>
                                        <div class="dropdown-option">Leave Type: Casual Leave</div>
                                    </div>
                                </div>

                                <div class="custom-dropdown">
                                    <div class="dropdown-selected">Employee: All</div>
                                    <div class="dropdown-options">
                                        <div class="dropdown-option">Employee: All</div>
                                        <div class="dropdown-option">Employee: Full Time</div>
                                        <div class="dropdown-option">Employee: Part Time</div>
                                    </div>
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
                    <div class="table-responsive">
                        <table class="leave-table">
                            <thead>
                                <tr>
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
                                <tr>
                                    <td>
                                        <span class="batch-info">Batch ID: 578</span>
                                        <span>Granted On: 22 Aug 2024 06:06:03</span>
                                    </td>
                                    <td>XSS-0571</td>
                                    <td>KALIGOTLA SAI NAGA SOWMYA</td>
                                    <td>Probation</td>
                                    <td>01 Aug 2024</td>
                                    <td>3.34</td>
                                    <td>
                                        <button class="delete-btn">&#128465;</button>
                                    </td>
                                </tr>
                                <!-- Repeat similar rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($showActiveGrantLeave)
                <div class="grantLeaveTab px-3 py-2">
                    <div class="row g-2 m-0 p-0">
                        <div class="col-md-6"></div>
                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <div class="date-picker p-0">
                                <!-- Start Year Dropdown -->
                                <select wire:model="selectedYear" wire:change="updateDateRange" class="form-control">
                                    <option value="" disabled>Select Year</option>
                                    @foreach($yearRange as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                            <button class="submit-btn" type="button">
                                <a href="/hr/user/leavePolicySettings">Leave Settings</a>
                            </button>
                        </div>
                    </div>

                    <div class="radio-buttons">
                        <label class="radioLabel">
                            <input type="radio" name="grantEmployees" value="all" checked>
                            Grant for All Employees
                        </label>
                        <label class="radioLabel">
                            <input type="radio" name="grantEmployees" value="newlyJoined">
                            Grant for Newly Joined Employees
                        </label>
                    </div>
                    <div>
                        <!-- Employee Selection -->
                        <div wire:click="toggleEmployeeList">
                            <label for="employees">Select Employees</label>

                            @if($showEmployeeList)
                            <div class="employee-checkbox-container">
                                <!-- Select All Checkbox -->
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="select_all"
                                        wire:model="selectAll"
                                        wire:click="toggleSelectAll">
                                    <label class="form-check-label" for="select_all">
                                        Select All
                                    </label>
                                </div>

                                <!-- Employee List -->
                                @foreach($employeeIds as $empId => $empName)
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        wire:model="selectedEmployeeIds"
                                        value="{{ $empId }}"
                                        id="employee_{{ $empId }}">
                                    <label class="form-check-label" for="employee_{{ $empId }}">
                                        {{ $empName }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>




                        <!-- Periodicity and Period -->
                        <div>
                            <label for="periodicity">Periodicity</label>
                            <select wire:model="periodicity" wire:change="updatePeriodOptions" class="form-control mb-3">
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Half yearly">Half yearly</option>
                                <option value="Yearly">Yearly</option>
                            </select>

                            <!-- Period Dropdown -->
                            <label for="period">Period</label>
                            <select wire:model="period" class="form-control mb-3">
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


                        <!-- Leave Policies Table -->
                        <div>
                            <label for="leavePolicyIds">Select Leave Policies</label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Leave Name</th>
                                        <th>Grant Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leavePolicies as $policy)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model="selectedPolicyIds" value="{{ $policy->id }}">
                                        </td>
                                        <td>{{ $policy->leave_name }}</td>
                                        <td>{{ $policy->grant_days }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Grant Leave Button -->
                        <button wire:click="storeLeaveBalance" class="btn btn-primary">Grant Leave</button>
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