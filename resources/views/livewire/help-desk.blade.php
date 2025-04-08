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
                        <div class="col-md-10 custom-container d-flex flex-column">
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
            class="nav-link {{ $activeTab === 'active' ? 'active' : '' }}" 
            style="border-radius: 5px 0 0 5px;  
                   background-color: {{ $activeTab === 'active' ? 'rgb(2, 17, 79)' : '#f0f0f0' }}; 
                   border-right: 2px solid #ddd;">
        Active
    </button>
    <button wire:click="$set('activeTab', 'pending')" 
            class="nav-link {{ $activeTab === 'pending' ? 'active' : '' }}" 
            style="border-radius: 0; ; 
                   background-color: {{ $activeTab === 'pending' ? 'rgb(2, 17, 79)' : '#f0f0f0' }}; 
                   border-right: 2px solid #ddd;">
        Pending
    </button>
    <button wire:click="$set('activeTab', 'closed')" 
            class="nav-link {{ $activeTab === 'closed' ? 'active' : '' }}" 
            style="border-radius: 0 5px 5px 0; 
                   background-color: {{ $activeTab === 'closed' ? 'rgb(2, 17, 79)' : '#f0f0f0' }};">
        Closed
    </button>
</div>









    @if($activeTab == "active")


    @if($forHR->whereIn('status_code', [8, 10])->count() > 0)
    
    @foreach ($forHR->whereIn('status_code', [8, 10]) as $record)
    <div class="col-md-10 " >
        <div class="newReq mt-2" style="align-items:end">

    <button wire:click="exportToExcel" class="cancel-btn" style="margin-left:20px">Export to Excel</button>
        </div>
    </div>
    
            <div class="container d-flex justify-content-center align-items-center mt-2">
    <div class="card" style="width:80%;margin-top:30px;">
        <div class="card-header" style="background-color:#dbf0f9; color: black;border: 1px solid #ddd;height:auto;cursor:pointer" data-bs-toggle="collapse" data-bs-target="#collapse-body-{{ $record->id }}" aria-expanded="false" aria-controls="collapse-body-{{ $record->id }}">
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

        <img class="navProfileImgFeeds " src="{{ $profileImage }}" alt="Profile Image" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;">
        
        <div style="display: flex; flex-direction: column; font-size: 14px;padding-left:5px">
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

        <div id="collapse-body-{{ $record->id }}" class="collapse" style="padding: 10px;">
        <div class="card-body" style="padding: 10px;font-size:14px">
         <p ><strong >Subject:</strong>{{ $record->subject }}</p>
<p><strong >Description:</strong> {{ $record->description }}</p>

<!-- Display Attachments Only If They Exist -->
@if (!empty($record->file_path))
    <p><strong style="font-weight: 500;">Attachments:</strong> 
        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF; font-weight: 500;">
            View File
        </a>
    </p>
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
    <img class="navProfileImgFeeds " 
         src="{{ $profileImage }}" 
         alt="Profile Image" 
         style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; cursor: pointer; margin-right: 10px;">

    <!-- Employee Details -->
    <p style="margin: 0; font-size: 14px;font-weight:500">
        <strong>{{ $employeeDetails->first_name ?? 'Unknown' }} 
                {{ $employeeDetails->last_name ?? '' }} 
                (#{{ $employeeDetails->emp_id ?? 'N/A' }})</strong> - 
                <small>{{ \Carbon\Carbon::parse($record->updated_at)->shortRelativeDiffForHumans() }}</small>

    </p>
</div>


        <p style="padding: 5px 10px; border-left: 3px solid #ddd; margin-bottom: 5px;">
     
        <p>{{ is_array($comment) ? ($comment['text'] ?? 'Invalid Comment') : $comment }}</p>

        </p>
    @endforeach

@endif

</div>

                <textarea rows="3" placeholder="Add a comment..." class="form-control" wire:model="commentText"></textarea>
          
                @error('commentText') 
        <span class="text-danger">{{ $message }}</span>
    @enderror 
    <br>
    <button wire:click="postComment({{ $record->id }})" class="cancel-btn mt-2">Post Comment</button>

                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                <button wire:click="pendingForDesks('{{ $record->id }}')" class="submit-btn">Pending</button>

                    <button wire:click="openForDesks('{{ $record->id }}')" class="submit-btn">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>


            @endforeach
            @else
            <div class="d-flex justify-content-center align-items-center mt-2">
                <div class="card p-4 text-center">
                <div class="no-data p-4 text-align-center justify-content-center align-items-center" style="width:700px;height:260px">
        <img src="{{ asset('images/no-data_Gif.gif') }}" 
     alt="No data available" 
     style="width:100%; height: 250px; object-fit: contain;">

            <p>No data available</p>
        </div>
        </div>
        </div>
        @endif
   
@endif


    @if ($activeTab == "pending")
    

        @if($forHR->where('status_code', 5)->count() > 0)
            @foreach ($forHR->where('status_code', 5) as $record)
            <div class="col-md-10 " >
        <div class="newReq mt-2" style="align-items:end">

    <button wire:click="exportToExcel" class="cancel-btn" style="margin-left:20px">Export to Excel</button>
        </div>
    </div>
            <div class="container d-flex justify-content-center align-items-center mt-2">
    <div class="card" style="width:80%;margin-top:30px;">
        <div class="card-header" style="background-color:#dbf0f9; color: black;border: 1px solid #ddd;height:auto;cursor:pointer" data-bs-toggle="collapse" data-bs-target="#collapse-body-{{ $record->id }}" aria-expanded="false" aria-controls="collapse-body-{{ $record->id }}">
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

        <img class="navProfileImgFeeds" src="{{ $profileImage }}" alt="Profile Image" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;">
        
        <div style="display: flex; flex-direction: column; font-size: 14px;margin-left:5px">
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

        <div id="collapse-body-{{ $record->id }}" class="collapse;margin-top:-10px" style="padding: 10px;">
            <div class="card-body" style="padding: 5px;font-size:14px">
           <p ><strong >Subject:</strong>{{ $record->subject }}</p>
         <p><strong >Description:</strong> {{ $record->description }}</p>
@if (!empty($record->file_path))
    <p><strong style="font-weight: 500;">Attachments:</strong> 
        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF; font-weight: 500;">
            View File
        </a>
    </p>
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
    <img class="navProfileImgFeeds " 
         src="{{ $profileImage }}" 
         alt="Profile Image" 
         style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; cursor: pointer; margin-right: 10px;">

    <!-- Employee Details -->
    <p style="margin: 0; font-size: 14px;font-weight:500">
        <strong>{{ $employeeDetails->first_name ?? 'Unknown' }} 
                {{ $employeeDetails->last_name ?? '' }} 
                (#{{ $employeeDetails->emp_id ?? 'N/A' }})</strong> - 
                <small>{{ \Carbon\Carbon::parse($record->updated_at)->shortRelativeDiffForHumans() }}</small>

    </p>
</div>


        <p style="padding: 5px 10px; border-left: 3px solid #ddd; margin-bottom: 5px;">
        <p>{{ is_array($comment) ? ($comment['text'] ?? 'Invalid Comment') : $comment }}</p>
        </p>
    @endforeach

@endif

</div>

                <textarea rows="3" placeholder="Add a comment..." class="form-control" wire:model="commentText"></textarea>
    <button wire:click="postComment({{ $record->id }})" class="cancel-btn mt-2">Post Comment</button>

                </div>

                <!-- Action Buttons -->
                <div class="mt-3">
                    <button wire:click="openForDesks('{{ $record->id }}')" class="submit-btn">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
                @endforeach
             @else

  
             <div class="d-flex justify-content-center align-items-center mt-2">
                <div class="card p-4 text-center">
                <div class="no-data p-4 text-align-center justify-content-center align-items-center" style="width:700px;height:260px">
        <img src="{{ asset('images/no-data_Gif.gif') }}" 
     alt="No data available" 
     style="width:100%; height: 250px; object-fit: contain;">

            <p>No data available</p>
        </div>
        </div>
        </div>


                @endif


    </div>
    @endif

    @if ($activeTab == "closed")
    
    @if($forHR->where('status_code', 9)->count() > 0)
    @foreach ($forHR->where('status_code', 9) as $record)
    <div class="col-md-10 " >
        <div class="newReq mt-2" style="align-items:end">

    <button wire:click="exportToExcel" class="cancel-btn" style="margin-left:20px">Export to Excel</button>
        </div>
    </div>
                <div class="container d-flex justify-content-center align-items-center mt-2">
    <div class="card" style="width:80%;margin-top:30px;">
        <div class="card-header" style="background-color:#dbf0f9; color: black;border: 1px solid #ddd;height:auto;cursor:pointer" data-bs-toggle="collapse" data-bs-target="#collapse-body-{{ $record->id }}" aria-expanded="false" aria-controls="collapse-body-{{ $record->id }}">
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

        <img class="navProfileImgFeeds " src="{{ $profileImage }}" alt="Profile Image" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;">
        
        <div style="display: flex; flex-direction: column; font-size: 14px;padding-left:5px">
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

        <div id="collapse-body-{{ $record->id }}" class="collapse" style="padding: 10px;">
        <div class="card-body" style="padding: 5px;font-size:14px">
         <p ><strong >Subject:</strong>{{ $record->subject }}</p>
<p><strong >Description:</strong> {{ $record->description }}</p>

<!-- Display Attachments Only If They Exist -->
@if (!empty($record->file_path))
    <p><strong style="font-weight: 500;">Attachments:</strong> 
        <a href="{{ asset('storage/' . $record->file_path) }}" target="_blank" style="text-decoration: none; color: #007BFF; font-weight: 500;">
            View File
        </a>
    </p>
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
    <img class="navProfileImgFeeds" 
         src="{{ $profileImage }}" 
         alt="Profile Image" 
         style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; cursor: pointer; margin-right: 10px;">

    <!-- Employee Details -->
    <p style="margin: 0; font-size: 14px;font-weight:500">
        <strong>{{ $employeeDetails->first_name ?? 'Unknown' }} 
                {{ $employeeDetails->last_name ?? '' }} 
                (#{{ $employeeDetails->emp_id ?? 'N/A' }})</strong> - 
                <small>{{ \Carbon\Carbon::parse($record->updated_at)->shortRelativeDiffForHumans() }}</small>

    </p>
</div>


        <p style="padding: 5px 10px; border-left: 3px solid #ddd; margin-bottom: 5px;">
          @php
    // Decode the JSON comments safely
    $comments = json_decode($record->active_comment, true);
@endphp

@if(!empty($comments))
    @foreach($comments as $comment)
        <p>{{ is_array($comment) ? ($comment['text'] ?? 'Invalid Comment') : $comment }}</p>
    @endforeach

@endif
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
                    <button wire:click="closeForDesks('{{ $record->id }}')" class="submit-btn">Close</button>

                </div>
            </div>
        </div>
    </div>
</div>
                @endforeach
                @else
                <div class="d-flex justify-content-center align-items-center mt-2">
                <div class="card p-4 text-center">
                <div class="no-data p-4 text-align-center justify-content-center align-items-center" style="width:700px;height:260px">
        <img src="{{ asset('images/no-data_Gif.gif') }}" 
     alt="No data available" 
     style="width:100%; height: 250px; object-fit: contain;">

            <p>No data available</p>
        </div>
        </div>
        </div>
                @endif


    </div>
    @endif
    @endauth

</div>