<div class="position-relative">
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

                        </div>
                        <div>
                            <div class="row d-flex flex-column position-relative">
                                <div class="col-md-4">
                                    <label for="search" class="mb-2"><strong class="main-title">Search An Employee</strong></label>
                                    <div class="col position-relative">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill "
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model.live="searchTerm"
                                            wire:keyup="loadEmployeeList">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeSearchContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;left:85%;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($showEmployeeSearch)
                            <div class="row">
                                <div class="col-md-4 position-absolute">
                                    <div class="selectEmp mb-3 bg-white d-flex flex-column ">
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
                                </div>
                            </div>
                            @endif
                            @if($openSelecetdEmpDetails)
                            <div class="selecetdEmpDet">
                                <div class="row p-0 my-3">
                                    <div class="col px-2">
                                        <div class="profileCon col-md-3 d-flex flex-row gap-2 align-items-start">
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
                                    <div class="col-md-5">
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
                                    <div class="col-md-7">
                                        <div class="more-info d-flex align-items-start gap-2 border rounded">
                                            <div clas="col">
                                                <p><strong class="main-title">Salary Information for the months</strong></p>
                                                <p class="normalTextValue">Apr 2023 to Jan 2025.</p>
                                            </div>
                                            <div class="col">
                                                <p><strong class="main-title">Leave Information</strong></p>
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

                        </div>
                        <div class="d-flex flex-column position-relative">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="search" class="mb-2"><strong class="main-title">Search An Employee</strong></label>
                                    <div class="col position-relative">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill "
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model.live="searchTerm"
                                            wire:keyup="loadEmployeeList">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeSearchContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;left:85%;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($showEmployeeSearch)
                        <div class="row">
                            <div class="col-md-4 position-absolute">
                                <div class="selectEmp mb-3 bg-white d-flex flex-column ">
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
                            </div>
                        </div>
                        @endif
                        @if($openConfirmEmpDetails)
                        <div class="selecetdEmpDet">
                            <div class="row p-0 my-3">
                                <div class="col px-2">
                                    <div class="profileCon col-md-3 d-flex flex-row gap-2 align-items-start">
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
                        @endif
                    </div>
                    @elseif($action == 'extend')
                    <div>
                        <div class="row main-overview-help py-3">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">When an employee joins the organization, normally the probation period will be agreed upon. However as part of performance review, sometimes the period may be extended.
                                    <br>
                                    Here you can extend the probation period for an employee whose confirmation is due.
                                </p>
                            </div>

                        </div>
                        <div class="d-flex flex-column position-relative">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="search" class="mb-2"><strong class="main-title">Search An Employee</strong></label>
                                    <div class="col position-relative">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill  "
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model.live="searchTerm"
                                            wire:keyup="loadEmployeeList">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeSearchContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;left:85%;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($showEmployeeSearch)
                        <div class="row">
                            <div class="col-md-4 position-absolute">
                                <div class="selectEmp mb-3 bg-white d-flex flex-column ">
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
                            </div>
                        </div>
                        @endif

                        @if($openExtendProbDetails)
                        <div class="selecetdEmpDet">
                            <div class="row p-0 my-3">
                                <div class="col px-2">
                                    <div class="profileCon col-md-3 d-flex flex-row gap-2 align-items-start">
                                        @if ($probToBeExtend->image !== null && $probToBeExtend->image != "null" && $probToBeExtend->image != "Null" && $probToBeExtend->image != "")
                                        <img src="data:image/jpeg;base64,{{ $probToBeExtend->image }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif

                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                {{ ucwords(strtolower($probToBeExtend->first_name)) }} {{ ucwords(strtolower($probToBeExtend->last_name)) }}
                                            </span>
                                            <small class="normalText">
                                                {{ strtoupper($probToBeExtend->emp_id) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="confirm-emp">
                                <table class="custom-table">
                                    <tbody>
                                        <tr>
                                            <td class="custom-cell"><strong>Join date &nbsp;&nbsp;</strong>{{ \Carbon\Carbon::parse($probToBeExtend->hire_date)->format('d M, Y') ?? 'N/A'  }}</td>
                                            <td class="custom-cell"><strong>Location &nbsp;&nbsp;</strong> {{ ucfirst(strtolower($probToBeExtend->job_location ))}}</td>
                                        </tr>
                                        <tr>
                                            <td class="custom-cell"><strong>Probation Period &nbsp;&nbsp;</strong> {{ $probToBeExtend->probation_Period ?? 'N/A' }}</td>
                                            <td class="custom-cell"><strong>Designation &nbsp;&nbsp;</strong> {{ $probToBeExtend->job_role ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="custom-cell"><strong>Confirm Date &nbsp;&nbsp;</strong> {{ $actualConfirmDate ?? 'N/A' }}</td>
                                            <td class="custom-cell"><strong> </strong> </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if($probToBeExtend->confirmation_date == null)
                                <div class="form-group mt-3 confirmInput">
                                    <label for="extend_probation">Extend Probation Period by</label>
                                    <select class="form-control" wire:model="extend_probation" wire:change="getExtendedProbationDays" id="extend_probation">
                                        <option value="">Select </option>
                                        <option value="1">1 months</option>
                                        <option value="2">2 months</option>
                                        <option value="3">3 months</option>
                                        <option value="4">4 months</option>
                                        <option value="5">5 months</option>
                                        <option value="6">6 months</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3 confirmInput">
                                    <label for="revisedConfirmationDate">Revise Confirmation Date</label>
                                    <span class="normalText">
                                        {{ $revisedConfirmationDate ?? $actualConfirmDate }}
                                    </span>

                                </div>
                                <div class="form-group mt-3 confirmInput">
                                    <label for="reason_for_extend">Reason For Extension</label>
                                    <textarea name="reason_for_extend" id="reason_for_extend" rows="4" style="outline:none;border:1px solid #ccc;"></textarea>
                                </div>
                                @else
                                <div class="mt-2 mb-2 confirmed-emp border rounded">
                                    <span class="normalText mb-2">Sorry! This employee is already confirmed on {{ \carbon\carbon::parse($probToBeExtend->confirmation_date)->format('d M, Y') }}</span>
                                    <br>
                                    <span class="normalText mb-2">
                                        You cannot update the probation period for confirmed employees
                                    </span>
                                    <div>
                                        <button type="button" class="cancel-btn"><a href="/">OK</a></button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($probToBeExtend && is_null($probToBeExtend->confirmation_date))
                        <div class="button-container d-flex justify-content-center p-0 mt-3 gap-2">
                            <button type="button" class="submit-btn" wire:click="extendProbation">Extend Probation</button>
                            <button type="button" class="cancel-btn"><a href="/">Cancel</a></button>
                        </div>
                        @endif
                    </div>
                    @elseif($action == 'disable')
                    <div>
                        <div class="row main-overview-help py-3">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">This <span class="msgHeighlighter">Disable Employee Access</span> page enables you to disable an employee's access to the Employee Portal when an employee resigns or is terminated from service.
                                </p>
                            </div>

                        </div>
                        <div class="d-flex flex-column position-relative">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="search" class="mb-2"><strong class="main-title">Search An Employee</strong></label>
                                    <div class="col position-relative">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill "
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model.live="searchTerm"
                                            wire:keyup="loadEmployeeList">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeSearchContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;left:85%;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($showEmployeeSearch)
                        <div class="row">
                            <div class="col-md-4 position-absolute">
                                <div class="selectEmp mb-3 bg-white d-flex flex-column ">
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
                            </div>
                        </div>
                        @endif

                        @if($openDisablePortalaAccess)
                        <div class="selecetdEmpDet">
                            <div class="row p-0 my-3">
                                <div class="col px-2">
                                    <div class="profileCon col-md-3 d-flex flex-row gap-2 align-items-start">
                                        @if ($disableEmployeeDetails->image !== null && $disableEmployeeDetails->image != "null" && $disableEmployeeDetails->image != "Null" && $disableEmployeeDetails->image != "")
                                        <img src="data:image/jpeg;base64,{{ $disableEmployeeDetails->image }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif

                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                {{ ucwords(strtolower($disableEmployeeDetails->first_name)) }} {{ ucwords(strtolower($disableEmployeeDetails->last_name)) }}
                                            </span>
                                            <small class="normalText">
                                                {{ strtoupper($disableEmployeeDetails->emp_id) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="confirm-emp">
                                <table class="custom-table">
                                    <tbody>
                                        <tr>
                                            <td class="custom-cell"><strong>Join date &nbsp;&nbsp;</strong>{{ \Carbon\Carbon::parse($disableEmployeeDetails->hire_date)->format('d M, Y') ?? 'N/A'  }}</td>
                                            <td class="custom-cell"><strong>Last Login Time &nbsp;&nbsp;</strong>
                                                @if($lastLoginTime && $lastLoginTime->created_at)
                                                {{ $lastLoginTime->created_at ? \Carbon\Carbon::parse($lastLoginTime->created_at)->format('d M, Y h:i:s A') : 'N/A' }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @if($searchTerm)
                        <div class="button-container d-flex justify-content-center p-0 mt-3 gap-2">
                            <button type="button" class="submit-btn" wire:click="disablePortalAccess">Disable</button>
                            <button type="button" class="cancel-btn"><a href="/">Cancel</a></button>
                        </div>
                        @endif
                    </div>
                    @elseif($action == 'enable')
                    <div>
                        <div class="row main-overview-help py-3">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">This <span class="msgHeighlighter">Enable Employee Access</span> page enables you to enable an employee's access to the Employee Portal when an employee resigns or is terminated from service.
                                </p>
                            </div>

                        </div>
                        <div class="d-flex flex-column position-relative">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="search" class="mb-2"><strong class="main-title">Search An Employee</strong></label>
                                    <div class="col position-relative">
                                        <input
                                            type="text"
                                            class="form-control rounded-pill"
                                            wire:click="toggleSearchEmployee"
                                            placeholder="Search Employee"
                                            wire:model.live="searchTerm"
                                            wire:keyup="loadEmployeeList">

                                        @if($searchTerm)
                                        <button
                                            type="button"
                                            class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="closeSearchContainer"
                                            aria-label="Clear Search" style="width:30px;color:#ccc;left:85%;">
                                            <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($showEmployeeSearch)
                        <div class="row">
                            <div class="col-md-4 position-absolute">
                                <div class="selectEmp mb-3 bg-white d-flex flex-column ">
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
                            </div>
                        </div>
                        @endif

                        @if($openEnablePortalaAccess)
                        <div class="selecetdEmpDet">
                            <div class="row p-0 my-3">
                                <div class="col px-2">
                                    <div class="profileCon col-md-3 d-flex flex-row gap-2 align-items-start">
                                        @if ($enableEmpDetails->image !== null && $enableEmpDetails->image != "null" && $enableEmpDetails->image != "Null" && $enableEmpDetails->image != "")
                                        <img src="data:image/jpeg;base64,{{ $enableEmpDetails->image }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif

                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                {{ ucwords(strtolower($enableEmpDetails->first_name)) }} {{ ucwords(strtolower($enableEmpDetails->last_name)) }}
                                            </span>
                                            <small class="normalText">
                                                {{ strtoupper($enableEmpDetails->emp_id) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="confirm-emp">
                                <table class="custom-table">
                                    <tbody>
                                        <tr>
                                            <td class="custom-cell"><strong>Join date &nbsp;&nbsp;</strong>{{ \Carbon\Carbon::parse($enableEmpDetails->hire_date)->format('d M, Y') ?? 'N/A'  }}</td>
                                            <td class="custom-cell"><strong>Last Login Time &nbsp;&nbsp;</strong>
                                                @if($lastLoginTime && $lastLoginTime->created_at)
                                                {{ $lastLoginTime->created_at ? \Carbon\Carbon::parse($lastLoginTime->created_at)->format('d M, Y h:i:s A') : 'N/A' }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @if($searchTerm)
                        <div class="button-container d-flex justify-content-center p-0 mt-3 gap-2">
                            <button type="button" class="submit-btn" wire:click="enablePortalAccess">Enable</button>
                            <button type="button" class="cancel-btn"><a href="/">Cancel</a></button>
                        </div>
                        @endif
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