<div >


       <div class="tab-container">
       <div class="tab-buttons">
    <button class="tab-button active" onclick="showTab(0)">Main</button>
    <button class="tab-button" onclick="showTab(1)">Activity</button>
</div>

<!-- Tab Content -->
<div class="tab-content-custom active" id="tab-0">
@if (session('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 500px; margin: auto;height:30px">
        {{ session('message') }}
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"
                    style="font-size: 0.75rem; padding: 0.2rem 0.4rem; margin-top: 4px;"></button>
         
    </div>
@endif


<div class="row justify-content-center mt-2"  >
                        <div class="col-md-10 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">
        This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.
    </p>
    <p class="hide-text" style="cursor: pointer;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
</div>

                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore greytHR by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-10 custom-container d-flex flex-column bg-white">
    <div class="row justify-content-center mt-3 flex-column m-0" style="border-radius: 5px; font-size:12px; width:88%;">
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
                        <p class="main-text">Employee Type:</p>
                        <p  class="edit-heading ml-2">Current Employees</p>
                    </div>
                 
                    <div class="profile" >
    <div class="col m-0">
     
    <div class="row d-flex align-items-center">
    <p class="main-text "  style="cursor:pointer" wire:click="NamesSearch">
        Search Employee:
    </p>

    @foreach($selectedPeopleData as $personData)
        <span class="selected-person d-flex align-items-center">
            <img class="profile-image-selected" src="{{ $personData['image'] }}" alt="Employee Image">
           
           
            <p class="selected-name" >
                {{ $personData['name'] }}
            </p>
            <svg class="close-icon-person"  
                 wire:click="removePerson('{{ $personData['emp_id'] }}')" 
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    @endforeach
</div>





               
       
    
        <div class="col-md-6 col-12"> 

@if($isNames)
<div class="col-md-6" style="border-radius: 5px; background-color: grey; padding: 8px; width: 330px; margin-top: 10px; height: 250px; overflow-y: auto;">
<div class="input-group4" style="display: flex; align-items: center; width: 100%;">

<input 
wire:model.debounce.500ms="searchTerm" placeholder="Search employees..."
style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" 
type="text" 
class="form-control" 
placeholder="Search for Emp.Name or ID" 
aria-label="Search" 
aria-describedby="basic-addon1"
/>

<div class="input-group-append" style="display: flex; align-items: center;">
<button 
  
 wire:click="searchforEmployee"  wire:model.debounce.500ms="searchTerm"  style="<?php echo ($searchEmployee) ? 'display: block;' : ''; ?>height: 30px; border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none; padding: 0 10px;" 
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
<div>


<!-- Display the Search Results -->
@if ($peopleData && $peopleData->isEmpty())
                                    <div class="search-container">
                                        No People Found
                                    </div>
                                    @else
                                 
                                    @foreach($peopleData as $employee)
        @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
        <label wire:click="selectPerson('{{ $employee->emp_id }}')" class="search-container">
            <div class="row align-items-center">
                <div class="col-auto"> 
                    <input type="checkbox" id="employee-{{ $employee->emp_id }}" 
                           wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"  
                           wire:model="selectedPeople" 
                           value="{{ $employee->emp_id }}" 
                           {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                </div>
                <div class="col-auto">
    @if($employee->image && $employee->image !== 'null')
        <img class="profile-image" src="{{ 'data:image/jpeg;base64,' . base64_encode($employee->image) }}" >
    @else
        @if($employee->gender == "Male")
            <img class="profile-image"src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
        @elseif($employee->gender == "Female")
            <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
        @else
            <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
        @endif
    @endif
</div>

                <div class="col">
                    <h6 class="username" style="font-size: 12px; color: white;">
                        {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}
                    </h6>
                    <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $employee->emp_id }})</p>
                </div>
            </div>
        </label>
        @endif
    @endforeach

@endif


</div>





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

   
<form wire:submit.prevent="create" style="margin-top: 10px; font-size: 12px;align-items:center">

        @csrf

        <!-- Asset Type -->
        <div class="row justify-content-center">
            <div class="col-md-4 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetType" class="col-cus-form-label cus-form-label ">Asset Type</label>
                    <select wire:model="asset_type" class="form-select form-control cus-form-label" id="assetType"  >
                        <option value="">Asset Type</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Keyboard">Keyboard</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Mouse">Mouse</option>
                    </select>
                 
                </div>
                @error('asset_type') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>

            <!-- Asset Status -->
            <div class="col-md-4  align-items-end">
                <div class="mb-3 d-flex align-items-end">
                    <label for="assetStatus" class="col-cus-form-label cus-form-label mb-2">Asset Status</label>
                    <select wire:model="asset_status" class="form-select form-control cus-form-label" id="assetStatus" >
                        <option value="">Asset Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                @error('asset_status') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Details and Issue Date -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-4 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetDetails" class="col-cus-form-label cus-form-label">Asset Details</label>
                    <input type="text" class="form-control cus-form-label" id="assetDetails"  wire:model="asset_details">
                </div>
                @error('asset_details') <span class="text-danger mb-6">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 ">
                <div class="mb-3 d-flex align-items-center">
                    <label for="issueDate" class="col-cus-form-label cus-form-label">Issue Date</label>
                    <input type="date" wire:model="issue_date" class="form-control cus-form-label" id="issueDate" >
                </div>
                @error('issue_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset ID and Valid Till -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-4 ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetID" class="col-cus-form-label cus-form-label">Asset ID</label>
                    <input type="text" wire:model="asset_id" class="form-control cus-form-label" id="assetID" >
                </div>
                @error('asset_id') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 custom-margin-left">
                <div class="mb-3 d-flex align-items-start">
                    <label for="validTill" class="col-cus-form-label cus-form-label">Valid Till</label>
                    <input type="date" wire:model="valid_till" class="form-control cus-form-label" id="validTill" >
                </div>
                @error('valid_till') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Asset Value and Returned On -->
        <div class="row justify-content-center mt-2">
            <div class="col-md-4  ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="assetValue" class="col-cus-form-label cus-form-label">Asset Value</label>
                    <input type="text" wire:model="asset_value" class="form-control cus-form-label" id="assetValue"  style="font-size: 12px;">
                </div>
                @error('asset_value') <span class="text-danger  ">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4  ">
                <div class="mb-3 d-flex align-items-start">
                    <label for="returnedOn" class="col-cus-form-label cus-form-label">Returned On</label>
                    <input type="date" wire:model="returned_on" class="form-control cus-form-label" id="returnedOn"  style="font-size: 12px;">
                </div>
                @error('returned_on') <span class="text-danger ">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Remarks -->
        <div class="row justify-content-center mt-2">
    <div class="col-md-4">
        <div class="mb-3 d-flex align-items-start">
            <label for="remarks" class="col-cus-form-label cus-form-label">Remarks</label>
            <textarea wire:model="remarks" class="form-control" id="remarks" style="font-size: 12px; width: 400px;"></textarea>
        </div>
        @error('remarks') 
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-4">

    </div>
</div>
     

        <!-- Submit Button -->
        <div class="row justify-content-center">
            <div class="col-md-6 mr-auto">
                <button type="submit" class="submit-btn" >Submit</button>
            </div>
        </div>
    </form>

<!-- Form ends here -->
@endif


@endif





                    </div>
              
           

          

                    <div class="tab-content-custom" id="tab-1">
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
            const contents = document.querySelectorAll('.tab-content-custom');
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