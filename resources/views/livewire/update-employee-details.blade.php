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

            /* .employee-profile-image-container{
                display: flex;
            } */

            .fa-sort,
            .fa-sort-down,
            .fa-sort-up {
                margin-left: 10px;
            }

            .sortavailable {
                cursor: pointer;
            }

            .Page {
                width: 100%;

                overflow: hidden;
                display: flex;
                justify-content: center;
                margin-top: 20px;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
                font-size: 10px;
            }

            .pagination {
                overflow: auto;
                width: fit-content;
            }

            .page-link {
                font-size: 10px;
            }

            .flash-message {
                position: sticky;
                top: 10px;
                /* Position the message at the top */
                z-index: 1000;
                /* Ensure it stays on top of other elements */
                background-color: #d4edda;
                /* Light green background for success */
                color: #155724;
                /* Dark green text for success */
                padding: 15px;
                margin: 0;
                border: 1px solid #c3e6cb;
                /* Border to match the success theme */
            }
        </style>
    </head>
     <div class="mt-4 p-0 "> <!--wire:poll="fetchEmployeeDetails" -->
        @if ($successMsg!='')
        <div id="success-alert" class="alert alert-success alert-dismissible fade show flash-message" wire:poll.10s='removeFlashMsg' style="
            height: 30px;
             width:fit-content;
            margin: 0 auto;
            text-align: center;
            align-items: center;
            display: flex;
            justify-content: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: lightgreen;
            color: white;
            margin-bottom: 5px;
            font-size: 12px;">
            {{ $successMsg}}
        </div>
        @endif
        @if ($errMsg!='')
        <div id="success-alert" class="alert alert-warning alert-dismissible fade show flash-message" wire:poll.10s='removeFlashMsg' style="
            height: 30px;
           width:fit-content;
            margin: 0 auto;
            text-align: center;
            align-items: center;
            display: flex;
            justify-content: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: lightgreen;
            color: white;
            margin-bottom: 5px;
            font-size: 12px;">
            {{ $errMsg}}
        </div>
        @endif
        <h4 class="text-2xl font-bold mb-2  text-center" style="color:rgb(2, 17, 79);">All Employee Details</h4>
        <div class="col-md-6  mb-2 ">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin-home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('add-employee-details') }}">Add Employee</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Employee</li>
                </ol>
            </nav>
        </div>
        <div class="row m-0 p-0">
            <!-- ... other buttons ... -->

            <div class="col-md-12 mb-2  d-flex" style=" align-items:center;justify-content:end;gap:20px">

                <div>
                    <input wire:input="fetchEmployeeDetails" wire:model="search" type="text" placeholder="Search by Name / Emp ID" class="search-input form-control" style="border-radius: 5px;padding: 3px 5px;border: 1px solid #ccc;outline:none;height:30px;">
                </div>
                <div class="d-flex  align-items-center">
                    <label for="" style="margin-right: 2px;">Gender:</label>
                    <select class="form-control   placeholder-small m-0  " wire:change='fetchEmployeeDetails' wire:model="emp_gender" style="height: 30px;width:fit-content ">
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>

                    </select>
                </div>

                <div class="d-flex  align-items-center">
                    <label for="" style="margin-right: 2px;">Status:</label>
                    <select class="form-control   placeholder-small m-0" wire:change='fetchEmployeeDetails' wire:model="emp_status" style="height: 30px;width:fit-content">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">In-Active</option>
                    </select>
                </div>
                <div class="d-flex  align-items-center">
                    <label for="" style="margin-right: 2px;">Items-Per-Page:</label>
                    <select class="form-control   placeholder-small m-0" wire:change='fetchEmployeeDetails' wire:model="perPage" style="width: fit-content;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                </div>


            </div>

        </div>
        <div style="overflow-x: auto;">
            @if (count($totalemployees)<=0 )
                <div style="text-align: center; margin: 20px;">
                <p style="font-size: 18px; color: #555;">No records found.</p>
        </div>
        @else
        <table class=" sortable empTable table-responsive">
            <thead>
                <tr>
                    {{--<th class="">S.NO </th>--}}
                    <th class="">Profile</th>
                    <th class="sortavailable" wire:click="sortBy('emp_id')">Emp ID @if($sortColumn=='emp_id')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    <th class="sortavailable" wire:click="sortBy('first_name')">Employee Name @if($sortColumn=='first_name')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    <th class="sortavailable" wire:click="sortBy('email')">Email @if($sortColumn=='email')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    <th class="sortavailable" wire:click="sortBy('gender')">Gender @if($sortColumn=='gender')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    {{-- <th class="" wire:click="sortBy('date_of_birth')">DOB</th>--}}
                    <th class="" wire:click="sortBy('emergency_contact')">Mobile Number @if($sortColumn=='emergency_contact')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    <th class="sortavailable" wire:click="sortBy('employee_type')">Employee Type @if($sortColumn=='employee_type')@if($sortDirection=='desc')<span><i class="fas fa-sort-down"></i></span>@else<span><i class="fas fa-sort-up"></i></span>@endif@else<span><i class="fas fa-sort"></i></span>@endif</th>
                    <th class="sortavailable">Actions</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach ($totalemployees as $employee)
                <tr>
                    {{-- <td class="whitespace-nowrap">{{ $counter++ }}</td>--}}
                    <td class="d-flex justify-content-center">
                        @if ($employee['image'] !== null && $employee['image']!= "null" && $employee['image']!= "Null" && $employee['image'] != "")
                        <!-- Check if the image is in base64 format -->
                        @if (strpos($employee['image'], 'data:image/') === 0)
                        <!-- It's base64 -->
                        <img src="{{ $employee['image'] }}" alt="binart" style='height:50px;width:50px' class="img-thumbnail" />
                        @else
                        <!-- It's binary, convert to base64 -->
                        <img src="data:image/jpeg;base64,{{ ($employee['image']) }}" alt="base" style='height:50px;width:50px' class="img-thumbnail" />
                        @endif
                        @else
                        <!-- Default images based on gender -->
                        @if($employee['gender'] == 'Male')
                        <img src="{{ asset('images/male-default.png') }}" class="employee-profile-image-placeholder img-thumbnail" style='height:50px;width:50px' alt="Default Image">
                        @elseif($employee['gender'] == 'Female')
                        <img src="{{ asset('images/female-default.jpg') }}" class="employee-profile-image-placeholder img-thumbnail" style='height:50px;width:50px' alt="Default Image">
                        @else
                        <img src="{{ asset('images/user.jpg') }}" class="employee-profile-image-placeholder img-thumbnail" style='height:50px;width:50px' alt="Default Image">
                        @endif
                        @endif
                    </td>

                    <td class="whitespace-nowrapp">{{ $employee['emp_id'] }}</td>

                    <td class="whitespace-nowrap">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</td>
                    <td class="aaa">{{ $employee['email'] }}</td>
                    <td class="whitespace-nowrap">{{ $employee['gender'] }}</td>
                    {{-- <td class="whitespace-nowrapp">
                                {{ \Carbon\Carbon::parse($employee['date_of_birth'])->format('d M Y') }}
                    </td>--}}
                    <td class="whitespace-nowrap">{{ $employee['emergency_contact'] }}</td>
                    <td class="whitespace-nowrap">
                        {{ ucwords(str_replace(['-', '_'], ' ', $employee['employee_type'])) }}
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:row;gap:5px;border:0px;align-items:center;justify-content:center">
                            <div style="background-color: #306cc6;border-radius:5px;">
                                <a title="Edit" href="{{ route('add-employee-details', ['emp_id' => $employee['encrypted_emp_id']]) }}" class="btn btn  btn-xs">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> <i style="color:#f0f4f8" class='fa fa-edit'></i>
                                </a>
                            </div>
                            <div class="d-inline-block">
                                @if ($employee['status'] == 1)
                                <button class="btn btn-danger " wire:click="terEmployee('{{$employee['emp_id']}}')">
                                    <i title="Terminate" class="fa-solid fa-trash-can"></i>
                                </button>
                                @else
                                <button class="btn btn-success ">
                                    <a href="{{ route('add-employee-details', ['re_emp_id' => $employee['encrypted_emp_id']]) }}">
                                        <i style="color: white;" title="Re-Join" class="fa-solid fa-recycle"></i>
                                    </a>
                                </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 mb-4">
            <nav aria-label="Page navigation d-flex justify-content-center" style="display: flex;justify-content:center">
                <ul class="pagination">
                    {{--<li class="page-item {{ $currentPage === 1 ? 'disabled' : '' }}">
                    <button class="page-link" wire:click="setPage(1)">First</button>
                    </li>--}}
                    <li class="page-item {{ $currentPage === 1 ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="setPage({{$currentPage}} - 1)">Pre</button>
                    </li>
                    @for ($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $currentPage === $i ? 'active' : '' }}">
                        <button class="page-link" wire:click="setPage({{ $i }})">{{ $i }}</button>
                        </li>
                        @endfor
                        <li class="page-item {{ $currentPage === $totalPages ? 'disabled' : '' }}">
                            <button class="page-link" wire:click="setPage({{$currentPage}} + 1)">Next</button>
                        </li>
                        {{-- <li class="page-item {{ $currentPage === $totalPages ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="setPage({{ $totalPages }})">Last</button>
                        </li>--}}
                </ul>
            </nav>
        </div>

        @endif
    </div>

</div>

@if($trerminateModal)
<div class="modal show d-block " tabindex="-1" role="dialog" style="display: block; ">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-header alert alert-success m-0">
                <h5 class="modal-title ">Employee Seperation Process</h5>
                <a style="margin-left: auto; margin-right:10px"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeTerModal"></button></a>
            </div>
            <div class="p-2">
                <div class="row justify-content-center mb-2">
                    <div class="col-md-5">
                        <label for="">Separation Type<span class="text-danger onboard-Valid">*</span></label>
                        <select class="form-control onboardinputs custom-select  placeholder-small m-0 w-100" wire:model='seperation_type'>
                            <option disabled value="">Select separation type</option>
                            <option value="resigned">Resignation</option>
                            <option value="terminated">Termination</option>

                        </select>
                        @error('seperation_type')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="seperation_date">Last Working Date<span class="text-danger onboard-Valid">*</span></label>
                        <input type="date" class="form-control onboardinputs  placeholder-small m-0 w-100" wire:model='seperation_date' max="{{ date('Y-m-d') }}">
                        @error('seperation_date')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row justify-content-center  mb-4">
                    <div class="col-md-10 ">
                        <label for="seperation_reason">Seperation Reason<span class="text-danger onboard-Valid">*</span></label>
                        <textarea class="form-control onboardinputs placeholder-small m-0 w-100" placeholder="Enter seperation reason" wire:model='seperation_reason'></textarea>
                        @error('seperation_reason')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row justify-content-center">
                    <button class="ilynn-btn" wire:click='submit'>Submit</button>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif

</div>
