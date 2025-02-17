<div class="container mt-4">


    <!-- Progress Bar -->
    <style>
        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 62%;
            position: relative;
            margin: 20px 130px;
        }

        .progress-line {
            position: absolute;
            top: 30%;
            left: -16%;
            width: 158%;
            height: 4px;
            background-color: #d1d1d1;
            z-index: 0;
            transform: translateY(-50%);
        }



        .progress-step {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: white;
            transition: background-color 0.3s ease-in-out;
            border: 2px solid #d1d1d1;
        }

        .circle.completed {
            background-color: lightgreen;
            border-color: lightgreen;
        }

        .circle.active {
            background-color: darkgreen;
            border-color: darkgreen;
        }

        .circle.pending {
            background-color: #d1d1d1;
            border-color: #d1d1d1;
        }

        .label {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        .employee-leave-hr-main {
            margin: 10px;
        }

        .employee-leave-searchrow {
            background-color: white;
            border-radius: 2px;
        }

        .employee-leave-start-para {
            color: #2d2b2b;
            font-weight: 600;
            font-size: 15px;
        }

        .employee-search-hr {
            padding: 5px 10px;
        }

        .Employee-select-leave {
            font-weight: 500;
            font-size: var(--main-headings-font-size);
            color: var(--main-heading-color);
        }

        .search-employee-type-leave {
            align-items: center;
            padding-bottom: 10px;
            display: flex;
            margin-right: 5px;
            color: var(--label-color);
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

        .Employee-leave-table-header {
            border-bottom: 1px solid rgb(199, 196, 196);
            height: fit-content;
        }

        .leave-balance-lave-types {
            width: fit-content;
            align-items: end;
            margin-top: 0px;
        }

        .leave-balance-lave-types button {
            padding: 0px 10px;
            font-size: 13px;
            color: var(--main-button-color);
            border: 0px;
            background-color: white;
            height: max-content;
            height: 30px;
        }

        .Leave-balance-buttons {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .Leave-balance-buttons button {
            color: var(--main-button-color);
            background-color: white;
            border: 0.5px solid var(--main-button-color);
            height: fit-content;
            font-size: 13px;
            font-weight: 500;
            border-radius: 5px;
        }

        .Employee-leave-table-maindiv {
            border: 0.5px solid rgb(199, 196, 196);
            padding: 0px;
        }

        .modal-title {
            color: #fff;
            font-weight: 600;
            font-size: var(--home-headings-font-size);
        }

        .modal-header {
            background-color: var(--main-button-color);
            color: white;
            height: 50px;
        }

        .btn-close {
            background-color: white;
            height: 7px;
            font-size: var(--normal-font-size);
            width: 7px;
        }

        .placeholder-small {
            /* color: var(--label-color); Placeholder text color */
            font-size: var(--normal-font-size);
            font-family: "Montserrat", sans-serif;
            font-weight: normal;
        }

        .error-text {
            font-size: var(--normal-font-size);
        }

        .task-follower-filter-container {
            margin-bottom: 10px;
        }

        .task-input-group-container {
            width: 230px;
        }

        .task-follower-search-container {
            width: 250px;
            padding-left: 10px;
        }

        .task-search-input {
            font-size: var(--normal-font-size);
            border-radius: 5px 0 0 5px;
            height: 32px;
        }

        .task-search-btn {
            height: 32px;
            width: 40px;
            position: relative;
            border-radius: 0 5px 5px 0;
            background-color: rgb(2, 17, 79);
            color: #fff;
            border: none;
            margin-right: 10px;
        }

        .task-search-icon {
            position: absolute;
            top: 9px;
            left: 11px;
            color: #fff;
        }

        .task-follower-close-icon {
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            background-color: rgb(2, 17, 79);
            padding: 6px 10px;
            border-radius: 5px;
        }

        .profile-image {
            height: 32px;

            width: 32px;

            background-color: lightgray;

            border-radius: 50%;
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

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            /* padding: 1rem; */
        }

        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: var(--main-button-color) var(--main-button-color) #fff var(--main-button-color);
            color: var(--main-button-color);
        }

        .nav-tabs .nav-link {
            font-size: var(--sub-headings-font-size);
            color: black;
        }

        .nav-tabs .nav-link.folder-active {
            font-size: var(--sub-headings-font-size);
            color: var(--main-button-color);
        }

        .nav-link {
            border: 1px solid transparent;
        }

        .list-group-item {
            padding: 0.5rem 1rem;
            color: var(--main-button-color);
            font-size: var(--sub-headings-font-size);
        }

        .analytic-view-all-search-bar {
            display: flex;
            padding: 20px 0px;
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

        .task-date-range-picker {
            display: flex;
            justify-content: flex-end;
            margin-left: 10px;
        }

        .task-custom-select-width {
            width: 170px;
            font-size: var(--normal-font-size);
            padding: 7px;
        }
    </style>
    <div>
        @if ($showHelp == false)
            <div class="row main-overview-help">
                <div class="col-md-11 col-10 d-flex flex-column">
                    <p class="main-overview-text">The Generate Letter wizard guides you through the process of generating
                        a letter for an employee. You can also send it to one or multiple employees in one effort. Note:
                        You can download a copy of the letter in the Generate Letter: Summary Page.</p>
                    <p class="main-overview-text">Learn more about the process by watching the <span
                            class="main-overview-highlited-text">
                            video</span> here. To view frequently asked questions <span
                            class="main-overview-highlited-text"> click</span>
                        here.</p>
                </div>
                <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                    <span wire:click="hideHelp">Hide Help</span>
                </div>
            </div>
        @else
            <div class="row main-overview-help">
                <div class="col-11 d-flex flex-column">
                    <p class="main-overview-text">The Generate Letter wizard guides you through the process of
                        generating a letter for an employee. You can also send it to one or multiple employees in one
                        effort. Note: You can download a copy of the letter in the Generate Letter: Summary Page.</p>

                </div>
                <div class="hide-main-overview-help col-1">
                    <span wire:click="showhelp">Show Help</span>
                </div>
            </div>
        @endif
    </div>

    <div class="progress-container">
        <div class="progress-line"></div>

        <!-- Step 1 -->
        <div class="progress-step">
            <div class="circle {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : 'pending' }}">1
            </div>
            <div class="label">General</div>
        </div>

        <!-- Step 2 -->
        <div class="progress-step">
            <div class="circle {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : 'pending' }}">2
            </div>
            <div class="label">Select Employees</div>
        </div>

        <!-- Step 3 -->
        <div class="progress-step">
            <div class="circle {{ $currentStep >= 3 ? ($currentStep == 3 ? 'active' : 'completed') : 'pending' }}">3
            </div>
            <div class="label">Preview</div>
        </div>
    </div>

    <div class="mt-4 p-4 border rounded bg-white">

        @if ($currentStep == 1)
            <div class="row">
                <div class="col-md-6">
                    <h5>Step 1: General</h5>

                    <div class="mb-3">
                        <label class="form-label">Letter Template</label>
                        <select class="form-select" wire:model="template_name" wire:change="updateTemplateName">
                            <option value="">Select Letter Template</option>
                            <option value="Appointment Order">Appointment Order</option>
                            <option value="Confirmation Letter">Confirmation Letter</option>
                        </select>
                        @error('template_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Serial No.</label>
                        <input type="text" class="form-control" wire:model="serial_no" readonly>
                        <small class="text-muted">(Provisional, may change at the time of generation)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Authorized Signatory</label>
                        <div class="d-flex">
                            {{-- <select class="form-select" wire:model="authorized_signatory">
                                <option value="Daisy M (CEO)" selected>Daisy M (CEO)</option>
                                <option value="John D (HR Manager)">John D (HR Manager)</option>
                            </select> --}}
                            <select class="form-select" wire:model="authorized_signatory" wire:change="updateAuthorizedSignatory">
                                <option value="">Select Signatory</option>
                                @foreach ($signatories as $signatory)
                                    <option
                                        value="{{ $signatory->first_name }} {{ $signatory->last_name }} ({{ $signatory->designation }})">
                                        {{ $signatory->first_name }} {{ $signatory->last_name }}
                                        ({{ $signatory->designation }})
                                    </option>
                                @endforeach
                            </select>

                            <a href="{{ route('authorize-signatory.page') }}" class="btn btn-link">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>

                        @error('authorized_signatory')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" wire:model="remarks"></textarea>
                    </div>
                </div>
            </div>
        @elseif($currentStep == 2)
            <h5>Step 2: Select Employees</h5>
            <p>Select employees for the letter.</p>

            {{-- Generate For Label with Radio Buttons --}}
            <div class="mb-3 d-flex align-items-center">
                <label class="fw-bold me-3">Generate For:</label>
                <div class="form-check form-check-inline">
                    <input type="radio" id="singleEmployee" class="form-check-input" wire:model="generateFor" wire:change="generateForChanged($event.target.value)"
                        value="single">
                    <label class="form-check-label" for="singleEmployee">Single</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="multipleEmployees" class="form-check-input" wire:model="generateFor" wire:change="generateForChanged($event.target.value)"
                        value="multiple">
                    <label class="form-check-label" for="multipleEmployees">Multiple</label>
                </div>
                @error('generateFor')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            {{-- Employee Search Field --}}
            @if ($generateFor == 'single')
                <div class="mb-3">
                    <label for="employeeSearch" class="fw-bold">Employee</label>
                    <div>
                        <div wire:click="searchFilter" style="cursor: pointer;">
                            <label>Search Employee</label>
                        </div>
                      
                        @if ($selectedEmployee)
                        @php
                        $employee= App\Models\EmployeeDetails::where('emp_id', $selectedEmployee)->first();
                        $employeeDetailsCollection = collect($selectedEmployeeDetails);
                      @endphp
                            <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                                <div class="col-md-4 col-6">
                                    <div class="d-flex Employee-details-hr">
                                        <div class="d-flex Employee-details-img-details-hr">
                                            @if ($employeeDetailsCollection && $employeeDetailsCollection->isNotEmpty())
                                                {{-- @php $selectedEmployee = $selectedEmployeeDetails->first();
                                                dd($selectedEmployee); @endphp --}}
                                                @if ($employee->image)
                                                    <img src="data:image/jpeg;base64,{{ $employee->image }}"
                                                        alt="base" class="profile-image" />
                                                @else
                                                    <!-- Gender-based default image -->
                                                    @if ($employee->gender == 'Male')
                                                        <img class="profile-image"
                                                            src="{{ asset('images/male-default.png') }}"
                                                            alt="Default Male Image">
                                                    @elseif ($employee->gender == 'Female')
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
                                                        {{ ucfirst(strtolower($employeeDetailsCollection['name'])) }}
                                                        
                                                    </p>
                                                    <p class="Emp-id-leave-details">{{ $employeeDetailsCollection['id'] }}
                                                    </p>
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
                            @error('selectedEmployee')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        @endif

                        @if ($showContainer)
                            <div
                                style="background: white; padding: 10px; border: 1px solid black; border-radius: 5px; width: 310px; position: absolute; z-index: 100; max-height:250px;overflow-y:auto;">
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
                    @error('employees')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @else
                {{-- Multiple Employees Selection UI (Table with Checkboxes) --}}
                <div class="mb-3">
                    <label class="fw-bold mb-3">Select Employees</label>
                    <div style="height: 400px; max-height: 400px; overflow-y: auto;">
                        <table class="analytic-table" >
                            <thead>
                                <tr>
                                    <th><input type="checkbox" wire:model="selectAll"></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model="selectedEmployees" value="{{ $employee['id'] }}">
                                        </td>
                                        <td>{{ $employee['id'] }}</td>
                                        <td>{{ $employee['name'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   
                </div>
            @endif

            @if ($template_name == 'Appointment Order')
                <div class="mb-3">
                    <label class="form-label">CTC</label>
                    <input type="number" class="form-control" wire:model="ctc">
                    @error('ctc')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            @elseif ($currentStep == 3)
            
           
            <h5>Step 3: Preview</h5>
            <p>Review your selections before finalizing the letter.</p>
        
            @livewire('letter-preview', ['previewLetter' => $previewLetter])
        @endif

    </div>

    <div class="m-3 d-flex justify-content-start gap-3">
        @if ($currentStep > 1)
            <button class="btn btn-secondary" wire:click="previousStep">Previous</button>
        @endif
        <button class="btn btn-light">Cancel</button>
        @if ($currentStep < 3)
            <button class="btn btn-primary" wire:click="nextStep">Next</button>
        @else
            <button class="btn btn-success" wire:click="saveLetter">Generate Letter</button>
        @endif
    </div>
</div>
</div>
