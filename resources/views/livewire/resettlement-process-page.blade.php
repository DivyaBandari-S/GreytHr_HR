<div>
    <div class="container-fluid px-1  rounded">
        <ul class="nav leave-grant-nav-tabs d-flex gap-3 py-1" id="myTab" role="tablist">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                <div class="px-3 py-2">
                    <div class="row main-overview-help">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text mb-0">Use the <span class="msgHeighlighter">Resettlement</span> page to resettle the final settlement of an employee. The resettlement can be any month. For example, performing resettlement in September for settlements done in August. </p>
                            <p class="main-overview-text mb-1">
                                You can view the Final Settlement Payslip from this page. The actual resettlement or the correction is done from the <span class="msgHeighlighter">Salary page.</span>
                            </p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                    <div class="row m-0 p-0">
                        <div class="col-md-6">
                            <div class="d-flex flex-row position-relative align-items-center gap-3">
                                <label for="search" class="mb-2">Settled Employee(s)</label>
                                <div class="searchWidth rounded-pill position-relative">
                                    @if($selectedEmpId && $selecetdEmpDetails)
                                    <!-- Display Selected Employee Details -->
                                    <div class="d-flex align-items-center p-2 rounded-pill border bg-light">
                                        @if ($selecetdEmpDetails->image !== null && $selecetdEmpDetails->image != "null" && $selecetdEmpDetails->image != "Null" && $selecetdEmpDetails->image != "")
                                        <img src="data:image/jpeg;base64,{{ ($selecetdEmpDetails->image ) }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @else
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        @endif
                                        <div class="ms-2 d-flex flex-column align-items-start">
                                            <span class=" normalText">{{ ucwords(strtolower($selecetdEmpDetails->first_name)) ?? '-' }} {{ ucwords(strtolower($selecetdEmpDetails->last_name)) ?? '-'}}</span>
                                            <span class="normalText"> {{ $selecetdEmpDetails->emp_id ?? '-'}}</span>
                                        </div>
                                        <button
                                            type="button"
                                            class="position-absolute end-0 translate-middle-y border-0 bg-transparent"
                                            wire:click="toggleSettledEmps"
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
                                            wire:click="toggleSettledEmps"
                                            placeholder="Search Employee"
                                            id="search">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="settled-emp-list">
                            @if($showSettledEmployees)
                            <div class="selectEmp mb-3 bg-white d-flex flex-column position-absolute">
                                <div class="d-flex justify-content-end">
                                    <span wire:click="toggleSettledEmps"><i class="fa fa-times-circle text-muted" style="color:#ccc;cursor:pointer;"></i></span>
                                </div>
                                <div>
                                    <div class="d-flex p-2 align-items-start gap-2 mb-2 border rounded bg-white" wire:click="getSelectedEmp('XSS-0480')">
                                        <!-- Fallback image if no image is found -->
                                        <img src="{{ asset('images/user.jpg') }}" style="width: 35px; height: 35px; object-fit: cover;" class="rounded-circle">
                                        <!-- Display employee name -->
                                        <div class="d-flex flex-column">
                                            <span class="normalText">
                                                Divya Bandari
                                            </span>
                                            <small class="normalText">
                                                XSS-0480
                                            </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row m-0 p-0">
                        <div class="col-md-10">
                            <div class="settlement-info rounded">
                                <h6>Settlement Info</h6>
                                <div class="adjust-content">
                                    <div class="resettle-grid-type">
                                        <div class="form-group settled-emp-info">
                                            <label for="leaving_date">Leaving Date</label>
                                            <input type="date" class="form-control" id="leaving_date" readonly>
                                        </div>
                                        <div class="form-group settled-emp-info">
                                            <label for="submission_date">Submission Date</label>
                                            <input type="date" class="form-control" id="submission_date" readonly>
                                        </div>
                                        <div class="form-group settled-emp-info">
                                            <label for="settlement_date">Settlement Date</label>
                                            <input type="date" class="form-control" id="settlement_date" readonly>
                                        </div>
                                        <div class="form-group settled-emp-info">
                                            <label for="last_processed_date">Last Processed Date</label>
                                            <input type="date" class="form-control" id="last_processed_date" readonly>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="cancel-btn">Preview Settlement Payslip</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="row m-0 p-0">
                        <div class="resettlement-contnet">
                            <h6>Resettlement Info</h6>
                            <div class="adjust-resettle-info">
                                <div class="form-group resettled-emp-info">
                                    <label for="resettlement_date">Settlement Date</label>
                                    <input type="date" class="form-control" id="resettlement_date">
                                </div>
                                <div class="form-group resettled-emp-info">
                                    <label for="remarks"> Remarks</label>
                                    <textarea class="form-control" id="remarks"> </textarea>
                                </div>
                            </div>

                            <div class="row m-0 p-0">
                                <div class="col-md-5">
                                    <div class="note-info">
                                        <p class="mb-0">Note : You need to manually enter/override employee salary under salary menu and process the payroll to complete the resettlement</p>
                                    </div>
                                </div>
                                <div class="col-md-7"></div>
                            </div>
                            <div class="btn-container">
                                <button type="button" class="submit-btn">Save</button>
                                <button type="button" class="cancel-btn" ><a href="/hr/user/payroll/resttlement" style="color:#306cc6;">Cancel</a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">
                <div class="px-3 py-2">
                    activity review
                </div>

            </div>
        </div>