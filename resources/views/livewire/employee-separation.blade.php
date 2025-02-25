<div class="position-relative">
    <div class="container-fluid px-1 rounded">
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
                <div class="px-4 py-2">
                    <div class="row main-overview-help py-3">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text mb-1">View and update the <span class="msgHeighlighter">Separation Details</span> of an employee on the <span class="msgHeighlighter">Separation.</span> Please note that depending on the <span class="msgHeighlighter">Separation Mode</span>, the captured information varies. If an employee resigns, then you need first to update the Resignation Details. Subsequently, on the day the employee leaves, <span class="msgHeighlighter">Exit </span> Details can be updated on this page.</p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                    <!-- //second row contnet -->
                    <div class="row d-flex align-items-center mx-0 mb-4 p-0 bg-white rounded">
                        <div class="col-md-7">
                            <div class="emp-search-div">
                                <h6> <strong class="main-title">Start searching to see specific employee details here</strong> </h6>
                                <div class="emp-filter-data">
                                    <div class="form-group mb-3 emp-type">
                                        <label for="employee_type">Employee Type</label>
                                        <select class="form-control" name="employee_type" id="employee_type" wire:model="filterEmp" wire:change="empfilteredData">
                                            <option value="">All</option>
                                            <option value="current_emp">Current Employees</option>
                                            <option value="resign_emp">Resign Employees</option>
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column position-relative">
                                        <label for="search" class="mb-2">Search An Employee</label>
                                        <div class="rounded-pill">
                                            <input
                                                type="text"
                                                class="form-control rounded-pill"
                                                wire:click="closeSearchContainer"
                                                placeholder="Search Employee"
                                                wire:model.live="searchTerm"
                                                wire:keyup="loadEmployeeList">

                                            @if($searchTerm)
                                            <button
                                                type="button"
                                                class="position-absolute end-0 translate-middle-y border-0 bg-transparent"
                                                wire:click="closeSearchContainer"
                                                aria-label="Clear Search" style="width:30px;color:#ccc;left:42%;top:72%;">
                                                <i class="fa fa-times-circle text-muted" style="color:#ccc;"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($showEmployeeSearch)
                                    <div class="selectEmp mb-3 bg-white d-flex flex-column position-absolute">
                                        <div class="d-flex justify-content-end">
                                            <span wire:click="closeSearchContainer"><i class="fa fa-times-circle text-muted" style="color:#ccc;cursor:pointer;"></i></span>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="emp-search-div d-flex align-items-center justify-content-end">
                                <div class="side-image ">
                                    <img src="{{ asset('/images/hr_image.png') }}" alt="" width="220" height="180">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //third row contnet -->
                     @if($showResignSection)
                     <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-7">
                            <div class="resign-status">
                                <div class="right-border">
                                    <h6 class="main-title mb-0">Resignation Status  </h6>
                                </div>
                                <div class="form-group emp-type py-3 flex-column align-items-start">
                                    <label for="separation_mode">Separation Mode</label>
                                    <select name="separation_mode" id="separation_mode">
                                        <option value="resigned">Resigned</option>
                                        <option value="terminated">Terminated</option>
                                        <option value="other">other</option>
                                        <option value="expired">Expired</option>
                                        <option value="sick">Sick</option>
                                        <option value="retired">Retired</option>
                                        <option value="transferred">Transferred</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
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