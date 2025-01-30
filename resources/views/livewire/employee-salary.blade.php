<div>
    <style>
        .employeMain {
            padding: 20px;
        }

        .emp-sal-heading {
            font-size: 1.50rem;
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

        .emp_sal_logo {
            display: flex;
            justify-content: center;
        }

        .emp_sal_img {
            width: 50%;
            height: 80%;
        }

        .emp-sal-table th,
        .emp-sal-table td {
            text-align: center;
            vertical-align: middle;
            background-color: #F8F8F8;
        }

        .emp-sal1-table th,
        .emp-sal1-table td {
            text-align: center;
            vertical-align: middle;
            background-color: #EBEFF7;
            color: #3b4452;
        }

        .emp-sal-table th {
            font-size: 14px;

        }

        .emp-sal-table td {
            font-size: 12px;
        }

        .arrow_icn {
            width: 10px;
            height: 10px;
        }

        .chart-container {
            /* position: relative; */
            width: 100%;
            height: 200px;
            margin-left: 15px
        }

        .ctc {
            font-size: 13px;
            font-weight: 500;
        }

        .month_ctc {
            font-size: 13px;
            font-weight: 500;
            color: #32CD32;
        }

        .salaryComponents-table {
            border-collapse: collapse;
            /* Ensures borders don't double up */
            width: 100%;
            /* margin: 10px;     */
        }

        .salaryComponents-table th,
        .salaryComponents-table td {
            border-radius: 5px;
            border: 1px solid silver;
            padding: 5px;
            /* text-align: left;     */
            font-size: 13px;
        }

        .salary-amount {
            text-align: right;
        }

        .salary-component {
            text-align: left;
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

        }

        .detail-items {
            display: flex;
        }

        .label-value {
            width: 50%;
            margin-right: 10px;
        }



        .revision-items {
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            border: 1px solid silver;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
        }

        .revision-items.active {
            background-color: #eff5f7;
            color: black;
            /* border-right: none; */
        }

        .salary-revision-history {
            border: 1px solid silver;
            padding: 10px;
            background-color: #eff5f7;
            border-radius: 5px;
        }

        .salary-revision-table {
            /* border-collapse: separate; */
            width: 100%;
            border: 1px solid silver;
            border-radius: 5px;

        }


        .salary-revision-table thead tr {
            background-color: #e4e9f0;
            color: #394657;
        }

        .salary-revision-table thead tr th {
            text-align: end;
            width: 20%;
            padding: 10px;
            font-weight: lighter;
            font-size: 13px;
        }

        .salary-revision-table thead tr:first-child th:first-child {
            width: 40%;
            text-align: center;
            font-weight: bold;
        }

        .salary-revision-table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* Light gray background for odd rows */
        }

        .salary-revision-table tbody tr:nth-child(even) {
            background-color: white;
        }

        .salary-revision-table tbody tr td {
            text-align: end;
            width: 20%;
            padding: 10px;
            font-weight: lighter;
            font-size: 13px;
        }

        .salary-revision-table tbody tr td:first-child {
            width: 40%;
            text-align: left;
            font-weight: lighter;
            font-size: 13px;
        }

        textarea::placeholder {
            font-size: 12px;
            color: #eff5f7  ;
        }




        @media (min-width: 360px) and (max-width: 550px) {
            .emp_sal_img {
                width: 87%;
                height: 64%;
            }

            .emp-sal-heading {
                font-size: 1.15rem;
            }

            .emp-sal-table-graph {
                display: flex;
                flex-direction: column;
                gap: 10px
            }

            .emp-sal-rev-table {
                display: flex;
                flex-direction: column;
                gap: 10px
            }

            .search-bar {
                width: 100%;
            }

            .emp-sal-table3 {
                margin-top: 15px;
            }

        }
    </style>
    @if($showPage1)
    <div class="employeMain">
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
                                <input wire:click="searchFilter" wire:input="searchFilter"
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
                        @endif
                    </div>
                </div>
                <div class="col-4 emp_sal_logo">
                    <img class="mt- emp_sal_img" src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRLhNu6xKTL2EfK_OTYoaBuqM-Irlz3eB03SmNtbY2NIn8W0NOz" alt="">
                </div>
            </div>
        </div>

        @if($decryptedData)
        <div class="row bg-light emp-sal-rev-table p-0">
            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered bg-light emp-sal-table">
                        <thead class="">
                            <tr>
                                <th>Last Revision</th>
                                <th>Medium Revision Period</th>
                                <th>Max Revision Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$decryptedData[0]['time_gap'] }}</td>
                                <td>{{$decryptedData[0]['medium_revision_period'] }}</td>
                                <td>{{$decryptedData[0]['max_revision_period'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <button wire:click="showRevisedSalary" class="btn bg-primary text-white mb-2">Revise Salary</button>
            </div>
        </div>
        @endif

        <div class="row bg-light emp-sal-table-graph">
            @if($decryptedData)
            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered bg-light emp-sal-table">
                        <thead class="">
                            <tr>
                                <th>Effective Date</th>
                                <th>Payout Month</th>
                                <th>ANNUAL CTC</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($decryptedData as $revisionData)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('d M, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('M, Y') }}</td>
                                <td>{{number_format( $revisionData['revised_ctc'],2) }}
                                    @if ($loop->last)
                                    <p>0.00% (Rs 0.00)</p>
                                    @else

                                    <p>
                                        @if($revisionData['percentage_change_diff']>0)
                                        <img class="arrow_icn" src="{{ asset('images/up-arrow-icon.png') }}" alt="">
                                        <span class="month_ctc">+{{$revisionData['percentage_change_diff']}}   </span>(Rs{{number_format($revisionData['difference_amount'])}})
                                        @elseif($revisionData['percentage_change_diff'] < 0)
                                        <img class="arrow_icn" style="height: 13px; width:13px" src="{{ asset('images/down-arrow-icon.png') }}" alt="">
                                        <span class="month_ctcup" style="color: red;">{{$revisionData['percentage_change_diff']}}   </span>(Rs{{number_format($revisionData['difference_amount'])}})
                                        @else

                                        @endif
                                    </p>
                                    @endif

                                </td>
                                <td>{{$empDetails->job_role}}</td>
                                <td>{{$empDetails->department}}</td>
                                <td style="color:cornflowerblue;cursor:pointer" wire:click="showModal({{ $revisionData['revised_ctc'] }},'{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('d M, Y') }}','{{ $revisionData['remarks'] }}')"> View</td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="col-12 col-md-4 ">
                <div class="chart-container">
                    <canvas id="salaryChart"></canvas>

                </div>
            </div>
        </div>
        @if($decryptedData)
        <div class="bg-light emp-sal-table3">
            <div class="row mb-3">
                <div class="col-6">
                    <h5>Peer Comparison</h5>
                </div>
                <div class="col-6">
                    <button class="btn bg-white text-primary border-primary float-end ms-2 mb-1">Export Excel</button>
                    <button class="btn bg-white text-primary border-primary float-end">Define Peers</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered emp-sal1-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee...</th>
                            <th>Employee Name</th>
                            <th>Experience</th>
                            <th>Last Revision</th>
                            <th>Monthly Revision</th>
                            <th>Previous Month Revision</th>
                            <th>Difference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Content goes here -->
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
    @else
    <div class="employeMain">
        <div class=" ">
            <div class="col-md-8 bg-white">


                <table class="emp-datails-table">
                    <tbody>
                        <tr>
                            <td style="width: 50%;">

                                <div class=" detail-items">
                                    <div class=" text-end p-0 label-value"><label for=""> Join Date </label></div>
                                    <div class=" emp-table-values ">{{ \Carbon\Carbon::parse($empDetails->hire_date)->format('d M, Y') }}</div>
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
                                    <div class="text-end p-0 label-value"><label for="">Experience</label> </div>
                                    <div class="emp-table-values ">{{\App\Models\EmpSalaryRevision::calculateExperience($empDetails->hire_date)}}</div>
                                </div>
                            </td>
                            <td style="width: 50%;">
                                <div class="detail-items">
                                    <div class="text-end p-0 label-value"><label for="">Department</label> </div>
                                    <div class="emp-table-values">{{$empDetails->department}}</div>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <div class="col text-end">
                    <button wire:click="selectRevision ('null',{{$selected_revised_ctc}},0,'','')" class="btn bg-primary text-white mb-2">Add New Revision</button>
                    <button wire:click="showRevisedSalary" class="btn bg-white text-primary mb-2" style="border: 1px solid cornflowerblue ;">Back</button>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-2 ">
                            <div class="list-group">
                                @foreach($decryptedData as $revision)
                                <div wire:click="selectRevision('{{ $revision['revision_date']}}',{{ $revision['current_ctc']}},{{ $revision['revised_ctc']}},'{{ $revision['remarks']}}','{{ $revision['revision_type']}}')"
                                    class=" revision-items {{ $selectedDate == $revision['revision_date'] ? 'active' : '' }}">
                                    <p class="m-0" style="font-size: 12px;font-weight:500">{{ \Carbon\Carbon::parse($revision['revision_date'])->format('M Y') }}</p>
                                    <p class="m-0" style="font-size: 9px;">Effective: {{ \Carbon\Carbon::parse($revision['revision_date'])->format('d M Y') }}</p>

                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-10  salary-revision-history">
                            <table class="salary-revision-table">
                                <thead>
                                    <tr>
                                        <th>Salary Item</th>
                                        <th>Current Salary</th>
                                        <th>Revised Salary</th>
                                        <th>Revision %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>FULL BASIC</td>
                                        <td>Rs {{$comparisionData['current']['basic']}}</td>
                                        <td>Rs {{$comparisionData['revised']['basic']}}</td>
                                        <td>{{$comparisionData['percentage_change']}} %</td>
                                    </tr>
                                    <tr>
                                        <td>FULL HRA</td>
                                        <td>Rs {{$comparisionData['current']['hra']}}</td>
                                        <td>Rs {{$comparisionData['revised']['hra']}}</td>
                                        <td>{{$comparisionData['percentage_change']}} %</td>
                                    </tr>
                                    <tr>
                                        <td>FULL CONVEYANCE</td>
                                        <td>Rs {{$comparisionData['current']['conveyance']}}</td>
                                        <td>Rs {{$comparisionData['revised']['conveyance']}}</td>
                                        <td>0 %</td>
                                    </tr>
                                    <tr>
                                        <td>FULL DA</td>
                                        <td>Rs {{$comparisionData['current']['da']}}</td>
                                        <td>Rs {{$comparisionData['revised']['da']}}</td>
                                        <td>0 %</td>
                                    </tr>
                                    <tr>
                                        <td>FULL SPECIAL ALLOWANCE</td>
                                        <td>Rs {{$comparisionData['current']['specialallowances']}}</td>
                                        <td>Rs {{$comparisionData['revised']['specialallowances']}}</td>
                                        <td>{{$comparisionData['percentage_change']}} %</td>
                                    </tr>
                                    <tr>
                                        <td>MONTHLY CTC</td>
                                        <td>Rs {{$comparisionData['current']['monthly_ctc']}}</td>
                                        <td>Rs {{$comparisionData['revised']['monthly_ctc']}}</td>
                                        <td>{{$comparisionData['percentage_change']}} %</td>
                                    </tr>
                                    <tr>
                                        <td>ANNUAL CTC</td>
                                        <td>Rs {{$comparisionData['current']['annual_ctc']}}</td>
                                        <td><input wire:model="new_revised_ctc" wire:blur="getNewRevisedSalary" type="number" style="text-align: right;"></td>
                                        <td class="d-flex" style="gap: 5px;">
                                            <span><input wire:model="ctc_percentage" wire:blur="getPercentageRevisedSalary" type="text" style="text-align: left;"></span>
                                            <span> %</span>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="p-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Effective From:</label>
                                        <input wire:model="effectiveDate" class="form-control" type="date">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Employee Remarks:</label>
                                        <textarea wire:model="reason" placeholder="This will be  visible to Employee" class="form-control" name="" ></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Notes: </label>
                                        <textarea wire:model="notes" placeholder="This will be not visible to Employee" class="form-control" name="" ></textarea>
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary " wire:click="saveSalaryRevision"  @if(!$isNewRevised) disabled @endif>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($viewDetails)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header " style=" background-color:white">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center; color:black">Salary Revision Breakup Details
                    </h6>
                </div>
                <div class="modal-body  text-center align-center" style="font-size: 14px;color:black">
                    <div class="row">
                        <div class="col-md-6 col-12" style="text-align: left;">
                            <Label>Effective Date: <span style="font-weight: bold;color:black"> {{$effectiveDate}}</span></Label>
                        </div>
                        <div class="col-md-6 col-12" style="text-align: left;">
                            <Label>Payout Month: <span style="font-weight: bold;color:black"> {{\Carbon\Carbon::parse($effectiveDate)->format('M, Y') }}</span></Label>
                        </div>
                    </div>
                    <div class="mt-2" style="width: 100%;">
                        <table class="salaryComponents-table">
                            <tr>
                                <th class="salary-component">Components</th>
                                <th class="salary-amount">Amount</th>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL BASIC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['basic'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL DA</td>
                                <td class="salary-amount">0</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL HRA</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['hra'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL CONVEYANCE</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['conveyance'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">FULL SPECIAL ALLOWANCE</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['special_allowance'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">MONTHLY CTC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['monthly_ctc'],2)}}</td>
                            </tr>
                            <tr>
                                <td class="salary-component">ANNUAL CTC</td>
                                <td class="salary-amount">{{number_format($salaryComponentDetails['annual_ctc'],2)}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-2 text-start align-start">
                        <label for="">Remarks: <span style="font-weight: bold;color:black">{{$remarks}}</span></label>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center p-3">

                    <button type="button" class="cancel-btn" wire:click="hideDetailsModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>

    @endif

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function formatDecimalInput(input) {
        let value = input.value;
        let cursorPosition = input.selectionStart;

        // Allow only numbers and one decimal point
        let formattedValue = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

        // Update the input value while preserving cursor position
        input.value = formattedValue;
        input.setSelectionRange(cursorPosition, cursorPosition);
    }
    document.addEventListener('livewire:init', () => {
        let salaryChart;
        console.log('entering script..');
        // Listen for the browser event from Livewire
        window.addEventListener('update-chart', (event) => {
            const chartData = event.detail[0].chartData;
            // Ensure the canvas exists
            const ctx = document.getElementById('salaryChart');
            console.log(' ctx..', ctx);
            if (!ctx) {
                console.error("Canvas element not found.");
                return;
            }
            const context = ctx.getContext('2d');

            // Destroy the previous chart instance if it exists
            if (salaryChart) {
                salaryChart.destroy();
            }
            // Create the chart
            salaryChart = new Chart(context, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revised Annual Salary',
                        data: chartData.data,
                        borderColor: 'blue',
                        borderWidth: 2,
                        tension: 0.3,
                    }, ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return `₹${value.toLocaleString()}`;
                                },
                            },
                        },
                    },
                },
            });
        });
    });
</script>
