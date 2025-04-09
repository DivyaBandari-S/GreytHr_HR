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
                        <select class="form-select" wire:model="selectedTemplate"
                            wire:change="onChange('selectedTemplate')">
                            <option value="all">All</option>
                            <option value="Appointment Order">Appointment Order</option>
                            <option value="Confirmation Letter">Confirmation Letter</option>
                        </select>
                    </div>



                    <div class="me-2">
                        <label>Publish Status:</label>
                        <select class="form-select" wire:model="selectedPublishStatus"
                            wire:change="onChange('selectedPublishStatus')">
                            <option value="all">All</option>
                            <option value="Published">Published</option>
                            <option value="Not Published">Not Published</option>
                        </select>
                    </div>

                    <div class="me-2">
                        <label>Search:</label>
                        <input type="text" class="form-control" wire:model.debounce.500ms="searchTerm" wire:input="loadLetters" placeholder="Search..">
                    </div>
                    

                </div>

                <!-- Buttons -->
                <div class="d-flex">
                    <button class="btn btn-outline-primary me-2"  wire:click="downloadExcel">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="submit-btn" wire:click="showPrepareLetter">Prepare A Letter</button>
                </div>
            </div>

            <!-- Table -->
            <div class="analytic-table-container mt-3">
                <table class="analytic-table table-responsive">
                    <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                        <tr>
                            <th>Letter Template</th>
                            <th style="width: 140px;">Serial No</th>
                            <th>Employee</th>
                            <th  style="width: 15%;">Prepared On</th>
                            <th>Prepared By</th>
                            <th>Status</th>
                            <th style="width: 14%;">Action</th> <!-- Add Action Column -->
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $preparedBy = Auth::user()->emp_id;
                            $name = App\Models\EmployeeDetails::where('emp_id', $preparedBy)->first();

                            if ($name) {
                                $preparedBy = $name->first_name . ' ' . $name->last_name;
                            } else {
                                $preparedBy = 'Unknown';
                            }

                        @endphp

                        @forelse($letters as $letter)
                            @php
                                // Decode the employees JSON field into an array
                                $employees = json_decode($letter->employees, true); // true converts the JSON to an associative array

                                // Get the employee names
                                $employeeNames = array_map(function ($employee) {
                                    return $employee['name'];
                                }, $employees);
                            @endphp

                            <tr>
                                <td class="analytic-grey-text"> {{ $letter->template_name }}</td>
                                <td class="analytic-grey-text" style="width: 140px;">{{ $letter->serial_no }}</td>
                                <td class="analytic-grey-text">
                                    {{-- Display employee names (you can join multiple names if there are multiple employees) --}}
                                    {{ ucwords(strtolower(implode(', ', $employeeNames))) }}
                                </td>
                                <td class="analytic-grey-text">
                                    {{ \Carbon\Carbon::parse($letter->created_at)->format('d M Y') }}</td>
                                <td class="analytic-grey-text">{{ ucwords(strtolower($preparedBy)) }}</td>

                                <td class="analytic-grey-text">{{ ucfirst($letter->status ?? 'Pending') }}</td>
                  
                                <td>
                                    <!-- Action Icons: Edit, View, Delete -->
                                    <a href="#" wire:click="viewLetter({{ $letter->id }})" title="View">
                                        <i class="fas fa-eye text-secondary"></i>
                                    </a>
                                    <a href="#" wire:click="downloadLetter({{ $letter->id }})" title="Download" class="mx-2">
                                        <i class="fas fa-download text-primary"></i>
                                    </a>
                                   
                                    <a href="#" wire:click="publishLetter({{ $letter->id }})" title="Publish" class="mx-2" @if ($letter->status === 'Published') disabled @endif>
                                        <i class="fas fa-paper-plane text-success"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}"
                                    alt="No items found">
                                <p>No Data Found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>



                <!-- Modal Popup -->
                @if ($showLetterModal)
                    <div class="modal fade show d-block" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $letter->template_name ?? 'Letter' }}</h5>
                                    <button type="button" class="btn-close" wire:click="closeLetterModal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 400px; height: 400px; overflow-y: scroll;">
                                    @if ($letter->template_name == 'Appointment Order')
                                        <div class="container">
                                            <div class="header" style="text-align: center;">
                                                <p>Xsilica Software Solutions Pvt. Ltd.</p>
                                                <p>Unit No - 4, Kapil Kavuri Hub, 3rd floor, Nanakramguda, <br>
                                                    Serilingampally, Ranga Reddy, Telangana-500032.</p>
                                            </div>
                                            <p style="text-align: left;">{{ now()->format('d M Y') }}</p>
                                            <p>To,<br>{{ $employeeName }}<br>
                                                Employee ID: {{ $employeeId }}<br>{{ $employeeAddress }}</p>
                                            <p class="text-center"><strong>Sub: Appointment Order</strong></p>
                                            <p><strong>Dear</strong> {{ $employeeName }},</p>
                                            <p>We are pleased to offer you the position of <strong>Software Engineer
                                                    I</strong> at Xsilica Software Solutions Pvt. Ltd., as per the
                                                discussion we had with you. Below are the details of your appointment:
                                            </p>
                                            <ul>
                                                <li><strong>1. Start Date:</strong> 02 Jan 2023</li>
                                                <li><strong>2. Compensation:</strong> Rs. 2,40,000/-</li>
                                                <li><strong>3. Probation Period:</strong> Six calendar months from your
                                                    joining date.</li>
                                                <li><strong>4. Confirmation of Employment:</strong> After probation.
                                                </li>
                                            </ul>
                                            <p><strong>We are excited to have you as a part of our team!</strong></p>
                                            <div class="signature">
                                                <p>Yours faithfully,</p>
                                                <p>For <strong>Xsilica Software Solutions Pvt. Ltd.</strong></p>
                                                <p style="font-size: 12px;"><strong>{{ $fullName }}</strong></p>
                                                @if ($signature)
                                              
                                                    <img src="data:image/jpeg;base64,{{ $signature }}"
                                                        alt="Signature" style="width:150px; height:auto;">
                                                @endif
                                                <p style="font-size: 12px;"><strong>{{ $designation }}</strong></p>
                                            </div>
                                        </div>
                                    @elseif ($letter->template_name == 'Confirmation Letter')
                                        <div class="container">
                                            <div class="header" style="text-align: center;">
                                                <p>Xsilica Software Solutions Pvt. Ltd.</p>
                                                <p>Unit No - 4, Kapil Kavuri Hub, 3rd floor, Nanakramguda, <br>
                                                    Serilingampally, Ranga Reddy, Telangana-500032.</p>
                                            </div>
                                            <p style="text-align: left;">{{ now()->format('d M Y') }}</p>
                                            <p>To,<br>{{ $employeeName }}<br>
                                                Employee ID: {{ $employeeId }}<br>{{ $employeeAddress }}</p>
                                            <p class="text-center"><strong>Sub: Confirmation Letter</strong></p>
                                            <p><strong>Dear</strong> {{ $employeeName }},</p>
                                            <p>Your employment with us is confirmed effective from
                                                <strong>{{ now()->format('d M Y') }}</strong>.</p>
                                            <div class="signature">
                                                <p>Yours faithfully,</p>
                                                <p>For <strong>Xsilica Software Solutions Pvt. Ltd.</strong></p>
                                                <p style="font-size: 12px;"><strong>{{ $fullName }}</strong></p>
                                                @if ($signature)
                                                    <img src="data:image/jpeg;base64,{{ $signature }}"
                                                        alt="Signature" style="width:150px; height:auto;">
                                                @endif
                                                <p style="font-size: 12px;"><strong>{{ $designation }}</strong></p>
                                            </div>
                                        </div>
                                    @else
                                        <p>Invalid template selected.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeLetterModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
                @endif




            </div>

        </div>
        <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab"
            tabindex="0">

            <div>
                activity review
            </div>

        </div>
    </div>
</div>
