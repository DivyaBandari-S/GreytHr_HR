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
        .add-employee-button{
            border:1px solid  #306cc6;
            background-color:#fff;
            color:#306cc6;
            border-radius:8px;
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 16px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .button-container {
            display: flex;
            justify-content: flex-end; /* Aligns buttons to the start */
            float: right;
            width: 50%; /* Adjust this value to change the container width */
            margin: 20px auto; /* Centers the container and adds some margin */
           
        }
        .button-container button {
            border-radius:8px;
            padding: 5px 20px; /* Adds padding to the buttons */
            font-size: 16px; /* Adjusts the font size */
            margin-right: 10px; /* Adjusts space between buttons */
        }
        .button-container button:last-child {
            margin-right: 0; /* Removes margin from the last button */
        }
        .dropdown-for-employee{
    position: relative; /* Position relative for dropdown positioning */
    display: inline-block; /* Align with other elements */
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
.arrow-for-employee {
    margin-left: 10px; /* Space between text and arrow */
    width: 0;
    height: 0;
    border-left: 5px solid transparent; /* Left side transparent */
    border-right: 5px solid transparent; /* Right side transparent */
    border-top: 5px solid black; /* Arrow color */
}
.search-input-employee-swipes {
    border: none;
    position: relative;
}

.search-input-employee-swipes input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
    font-size: 13px;
    background-color: #fff;
}
.search-icon-employee-swipes {
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    color: var(--label-color);
}

.search-icon-employee-swipes::before {
    font-size: 16px;
}
</style>   
<div class="button-container">
       
       <a href="{{ route('add-fine-page') }}" class="add-employee-button"><i class="fa-sharp fa-solid fa-plus"></i>Add Fine</a>

    </div>
    <div style="margin-top:40px;display:flex;flex-direction:row;gap:5px;margin-left:15px;">

             <!-- Dropdown trigger -->
             <div class="dropdown-for-employee">
                  <button class="dropdown-button-for-employee"wire:click="togglePicker"><span style="font-size:12px;">Fine Realized: {{ ucfirst($selectedOption) }}</span> <span class="arrow-for-employee"></span></button>
                  @if($showPicker==true)
                  <div class="dropdown-content-for-employee">
                      <div class="scrollApplyingTO absolute mt-2 bg-white shadow-md rounded-lg overflow-hidden">
                              <button wire:click="closePicker" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 border border-gray-300 rounded"style="margin-left:400px;margin-top:15px;">
                                     &times;
                              </button>
                          <div class="px-4 py-2">
                              
                              <label for="startDate">Between:</label>
                              <input type="date" id="startDate" wire:model="startDate" class="border rounded w-full py-2 px-3">
                              <label for="endDate">And:</label>
                              <input type="date" id="endDate" wire:model="endDate" class="border rounded w-full py-2 px-3">
                              <button wire:click="applyDateRange"  class="bg-white hover:bg-blue-100 text-blue-700 font-bold py-2 px-4 border border-blue-500 rounded mt-4">
                                  OK
                              </button>
                             
                          </div>
                      </div>
                  </div>
                  @endif
              </div>

                  
              <div class="dropdown-for-employee">
                  <button class="dropdown-button-for-employee"wire:click="showPickerForEmployee"><span style="font-size:12px;">Employee: {{ ucfirst($selectedOptionForEmployee) }}</span> <span class="arrow-for-employee"></span></button>
                  @if($employeePicker==true)
                  <div class="dropdown-content-for-employee">

                          <div class="absolute mt-2 bg-white shadow-md rounded-lg overflow-hidden">  
                                      <div class="px-4 py-2">
                                                  <div class="search-input-employee-swipes">
                                                          <div class="search-container-for-employee-swipes" style="position: relative;">
                                                              <i class="fa fa-search search-icon-employee-swipes" aria-hidden="true" style="cursor:pointer;" wire:click="searchEmployee"></i>
                                                              <input wire:model="search" type="text" placeholder="Search Employee" class="search-text">

                                                          </div>

                                                  </div>
                                                  <button wire:click="closeSearchDropdown" class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 border border-gray-300 rounded">
                                                       &times;
                                                 </button>
                                      </div>
                           </div> 
                  </div>
                  @endif
              </div>
                 
    </div> 
<div class="table-container"style="max-height:300px;overflow-y:auto;width:100%; margin:0;padding:0 10px;">  
      <table id="employee-table">
        <thead style="position:sticky;top:0;">
            <tr>
                <th>#</th>
                <th>Date of Offence</th>
                <th>Employee No</th>
                <th>Employee Name</th>
                <th>Act/Omission</th>
                <th>Fine Amount</th>
                <th>Fine Realized</th>
                <th>Remarks</th>
                <th>Edit/Delete</th>
            </tr>
        </thead>
        <tbody>
          
            @if (count($fineAndDamage)>0)
            @php
               $i=0;
            @endphp
            @foreach ($fineAndDamage as $fad)
            @php
               $employeefirstName=App\Models\EmployeeDetails::where('emp_id',$fad->emp_id)->value('first_name');
               $employeelastName=App\Models\EmployeeDetails::where('emp_id',$fad->emp_id)->value('last_name');
            @endphp
            <tr>
                <td style="font-size:12px;">{{ ++$i }}.</td>
                <td style="font-size:12px;">{{ \Carbon\Carbon::parse($fad->offence_date)->format('jS F,Y') }}</td>
                <td style="text-decoration:underline;font-size:12px;">{{ $fad->emp_id }}</td>
                <td style="white-space:nowrap;font-size:12px;">{{ ucwords(strtolower($employeefirstName)) }} {{ ucwords(strtolower($employeelastName)) }}</td>
                <td style="font-size:12px;">
                   {{ $fad->act_or_omission }}
                </td>
                <td style="font-size:12px;"> {{ \App\Helpers\FormatHelper::formatAmount($fad->amount_of_fine) }}</td>
                <td style="font-size:12px;">{{ \Carbon\Carbon::parse($fad->fine_realized_date)->format('jS F,Y') }}</td>
                <td style="font-size:12px;">{{ $fad->remarks }}</td>
                <td style="font-size:12px;">

                       <a href="{{ route('add-fine-page', ['id' => $fad->id, 'viewMode' => true]) }}" class="edit-employee-button">
                            <i class="fa-sharp fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('add-fine-page', ['id' => $fad->id]) }}" class="edit-employee-button">
                            <i class="fa-sharp fa-solid fa-edit"></i>
                        </a>
                        <a href="#"class="text-danger"wire:click="openDeleteModal({{  $fad->id  }})"title="Delete">
                                <i class="fas fa-trash-alt"></i>
                        </a>  
                        @if($deleteModal==true)
                                <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #f5f5f5; height: 50px">
                                                <h5 class="modal-title" id="approveModalTitle" style="color:#778899;">Delete Confirmation</h5>
                                                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeFineAndDamageRecord" style="background-color: #f5f5f5;font-size:12px;border-radius:20px;border:2px solid #778899;height:15px;width:15px;">
                                                </button>
                                            </div>
                                            <div class="modal-body" style="max-height:300px;overflow-y:auto;">
                                                <div style="display:flex;flex-direction:row;align-items: center; justify-content: center; text-align: center;">
                                                    <img src="{{ asset('images/question-mark-image.png') }}"height="40" width="40"style="margin-top:-10px;"><p style="font-size:14px;padding-top:10px;">Do you want to delete the selected record?</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                            <div style="display: flex; justify-content: center; gap: 10px; width: 100%; text-align: center;">
                                                                        <button type="button" class="approveBtn" wire:click="deleteFineAndDamageRecord({{$idfordeletingrecord}})">Confirm</button>
                                                                        <button type="button" class="rejectBtn" wire:click="closeFineAndDamageRecord">Cancel</button>
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
              <tr>
                  <td colspan="12"style="text-align:center;">No Details found</td>
              </tr>
            @endif
          
            <!-- Add more rows as needed -->
        </tbody>
     </table>
     </div>
</div>
