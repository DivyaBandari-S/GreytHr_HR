<div >
    <div class="container-fluid px-1  rounded">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                <div>
                    <div class="row m-0 px-4 ">
                        <div class="main-overview-help d-flex px-3">
                            <div class="col-md-11 col-10 d-flex flex-column  ">
                                <p class="main-overview-text mb-1">The Leave Type Reviewer page lists the changes done to the default Leave Reviewer configuration. By default, an employee's Reporting Manager is the person who can review his Leave. In case you intend to override this configuration, you can do so by using the Leave Reviewer configuration.
                                </p>
                                <p class="main-overview-text mb-0">The Config tab displays the policy of Leave Review. Once this has been set up, the Reviewers tab indicates the specific people who can do the Review.
                                </p>
                            </div>
                            <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                                <span wire:click="hideHelp">Hide Help</span>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0  d-flex align-items-center px-4">
                        <div class="col-md-8 p-0">
                            <div class="col dropdown p-0 mt-0">
                                <select id="leaveScheme">
                                    <option value="all">All</option>
                                    <option value="contract">Contract Scheme</option>
                                    <option value="general">General Scheme</option>
                                    <option value="probation">Probation scheme</option>
                                    <option value="trainee">Trainee scheme</option>
                                </select>
                            </div>
                            <div class="col dropdown p-0 mt-0">
                                <select id="leaveScheme">
                                    <option value="all">All</option>
                                    <option value="contract">Contract Scheme</option>
                                    <option value="general">General Scheme</option>
                                    <option value="probation">Probation scheme</option>
                                    <option value="trainee">Trainee scheme</option>
                                </select>
                            </div>
                            <div class="col dropdown p-0 mt-0">
                                <select id="leaveScheme">
                                    <option value="all">All</option>
                                    <option value="contract">Contract Scheme</option>
                                    <option value="general">General Scheme</option>
                                    <option value="probation">Probation scheme</option>
                                    <option value="trainee">Trainee scheme</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 p-0">
                            <div class="col d-flex justify-content-end align-items-end">
                                <button type="button" class="cancel-btn">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 pt-3 px-4">
                        <div class="table-responsive p-0">
                            <table id="typeReviewer">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"></th>
                                        <th>#</th>
                                        <th>Employee Name</th>
                                        <th>EMployee Number</th>
                                        <th>Leaving Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>1</td>
                                        <td>Aarav Gandhi</td>
                                        <td>T0022</td>
                                        <td>22 Mar, 2024</td>
                                        <td>Pending</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>2</td>
                                        <td>A Kalyan Kumar</td>
                                        <td>T0010</td>
                                        <td>22 Mar, 2024</td>
                                        <td>Pending</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                <div>
                    activity review
                </div>

            </div>
        </div>
    </div>