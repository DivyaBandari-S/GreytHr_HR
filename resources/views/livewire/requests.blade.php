<div class="position-relative">
 <style>/* Custom Colors */
.view-history-container {
    display: flex;
    flex-direction: column;
    color:#778899;
    gap: 16px;
    padding: 16px;
}

.history-card {
    background: #ffffff;
    border: 1px solid #ddd;
    color:#778899;
    font-weight: 500;
    border-radius: 8px;
    width:60%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.history-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.user-details {
    display: flex;
    align-items: center;
    color:#778899;
    gap: 12px;
}

.user-icon {
    font-size: 36px;
    color: #007bff;
}

.history-date p {
    font-size: 14px;
    color:#778899;
    margin: 0;
}

.history-body {
    margin-bottom: 12px;
    font-size: 14px;
    line-height: 1.5;
    color:#778899;
   
}

.history-footer {
    text-align: right;
}

.status {
    font-size: 14px;
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 12px;
    display: inline-block;
}

.status.completed {
    color: #ffffff;
    background: #28a745;
}
.status.Open {
    color: #ffffff;
    background:#28a745;
}

.status.pending {
    color: #ffffff;
    background: #ffc107;
}

.status.rejected {
    color: #ffffff;
    background: #dc3545;
}

.no-history {
    text-align: center;
    color: #666;
    padding: 32px;
}

.no-history img {
    width: 100px;
    height: 100px;
    margin-bottom: 12px;
}

</style>
    <div wire:loading
        wire:target="open,file_path,submitHR,Catalog,activeTab,LapRequest,closeImageDialog,downloadImage,showImage,">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>

        </div>
    </div>
    <div   class="position-relative" style="overflow-x:hidden">

        <div class="row m-0">
    
        <div class="col-md-12 " >
            <div class="newReq mt-5" style="align-items:end">
                <button class="submit-btn" wire:click="LapRequest">
                    Employee Onboarding 
                </button>
                <button class="submit-btn" wire:click="IDRequest">
                Employee Offboarding 
                </button>
            </div>
        </div>
        
        <!-- modals for service requst -->
        @if($LapRequestaceessDialog)
        <div class="modal" tabindex="-1" role="dialog" style="{{ $showModal ? 'display: block;' : 'display: none;' }}">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header" style="background:white;height:80px">
                                        <h4 class="modal-title fs-5" id="exampleModalLabel" style="color:black">
    Employee Onboarding Request<br> 
    <p style="font-size:12px; margin-top:5px; font-weight:300;">
        Employee Onboarding Request
    </p>
</h4>

  
</div>



                                            <div class="modal-body">
                                            <div class="row m-0">
    <div class="col " style="text-align: right; padding: 0;"> <!-- Align text to left -->
        <img src="images/it-images/onboard.jpg" style="display: block; margin-right: 0;height:100px" />
    </div>
</div>


                                                <hr style="border: 1px solid #ccc;margin: 10px 0;">
                                                <form wire:submit.prevent="Onboarding">




                                                    <div class="form-group  mt-2">
                                                        <label for="Name">Requested By:</label>


                                                        <div class="input-group mb-3">



                                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-info-circle" style="color:blue"></i></span> <!-- Change label as needed -->
                                                            @if($employeeDetails)
                                                            <input wire:model.lazy="full_name" type="text" class="form-control" aria-describedby="basic-addon1" readonly>
                                                            @else
                                                            <p>No employee details found.</p>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="Name">Requested For:</label>
                                                        <div class="input-group mb-3">
                                                            <!-- Info icon on the left side -->
                                                            <span class="input-group-text" id="basic-addon">
                                                                <i class="fa fa-info-circle" style="color:blue"></i>
                                                            </span>

                                                            <!-- Employee details input -->

                                                            <input type="text" wire:click="NamesSearch"
                                                                value="{{ implode(', ', array_unique($selectedPeopleNames)) }}"
                                                                class="form-control"
                                                                aria-describedby="basic-addon1"
                                                                readonly>



                                                            <!-- Dropdown toggle icon on the right side -->
                                                            <button class="btn btn-outline-secondary dropdown-toggle" style="border:1px solid silver" wire:click="NamesSearch" type="button" data-bs-toggle="dropdown">
                                                            </button>

                                                        </div>

                                                        @if($isNames)
                                                        <div style="border-radius:5px; background-color:grey; padding:8px; width:330px; margin-top:10px; height:200px; overflow-y:auto;">
                                                            <div class="input-group4" style="display: flex; align-items: center; width: 100%;">
                                                                <input wire:model="searchTerm" style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" type="text" class="form-control" placeholder="Search for Emp.Name or ID" aria-label="Search" aria-describedby="basic-addon1">
                                                                <div class="input-group-append" style="display: flex; align-items: center;">
                                                                    <button wire:click="filter" style="height: 30px; border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none; padding: 0 10px;" class="btn" type="button">
                                                                        <i style="text-align: center;" class="fa fa-search"></i>
                                                                    </button>

                                                                    <button wire:click="closePeoples" type="button" class="close rounded px-1 py-0" aria-label="Close" style="background-color: rgb(2,17,79); height: 30px; width: 30px; margin-left: 5px; display: flex; align-items: center; justify-content: center;">
                                                                        <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            @if ($peopleData->isEmpty())
                                                            <div class="container" style="text-align: center; color: white; font-size:12px">
                                                                No People Found
                                                            </div>
                                                            @else
                                                            @foreach($peopleData->sortBy(function($people) { return strtolower($people->first_name) . ' ' . strtolower($people->last_name); }) as $people)
                                                            <label wire:click="addselectPerson('{{ $people->emp_id }}')" class="container" style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-top: 10px; width: 300px; border-radius: 5px;">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <input type="checkbox" id="person-{{ $people->emp_id }}" class="form-check-input custom-checkbox-helpdesk" wire:model="addselectedPeople" value="{{ $people->emp_id }}" {{ in_array($people->emp_id, $addselectedPeople) ? 'checked' : '' }}>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        @if (!empty($people->image) && $people->image !== 'null')
                                                                        <img class="profile-image" src="data:image/jpeg;base64,{{($people->image) }}">
                                                                        @else
                                                                        @php $gender = $people->gender ?? null; @endphp
                                                                        @if ($gender === 'Male')
                                                                        <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                                                                        @elseif($gender === 'Female')
                                                                        <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                                                                        @else
                                                                        <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                                        @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="col">
                                                                        <h6 class="username" style="font-size: 12px; color: white;">{{ ucwords(strtolower($people->first_name)) }} {{ ucwords(strtolower($people->last_name)) }}</h6>
                                                                        <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $people->emp_id }})</p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            @endforeach

                                                            @endif
                                                        </div>
                                                        @endif

                                                    </div>
                                                    <div style="display:flex">

                                                        <div class="form-group col-md-6 mt-2">

                                                            <label for="mobile">Mobile Number <span style="color:red">*</span></label>
                                                            <input wire:model="mobile" wire:keydown.debounce.500ms="validateField('mobile')" type="text" class="form-control">
                                                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="form-group col-md-6 mt-2 ml-3" style="margin-left:10px">
                                                            <label for="contactDetails">Email <span style="color:red">*</span></label>
                                                            <input wire:model.lazy="mail" wire:keydown.debounce.500ms="validateField('mail')" type="text" class="form-control">
                                                            @error('mail') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>

                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="priority" class="helpdesk-label">Priority<span style="color:red">*</span></label>
                                                        <div class="input" class="form-control placeholder-small">
                                                            <div style="position: relative;">
                                                                <select name="priority" id="priority" wire:keydown.debounce.500ms="validateField('priority')" wire:model.lazy="priority" style="font-size: 12px; " class="form-control placeholder-small">
                                                                    <option style="color: grey;" value="" hidden>Select Priority</option>
                                                                    <option value="Low">Low</option>
                                                                    <option value="Medium">Medium</option>
                                                                    <option value="High">High</option>


                                                                </select>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                                    class="bi bi-caret-down" viewBox="0 0 16 16" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); pointer-events: none;align-items :center">
                                                                    <path d="M3.204 5h9.592L8 10.481 3.204 5z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                 
                                                    <div class="form-group mt-2">
    <label for="job_role">Job Role<span style="color:red">*</span></label>
    <input wire:model="job_role" wire:keydown.debounce.500ms="validateField('job_role')" type="text" class="form-control">
    @error('last_working_day') <span class="text-danger">{{ $message }}</span> @enderror
</div>
<p style="margin-top: 5px;font-size:14px;color:#778899" >Equipment Preferences :</p>
<div class="equipment-preferences mt-2">
    <label for="laptop-os">Preferred Laptop OS:</label><br>
    <div class="radio-group mt-3" style="display:flex">
        <input type="radio" id="windows" name="laptop_os" value="Windows">
        <label for="windows">Windows</label><br>

        <input type="radio" id="mac" name="laptop_os" value="Mac" style="margin-left:10px">
        <label for="mac">Mac</label><br>
    </div>
</div>                                       

<div class="equipment-preferences mt-2">
  <label>Do you need a mouse?</label><br>
  <div class="radio-group mt-3" style="display:flex">
  <input type="radio" id="yes" name="needMouse" value="yes">
  <label for="yes">Yes</label><br>
  <input type="radio" id="no" name="needMouse" value="no" style="margin-left:10px">
  <label for="no">No</label><br>
  </div>
 
</div>

                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <label for="fileInput" style="color:#778899;font-weight:500;font-size:12px;cursor:pointer;">
                                                                <i class="fa fa-paperclip"></i> Attach Image
                                                            </label>
                                                        </div>
                                                        @error('file_paths') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div>
                                                    <input id="file_paths" type="file" wire:model="file_paths" multiple />

                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" wire:click="Offboarding" class="submit-btn">Submit</button>
                                                <button wire:click="closecatalog" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);">Cancel</button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-backdrop fade show blurred-backdrop" style="{{ $showModal ? '' : 'display: none;' }}"></div>
        @endif
        <!-- modals for incident requst -->
        @if($IDRequestaceessDialog)
        <div class="modal" tabindex="-1" role="dialog" style="{{ $showModal ? 'display: block;' : 'display: none;' }}">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header" style="background:white;height:80px">
                                        <h4 class="modal-title fs-5" id="exampleModalLabel" style="color:black">
    Employee Offboarding Request<br> 
    <p style="font-size:12px; margin-top:5px; font-weight:300;">
        Employee Offboarding Request
    </p>
</h4>

  
</div>



                                            <div class="modal-body">
                                            <div class="row m-0">
    <div class="col " style="text-align: right; padding: 0;"> <!-- Align text to left -->
        <img src="images/it-images/offboarding.png" style="display: block; margin-right: 0;height:100px" />
    </div>
</div>


                                                <hr style="border: 1px solid #ccc;margin: 10px 0;">
                                                <form wire:submit.prevent="Offboarding">




                                                    <div class="form-group  mt-2">
                                                        <label for="Name">Requested By:</label>


                                                        <div class="input-group mb-3">



                                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-info-circle" style="color:blue"></i></span> <!-- Change label as needed -->
                                                            @if($employeeDetails)
                                                            <input wire:model.lazy="full_name" type="text" class="form-control" aria-describedby="basic-addon1" readonly>
                                                            @else
                                                            <p>No employee details found.</p>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="Name">Requested For:</label>
                                                        <div class="input-group mb-3">
                                                            <!-- Info icon on the left side -->
                                                            <span class="input-group-text" id="basic-addon">
                                                                <i class="fa fa-info-circle" style="color:blue"></i>
                                                            </span>

                                                            <!-- Employee details input -->

                                                            <input type="text" wire:click="NamesSearch"
                                                                value="{{ implode(', ', array_unique($selectedPeopleNames)) }}"
                                                                class="form-control"
                                                                aria-describedby="basic-addon1"
                                                                readonly>



                                                            <!-- Dropdown toggle icon on the right side -->
                                                            <button class="btn btn-outline-secondary dropdown-toggle" style="border:1px solid silver" wire:click="NamesSearch" type="button" data-bs-toggle="dropdown">
                                                            </button>

                                                        </div>

                                                        @if($isNames)
                                                        <div style="border-radius:5px; background-color:grey; padding:8px; width:330px; margin-top:10px; height:200px; overflow-y:auto;">
                                                            <div class="input-group4" style="display: flex; align-items: center; width: 100%;">
                                                                <input wire:model="searchTerm" style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" type="text" class="form-control" placeholder="Search for Emp.Name or ID" aria-label="Search" aria-describedby="basic-addon1">
                                                                <div class="input-group-append" style="display: flex; align-items: center;">
                                                                    <button wire:click="filter" style="height: 30px; border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none; padding: 0 10px;" class="btn" type="button">
                                                                        <i style="text-align: center;" class="fa fa-search"></i>
                                                                    </button>

                                                                    <button wire:click="closePeoples" type="button" class="close rounded px-1 py-0" aria-label="Close" style="background-color: rgb(2,17,79); height: 30px; width: 30px; margin-left: 5px; display: flex; align-items: center; justify-content: center;">
                                                                        <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            @if ($peopleData->isEmpty())
                                                            <div class="container" style="text-align: center; color: white; font-size:12px">
                                                                No People Found
                                                            </div>
                                                            @else
                                                            @foreach($peopleData->sortBy(function($people) { return strtolower($people->first_name) . ' ' . strtolower($people->last_name); }) as $people)
                                                            <label wire:click="addselectPerson('{{ $people->emp_id }}')" class="container" style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-top: 10px; width: 300px; border-radius: 5px;">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <input type="checkbox" id="person-{{ $people->emp_id }}" class="form-check-input custom-checkbox-helpdesk" wire:model="addselectedPeople" value="{{ $people->emp_id }}" {{ in_array($people->emp_id, $addselectedPeople) ? 'checked' : '' }}>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        @if (!empty($people->image) && $people->image !== 'null')
                                                                        <img class="profile-image" src="data:image/jpeg;base64,{{($people->image) }}">
                                                                        @else
                                                                        @php $gender = $people->gender ?? null; @endphp
                                                                        @if ($gender === 'Male')
                                                                        <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                                                                        @elseif($gender === 'Female')
                                                                        <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                                                                        @else
                                                                        <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                                        @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="col">
                                                                        <h6 class="username" style="font-size: 12px; color: white;">{{ ucwords(strtolower($people->first_name)) }} {{ ucwords(strtolower($people->last_name)) }}</h6>
                                                                        <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $people->emp_id }})</p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            @endforeach

                                                            @endif
                                                        </div>
                                                        @endif

                                                    </div>
                                                    <div style="display:flex">

                                                        <div class="form-group col-md-6 mt-2">

                                                            <label for="mobile">Mobile Number <span style="color:red">*</span></label>
                                                            <input wire:model="mobile" wire:keydown.debounce.500ms="validateField('mobile')" type="text" class="form-control">
                                                            @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="form-group col-md-6 mt-2 ml-3" style="margin-left:10px">
                                                            <label for="contactDetails">Email <span style="color:red">*</span></label>
                                                            <input wire:model.lazy="mail" wire:keydown.debounce.500ms="validateField('mail')" type="text" class="form-control">
                                                            @error('mail') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>

                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="priority" class="helpdesk-label">Priority<span style="color:red">*</span></label>
                                                        <div class="input" class="form-control placeholder-small">
                                                            <div style="position: relative;">
                                                                <select name="priority" id="priority" wire:keydown.debounce.500ms="validateField('priority')" wire:model.lazy="priority" style="font-size: 12px; " class="form-control placeholder-small">
                                                                    <option style="color: grey;" value="" hidden>Select Priority</option>
                                                                    <option value="Low">Low</option>
                                                                    <option value="Medium">Medium</option>
                                                                    <option value="High">High</option>


                                                                </select>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                                    class="bi bi-caret-down" viewBox="0 0 16 16" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); pointer-events: none;align-items :center">
                                                                    <path d="M3.204 5h9.592L8 10.481 3.204 5z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        @error('priority') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="form-group mt-2">
    <label for="last_working_day">Last Working Day<span style="color:red">*</span></label>
    <input wire:model="last_working_day" wire:keydown.debounce.500ms="validateField('last_working_day')" type="date" class="form-control">
    @error('last_working_day') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                                                 

                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <label for="fileInput" style="color:#778899;font-weight:500;font-size:12px;cursor:pointer;">
                                                                <i class="fa fa-paperclip"></i> Attach Image
                                                            </label>
                                                        </div>
                                                        @error('file_paths') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div>
                                                    <input id="file_paths" type="file" wire:model="file_paths" multiple />

                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" wire:click="Offboarding" class="submit-btn">Submit</button>
                                                <button wire:click="closecatalog" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);">Cancel</button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-backdrop fade show blurred-backdrop" style="{{ $showModal ? '' : 'display: none;' }}"></div>
        @endif
    </div>


        @if ($activeTab == "active")
        <div class="row align-items-center " style="margin-top:20px;">
            <div class="col-12 col-md-3  ">
                <div class="input-group request-input-group-container">
                    <input wire:input="searchActiveHelpDesk" wire:model="activeSearch" type="text"
                        class="form-control request-search-input" placeholder="Search..." aria-label="Search"
                        aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button wire:click="searchActiveHelpDesk" class="request-search-btn " type="button">
                            <i class="fa fa-search request-search-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 " style="margin-top:-5px">
            <select wire:model="activeCategory" wire:change="searchActiveHelpDesk" id="activeCategory" class="form-select" style="height:33px; font-size:0.8rem;">
    <option value="" class="option-default">Select Request</option>
    <option value="Service Request" class="option-item">Service Request</option>
    <option value="Incident Request" class="option-item">Incident Request</option>
</select>

            </div>
        </div>


        <div class="view-history-container">
        @if ($searchData && $searchData->whereIn('status_code', [8, 10])->isEmpty())
        <div class="no-history">
            <img src="https://via.placeholder.com/150" alt="No History Found">
            <p>No history items found.</p>
        </div>
    @else
    @foreach ($searchData->whereIn('status_code', [8, 10])->sortByDesc('created_at') as $record)
            <div class="history-card">
                <!-- Header with user and action details -->
                <div class="history-header">
                    <div class="user-details">
                        <span class="user-icon">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <div>
                        <h8>{{ ucfirst(strtolower($record->emp->first_name)) }} {{ ucfirst(strtolower($record->emp->last_name)) }}(#{{ $record->emp_id }})</h8>
                            
                        </div>
                    </div>
                    <div class="history-date">
                  <p>{{ $record->create_at ? $record->create_at->format('d-m-Y') : '-' }}</p> 

                       
                    </div>
                </div>

                <!-- Body with content description -->
                <div class="history-body">
                <h7>Requested For:</h7>
                <h8>{{ $record->cc_to ?? '-' }}</h8><br>
                            <div style="margin-top:5px">
                            <h7>Last Working Day:</h7> {{ $record->last_working_day ? \Carbon\Carbon::parse($record->last_working_day)->format('d-m-Y') : '-' }}
                            </div>
                            <div style="margin-top:5px">
                            <h7 >Email:</h7> 
                            <a href="#" onclick="viewAttachment">{{ $record->mail ?? '-' }}</a>
                        </div>
                        <div style="margin-top:5px">
                            <h7 >Mobile:</h7> 
                            <a href="#" onclick="viewAttachment">{{ $record->mobile ?? '-' }}</a>
                        </div>

                    @if ($record->file_paths)
                        <div style="margin-top:5px">
                            <h7 >Attachments:</h7> 
                            <a href="#" onclick="viewAttachment">View Attachments</a>
                        </div>
                    @endif
                 
                </div>

                <!-- Footer with status -->
                <div class="history-footer">
                <span class="status {{ $record->status_code == 10 ? 'complete' : ($record->status_code == 8 ? 'pending' : 'other') }}">
                        <i class="{{ $record->status_code == 10 ? 'fas fa-check-circle' : ($record->status_code == 8 ? 'fas fa-clock' : 'fas fa-circle') }}"></i>
                        {{ $record->status->status_name ?? '-' }}
                    </span>
                </div>
            </div>
        @endforeach
    @endif
</div>




        @endif










        @if ($activeTab == "closed")
        <div class="row align-items-center" style="margin-top:20px">

            <div class="col-12 col-md-3 ">
            <div class="input-group request-input-group-container">
                    <input wire:input="searchActiveHelpDesk" wire:model="activeSearch" type="text"
                        class="form-control request-search-input" placeholder="Search..." aria-label="Search"
                        aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button wire:click="searchActiveHelpDesk" class="request-search-btn " type="button">
                            <i class="fa fa-search request-search-icon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3" style="margin-top:-5px">
            <select wire:model="closedCategory" wire:change="searchClosedHelpDesk" id="closedCategory" class="form-select" style="height:33px; font-size:0.8rem;">
    <option value="" class="option-default">Select Request</option>
    <option value="Service Request" class="option-item">Service Request</option>
    <option value="Incident Request" class="option-item">Incident Request</option>
</select>
            </div>
        </div>
        <div class="help-desk-table">

            <table class="help-desk-table-main">
                <thead>
                    <tr class="help-desk-table-row">
                        <th class="help-desk-table-column">Request Raised By</th>
                        <th class="help-desk-table-column">Request ID</th>
                        <th class="help-desk-table-column">Short Description</th>
                      
                        <th class="help-desk-table-column">Description</th>
                        <th class="help-desk-table-column">Attach Files</th>
                        <th class="help-desk-table-column">Priority</th>
                        <th class="help-desk-table-column">Status</th> <!-- Added Status Column -->
                    </tr>
                </thead>
                <tbody>
          
        </tbody>
        </table>


    </div>
    @endif



    @if ($activeTab == "pending")
    <div class="row align-items-center" style="margin-top:20px">
        <div class="col-12 col-md-3 ">
        <div class="input-group request-input-group-container">
                    <input wire:input="searchActiveHelpDesk" wire:model="activeSearch" type="text"
                        class="form-control request-search-input" placeholder="Search..." aria-label="Search"
                        aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button wire:click="searchActiveHelpDesk" class="request-search-btn " type="button">
                            <i class="fa fa-search request-search-icon"></i>
                        </button>
                    </div>
                </div>
        </div>
        <div class="col-12 col-md-3" style="margin-top:-2px">
        <select wire:model="pendingCategory" wire:change="searchPendingHelpDesk" id="pendingCategory" class="form-select" style="height:33px; font-size:0.8rem;">
    <option value="" class="option-default">Select Request</option>
    <option value="Service Request" class="option-item">Service Request</option>
    <option value="Incident Request" class="option-item">Incident Request</option>
</select>
        </div>
    </div>
    <div class="help-desk-table">
        <table class="help-desk-table-main">
        <thead>
                    <tr class="help-desk-table-row">
                        <th class="help-desk-table-column">Request Raised By</th>
                        <th class="help-desk-table-column">Request ID</th>
                        <th class="help-desk-table-column">Short Description</th>
                      
                        <th class="help-desk-table-column">Description</th>
                        <th class="help-desk-table-column">Attach Files</th>
                        <th class="help-desk-table-column">Priority</th>
                        <th class="help-desk-table-column">Status</th> <!-- Added Status Column -->
                    </tr>
                </thead>
            <tbody>
            
            </tbody>
        </table>


    </div>
    @endif
</div>
</div>