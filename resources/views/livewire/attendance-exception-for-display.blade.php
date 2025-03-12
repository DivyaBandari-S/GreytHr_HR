<div>
    <style>
       
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
                   
                    <p>The <span style="font-weight:bold;">Attendance Exception</span> allows you to add an exception in attendance. This is required when the employee needs to allow an exception to attendance. <br/>Adding an exception is helpful if the employee cannot swipe attendance conventionally due to official reasons. Click <span style="font-weight:bold;">Add Exception</span> to create an exception. </p>
                    <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
    </div>
   
    <button style="border:1px solid blue;margin-right:40px;margin-top:-10px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="addException">
        <span style="font-size:12px;">Add Exception</span>
    </button>
    <div class="row m-0 p-0">
       <div class="col-md-10 mb-6"style="margin-top:30px;">
        <div class="table-container table-responsive p-0 m-0"style="border-radius:5px;border:1px solid #ddd;">  
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
                    <td style="white-space:nowrap;font-size:12px;">{{++$i}}.</td>
                    <td style="white-space:nowrap;font-size:12px;">{{$a1->emp_id}}</td>
                    <td style="white-space:nowrap;font-size:12px;">{{ucwords(strtolower($a1->employee->first_name))}} {{ucwords(strtolower($a1->employee->last_name))}}</td>
                    <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($a1->from_date)->format('jS F Y')}}</td>
                    <td style="white-space:nowrap;font-size:12px;">{{\Carbon\Carbon::parse($a1->to_date)->format('jS F Y')}}</td>
                    <td style="white-space:nowrap;font-size:12px;">{{$a1->status}}</td>
                    <td style="white-space:nowrap;">
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
     </div>
   

  
 
</div>
 