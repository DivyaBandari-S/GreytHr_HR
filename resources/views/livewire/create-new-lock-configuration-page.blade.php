<div>
    <style>
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
.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}
.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}
.hide-attendance-help {
    margin-top:40px;
    position: absolute;
    bottom: 180px;
    right: 10px;
    color: #0000FF;
    font-weight:500;
    cursor: pointer;
}
    </style>
             <div class="attendance-overview-help">
                    
                    <p>Select <span style="font-weight:bold;">Category Type</span> and enter other details to create a lock.</p>
                    <p>Note:The lock action is based on the type of category you select such as locking attendance processing in given example or preventing attendance regularization. The selected values operate within the given date range. The action you select as per <span style="font-style:italic;">Category Type</span> comes in to effect on <span style="font-style:italic;">Effective</span> Date. For example, you may prevent application for leave between <span style="font-style:italic;">5th and 7th of August</span> but the actual lock comes in to effect only from <span style="font-style:italic">8th August</span>.</p>
                    <p>In the case of overlapping dates in locks, the effective date determines action. For example, <span style="font-style:italic;">Leave Process</span> is from <span style="font-style:italic;">1st to 30th June</span> and <span style="font-style:italic;">Attendance Regularization</span> is from <span style="font-style:italic;">1st to 29th June</span>. In such a case, when an employee applies for leave on <span style="font-style:italic">23rd June</span> and it is not approved before <span style="font-style:italic">29th June</span> then the leave is processed as absent day due to the effective date of <span style="font-style:italic">Attendance Regularization</span> taking precedence. Approved leaves will not be affected.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
            </div>
   <div class="exception-form">
        <div class="form-group">
             <label for="categoryType" class="form-label">Category Type</label>
             <select id="categoryType" class="form-control" wire:model="categoryType"wire:change="updatecategoryType">
                    <option value="">-- Select --</option>
                    <option value="mass_override">MASS_OVERRIDE</option>
                    <option value="attendance_process">ATTENDANCE_PROCESS</option>
            </select>
            @error('categoryType') 
                  <span class="text-danger">{{ $message }}</span> 
            @enderror
        </div>
        
        <div class="form-group">
             <label class="form-label">Lock By</label>
             <div class="form-check">
                <input class="form-check-input" type="radio" name="lockBy" id="employeeFilter" wire:model="lockBy" value="employeeFilter"wire:change="updateLockBy">
                <label class="form-check-label" for="employeeFilter">
                     Employee Filter
                </label>
             </div>
             <div class="form-check">
              <input class="form-check-input" type="radio" name="lockBy" id="attendanceCycle" wire:model="lockBy" value="attendanceCycle"wire:change="updateLockBy">
              <label class="form-check-label" for="attendanceCycle">
                        Attendance Cycle
              </label>
           </div>
        </div>
        @if ($lockBy=='employeeFilter')
            <div class="form-group">
                <label for="employeeFilter" class="form-label">Employee Filter</label>
                <select id="employeeFilter" class="form-control" wire:model="employeeFilter"wire:change="updateemployeeFilter">
                        <option value="all_employees">All Employees</option>
                        <option value="trainee_employees">Trainee Employees</option>
                </select>
            </div>
        @elseif($lockBy=='attendanceCycle')
        <div class="form-group">
                <label for="attendanceCycle" class="form-label">Attendance Cycle</label>
                <select id="attendanceCycle" class="form-control" wire:model="attendanceCycle"wire:change="updateattendanceCycle">
                        <option value="">Select Attendance Cycle</option>
                        <option value="default_attendance">Default Attendance Cycle</option>
                        
                </select>
                @error('attendanceCycle') <span class="text-danger">{{ $message }}</span> @enderror
            </div>    
        @endif
        <div class="form-group">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" id="from_date" class="form-control"wire:model="fromDate"wire:change="updatefromDate">
            @error('fromDate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <!-- To Date -->
        <div class="form-group">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" id="to_date" class="form-control"wire:model="toDate"wire:change="updatetoDate">
            @error('toDate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
        <!-- Status -->
        
        <div class="form-group">
            <label for="effective_date" class="form-label">Effective Date</label>
            <input type="date" id="effective_date" class="form-control"wire:model="effectiveDate"wire:change="updateeffectiveDate">
            @error('effectiveDate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        @error('to_date') <span class="text-danger">{{ $message }}</span> @enderror
        <!-- Reason -->
       
        <!-- Action Buttons -->
        <div class="C">
            <button type="submit" class="btn btn-primary"wire:click="save">Save</button>
            <button type="button" class="btn btn-secondary"wire:click="cancel">Cancel</button>
        </div>
    
   </div>
</div>
