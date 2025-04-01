<div >
    <style>
        .toggle-button {
        display: inline-flex;
        border: none;
        outline: none;
        cursor: pointer;
        background-color: var(--main-button-color);
        color: white;
        border-radius: 5px;
        padding: 5px 5px;
        font-size: 12px;
        float:right;
        transition: background-color 0.3s;
    }

    .toggle-button div {
        padding: 0 5px;
        transition: all 0.3s;
    }

    .toggle-button .all {
        background-color: #3b55a4;
    }
    .toggle-button .selected {
        background-color: #3b55a4;
    }
    </style>
    <div class="nav-buttons mt-2 d-flex justify-content-center">
        <ul class="nav custom-nav-tabs border">
            <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-active">
                <div class="reviewActiveButtons custom-nav-link {{ $activeSection === 'All' ? 'active' : '' }}" wire:click.prevent="toggleSection('All')">All Reports</div>
            </li>
            <li class="pendingCustomStyles custom-item m-0 p-0 flex-grow-1">
                <a href="#" class="custom-nav-link {{ $activeSection === 'Favorites' ? 'active' : '' }}" wire:click.prevent="toggleSection('Favorites')">Favorites</a>
            </li>
        </ul>
    </div>

    <!-- Content Tabs -->
    <div class="tab-content mt-3" style="overflow: auto; max-height: 70vh;">
        <!-- all Tab -->
        <div class="tab-pane {{ $activeSection === 'All' ? 'active' : '' }}" id="apply-section">
            <div class="row m-0 px-2">
                @foreach($reportsGallery->groupBy('category') as $category => $reports)
                <div class="col-md-4">
                    <div class="allreports">
                        <div class="allreports-header">
                            <i class="bi bi-file-earmark-text"></i> {{ ucfirst(strtolower($category)) }}
                        </div>
                        <div class="allreports-body">
                            <ul>
                                @foreach($reports as $report)
                                <li wire:click="showContent('{{ $report->description }}')">
                                    {{ $report->description }}
                                    <span class="star-icon" wire:click="toggleStarred({{ $report->id }})">
                                        @if($report->favorite)
                                        <i class="ph-star-fill"></i> <!-- Filled star if favorite is true -->
                                        @else
                                        <i class="ph-star-bold"></i> <!-- Bold star if favorite is false -->
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <!-- Pending Tab -->
        <div class="tab-pane {{ $activeSection === 'Favorites' ? 'active' : '' }}" id="pending-section">
            <div class="row m-0 px-2">
                @foreach($reportsGallery->groupBy('category') as $category => $reports)
                <div class="col-md-4">
                    <div class="allreports">
                        <div class="allreports-header">
                            <i class="bi bi-file-earmark-text"></i> {{ ucfirst(strtolower($category)) }}
                        </div>
                        <div class="allreports-body">
                            @php
                            $starredReports = $reports->where('favorite', true);
                            @endphp
                            @if($starredReports->isEmpty())
                            <p>Not added any fav</p> <!-- Message when no starred reports exist -->
                            @else
                            <ul>
                                @foreach($starredReports as $report)
                                <li>
                                    {{ $report->description }}
                                    <span class="star-icon">
                                        @if($report->favorite)
                                        <i class="ph-star-fill"></i> <!-- Filled star if favorite is true -->
                                        @else
                                        <i class="ph-star-bold"></i> <!-- Bold star if favorite is false -->
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    @if($currentSection == 'Leave Availed Report')
            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <b>{{ $currentSection }}</b>
                            </h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                                wire:click="close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="fromDate">From <span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small"
                                        wire:change="updateFromDate"   id="fromDate"
                                        wire:model="fromDate">
                                    @error('fromDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="toDate">To <span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small" 
                                        wire:change="updateToDate" wire:model="toDate"   id="toDate">
                                    @error('toDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="leaveType">Leave
                                        Type</label>
                                    <select id="leaveType"  wire:change="updateLeaveType"
                                        wire:model="leaveType" class="form-select placeholder-small">
                                        <option value="all">All Leaves</option>
                                        <option value="lop">Loss Of Pay</option>
                                        <option value="casual_leave">Casual Leave</option>
                                        <option value="earned_leave">Earned Leave</option>
                                        <option value="sick">Sick Leave</option>
                                        <optiorn value="petarnity">Paternity Leave</optiorn>
                                        <option value="maternity">Maternity Leave</option>
                                        <option value="casual_leave_probation">Casul Leave Probation</option>
                                        <option value="marriage_leave">Marriage Leave</option>

                                        <!-- Add other leave types as needed -->
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="to-date">Employee
                                        Type</label>
                                    <select id="employeeType" wire:model="employeeType"
                                        class="form-select placeholder-small">
                                        <option value="active" selected>Current Employees</option>
                                        <option value="past">Past Employees</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="to-date">Sort
                                        Order</label>
                                    <select name="sortBy" wire:model="sortBy" wire:change="updateSortBy"
                                        id="sortBySelect" class="form-select placeholder-small">
                                        <option value="newest_first" selected>Employee Number (Newest First)
                                        </option>
                                        <option value="oldest_first">Employee Number (Oldest First)
                                        </option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="submit-btn"
                                wire:click="downloadLeaveAvailedReportInExcel">Run</button>
                            <button type="button" class="cancel-btn" wire:click="resetFields"
                                style="border:1px solid rgb(2,17,79);">Clear</button>
                        </div>

                    </div>
                </div>
            </div>
  
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Negative Leave Balance')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Leave Type</label>
                            <select id="leaveType" wire:model="leaveType" class="form-select placeholder-small">
                                <option value="all">All Leaves</option>
                                <option value="lop">Loss Of Pay</option>
                                <option value="casual_leave">Casual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="petarnity">Petarnity Leave</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="casual_leave_probation">Casul Leave Probation</option>
                                <option value="marriage_leave">Marriage Leave</option>

                                <!-- Add other leave types as needed -->
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Employee
                                Type</label>
                            <select id="employeeType" wire:model="employeeType"
                                class="form-select placeholder-small">
                                <option value="active" selected>Current Employees</option>
                                <option value="past">Past Employees</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Sort
                                Order</label>
                            <select name="sortBy" wire:model="sortBy" wire:change="updateSortBy" id="sortBySelect"
                                class="form-select placeholder-small">
                                <option value="newest_first" selected>Employee Number (Newest First)
                                </option>
                                <option value="oldest_first">Employee Number (Oldest First)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadNegativeLeaveBalanceReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Day Wise Leave Transation Report')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label for="from-date">From <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="fromDate"
                                wire:change="updateFromDate" wire:model.lazy="fromDate">
                            @error('fromDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate" wire:model.lazy="toDate">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Transaction
                                Type</label>
                            <select id="transactionType" 
                                wire:change="updateTransactionType($event.target.value)"
                                wire:model.lazy="transactionType" class="form-select placeholder-small">
                                <option value="all">All Transactions</option>
                                        <option value="granted">Granted</option>
                                        <option value="availed">Availed</option>
                                        <option value="lapsed">Lapsed</option>
                                        <option value="withdrawn">Withdrawn</option>
                                        <option value="rejected">Rejected</option>
                              

                                <!-- Add other leave types as needed -->
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Employee
                                Type</label>
                            <select id="employeeType" wire:model="employeeType"
                                class="form-select placeholder-small">
                                <option value="active" selected>Current Employees</option>
                                <option value="past">Past Employees</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Sort
                                Order</label>
                            <select name="sortBy" wire:model="sortBy" wire:change="updateSortBy" id="sortBySelect"
                                class="form-select placeholder-small">
                                <option value="newest_first" selected>Employee Number (Newest First)
                                </option>
                                <option value="oldest_first">Employee Number (Oldest First)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="dayWiseLeaveTransactionReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Leave Balance As On A Day')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate" wire:model.lazy="toDate">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-2">
                        {{-- <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll" wire:model="selectAll" wire:click="toggleSelectAll">
                                        <label class="form-check-label report-select-all-text" for="selectAll">
                                            Select All
                                        </label>
                                    </div>
                                </div> --}}
                        <div class="col-md-6">
                            <div class="input-group">
                                <input wire:model="search" wire:change="searchfilterleave" type="text"
                                    class="form-control report-search-input" placeholder="Search..."
                                    aria-label="Search" aria-describedby="basic-addon1">
                                <div>
                                    <button wire:change="searchfilterleave" class="report-search-btn" type="button">
                                        <i class="fa fa-search report-search-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger mt-2">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div style="overflow-y: auto; max-height: 200px; ">
                        <table class="swipes-table mt-2 border"
                            style="width: 100%; max-height: 400px; overflow-y: auto;">
                            <tr style="background-color: #f6fbfc;">
                                <th
                                    style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color: var(--label-color);font-weight:500;white-space:nowrap;">
                                    Employee Name</th>
                                <th
                                    style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color: var(--label-color);font-weight:500;white-space:nowrap;">
                                    Employee Number</th>

                            </tr>
                            @php
                            // Sorting alphabetically by first_name and then last_name
                            $sortedEmployees = $this->filteredEmployees->sortBy(function ($employee) {
                                return strtolower($employee->first_name . ' ' . $employee->last_name); // Combine first and last name for sorting
                            });
                        
                           
                        @endphp
                        
                            @if ($sortedEmployees->isNotEmpty())
                                @foreach ($sortedEmployees as $emp)
                                    <tr style="border:1px solid #ccc;">

                                        <td
                                            style="width:50%; font-size: 10px; color: var(--label-color); text-align:start; padding:5px 10px; white-space:nowrap; display: flex; align-items: center;">
                                            <label class="custom-checkbox" style="margin-right: 5px;">
                                                <input type="checkbox" name="employeeCheckbox[]"
                                                    class="employee-swipes-checkbox" wire:model="leaveBalance"
                                                    value="{{ $emp->emp_id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                            {{ ucwords(strtolower($emp->first_name)) }}&nbsp;{{ ucwords(strtolower($emp->last_name)) }}
                                        </td>

                                        <td
                                            style="width:50%;font-size: 10px; color: var(--label-color);text-align:start;padding:5px 10px;white-space:nowrap;">
                                            {{ $emp->emp_id }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr style="border:1px solid #ccc;">
                                    <td colspan="2"
                                        style="text-align: center;  padding: 10px; font-size: 10px; color: var(--label-color);">
                                        No employees found.
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="leaveBalanceAsOnADayReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Leave Transaction Report')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-2">
                            <label for="from-date">From <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="fromDate"
                                wire:change="updateFromDate" wire:model.lazy="fromDate">
                            @error('fromDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate" wire:model.lazy="toDate">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Leave
                                Type</label>
                            <select id="leaveType" wire:model="leaveType" wire:change="updateLeaveType"
                                wire:model.lazy="leaveType" class="form-select placeholder-small">
                                <option value="all">All Leaves</option>
                                <option value="lop">Loss Of Pay</option>
                                <option value="casual_leave">Casual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="petarnity">Petarnity Leave</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="casual_leave_probation">Casul Leave Probation</option>
                                <option value="marriage_leave">Marriage Leave</option>

                                <!-- Add other leave types as needed -->
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Leave
                                Transaction</label>
                            <select id="transactionType" wire:model="transactionType"
                                class="form-select placeholder-small">
                                <option value="all">All</option>
                                <option value="granted">Granted</option>
                                <option value="availed">Availed</option>
                                <option value="withdrawn">Withdrawn</option>
                                <option value="rejected">Rejected</option>
                                <option value="lapsed">Lapsed</option>

                                <!-- Add other leave types as needed -->
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Employee
                                Type</label>
                            <select id="employeeType" wire:model="employeeType"
                                class="form-select placeholder-small">
                                <option value="active" selected>Current Employees</option>
                                <option value="past">Past Employees</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">Sort
                                Order</label>
                            <select name="sortBy" wire:model="sortBy" wire:change="updateSortBy" id="sortBySelect"
                                class="form-select placeholder-small">
                                <option value="newest_first" selected>Employee Number (Newest First)
                                </option>
                                <option value="oldest_first">Employee Number (Oldest First)
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadLeaveTransactionReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Shift Summary Report')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="form-group col-md-6 mb-2">
                                    <label for="fromDate">From Date<span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small"
                                        wire:change="updateFromDate"   id="fromDate"
                                        wire:model="fromDate"
                                        max="{{ now()->toDateString() }}">
                                    @error('fromDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate"max="{{ now()->toDateString() }}">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                       
                        <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <!-- #region -->
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="allEmployees" value="allEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="allEmployees">
                                        All Employees
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="selectedEmployees" value="selectedEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="selectedEmployees">
                                        Selected Employees
                                    </label>
                                </div>
                            </div>
                        
                    </div>
                    @if($this->employeeTypeForAttendance=='selectedEmployees')
                        <div class="toggle-button"wire:click="toggleSelectedEmployee">
                            <div class="{{ $isToggleSelectedEmployee===false ? 'all' :'none'}}">All</div>
                            <div class="{{ $isToggleSelectedEmployee===true ? 'selected' :'none'}}">Selected</div>
                        </div>
                  
                    
                    <div class="table-responsive mt-2" style="height:200px;max-height:200px;overflow-y:auto;width:100%;">
                                <table class="swipes-table mt-2 border">
                                    <tr style="background-color: #f6fbfc;">
                                        <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Name</th>
                                        <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Number</th>
                                    </tr>
                                    @foreach ($Employees as $emp)
                                    <tr style="border:1px solid #ccc;">
                                        <td style="width:50%;font-size: 10px; color:  #778899;text-align:start;padding:5px 10px;white-space:nowrap;">
                                        <label class="custom-checkbox">
                                                <input type="checkbox" name="employeeCheckbox[]" class="employee-swipes-checkbox" wire:model="EmployeeId" wire:change="updateEmployeeId" value="{{ $emp->emp_id }}">
                                                <span class="checkmark"></span>
                                                {{ucwords(strtolower($emp->first_name))}} {{ucwords(strtolower($emp->last_name))}}
                                            </label> 
                                        </td>
                                        <td style="width:50%;font-size: 10px;color:#778899;text-align:start;padding:5px 32px">{{$emp->emp_id}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>


                    @endif
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadShiftSummaryReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Absent Report')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="form-group col-md-6 mb-2">
                                    <label for="fromDate">From Date<span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small"
                                        wire:change="updateFromDate"   id="fromDate"
                                        wire:model="fromDate"max="{{ now()->toDateString() }}">
                                    @error('fromDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate"max="{{ now()->toDateString() }}">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                       
                        <div class="form-group col-md-6 mb-2"style="margin-top: 30px;">
                           <label for="includeLeftEmployees"style="white-space: nowrap;">
                                <input type="checkbox" id="includeLeftEmployees" name="includeLeftEmployees">
                                Include employees who have left the organisation
                            </label>
                        </div>
                       
                        
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadAbsentReportInExcel">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Attendance Muster Info')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="form-group col-md-6 mb-2">
                                    <label for="fromDate">From Date<span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small"
                                        wire:change="updateFromDate"   id="fromDate"
                                        wire:model="fromDate"max="{{ now()->toDateString() }}">
                                    @error('fromDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate"max="{{ now()->toDateString() }}">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                       
                        <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <!-- #region -->
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="allEmployees" value="allEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="allEmployees">
                                        All Employees
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="selectedEmployees" value="selectedEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="selectedEmployees">
                                        Selected Employees
                                    </label>
                                </div>
                            </div>
                       
                        
                    </div>
                    @if($this->employeeTypeForAttendance=='selectedEmployees')
                        <div class="toggle-button"wire:click="toggleSelectedEmployee">
                            <div class="{{ $isToggleSelectedEmployee===false ? 'all' :'none'}}">All</div>
                            <div class="{{ $isToggleSelectedEmployee===true ? 'selected' :'none'}}">Selected</div>
                        </div>
                  
                    
                    <div class="table-responsive mt-2" style="height:200px;max-height:200px;overflow-y:auto;width:100%;">
                                <table class="swipes-table mt-2 border">
                                    <tr style="background-color: #f6fbfc;">
                                        <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Name</th>
                                        <th style="width:50%;font-size: 11px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;white-space:nowrap;">Employee Number</th>
                                    </tr>
                                    @foreach ($Employees as $emp)
                                    <tr style="border:1px solid #ccc;">
                                        <td style="width:50%;font-size: 10px; color:  #778899;text-align:start;padding:5px 10px;white-space:nowrap;">
                                        <label class="custom-checkbox">
                                                <input type="checkbox" name="employeeCheckbox[]" class="employee-swipes-checkbox" wire:model="EmployeeId" wire:change="updateEmployeeId" value="{{ $emp->emp_id }}">
                                                <span class="checkmark"></span>
                                                {{ucwords(strtolower($emp->first_name))}} {{ucwords(strtolower($emp->last_name))}}
                                            </label> 
                                        </td>
                                        <td style="width:50%;font-size: 10px;color:#778899;text-align:start;padding:5px 32px">{{$emp->emp_id}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>


                    @endif
               
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadAttendanceMusterReportInExcel">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @elseif($currentSection == 'Employee Family Details')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                            <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <!-- #region -->
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="allEmployees" value="allEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="allEmployees">
                                        All Employees
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 mb-2" style="margin-top: 30px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="employeeType" id="selectedEmployees" value="selectedEmployees" wire:model="employeeTypeForAttendance" wire:change="updateEmployeeType">
                                    <label class="form-check-label" for="selectedEmployees">
                                        Selected Employees
                                    </label>
                                </div>
                            </div>
                        
                        
                        
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadFamilyDetailsReport">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
   
    @elseif($currentSection == 'Attendance Summary Report')
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <b>{{ $currentSection }}</b>
                    </h5>
                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                        wire:click="close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                       <div class="form-group col-md-6 mb-2">
                                    <label for="fromDate">From Date<span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <input type="date" class="form-control placeholder-small"
                                        wire:change="updateFromDate"   id="fromDate"
                                        wire:model="fromDate"max="{{ now()->toDateString() }}">
                                    @error('fromDate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                        </div>
                        <div class="form-group col-md-6 mb-2">
                            <label for="to-date">To Date <span style="color: var(--requiredAlert);">*</span></label>
                            <input type="date" class="form-control placeholder-small" wire:model="toDate"
                                wire:change="updateToDate"max="{{ now()->toDateString() }}">
                            @error('toDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                   
                       
                        
                    </div>
                   
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="submit-btn"
                            wire:click="downloadAttendanceMusterReportInExcel">Run</button>
                        <button type="button" class="cancel-btn" wire:click="resetFields"
                            style="border:1px solid rgb(2,17,79);">Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
    @endif


</div>