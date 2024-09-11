<div style="color:#778899">
 <style>


    </style>


    <div class="tab-container">
        <!-- Tab buttons -->
        <div class="tab-buttons">
            <button class="tab-button active" onclick="showTab(0)">Main</button>
            <button class="tab-button" onclick="showTab(1)">Activity</button>
          
        </div>

        <!-- Tab contents -->
        <div class="tab-content active">
        <div class="row justify-content-center"  >
                        <div class="col-md-10 custom-container d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <p class="main-text mb-0">This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.</p>
                                <p style="font-size: 12px; cursor: pointer;color:blue;font-weight:500;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
                            </div>
                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore greytHR by 
    <span style="color:blue">Help-Doc</span>, watching How-to 
    <span style="color:blue">Videos</span> and 
    <span style="color:blue">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-10 custom-container d-flex flex-column bg-white">
    <div class="row justify-content-center mt-3 flex-column m-0" style="border-radius: 5px; font-size:12px; width:88%;">
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-10 m-0">
                    <b>Start searching to see specific employee details here</b>
                    <div class="col mt-3" style="display: flex;">
             
                        <p style="font-size: 12px; font-weight:260">Employee Type:</p>
                        <p>Current Employees</p>
                    </div>
                 
                    <div class="profile" style="margin-top: 10px;">
    <div class="col m-0">
     
            <div class="row  ">
            <div>
    <p style="cursor: pointer;" wire:click="NamesSearch">
        Search Employee:
      
            @foreach($selectedPeopleData as $personData)
                <div class=" align-items-center" style="margin-right: 15px;display:flex">
                    <img class="profile-image" src="{{ $personData['image'] }}" style="border-radius:50%; height:30px; width:30px;" alt="Employee Image">
                    <p style="margin-left: 10px; line-height: 30px; font-size:10px;">{{ $personData['name'] }}</p>
                </div>
            @endforeach
      
    </p>
</div>




               
       
        </div>
       <div class="col-md-6 col-12"> 

        @if($isNames)
        <div class="col-md-6" style="border-radius: 5px; background-color: grey; padding: 8px; width: 330px; margin-top: 10px; height: 250px; overflow-y: auto;">
        <div class="input-group4" style="display: flex; align-items: center; width: 100%;">
    <input 
        wire:model="searchTerm" 
        style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" 
        type="text" 
        class="form-control" 
        placeholder="Search for Emp.Name or ID" 
        aria-label="Search" 
        aria-describedby="basic-addon1"
    >
    <div class="input-group-append" style="display: flex; align-items: center;">
        <button 
            wire:click="filter" 
            style="height: 30px; border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none; padding: 0 10px;" 
            class="btn" 
            type="button"
        >
            <i style="text-align: center;" class="fa fa-search"></i>
        </button>

        <button 
            wire:click="closePeoples"  
            type="button" 
            class="close rounded px-1 py-0" 
            aria-label="Close" 
            style="background-color: rgb(2,17,79); height: 30px; width: 30px; margin-left: 5px; display: flex; align-items: center; justify-content: center;"
        >
            <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
        </button>
    </div>
</div>

        
        
 

@foreach($employeeIds as $emp_id)
                            @php
                                $employee = $employees->firstWhere('emp_id', $emp_id);
                            @endphp
                           
                            <label wire:click="selectPerson('{{ $emp_id }}')"
 class="container" style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-bottom: 8px; width: 300px; border-radius: 5px; margin-top:5px">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <input type="checkbox" wire:model="selectedPeople" value="{{ $emp_id }}" {{ in_array($emp_id, $selectedPeople) ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-auto">
                                        @if($employee && $employee->image && $employee->image !== 'null')
                                        <img class="profile-image" src="{{ 'data:image/jpeg;base64,' . base64_encode($employee->image) }}" style="border-radius:50%">
                                        @else
                                            @if($employee && $employee->gender == "Male")
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                                            @elseif($employee && $employee->gender == "Female")
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                                            @else
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col">
                                        <h6 class="username" style="font-size: 12px; color: white;">
                                            
                                            {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}
                                        </h6>
                                        <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $emp_id }})</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach

      
        </div>
        @endif
        </div>
 
    </div>
</div>

                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTrb080MeuXwgT6ZB-x7qWZ3i_xQks-9xsRz5F9wWIyKbEEbGzL" alt="Employee Image" style="height: 180px; width:300px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>
 
    @if(!empty($selectedPeople))
    <div class="row mt-3 p-0 justify-content-center">
   
    </div>
    
    @if ($showSuccessMessage)
 
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
    style="font-size: 12px; padding: 5px 10px; display: flex; align-items: center; justify-content: space-between; width: 300px; margin: 0 auto;" 
    wire:poll.5s="closeMessage">
    Profile updated successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" 
        style="font-size: 8px;align-items:center;margin-top:-7px">
        &times;
    </button>
</div>


@endif

    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
<div class="d-flex justify-content-center" style="width: 100%;flex-direction:row">
    <div class="row" style="display:flex; justify-content: center; width:90%; align-items:center;">
        <div class="col" style="background:#98CBBA; border-radius:5px; height:60px; display:flex; padding: 10px; margin-top:10px; align-items:center; justify-content:space-between; width:100%;">
            
            {{-- Employee Image --}}
            @if($employee->image && $employee->image !== 'null')
                <img class="profile-image" src="{{ 'data:image/jpeg;base64,' . base64_encode($employee->image) }}" style="border-radius:50%; height:50px; width:50px;" alt="Employee Image">
            @else
                @if($employee->gender == "Male")
                    <img style="border-radius: 50%;" height="40" width="40" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                @elseif($employee->gender == "Female")
                    <img style="border-radius: 50%;" height="40" width="40" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                @else
                    <img style="border-radius: 50%;" height="40" width="40" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                @endif
            @endif

            {{-- Employee Name --}}
            <p style="margin-left: 15px; font-size:12px; justify-content:center;">{{ $employee->first_name }} {{ $employee->last_name }}</p>

            {{-- Update Button and File Input --}}
            <div class="d-flex align-items-center gap-2" style="margin-left: auto;">
                <input type="file" id="imageInput" wire:model="image" class="form-control-small" style="font-size: 0.75rem; margin-right: 10px;">
                <button class="submit-btn px-2 py-1" wire:click="updateProfile('{{ $employee->emp_id }}')">
                    <span style="font-size: 10px;">Update</span>
                </button>
            </div>

            {{-- Image Upload Error --}}
            @if ($errors->has('image'))
                <span class="text-danger">{{ $errors->first('image') }}</span><br>
            @endif
        </div>
    </div>
</div>



    <div class="row align-items-center">
        <div class="card mx-auto" style="margin-top: 20px; height:auto; width:90%;">
            <div class="card-header" style="font-size: 15px; background:white; width:100%; display:flex; justify-content:space-between;">
                <p style="color:#3b4452;font-weight: 500;">Employee Information</p>
                <p style="text-align: end; font-size:  14px;">
                    <i>
                        @if($currentEditingProfileId == $employee->emp_id)
                            <i wire:click="cancelProfile()" class="fas fa-times me-3" style="cursor: pointer;"></i>
                            <i wire:click="saveProfile('{{ $employee->emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                        @else
                            <i wire:click="editProfile('{{ $employee->emp_id }}')" class="fas fa-edit" style="cursor: pointer;"></i>
                        @endif
                    </i>
                </p>
            </div>
            <div class="row" style="color: #778899; margin-top: 10px; margin-left:10px; height:auto;">
                <div class="col-md-3" style="font-size: 12px;">Title</div>
                <div class="col-md-3" style="font-size: 12px;">Nick Name</div>
                <div class="col-md-3" style="font-size: 12px;">Gender</div>
                <div class="col-md-3" style="font-size: 12px;">Name</div>
            </div>

            @if($currentEditingProfileId == $employee->emp_id)
 
                <div class="row" style="margin-top: 10px; margin-left:5px;">
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="title" placeholder="Title"></div>
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="nickName" placeholder="Nickname"></div>
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="gender" placeholder="Gender"></div>
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="name" placeholder="Name"></div>
                </div>
            @else
                <div class="row" style="margin-left:15px;">
                    <div class="col-md-3" style="font-size: 12px;">{{ $employee->title ?? '-' }}</div>
                    <div class="col-md-3" style="font-size: 12px;">{{ $employee->nick_name ?? '-' }}</div>
                    <div class="col-md-3" style="font-size: 12px;">{{ $employee->gender ?? '-' }}</div>
                    <div class="col-md-3" style="font-size: 12px;">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                </div>
            @endif
            <div class="row" style="color: #778899; margin-top: 10px; margin-left:10px; height:auto;">
            <div class="col-md-3 mb-3" style="font-size: 12px;">Mobile</div>
                    <div class="col-md-3 mb-3" style="font-size: 12px;">Email</div>
                    <div class="col-md-3 mb-3" style="font-size: 12px;">Extension</div>
              
            </div>

            @if($currentEditingProfileId == $employee->emp_id)
            @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 12px; padding: 5px 10px; width: 100%; max-width: 500px; margin: 10px auto;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 2px; font-size: 8px;">
            &times;
        </button>
    </div>
@endif
                <div class="row" style="margin-top: 5px; margin-left:5px;">
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="emergency_contact" placeholder="Mobile"></div>
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="Email" placeholder="Email"></div>
                    <div class="col-md-3 mb-3"><input style="font-size:12px" type="text" class="form-control" wire:model="extension" placeholder="Extension"></div>
      
                </div>
            @else
                <div class="row" style="margin-left:15px;">
                <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->emergency_contact ?? '-'}}</div>
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->email ?? '-'}}</div>
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->extension ?? '-'}}</div>
                </div>
            @endif
        </div>
        <div class="card mx-auto mt-3" style="width: 90%; height: auto;">
    <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px; background:white;">
        <p class="mb-0" style=" font-weight: 500;">Personal Information</p>
        <div style="font-size: 14px;">
            <i>
            @if($currentEditingPersonalProfileId == $employee->emp_id)
                    <i wire:click="cancelpersonalProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="savepersonalProfile('{{ $emp_id }}')" class="fas fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editpersonalProfile('{{ $emp_id }}')" class="fas fa-edit" style="cursor: pointer;"></i>
                @endif
            </i>
        </div>
    </div>

    <div class="row px-3 mt-2 text-muted" style="font-size: 12px;">
        <div class="col-md-3">DOB</div>
        <div class="col-md-3">Blood Group</div>
        <div class="col-md-3">Marital Status</div>
    </div>

    @if($currentEditingPersonalProfileId == $employee->emp_id)
        <div class="row px-3 mt-2">
            <div class="col-md-3 mb-3">
                <input type="date" class="form-control" wire:model="dob" style="font-size:12px">
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" wire:model="blood_group" placeholder="Blood Group" style="font-size:12px">
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" wire:model="marital_status" placeholder="Marital Status" style="font-size:12px">
            </div>
        </div>
    @else
        <div class="row px-3 mt-3 text-black" style="font-size: 12px;">
            <div class="col-md-3 mb-3">{{ $employee->dob ?? '-' }}</div>
            <div class="col-md-3 mb-3">{{ $employee->blood_group ?? '-' }}</div>
            <div class="col-md-3 mb-3">{{ $employee->marital_status ?? '-' }}</div>
        </div>
    @endif
</div>

    </div>
@endif

    @endforeach
@endif





                    </div>
              
           

          

        <div class="tab-content">
        <div class="row mt-3 ml-3" style="font-size:12px">
      
        <div id="employee-container">
        @foreach($employeess as $employee)
    <span style="font-weight:600">
        Added New Employee: ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}
    </span>
    @if (!$loop->first)<br>@endif
    <p>
        Hire Date: 
        @if($employee->hire_date)
            @php
                $hireDate = \Carbon\Carbon::parse($employee->hire_date);
            @endphp
            ({{ $hireDate->format('M d, Y') }})
        @else
            N/A
        @endif
    </p>
    @if (!$loop->first)<br>@endif
@endforeach

</div>





      
      
      
              <br>
          
      
      
       
                  </div>
      
        </div>

    </div>

    <script>
        // JavaScript to handle tab switching
        function showTab(index) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => button.classList.remove('active'));

            // Show the selected tab content and mark the button as active
            contents[index].classList.add('active');
            buttons[index].classList.add('active');
        }
    </script>


    </div>