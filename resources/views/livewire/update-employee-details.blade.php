<div style="width:100%;">
    <head>

        <style>

            .empTable {
                border-collapse: collapse;
                background-color: white;
                border: 1px solid #e2e8f0;
                font-size: 12px;
                max-width:100%;
            }

            .empTable td {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                text-align: left;
            }

            .empTable th {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                text-align: center;
                background-color: #02134F;
                color: #f0f4f8;
                font-weight: bold;
                white-space: nowrap;
            }

            .empTable tbody tr:hover {
                background-color: #f0f4f8;
            }
            table,thead,tbody{
                width: 100%;
            }
            .whitespace-nowrap{
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
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('add-employee-details') }}">Add Employee</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Employee</li>
                </ol>
            </nav>
        </div>
      <div class="col-md-5 mb-2">
          <div class="d-flex justify-content-end">
              <input wire:change="filter" wire:model="search" type="text" placeholder="Search employees" class="search-input" style="border-radius: 5px;padding: 3px 5px;border: 1px solid #ccc;outline:none;">
          </div>
      </div>
    </div>
    <div class="table-responsive">
        @if (is_null($employees) || $employees->isEmpty())
        <div style="text-align: center; margin: 20px;">
            <p style="font-size: 18px; color: #555;">No records found.</p>
        </div>
        @else
        <table class="empTable">
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
                    <th class="whitespace-nowrappp" style="width:150px;">Action</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td class="whitespace-nowrap">{{ $counter++ }}</td>
                    <td >
                    @if(trim($employee->image))
                        <img src="{{ asset('storage/' . trim($employee->image)) }}" style="height:50px;width:50px" class="img-thumbnail" />
                    @else
                        @php
                            $defaultImageUrl = "https://th.bing.com/th/id/OIP.vwP9cMIiXIK1N3mPuqvxSgHaHa?w=1024&h=1024&rs=1&pid=ImgDetMain";
                        @endphp
                        <img src="{{ $defaultImageUrl }}" width="50" height="50" class="img-thumbnail" />
                    @endif

                    </td>
                    <td class="whitespace-nowrapp">{{ $employee->emp_id }}</td>

                    <td class="whitespace-nowrap">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td class="aaa">{{ $employee->email }}</td>
                    <td class="whitespace-nowrap">{{ $employee->gender }}</td>
                    <td class="whitespace-nowrapp">
                        {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}
                    </td>
                    <td class="whitespace-nowrap">{{ $employee->mobile_number }}</td>
                    <td class="whitespace-nowrap">
                        {{ ucwords(str_replace(['-', '_'], ' ', $employee->employee_type)) }}
                    </td>

                    <td class="whitespace-nowrappp">
                        <div class="row-md-12">
                            <div class="col-md-6">
                                <div class="d-inline-block">
                                    <a href="{{ route('add-employee-details', ['emp_id' => $employee->encrypted_emp_id]) }}" class="btn btn-primary btn-xs">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-inline-block"  style="margin-left: -5px;">
                                    @if ($employee->status == 1)
                                    <button class="btn btn-danger btn-xs" wire:click="deleteEmp('{{ $employee->emp_id }}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        Delete
                                    </button>
                                    @else
                                    <button class="btn btn-danger btn-xs" wire:click="deleteEmp('{{ $employee->emp_id }}')" style="background-color: lightcoral;">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        Delete
                                    </button>
                                    @endif
                                </div>
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
