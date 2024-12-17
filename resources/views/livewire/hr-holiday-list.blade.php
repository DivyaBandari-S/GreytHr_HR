<div>
<style>
    /* Leave->Admin->Leave Granter styles start  */
#myTab {
    margin-top: 3px;
    padding: 0 20px;
    background: #fff;
    font-size: var(--sub-headings-font-size);
}
.radioLabel{
    display: flex;
    gap: 5px;
    color: var(--label-color);
}
.labelText{
    color: var(--label-color);
    font-size: var(--normal-font-size);
    font-weight: 500;
    margin-bottom: 5px;
}
.grid {
    display: flex;
    flex-direction: column;
}
.periodicity {
    display: grid;
    grid-template-columns: 1fr 4fr; /* Adjust the fraction values as needed */
    gap: 10px; /* Space between the columns */
    align-items: center; /* Align items vertically */
}
.radio-buttons{
    display: flex;
    gap: 20px;
}
.leave-grant-nav-tabs .leave-grant-nav-item.show .leave-grant-nav-link,
.leave-grant-nav-tabs .leave-grant-nav-link {
    border: none;
    color: var(--main-heading-color);
    font-weight: 500;
    font-size: 15px;
    background: none !important;
}
.leave-grant-nav-tabs .leave-grant-nav-item.show .leave-grant-nav-link,
.leave-grant-nav-tabs .leave-grant-nav-link.active {
    border-bottom: 2px solid #306cc6 !important;
    color: #306cc6;
    background-color: none !important;
    font-weight: 500;
    font-size: 15px;
    width: 65px;
}
.msgHeighlighter {
    color: var(--main-heading-color);
    font-weight: 500;
    font-size: var(--normal-font-size);
}
.leave-granter {
    background-color: none;
    font-family: Arial, sans-serif;
}

.filters select,
.date-picker input {
    padding: 8px 12px;
    border: 1px solid #d9dde2;
    border-radius: 4px;
    background-color: #fff;
}

/* General styles for the filters */
.filters {
    display: flex;
    gap: 10px;
}
.buttonRouting{
    color: white !important;
}
/* Style for custom dropdown */
.custom-dropdown {
    position: relative;
    width: 150px;
}

.dropdown-selected {
    padding: 8px 12px;
    border: 1px solid #d9dde2;
    border-radius: 4px;
    background-color: #fff;
    font-size: var(--normal-font-size);
    font-weight: 500;
    color: var(--main-heading-color);
    cursor: pointer;
    transition: border-color 0.3s ease;
    position: relative; /* For arrow positioning */
    width: 200px;

}

.dropdown-selected:hover {
    border-color: #4a90e2; /* Border color on hover */
}

/* Arrow styles */
.dropdown-selected::after {
    content: "";
    position: absolute;
    /* right: 12px; /* Adjust for padding */
    top: 50%; */
    top: 379px;
    left: 939px;
    right: 20px;
    transform: translateY(-50%);
    border: solid transparent;
    border-width: 5px 5px 0;
    border-top-color: #4a4a4a; /* Arrow color */
}

.dropdown-options {
    display: none; /* Hide options by default */
    position: absolute;
    top: 379px;
    left: 939px;
    right: 20px;

    font-size: var(--normal-font-size);
    color: var(--main-heading-color);
    background-color: #fff;
    border: 1px solid #d9dde2;
    border-radius: 4px;
    z-index: 10;
}

.dropdown-option {
    padding: 8px 12px;
    font-size: var(--normal-font-size);
    color: var(--main-heading-color);
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.dropdown-option:hover {
    background-color: #dbedf4; /* Light blue on hover */
}

/* Hover effect for the button */
.submit-btn:hover {
    background-color: #357ab8; /* Darker blue on hover */
    border-color: #357ab8;
}

.date-picker input {
    border: 1px solid #d9dde2;
    outline: none;
    font-size: var(--sub-headings-font-size);
    font-weight: 300;
    color: var(--main-heading-color);
    background-color: #fff;
}

.leave-table {
    width: 100%;
    border-collapse: collapse;
}

.leave-table th,
.leave-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #a7a6a65e;
}

.leave-table th {
    background-color: var(--main-table-heading-bg-color);
    font-weight: 600;
    font-size: var(--sub-headings-font-size);
    color: var(--main-table-heading-text-color);
}

.leave-table tbody tr {
    background-color: #fff;
}

.leave-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.leave-table .batch-info {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.leave-table td button.delete-btn {
    border: none;
    background: none;
    color: #d9534f;
    font-size: 18px;
    cursor: pointer;
}

.leave-table td button.delete-btn:hover {
    color: #b52b27;
}
.task-date-range-picker {
            display: flex;
            justify-content: flex-end;
            margin-right: 15px;
        }

        .task-custom-select-width {
            width: 170px;
            font-size: var(--normal-font-size);
            padding: 7px;
        }
</style>
    <div class="container-fluid px-1  rounded">

        <ul class="nav leave-grant-nav-tabs d-flex" id="myTab" role="tablist" style="gap: 30px;padding: 18px 18px 0px 23px;">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>

        <div class="tab-content" id="myTabContent">

            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                @if($showLeaveBalanceSummary)
                <div class="px-3 py-2">
                    <div class="row main-overview-help">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Holiday List </span>  page displays all the holidays declared for the year. You can also manage the Holiday calendar here. You may define the same holidays across the organization or have a different list across various employee categories (Location, Factory, and so on). 

                            </p>
                            <p class="main-overview-text mb-0">Explore greytHR by <span class="main-overview-highlited-text">
                                    Help-Doc</span>, watching<span class="main-overview-highlited-text"> How-to Videos</span>
                                and<span class="main-overview-highlited-text"> FAQ</span>.</p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group task-date-range-picker">
                    <select class="form-select task-custom-select-width" wire:model.defer="filterPeriodValue"
                        wire:change="filterPeriodChanged">
                        <option value="this_year">Jan {{ date('Y') }} - Dec {{ date('Y') }}</option>
                        <option value="current_year">Jan {{ date('Y') - 1 }} - Dec {{ date('Y') - 1 }}</option>
                    </select>
    
                </div>
                <div class="table-responsive" style="margin: 15px 15px 5px 15px; height: 300px; overflow-y: scroll;">
                    <table class="table pendingLeaveTable table-bordered table-hover ">
                        <thead class="table-light">
           
                        <tr >
                            <th >
                               
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        wire:model="selectAll"
                                        wire:click="toggleSelectAll"
                                        />
                                </div>
                            </th>
                            <th >#</th>
                            <th >Occasion</th>
                            <th >Date</th>
                            <th >Day</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($holidays as $index => $holiday)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        wire:model="selectedHolidays" value="{{ $holiday->id }}" />
                                </div>
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $holiday->festivals }}</td>
                            <td>{{ $holiday->date }}</td>
                            <td>{{ $holiday->day }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; font-size: var(--normal-font-size);">
                                    There are no holidays defined for this year. Add them now.
                                </td>
                            </tr>
                        @endforelse
                
                        @if($addButton)
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <button wire:click="addHolidays" class="submit-btn">Add Holidays</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: center;">
                                    <a href="#"  wire:click="openModal" style="font-size: 14px; color: rgb(65, 164, 246); text-decoration: underline;">Add From Master List</a>
                                </td>
                            </tr>
                        @endif
                      
                    </tbody>
                  
                </table>
            </div>
               
                @if(count($holidays) > 0)
                <div class="d-flex justify-content-center mt-1 mb-3">
                    <button wire:click="saveHolidays" class="cancel-btn me-2">Save</button>
                    <button class="cancel-btn me-2" wire:click="deleteModal">Delete</button>
                </div>
                @endif
                @if($showDeleteModal)
                <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-white">
                                <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Delete</h6>
                            </div>
                            <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                                Do you want to delete selected record(s)?
                            </div>
                            <div class="d-flex gap-3 justify-content-center p-3">
                                <button type="button" class="submit-btn " wire:click="deleteSelectedHolidays">Confirm</button>
                                <button type="button" class="cancel-btn" wire:click="deleteModal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show"></div>
                @endif
              
               

             
            </div>
           
           

            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">

                <div>
                    activity review
                </div>

            </div>

        </div>
        @if ($isModalOpen)
        <div class="modal fade show d-block bd-example-modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">Select Holidays to Add</h6>
                        <button type="button" class="btn-close btn-primary"  
                            wire:click="close">
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive" style= "height: 300px; overflow-y: scroll;">
                            <table class="table pendingLeaveTable table-bordered table-hover ">
                                <thead class="table-light">
                                <tr>
                                    <th >
                                        {{-- <input type="checkbox" wire:click="toggleSelectAll1"> --}}
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                wire:model="selectAll1"
                                                wire:click="toggleSelectAll1"/>
                                        </div>
                                    </th>
                                    <th >#</th>
                                    <th >Occasion</th>
                                    <th >Date</th>
                                    <th >Day</th>
                                    <th >Restricted Holiday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidaysList as $index => $holiday)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    wire:model="selectedHolidays1"
                                                    value="{{ $holiday->id }}" />
                                            </div>
                                            {{-- <input type="checkbox" wire:model="selectedHolidays1" value="{{ $holiday->id }}"> --}}
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $holiday->occasion }}</td>
                                        <td>{{ $holiday->date }}</td>
                                        <td>{{ $holiday->day }}</td>
                                        <td>{{ $holiday->state }}</td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="addSelectedHolidays" class="btn btn-primary">Add</button>
                        <button wire:click="close" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    </div>

</div>

