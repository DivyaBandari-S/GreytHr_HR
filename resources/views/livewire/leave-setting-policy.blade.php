<div class="leaveSettings">
    <div class="row m-0  leaveGranter-container">
        <div class="col-md-8">
            <div class="leaveGranter-header mb-2">
                <div class="d-flex flex-column text-start">
                    <h3 class="mb-0">Leave Settings</h3>
                    <p>This page helps you to configure leave policy.</p>
                </div>
                <span class="subTextValue">Jan 2024 - Dec 2024</span>
            </div>

            <div class="accordion d-flex flex-column gap-3" id="leaveAccordion">
                <!-- Casual Leave -->
                <div class="accordion-item border rounded">
                    <h2 class="accordion-header" id="headingCasual">
                        <button class="accordion-button collapsed p-0" type="button">
                            <div class="d-flex rounded py-2 px-4 w-100 align-items-center justify-content-between" style="box-shadow: 1px 0px 3px 0px rgb(0,0,0,0.1);">
                                <div class="col p-0 text-start">
                                    <span class="subText">Leave Name & Code</span><br>
                                    <span class="subTextValue">Casual Leave | CL</span>
                                </div>
                                <div class="col p-0 text-start">
                                    <span class="subText">Grant Days & Frequency</span><br>
                                    <span class="subTextValue">6 | Annual</span>
                                </div>
                                <div class="iconAccordion">
                                    <i class="fas fa-pen leaveGranter-edit" data-bs-toggle="collapse" data-bs-target="#collapseCasual" aria-expanded="false" aria-controls="collapseCasual"></i>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseCasual" class="accordion-collapse collapse" aria-labelledby="headingCasual" data-bs-parent="#leaveAccordion">
                        <div class="accordion-body rounded">
                            <div class="form-container">
                                <form>
                                    <!-- Leave Name -->
                                    <div class="form-group mb-2">
                                        <label for="leaveName">Leave Name <span class="required">*</span></label>
                                        <input type="text" id="leaveName" name="leaveName" class="form-control" placeholder="Casual Leave" required>
                                    </div>

                                    <!-- Leave Code -->
                                    <div class="form-group mb-2">
                                        <label for="leaveCode">Leave Code <span class="required">*</span></label>
                                        <input type="text" id="leaveCode" name="leaveCode" class="form-control" placeholder="CL" required>
                                    </div>

                                    <!-- Leave Granting Frequency -->
                                    <div class="form-group mb-2">
                                        <label for="leaveFrequency">Leave Granting Frequency <span class="required">*</span></label>
                                        <select id="leaveFrequency" name="leaveFrequency" class="form-control" required>
                                            <option value="Annual">Annual</option>
                                            <option value="Monthly">Monthly</option>
                                        </select>
                                    </div>

                                    <!-- Grant Days -->
                                    <div class="form-group mb-2">
                                        <label for="grantDays">Grant Days <span class="required">*</span></label>
                                        <input type="number" id="grantDays" name="grantDays" class="form-control" placeholder="6" required>
                                    </div>

                                    <!-- Save Changes Button -->
                                    <div class=" mt-3 d-flex justify-content-center">
                                        <button type="submit" class="submit-btn">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="submit-btn mt-3">OK & Continue</button>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end">
                <button type="submit" wire:click="toggleAddForm" class="cancel-btn">Add New</button>
            </div>
            @if($showAddForm)
            <div>
                <form wire:submit.prevent="addNewType">
                    <div class="form-group mb-2">
                        <label for="leave_name">Leave Name <span class="required">*</span></label>
                        <input type="text" id="leave_name" wire:model="leave_name" class="form-control" placeholder="Casual Leave" >
                        @error('leave_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="grant_days">Grant Days <span class="required">*</span></label>
                        <input type="number" id="grant_days" wire:model="grant_days" class="form-control" placeholder="6" >
                        @error('grant_days') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="leave_frequency">Leave Frequency</label>
                        <select id="leave_frequency" wire:model="leave_frequency" class="form-control">
                            <option value="">Select Frequency</option>
                            <option value="Annual">Annual</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                        @error('leave_frequency') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="leave_code">Leave Code <span class="required">*</span></label>
                        <input type="text" id="leave_code" wire:model="leave_code" class="form-control" placeholder="CL" >
                        @error('leave_code') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        <button type="submit" class="submit-btn">Add Leave Policy</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.leaveGranter-edit').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering parent toggle
            // Toggle active color on the icon itself
            icon.classList.toggle('active-edit');
        });
    });
</script>