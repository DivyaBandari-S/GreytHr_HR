<div class="container mt-4">
    <ul class="nav leave-grant-nav-tabs d-flex" id="myTab" role="tablist"
        style="gap: 30px;padding: 18px 18px 0px 23px;">

        <li class="leave-grant-nav-item" role="presentation">

            <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab"
                data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane"
                aria-selected="true">Main</button>

        </li>

        <li class="leave-grant-nav-item" role="presentation">

            <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab"
                data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane"
                aria-selected="false">Activity</button>

        </li>

    </ul>
    <div class="tab-content" id="myTabContent">

        <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab"
            tabindex="0">

            <div>
                @if ($showHelp == false)
                    <div class="row main-overview-help">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text">The Generate Letter wizard guides you through the process of
                                generating a letter for an employee. You can also send it to one or multiple employees
                                in one effort. Note: You can download a copy of the letter in the Generate Letter:
                                Summary Page.</p>
                            <p class="main-overview-text">Learn more about the process by watching the <span
                                    class="main-overview-highlited-text">
                                    video</span> here. To view frequently asked questions <span
                                    class="main-overview-highlited-text"> click</span>
                                here.</p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                @else
                    <div class="row main-overview-help">
                        <div class="col-11 d-flex flex-column">
                            <p class="main-overview-text">The Generate Letter wizard guides you through the process of
                                generating a letter for an employee. You can also send it to one or multiple employees
                                in one effort. Note: You can download a copy of the letter in the Generate Letter:
                                Summary Page.</p>

                        </div>
                        <div class="hide-main-overview-help col-1">
                            <span wire:click="showhelp">Show Help</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Filters and Buttons Row -->
            <div class="d-flex justify-content-between align-items-end flex-wrap mt-3">
                <div class="d-flex flex-wrap">
                    <div class="me-2">
                        <label>Letter Template:</label>
                        <select class="form-select">
                            <option value="all">All</option>
                            <option value="confirmation">Confirmation Letter</option>
                            <option value="experience">Experience Letter</option>
                        </select>
                    </div>

                    <div class="me-2">
                        <label>Employee:</label>
                        <select class="form-select">
                            <option value="all">All</option>
                            <option value="aadish">Aadesh Hiralal</option>
                        </select>
                    </div>

                    <div class="me-2">
                        <label>Publish Status:</label>
                        <select class="form-select">
                            <option value="all">All</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="me-2">
                        <label>Date:</label>
                        <input type="text" class="form-control" readonly>
                    </div>

                    <div class="me-2">
                        <label>Status:</label>
                        <select class="form-select">
                            <option value="all">All</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex">
                    <button class="btn btn-outline-primary me-2">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-primary" wire:click="showPrepareLetter">Prepare A Letter</button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Letter Template</th>
                            <th>Serial No</th>
                            <th>Employee</th>
                            <th>Prepared On</th>
                            <th>Prepared By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Experience Letter</td>
                            <td>12345</td>
                            <td>Aadesh Hiralal</td>
                            <td>01 Jan 2024</td>
                            <td>HR Manager</td>
                            <td>Approved</td>
                        </tr>
                        <tr>
                            <td>Confirmation Letter</td>
                            <td>67890</td>
                            <td>John Doe</td>
                            <td>15 Feb 2024</td>
                            <td>HR Executive</td>
                            <td>Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

            <div>
                activity review
            </div>

        </div>
    </div>
</div>
