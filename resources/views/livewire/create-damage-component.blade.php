<div>
<style>
      .hide-attendance-help {
    position: absolute;
    bottom: 75px; /* Adjust as needed */
    right: 10px;
    color: #0000FF;
    font-weight: 500;
    cursor: pointer;
}
.exception-form {
    max-width: 400px;
    margin: auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: Arial, sans-serif;
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}
.closeIcon {
    color: white;
    font-size: 18px;
}
.close {
    background-color: #ccc;
    border: #ccc;
    height: 33px;
    width: 33px;
}
.employee-select {
    display: flex;
    align-items: center;
    gap: 8px;
}

.employee-icon {
    color: #6c757d;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}
.ellipsis {
    font-size: var(--normal-font-size);
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 110px;
    display: inline-block;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
}
.normalTextSmall {
    color: var(--label-color);
    font-weight: 500;
    font-size: 0.65rem;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #f8f9fa;
    color: #6c757d;
}
.scrollApplyingTO {
            height: 200px;
            max-height: 200px;
            overflow-y: auto;
        }
        .searchContainer {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 2px 0 5px 0 #ccc;
    padding: 12px 15px;
    width: 250px;
    margin-top: 15px;
    display:none;
}
.selected-employee-box {
            border: 1px solid #007bff;
            /* Border color */
            padding: 5px 10px;
            /* Adjust padding for a smaller box */
            border-radius: 5px;
            /* Rounded corners */
            font-size: 14px;
            /* Smaller font size */
            color: #333;
            /* Text color */
            width: 300px;
            /* Fixed width for rectangular shape */
            height: 50px;
            /* Fixed height for a smaller box */
            display: flex;
            /* Use flexbox for positioning */
            align-items: center;
            /* Center items vertically */
            justify-content: space-between;
            /* Space between items */
            margin-top: 10px;
            /* Adds some space from the top */
        }

        .selected-employee-box {
            margin-left: 15px;
            /* Space between circle and text */
        }

        
        



       

        .selected-employee-box .close-btn {
            position: absolute;
            /* Absolute positioning */
            right: 5px;
            /* Position from the right edge */
            top: 1px;
            /* Position from the top edge */
            background: transparent;
            /* Transparent background */
            border: none;
            /* Remove default border */
            cursor: pointer;
            /* Change cursor on hover */
        }
        .employee-container {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 14px;
                color: #333;
                background-color: #d9edf7;
                border:1px solid #bce8f1;
                color: #3a87ad;
        }
      
     </style>     
    <div class="attendance-overview-help">
            <p style="font-size:13px;">On the Fines / Damages page, you can track the fines and damages collected or to be collected from an employee.<br/>Fines are collected from regular employees. At the same time, damages are collected from the contract employees. The collection can be<br/> in installments or at one time. Click Add Fine under the Fines tab and Add Damage under the Damages tab to add a new fine or damage. </p>
            <p style="font-size:13px;">Explore greytHR by <a href="https://admin-help.greythr.com/admin/answers/123712613" target="_blank" style="color: #1fb6ff;cursor:pointer;">Help-Doc</a>.Watching<a href="https://youtu.be/drrSfJrjHz0?si=BrqYlKmNr9kFrHLT" target="_blank" style="color: #1fb6ff;cursor:pointer;">&nbsp;How-to Videos&nbsp;</a>and<a href="https://greythr.freshdesk.com/support/search/solutions?term=attendance+overview"style="color: #1fb6ff;cursor:pointer;">&nbsp;FAQ&nbsp;</a>.</p>
            <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
      </div>
    <div class="exception-form">
   
      
        <!-- Employee Selection -->
        <div class="form-group">
        <label for="employeeType" wire:click="searchforEmployee" style="cursor:pointer;">Employee:</label><br/>
        @if (!empty($fineId))
            
                <div class="employee-container">
                    <span id="employeeName">{{$empName}}</span>
                    <span id="employeeId">({{$empId}})</span>
                </div>
            
        
        @endif
        @if (empty($fineId))
        
       
        <div class="searchContainer" style="<?php echo ($searchEmployee == 1) ? 'display: block;' : ''; ?>">
            <!-- Content for the search container -->
            <div class="row mb-2 py-0 px-2">
                <div class="row m-0 p-0 d-flex align-items-center justify-content-between">
                    <div class="col-md-10 p-0 m-0">
                        <div class="input-group">
                            <input wire:model="searchTerm" wire:change="updatesearchTerm" id="searchInput" type="text"
                                class="form-control placeholder-small" placeholder="Search...." aria-label="Search"
                                aria-describedby="basic-addon1">
                            <div class="input-group-append searchBtnBg d-flex align-items-center">
                                <button type="button" class="search-btn">
                                    <i class="ph-magnifying-glass ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col m-0 p-0 d-flex justify-content-end">
                        <button wire:click="closeEmployeeBox" type="button" class="close rounded px-1 py-0"
                            aria-label="Close">
                            <span aria-hidden="true" class="closeIcon"><i class="ph-x"></i></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Your Blade file -->
            <div class="scrollApplyingTO">
                @if(!empty($employees) && $employees->isNotEmpty())
                @foreach($employees as $employee)
                <div class="d-flex gap-3 align-items-center" style="cursor: pointer;"
                    wire:click="updateselectedEmployee('{{ $employee->emp_id }}')">
                    @if($employee['image'] && $employee['image'] !== 'null')
                    <div class="employee-profile-image-container">
                        <img class="navProfileImg rounded-circle" src="data:image/jpeg;base64,{{ $employee->image }}">

                    </div>
                    @else
                    @if($employee['gender'] == 'Female')
                    <div class="employee-profile-image-container">
                        <img src="{{ asset('images/female-default.jpg') }}"
                            class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                            alt="Default Image">
                    </div>
                    @elseif($employee['gender'] == 'Male')
                    <div class="employee-profile-image-container">
                        <img src="{{ asset('images/male-default.png') }}"
                            class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                            alt="Default Image">
                    </div>
                    @else
                    <div class="employee-profile-image-container">
                        <img src="{{ asset('images/user.jpg') }}"
                            class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px"
                            alt="Default Image">
                    </div>
                    @endif
                    @endif
                    <div class="d-flex flex-column mt-2 mb-2">
                        <span class="ellipsis mb-0">{{ ucwords(strtolower($employee['first_name'])) }}
                            {{ ucwords(strtolower($employee['last_name'])) }}</span>
                        <span class="mb-0 normalTextSmall"> #{{ $employee['emp_id'] }} </span>
                    </div>
                </div>
                @endforeach
                @else
                <p class="mb-0 normalTextValue text-muted m-auto text-center" style="font-size:12px;">No employees
                    found.</p>
                @endif
            </div>
           
        </div>
        @endif
        @if(!empty($selectedEmployeeId) && empty($fineId))
            
                        @php
                            function getRandomAbsentColor() {
                                $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF', '#FFC5C5'];
                                        return $colors[array_rand($colors)];
                                }
                        @endphp         
                        <div class="row m-0 p-0">
                            <div class="col p-0 m-0">
                                @if($searchEmployee==0)
                                <div class="selected-employee-box position-relative gap-4">
                                    <button type="button" class="close-btn" wire:click="clearSelectedEmployee">
                                        &times; <!-- This will render a cross (×) symbol -->
                                    </button>
                                        <div class="gap-1" style="display: flex; align-items: center;">
                                            <div class="thisCircle" style="border: 2px solid {{ getRandomAbsentColor() }};" data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">
                                                <span class="initials">
                                                    {{ strtoupper(substr(trim($selectedEmployeeFirstName), 0, 1)) }}{{ strtoupper(substr(trim($selectedEmployeeLastName), 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="employee-info">
                                                <span class="employee-info-name"data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ ucwords(strtolower($selectedEmployeeFirstName)) }} {{ ucwords(strtolower($selectedEmployeeLastName)) }}">{{ ucwords(strtolower($selectedEmployeeFirstName)) }}&nbsp;{{ ucwords(strtolower($selectedEmployeeLastName)) }}</span>
                                                {{ $selectedEmployeeId }}
                                            </div>
                                        </div>    
                                </div>
                                @endif
                            </div>
                        </div>
                @endif
        </div>
      
        <!-- From Date -->
      
        <div class="form-group">
            <label for="damage_or_loss_date" class="form-label">Date of Damage/Loss:</label>
            <input type="date" id="damage_or_loss_date" class="form-control"wire:model="damage_or_loss_date"wire:change="updatedamageOrLossDate"@if($viewMode) disabled @endif>
        </div>
        @error('damage_or_loss_date') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- To Date -->
        <div class="form-group">
            <label for="damage_or_loss_reason" class="form-label">Particulars of Damage/Loss:</label>
            <input type="text" id="damage_or_loss_reason" class="form-control"wire:model="damage_or_loss_reason"wire:change="updateDamageOrLossReason"@if($viewMode) disabled @endif>
        </div>
        @error('damage_or_loss_reason') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- Status -->
        <div class="form-group">
             <label for="show_cause" class="form-label">Whether workman showed cause against deduction:</label>
             <select id="show_cause" class="form-control" wire:model="show_cause"wire:change="updateShowCause"@if($viewMode) disabled @endif>
                    <option value="">-- Select --</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                    
            </select>
        </div>
        @error('show_cause') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- Reason -->

        <div class="form-group">
            <label for="show_cause_notice_date" class="form-label">Date of Show Cause Notice:</label>
            <input type="date" id="show_cause_notice_date" class="form-control"wire:model="show_cause_notice_date"wire:change="updateShowCauseNoticeDate"@if($viewMode) disabled @endif>
        </div>
        @error('show_cause_notice_date') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="form-group">
            <label for="name" class="form-label">Name of the person in whose presence employee's explaination was heard
            </label>
            <input type="name" id="name" class="form-control"wire:model="name"wire:change="updateName"oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"@if($viewMode) disabled @endif>

        </div>
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror

        <div class="form-group">
            <label for="amount" class="form-label">Amount of deduction imposed

            </label>
            <input type="number" id="amount" class="form-control"wire:model="amount"wire:change="updateAmount"@if($viewMode) disabled @endif>
        </div>
        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="form-group">
            <label for="installment_number" class="form-label">No of Installments

            </label>
            <input type="number" id="installment_number" class="form-control"wire:model="installment_number"wire:change="updateInstallmentNumber"@if($viewMode) disabled @endif>
        </div>
        @error('installment_number') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="form-group">
            <label for="recovery_first_installment_date" class="form-label">Date of recovery-first installment
            </label>
            <input type="date" id="recovery_first_installment_date" class="form-control"wire:model="recovery_first_installment_date"wire:change="updateRecoveryFirstInstallmentDate"@if($viewMode) disabled @endif>
        </div>
        @error('recovery_first_installment_date') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="form-group">
            <label for="recovery_last_installment_date" class="form-label">Date of recovery-last installment
            </label>
            <input type="date" id="recovery_last_installment_date" class="form-control"wire:model="recovery_last_installment_date"wire:change="updateRecoveryLastInstallmentDate"@if($viewMode) disabled @endif>
        </div>
        @error('recovery_last_installment_date') <span class="text-danger">{{ $message }}</span> @enderror
        <div class="form-group">
            <label for="remarks" class="form-label">Remarks
            </label>
            <textarea id="remarks" rows="3" class="form-control"wire:model="remarks"wire:change="updateRemarks"oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"@if($viewMode) disabled @endif></textarea>
        </div>
        @error('remarks') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- Action Buttons -->
        <div class="C">
       
            <button type="submit" class="btn btn-primary"wire:click="submitFineDetails">
                
                  Save
                
            </button>
           
            <button type="button" class="btn btn-secondary"wire:click="closeFineDetails">Cancel</button>
        
        </div>
    
</div>
</div>
