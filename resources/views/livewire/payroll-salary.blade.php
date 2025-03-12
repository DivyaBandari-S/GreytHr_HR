<div class=" " style="margin: 20px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
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

        .parent:hover,
        .parent:hover .child {
            background-color: #f3f4f6;
            /* Light gray */
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items"> Salary page </span> provides information on an employee's salary such as Net pay, Gross, Total deduction, and Loss of pay for the selected payroll month. The page enables you to add/revise an employee's salary, edit the values of salary components, and process the revised salary.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div class="mt-4" style="padding-bottom: 30px;">
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
    </div>

    @if($selectedEmployee)
    <div class=" d-flex" style="width: 100%;gap:10px;justify-content:end;">
        <button wire:click="" class="btn btn-primary " style="font-size: 12px;padding:5px">Preview Payslip</button>
        <button wire:click="showRevisedSalary" class="btn bg-white text-primary border-primary float-end " style="font-size: 12px;padding:5px">Update Salary</button>
        <button wire:click="" class="btn btn-primary" style="font-size: 12px;padding:5px">Process Payroll</button>
    </div>
    <div class="row g-4 mt-1">
        <div class="col-md-7">
            <div class=" bg-white rounded shadow-md">
                <!-- Header -->
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <input
                            type="text"
                            wire:model="searchComponent"
                            wire:input="getFilteredComponentsProperty"
                            placeholder="Search by component..."
                            class="border p-2 rounded w-64 m-2" />
                        <button wire:click="toggleExpandAll" class="text-primary" style="border:none;background-color:white">
                            {{ $expandAll ? 'Collapse All' : 'Expand All' }}
                        </button>
                    </div>
                </div>

                <!-- Components List -->
                <div class="border " style="height: 500px;overflow-x:auto">

                    @foreach ($filteredcomponents as $component)
                    @if (stripos($component['name'], $searchComponent) !== false)
                    <div class="border-b ">
                        <div class="parent flex justify-between items-center cursor-pointer bg-gray-100 hover:bg-gray-200 border"
                            style="font-size: 13px;height: 35px;padding:0px 10px 0px 10px">
                            <span>
                                @if (!empty($component['children']))
                                <span wire:click="toggleExpand('{{ $component['name'] }}')" style="margin-right:5px">
                                    @if ($expanded[$component['name']])
                                    <img style="height: 10px;" src="{{asset('images/minuss.png')}}" alt="">
                                    @else
                                    <img style="height: 10px;" src="{{asset('images/plus.png')}}" alt="">
                                    @endif
                                </span>
                                @endif
                                <span>{{ $component['name'] }}</span>
                            </span>
                            <span style="margin-left: auto;">{{ number_format((float) $component['amount'], 2) }}</span>
                        </div>
                        @if (!empty($component['children']) && $expanded[$component['name']])
                        <div class="pl-4">
                            @foreach ($component['children'] as $child)
                            @include('livewire.salary-component-item', ['component' => $child,'level' => 0])
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- <div>om</div> -->
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class=" p-4" style="background-color: #edf3ff;">
                <p class="m-0" style="font-size: 1.05rem;font-weight:bold;color:#4d4d4d">CALCULATION FIELDS</p>
                <div class="mt-3 bg-white" style="padding:10px 0px 10px 10px;border-radius:5px">
                    <Span style="font-size: .875rem;">Actions</Span>
                    <hr>
                    <div class="d-flex" style=" align-items: center; gap: 10px; font-size: .875rem;">
                        <i class="fa fa-history text-primary"></i>
                        <div class="text-primary">
                            History
                        </div>
                    </div>
                </div>

                <div class="mt-3 bg-white" style="padding:10px 0px 10px 10px;border-radius:5px">
                    <Span style="font-size: .875rem;">Details</Span>
                    <hr>
                    <div class="d-flex ">
                        <span class="text-gray-600 font-medium" style="color: #A3B3C8;    font-size: 85%;">Employee</span>
                        <hr class="flex-grow-1 border-gray-300 ms-2" style="margin-right: 20px;">
                    </div>

                    <div>
                        <label for="">Join Date</label>
                        <p style="color:#394657;font-size: .875rem; margin:0px">{{\Carbon\Carbon::parse($empDetails->hire_date)->format('d M, Y')}}</p>
                    </div>
                    <div class="mt-1">
                        <label for="">Date Of Birth</label>
                        @php
                        $date_of_birth_words = $empDetails ? \App\Models\EmpSalaryRevision::calculateExperience($empDetails->date_of_birth) : 'N/A';
                        @endphp
                        <p style="color:#394657;font-size: .875rem; margin:0px"> @if($empDetails->date_of_birth) {{$empDetails->date_of_birth}} ({{ $date_of_birth_words}}) @else - @endif</p>
                    </div>
                    <div class="mt-1">
                        <label for="">Location</label>
                        <p style="color:#394657;font-size: .875rem; margin:0px">{{$empDetails->job_location}}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @else
    @endif

</div>
