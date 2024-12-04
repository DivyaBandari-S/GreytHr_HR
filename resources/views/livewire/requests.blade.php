<div class="position-relative">
    <div wire:loading
        wire:target="open,file_path,submitHR,Catalog,activeTab,LapRequest,closeImageDialog,downloadImage,showImage,">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>

        </div>
    </div>
    <div style="overflow-x:hidden">
        <div class="row mt-3">

            <div class="d-flex border-0  align-items-center justify-content-center">
                <div class="nav-buttons d-flex justify-content-center">
                    <ul class="nav ">

                        <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-active">
                            <div href="#"
                                wire:click="$set('activeTab', 'active')"
                                class="reviewActiveButtons custom-nav-link  {{ $activeTab === 'active' ? 'active left-radius' : '' }}">
                                Active
                            </div>

                        </li>

                        <li class="pendingCustomStyles custom-item m-0 p-0 flex-grow-1">
                            <a href="#"
                                wire:click="$set('activeTab', 'pending')"
                                class="custom-nav-link {{ $activeTab === 'pending' ? 'active' : '' }}">
                                Pending
                            </a>
                        </li>

                        <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-closed">
                            <a href="#"
                                wire:click="$set('activeTab', 'closed')"
                                class="reviewClosedButtons custom-nav-link {{ $activeTab === 'closed' ? 'active' : '' }}">
                                Closed
                            </a>
                        </li>
                    </ul>
                </div>

            </div>







        </div>
        <div class="row m-0">
    
        <div class="col-md-12 mb-2" >
            <div class="newReq" style="align-items:end">
                <button class="submit-btn" wire:click="LapRequest">
                    Laptop Request
                </button>
                <button class="submit-btn" wire:click="IDRequest">
                    ID Card Request
                </button>
            </div>
        </div>
        <!-- modals for service requst -->
        @if($LapRequestaceessDialog)
        <div class="modal" tabindex="-1" role="dialog" style="{{ $showModal ? 'display: block;' : 'display: none;' }}">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Laptop Request</h1>

                                            </div>

                                            <div class="modal-body">
                                                <div class="row m-0">
                                                    <div class="col-4 m-auto">
                                                        <img src="images/it-images/laptop.png" style="height:4em;" />
                                                    </div>
                                                    <div class="col-8 m-auto">
                                                        <p style="font-size:15px;"> Laptop Request</p>
                                                    </div>
                                                </div>
                                                <hr style="border: 1px solid #ccc;margin: 10px 0;">
                                                <form wire:submit.prevent="Devops">




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
                                                        <label for="contactDetails">Business Justification<span style="color:red">*</span></label>
                                                        <input wire:model="subject" wire:keydown.debounce.500ms="validateField('subject')" type="text" class="form-control">
                                                        @error('subject') <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="reason">Specific Information<span style="color:red">*</span></label>
                                                        <textarea wire:model="description" wire:keydown.debounce.500ms="validateField('description')" class="form-control"></textarea>
                                                        @error('description') <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <label for="fileInput" style="color:#778899;font-weight:500;font-size:12px;cursor:pointer;">
                                                                <i class="fa fa-paperclip"></i> Attach Image
                                                            </label>
                                                        </div>
                                                        @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div>
                                                        <input type="file" wire:model="file_path" id="file_path" class="form-control">

                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <div class="m-0 p-0 mt-3 d-flex gap-3 justify-content-center">

                                                    <button type="button" wire:click="Devops" class="submit-btn">Submit</button>

                                                    <button wire:click="closecatalog" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);">Cancel</button>
                                                </div>

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
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">ID Card Request</h1>

                                            </div>

                                            <div class="modal-body">
                                                <div class="row m-0">
                                                    <div class="col-4 m-auto">
                                                        <img src="images/it-images/id-card.png" style="height:4em;" />
                                                    </div>
                                                    <div class="col-8 m-auto">
                                                        <p style="font-size:15px;">New ID Card Request</p>
                                                    </div>
                                                </div>
                                                <hr style="border: 1px solid #ccc;margin: 10px 0;">
                                                <form wire:submit.prevent="Devops">




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
                                                        <label for="contactDetails">Business Justification<span style="color:red">*</span></label>
                                                        <input wire:model="subject" wire:keydown.debounce.500ms="validateField('subject')" type="text" class="form-control">
                                                        @error('subject') <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mt-2">
                                                        <label for="reason">Specific Information<span style="color:red">*</span></label>
                                                        <textarea wire:model="description" wire:keydown.debounce.500ms="validateField('description')" class="form-control"></textarea>
                                                        @error('description') <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="row mt-2">
                                                        <div class="col">
                                                            <label for="fileInput" style="color:#778899;font-weight:500;font-size:12px;cursor:pointer;">
                                                                <i class="fa fa-paperclip"></i> Attach Image
                                                            </label>
                                                        </div>
                                                        @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>

                                                    <div>
                                                        <input type="file" wire:model="file_path" id="file_path" class="form-control">

                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" wire:click="Devops" class="submit-btn">Submit</button>
                                                <button wire:click="closecatalog" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);">Cancel</button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-backdrop fade show blurred-backdrop" style="{{ $showModal ? '' : 'display: none;' }}"></div>
        @endif
    </div>


        @if ($activeTab == "active")
        <div class="row align-items-center " style="margin-top:20px">
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



        <div class="help-desk-table">

        <table class="help-desk-table-main">
    <thead class="help">
        <tr class="help-desk-table-row">
            <th class="help-desk-table-column">Request Raised By</th>
            <th class="help-desk-table-column">Request ID</th>
            <th class="help-desk-table-column">Category</th>
            <th class="help-desk-table-column">Short Description</th>
       
            <th class="help-desk-table-column">Description</th>
            <th class="help-desk-table-column">Attach Files</th>
            <th class="help-desk-table-column">Priority</th>
            <th class="help-desk-table-column">Status</th>
        </tr>
    </thead>
    <tbody>
     
    
   
     
    </tbody>
    <tbody>

    </tbody>
    
</table>

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