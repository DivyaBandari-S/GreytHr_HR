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
                                    <button type="button" wire:click="toggleAddReviewer" class="cancel-btn">Add</button>
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
                                            @if($addedLeaveReviewersData)
                                            @foreach ($addedLeaveReviewersData as $reviewer )
                                            <tr>
                                                <td>{{ ucwords(strtolower($reviewer->leave_scheme)) }}</td>
                                                <td>{{ $reviewer->leave_type }}</td>
                                                <td>{{ ucfirst(strtolower($reviewer->reviewer1_first_name )) }} {{ ucfirst(strtolower($reviewer->reviewer1_last_name ))}} <span class="smallText">
                                                        @if($reviewer->reviewer_1)
                                                        <span class="smallText">({{ $reviewer->reviewer_1 }})</span>
                                                        @else
                                                        <span>-</span>
                                                        @endif
                                                </td>
                                                <td>{{ ucfirst(strtolower($reviewer->reviewer2_first_name ))}} {{ ucfirst(strtolower($reviewer->reviewer2_last_name )) }}
                                                    @if($reviewer->reviewer_2)
                                                    <span class="smallText">({{ $reviewer->reviewer_2 }})</span>
                                                    @else
                                                    <span>-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="iconforAction" wire:click="openDeleteModal('{{ $reviewer->id }}')">
                                                            <i class=" ph-trash-bold"></i>
                                                        </div>
                                                        <div class="iconforAction" wire:click="openEditModal('{{ $reviewer->id }}')">
                                                            <i class="ph-note-pencil-bold"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5">no data found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $activeTab === 'nav-config' ? 'show active' : '' }}" id="nav-home"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- //add revoewer modal -->
            @if ($showAddReviewerModal)
            <div class="modal" id="reviewerModal" tabindex="-1" style="display: block;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h6 class="modal-title " id="reviewerModalLabel" style="align-items: center;">Add Leave Reviewer</h6>
                        </div>
                        <div class="modal-body text-center" style="    height: 300px; max-height: 300px; overflow-y: auto;">
                            <form wire:submit.prevent="addLeaveReviewer">
                                <div class="form-group d-flex flex-column align-items-start mb-2">
                                    <label for="leave_scheme">Leave Scheme</label>
                                    <select class="form-control" id="leave_scheme" name="leave_scheme" wire:model="leave_scheme">
                                        <option value="general">General Scheme</option>
                                    </select>
                                    @error('leave_scheme')
                                    <span class="mt-1 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group d-flex flex-column align-items-start mb-2">
                                    <label for="leave_type" wire:model="leave_type">Leave Type</label>
                                    <select class="form-control" id="leave_type" wire:model="leave_type" name="leave_type">
                                        <!-- Default "Select Leave Type" option -->
                                        <option value="">Select Leave Type</option>

                                        @foreach ($leaveTypes as $index => $leaves)
                                        <option value="{{ $leaves }}">{{ $leaves }}</option>
                                        @endforeach
                                    </select>
                                    <!-- Validation error message -->
                                    @error('leave_type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- //reviewer 1 -->
                                <div class="form-group d-flex flex-column align-items-start mb-2 position-relative">
                                    <label for="reviewer_1">Reviewer1</label>
                                    <div class="input-group ">
                                        <!-- Input field set to readonly -->
                                        <input type="text" class="form-control" id="reviewer_1_combined" name="reviewer_1_combined" value="{{ $reviewer_1_combined}}" wire:click="getEmployeeData" wire:model="reviewer_1_combined" readonly>
                                        <!-- Dropdown icon added to the input group -->
                                        <div class="input-group-append bg-white border" wire:click="toggleEmployeeContainer">
                                            <span class="input-group-text " style="border:none; background:none;">
                                                <i class=" ph-caret-down-fill"></i> <!-- Bootstrap icon for dropdown -->
                                            </span>
                                        </div>
                                    </div>
                                    @if($openEmployeeContainer1)
                                    <div class="search-container position-absolute" style="top:100%;z-index:1;">
                                        <!-- Search input field -->
                                        <input type="text" wire:input="getEmployeeData" wire:model="searchTerm" class="form-control" id="employeeSearch" placeholder="Search for employee..." />
                                        <!-- Display employee data if employeeIds is not null and has values -->
                                        @if(!is_null($employeeIds) && $employeeIds)
                                        <div>
                                            @foreach ($employeeIds as $empData)
                                            <div wire:click="getSelecetedReviewer('{{ $empData->emp_id }}')" class="empDiv mt-2 p-2 border rounded bg-white d-flex align-items-center gap-3">
                                                <div class="rounded-circle name d-flex bg-grey align-items-center justify-content-center">
                                                    <span>
                                                        {{ substr($empData->first_name, 0, 1) }}{{ substr($empData->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column align-items-start">
                                                    <div>
                                                        <span class="normalText">{{ ucwords(strtolower( $empData->first_name)) }} {{ ucwords(strtolower($empData->last_name)) }}</span>
                                                    </div>
                                                    <span class="smallText">{{ $empData->emp_id }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @elseif(!is_null($employeeIds) && $employeeIds)
                                        <p>No employees found matching the search criteria.</p>
                                        @else
                                        <p class="mt-2">Search for employees.</p>
                                        @endif
                                    </div>
                                    @endif

                                </div>
                                <!-- reviewer 2 -->
                                <div class="form-group d-flex flex-column align-items-start mb-2 position-relative">
                                    <label for="reviewer_2">Reviewer2</label>
                                    <div class="input-group ">
                                        <!-- Input field set to readonly -->
                                        <input type="text" class="form-control" id="reviewer_2" name="reviewer_2" value="{{ $reviewer_2_combined}}" wire:click="getEmployeeData" wire:model="reviewer_2_combined" readonly>

                                        <!-- Dropdown icon added to the input group -->
                                        <div class="input-group-append bg-white border" wire:click="toggleEmployeeContainer2">
                                            <span class="input-group-text " style="border:none; background:none;">
                                                <i class=" ph-caret-down-fill"></i> <!-- Bootstrap icon for dropdown -->
                                            </span>
                                        </div>
                                    </div>
                                    @if($openEmployeeContainer2)
                                    <div class="search-container position-absolute" style="top:100%;z-index:1;">
                                        <!-- Search input field -->
                                        <input type="text" wire:input="getEmployeeData" wire:model="searchTerm" class="form-control" id="employeeSearch" placeholder="Search for employee..." />
                                        <!-- Display employee data if employeeIds is not null and has values -->
                                        @if(!is_null($employeeIds) && $employeeIds)
                                        <div>
                                            @foreach ($employeeIds as $empData)
                                            <div wire:click="getSelecetedReviewer2('{{ $empData->emp_id }}')" class="empDiv mt-2 p-2 border rounded bg-white d-flex align-items-center gap-3">
                                                <div class="rounded-circle name d-flex bg-grey align-items-center justify-content-center">
                                                    <span>
                                                        {{ substr($empData->first_name, 0, 1) }}{{ substr($empData->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column align-items-start">
                                                    <div>
                                                        <span class="normalText">{{ ucwords(strtolower( $empData->first_name)) }} {{ ucwords(strtolower($empData->last_name)) }}</span>
                                                    </div>
                                                    <span class="smallText">{{ $empData->emp_id }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @elseif(!is_null($employeeIds) && $employeeIds)
                                        <p>No employees found matching the search criteria.</p>
                                        @else
                                        <p class="mt-2">Search for employees.</p>
                                        @endif
                                    </div>
                                    @endif

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-3 justify-content-center p-3">
                                <button type="button" class="submit-btn mr-3" wire:click="addLeaveReviewer">Add</button>
                                <button type="button" class="cancel-btn" wire:click="cancelModal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif
            <!-- //deldete reviewer -->
            @if ($showDeleteReviewerModal)
            <div class="modal" id="reviewerModal" tabindex="-1" style="display: block;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h6 class="modal-title " id="reviewerModalLabel" style="align-items: center;">Delete Leave Reviewer</h6>
                        </div>
                        <div class="modal-body text-center">
                            Are sure you want to delete this reviewer?
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-3 justify-content-center p-3">
                                <button type="button" class="submit-btn mr-3" wire:click="confirmDelete">Delete</button>
                                <button type="button" class="cancel-btn" wire:click="cancelDeleteModal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif

            <!-- //edit modal -->
            @if ($showEditReviewerModal)
            <div class="modal" id="editReviewerModal" tabindex="-1" style="display: block;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h6 class="modal-title" id="editReviewerModalLabel" style="align-items: center;">Edit Leave Reviewer</h6>
                        </div>
                        <div class="modal-body text-center" style="height: 300px; max-height: 300px; overflow-y: auto;">
                            <form wire:submit.prevent="editLeaveReviewer">
                                <div class="form-group d-flex flex-column align-items-start mb-2">
                                    <label for="leave_scheme">Leave Scheme</label>
                                    <select class="form-control" id="leave_scheme" name="leave_scheme" wire:model="leave_scheme">
                                        <option value="general">General Scheme</option>
                                    </select>
                                    @error('leave_scheme')
                                    <span class="mt-1 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group d-flex flex-column align-items-start mb-2">
                                    <label for="leave_type" wire:model="leave_type">Leave Type</label>
                                    <select class="form-control" id="leave_type" wire:model="leave_type" name="leave_type">
                                        <option value="">Select Leave Type</option>
                                        @foreach ($leaveTypes as $index => $leaves)
                                        <option value="{{ $leaves }}">{{ $leaves }}</option>
                                        @endforeach
                                    </select>
                                    @error('leave_type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Reviewer 1 -->
                                <div class="form-group d-flex flex-column align-items-start mb-2 position-relative">
                                    <label for="reviewer_1">Reviewer1</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="reviewer_1_combined" name="reviewer_1_combined" value="{{ $reviewer_1_combined }}" wire:click="getEmployeeData" wire:model="reviewer_1_combined" readonly>
                                        <div class="input-group-append bg-white border" wire:click="toggleEmployeeContainer">
                                            <span class="input-group-text" style="border:none; background:none;">
                                                <i class="ph-caret-down-fill"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if($openEmployeeContainer1)
                                    <div class="search-container position-absolute" style="top:100%; z-index:1;">
                                        <input type="text" wire:input="getEmployeeData" wire:model="searchTerm" class="form-control" id="employeeSearch" placeholder="Search for employee..." />
                                        @if(!is_null($employeeIds) && $employeeIds)
                                        <div>
                                            @foreach ($employeeIds as $empData)
                                            <div wire:click="getSelecetedReviewer('{{ $empData->emp_id }}')" class="empDiv mt-2 p-2 border rounded bg-white d-flex align-items-center gap-3">
                                                <div class="rounded-circle name d-flex bg-grey align-items-center justify-content-center">
                                                    <span>{{ substr($empData->first_name, 0, 1) }}{{ substr($empData->last_name, 0, 1) }}</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-start">
                                                    <div>
                                                        <span class="normalText">{{ ucwords(strtolower($empData->first_name)) }} {{ ucwords(strtolower($empData->last_name)) }}</span>
                                                    </div>
                                                    <span class="smallText">{{ $empData->emp_id }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="mt-2">Search for employees.</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <!-- Reviewer 2 -->
                                <div class="form-group d-flex flex-column align-items-start mb-2 position-relative">
                                    <label for="reviewer_2">Reviewer2</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="reviewer_2" name="reviewer_2" value="{{ $reviewer_2_combined }}" wire:click="getEmployeeData" wire:model="reviewer_2_combined" readonly>
                                        <div class="input-group-append bg-white border" wire:click="toggleEmployeeContainer2">
                                            <span class="input-group-text" style="border:none; background:none;">
                                                <i class="ph-caret-down-fill"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if($openEmployeeContainer2)
                                    <div class="search-container position-absolute" style="top:100%; z-index:1;">
                                        <input type="text" wire:input="getEmployeeData" wire:model="searchTerm" class="form-control" id="employeeSearch" placeholder="Search for employee..." />
                                        @if(!is_null($employeeIds) && $employeeIds)
                                        <div>
                                            @foreach ($employeeIds as $empData)
                                            <div wire:click="getSelecetedReviewer2('{{ $empData->emp_id }}')" class="empDiv mt-2 p-2 border rounded bg-white d-flex align-items-center gap-3">
                                                <div class="rounded-circle name d-flex bg-grey align-items-center justify-content-center">
                                                    <span>{{ substr($empData->first_name, 0, 1) }}{{ substr($empData->last_name, 0, 1) }}</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-start">
                                                    <div>
                                                        <span class="normalText">{{ ucwords(strtolower($empData->first_name)) }} {{ ucwords(strtolower($empData->last_name)) }}</span>
                                                    </div>
                                                    <span class="smallText">{{ $empData->emp_id }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="mt-2">Search for employees.</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="d-flex gap-3 justify-content-center p-3">
                                <button type="button" class="submit-btn mr-3" wire:click="saveLeaveReviewer">Update</button>
                                <button type="button" class="cancel-btn" wire:click="closeEditModal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif

            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                <div>
                    activity review
                </div>

            </div>
        </div>
    </div>