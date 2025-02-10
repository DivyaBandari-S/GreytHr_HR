<div>
    <style>
        .dropdown-right
        {
            float: right;
            margin-right:10px;
        }
        .arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
        .dropdown-for-employee{
            position: relative; /* Position relative for dropdown positioning */
            display: inline-block; /* Align with other elements */
        }
        .dropdown-for-employee:hover {
               display: block; /* Show on hover */
        }
        .dropdown-button-for-employee {
    background-color: white; /* White background */
    color: #333; /* Text color */
    padding: 10px 15px; /* Space around text */
    border: 1px solid #d1d1d1; /* Light border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer on hover */
    display: flex; /* Flexbox for alignment */
    align-items: center; /* Center items vertically */
    margin-top: 40px;
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
.dropdown-content-for-employee {
    display: none; /* Hidden by default */
    position: absolute; /* Positioned absolutely */
    background-color: white; /* White background */
    min-width: 160px; /* Minimum width */
    border: 1px solid #d1d1d1; /* Light border */
    z-index: 1; /* On top of other elements */
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Shadow for dropdown */
}

.dropdown-content-for-employee a {
    color: #333; /* Text color */
    padding: 12px 16px; /* Space around items */
    text-decoration: none; /* No underline */
    display: block; /* Block display */
}
.dropdown-content-for-employee a:hover {
    background-color: #f1f1f1; /* Light gray background on hover */
}
table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            text-align: left;
           
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #d3dadf; /* Sets the background color of the table headers to pink */
            font-weight: 500;
            font-size: 12px;
            border-right:1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    list-style: none;
    border-radius: 5px;
}

.pagination li {
    margin: 0 5px;
}

.pagination .page-item .page-link {
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #f8f9fa;
    color: #007bff;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border-radius: 5px;
}

.pagination .page-item.disabled .page-link {
    background-color: #e9ecef;
    color: #6c757d;
    pointer-events: none;
}
.custom-dropdown {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.custom-dropdown:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.dropdown-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 250px;
    float: right;
    margin-top: -15px;
}
    </style>    
      @php
                       $i=0;
                    @endphp
                <div class="attendance-overview-help">
                    
                    <p>The <span style="font-weight:bold;">Employee Week Days</span> page allows you to override the weekdays for your employees. The page displays a list of your employees' overridden weekdays <br/> in a tabular format. Normally, Monday to Friday are the working days, and Saturday-Sunday is off days.<br/>
                    The <span style="font-weight:bold;">Add</span> button enables you to specify a working pattern for a week. The From and To date indicates the time period for which this pattern is applicable.</p>
                    <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    
                    <span class="hide-attendance-help-for-employee-week-day" style="margin-top:-70px;" wire:click="hideHelp">Hide Help</span>
                </div>
                    <div class="dropdown-container">
                            <select class="dropdown-right custom-dropdown" wire:model="selectedYear" wire:change="updateSelectedYear">
                                <option value="{{ $previousYear }}">
                                    {{ \Carbon\Carbon::createFromDate($previousYear, 1, 1)->format('M') }} {{ $previousYear }} - 
                                    {{ \Carbon\Carbon::createFromDate($previousYear, 12, 1)->format('M') }} {{ $previousYear }}
                                </option>
                                <option value="{{ $currentYear }}">
                                    {{ \Carbon\Carbon::createFromDate($currentYear, 1, 1)->format('M') }} {{ $currentYear }} - 
                                    {{ \Carbon\Carbon::createFromDate($currentYear, 12, 1)->format('M') }} {{ $currentYear }}
                                </option>
                                <option value="{{ $nextYear }}">
                                    {{ \Carbon\Carbon::createFromDate($nextYear, 1, 1)->format('M') }} {{ $nextYear }} - 
                                    {{ \Carbon\Carbon::createFromDate($nextYear, 12, 1)->format('M') }} {{ $nextYear }}
                                </option>
                            </select>
                    </div>
    <div style="margin-top:60px;">               
            <div style="display:flex;flex-direction:row;justify-content:space-between; align-items:center;margin-left: 45px;">
                <div class="gap-4" style="display:flex;flex-direction:row;">
                    <div class="dropdown-for-employee">
                                <button class="dropdown-button-for-employee"><span style="font-size:12px;">Employee: {{ ucfirst($selectedOption) }}</span> <span class="arrow-for-employee"></span></button>
                                <div class="dropdown-content-for-employee">
                                    <a href="#" wire:click.prevent="updateSelected('all')">All</a>
                                    <a href="#" wire:click.prevent="updateSelected('current')">Current</a>
                                    <a href="#" wire:click.prevent="updateSelected('past')">Past</a>
                                    <a href="#" wire:click.prevent="updateSelected('intern')">Intern</a>
                                </div>
                    </div>
                    
                </div>
                
            </div>
            <button style="border:1px solid blue;margin-right:15px;margin-top:-40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="callCreateWeekDay">
                            <span style="font-size:12px;">Add</span>
            </button>
    </div>
    <div class="row m-0 p-0 mt-4">
    <div class="col-md-10 mb-4">  
        <div class="table-responsive p-0 m-0"style="border-radius:5px;border:1px solid #ddd;margin-left:45px;">
                <table id="employee-table"style="border-radius:5px;">
                       <thead>
                            <tr>
                                <th style="border-right:1px solid #ddd;">#</th>
                                <th style="border-right:1px solid #ddd;">Employee</th>
                                <th style="border-right:1px solid #ddd;">From Date</th>
                                <th style="border-right:1px solid #ddd;">To Date</th>
                                <th style="border-right:1px solid #ddd;">Sunday</th>
                                <th style="border-right:1px solid #ddd;">Monday</th>
                                <th style="border-right:1px solid #ddd;">Tuesday</th>
                                <th style="border-right:1px solid #ddd;">Wednesday</th>
                                <th style="border-right:1px solid #ddd;">Thursday</th>
                                <th style="border-right:1px solid #ddd;">Friday</th>
                                <th style="border-right:1px solid #ddd;">Saturday</th>
                                <th style="border-right:1px solid #ddd;">Modified Date</th>
                                <th style="border-right:1px solid #ddd;">Actions</th>
                            </tr>
                        </thead>
                    <tbody>
                    
                    
                    @if ($employeeWeekdayChart->count() > 0)
                    
                  
                        
                   
                    @foreach ($employeeWeekdayChart as $employee)
                    <tr>

                            <td style="font-size:12px;">{{++$i}}.</td>
                            @php
                                $employeefirstName=App\Models\EmployeeDetails::where('emp_id',$employee->emp_id)->value('first_name');
                                $employeelastName=App\Models\EmployeeDetails::where('emp_id',$employee->emp_id)->value('last_name');
                            @endphp
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employeefirstName))}} {{ucwords(strtolower($employeelastName))}}({{$employee->emp_id}})</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($employee->from_date)->format('jS F,Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($employee->to_date)->format('jS F,Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->sunday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->monday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->tuesday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->wednesday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->thursday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->friday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($employee->saturday))}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($employee->created_at)->format('jS F,Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">
                                <button class="btn btn-primary btn-sm" title="View">
                                    <i class="ph ph-eye"wire:click="viewEmployeeWeekDetails('{{$employee->id}}')"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" title="Edit">
                                    <i class="ph ph-pencil"wire:click="editEmployeeWeekDetails('{{$employee->id}}')"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Delete">
                                    <i class="ph ph-trash"wire:click="openDeleteModal('{{$employee->id}}')"></i>
                                </button>
                            </td>
                           

                    </tr>

                    @endforeach
                    @else
                            <tr>
                                <td colspan="13" class="text-center">No Data Found</td>
                            </tr>
                    @endif
                    </tbody>
               </table>
               </div> 
            </div>
            </div>
            @if($totalPages > 1)
        <div class="mt-3 text-center">
            <!-- Previous Button -->
            <button 
                wire:click="previousPage" 
                class="btn btn-sm btn-secondary"
                style="{{ $currentPage != 1 ? 'background-color: #306cc6; border-color: #306cc6; color: #fff;' : 'background-color: lightgrey; border-color: lightgrey; color: #fff;' }}">
                Previous
            </button>

            <!-- Numbered Page Buttons -->
            @for($page = 1; $page <= $totalPages; $page++)
                <button 
                    wire:click="setPage({{ $page }})" 
                    class="btn btn-sm"
                    style="margin: 0 5px; background-color: {{ $currentPage == $page ? '#306cc6' : 'lightgrey' }}; border-color: {{ $currentPage == $page ? '#306cc6' : 'lightgrey' }}; color: #fff;">
                    {{ $page }}
                </button>
            @endfor

            <!-- Next Button -->
            <button 
                wire:click="nextPage" 
                class="btn btn-sm"
                style="{{ $currentPage == $totalPages ? 'background-color: lightgrey; border-color: lightgrey; color: #fff;' : 'background-color: #306cc6; border-color: #306cc6; color: #fff;' }}">
                Next
            </button>
        </div>
    @endif
               @if ($openViewPage==true)
               <div class="modal d-block" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <b>View WeekDay Chart</b>
                                </h5>
                                <button type="button" class="btn-close btn-primary" data-dismiss="modal"
                                    aria-label="Close" wire:click="closeWeekDayChart">
                                </button>
                            </div>
                            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                              
                                    <div class="row m-0 mt-3">
                                          <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Employee&nbsp;Name:<br /><span style="color: #000000;">{{ucwords(strtolower($employeeweekdayfirstname))}} {{ucwords(strtolower($employeeweekdaylastname))}}</span></div>

                                          <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Employee&nbsp;Id:<br /><span style="color: #000000;">{{$employeeweekdayid}}</span></div>
                                    </div>
                                    <div class="row m-0 mt-3">


                                         <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">From&nbsp;Date:<br /><span style="color: #000000;">{{\Carbon\Carbon::parse($employeeweekdayfromdate)->format('jS F,Y')}}</span></div>

                                         <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">To&nbsp;Date:<br /><span style="color: #000000;">{{\Carbon\Carbon::parse($employeeweekdaytodate)->format('jS F,Y')}}</span></div>

                                    </div>
                                    <div class="row m-0 mt-3">


                                         <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Sunday:<br /><span style="color: #000000;">{{$employeeweekdaysunday}}</span></div>

                                         <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Monday:<br /><span style="color: #000000;">{{$employeeweekdaymonday}}</span></div>


                                    </div>
                                    <div class="row m-0 mt-3">
                                        
                                           <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Tuesday:<br /><span style="color: #000000;">{{$employeeweekdaytuesday}}</span></div>

                                           <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Wednesday:<br /><span style="color: #000000;">{{$employeeweekdaywednesday}}</span></div>


                                         
                                    </div>
                                    <div class="row m-0 mt-3">
                                        
                                           <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Thursday:<br /><span style="color: #000000;">{{$employeeweekdaythursday}}</span></div>

                                           <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Friday:<br /><span style="color: #000000;">{{$employeeweekdayfriday}}</span></div>


                                         
                                    </div>
                                    <div class="row m-0 mt-3">
                                        
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Saturday:<br /><span style="color: #000000;">{{$employeeweekdaysaturday}}</span></div>


                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Modified Date:<br /><span style="color: #000000;">{{\Carbon\Carbon::parse($employeeweekdaymodifiedDate)->format('jS F,Y')}}</span></div>


                                      
                                 </div>
                                    
                                    <div style="display: flex; justify-content: center; margin-top: 20px;">
                                            <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79);"wire:click="closeWeekDayChart">Close</button>
                                    </div>
                               

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div>
               @endif  
               @if ($deleteModal==true)
               

                          <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                            <h5 class="modal-title" id="approveModalTitle" style="color:#778899;">Delete Confirmation</h5>
                                            <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeEmployeeWeekDayModal" style="background-color: #f5f5f5;font-size:12px;border-radius:20px;border:2px solid #778899;height:15px;width:15px;">
                                            </button>
                                        </div>
                                        <div class="modal-body" style="max-height:300px;overflow-y:auto;">
                                            <div style="display:flex;flex-direction:row;align-items: center; justify-content: center; text-align: center;">
                                               <img src="{{ asset('images/question-mark-image.png') }}"height="40" width="40"style="margin-top:-10px;"><p style="font-size:14px;padding-top:10px;">Do you want to delete the selected record?</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                                        <div style="display: flex; justify-content: center; gap: 10px; width: 100%; text-align: center;">
                                                                    <button type="button" class="approveBtn" wire:click="deleteEmployeeWeekDetails({{$idfordeletingrecord}})">Confirm</button>
                                                                    <button type="button" class="rejectBtn" wire:click="cancelEmployeeWeekDetails">Cancel</button>
                                                        </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"style="background-color: rgba(0, 0, 0, 0.2);"></div>

               @endif  
               @if ($openEditPage==true)
               <div class="modal d-block" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><b>Edit WeekDay Chart</b></h5>
                                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="cancelEdit"></button>
                                </div>
                                <div class="modal-body" style="max-height:300px;overflow-y:auto">
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Employee:<br />
                                            
                                            <div class="form-group">
                                                <div class="employee-container">
                                                        <span id="employeeName">{{$editemployeeweekdayfirstname}} {{$editemployeeweekdaylastname}}</span>
                                                        <span id="employeeId">({{$editemployeeweekdayid}})</span>
                                                </div>
                                            </div>
                                           
                                        </div>
                                      
                                    </div>
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            From&nbsp;Date:<br />
                                            
                                                <input type="date" wire:model="editemployeeweekdayfromdate" class="form-control form-control-sm">
                                            
                                        </div>
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            To&nbsp;Date:<br />
                                            
                                                <input type="date" wire:model="editemployeeweekdaytodate" class="form-control form-control-sm">
                                            
                                        </div>
                                    </div>
                                    <!-- Repeat similar conditional blocks for other fields -->
                                    <div class="row m-0 mt-3">
                                    <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                                    Sunday:<br />
                                                    <select wire:model="editemployeeweekdaysunday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaysunday}}">{{$editemployeeweekdaysunday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                                    <!-- Debug output -->
                                                    
                                                </div>
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Monday:<br />
                                           
                                                <select wire:model="editemployeeweekdaymonday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaymonday}}">{{$editemployeeweekdaymonday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                            
                                        </div>
                                    </div>
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Tuesday:<br />
                                            
                                            <select wire:model="editemployeeweekdaytuesday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaytuesday}}">{{$editemployeeweekdaytuesday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                        </div>
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Wednesday:<br />
                                           
                                            <select wire:model="editemployeeweekdaywednesday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaywednesday}}">{{$editemployeeweekdaywednesday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Thursday:<br />
                                            
                                            <select wire:model="editemployeeweekdaythursday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaythursday}}">{{$editemployeeweekdaythursday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                        </div>
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Friday:<br />
                                           
                                            <select wire:model="editemployeeweekdayfriday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdayfriday}}">{{$editemployeeweekdayfriday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Saturday:<br />
                                            
                                            <select wire:model="editemployeeweekdaysaturday" class="form-control form-control-sm">
                                                        <option value="{{$editemployeeweekdaysaturday}}">{{$editemployeeweekdaysaturday}}</option>
                                                        <option value="off">Off</option>
                                                        <option value="default">default</option>
                                                        <option value="full">Full</option>
                                                        <option value="session-1">Session 1</option>
                                                        <option value="session-2">Session 2</option>
                                                    </select>
                                        </div>
                                        
                                    </div>
                                    <!-- Continue for Tuesday, Wednesday, Thursday, Friday, Saturday -->
                                    <div class="row m-0 mt-3">
                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">
                                            Modified Date:<br />
                                            
                                                <input type="date" wire:model="editemployeeweekdaymodifiedDate" class="form-control form-control-sm">
                                            
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: center; margin-top: 20px;">
                                        
                                            <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79); margin-right:10px;" wire:click="saveChanges">Save</button>
                                            <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79);" wire:click="cancelEdit">Cancel</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show blurred-backdrop"></div>
               
               @endif
</div>
