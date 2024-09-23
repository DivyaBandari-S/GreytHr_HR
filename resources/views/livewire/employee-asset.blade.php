<div style="color:#778899">
 <style>
    
        .custom-container {
            border-radius: 5px;
            background-color: #F1F9FB;
            border: 1px solid silver;
            padding: 15px;
        }

  
        .help-text {
            font-size: 12px;
            color: blue;
            cursor: pointer;
            margin-left: auto;
        }

        .secondary-text {
            font-size: 12px;
            margin-top: 10px;
            font-weight: 200;
        }
        .form-label {
            width: 120px;
        }
        .form-control {
            margin-left: 20px;
        }
        /* Added CSS for the image container */
        .image-container {
            height: 180px; /* Fixed height to prevent image movement */
        }
        .custom-margin-left {
    margin-left: 60px;
}

   

    </style>


    <div class="tab-container">
        <!-- Tab buttons -->
        <div class="tab-buttons">
            <button class="tab-button active" onclick="showTab(0)">Main</button>
            <button class="tab-button" onclick="showTab(1)">Activity</button>
          
        </div>

        <!-- Tab contents -->
        <div class="tab-content active">
        @if (session()->has('message'))
    <div class="container" style="display: flex; justify-content: center; align-items: center; ">
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 500px; margin: auto; height: 30px; align-items: center;font-size:12px;display:flex">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem; padding: 0.2rem 0.4rem; margin-top: 4px;"></button>
        </div>
    </div>
@endif

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
        class="container" 
        style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-bottom: 8px; width: 300px; border-radius: 5px; margin-top:5px">
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
    
    @if($employee)

   
<form wire:submit.prevent="create" style="margin-top: 10px; font-size: 12px;">

        @csrf

        <!-- Asset Type -->
        <div class="row justify-content-start">
            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetType" class="col-form-label form-label">Asset Type</label>
                    <select wire:model="asset_type" class="form-select form-control" id="assetType"  style="font-size: 12px;">
                        <option value="">Asset Type</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Keyboard">Keyboard</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Mouse">Mouse</option>
                    </select>
                </div>
                @error('asset_type') <span class="text-danger mb-5 ">{{ $message }}</span> @enderror
            </div>

            <!-- Asset Status -->
            <div class="col-md-4 offset-sm-3 custom-margin-left">
                <div class="mb-3 d-flex align-items-end">
                    <label for="assetStatus" class="col-form-label form-label">Asset Status</label>
                    <select wire:model="asset_status" class="form-select form-control" id="assetStatus"  style="font-size: 12px;">
                        <option value="">Asset Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                @error('asset_status') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Details and Issue Date -->
        <div class="row justify-content-start">
            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetDetails" class="col-form-label form-label">Asset Details</label>
                    <input type="text" class="form-control" id="assetDetails"  wire:model="asset_details">
                </div>
                @error('asset_details') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-center">
                    <label for="issueDate" class="col-form-label form-label">Issue Date</label>
                    <input type="date" wire:model="issue_date" class="form-control" id="issueDate"  style="font-size: 12px;">
                </div>
                @error('issue_date') <span class="text-danger mb-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset ID and Valid Till -->
        <div class="row justify-content-start">
            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetID" class="col-form-label form-label">Asset ID</label>
                    <input type="text" wire:model="asset_id" class="form-control" id="assetID"  style="font-size: 12px;">
                </div>
                @error('asset_id') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="validTill" class="col-form-label form-label">Valid Till</label>
                    <input type="date" wire:model="valid_till" class="form-control" id="validTill"  style="font-size: 12px;">
                </div>
                @error('valid_till') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Value and Returned On -->
        <div class="row justify-content-start">
            <div class="col-md-4  custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetValue" class="col-form-label form-label">Asset Value</label>
                    <input type="text" wire:model="asset_value" class="form-control" id="assetValue"  style="font-size: 12px;">
                </div>
                @error('asset_value') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4  custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="returnedOn" class="col-form-label form-label">Returned On</label>
                    <input type="date" wire:model="returned_on" class="form-control" id="returnedOn"  style="font-size: 12px;">
                </div>
                @error('returned_on') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Remarks -->
        <div class="row justify-content-start">
            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="remarks" class="col-form-label form-label">Remarks</label>
                    <input type="text" wire:model="remarks" class="form-control" id="remarks"  style="font-size: 12px;">
                </div>
                @error('remarks') <span class="text-danger  mb-5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row justify-content-start">
            <div class="col-md-6  custom-margin-left">
                <button type="submit" class="btn btn-primary" style="background-color: rgb(2, 17, 79);">Submit</button>
            </div>
        </div>
    </form>

<!-- Form ends here -->
@endif


@endif





                    </div>
              
           

          

        <div class="tab-content">
        <div class="row mt-3 ml-3" style="font-size:12px">
      
        <div id="employee-container">


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