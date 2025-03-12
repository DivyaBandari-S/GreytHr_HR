<div class=" " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .stepper-container {
            width: 100%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* text-align: center; */
        }

        .stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin-bottom: 20px;
        }

        .line {
            position: absolute;
            width: 100%;
            height: 4px;
            background: #ccc;
            top: 9px;
            left: 0;
            z-index: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
        }

        .circle {
            width: 20px;
            height: 20px;
            background: #ccc;
            border-radius: 50%;
            /* border: 3px solid white; */
            position: relative;
        }

        .step.active .circle {
            background: green;
        }

        .step span {
            margin-top: 5px;
            font-size: 12px;
            /* font-weight: bold; */
            color: #3472af;
        }

        .step.active span {
            font-weight: bold;
        }

        .stepper-content {
            margin-top: 20px;
            padding: 20px;
            /* padding-bottom: 0px; */
            background: #f9f9f9;
            border-radius: 5px;
        }

        .stepper-buttons {
            margin-top: 20px;
        }

        .btn {

            font-size: 14px;
        }

        .select2 {
            font-size: 12px;
        }

        .select2-results__option {
            font-size: 12px;
            /* Adjust font size */
        }

        .assetDetailsTable th,
        .leavesEncashTable th,
        .assetDetailsTable td,
        .leavesEncashTable td,
        .workDaysTable th,
        .workDaysTable td {
            border: 1px solid silver;
            padding: 5px;
        }

        .assetDetailsTable,
        .workDaysTable,
        .leavesEncashTable {
            font-size: 13px;
        }

        .notes {
            background-color: #fcf8e3;
            color: #c09853;
            padding: 5px;
            font-size: 13px
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items">Final Settlement </span> page helps you easily handle the settlement process when an employee separates from the organization. Click <span class="bold-items"> Settle Employee </span> to perform the final settlement process of an employee. Once the settlement is processed, the settlement sheets are automatically generated and ready for delivery! </p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div class="d-flex  mt-4" style="width: 100%;">
        <div class="stepper-container">
            <!-- Stepper UI -->
            <div class="stepper">
                <div class="line"></div>

                <div class="step {{ $step >= 1 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>EMPLOYEE</span>
                </div>
                <div class="step {{ $step >= 2 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>RESIGNATION DETAILS</span>
                </div>
                <div class="step {{ $step >= 3 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>NOTICE PAY</span>
                </div>
                <div class="step {{ $step >= 4 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>WORK DAYS</span>
                </div>
                <div class="step {{ $step >= 5 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>LEAVE ENCASHMENT</span>
                </div>
                <div class="step {{ $step >= 6 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>ASSETS DETAILS</span>
                </div>
                <div class="step {{ $step >= 7 ? 'active' : '' }}">
                    <div class="circle"></div>
                    <span>REMARKS</span>
                </div>
            </div>

            <!-- Stepper Content -->
            <div class="stepper-content">
                @if($step == 1)
                <h6>Step 1: Employee</h6>
                <div class="row w-50">
                    <div class="col-md-6">
                        <input id="separated_employee"
                            wire:model="selectedEmployeeType"
                            wire:change="changeRadio('separated_employee')"
                            name="employee"
                            class="form-check-input"
                            type="radio"
                            value="separated_employee">
                        <label for="separated_employee">Separated Employee</label>
                    </div>

                    <div class="col-md-6">
                        <input id="search_employee"
                            wire:model="selectedEmployeeType"
                            wire:change="changeRadio('search_employee')"
                            name="employee"
                            class="form-check-input"
                            type="radio"
                            value="search_employee">
                        <label for="search_employee">Search Employee</label>
                    </div>
                </div>
                <div class="row mt-3">

                    @if($selectedEmployeeType == 'separated_employee')
                    <div class="col-md-2">
                        <label for="">{{count($separatedEmployees)}} employee(s) to be settled
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div wire:ignore>
                            <select id="employeeDropdown" class="form-control select2" wire:model="selectedEmployee" wire:ignore>
                                <option value="">---Select---</option>
                                @foreach($separatedEmployees as $employee)
                                <option value="{{ $employee['emp_id'] }}">{{ ucwords(strtolower($employee['first_name'] . ' ' . $employee['last_name'])) }} [{{$employee['emp_id']}}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Include jQuery & Select2 -->

                    @else

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
                                <div style="position: relative;">


                                    @if ($showSearch)
                                    <div class="analytic-view-all-search-bar">
                                        <div class="search-wrapper">
                                            <input wire:click="searchFilter" wire:input="searchFilter" wire:blur=""
                                                wire:model.debounce.500ms="search" type="text" placeholder="Search...">
                                            <i class="search-icon bx bx-search"></i>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($showContainer)
                                    <div class="employees-container"
                                        style="background: white; padding: 10px; border: 1px solid black; border-radius: 5px; width: 310px;    position: absolute; z-index: 1000; top: 100%; left: 0;max-height: 250px; overflow-x: auto; ">
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
                        </div>
                        <div class="col-4 emp_sal_logo">
                            <img class="mt- emp_sal_img" src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRLhNu6xKTL2EfK_OTYoaBuqM-Irlz3eB03SmNtbY2NIn8W0NOz" alt="">
                        </div>
                    </div>
                    @endif
                </div>

                @elseif($step == 2)
                <h6>Step 2: Resignation Details</h6>
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Resignation Submitted On </label>
                    </div>
                    <div class="col-md-3">
                        <input wire:model="resignation_submitted_on" class="form-control" type="date">
                        @error('resignation_submitted_on')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="">Leaving Date</label>
                    </div>
                    <div class="col-md-3">
                        <input wire:model="leaving_date" wire:change="getSettlementDate(event.target.value)" class="form-control" type="date">
                        @error('leaving_date')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="">Leaving Reason</label>
                    </div>
                    <div class="col-md-3">
                        <select id="employeeDropdown" class="form-select select2" wire:model="leaving_reason">
                            <option value="">---Select---</option>
                            <option value="expired">EXPIRED</option>
                            <option value="abandoned">ABANDONED</option>
                            <option value="others">OTHERS</option>
                            <option value="transferred">TRANSFERRED</option>
                            <option value="sick">SICK</option>
                            <option value="termination on leave">TERMINATION ON LEAVE</option>
                            <option value="resigned on leave">RESIGNED ON LEAVE</option>
                            <option value="deported">DEPORTED</option>
                            <option value="terninated">TERNINATED</option>
                            <option value="contract expiry">CONTRACT EXPIRY </option>
                            <option value="resigned">RESIGNED</option>
                        </select>
                        @error('leaving_reason')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2 mb-2 ">
                    <div class="col-md-3">
                        <label for="">Settlement Date
                        </label>
                    </div>
                    <div class="col-md-3">
                        <input wire:model="settlement_date" class="form-control" type="date">
                        @error('settlement_date')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @elseif($step == 3)
                <h6>Step 3: Notice Pay</h6>
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-3 d-flex" style="gap: 5px;align-items:center">
                        <input class="form-check-input" wire:model="notice_required" type="checkbox"> <label>Notice Required</label>
                    </div>
                    <!-- <div class="col-md-2"></div> -->
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <label for="">Notice Period </label>
                    </div>
                    <div class="col-md-1">
                        <input value="0" min="0" wire:model="notice_period" max="500" class="form-control" type="number">
                    </div>

                    <div class="col-md-2">
                        <label>days</label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <label for="">No of Days Served </label>
                    </div>
                    <div class="col-md-1">
                        <input value="0" wire:model="served_days" min="0" max="500" class="form-control" type="number">
                    </div>

                    <div class="col-md-2">
                        <label>days</label>
                    </div>
                </div>
                @elseif($step == 4)
                <h6>Step 4: Work Days</h6>

                <div class="col-md-6 notes"> Based on leaving date workdays are updated.</div>
                <div class="col-md-6 notes mt-2">Last working day : {{\Carbon\Carbon::parse($leaving_date)->format('d M, Y')}} </div>
                @if($lastPaidDate)
                <div class="col-md-6 notes mt-2"> Last paid month : {{\Carbon\Carbon::parse($lastPaidDate)->format('M Y')}} </div>
                @endif

                <div class="mt-2">
                    <table class="workDaysTable">
                        <tr>
                            <th>Payroll Month</th>
                            <th>Work Days</th>
                            <th>Days Worked</th>
                        </tr>
                        @foreach($workingDaysData['monthly_data'] as $index => $monthData)
                        <tr>
                            <td>{{ $monthData['month'] }}</td>
                            <td>{{ $monthData['total_days'] }}</td>
                            <td> <input class="form-control" wire:model="workingDaysData.monthly_data.{{ $index }}.paid_days" wire:input="getTotalWorkDays" name="workingDays[{{ $index }}]" type="number" style="width:100px;"></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">No. of Days Worked </td>
                            <td> <input disabled class="form-control" value="{{$workingDaysData['total_paid_days'] }}" type="number" style="width:100px;"></td>
                        </tr>
                    </table>
                </div>
                @elseif($step == 5)
                <h6>Step 5: Leave Encashment</h6>
                <table class="leavesEncashTable">
                    <tr>
                        <th>Leave Type</th>
                        <th>Balance</th>
                        <th>Encash</th>
                    </tr>
                    @foreach($leavesData['leave_data'] as $index => $leaveData)
                    <tr>
                        <td>{{ $leaveData['leave_type'] }}</td>
                        <td>{{ $leaveData['pending_leaves'] }}</td>
                        <td>
                            <input class="form-control" wire:input="getTotalEncashDays"
                                wire:model.lazy="leavesData.leave_data.{{ $index }}.encash"
                                type="number" name="leavesEncash[{{ $index }}]" style="width:100px;">
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">Total </td>
                        <td>
                            <input disabled class="form-control"
                                value="{{ $leavesData['total_encash'] }}"
                                type="number" style="width:100px;">
                        </td>
                    </tr>
                </table>

                @elseif($step == 6)
                <h6>Step 6: Asset Details</h6>

                <table class="assetDetailsTable">
                    <tr>
                        <th>Emp Id</th>
                        <th>Employee Name</th>
                        <th>Asset Id</th>
                        <th>Asset Type</th>
                        <th>Assigned Date</th>
                        <th>Received Date</th>
                        <th>Return Status</th>
                    </tr>
                    @forelse($assetDetails as $asset)
                    <tr>
                        <td>{{$asset->emp_id}}</td>
                        <td>{{ucwords(strtolower($asset->first_name))}} {{ucwords(strtolower($asset->last_name))}} </td>
                        <td>{{$asset->asset_id}}</td>
                        <td>{{$asset->asset_names}}</td>
                        <td>
                            @if($asset->assigned_at)
                            {{\Carbon\Carbon::parse($asset->assigned_at)->format('d M, Y')}}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($asset->laptop_received)
                            {{\Carbon\Carbon::parse($asset->laptop_received)->format('d M, Y')}}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($asset->is_active==0)
                            <i style="color:green" class="fa fa-check"></i>
                            @else
                            <i style="color:red" class="fa fa-times"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="7">No Assets Found</td>
                    </tr>
                    @endforelse
                </table>
                @elseif($step == 7)
                <h6>Step 7:Remarks</h6>
                <div class="row mt-2">
                    <div class="col-md-2 text-end">
                        <label for="">Remarks</label>
                    </div>
                    <div class="col-md-5">
                        <textarea wire:model='remarks' height="20" class="form-control" name="" id=""></textarea>
                    </div>
                </div>
                @else
                @endif

            </div>

            <!-- Navigation Buttons -->
            <div class="stepper-buttons">
                @if($step > 1)
                <button wire:click="previousStep" class="btn bg-white text-primary border-primary ">Previous</button>
                @endif

                @if($step < 7)
                    <button wire:click="nextStep" class="btn bg-white text-primary border-primary ">Next</button>
                    @endif
                    <button onclick="window.history.back()" class="btn bg-white text-primary border-primary ">Cancel</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#employeeDropdown').select2();

            // Livewire.hook('message.processed', (message, component) => {
            //     $('#employeeDropdown').select2();
            // });

            $('#employeeDropdown').on('change', function() {
                var selectedValue = $(this).val();
                @this.set('selectedEmployee', selectedValue); // Sync with Livewire
            });
        });
    </script>

</div>
