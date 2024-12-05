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
                                @if($employeeLeaveBalance)
                                @foreach($employeeLeaveBalance as $batchId => $leaveBalances)
                                <tr>
                                    <td colspan="8">
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <div class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button">
                                                        <i class="fas fa-plus"></i>
                                                            <div class="d-flex flex-column">
                                                                <span>Batch ID: <strong>oo</strong></span>
                                                                <span>granted on: <strong>01 dec 34:u789</strong></span>
                                                            </div>
                                                            <div>
                                                                peroid
                                                            </div>
                                                            <div>
                                                                leavetype
                                                            </div>
                                                            <div>
                                                                headcount
                                                            </div>
                                                    </button>

                                                </div>

                                                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">
                                                        Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>no data found</td>
                                </tr>
                                @endif
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
                                <select wire:model="selectedYear" wire:change="updateDateRange" class="form-control" style="font-size: 14px;">
                                    <option value="" disabled>Select Year</option>
                                    @foreach($yearRange as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                            <button class="submit-btn">
                                <a class="btnAnchor" href="/hr/user/leavePolicySettings">Leave Settings</a>
                            </button>
                        </div>
                    </div>
                    <div>
                        <!-- Employee Selection -->
                        <div class="mb-3">
                            <div class="form-check">
                                <div>
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="selectAllEmployees"
                                        wire:model="selectAll"
                                        wire:click="toggleSelectAll">
                                    <label class="form-check-label mt-2" for="selectAllEmployees">
                                        Select All Employees
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Periodicity and Period -->
                        <div class="fieldsWidth">
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
                                        @if($leavePolicies->isNotEmpty())
                                        @foreach($leavePolicies as $policy)
                                        <tr class="trHover">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" wire:model="selectedPolicyIds" value="{{ $policy->id }}">
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
<script>
    // JavaScript to toggle icon on accordion open/close
    const accordionButtons = document.querySelectorAll('.accordion-button');

    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const collapseTarget = this.closest('.accordion-item').querySelector('.accordion-collapse');

            // Toggle the collapse using Bootstrap collapse methods
            if (collapseTarget.classList.contains('show')) {
                collapseTarget.classList.remove('show');
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            } else {
                collapseTarget.classList.add('show');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        });
    });
</script>