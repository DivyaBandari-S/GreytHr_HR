<div>
    <div class="container-fluid px-1  rounded">
        <ul class="nav leave-grant-nav-tabs bg-white d-flex gap-3 py-1" id="myTab" role="tablist">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                <div>
                    <div class="px-3 py-2">
                        <div class="row main-overview-help">
                            <div class="col-md-11 col-10 d-flex flex-column">
                                <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Resettlement</span> page displays the list of employees for whom Full & Final Settlement is done, and you can select those who need resettlement. During Full & Final Settlement, there are chances of errors being committed. These errors may be brought to your notice by the concerned ex-employee, or you may discover them yourself. In such cases, the Full & Final Settlement amount has to be recalculated. </p>
                            </div>
                            <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                                <span wire:click="hideHelp">Hide Help</span>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex align-items-center m-0 px-3">
                        <div class="col-md-9 py-2 px-0">
                            <div class="filters">
                                <div class="form-group dropdown">
                                    <select class="form-control">
                                        <option value="All">Filter : Mar 2025</option>
                                        <option value="Monthly">Apr 2025</option>
                                        <option value="Yearly">May 2025</option>
                                    </select>
                                </div>
                                <div class="form-group dropdown">
                                    <select class="form-control">
                                        <option value="All">Employee Type : All</option>
                                        <option value="full-time">Full Time</option>
                                        <option value="part-time">Part Time</option>
                                        <option value="contract"> Contract</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Search" class="form-control py-2">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 py-2 px-0">
                            <div class="d-flex justify-content-end">
                                <button class="submit-btn"> <a href="/hr/user/payroll/resttlement/process" style="color: white;">Resettle Employee</a></button>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 px-3">
                        <div class="table-responsive p-0 rounded border ">
                            <table class="table arrears-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee No</th>
                                        <th>Employee Name</th>
                                        <th>Payrout Month</th>
                                        <th>Leaving Date</th>
                                        <th>Settlement Date</th>
                                        <th>Resettlement Date</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>1234</td>
                                        <td>abc</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>23 Jan, 2025</td>
                                        <td>-</td>
                                        <td>
                                           -
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>1234</td>
                                        <td>abc</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
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