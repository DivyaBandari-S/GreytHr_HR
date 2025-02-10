<div>
    <style>
        .hide-attendance-help {
    margin-top:40px;
    position: absolute;
    bottom: 100px;
    right: 10px;
    color: #0000FF;
    font-weight:500;
    cursor: pointer;
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
    </style>    
    @php
        $i=0;
    @endphp
           <div class="attendance-overview-help">
                    
                    <p>The <span style="font-weight:bold;">Lock Configuration</span> page allows you to lock attendance-related activities such as mass override, leave apply, and so on. For example, you may prevent <br/> an application for leave between the 5th and 7th of March, but the actual lock comes into effect only from the 8th of August. You have the liberty to select any <br/>number of category types and lock them for a period of time. </p>
                    <p>Explore  greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
            </div>
          
                    <button style="border:1px solid blue;margin-right:40px;margin-top:-10px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="addLockConfiguration">
                            <span style="font-size:12px;">Add Lock Configuration</span>
                    </button>
                   
            <div class="row m-0 p-0 mt-4">
            <div class="col-md-14"style="margin-top:50px;">    
            <div class="table-container table-responsive p-0 m-0"style="border-radius:5px;border:1px solid #ddd;margin-left:45px;">  
                <table id="employee-table">
                       <thead>
                            <tr>
                                <th>#</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Category</th>
                                <th>Effective Date</th>
                                <th>Lock Criteria</th>
                                <th>Criteria Name</th>
                                <th>Updated By</th>
                                <th>Updated Date</th>
                            </tr>
                        </thead>
                    <tbody>
                    
                    
                    
                    
                    
                        
                    @foreach ($lockConfiguration as $l1)
                    
                    <tr>

                            <td style="font-size:12px;">{{++$i}}.</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($l1->from_date)->format('jS M, Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($l1->to_date)->format('jS M, Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">
                                @if($l1->category=='mass_override')
                                   Mass override
                                @elseif($l1->category=='attendance_process')
                                   Attendance Process
                                @else   
                                <span style="text-align:center;font-size:12px;">-</span>
                                @endif 
                                   
                            </td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($l1->effective_date)->format('jS M, Y')}}</td>
                            <td style="white-space:nowrap;font-size:12px;">
                                @if($l1->lock_criteria=='employeeFilter')
                                   Employee Filter
                                @elseif($l1->lock_criteria=='attendanceCycle')
                                   Attendance Cycle
                                @else   
                                 <span style="text-align:center;font-size:12px;">-</span>
                                @endif      
                            </td>
                            <td style="white-space:nowrap;font-size:12px;">
                            
                                @if($l1->criteria_name=='all_employees')
                                   All Employees
                                @elseif($l1->criteria_name=='trainee_employees')
                                   Trainee Employees   
                                @elseif($l1->criteria_name=='default_attendance')
                                   Default Attendance
                                @else   
                                    <span style="text-align:center;">-</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;font-size:12px;">{{$l1->updated_by}}</td>
                            <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($l1->updated_lock_at)->format('jS M, Y')}}</td>
                        </tr>
                    @endforeach
                        
                    
                        

                        
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
                style="{{ $currentPage == $totalPages? 'background-color: lightgrey; border-color: lightgrey; color: #fff;' : 'background-color: #306cc6; border-color: #306cc6; color: #fff;' }}">
                Next
            </button>
        </div>
    @endif
</div>
