<div>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Pending Applications</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Year End Policy</button>
            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Manual Process</button>
        </div>
    </nav>
    <div class="tab-content bg-white" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div>
                <div class="container mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <label for="managerFilter">Manager: </label>
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
                                    @if($employeeDetailsList && $employeeDetailsList->isNotEmpty())
                                    @foreach($employeeDetailsList as $empName)
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
                        <button class="cancel-btn"  wire:click="toggleReminderModal">Remind as Mail</button>
                    </div>
                    @if($showReminderModal)
                    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header text-white" >
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
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">..profile.</div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">.active..</div>
    </div>
</div>