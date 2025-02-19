<div class="p-4">
    <style>
        .nav-link {
            font-size: 13px;
            font-weight: 500;
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

        .profile-image {
            height: 32px;
            width: 32px;
            background-color: lightgray;
            border-radius: 50%;
        }

        .employeType,
        .searchEmploye {
            color: grey;
        }

        .employe_Type {
            border: none;
        }

        .search-bar {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .loan-name {
            border: 1px solid #ddd;
            padding: 5px 10px;
            width: 100%;
        }

        .loan-container {
            border: 1px solid #ddd;
            padding: 10px 5px 10px 10px;
            /* width: 100%; */
        }

        .plain-button {
            border: none;
            width: fit-content;
            background-color: white;
            border-radius: 5px;
            font-size: 13px;
            color: #a3b3c8;
        }

        .input-col {
            align-items: center;
            margin-bottom: 20px;
            /* gap: 20px; */
        }

        .general-input {
            width: 50%;

        }

        .general-label {
            width: 180px;
            /* text-align: End; */
        }

        .emp-sal1-table body {
            height: 300px;

        }

        .emp-sal1-table th {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 10px 5px;
            font-size: 12px;
            background-color: #EBEFF7;
            border: 1px solid #d3cfcf;
        }

        .emp-sal1-table td {
            border-style: none;
            font-size: 12px;
            color: #394657;

        }
    </style>

    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <div>
            <p class="m-0">A loan is an amount that an organization provides on interest to its employees when they require financial assistance. Employees can apply for a loan depending on company policies and guidelines.</p>
            <p class="m-0">The <span class="bold-items"> Loan</span> page helps you create a loan for an employee, add the interest percentage, and the number of installments. The page automatically calculates the amount you must deduct monthly from the employee's salary. </p>
        </div>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

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

    <div class="row mt-3">
        <div class="col-md-1">
            <label class="loan-name" for="">LOAN</label>
        </div>
        <div class="col-md-11 loan-container">
            <p style="font-weight: bold;color:#4d4d4d;margin:0px">LOAN</p>
            <div style="margin: 10px;">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeButton === 'General' ? 'active' : '' }}"
                            wire:click="setActiveTab( 'General')" id="all-tab" data-bs-toggle="tab" href="#all"
                            role="tab" aria-controls="all" aria-selected="true">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeButton === 'Details' ? 'active' : '' }}"
                            wire:click="setActiveTab( 'Details')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                            role="tab" aria-controls="starred" aria-selected="false">Loan Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeButton === 'Repayments' ? 'active' : '' }}"
                            wire:click="setActiveTab('Repayments')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                            role="tab" aria-controls="starred" aria-selected="false">Loan Repayments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeButton === 'Revision' ? 'active' : '' }}"
                            wire:click="setActiveTab('Revision')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                            role="tab" aria-controls="starred" aria-selected="false">Loan Revision</a>
                    </li>
                </ul>
            </div>
            @if($activeButton === 'General')
            <div class="row d-flex mt-3" style="gap: 5%;">
                <div class="d-flex" style="align-items: center; gap:20px;width:fit-content">
                    <label style="font-size: 13px;" for="">Loan as on</label>
                    <select name="" class="form-select " id="" style="width: fit-content;">
                        <option value="#">--Select Loan--</option>
                        <option value="">01 Jan 2025</option>
                    </select>
                </div>
                <button class="plain-button">Create New </button>
                <button class="plain-button">Remove </button>
            </div>
            <div class="mt-3" style="font-weight: bold;font-size:large;color:#999">Loan Details</div>
            <hr>
            <div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Date of Loan </label>
                            <input class="form-control general-input" type="text">
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Deduct From</label>
                            <input class="form-control general-input" type="text">
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Created Date</label>
                            <input class="form-control general-input" type="text">
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Loan Type </label>
                            <select name="" class="form-select general-input " id="">
                                <option value="">Flat Interest</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Amount </label>
                            <input class="form-control general-input" type="text">
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Interest Rate</label>
                            <input class="form-control general-input" type="text">
                            <span style="font-size: 10px;">(%p.a)</span>
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">No of Installments</label>
                            <input class="form-control general-input" type="text">
                            <span style="font-size: 9px;">(In Months)</span>
                        </div>
                        <div class="d-flex input-col">
                            <label class="general-label" for="">Loan Account No</label>
                            <input class="form-control general-input" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3" style="font-weight: bold;font-size:large;color:#999">Installment Details</div>
            <hr>
            <div class="row">
                <div class="col-md-4 d-flex input-col" style="gap: 5px;">
                    <label class="" for="">Monthly Installment </label>
                    <input class="form-control general-input" type="number">
                </div>
                <div class="col-md-4 d-flex input-col" style="gap: 5px;">
                    <label class=" " for="">Principal Balance </label>
                    <input class="form-control general-input " type="number">
                </div>
                <div class="col-md-4 d-flex input-col" style="gap: 5px;">
                    <label class="" for="">Interest Balance</label>
                    <input class="form-control general-input " type="number">
                </div>
            </div>
            <div class="mt-3" style="font-weight: bold;font-size:large;color:#999">Other Information</div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Demand Promissory Note </label>
                        <input type="checkbox">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Perquisite Rate</label>
                        <input class="form-control general-input" type="text">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Loan Completed</label>
                        <input type="checkbox">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Completed Date</label>
                        <input class="form-control general-input" type="text">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Remarks</label>
                        <input class="form-control general-input" type="text">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center" style="gap:20px">
                <button class="btn btn-primary">Back to Salary</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </div>
        @endif
        @if($activeButton === 'Details')
        <div class="table-responsive mt-2" style="width: 100%; min-height:400px;border:1px solid #d3cfcf;">
            <table class="table table-bordered emp-sal1-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Trans Type</th>
                        <th>Amount</th>
                        <th>To Principal</th>
                        <th>To Interest</th>
                        <th>Actual Principal</th>
                        <th>Actual Interest</th>
                        <th>Remarks</th>
                        <th>Perk Value</th>
                        <th>Perk Amount</th>
                        <th>Perk Rate</th>
                    </tr>
                </thead>
                <tbody style="overflow-x:auto">

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-2" style="gap:20px">
            <button class="btn btn-primary">Back to Salary</button>
        </div>
        @endif
        @if($activeButton === 'Repayments')
        <div class="table-responsive mt-2" style="width: 100%; min-height:400px;border:1px solid #d3cfcf;">
            <table class="table table-bordered emp-sal1-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>To Principal</th>
                        <th>To Interest</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Modified Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="overflow-x:auto">

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-2" style="gap:20px">
            <button class="btn btn-primary">Back to Salary</button>
            <button class="btn btn-primary">Save</button>
        </div>
        @endif

        @if($activeButton === 'Revision')
        <div class="mt-3" style="font-weight: bold;font-size:large;color:#999">Running Loan Details</div>
        <hr>
        <div>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Loan Amount</label>
                        <input class="form-control general-input" type="text">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Loan Type</label>
                        <input class="form-control general-input" type="text">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Principal Balance</label>
                        <input class="form-control general-input" type="text">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Top Up Amount</label>
                        <input class="form-control general-input" type="text">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">New Interest Rate</label>
                        <input class="form-control general-input" type="text">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Current Interest Rate</label>
                        <input class="form-control general-input" type="text">
                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">Total Installments</label>
                        <input class="form-control general-input" type="text">

                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">No. of Installments Paid</label>
                        <input class="form-control general-input" type="text">

                    </div>
                    <div class="d-flex input-col">
                        <label class="general-label" for="">New Loan Period
                        </label>
                        <input class="form-control general-input" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-2" style="gap:20px">
            <button class="btn btn-primary">Back to Salary</button>
            <button class="btn btn-primary">Save</button>
        </div>
        <div class="table-responsive mt-3" style="width: 100%; min-height:400px;border:1px solid #d3cfcf;">
            <table class="table table-bordered emp-sal1-table">
                <thead>
                    <tr>
                        <th>Effective From</th>
                        <th>Effective Till</th>
                        <th>Opening Balance</th>
                        <th>Top Up Amount</th>
                        <th>Loan Amount</th>
                        <th>Overall Loan Amount</th>
                        <th>Interest Rate</th>
                        <th>Remaining Period</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody style="overflow-x:auto">

                </tbody>
            </table>
        </div>

        @endif



    </div>

</div>



</div>
