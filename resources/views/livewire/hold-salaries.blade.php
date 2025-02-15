<div class="main " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .emp-sal1-table th {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 3px;
            font-size: 12px;
            background-color: #EBEFF7;
        }

        .emp-sal1-table td {
            border-style: none;
            font-size: 12px;
            color: #394657;

        }

        .emp-sal1-table tbody tr:nth-child(odd) {
            background-color: rgb(228, 223, 223) !important;
            /* Light gray background for odd rows */
        }

        .emp-sal1-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Light gray background for odd rows */
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

        .profile-image {
            height: 32px;
            width: 32px;
            background-color: lightgray;
            border-radius: 50%;
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

        .emp-table-values {
            font-weight: bold;
            width: 50%;
            color: #3b4452;

        }

        .detail-items {
            display: flex;
        }

        .label-value {
            width: 50%;
            margin-right: 10px;
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items"> Hold Salary Payout </span> page helps hold employees' salaries whose details are yet to be verified or are incomplete in greytHR. It has no impact on the salary processing of concerned employees.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    @if($isPageOne)



    <div class=" d-flex  mt-4" style="width: 80%; ">
        <div>
            <input type="search" wire:model="searchtable" wire:input="getTableData" placeholder="search....." class="form-control" name="" id="">
        </div>

        <button wire:click="addHoldSalaryProcessing" class="btn bg-white text-primary border-primary float-end " style="margin-left: auto;font-size:15px">Hold Salary Payout </button>
    </div>
    <div class="table-responsive mt-2" style="width: 80%;">
        <table class="table table-bordered emp-sal1-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee No</th>
                    <th>Name</th>
                    <th>Hold Salary Payout </th>
                    <th>Hold Reason </th>
                    <th>Remarks</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($holdedPayoutEmployees)
                @foreach($holdedPayoutEmployees as $index => $holdedEmployee)
                <tr>
                    <td>{{ $index+1}}</td>
                    <td>{{$holdedEmployee->emp_id}}</td>
                    <td style="text-transform: capitalize;">{{$holdedEmployee->first_name}} {{$holdedEmployee->last_name}} </td>
                    <td> Rs {{number_format($holdedEmployee->payout,2)}}</td>
                    <td>{{$holdedEmployee->hold_reason}}</td>
                    <td>{{$holdedEmployee->remarks}}</td>
                    <td style="text-align: center;"><i class="fa fa-trash" wire:click="deleteHoldedEmployee({{$holdedEmployee->id}})" style="cursor: pointer;"></i></td>

                    @if($isDelete)
                    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirmation</h6>
                                    </div>
                                    <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                                        Are you sure you want to remove the Employee <span style="color: red;"> {{ucwords(strtolower($deleteEmpDetails->first_name))}} {{ ucwords(strtolower($deleteEmpDetails->last_name))}} [{{$deleteEmpDetails->emp_id}}] </span>from hold salary Payout?
                                    </div>
                                    <div class="d-flex gap-3 justify-content-center p-3">
                                        <button type="button" class="submit-btn " wire:click="confirmdeleteHoldedEmployee">Confirm</button>
                                        <button type="button" class="cancel-btn" wire:click="hideModel">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                    @endif
                </tr>
                @endforeach
                @endif

            </tbody>

        </table>
    </div>
    @else
    <div style="padding-bottom: 30px;">
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
                                <input wire:click="searchFilter" wire:input="searchFilter" wire:blur="hideContainer"
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

        @if($selectedEmployee && !$isAlreadyHolded)
        <div class="mt-3" style="padding-bottom: 10px; align-items:center">

            <div class="col-md-6 mt-3 d-flex flex-column align-items-center">
                <div  style="width: 70%;">

                    <label for="hold_reason">Select Hold Reason <span style="color: red;">*</span></label>
                    <select wire:model="selectedHoldReason" class="form-select">
                        <option value="">Select Hold Reason</option>
                        @foreach($holdReasons as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('selectedHoldReason')
                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mt-3 d-flex flex-column align-items-center">
                <div style="width: 70%;">
                    <label for="">Remarks </label>
                    <br>
                    <textarea wire:model='remarks' style="height: 80px;" class="form-control"></textarea>
                    @error('remarks')
                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        @endif
        @if($isAlreadyHolded)
        <div class="col-md-6 mt-2 mb-2" style="background-color: #f2dede;padding:10px 10px;font-size:12px">
            <p class="m-0" style="text-transform: capitalize;">
                Employee {{ ucfirst(strtolower($empDetails->first_name)) }} {{ ucfirst(strtolower($empDetails->last_name)) }} [{{$empDetails->emp_id}}] salary has already in hold!
            </p>
        </div>
        @endif
        <div class="d-flex col-md-6 mt-3" style="justify-content: center;gap:10px ; background-color:#d2e9ef;padding:20px">
            @if($selectedEmployee && !$isAlreadyHolded)
            <button class="btn btn-primary" wire:click="saveHoldProcessingSalary">Save</button>
            @endif
            <button class="btn bg-white text-primary border-primary" wire:click="addHoldSalaryProcessing">Cancel</button>
        </div>
    </div>
    @endif

</div>
