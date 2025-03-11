<div class="table-container1">
    <div class="d-flex justify-content-between flex-wrap mt-3">
        <div class="d-flex flex-wrap">
            <div class="me-2">
                <label>Employee Role:</label>
                <select class="form-select" wire:model="selectedRole"
                    wire:change="onChange('selectedRole')">
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
                <input type="text" class="form-control" wire:model.debounce.500ms="search" wire:input="searchEmployees" placeholder="Search something..">
            </div>
            

        </div>

        <!-- Buttons -->
        <div class="d-flex">
           
            <button class="submit-btn" style="height: 35px; margin-top:20px;" wire:click="showAddEditEmployee">Add Employee</button>
        </div>
    </div>
    {{-- <div class="d-flex justify-content-end">
        
        <input type="text" class="search-input" wire:model="search" wire:input="searchEmployees" placeholder="Search...">
        <button class="submit-btn" style="height: 35px;">Add Employee</button>
    </div> --}}

    <style>
        .container {
            max-width: 1200px;
        }
    
        h2 {
            font-size: 18px; /* Decreased the font size */
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            padding: 15px;
            border-radius: 8px;
        }
    
        h3 {
            font-size: 18px; /* Decreased the font size */
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
            background-color: white; /* White background for the table */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow effect for the table */
        }
    
        .table th, .table td {
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
        .form-container{
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
    </style>
    
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
            @foreach($hrEmployees as $employee)
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
                        <a href="#"  title="Edit" class="mx-2">
                            <i class="fas fa-edit text-info"></i>
                        </a>
                        <a href="#" wire:click="deleteProject({{ $employee->emp_id }})" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

