<div class="position-relative">
    <div wire:loading
        wire:target="open,file_path,submitHR,Catalog,activeTab,closeImageDialog,downloadImage,showImage,file_paths">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>

        </div>
    </div>
    <div class="row justify-content-center mt-2"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
    The Monitor: Help Desk page enables you to view the workflow of all Help Desk transactions that are underway or are completed. Use the drop-downs to sort out the employees. Click Detailed View to get additional information on a transaction. Click Export Excel to download the Help Desk data in an Excel file.
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>


                    @auth('hr')
                 
                    <div class="nav nav-pills" style="display: flex; justify-content: center; width: 100%; padding-right: 10px; margin-top: 5px;">
    <button wire:click="$set('activeTab', 'active')" 
            class="nav-link @if($activeTab === 'active') active @else @endif" 
            style="border-radius: 5px 0 0 5px; margin-right: 5px; 
                   background-color: @if($activeTab === 'active') rgb(2, 17, 79) @else #f0f0f0 @endif; 
                   border-right: 2px solid #ddd;">
        Active
    </button>
    <button wire:click="$set('activeTab', 'pending')" 
            class="nav-link @if($activeTab === 'pending') active @else @endif" 
            style="border-radius: 0; margin-right: 5px; 
                   background-color: @if($activeTab === 'pending') rgb(2, 17, 79) @else #f0f0f0 @endif; 
                   border-right: 2px solid #ddd;">
        Pending
    </button>
    <button wire:click="$set('activeTab', 'closed')" 
            class="nav-link @if($activeTab === 'closed') active @else @endif" 
            style="border-radius: 0 5px 5px 0; 
                   background-color: @if($activeTab === 'closed') rgb(2, 17, 79) @else #f0f0f0 @endif;">
        Closed
    </button>
</div>


<div class="col-md-10 " >
        <div class="newReq mt-2" style="align-items:end">

    <button wire:click="exportToExcel" class="cancel-btn" style="margin-left:20px">Export to Excel</button>
        </div>
    </div>





    @if($activeTab == "active")


        @if($forHR->where('status_code', 8)->count() > 0)
            @foreach ($forHR->where('status_code', 8) as $record)
            <div class="container d-flex justify-content-center align-items-center mt-2">
    <div class="card" style="width:80%;margin-top:30px;border-radius:5px;">
        <div class="card-header" style="background-color:#dbf0f9; color: black;border: 1px solid #ddd;border-radius: 5px;height:auto;cursor:pointer" data-bs-toggle="collapse" data-bs-target="#collapse-body-{{ $record->id }}" aria-expanded="false" aria-controls="collapse-body-{{ $record->id }}">
         <div class="col-md-5" style="display:flex">
        
    @if (auth('hr')->check())
        @php
            // Get the profile image for the employee in this record
            $profileImage = asset('images/user.jpg'); // Default image

            if ($record->emp->image && $record->emp->image !== 'null') {
                $profileImage = 'data:image/jpeg;base64,' . $record->emp->image;
            } elseif ($record->emp->gender === 'Male') {
                $profileImage = asset('images/male-default.png');
            } elseif ($record->emp->gender === 'Female') {
                $profileImage = asset('images/female-default.jpg');
            }
        @endphp

        <img class="navProfileImgFeeds rounded-circle" src="{{ $profileImage }}" alt="Profile Image" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;">
        
        <div style="display: flex; flex-direction: column; font-size: 16px;">
            <span style="font-weight: 500;">{{ $record->emp->first_name }} {{ $record->emp->last_name }}</span>
            <span style="font-size: 12px;">#{{ $record->emp_id }}</span>
            <p style="font-size:14px;margin-top:8px">Currently with {{ $record->emp->first_name }} {{ $record->emp->last_name }}</p>
        </div>

    @else
        <p>User is not authenticated.</p>
    @endif
</div>

    <br>     
                
            
    
   
            <p style="font-size: 12px; text-transform: capitalize;">
                Request ID: {{ $record->request_id }}  
              
            </p>
            <div style="display: flex; flex-direction: row; font-size: 12px;text-align:end;">
            <span >{{ \Carbon\Carbon::parse($record->updated_at)->diffForHumans() }}</span>

            <span style="font-size: 12px;margin-left:2px"><button style="background:darkgray;color:white;font-weight:500;border:none;border-radius:3px">{{ $record->category }}</button></span>
            
        </div>
        </div>

        <div id="collapse-body-{{ $record->id }}" class="collapse">
            <div class="card-body" style="padding: 10px;font-size:14px">
                <p><strong>Subject:</strong> {{ $record->subject }}</p>
                <p><strong>Description:</strong> {{ $record->description }}</p>

                <!-- Attachments Section -->
                @if ($record->file_path)
                    <p><strong>Attachments:</strong> <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF;">View File</a></p>
                @else
                    <p><strong>Attachments:</strong> N/A</p>
                @endif

                <!-- Expandable Comments Section -->
                <div>
                 
                <div class="existing-comments">
    <strong>Comments:</strong>
    @php
        $comments = $record->active_comment ? json_decode($record->active_comment, true) : [];
        $user = auth()->user(); // Get logged-in user to show emp_id dynamically
    @endphp

@if(!empty($comments))
    @foreach($comments as $comment)
        @php
            // Fetch user details based on logged-in emp_id
            $employeeDetails = \App\Models\EmployeeDetails::where('emp_id', auth()->user()->emp_id)->first();
        @endphp

@php
    // Fetch user details based on logged-in emp_id
    $employeeDetails = \App\Models\EmployeeDetails::where('emp_id', auth()->user()->emp_id)->first();

    // Default profile image
    $profileImage = asset('images/user.jpg');

    // Check if the employee has a valid profile image stored in the database
    if (!empty($employeeDetails->image) && $employeeDetails->image !== 'null') {
        $profileImage = 'data:image/jpeg;base64,' . $employeeDetails->image;
    } elseif (!empty($employeeDetails->gender)) {
        // Assign default gender-based profile images
        $profileImage = $employeeDetails->gender === 'Male' 
                        ? asset('images/male-default.png') 
                        : asset('images/female-default.jpg');
    }
@endphp


<!-- Employee Comment Section -->
<div class="m" style="border-bottom: 1px solid #ddd; padding: 5px; background: lightgray; height: auto; display: flex; align-items: center;">
    <!-- Profile Picture -->
    <img class="navProfileImgFeeds rounded-circle" 
         src="{{ $profileImage }}" 
         alt="Profile Image" 
         style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; cursor: pointer; margin-right: 10px;">

    <!-- Employee Details -->
    <p style="margin: 0; font-size: 14px;font-weight:500">
        <strong>{{ $employeeDetails->first_name ?? 'Unknown' }} 
                {{ $employeeDetails->last_name ?? '' }} 
                (#{{ $employeeDetails->emp_id ?? 'N/A' }})</strong> - 
                <small>{{ \Carbon\Carbon::parse($employeeDetails->updated_at)->diffForHumans() }}</small>
    </p>
</div>


        <p style="padding: 5px 10px; border-left: 3px solid #ddd; margin-bottom: 5px;">
            {{ $comment }}
        </p>
    @endforeach
@else
    <p>No comments available.</p>
@endif

</div>

                <textarea rows="3" placeholder="Add a comment..." class="form-control" wire:model="commentText"></textarea>
    <button wire:click="postComment({{ $record->id }})" class="cancel-btn mt-2">Post Comment</button>

                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                    <button wire:click="openForDesks('{{ $record->id }}')" class="submit-btn">Close</button>
                    <button wire:click="pendingForDesks('{{ $record->id }}')" class="submit-btn">Pending</button>
                </div>
            </div>
        </div>
    </div>
</div>


            @endforeach
        @else
            <p>No records found with status code 8</p>
        @endif
   
@endif


    @if ($activeTab == "pending")
    <div class="card-body" style="background-color:white;width:95%;margin-top:30px;border-radius:5px;max-height:400px;height:400px;overflow-y:auto">

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: rgb(2, 17, 79); color: white;">
                    <th style="padding: 10px;font-size:12px;text-align:center;width:120px">Request Raised By</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Category</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Subject</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Description</th>
              
                    <th style="padding: 10px;font-size:12px;text-align:center">Attach Files</th>
              
                    <th style="padding: 10px;font-size:12px;text-align:center">Priority</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($forHR->where('status', 'Pending')->count() > 0)
                @foreach ($forHR->where('status', 'Pending') as $record)
                <tr>
                    <td style="padding: 10px;font-size:12px;text-align:center;width:120px;text-transform: capitalize;">{{ $record->emp->first_name }} {{ $record->emp->last_name }} <br> <strong style="font-size: 10px;">({{$record->emp_id}})</strong></td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;"> {{ $record->category ?? 'N/A' }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->subject ?? 'N/A'  }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->description ?? 'N/A'  }}</td>

                    <td style="padding: 10px;font-size:12px;text-align:center">
                        @if ($record->file_path)
                        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF;text-transform: capitalize;">View File</a>
                        @else
                        N/A
                        @endif
                    </td>

                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->priority ?? 'N/A' }}</td>
                    <td style="padding: 5px; font-size: 12px; text-align: center;">
                        <div class="row" style="display: flex; justify-content: space-between;">
                            <button wire:click="openForDesks('{{$record->id}}')" style="background-color: rgb(2, 17, 79); color: white; border-radius: 3px; padding: 5px;">Close</button>
                        </div>

                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8" style="text-align: center;font-size:12px">Pending records not found</td>
                </tr>
                @endif

            </tbody>
        </table>

    </div>
    @endif

    @if ($activeTab == "closed")
    <div class="card-body" style="background-color:white;width:95%;margin-top:30px;border-radius:5px;max-height:400px;height:400px;overflow-y:auto">

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: rgb(2, 17, 79); color: white;">
                    <th style="padding: 10px;font-size:12px;text-align:center;width:120px">Request Raised By</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Category</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Subject</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Description</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Distributor</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Mobile</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">MailBox</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Attach Files</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">CC To</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Priority</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Select Equipment</th>
                    <th style="padding: 10px;font-size:12px;text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @if($forHR->where('status', 'Completed')->count() > 0)
                @foreach ($forHR->where('status', 'Completed') as $record)
                <tr>
                    <td style="padding: 10px;font-size:12px;text-align:center;width:120px;text-transform: capitalize;">{{ $record->emp->first_name }} {{ $record->emp->last_name }} <br> <strong style="font-size: 10px;">({{$record->emp_id}})</strong></td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->category }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->subject }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->description }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->distributor_name}}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->mobile }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->mail }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center">
                        @if ($record->file_path)
                        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF;text-transform: capitalize;">View File</a>
                        @else
                        N/A
                        @endif
                    </td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->cc_to }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->priority }}</td>
                    <td style="padding: 10px;font-size:12px;text-align:center;text-transform: capitalize;">{{ $record->selected_equipment ??'N/A' }}</td>

                    <td style="padding: 5px; font-size: 12px; text-align: center">

                        <div class="row" style="display: flex; justify-content: space-between;">
                            <button wire:click="closeForDesks('{{$record->id}}')" style="background-color: rgb(2, 17, 79); color: white; border-radius: 5px; padding: 5px;">Open</button>
                        </div>

                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8" style="text-align: center;font-size:12px">Closed records not found</td>
                </tr>
                @endif

            </tbody>
        </table>

    </div>
    @endif
    @endauth

</div>