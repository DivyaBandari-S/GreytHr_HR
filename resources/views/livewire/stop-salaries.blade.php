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
        <p>From the <span class="bold-items"> Stop Salary Processing </span> page, you can stop processing the payroll for an employee. Click <span class="bold-items"> Add Stop Salary Processing </span> to stop the payroll processing. This is usually done when an employee is on long leave without pay, absconding, on notice, or pending settlement. When you remove an employee from the list, the previous month's status is not affected and the employee continues to be on the stop-payment list for the previous months.</p>
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
            <input type="search" placeholder="search....." class="form-control" name="" id="">
        </div>

        <button wire:click="addStopSalaryProcessing" class="btn bg-white text-primary border-primary float-end " style="margin-left: auto;font-size:15px">Add Stop Salary Processing </button>
    </div>
    <div class="table-responsive mt-2" style="width: 80%;">
        <table class="table table-bordered emp-sal1-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Number</th>
                    <th>Name</th>
                    <th>Remarks</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($stoppedPayoutEmployees)
                @foreach($stoppedPayoutEmployees as $index => $stoppedEmployee)
                <tr>
                    <td>{{ $index+1}}</td>
                    <td>{{$stoppedEmployee->emp_id}}</td>
                    <td style="text-transform: capitalize;">{{$stoppedEmployee->first_name}} {{$stoppedEmployee->last_name}} </td>
                    <td>{{$stoppedEmployee->reason}}</td>
                    <td>Delete</td>
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

        @if($selectedEmployee && !$isAlreadyStopped)
        <div class="mt-3" style="padding-bottom: 10px;">
            <div class="col-md-6 ">
                <table class="emp-datails-table ">
                    <tbody>
                        <tr>
                            <td style="width: 50%;">

                                <div class=" detail-items">
                                    <div class=" text-end p-0 label-value"><label for=""> Join Date </label></div>
                                    <div class=" emp-table-values "> {{ \Carbon\Carbon::parse($empDetails->hire_date)->format('d M, Y') }}</div>
                                </div>
                            </td>
                            <td style="width: 50%;">
                                <div class="detail-items">

                                    <div class=" text-end p-0 label-value"><label for="">Designation</label> </div>
                                    <div class="emp-table-values ">{{$empDetails->job_role}} </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <div class="detail-items">
                                    <div class="text-end p-0 label-value"><label for="">Payroll Month</label> </div>
                                    <div class="emp-table-values ">{{$payout_month}}</div>
                                </div>
                            </td>
                            <td style="width: 50%;">
                                <div class="detail-items">
                                    <div class="text-end p-0 label-value"><label for="">Location</label> </div>
                                    <div class="emp-table-values">{{$empDetails->job_location}}</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 mt-3">
                <label for=""> Reason for stopping salary process <span style="color: red;">*</span></label>
                <br>
                <textarea wire:model='reason' style="height: 80px;" class="form-control"></textarea>
                @error('reason')
                <span class="text-danger onboard-Valid">{{ $message }}</span>
                @enderror
            </div>

        </div>
        @endif
        @if($isAlreadyStopped)
        <div class="col-md-6 mt-2 mb-2" style="background-color: #f2dede;padding:10px 10px;font-size:12px">
            <p class="m-0" style="text-transform: capitalize;">
                Payment already stopped for {{ ucfirst(strtolower($empDetails->first_name)) }} {{ ucfirst(strtolower($empDetails->last_name)) }} ({{$empDetails->emp_id}}) for the month {{$payout_month}}.
            </p>
        </div>
        @endif
        <div class="d-flex col-md-6 mt-3" style="justify-content: center;gap:10px ; background-color:#d2e9ef;padding:20px">
            @if($selectedEmployee  && !$isAlreadyStopped)
            <button class="btn btn-primary" wire:click="saveStopProcessingSalary">Save</button>
            @endif
            <button class="btn bg-white text-primary border-primary" wire:click="addStopSalaryProcessing">Cancel</button>
        </div>
    </div>
    @endif

</div>
