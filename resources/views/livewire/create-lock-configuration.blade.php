<div>
    <style>
         .hide-attendance-help {
    margin-top:40px;
    position: absolute;
    bottom: 180px;
    right: 10px;
    color: #0000FF;
    font-weight:500;
    cursor: pointer;
}
.container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    </style>
         <div class="attendance-overview-help">
                    
                    <p>Select <span style="font-weight:bold;">Category Type</span> and enter other details to create a lock.</p>
                    <p>Note:The lock action is based on the type of category you select such as locking attendance processing in given example or preventing attendance regularization. The selected values operate within the given date range. The action you select as per <span style="font-style:italic;">Category Type</span> comes in to effect on <span style="font-style:italic;">Effective</span> Date. For example, you may prevent application for leave between <span style="font-style:italic;">5th and 7th of August</span> but the actual lock comes in to effect only from <span style="font-style:italic">8th August</span>.</p>
                    <p>In the case of overlapping dates in locks, the effective date determines action. For example, <span style="font-style:italic;">Leave Process</span> is from <span style="font-style:italic;">1st to 30th June</span> and <span style="font-style:italic;">Attendance Regularization</span> is from <span style="font-style:italic;">1st to 29th June</span>. In such a case, when an employee applies for leave on <span style="font-style:italic">23rd June</span> and it is not approved before <span style="font-style:italic">29th June</span> then the leave is processed as absent day due to the effective date of <span style="font-style:italic">Attendance Regularization</span> taking precedence. Approved leaves will not be affected.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
            </div>
            <div class="container mt-5">
    <form wire:submit.prevent="save" class="row g-3">
             <label for="categoryType" class="form-label">Category Type</label>
            <select class="form-select" id="categoryType" wire:model="categoryType">
                <option value=" "></option>
                <option value="mass_override">MASS_OVERRIDE</option>
                <option value="attendance_process">MASS_OVERRIDE</option>
                <!-- Add more options as needed -->
            </select>

        <div class="col-6 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="lockBy" id="employeeFilter" wire:model="lockBy" value="employeeFilter">
                <label class="form-check-label" for="employeeFilter">
                    Lock by Employee Filter
                </label>
            </div>
        </div>
        <div class="col-6 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="lockBy" id="attendanceCycle" wire:model="lockBy" value="attendanceCycle">
                <label class="form-check-label" for="attendanceCycle">
                    Lock by Attendance Cycle
                </label>
            </div>
        </div>

        <div class="col-12 mb-3">
            <label for="employeeFilter" class="form-label">Employee Filter</label>
            <select class="form-select" id="employeeFilter" wire:model="employeeFilter">
                <option value="all">All Employees</option>
                <!-- Add more options as needed -->
            </select>
        </div>

        <div class="col-4 mb-3">
            <label for="fromDate" class="form-label">From Date</label>
            <input type="date" class="form-control" id="fromDate" wire:model="fromDate">
        </div>

        <div class="col-4 mb-3">
            <label for="toDate" class="form-label">To Date</label>
            <input type="date" class="form-control" id="toDate" wire:model="toDate">
        </div>

        <div class="col-4 mb-3">
            <label for="effectiveDate" class="form-label">Effective Date</label>
            <input type="date" class="form-control" id="effectiveDate" wire:model="effectiveDate">
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" wire:click="cancel">Cancel</button>
        </div>
    </form>
</div>
</div>
