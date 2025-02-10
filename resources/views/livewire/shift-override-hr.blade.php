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
  <div>
            <div class="attendance-overview-help"style="position: relative; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
                    
                    <p>The <span style="font-weight:bold;">Shift Override</span> page enables you to override the shift details of one or more employees. Click <span style="font-weight:bold;">Override</span> to enter the details</p>
                    <p>To view frequently asked questions <span style="color: #1fb6ff;cursor:pointer;"> click here</span>.</p>
                                    
                                    <span class="hide-attendance-help" style="display: block; text-align: center; margin-top: 40px; cursor: pointer; color: blue; font-weight: bold;">
                                                 Hide Help
                                    </span>
            </div>
    </div>                
    <button style="border:1px solid blue;margin-right:40px;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="createShiftOverride">
        <span style="font-size:12px;">Override</span>
    </button>
    <div class="row p-0 m-0">
        <div class="col-md-10 mt-6">
            <div class="table-container table-responsive p-0 m-0"style="max-height:300px;overflow-y:auto;width:100%; margin:0;padding:0 10px;border-radius:5px;border:1px solid #ddd;">  
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
                
                    
                    
                    

                    @if (count($shiftoverride)>0)
                    
                            
                    @foreach ($shiftoverride as $s1)
                    <tr>
                            <td style="font-size:12px;white-space:nowrap;">{{++$i}}.</td>
                            <td style="font-size:12px;white-space:nowrap;">{{$s1->emp_id}}</td>
                            <td style="font-size:12px;white-space:nowrap;">{{ucwords(strtolower($s1->employee->first_name))}} {{ucwords(strtolower($s1->employee->last_name))}}</td>
                            <td style="font-size:12px;white-space:nowrap;">{{\Carbon\Carbon::parse($s1->from_date)->format('jS F Y')}}</td>
                            <td style="font-size:12px;white-space:nowrap;">{{\Carbon\Carbon::parse($s1->to_date)->format('jS F Y')}}</td>
                            <td style="font-size:12px;white-space:nowrap;">{{$s1->shift_type}}</td>
                            <td style="font-size:12px;white-space:nowrap;">
                                    <a href="#" class="text-primary" title="Edit">
                                        <i class="fas fa-edit"wire:click="editShiftOverride({{$s1->id}})"></i>
                                    </a>
                                    
                                    <!-- Delete Icon -->
                                    <a href="#"class="text-danger"title="Delete"wire:click="openDeleteModal({{$s1->id}})">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    @if($deleteModal==true)
                                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                                        <h5 class="modal-title" id="approveModalTitle" style="color:#778899;">Delete Confirmation</h5>
                                                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeShiftOverrideModal" style="background-color: #f5f5f5;font-size:12px;border-radius:20px;border:2px solid #778899;height:15px;width:15px;">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="max-height:300px;overflow-y:auto;">
                                                        <div style="display:flex;flex-direction:row;align-items: center; justify-content: center; text-align: center;">
                                                        <img src="{{ asset('images/question-mark-image.png') }}"height="40" width="40"style="margin-top:-10px;"><p style="font-size:14px;padding-top:10px;">Do you want to delete the selected record?</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                                    <div style="display: flex; justify-content: center; gap: 10px; width: 100%; text-align: center;">
                                                                                <button type="button" class="approveBtn" wire:click="deleteShiftOverride({{$idfordeletingrecord}})">Confirm</button>
                                                                                <button type="button" class="rejectBtn" wire:click="closeShiftOverrideModal">Cancel</button>
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
                    
                    @endif
                    
                    </tbody>
                </table>
            </div>
        </div>    
    </div>
</div>
