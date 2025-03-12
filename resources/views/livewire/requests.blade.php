<div >
<div class="position-absolute" wire:loading
        wire:target="searchData,NamesSearch,IDRequest,">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>
        </div>
    </div>


<div class="row justify-content-center mt-2"  >
                        <div class="col-md-9 custom-container d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
    <p class="main-text mb-0" style="width:88%">Offboarding is the process of managing an employee’s departure from  company.ffboarding programs go further, aiming to leave a positive lasting impression on the departing employee..
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
                    <div class="row m-0">
    
    <div class="col-md-10 " >
        <div class="newReq mt-3" style="align-items:end">
       
            <button class="cancel-btn" wire:click="IDRequest">
            Employee Offboarding 
            </button>
        </div>
    </div>
    </div>
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
    <img src="{{ asset('images/it-images/offboarding.png') }}" style="display: block; margin-right: 0; height:90px;" />

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
                                                                    <button wire:click="filter" style="height: 30px; border-radius: 0 5px 5px 0; background-color: var(--main-button-color); color: #fff; border: none; padding: 0 10px;" class="btn" type="button">
                                                                        <i style="text-align: center;" class="fa fa-search"></i>
                                                                    </button>

                                                                </div>
                                                                
                                                                <button wire:click="closePeoples" type="button" class="close-btn rounded px-1 py-0" aria-label="Close" style="background-color: var(--main-button-color); height: 30px; width: 30px; margin-left: 5px; display: flex; align-items: center; justify-content: center;">
                                                                        <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
                                                                    </button>
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
        <div class="row align-items-start justify-content-start">
    <div class="col-12 col-md-3 align-items-center">
        <div class="input-group request-input-group-container" style="margin-left:130px">
            <input  wire:model="activeSearch" type="text"
                class="form-control request-search-input" placeholder="Search..." aria-label="Search">
            <div class="input-group-append" style="margin-left:-10px">
                <button wire:click="searchActiveHelpDesk" class="request-search-btn" type="button">
                    <i class="fa fa-search request-search-icon"></i>
                </button>
            </div>
        </div>
    </div>
 
    @if ($searchData && $searchData->isNotEmpty()) 
    <div class="row align-items-center justify-content-center">
        <table class="employee-requests-table table table-bordered table-striped mt-5">
            <thead class="table-header">
                <tr class="header-row">
                    <th class="header-column">Employee ID</th>
                    <th class="header-column">Priority</th>
                    <th class="header-column">Email</th>
                    <th class="header-column">Mobile</th>
                    <th class="header-column">Status</th>
                    <th class="header-column">Last Working Day</th>
                </tr>
            </thead>
            <tbody class="table-body">
            @foreach ($searchData->sortByDesc('created_at') as $index => $request)
                    <tr class="body-row">
                        <td class="body-column">{{ $request->cc_to }}</td>
                        <td class="body-column">{{ $request->priority }}</td>
                        <td class="body-column">{{ $request->mail }}</td>
                        <td class="body-column">{{ $request->mobile }}</td>
                        <td class="body-column">{{$request->status->status_name}}</td>
                        <td class="body-column">{{\Carbon\Carbon::parse($request->last_working_day)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif ($searchData && $searchData->isEmpty())
    <tr>
<td> 
<div class="d-flex justify-content-center align-items-center mt-2">
                <div class="card p-4 text-center">
                <div class="no-data p-4 text-align-center justify-content-center align-items-center" style="width:700px;height:150px">  
<img style="width: 10em; margin: 20px;" 
            src="{{ asset('images/norecordstoshow.png') }}" 
                 alt="No items found">
                 <p>No Record Found</p>
                </div>
            </div>
        </div></td>
    </tr>
    @else
    <div class="d-flex justify-content-center align-items-center mt-2">
                <div class="card p-4 text-center">
                <div class="no-data p-4 text-align-center justify-content-center align-items-center" style="width:700px;height:260px">
            <img style="width: 10em; margin: 20px;" 
            src="{{ asset('images/norecordstoshow.png') }}" 
                 alt="No items found">
                 <p>No Record Found</p>
     </div>
     </div>
     </div>
    @endif
</div>


    </div>


 




