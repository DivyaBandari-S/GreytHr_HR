<div>
    <div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button
                    class="nav-link {{ $activeTab === 'nav-home' ? 'active' : '' }}"
                    wire:click="setActiveTab('nav-home')"
                    type="button"
                    role="tab">
                    Pending Applications
                </button>
                <button
                    class="nav-link {{ $activeTab === 'nav-profile' ? 'active' : '' }}"
                    wire:click="setActiveTab('nav-profile')"
                    type="button"
                    role="tab">
                    Year End Policy
                </button>
                <button
                    class="nav-link {{ $activeTab === 'nav-contact' ? 'active' : '' }}"
                    wire:click="setActiveTab('nav-contact')"
                    type="button"
                    role="tab">
                    Manual Process
                </button>
            </div>
        </nav>

        <div class="tab-content bg-white" id="nav-tabContent">
            <div class="tab-pane fade {{ $activeTab === 'nav-home' ? 'show active' : '' }}" id="nav-home">
                <div>
                    <div class="container mt-4">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <label for="managerFilter" class="managerFilter">Manager: </label>
                            <select id="managerFilter" class="form-select w-auto">
                                <option value="all">All</option>
                                <option value="manager1">Manager 1</option>
                                <option value="manager2">Manager 2</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table pendingLeaveTable table-bordered table-hover ">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="selectAll"
                                                    wire:click="toggleSelectAll"
                                                    @if(count($selectedLeaveRequestIds)===$pendingLeaveRequests->count()) checked @endif />
                                            </div>
                                        </th>
                                        <th>Manager</th>
                                        <th>Employee</th>
                                        <th>Employee No</th>
                                        <th>Leave On</th>
                                        <th>Leave Type</th>
                                        <th>Application Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pendingLeaveRequests && $pendingLeaveRequests->isNotEmpty())
                                    @foreach($pendingLeaveRequests as $request)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    wire:model="selectedLeaveRequestIds"
                                                    value="{{ $request->id }}" />
                                            </div>
                                        </td>

                                        {{-- Check if manager details are available --}}
                                        <td>
                                            @if($managerDetails)
                                            {{ ucwords(strtolower($managerDetails->first_name)) }} {{ ucwords(strtolower($managerDetails->last_name)) }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        {{-- Check if employee details list is available --}}
                                        @if($employeeReqDetails && $employeeReqDetails->isNotEmpty())
                                        @foreach($employeeReqDetails as $empName)
                                        <td>{{ ucwords(strtolower($empName->first_name)) }} {{ ucwords(strtolower($empName->last_name)) }}</td>
                                        @endforeach
                                        @else
                                        <td>N/A</td>
                                        @endif

                                        <td>{{ $request->emp_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->from_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($request->to_date)->format('d M, Y') }}</td>
                                        <td>{{ $request->leave_type }}</td>
                                        <td>{{ $request->category_type }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">No data found</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3 mb-3">
                            <button wire:click="approveLeave" class="cancel-btn me-2">Accept</button>
                            <button class="cancel-btn me-2" wire:click="rejectLeave">Reject</button>
                            <button class="cancel-btn" wire:click="toggleReminderModal">Remind as Mail</button>
                        </div>
                        @if($showReminderModal)
                        <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirmation</h6>
                                    </div>
                                    <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                                        Are you sure you want to send reminder mail?
                                    </div>
                                    <div class="d-flex gap-3 justify-content-center p-3">
                                        <button type="button" class="submit-btn " wire:click="sendReminder">Confirm</button>
                                        <button type="button" class="cancel-btn" wire:click="toggleReminderModal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ $activeTab === 'nav-profile' ? 'show active' : '' }}" id="nav-profile">
                <div class="accordion yearEnd-accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                General Scheme
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body d-flex flex-column gap-3">
                                @if($leaveTypeList)
                                @foreach ($leaveTypeList as $index => $leaves)
                                <div class="leave-type-content d-flex gap-2 align-items-center">
                                    <span class="leaveIndex">{{ $loop->iteration }}.</span>
                                    <span class="leaveType">{{ $leaves->leave_name }}</span>
                                    <i class="fas fa-edit editIcon" title="Edit" wire:click.prevent="openSettingsContainer({{ $leaves->id }})"></i>
                                </div>

                                {{-- Conditionally display the container if this leave is selected --}}
                                @if($selectedLeaveId === $leaves->id)
                                <div class="detailsToEdit">
                                    <span>Settings for {{ $leaves->leave_name }}</span>
                                </div>
                                @endif
                                @endforeach
                                @else
                                <span>No data found</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ $activeTab === 'nav-contact' ? 'show active' : '' }}" id="nav-contact">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <select class="form-select" wire:model="selectedLeaveType">
                                <option value="All">Leave Type: All</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Loss Of Pay">Loss Of Pay</option>
                                <!-- Add other leave types as needed -->
                            </select>

                            <span class="ms-3">
                                Period: 01 Jan 2024 to 31 Dec 2024
                            </span>
                        </div>

                        <button class="btn btn-primary" wire:click="triggerYearEndProcess">Year End Process</button>
                        @if($showYearEndProcessModal)
                        <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirmation</h6>
                                    </div>
                                    <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                                        Are you sure you want to Lapse the all leaves?
                                    </div>
                                    <div class="d-flex gap-3 justify-content-center p-3">
                                        <button type="button" class="submit-btn " wire:click="changeToLapsed">Confirm</button>
                                        <button type="button" class="cancel-btn" wire:click="triggerYearEndProcess">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                        @endif
                    </div>
                    <table class="table pendingLeaveTable table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee ID</th>
                                <th>Leave Name</th>
                                <th>Grant Days</th>
                                <th>Balance</th>
                                <th>Lapsed</th>
                                <th>Encashed</th>
                                <th>Closing Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($leaveDetailsWithBalance && count($leaveDetailsWithBalance) > 0)
                            @foreach($leaveDetailsWithBalance as $data)
                            @foreach($data['leave_details'] as $leave)
                            <tr>
                                <td>{{ $data['emp_id'] }}</td>
                                <td>{{ $leave['leave_name'] }}</td>
                                <td>{{ $leave['grant_days'] }}</td>
                                <td>{{ $leave['remaining_balance'] ?? '-' }}</td>
                                <td>{{ $leave['lapsed'] ?? '-' }}</td>
                                <td>{{ $leave['encashed'] ?? '-' }}</td>
                                <td>{{ isset($leave['remaining_balance']) && isset($leave['lapsed']) && isset($leave['encashed']) 
                            ? $leave['remaining_balance'] - ($leave['lapsed'] + $leave['encashed'])
                            : '-' }}</td>
                            </tr>
                            @endforeach
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" class="text-center">No data found</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>