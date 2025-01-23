<div>
    <style>
       
            table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
                   
                    <p>The <span style="font-weight:bold;">Attendance Exception</span> allows you to add an exception in attendance. This is required when the employee needs to allow an exception to attendance. <br/>Adding an exception is helpful if the employee cannot swipe attendance conventionally due to official reasons. Click <span style="font-weight:bold;">Add Exception</span> to create an exception. </p>
                    <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
    </div>
   
    <button style="border:1px solid blue;margin-right:40px;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="addException">
        <span style="font-size:12px;">Add Exception</span>
    </button>
   
        <div class="table-container"style="max-height:300px;overflow-y:auto;width:100%; margin:0;padding:0 10px;">  
      <table id="employee-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Status</th>
                <th></th>
               
            </tr>
        </thead>
        <tbody>
           @if (count($attendanceExceptions)>0)
           
          
           @foreach ($attendanceExceptions as $a1)

            
         
            <tr>
                <td>{{++$i}}.</td>
                <td>{{$a1->emp_id}}</td>
                <td>{{ucwords(strtolower($a1->employee->first_name))}} {{ucwords(strtolower($a1->employee->last_name))}}</td>
                <td>{{\Carbon\Carbon::parse($a1->from_date)->format('jS F Y')}}</td>
                <td>{{\Carbon\Carbon::parse($a1->to_date)->format('jS F Y')}}</td>
                <td>{{$a1->status}}</td>
                <td>
                        <a href="#" wire:click="editAttendanceException({{ $a1->id }})" class="text-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!-- Delete Icon -->
                        <a href="#"class="text-danger"wire:click="openDeleteModal({{  $a1->id  }})"title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        @if($deleteModal==true)
                            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                            <h5 class="modal-title" id="approveModalTitle" style="color:#778899;">Delete Confirmation</h5>
                                            <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeAttendanceExceptionModal" style="background-color: #f5f5f5;font-size:12px;border-radius:20px;border:2px solid #778899;height:15px;width:15px;">
                                            </button>
                                        </div>
                                        <div class="modal-body" style="max-height:300px;overflow-y:auto;">
                                            <div style="display:flex;flex-direction:row;align-items: center; justify-content: center; text-align: center;">
                                               <img src="{{ asset('images/question-mark-image.png') }}"height="40" width="40"style="margin-top:-10px;"><p style="font-size:14px;padding-top:10px;">Do you want to delete the selected record?</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                                        <div style="display: flex; justify-content: center; gap: 10px; width: 100%; text-align: center;">
                                                                    <button type="button" class="approveBtn" wire:click="deleteAttendanceException({{$idfordeletingrecord}})">Confirm</button>
                                                                    <button type="button" class="rejectBtn" wire:click="closeAttendanceExceptionModal">Cancel</button>
                                                        </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show blurred-backdrop"style="background-color: rgba(0, 0, 0, 0.2);"></div>


                        @endif
                </td>
            </tr>
         
            @endforeach

            @else
                <td colspan="12"style="text-align:center;">Record Not Found</td>
            <!-- Add more rows as needed -->
            @endif 
        </tbody>
     </table>
    </div>
 
</div>
 