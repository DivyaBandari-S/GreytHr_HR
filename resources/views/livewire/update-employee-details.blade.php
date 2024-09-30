<div style="width:100%;">

    <head>

        <style>
            .empTable {
                border-collapse: collapse;
                background-color: white;
                border: 1px solid #e2e8f0;
                font-size: 12px;
                overflow-x: auto;
                width: 100%;
            }

            .empTable td {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                /* text-align: left; */
            }

            .empTable th {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                text-align: center;
                background-color: #306cc6;
                color: white;
                font-weight: bold;
                /* white-space: nowrap; */

            }

            .empTable tbody tr:hover {
                background-color: #f0f4f8;
            }

            .whitespace-nowrap {
                text-transform: capitalize;
                text-align: center;
            }
        </style>
    </head>
    <div class="mt-4 p-0">
        <h4 class="text-2xl font-bold mb-2  text-center" style="color:rgb(2, 17, 79);">All Employee Details</h4>
        <div class="row m-0 p-0">
            <!-- ... other buttons ... -->
            <div class="col-md-6  mb-2 ">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('hello') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('add-employee-details') }}">Add Employee</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Employee</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-5 mb-2">
                <div class="d-flex justify-content-end">
                    <input wire:input="filter" wire:model="search" type="text" placeholder="Search employees" class="search-input" style="border-radius: 5px;padding: 3px 5px;border: 1px solid #ccc;outline:none;">
                </div>
            </div>
        </div>
        <div style="overflow-x: auto;">
            @if (count($employees)<=0 || $employees->isEmpty())
                <div style="text-align: center; margin: 20px;">
                    <p style="font-size: 18px; color: #555;">No records found.</p>
                </div>
                @else
                <table class="empTable table-responsive">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap" wire:click="sortBy('emp_id')">S.NO</th>
                            <th class="whitespace-nowrap" wire:click="sortBy('image')">Profile</th>
                            <th class="whitespace-nowrapp" wire:click="sortBy('emp_id')">Emp ID</th>
                            <th class="whitespace-nowrap" wire:click="sortBy('name')">Name</th>
                            <th class="aaa" wire:click="sortBy('email')">Email</th>
                            <th class="whitespace-nowrap" wire:click="sortBy('gender')">Gender</th>
                            <th class="whitespace-nowrapp" wire:click="sortBy('date_of_birth')">DOB</th>
                            <th class="whitespace-nowrap" wire:click="sortBy('mobile_number')">Mobile No</th>
                            <th class="whitespace-nowrap" wire:click="sortBy('employee_type')">Employee Type</th>
                            <th class="whitespace-nowrappp">Action</th>
                            <!-- Add more table headers as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                        <tr>
                            <td class="whitespace-nowrap">{{ $counter++ }}</td>
                            <td>
                                @if ($employee->image !== null && $employee->image != "null" && $employee->image != "Null" && $employee->image != "")
                                <!-- Check if the image is in base64 format -->
                                @if (strpos($employee->image, 'data:image/') === 0)
                                <!-- It's base64 -->
                                <img src="{{ $employee->image }}" alt="binart" style='height:50px;width:50px' class="img-thumbnail" />
                                @else
                                <!-- It's binary, convert to base64 -->
                                <img src="data:image/jpeg;base64,{{ ($employee->image) }}" alt="base" style='height:50px;width:50px' class="img-thumbnail" />
                                @endif
                                @else
                                <!-- Default images based on gender -->
                                @if($employee->gender == 'Male')
                                <div class="employee-profile-image-container mb-2">
                                    <img src="{{ asset('images/male-default.png') }}" class="employee-profile-image-placeholder" style='height:50px;width:50px' alt="Default Image">
                                </div>
                                @elseif($employee->gender == 'Female')
                                <div class="employee-profile-image-container mb-2">
                                    <img src="{{ asset('images/female-default.jpg') }}" class="employee-profile-image-placeholder" style='height:50px;width:50px' alt="Default Image">
                                </div>
                                @else
                                <div class="employee-profile-image-container mb-2">
                                    <img src="{{ asset('images/user.jpg') }}" class="employee-profile-image-placeholder" style='height:50px;width:50px' alt="Default Image">
                                </div>
                                @endif
                                @endif
                            </td>

                            <td class="whitespace-nowrapp">{{ $employee->emp_id }}</td>

                            <td class="whitespace-nowrap">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td class="aaa">{{ $employee->email }}</td>
                            <td class="whitespace-nowrap">{{ $employee->gender }}</td>
                            <td class="whitespace-nowrapp">
                                {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap">{{ $employee->emergency_contact }}</td>
                            <td class="whitespace-nowrap">
                                {{ ucwords(str_replace(['-', '_'], ' ', $employee->employee_type)) }}
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:row;gap:5px;border:0px;align-items:center;justify-content:center">
                                    <div style="background-color: #306cc6;border-radius:5px;">
                                        <a href="{{ route('add-employee-details', ['emp_id' => $employee->encrypted_emp_id]) }}" class="btn btn  btn-xs">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <i style="color:#f0f4f8" class='far fa-edit'></i>
                                        </a>
                                    </div>
                                    <div class="d-inline-block">
                                        @if ($employee->status == 1)
                                        <button class="btn btn-danger " wire:click="deleteEmp('{{ $employee->emp_id }}')">

                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-danger " wire:click="deleteEmp('{{ $employee->emp_id }}')" style="background-color: lightcoral;">

                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @endif
        </div>

    </div>

</div>
