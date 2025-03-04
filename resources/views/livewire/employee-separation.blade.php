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
                                        <div class="searchWidth rounded-pill position-relative">
                                            @if($seleceted_emp_id && $selecetdEmpDetails)
                                            <!-- Display Selected Employee Details -->
                                            <div class="d-flex align-items-center p-2 rounded-pill border bg-light">
                                                @if ($selecetdEmpDetails->image !== null && $selecetdEmpDetails->image != "null" && $selecetdEmpDetails->image != "Null" && $selecetdEmpDetails->image != "")
                                                <img src="data:image/jpeg;base64,{{ ($selecetdEmpDetails->image ) }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                                @else
                                                <!-- Fallback image if no image is found -->
                                                <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                                @endif
                                                <div class="ms-2 d-flex flex-column align-items-start">
                                                    <span class=" normalText">{{ ucwords(strtolower($selecetdEmpDetails->first_name)) }} {{ ucwords(strtolower($selecetdEmpDetails->last_name)) }}</span>
                                                    <span class="normalText"> {{ $selecetdEmpDetails->emp_id }}</span>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="position-absolute end-0 translate-middle-y border-0 bg-transparent"
                                                    wire:click="closeSearchContainer"
                                                    aria-label="Clear Selection"
                                                    style="width:30px;color:#ccc;left:90%;top:50%;">
                                                    <i class="fa fa-times-circle text-muted"></i>
                                                </button>
                                            </div>
                                            @else
                                            <!-- Display Search Input with User Icon -->
                                            <div class="d-flex align-items-center p-2 rounded-pill border bg-light">
                                                <i class="fa fa-user-circle text-muted" style="font-size: 24px;"></i>
                                                <input
                                                    type="text"
                                                    class="form-control border-0 bg-transparent ms-2"
                                                    wire:click="closeSearchContainer"
                                                    placeholder="Search Employee"
                                                    wire:model.live="searchTerm"
                                                    wire:keyup="loadEmployeeList"
                                                    id="search">
                                            </div>
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
                                    <h6 class="main-title mb-0">Resignation Status </h6>
                                </div>
                                <div class="form-group emp-type py-3 flex-column align-items-start">
                                    <label for="separation_mode">Separation Mode</label>
                                    <select name="separation_mode" wire:model="separation_mode" id="separation_mode" wire:change="toggleContent">
                                        <option value="">Select</option>
                                        <option value="awol">Awol</option>
                                        <option value="deported">Deported</option>
                                        <option value="resigned">Resigned</option>
                                        <option value="terminated">Terminated</option>
                                        <option value="other">Other</option>
                                        <option value="contract_expiry">Contract Expiry</option>
                                        <option value="absconding">Absconding</option>
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

                    <!-- //fourth row contnet -->
                    @if($showOtherDetails)
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-12">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title mb-0 d-flex align-items-center gap-3">
                                        Other Details
                                        <button wire:click="toggleEdit" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="is_left_org">Employee has left the organization</label>
                                        @if($showEdit)
                                        <!-- Radio buttons for Yes/No -->
                                        <div class="mt-1 d-flex align-items-center justify-content-start gap-4">
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="1" class="form-check-input m-0">
                                                Yes
                                            </label>
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="0" class="form-check-input m-0">
                                                No
                                            </label>
                                        </div>
                                        @else
                                        <!-- Display the selected value -->
                                        <span>{{ $separationDetails ? ($separationDetails->is_left_org == 1 ? 'Yes' : 'No') : 'N/A' }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-group emp-data-resign">
                                        <label for="other_date">Date</label>
                                        @if($showEdit)
                                        <input type="date" wire:model="other_date" id="other_date" class="form-control">
                                        @else
                                        <span>{{ optional($separationDetails->other_date)->format('d M, Y') ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="remarks">Remarks</label>
                                        @if($showEdit)
                                        <textarea wire:model="remarks" id="remarks" class="form-control" spellcheck="true"></textarea>
                                        @else
                                        <span>{{ $separationDetails->remarks ?? '-'}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="alt_email_id">Alternate email ID</label>
                                        @if($showEdit)
                                        <input type="text" wire:model="alt_email_id" id="alt_email_id" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->alt_email_id ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="alt_mbl_no">Alternate mobile No</label>
                                        @if($showEdit)
                                        <input type="text" wire:model="alt_mbl_no" id="alt_mbl_no" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->alt_mbl_no ?? '-' }}</span>
                                        @endif
                                    </div>

                                </div>
                                @if($showEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveOtherDetails">Save</button>
                                    <button class="cancel-btn" wire:click="resetDetails">Cancel</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @endif

                    <!-- //fifth row contnet expired-->
                    @if($showOtherDetailsExp)
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-12">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title d-flex align-items-center gap-3 mb-0">
                                        Other Details
                                        <button wire:click="toggleEdit" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="is_left_org">Employee has left the organization</label>
                                        @if($showEdit)
                                        <!-- Radio buttons for Yes/No -->
                                        <div class="mt-1 d-flex align-items-center justify-content-start gap-4">
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="1" class="form-check-input m-0">
                                                Yes
                                            </label>
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="0" class="form-check-input m-0">
                                                No
                                            </label>
                                        </div>
                                        @else
                                        <!-- Display the selected value -->
                                        <span>{{ $separationDetails->is_left_org == 1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="other_date">Date</label>
                                        @if($showEdit)
                                        <input type="date" wire:model="other_date" id="other_date" class="form-control">
                                        @else
                                        <span>{{ \Carbon\Carbon::parse($separationDetails->other_date)->format('d M, Y') ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="date_of_demise">Date of Demise</label>
                                        @if($showEdit)
                                        <input type="date" wire:model="date_of_demise" id="date_of_demise" class="form-control">
                                        @else
                                        <span>{{ \Carbon\Carbon::parse($separationDetails->date_of_demise)->format('d M, Y') ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="remarks">Remarks</label>
                                        @if($showEdit)
                                        <textarea wire:model="remarks" id="remarks" spellcheck="true" class="form-control"></textarea>
                                        @else
                                        <span>{{ $separationDetails->remarks ?? '-'}}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($showEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveExpireDetails">Save</button>
                                    <button class="cancel-btn" wire:click="resetDetails">Cancel</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- //sixth row contnet -->
                    @if($showOtherDetailsRetired)
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-12">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title mb-0 d-flex align-items-center gap-3">
                                        Other Details
                                        <button wire:click="toggleEdit" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="is_left_org">Employee has left the organization</label>
                                        @if($showEdit)
                                        <!-- Radio buttons for Yes/No -->
                                        <div class="mt-1 d-flex align-items-center justify-content-start gap-4">
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="1" class="form-check-input m-0">
                                                Yes
                                            </label>
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="0" class="form-check-input m-0">
                                                No
                                            </label>
                                        </div>
                                        @else
                                        <!-- Display the selected value -->
                                        <span>{{ $separationDetails->is_left_org ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="other_date">Other Date</label>
                                        @if($showEdit)
                                        <input type="date" wire:model="other_date" id="other_date" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->other_date ? \Carbon\Carbon::parse($separationDetails->other_date)->format('d M, Y') : '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="retired_date">Date of Retirement</label>
                                        @if($showEdit)
                                        <input type="date" wire:model="retired_date" id="retired_date" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->retired_date ?  \Carbon\Carbon::parse($separationDetails->retired_date)->format('d M, Y') : '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="remarks">Remarks</label>
                                        @if($showEdit)
                                        <textarea wire:model="remarks" id="remarks" spellcheck="true" class="form-control"></textarea>
                                        @else
                                        <span>{{ $separationDetails->remarks ?? '-'}}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($showEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveRetireDetails">Save</button>
                                    <button class="cancel-btn" wire:click="resetDetails">Cancel</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- //seventh row contnet -->
                    @if($showResignationDetails)
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-12">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title mb-0 d-flex align-items-center gap-3">Resignation Details
                                        <button wire:click="toggleEditResignedDet('resignation')" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-resign-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="resignation_submitted_on">
                                            Resignation Submitted On
                                        </label>
                                        @if($showResignationEdit)
                                        <input type="date" wire:model="resignation_submitted_on" id="resignation_submitted_on" class="form-control">
                                        @else
                                        <span>{{ $separationDetails && $separationDetails->resignation_submitted_on ? \Carbon\Carbon::parse($separationDetails->resignation_submitted_on)->format('d M, Y') : '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="emp_left">
                                            Reason For Leaving
                                        </label>
                                        @if($showResignationEdit)
                                        <input type="text" wire:model="reason" id="reason" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->reason ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="notice_required">
                                            Notice Required
                                        </label>
                                        @if($showResignationEdit)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="notice_required" wire:model="notice_required"> <span class="normalTextSmall">Notice Required</span>
                                        </div>
                                        @else
                                        <span>{{ $separationDetails->notice_required == 1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="notice_period">
                                            Notice Period
                                        </label>
                                        @if($showResignationEdit)
                                        <input type="number" wire:model="notice_period" id="notice_period" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->notice_period ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="short_fall_notice_period">
                                            Short Fall In Notice Period
                                        </label>
                                        @if($showResignationEdit)
                                        <input type="number" wire:model="short_fall_notice_period" id="short_fall_notice_period" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->short_fall_notice_period ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="tentative_date">
                                            Tentative Leaving Date
                                        </label>
                                        @if($showResignationEdit)
                                        <input type="date" wire:model="tentative_date" id="tentative_date" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->tentative_date ? \Carbon\Carbon::parse($separationDetails->tentative_date)->format('d M, Y')  : '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="exclude_final_settlement">
                                            Exclude from final settlement
                                        </label>
                                        @if($showResignationEdit)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="exclude_final_settlement" wire:model="exclude_final_settlement"><span class="normalTextSmallSmallSmall">Exclude from final settlement</span>
                                        </div>
                                        @else
                                        <span>{{ $separationDetails->exclude_final_settlement == 1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="remarks">
                                            Remarks
                                        </label>
                                        @if($showResignationEdit)
                                        <textarea wire:model="remarks" id="remarks" spellcheck="true" class="form-control"></textarea>
                                        @else
                                        <span>{{ $separationDetails->remarks ?? '-'}}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($showResignationEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveResignDetails">Save</button>
                                    <button class="cancel-btn" wire:click="toggleEditResignedDet('resignation')">Cancel</button>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-7">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title mb-0 d-flex align-items-center gap-3">Exit Interview
                                        <button wire:click="toggleEditResignedDet('exit_interview')" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="exit_interview_date">
                                            Interview date
                                        </label>
                                        @if($showExitInterviewEdit)
                                        <input type="date" wire:model="exit_interview_date" id="exit_interview_date" class="form-control">
                                        @else
                                        <span>
                                            {{ $separationDetails->exit_interview_date ? \Carbon\Carbon::parse($separationDetails->exit_interview_date)->format('d M, Y') : '-' }}
                                        </span>

                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="notes">
                                            Notes
                                        </label>
                                        @if($showExitInterviewEdit)
                                        <textarea wire:model="notes" id="notes" spellcheck="true" class="form-control"></textarea>
                                        @else
                                        <span>{{ $separationDetails->notes ?? '-'}}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($showExitInterviewEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveExitInterviewDetails">Save</button>
                                    <button class="cancel-btn" wire:click="toggleEditResignedDet('exit_interview')">Cancel</button>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5"></div>

                    </div>
                    <div class="row mx-0 mt-4 mb-3 bg-white rounded p-0">
                        <div class="col-md-12">
                            <div class="resign-status">
                                <div class="left-border">
                                    <h6 class="main-title mb-0 d-flex align-items-center gap-3">Exit Details
                                        <button wire:click="toggleEditResignedDet('exit_details')" class="btn btn-link edit-icon" title="Edit">
                                            <i class="fas fa-edit"></i> <!-- Using Font Awesome for the edit icon -->
                                        </button>
                                    </h6>
                                </div>
                                <div class="coulumn-resign-grid">
                                    <div class="form-group emp-data-resign">
                                        <label for="is_left_org">Employee has left the organization</label>
                                        @if($showExitDetailsEdit)
                                        <!-- Radio buttons for Yes/No -->
                                        <div class="mt-1 d-flex align-items-center justify-content-start gap-4">
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="1" class="form-check-input m-0">
                                                Yes
                                            </label>
                                            <label class="d-flex align-items-center gap-2">
                                                <input type="radio" wire:model="is_left_org" id="is_left_org" value="0" class="form-check-input m-0">
                                                No
                                            </label>
                                        </div>
                                        @else
                                        <!-- Display the selected value -->
                                        <span>{{ $separationDetails->is_left_org == 1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="leaving_date">
                                            Leaving date
                                        </label>
                                        @if($showExitDetailsEdit)
                                        <input type="date" wire:model="leaving_date" id="leaving_date" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->leaving_date ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="settled_date">
                                            Settled On
                                        </label>
                                        @if($showExitDetailsEdit)
                                        <input type="date" wire:model="settled_date" id="settled_date" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->settled_date ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="fit_to_rehire">
                                            Fit to be rehired
                                        </label>
                                        @if($showExitDetailsEdit)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="fit_to_rehire" wire:model="fit_to_rehire"> <span class="normalTextSmallSmallSmall"> Fit to be rehired</span>
                                        </div>
                                        @else
                                        <span>{{ $separationDetails->fit_to_rehire == 1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="is_served_notice">
                                            Notice Served
                                        </label>
                                        @if($showExitDetailsEdit)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_served_notice" wire:model="is_served_notice"><span class="normalTextSmallSmallSmall">Notice Served</span>
                                        </div>
                                        @else
                                        <span>{{ $separationDetails->is_served_notice ==1 ? 'Yes' : 'No' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="alt_email_id">Alternate email ID</label>
                                        @if($showExitDetailsEdit)
                                        <input type="text" wire:model="alt_email_id" id="alt_email_id" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->alt_email_id ?? '-' }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group emp-data-resign">
                                        <label for="alt_mbl_no">Alternate mobile No</label>
                                        @if($showExitDetailsEdit)
                                        <input type="text" wire:model="alt_mbl_no" id="alt_mbl_no" class="form-control">
                                        @else
                                        <span>{{ $separationDetails->alt_mbl_no ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($showExitDetailsEdit)
                                <div class="d-flex justify-content-end align-items-center gap-3">
                                    <button class="submit-btn" wire:click="saveExitDetails">Save</button>
                                    <button class="cancel-btn" wire:click="toggleEditResignedDet('exit_details')">Cancel</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- //confirmation modal -->
                    @if ($showWarningModal)
                    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header text-white">
                                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirm Logout</h6>
                                </div>
                                <div class="modal-body text-center">
                                    {!! $warningMessage !!}
                                </div>
                                <div class="d-flex gap-3 justify-content-center p-3">
                                    <button type="button" class="submit-btn mr-3" wire:click="handleConfirmation">Confirm</button>
                                    <button type="button" class="cancel-btn" wire:click="cancelWarningModal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
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