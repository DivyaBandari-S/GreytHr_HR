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
                    <div class="row m-0 px-4">
                        <div class="dropdown">
                            <label for="leaveScheme">Leave Scheme:</label>
                            <select id="leaveScheme">
                                <option value="all">All</option>
                                <option value="contract">Contract Scheme</option>
                                <option value="general">General Scheme</option>
                                <option value="probation">Probation scheme</option>
                                <option value="trainee">Trainee scheme</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-0 pt-3 px-4">
                        <nav class="p-0">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button
                                    class="nav-link {{ $activeTab === 'nav-reviewers' ? 'active' : '' }}"
                                    wire:click="setActiveTab('nav-reviewers')"
                                    type="button"
                                    role="tab">
                                    Reviewers
                                </button>
                                <button
                                    class="nav-link {{ $activeTab === 'nav-config' ? 'active' : '' }}"
                                    wire:click="setActiveTab('nav-config')"
                                    type="button"
                                    role="tab">
                                    Config
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content bg-white" id="nav-tabContent">
                            <div class="tab-pane fade {{ $activeTab === 'nav-reviewers' ? 'show active' : '' }}" id="nav-home">
                                <div class="py-2 d-flex justify-content-end">
                                    <button type="button" class="cancel-btn">Add</button>
                                </div>
                                <div class="table-responsive">
                                    <table id="typeReviewer">
                                        <thead>
                                            <tr>
                                                <th>Leave Scheme</th>
                                                <th>Leave Type</th>
                                                <th>Reviewer1</th>
                                                <th>Reviewer2</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Contract Scheme</td>
                                                <td>Loss Of Pay</td>
                                                <td>Aarav Gandhi(T0010)</td>
                                                <td>P Hari Hara Rao(T0022)</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <i class=" ph-trash-bold"></i>
                                                        <i class="ph-note-pencil-bold"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>All</td>
                                                <td>Work From Home</td>
                                                <td>A Kalyan Kumar(T0023)</td>
                                                <td>Aarav Gandhi(T0010)</td>
                                                <td>edit/delete</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $activeTab === 'nav-config' ? 'show active' : '' }}" id="nav-home"></div>
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