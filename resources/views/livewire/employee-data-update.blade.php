<div>
    <div class="container-fluid px-1  rounded">
        <ul class="nav bg-white leave-grant-nav-tabs d-flex gap-3 py-1" id="myTab" role="tablist">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                <div class="displayContnet">
                    @if($action == 'delete')
                    <div class="empDelContent">
                        <div class="row main-overview-help py-3">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Delete Employee</span> page enables you to remove those employees from the database who have either worked for just one day or been accidentally created. You can also use this page to delete duplicate or incorrect employee records. NOTE: Use this only in rare situations.<span class="msgHeighlighter">Definitely not to be used for resigned employees.</span> </p>
                            </div>
                            <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                                <span wire:click="hideHelp">Hide Help</span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex flex-column position-relative">
                                <label for="search" class="mb-2"><strong>Search An Employee</strong></label>
                                <div class="searchEmp position-relative">
                                    <input
                                        type="text"
                                        class="form-control rounded-pill py-3 "
                                        wire:click="toggleSearchEmployee"
                                        placeholder="Search Employee"
                                        wire:model.live="searchTerm"
                                        wire:keyup="loadEmployeeList">

                                    @if($searchTerm)
                                    <button
                                        type="button"
                                        class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                        wire:click="closeSearchContainer"
                                        aria-label="Clear Search" style="width:30px;color:#ccc;">
                                        <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @if($showEmployeeSearch)
                            <div class="selectEmp mb-3 bg-white d-flex flex-column position-absolute">
                                <div class="d-flex justify-content-end">
                                    <span wire:click="closeSearchContainer"><i class="fa fa-times-circle text-muted" style="color:#ccc;"></i></span>
                                </div>
                                <div>
                                    @if(count($employeeIds) > 0)
                                    @foreach($employeeIds as $emp_id => $emp_data)
                                    <div class="d-flex p-2 align-items-start gap-2 mb-2 border rounded bg-white" wire:click="getSelectedEmp('{{  $emp_id }}')">
                                        <!-- Display employee image if image exists -->
                                        @if ($emp_data['image'] !== null && $emp_data['image']!= "null" && $emp_data['image']!= "Null" && $emp_data['image'] != "")
                                        <img src="data:image/jpeg;base64,{{ ($emp_data['image']) }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif

                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                {{ ucwords(strtolower($emp_data['full_name'])) }}
                                            </span>
                                            <small class="normalText">
                                                {{ strtoupper($emp_id) }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="text-center subTextValue py-2">No data found for your search query.</div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($openSelecetdEmpDetails)
                            <div class="selecetdEmpDet">
                                <div class="row p-0 my-3">
                                    <div class="col px-2">
                                        <div class="profileCon d-flex flex-row gap-2 align-items-start">
                                            @if ($empToBeDelet->image !== null && $empToBeDelet->image != "null" && $empToBeDelet->image != "Null" && $empToBeDelet->image != "")
                                            <img src="data:image/jpeg;base64,{{ $empToBeDelet->image }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                            @else
                                            <!-- Fallback image if no image is found -->
                                            <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                            @endif

                                            <!-- Display employee name -->
                                            <div class="d-flex flex-column">
                                                <span class="normalText">
                                                    {{ ucwords(strtolower($empToBeDelet->first_name)) }} {{ ucwords(strtolower($empToBeDelet->last_name)) }}
                                                </span>
                                                <small class="normalText">
                                                    {{ strtoupper($empToBeDelet->emp_id) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row bg-white rounded m-0 border p-2 py-4 vh-auto">
                                    <div class="col-md-4">
                                        <div class="emp-content rounded d-flex gap-4 justify-content-center border">
                                            <div class="d-flex flex-column gap-2">
                                                <span class="normalText">Joined On</span>
                                                <span class="normalText">Email</span>
                                                <span class="normalText">Designation</span>
                                                <span class="normalText">Location</span>
                                            </div>

                                            <div class="d-flex flex-column gap-2">
                                                <span class="normalText">{{ \Carbon\Carbon::parse($empToBeDelet->hire_date)->format('d M, Y') ?? 'N/A'  }}</span>
                                                <span class="normalText">{{ $empToBeDelet->email ?? 'N/A' }}</span>
                                                @php
                                                $jobRole = $empToBeDelet->job_role ?? 'N/A'; // Use the original job role
                                                $romanNumerals = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x']; // Array of valid roman numerals

                                                // Convert the job role to lowercase first
                                                $lowercaseJobRole = strtolower($jobRole);

                                                // Loop through each Roman numeral and check if it exists in the job role
                                                foreach ($romanNumerals as $roman) {
                                                if (strpos($lowercaseJobRole, $roman) !== false) {
                                                // Preserve Roman numeral in uppercase
                                                $jobRole = preg_replace_callback("/\b($roman)\b/i", function($matches) {
                                                return strtoupper($matches[0]);
                                                }, $jobRole);
                                                break;
                                                }
                                                }

                                                // Apply ucfirst to the job role (only if the Roman numeral check is done)
                                                $jobRole = ucwords(strtolower($jobRole));
                                                @endphp
                                                <span class="normalText">{{ $jobRole }}</span>
                                                <span class="normalText">{{ ucfirst(strtolower($empToBeDelet->job_location ?? 'N/A' )) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="more-info d-flex align-items-start gap-2 border rounded">
                                            <div clas="col">
                                                <h6><strong>Salary Information for the months</strong></h6>
                                                <p class="normalTextValue">Apr 2023 to Jan 2025.</p>
                                            </div>
                                            <div class="col">
                                                <h6><strong>Leave Information</strong></h6>
                                                <div class="d-flex flex-column">
                                                    <span class="normalTextValue">Availed - 6 Transactions.</span>
                                                    <span class="normalTextValue">Granted - 278 Transactions.</span>
                                                    <span class="normalTextValue">Opening Balance - 5 Transactions.</span>
                                                    <span class="normalTextValue">Closing Balance - 5 Transactions.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-container d-flex justify-content-center p-0 mt-3 gap-2">
                                <button type="button" class="submit-btn" wire:click="deleteSelecetedEmployee">Delete</button>
                                <button type="button" class="cancel-btn"><a href="/">Cancel</a></button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @elseif($action == 'confirm')
                    <div>
                        <div class="row main-overview-help py-3">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Confirm Employee</span> page enables you to update an employee's status toward the end of the employee's probation period. The regular procedure is to confirm the employee. It is recommended that you use Employee Directory or Employee Overview to filter employees requiring confirmation and then proceed to this page.</span> </p>
                            </div>
                            <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                                <span wire:click="hideHelp">Hide Help</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column position-relative">
                            <label for="search" class="mb-2"><strong>Search An Employee</strong></label>
                            <div class="searchEmp position-relative">
                                <input
                                    type="text"
                                    class="form-control rounded-pill py-3 "
                                    wire:click="toggleSearchEmployee"
                                    placeholder="Search Employee"
                                    wire:model.live="searchTerm"
                                    wire:keyup="loadEmployeeList">

                                @if($searchTerm)
                                <button
                                    type="button"
                                    class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                    wire:click="closeSearchContainer"
                                    aria-label="Clear Search" style="width:30px;color:#ccc;">
                                    <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        @if($showEmployeeSearch)
                        <div class="selectEmp mb-3 bg-white d-flex flex-column position-absolute">
                            <div class="d-flex justify-content-end">
                                <span wire:click="closeSearchContainer"><i class="fa fa-times-circle text-muted" style="color:#ccc;"></i></span>
                            </div>
                            <div>
                                @if(count($employeeIds) > 0)
                                @foreach($employeeIds as $emp_id => $emp_data)
                                <div class="d-flex p-2 align-items-start gap-2 mb-2 border rounded bg-white" wire:click="getSelectedEmp('{{  $emp_id }}')">
                                    <!-- Display employee image if image exists -->
                                    @if ($emp_data['image'] !== null && $emp_data['image']!= "null" && $emp_data['image']!= "Null" && $emp_data['image'] != "")
                                    <img src="data:image/jpeg;base64,{{ ($emp_data['image']) }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                    @else
                                    <!-- Fallback image if no image is found -->
                                    <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                    @endif

                                    <!-- Display employee name -->
                                    <div class="d-flex flex-column">
                                        <span class="normalText">
                                            {{ ucwords(strtolower($emp_data['full_name'])) }}
                                        </span>
                                        <small class="normalText">
                                            {{ strtoupper($emp_id) }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="text-center subTextValue py-2">No data found for your search query.</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($openConfirmEmpDetails)
                        <div class="selecetdEmpDet">
                            <div class="row p-0 my-3">
                                <div class="col px-2">
                                    <div class="profileCon d-flex flex-row gap-2 align-items-start">
                                        @if ($empToBeConfirm->image !== null && $empToBeConfirm->image != "null" && $empToBeConfirm->image != "Null" && $empToBeConfirm->image != "")
                                        <img src="data:image/jpeg;base64,{{ $empToBeConfirm->image }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif

                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                {{ ucwords(strtolower($empToBeConfirm->first_name)) }} {{ ucwords(strtolower($empToBeConfirm->last_name)) }}
                                            </span>
                                            <small class="normalText">
                                                {{ strtoupper($empToBeConfirm->emp_id) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="confirm-emp">
                                <table class="custom-table">
                                    <tbody>
                                        <tr>
                                            <td class="custom-cell"><strong>Join date &nbsp;&nbsp;</strong>{{ \Carbon\Carbon::parse($empToBeConfirm->hire_date)->format('d M, Y') ?? 'N/A'  }}</td>
                                            <td class="custom-cell"><strong>Location &nbsp;&nbsp;</strong> {{ ucfirst(strtolower($empToBeConfirm->job_location ))}}</td>
                                        </tr>
                                        <tr>
                                            <td class="custom-cell"><strong>Probation Period &nbsp;&nbsp;</strong> {{ $empToBeConfirm->probation_Period ?? 'N/A' }}</td>
                                            <td class="custom-cell"><strong>Designation &nbsp;&nbsp;</strong> {{ $empToBeConfirm->job_role ?? 'N/A'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if($empToBeConfirm->confirmation_date == null)
                                <div class="form-group mt-3 confirmInput">
                                    <label for="formattedConfirmDate">Confirmation Date</label>
                                    <input type="date" class="form-control" wire:model="formattedConfirmDate" value="{{ $formattedConfirmDate ?? '' }}" id="formattedConfirmDate">
                                </div>
                                @else
                                <div class="mt-2 mb-2 confirmed-emp border rounded">
                                    <span class="normalText mb-2">Sorry! This employee is already confirmed on {{ \carbon\carbon::parse($empToBeConfirm->confirmation_date)->format('d M, Y') }}</span>
                                    <div>
                                        <button type="button" class="submit-btn">Generate Confirmation Letter</button>
                                        <button type="button" class="cancel-btn"><a href="/">OK</a></button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($empToBeConfirm->confirmation_date == null)
                        <div class="button-container d-flex justify-content-center p-0 mt-3 gap-2">
                            <button type="button" class="submit-btn" wire:click="confirmSelecetedEmployee">Confirm</button>
                            <button type="button" class="cancel-btn"><a href="/">Cancel</a></button>
                        </div>
                        @endif
                    </div>
                    @elseif($action == 'extend')
                    <div class="col-md-3">
                        <div class="blue-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                            <div class="row m-0">
                                <div class="col-6 p-0">
                                    <i class="fa-regular fa-user blue-bg-icon"></i>
                                </div>
                                <div class="col-6 p-0 text-end">
                                    <i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                            <p>Confirm Employee</p>
                        </div>
                    </div>
                    @elseif($action == 'regenerate')
                    <div class="col-md-3">
                        <div class="blue-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                            <div class="row m-0">
                                <div class="col-6 p-0">
                                    <i class="fa-regular fa-user blue-bg-icon"></i>
                                </div>
                                <div class="col-6 p-0 text-end">
                                    <i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                            <p>Regenerate Employee Password</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                <div>
                    activity review
                </div>

            </div>
        </div>

    </div>

</div>