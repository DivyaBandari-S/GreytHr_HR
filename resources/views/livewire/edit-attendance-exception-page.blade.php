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

        .selected-employee-box {
            margin-left: 15px;
            /* Space between circle and text */
        }

        .thisCircle {
            display: flex;
            margin-left: 10px;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 35px;
            cursor: pointer;
            border-radius: 50%;
            color: var(--label-color);
            font-weight: 500;
            background-color: #fff;
            font-size: var(--normal-font-size);
        }

        .selected-employee-box .employee-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-right: 115px;
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

     </style>     
    <div class="attendance-overview-help"style="position: relative; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
                    
                    <p>This page enables you to create an Attendance Exception for an employee. Select an employee and add other details to create an exception. The exception created, is then displayed on the <span style="font-weight:bold;">Attendance Exception</span> page.</p>
                    
                    <span class="hide-attendance-help" style="display: block; text-align: center; margin-top: 40px; cursor: pointer; color: blue; font-weight: bold;">
                                 Hide Help
                    </span>
    </div>
    <div class="exception-form">
   
      
        <!-- Employee Selection -->
        <div class="form-group">
             <label for="employeeType">Employee:</label>
             <div class="employee-container">
                <span id="employeeName">{{$employeeName}}</span>
                <span id="employeeId">({{$employeeId}})</span>
             </div>
        </div>
      
        <!-- From Date -->
        <div class="form-group">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" id="from_date" class="form-control"wire:model="from_date">
        </div>
      
        <!-- To Date -->
        <div class="form-group">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" id="to_date" class="form-control"wire:model="to_date">
        </div>
        
        <!-- Status -->
        <div class="form-group">
             <label for="status" class="form-label">Status</label>
             <select id="status" class="form-control" wire:model="status">
                    <option value="">-- Select --</option>
                    <option value="Absent">Absent</option>
                    <option value="Holiday">Holiday</option>
                    <option value="Leave">Leave</option>
                    <option value="Off Day">Off Day</option>
                    <option value="On Duty">On Duty</option>
                    <option value="Present">Present</option>
                    <option value="Rest Day">Rest Day</option>
            </select>
        </div>
        
        <!-- Reason -->
        <div class="form-group">
            <label for="reason" class="form-label">Reason</label>
            <textarea id="reason" rows="3" class="form-control"wire:model="reason"wire:change="updateReason"></textarea>
        </div>
        
        <!-- Action Buttons -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"wire:click="updateAttendanceException">Save</button>
            <button type="button" class="btn btn-secondary"wire:click="closeAttendanceException">Cancel</button>
        </div>
    
</div>

</div>
