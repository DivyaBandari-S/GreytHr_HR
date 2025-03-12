<div class="table-container1">
    <style>
          .container {
            max-width: 1200px;
        }

        h2 {
            font-size: 18px;
            /* Decreased the font size */
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            padding: 15px;
            border-radius: 8px;
        }

        h3 {
            font-size: 18px;
            /* Decreased the font size */
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
        }

        .form-control {
            border-radius: 8px;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .table {
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            /* White background for the table */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Shadow effect for the table */
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
        }

        .table th {
            background-color: var(--main-table-heading-bg-color);
            font-weight: 600;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        /* Adding a container with a background and padding to form and table */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            height: 400px;
            margin-bottom: 30px;
        }

        .search-input {
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 180px;
            font-size: 16px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        /* Specific styles for the table container */
        .table-container1 {

            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        /* Aligning buttons nicely */
        .table td button {
            margin-right: 5px;
        }
        .form-container,
        .table-container1 {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            /* margin-bottom: 30px; */
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
    <div class="d-flex justify-content-between flex-wrap mt-3">
        <div class="d-flex flex-wrap">
            <div class="me-2">
                <label>Employee Role:</label>
                <select class="form-select" wire:model="selectedRole" wire:change="onChange('selectedRole')">
                    <option value="all">All</option>
                    <option value="user">User</option>
                    {{-- <option value="Confirmation Letter">Confirmation Letter</option> --}}
                </select>
            </div>



            <div class="me-2">
                <label>Employee Status:</label>
                <select class="form-select" wire:model="selectedEmployeeStatus"
                    wire:change="onChange('selectedEmployeeStatus')">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="me-2">
                <label>Search:</label>
                <input type="text" class="form-control" wire:model.debounce.500ms="search"
                    wire:input="searchEmployees" placeholder="Search something..">
            </div>


        </div>

        <!-- Buttons -->
        <div class="d-flex">

            <button class="submit-btn" style="height: 35px; margin-top:20px;" wire:click="showAddEditEmployee">Add
                Employee</button>
        </div>
    </div>
    @if ($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header alert alert-success m-0">
                        <!-- <h5 class="modal-title ">Success</h5> -->
                        <h5 class="modal-title ">{{ $employee ? 'Edit Employee' : 'Add Employee' }}</h5>
                        <a style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close" wire:click="$set('showModal', false)"></button></a>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="d-flex flex-column justify-content-center">
                                <div>
                                    <div wire:click="searchFilter" style="cursor: pointer;">
                                        <label>Search Employee</label>
                                    </div>
                                    @if ($selectedEmployee)
                                        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                                            <div class="col-md-6 col-6">
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
                                                                    <img class="profile-image"
                                                                        src="{{ asset('images/user.jpg') }}"
                                                                        alt="Default Image">
                                                                @endif
                                                            @endif
                                                            <div style="margin-left: 15px; color: var(--label-color)">
                                                                <p class="Emp-name-leave-details">
                                                                    {{ ucfirst(strtolower($selectedEmployee->first_name)) }}
                                                                    {{ ucfirst(strtolower($selectedEmployee->last_name)) }}
                                                                </p>
                                                                <p class="Emp-id-leave-details">
                                                                    {{ $selectedEmployee->emp_id }}</p>
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
                                                    wire:model.debounce.500ms="search" type="text"
                                                    placeholder="Search...">
                                                <i class="search-icon bx bx-search"></i>
                                            </div>
                                        </div>
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
                                                                        src="{{ asset('images/user.jpg') }}"
                                                                        alt="Default Image">
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
                                    @if ($errorMessage)
                                        <div class="text-danger mt-2">
                                            {{ $errorMessage }}
                                        </div>
                                    @endif




                                </div>


                                <div class="form-group mt-3 mb-3">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="text" class="form-label">Employee Name</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="text" class="form-control"
                                                wire:model="employee_name"
                                                value="{{ $selectedEmployee ? $selectedEmployee->first_name . ' ' . $selectedEmployee->last_name : '' }}"
                                                readonly>
                                            @error('employee_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group mt-3 mb-3">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" id="email" class="form-control"
                                                wire:model="email"
                                                value="{{ $selectedEmployee ? $selectedEmployee->email : '' }}"
                                                readonly>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="form-group mt-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="role" class="form-label">Role</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="role" class="form-select" wire:model="role">
                                                <option value="user">User</option>
                                                {{-- <option value="admin">Admin</option> --}}
                                            </select>
                                            @error('role')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit and Cancel Buttons -->
                            <div style="margin-left: 60px; margin-top: 25px; margin-bottom: 20px;">
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="cancel-btn me-2"
                                        wire:click="cancel">Cancel</button>
                                    <button type="submit"
                                        class="submit-btn">{{ $employee ? 'Update Employee' : 'Add Employee' }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
     <!-- Success or error message -->
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- HR Employees Table -->
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Emp ID</th>
                <th>Employee Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hrEmployees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->emp_id }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->status == 0 ? 'Active' : 'Inactive' }}</td>
                    <td>{{ ucwords($employee->role) }}</td>
                    <td>
                        <!-- Action Icons: Edit, View, Delete -->
                        <a href="#" wire:click="viewProject({{ $employee->emp_id }})" title="View">
                            <i class="fas fa-eye text-secondary"></i>
                        </a>
                        <a href="#" wire:click="showAddEditEmployee({{ $employee->emp_id }})" title="Edit" class="mx-2">
                            <i class="fas fa-edit text-info"></i>
                        </a>
                        <a href="#" wire:click="deleteEmployee({{ $employee->emp_id }})" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
