<div>

    @if( $employeeDetails->isEmpty())
    <p>No employee details found.</p>

    @else
    <div class="px-4" style="position: relative;">
   
        @if ($message)
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 500px; margin: auto;">
                {{ $message }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"
                        style="font-size: 0.75rem; padding: 0.2rem 0.4rem; margin-top: 4px;"></button>
                </div>
                @endif
        <div class="col-md-12  mt-3" style="height:60px;margin-top:10px">

            <div class="row bg-white rounded border d-flex" style="height:80px; ">
                <div style="display:flex; flex-direction:row;">


                    <div class=" mt-3" style="height:60px">
                        @if(auth('emp')->check() || auth('hr')->check())
                        @php
                        // Determine the employee ID based on the authentication guard
                        $empEmployeeId = auth('emp')->check() ? auth('emp')->user()->emp_id : auth('hr')->user()->hr_emp_id;

                        // Fetch the employee details from EmployeeDetails model
                        $employeeDetails = \App\Models\EmployeeDetails::where('emp_id', $empEmployeeId)->first();
                        @endphp

                        @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
                        <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
                        @else
                        @if($employeeDetails && $employeeDetails->gender == "Male")
                        <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                        @elseif($employeeDetails && $employeeDetails->gender == "Female")
                        <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                        @else
                        <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                        @endif
                        @endif
                        @else
                        <p>User is not authenticated.</p>
                        @endif
                    </div>
                    <div style="color:#3b4452; display:flex;flex-direction:column;justify-content:center;margin-left:10px">
                        @if(auth()->guard('emp')->check())
                        <span class="text-base">Hey {{ ucwords(strtolower(auth()->guard('emp')->user()->first_name)) }} {{ ucwords(strtolower(auth()->guard('emp')->user()->last_name)) }}</span>
                        @elseif(auth()->guard('hr')->check())
                        <span class="text-base">Hey {{ ucwords(strtolower(auth()->guard('hr')->user()->employee_name)) }}</span>
                        @else
                        <p>No employee details available.</p>
                        @endif

                        <div class="text-xs" style="color:#3b4452;">Ready to dive in?</div>
                    </div>
                    <div style="margin-left:auto; display:flex;align-items:center">
                        <button wire:click="addFeeds" class="flex flex-col justify-between items-start group w-20  p-1 rounded-md border border-purple-200" style="background-color: #F1ECFC;border:1px solid purple;border-radius:5px;width:130px">
                            <div class="w-6 h-6 rounded bg-purple-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current group-hover:text-purple-600 stroke-1 text-purple-400">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                            </div>
                            <div class="row  mt-1">
                                <div class="text-left text-xs" style="margin-left:5px" wire:click="addFeeds">Create Posts</div>

                            </div>
                        </button>
                    </div>
                </div>
                <div class=" mt-2 bg-white d-flex align-items-center ">

                    <div style="display:flex;margin-left:auto">


                        @if($showFeedsDialog)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block; color: #3b4452; font-family: Montserrat, sans-serif;">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="color: #3b4452;">
                                <div class="modal-content" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                    <div class="modal-header" style="border-bottom: 1px solid #ccc; padding: 15px; height: 40px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                                        <!-- <h5 class="modal-title" style="font-weight: 500; font-size: 1.25rem; color: #3b4452;">Creating a Post</h5> -->
                                        <p style="font-weight:600px">Create a post</p>
                                        <span style="margin-left: auto;margin-top:-5px">
                                            <img src="{{ asset('images/Posts.jpg') }}" style="height: 30px; border-radius: 5px;">
                                        </span>
                                    </div>


                                    @if(Session::has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-center" role="alert"
                                        style="font-size: 0.875rem; width: 90%; margin: 10px auto; padding: 10px; border-radius:4px; background-color: #f8d7da; color: #721c24;">
                                        {{ Session::get('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-left: 10px;margin-top:-5px"></button>
                                    </div>
                                    @endif
                                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
    <div class="modal-body" style="padding: 20px;">
        <!-- Category Selection -->
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="category" style="font-weight: 600; color: #3b4452;">You are posting in:</label>
            <select wire:model.lazy="category" class="form-select" id="category" style="border: 1px solid #ccc; border-radius: 4px; padding: 5px; font-size: 0.75rem; color: #3b4452; margin-top: 5px; height: 30px;">
                <option value="">Select Category</option>
                <option value="Appreciations">Appreciations</option>
                <option value="Buy/Sell/Rent">Buy/Sell/Rent</option>
                <option value="Companynews">Company News</option>
                <option value="Events">Events</option>
                <option value="Everyone">Everyone</option>
                <option value="Hyderabad">Hyderabad</option>
                <option value="US">US</option>
            </select>
            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Description Input -->
        <div class="form-group">
            <label for="content" style="font-weight: 600; color: #3b4452;">Write something here:</label>
            <textarea wire:model.lazy="description" class="form-control" id="content" rows="2" style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; font-size: 0.875rem; resize: vertical; width: 100%; margin-left: -250px; margin-top: 5px" placeholder="Enter your description here..."></textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
       <!-- File Input -->
       <div id="flash-message-container" style="display: none;margin-top:10px" class="alert alert-success"
                                    role="alert"></div>
        <!-- File Upload -->
        <div class="form-group" style="margin-top: 5px;">
            <label for="file_path" style="font-weight: 600; color: #3b4452;">Upload Attachment:</label>
            <div style="text-align: start;">
           

                <input type="file" wire:model="file_path" class="form-control"  id="file_path"  style="margin-top:5px" onchange="handleImageChange()">
                @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror

                <!-- Success Message -->
           
        
            </div>
        </div>
    </div>

    <!-- Submit & Cancel Buttons -->
    <div class="modal-footer" style="border-top: 1px solid #ccc;">
    <div class="d-flex justify-content-center" style="width: 100%;">
            <button type="submit" wire:target="file_path" wire:loading.attr="disabled" class="submit-btn">Submit</button>
            <button wire:click="closeFeeds" type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79); margin-left: 10px">Cancel</button>
        </div>
    </div>
</form>


                                </div>
                            </div>
                        </div>




                        <div class="modal-backdrop fade show"></div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Additional row -->
            <div class="row mt-2 d-flex">

                <div class="col-md-4 bg-white p-3" style="border-radius:5px;border:1px solid silver;height:fit-content">

                    <p style="font-weight: 500;font-size:13px;color:#47515b;">Filters</p>
                    <hr style="width: 100%;border-bottom: 1px solid grey;">


                    <p style="font-weight: 500;font-size:13px;color:#47515b;cursor:pointer">Activities</p>
                    <div class="activities" style="width: 100%; height: 30px;">
                        <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
                            <input type="radio" name="radio" value="activities" checked data-url="/Feeds" onclick="handleRadioChange(this)">
                            <div class="feed-icon-container" style="margin-left: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <rect x="7" y="7" width="3" height="9"></rect>
                                    <rect x="14" y="7" width="3" height="5"></rect>
                                </svg>
                            </div>
                            <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 8px;"></span>
                            <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">All Activities</span>
                        </label>
                    </div>


                    <div class="posts" style="width: 100%; height: 30px;">
                        <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
                            @if(auth()->guard('emp')->check())
                            <input type="radio" id="radio-emp" name="radio" value="posts" data-url="/everyone" onclick="handleRadioChange(this)">
                            @elseif(auth()->guard('hr')->check())
                            <input type="radio" id="radio-hr" name="radio" value="posts" data-url="/hreveryone" onclick="handleRadioChange(this)">
                            @else
                            <p>No employee details available.</p>
                            @endif
                            <div class="feed-icon-container" style="margin-left: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                            </div>
                            <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 10px;"></span>
                            <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">Posts</span>
                        </label>
                    </div>
                    @if($isManager)
    <div class="post-requests" style="width: 100%; height: 30px;">
        <label class="custom-radio-label" style="display: flex; align-items: center; padding: 5px; height: 100%;">
            @if(auth()->guard('emp')->check())
                <input type="radio" id="radio-emp" name="radio" style="margin-left:5px" value="post-requests" data-url="/emp-post-requests" onclick="handleRadioChange(this)">
            @elseif(auth()->guard('hr')->check())
                <input type="radio" id="radio-hr" name="radio" style="margin-left:5px" value="post-requests" data-url="/hr-post-requests" onclick="handleRadioChange(this)">
            @endif
            <div class="feed-icon-container" style="margin-left: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file stroke-current text-purple-400 stroke-1" style="width: 1rem; height: 1rem;">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                    <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
            </div>
            <span class="custom-radio-button bg-blue" style="margin-left: 10px; font-size: 10px;"></span>
            <span style="color: #778899; font-size: 12px; font-weight: 500; margin-left: 5px;">Post Requests</span>
        </label>
    </div>
@endif


                    <hr style="width: 100%;border-bottom: 1px solid grey;">
                    <div>
                        <div class="row" style="max-height:auto">
                            <div class="col " style="margin: 0px;">
                                <div class="input-group">
                                    <input wire:model="search" id="filterSearch" onkeyup="filterDropdowns()" style="width:80%;font-size: 10px; border-radius: 5px 0 0 5px;" type="text" class="form-control" placeholder="Search...." aria-label="Search" aria-describedby="basic-addon1">
                                    <button style="border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none;" class="search-btn" type="button">
                                        <i style="text-align: center;color:white;margin-left:10px" class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full visible mt-1" style="margin-top:20px;display:block">
                            <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;" onclick="toggleDropdown('dropdownContent1', 'arrowSvg1')">
                                <span class="text-xs leading-4" style="font-weight:bold; color: grey;">Groups</span>
                                <span class="arrow-icon" id="arrowIcon1" style="margin-top:-5px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg1" style="color:#3b4452;margin-top:-5px">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div id="dropdownContent1" style="display: none;">
                                <ul class="d-flex flex-column" style="font-size: 12px; line-height: 1; text-decoration: none; color:black;text-align: left; padding-left: 0;overflow-y:auto;max-height:200px;overflow-x: hidden;">
                                    <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">All Feeds</a>
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Every One</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Every One</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Events</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/Feeds" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Events</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Company News</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Company News</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Appreciation</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Appreciation</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Buy/Sell/Rent</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Buy/Sell/Rent</a>
                                    @endif
                                </ul>
                            </div>
                        </div>


                        <div class="w-full visible mt-1" style="margin-top: 20px;display:block;">
                            <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;">
                                <span class="text-xs leading-4 " style="font-weight: bold;color:grey">Location</span>
                                <span class="arrow-icon" id="arrowIcon2" onclick="toggleDropdown('dropdownContent2', 'arrowSvg2')" style="margin-top:-5px;color:#3b4452;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg2" style="color:#3b4452;margin-top:-5px">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div id="dropdownContent2" style="font-size: 12px; line-height: 1; text-decoration: none; color:#3b4452; text-align: left; padding-left: 0; display: none;overflow-y:auto;max-height:200px;overflow-x: hidden;">
                                <ul class="d-flex flex-column" style="font-size: 12px; margin: 0; padding: 0;">
                                    <b class="menu-item" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">India</b>

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Adilabad</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Adilabad</a>
                                    @endif

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Doddaballapur</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Doddaballapur</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Guntur</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Guntur</a>

                                    @endif

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hoskote</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hoskote</a>
                                    @endif

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hyderabad</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Hyderabad</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mandya
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mandya
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mangalore
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mangalore
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mumbai
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mumbai
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mysore
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Mysore
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Pune
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Pune
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Sirsi
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Sirsi
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Thumkur
                                    </a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Thumkur
                                    </a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Tirupati</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Tirupati</a>
                                    @endif

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Trivandrum</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Trivandrum</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Udaipur</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Udaipur</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Vijayawada</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">Vijayawada</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;font-weight:700">USA</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;font-weight:700">USA</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">California</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">California</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">New York</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease;color:#3b4452;">New York</a>
                                    @endif

                                    @if (Auth::guard('hr')->check())

                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hawaii</a>

                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block;  padding: 5px 10px; transition: background-color 0.3s ease; color:#3b4452;">Hawaii</a>
                                    @endif

                                </ul>
                            </div>
                        </div>
                        <div class="w-full visible mt-1" style="margin-top: 20px;display:block">
                            <div class="cus-button" style="display: flex; justify-content: space-between; width: 100%; padding: 0.5rem;overflow-y:auto;max-height:fit-content;overflow-x: hidden">
                                <span class="text-xs leading-4" style="font-weight: bold; color: grey;">Department</span>
                                <span class="arrow-icon" id="arrowIcon3" onclick="toggleDropdown('dropdownContent3', 'arrowSvg3')" style="margin-top:-5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400" id="arrowSvg3" style="color:#3b4452;">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div id="dropdownContent3" style="font-size: 12px; line-height: 1; text-decoration: none; color: black; text-align: left; padding-left: 0; display: none;">
                                <ul class="d-flex flex-column" style="font-size: 12px; margin: 0; padding: 0;">
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">HR</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">HR</a>
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations Team</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Operations</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Production Team</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Production Team</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">QA</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">QA</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Sales Team</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Sales Team</a>
                                    @endif
                                    @if (Auth::guard('hr')->check())
                                    <a class="menu-item" href="/hrevents" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Testing Team</a>
                                    @elseif (Auth::guard('emp')->check())
                                    <a class="menu-item" href="/events" style="margin-top: 5px; display: block; padding: 5px 10px; transition: background-color 0.3s ease; color: #3b4452;">Testing Team</a>
                                    @endif
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col m-0" style="max-height: 100vh; overflow-y: auto;scroll-behavior: smooth;">
                    <div class="row align-items-center ">
                        <div class="col-md-5" style=" justify-content: flex-start;display:flex">
                            <div style="width: 2px; height: 40px; background-color: #97E8DF; margin-right: 10px;"></div>
                            <gt-heading _ngcontent-eff-c648="" size="md" class="ng-tns-c648-2 hydrated"></gt-heading>
                            <div class="medium-header border-cyan-200" style="margin-left:-1px">All Activities - All Groups</div>
                        </div>

                        <div class="col-md-5 text-right" style="display: flex; justify-content: flex-end; align-items: center; margin-left: -80px;">
                            <p style="font-size: 14px; margin-right: 5px;margin-top:10px;font-weight:500;">Sort:</p>
                            <div class="dropdown" style="position: relative; display: inline-block;margin-top:-5px">
                                <button id="dropdown-toggle" class="dropdown-toggle" style="background: none; border: none; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center;color:#3b4452;">
                                    {{ $sortType === 'newest' ? 'Newest First' : 'Most Recent Interacted' }}
                                </button>
                                <div class="dropdown-menu" style="display: {{ $dropdownVisible ? 'block' : 'none' }}; position: absolute; background-color: white; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1; min-width: 190px; right: -60px; border-radius: 4px; border: 1px solid #ddd;">
                                    <a href="#" data-sort="newest" wire:click.prevent="updateSortType('newest')" class="dropdown-item" style="padding: 8px 16px; display: block; font-size: 14px; text-decoration: none; color:#3b4452;">Newest First</a>
                                    <a href="#" data-sort="interacted" wire:click.prevent="updateSortType('interacted')" class="dropdown-item" style="padding: 8px 16px; display: block; font-size: 14px; text-decoration: none; color:#3b4452;">Most Recent Interacted</a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-10">

                        @foreach ($combinedData as $index => $data)
                        @php


                        // Group comments by card_id and count the number of comments per card
                        $cardCommentsCount = $comments->groupBy('card_id')->map(function ($comments) {
                        return $comments->count();
                        });


                        @endphp

                        @if (isset($data['type']) && $data['type'] === 'date_of_birth')

                        @if($sortType==='newest')
                        <div class="birthday-card mt-2 comment-item"
                            data-created="{{ $data['created_at'] ?? '' }}" data-interacted="{{ $data['updated_at'] ?? '' }}">

                            <div class="F mb-4" style="padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #CFCACA; color: #3b4452; margin-top: 5px">

                                <div class="row m-0">
                                    <div class="col-md-4 mb-2" style="text-align: center;">
                                        <img src="{{ $empCompanyLogoUrl }}" alt="Company Logo" style="width:120px">
                                    </div>
                                    <div class="col-md-4 m-auto" style="color: #677A8E; font-size: 14px;font-weight: 100px; text-align: center;">
                                        Group Events
                                    </div>
                                    <div class="c col-md-4 m-auto" style="font-size: 13px; font-weight: 100px; color: #9E9696; text-align: center;">
                                        {{ date('d M', strtotime($data['employee']->personalInfo->date_of_birth??'-')) }}
                                    </div>
                                </div>
                                <div class="row m-0 mt-2">
                                    <div class="col-md-4">
                                        <img src="{{ asset('images/Blowing_out_Birthday_candles_Gif.gif') }}" alt="Image Description" style="width: 120px;">
                                    </div>
                                    <div class="col-md-8 m-auto">
                                        <p style="color: #778899;font-size: 12px;font-weight:normal;">
                                            Happy Birthday {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                            {{ ucwords(strtoupper($data['employee']->last_name)) }},
                                            Have a great year ahead!
                                        </p>
                                        <div style="display: flex; align-items: center;">
                                            @if(($data['employee']->image) &&$data['employee']->image !== 'null')
                                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{$data['employee']->image_url }}" alt="Employee Image">
                                            @else
                                            @if($data['employee'] && $data['employee']->gender == "Male")
                                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                            @elseif($data['employee'] && $data['employee']->gender == "Female")
                                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                            @else
                                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                            @endif
                                            @endif







                                            <p style="margin-left: 10px; font-size: 12px; color:#3b4452;margin-bottom:0;font-weight:600;">
                                                Happy Birthday {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                                {{ ucwords(strtoupper($data['employee']->last_name)) }}! 🎂
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2 p-0" style="margin-left:5px;">
                                    @php
                                    $currentCardEmojis = $emojis->where('emp_id', $data['employee']->emp_id);
                                    $emojisCount = $currentCardEmojis->count();
                                    $lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 2, 0))->reverse();
                                    $uniqueNames = [];
                                    @endphp

                                    @if($currentCardEmojis && $emojisCount > 0)
                                    <div style="white-space: nowrap;">
                                        @foreach($lastTwoEmojis as $index => $emoji_reaction)
                                        <span style="font-size: 16px;margin-left:-7px;">{{ $emoji_reaction->emoji_reaction }}</span>
                                        @if (!$loop->last)

                                        @endif
                                        @endforeach

                                        @foreach($lastTwoEmojis as $index => $emoji)
                                        @php
                                        $fullName = ucwords(strtolower($emoji->first_name)) . ' ' . ucwords(strtolower($emoji->last_name));
                                        @endphp
                                        @if (!in_array($fullName, $uniqueNames))
                                        @if (!$loop->first)
                                        <span>,</span>
                                        @endif
                                        <span style="font-size: 8px;"> {{ $fullName }}</span>
                                        @php $uniqueNames[] = $fullName; @endphp
                                        @endif
                                        @endforeach
                                        @if (count($uniqueNames) > 0)
                                        <span style="font-size:8px">reacted</span>
                                        @endif


                                    </div>




                                    @endif
                                </div>
                                <div class="w-90" style="border-top: 1px solid #E8E5E4; margin: 10px;"></div>
                                <div class="row" style="display: flex;">
                                    <div class="col-md-5" style="display: flex;">
                                        <form wire:submit.prevent="createemoji('{{ $data['employee']->emp_id }}')">
                                            @csrf
                                            <div class="emoji-container">
                                                <span id="smiley-{{ $index }}" class="emoji-trigger" onclick="showEmojiList({{ $index }})" style="font-size: 16px;cursor:pointer">
                                                    😊




                                                    <!-- List of emojis -->
                                                    <div id="emoji-list-{{ $index }}" class="emoji-list" style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑 </span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128537')">😙</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <!-- Add more emojis here -->
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡 </span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                                            <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                                                        </div>
                                                        <div class="emoji-row">
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍 </span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>

                                                        </div>


                                        </form>
                                    </div>
                                </div>
                            </div>





                            <div class="col-md-7 p-0">
                                <div class="col-md-7 mb-2">
                                    <div style="display: flex; align-items: center;">
                                        <span>
                                            <i class="comment-icon">💬</i>
                                        </span>
                                        <span style="margin-left: 5px;">
                                            <a href="#" onclick="comment({{ $index }})" style="font-size: 10px;">Comment</a>
                                        </span>
                                    </div>
                                </div>




                            </div>
                            <div class="col-md-10">
                                <form wire:submit.prevent="add_comment('{{ $data['employee']->emp_id }}')">
                                    @csrf
                                    <div class="row m-0">


                                        <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                                            <div class="replyDiv row m-0" id="replyDiv_{{ $index }}" style="display: none;">

                                                <div class="col-md-1">
                                                    @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
                                                    <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
                                                    @else
                                                    @if($employeeDetails && $employeeDetails->gender == "Male")
                                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                                    @elseif($employeeDetails && $employeeDetails->gender == "Female")
                                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                                    @else
                                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                                    @endif
                                                    @endif


                                                </div>
                                                <div class="col-md-11" style="position: relative;">
                                                    <textarea
                                                        wire:model="newComment"
                                                        placeholder="Post your comments here.."
                                                        name="comment"
                                                        class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none"
                                                        style="overflow: hidden; height: 60px;margin-left:30px">
                    </textarea>
                                                    <input
                                                        type="submit"
                                                        class=" addcomment"
                                                        value="Comment" wire:target="add_comment">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row m-0">
                                @php
                                $currentCardComments = $comments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at');
                                @endphp
                                <div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
                                    @if($currentCardComments && $currentCardComments->count() > 0)
                                    @foreach ($currentCardComments as $comment)
                                    <div class="mb-3 comment-item" data-created="{{ $comment->created_at }}" data-interacted="{{ $comment->updated_at }}" style="display: flex; gap: 10px; align-items: center;">
                                        @if($comment->employee)
                                        @if(($comment->employee->image) &&$comment->employee->image !== 'null')

                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                                        @else
                                        @if($comment->employee && $comment->employee->gender == "Male")
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                        @elseif($comment->employee&& $comment->employee->gender == "Female")
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                        @else
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                        @endif
                                        @endif






                                        <div class="comment" style="font-size: 10px;">
                                            <b style="color:#778899; font-weight:500; font-size: 10px;">
                                                {{ ucwords(strtolower($comment->employee->first_name)) }} {{ ucwords(strtolower($comment->employee->last_name)) }}
                                            </b>
                                            <p class="mb-0" style="font-size: 11px;">
                                                {{ ucfirst($comment->comment) }}
                                            </p>
                                        </div>
                                        @elseif ($comment->hr)
                                        @if(($comment->hr->image) &&$comment->hr->image !== 'null')
                                        <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{$comment->hr->image_url }}" alt="Employee Image">
                                        @else
                                        @if($comment->hr && $comment->hr->gender == "Male")
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                        @elseif($comment->hr&& $comment->hr->gender == "Female")
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                        @else
                                        <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                        @endif
                                        @endif
                                        <div class="comment" style="font-size: 10px;">
                                            <b style="color:#778899; font-weight:500; font-size: 10px;">
                                                {{ ucwords(strtolower($comment->hr->first_name)) }} {{ ucwords(strtolower($comment->hr->last_name)) }}
                                            </b>
                                            <p class="mb-0" style="font-size: 11px;">
                                                {{ ucfirst($comment->comment) }}
                                            </p>
                                        </div>
                                        @else
                                        <div class="comment" style="font-size: 10px;">
                                            <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown Employee</b>
                                            <p class="mb-0" style="font-size: 11px;">
                                                {{ ucfirst($comment->comment) }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                    @endif
                                </div>

                            </div>



                        </div>

                    </div>
                </div>

                @else($sortType==='interacted')
                @php
                // Group comments by card_id and count the number of comments per card
                $cardCommentsCount = $comments->groupBy('card_id')->map(function ($comments) {
                return $comments->count();
                });

                // Get card IDs with more than 2 comments
                $validCardIds = $cardCommentsCount->filter(function ($count) {
                return $count > 2;
                })->keys();
                $filteredComments = $comments->whereIn('card_id', $validCardIds);

                // Check if the card is a birthday card based on your conditions,
                // for example, checking if the card_id matches the employee's emp_id.
                $birthdayCardId = $data['employee']->emp_id; // assuming this is your birthday card's ID
                @endphp
                <div class="birthday-card mt-2 comment-item"
                    data-created="{{ $data['created_at'] ?? '' }}" data-interacted="{{ $data['updated_at'] ?? '' }}">
                    @if ($filteredComments->where('card_id', $birthdayCardId)->count() > 0)
                    <div class="F mb-4" style="padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #CFCACA; color: #3b4452; margin-top: 5px">
                        <div class="row m-0">
                            <div class="col-md-3 mb-2" style="text-align: center;">
                                <img src="{{ $empCompanyLogoUrl }}" alt="Company Logo" style="width:120px">
                            </div>
                            <div class="col-md-4 m-auto" style="color: #677A8E; font-size: 14px;font-weight: 100px; text-align: center;">
                                Group Events
                            </div>
                            <div class="c col-md-4 m-auto" style="font-size: 13px; font-weight: 100px; color: #9E9696; text-align: center;">
                                {{ date('d M ', strtotime($data['employee']->date_of_birth)) }}
                            </div>
                        </div>
                        <div class="row m-0 mt-2">
                            <div class="col-md-3">
                                <img src="{{ asset('images/Blowing_out_Birthday_candles_Gif.gif') }}" alt="Image Description" style="width: 120px;">
                            </div>
                            <div class="col-md-8 m-auto">
                                <p style="color: #778899;font-size: 12px;font-weight:normal;">
                                    Happy Birthday {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                    {{ ucwords(strtoupper($data['employee']->last_name)) }},
                                    Have a great year ahead!
                                </p>
                                <div style="display: flex; align-items: center;">
                                    @if(($data['employee']->image) &&$data['employee']->image !== 'null')
                                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{$data['employee']->image_url }}" alt="Employee Image">
                                    @else
                                    @if($data['employee'] && $data['employee']->gender == "Male")
                                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                    @elseif($data['employee'] && $data['employee']->gender == "Female")
                                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                    @else
                                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                    @endif
                                    @endif

                                    <p style="margin-left: 10px; font-size: 12px; color: #47515b;margin-bottom:0;font-weight:600;">
                                        Happy Birthday {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                        {{ ucwords(strtoupper($data['employee']->last_name)) }}! 🎂
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-2 p-0" style="margin-left:5px;">
                            @php
                            $currentCardEmojis = $emojis->where('emp_id', $data['employee']->emp_id);
                            $emojisCount = $currentCardEmojis->count();
                            $lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 2, 0))->reverse();
                            $uniqueNames = [];
                            @endphp
                            @if($currentCardEmojis && $emojisCount > 0)
                            <div style="white-space: nowrap;">
                                @foreach($lastTwoEmojis as $index => $emoji_reaction)
                                <span style="font-size: 16px;">{{ $emoji_reaction->emoji_reaction }}</span>
                                @if (!$loop->last)
                                <span>,</span>
                                @endif
                                @endforeach

                                @foreach($lastTwoEmojis as $index => $emoji)
                                @php
                                $fullName = ucwords(strtolower($emoji->first_name)) . ' ' . ucwords(strtolower($emoji->last_name));
                                @endphp
                                @if (!in_array($fullName, $uniqueNames))
                                @if (!$loop->first)
                                <span>,</span>
                                @endif
                                <span style="font-size: 8px;"> {{ $fullName }}</span>
                                @php $uniqueNames[] = $fullName; @endphp
                                @endif
                                @endforeach
                                @if (count($uniqueNames) > 0)
                                <span style="font-size:8px">reacted</span>
                                @endif


                            </div>




                            @endif
                        </div>
                        <div class="w-90" style="border-top: 1px solid #E8E5E4; margin: 10px;"></div>
                        <div class="row" style="display: flex;">
                            <div class="col-md-5" style="display: flex;">
                                <form wire:submit.prevent="createemoji('{{ $data['employee']->emp_id }}')">

                                    @csrf
                                    <div class="emoji-container">
                                        <span id="smiley-{{ $index }}" class="emoji-trigger" onclick="showEmojiList({{ $index }})" style="font-size: 16px;cursor:pointer">
                                            😊




                                            <!-- List of emojis -->
                                            <div id="emoji-list-{{ $index }}" class="emoji-list" style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                                    <span class="emoji-option" style="font-size: 14px;" wire:click="addEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑 </span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128537')">😙</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <!-- Add more emojis here -->
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡 </span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                                                </div>
                                                <div class="emoji-row">
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍 </span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="addEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>


                                                </div>


                                </form>
                            </div>
                        </div>
                    </div>





                    <div class="col-md-7 p-0">
                        <div class="col-md-7 mb-2">
                            <div style="display: flex; align-items: center;">
                                <span>
                                    <i class="comment-icon">💬</i>
                                </span>
                                <span style="margin-left: 5px;">
                                    <a href="#" onclick="comment({{ $index }})" style="font-size: 10px;">Comment</a>
                                </span>
                            </div>
                        </div>




                    </div>
                    <div class="col-md-10">
                        <form wire:submit.prevent="add_comment('{{ $data['employee']->emp_id }}')">
                            @csrf
                            <div class="row m-0">


                                <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                                    <div class="replyDiv row m-0" id="replyDiv_{{ $index }}" style="display: none;">

                                        <div class="col-md-1">
                                            @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
                                            <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
                                            @else
                                            @if($employeeDetails && $employeeDetails->gender == "Male")
                                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                            @elseif($employeeDetails && $employeeDetails->gender == "Female")
                                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                            @else
                                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                            @endif
                                            @endif


                                        </div>
                                        <div class="col-md-11" style="position: relative;">
                                            <textarea
                                                wire:model="newComment"
                                                placeholder="Post your comments here.."
                                                name="comment"
                                                class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none"
                                                style="overflow: hidden; height: 60px;margin-left:30px">
                    </textarea>
                                            <input
                                                type="submit"
                                                class=" addcomment"
                                                value="Comment" wire:target="add_comment">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
                <div class="row m-0">
                    @php
                    // Group comments by card_id and count the number of comments per card
                    $cardCommentsCount = $comments->groupBy('card_id')->map(function ($comments) {
                    return $comments->count();
                    });

                    // Get card IDs with more than 2 comments
                    $validCardIds = $cardCommentsCount->filter(function ($count) {
                    return $count >= 2; // Use >= 2 to include cards with exactly 2 comments
                    })->keys();

                    // Filter comments to include only those for cards with at least 2 comments
                    $filteredComments = $comments->whereIn('card_id', $validCardIds);

                    // Sort the filtered comments based on the sortType
                    if ($sortType === 'interacted') {
                    $filteredComments = $filteredComments->sortByDesc('updated_at');
                    }
                    @endphp
                    <div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
                        @foreach ($filteredComments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at') as $comment)
                        <div class="mb-3 comment-item" data-created="{{ $comment->created_at }}" data-interacted="{{ $comment->updated_at }}" style="display: flex; gap: 10px; align-items: center;">
                            @if($comment->employee)
                            @if(($comment->employee->image) &&$comment->employee->image !== 'null')

                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                            @else
                            @if($comment->employee && $comment->employee->gender == "Male")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($comment->employee&& $comment->employee->gender == "Female")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif





                            <div class="comment" style="font-size: 10px;">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">
                                    {{ ucwords(strtolower($comment->employee->first_name)) }} {{ ucwords(strtolower($comment->employee->last_name)) }}
                                </b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->comment) }}
                                </p>
                            </div>
                            @elseif ($comment->hr)
                            @if(($comment->hr->image) &&$comment->hr->image !== 'null')
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                            @else
                            @if($comment->hr && $comment->hr->gender == "Male")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($comment->hr&& $comment->hr->gender == "Female")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif
                            <div class="comment" style="font-size: 10px;">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">
                                    {{ ucwords(strtolower($comment->hr->first_name)) }} {{ ucwords(strtolower($comment->hr->last_name)) }}
                                </b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->comment) }}
                                </p>
                            </div>
                            @else
                            <div class="comment" style="font-size: 10px;">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown Employee</b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->comment) }}
                                </p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>




            </div>
            @endif

        </div>

        @endif

        @else(isset($data['type']) && $data['type'] === 'hire_date')

        @if($sortType==='newest')
        <div class="hire-card mt-2 comment-item"
            data-created="{{ $data['created_at'] ?? '' }}" data-interacted="{{ $data['updated_at'] ?? '' }}">

            <div class="F mb-4" style="padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #CFCACA; color: #3b4452; margin-top: 5px">

                <div class="row m-0">
                    <div class="col-md-3 mb-2" style="text-align: center;">
                        <img src="{{ $empCompanyLogoUrl }}" alt="Company Logo" style="width:120px">
                    </div>
                    <div class="col-md-4 m-auto" style="color: #677A8E; font-size: 14px;font-weight: 100px; text-align: center;">
                        Group Events
                    </div>
                    <div class="c col-md-4 m-auto" style="font-size: 12px; font-weight: 100px; color: #9E9696; text-align: center;">
                        {{ date('d M Y', strtotime($data['employee']->hire_date)) }}
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-md-3">
                        <img src="{{ asset('images/New_team_members_gif.gif') }}" alt="Image Description" style="width: 120px;">
                    </div>
                    <div class="col-md-8 m-auto">
                        <p style="font-size:12px;color:#778899;font-weight:normal;margin-top:10px;padding-left:10px">
                            @php
                            $hireDate = $data['employee']->hire_date;
                            $yearsSinceHire = date('Y') - date('Y', strtotime($hireDate));
                            $yearText = $yearsSinceHire == 1 ? 'year' : 'years';
                            @endphp

                            Our congratulations to {{ ucwords(strtoupper($data['employee']->first_name)) }}
                            {{ ucwords(strtoupper($data['employee']->last_name)) }},on completing {{ $yearsSinceHire }} successful {{$yearText}}.


                        </p>
                        <div style="display: flex; align-items: center;">
                            @if(($data['employee']->image) &&$data['employee']->image !== 'null')
                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{$data['employee']->image_url }}" alt="Employee Image">
                            @else
                            @if($data['employee'] && $data['employee']->gender == "Male")
                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($data['employee'] && $data['employee']->gender == "Female")
                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif

                            @php
    // Get the hire date and calculate the number of years since hiring
    $hireDate = $data['employee']->hire_date;
    $yearsSinceHire = date('Y') - date('Y', strtotime($hireDate));
    $yearText = $yearsSinceHire == 1 ? 'year' : 'years';
@endphp

<p style="margin-left: 10px; font-size: 12px; color:#3b4452; margin-bottom:0; font-weight:600;">
    @if ($yearsSinceHire < 1)
        {{ ucwords(strtoupper($data['employee']->first_name)) }} {{ ucwords(strtoupper($data['employee']->last_name)) }} just joined us!
    @else
        Congratulations, {{ ucwords(strtoupper($data['employee']->first_name)) }} {{ ucwords(strtoupper($data['employee']->last_name)) }} on completing {{ $yearsSinceHire }} successful {{ $yearText }}!
    @endif
</p>

                        </div>
                    </div>


                </div>

                <div class="col-md-2 p-0" style="margin-left: 9px;">
                    @php
                    $currentCardEmojis = $storedemojis->where('emp_id', $data['employee']->emp_id);
                    $emojisCount = $currentCardEmojis->count();
                    $lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 2, 0))->reverse();
                    $uniqueNames = [];
                    @endphp

                    @if($currentCardEmojis && $emojisCount > 0)
                    <div style="white-space: nowrap;">
                        @foreach($lastTwoEmojis as $index => $emoji)
                        <span style="font-size: 16px;margin-left:-10px;">{{ $emoji->emoji }}</span>
                        @if (!$loop->last)

                        @endif
                        @endforeach
                        @foreach($lastTwoEmojis as $index => $emoji)
                        @php
                        $fullName = ucwords(strtolower($emoji->first_name)) . ' ' . ucwords(strtolower($emoji->last_name));
                        @endphp
                        @if (!in_array($fullName, $uniqueNames))
                        @if (!$loop->first)
                        <span>,</span>
                        @endif
                        <span style="font-size: 8px;"> {{ $fullName }}</span>
                        @php $uniqueNames[] = $fullName; @endphp
                        @endif
                        @endforeach

                        @if (count($uniqueNames) > 0)
                        <span style="font-size:8px"> reacted</span>
                        @endif

                    </div>


                    @endif

                </div>
                <div class="w-90" style="border-top: 1px solid #E8E5E4; margin: 10px;"></div>
                <div class="row" style="display: flex;">
                    <div class="col-md-5" style="display: flex;">
                        <form wire:submit.prevent="add_emoji('{{ $data['employee']->emp_id }}')">
                            @csrf
                            <div class="emoji-container">
                                <span id="smiley-{{ $index }}" class="emoji-trigger" onclick="showEmojiList({{ $index }})" style="font-size: 16px;cursor:pointer">
                                    😊




                                    <!-- List of emojis -->
                                    <div id="emoji-list-{{ $index }}" class="emoji-list" style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑 </span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128537')">😙</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                                        </div>
                                        <div class="emoji-row">
                                            <!-- Add more emojis here -->
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡 </span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                            <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍 </span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>

                                        </div>
                                        <div class="emoji-row">
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#129307','{{ $data['employee']->emp_id }}')">🤛</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#9996','{{ $data['employee']->emp_id }}')">✌</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128076','{{ $data['employee']->emp_id }}')">👌</span>
                                            <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>


                                        </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7 p-0">
                <div class="col-md-7 mb-2">
                    <div style="display: flex; align-items: center;">
                        <span>
                            <i class="comment-icon">💬</i>
                        </span>
                        <span style="margin-left: 5px;">
                            <a href="#" onclick="comment({{ $index }})" style="font-size: 10px;">Comment</a>
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-md-10">
                <form wire:submit.prevent="createcomment('{{ $data['employee']->emp_id }}')">
                    @csrf
                    <div class="row m-0">


                        <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                            <div class="replyDiv row m-0" id="replyDiv_{{ $index }}" style="display: none;">

                                <div class="col-md-1">
                                    @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
                                    <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
                                    @else
                                    @if($employeeDetails && $employeeDetails->gender == "Male")
                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                                    @elseif($employeeDetails && $employeeDetails->gender == "Female")
                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                                    @else
                                    <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                                    @endif
                                    @endif


                                </div>
                                <div class="col-md-11" style="position: relative;">
                                    <textarea
                                        wire:model="newComment"
                                        placeholder="Post your comments here.."
                                        name="comment"
                                        class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none"
                                        style="overflow: hidden; height: 60px;margin-left:30px">
                    </textarea>
                                    <input
                                        type="submit"
                                        class=" addcomment"
                                        value="Comment" wire:target="addcomment">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>




            <div class="row m-0">
                @php
                $currentCardComments = $addcomments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at');
                @endphp

                <div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
                    @if($currentCardComments && $currentCardComments->count() > 0)
                    @foreach ($currentCardComments as $comment)
                    <div class="mb-3 comment-item" data-created="{{ $comment->created_at }}" data-interacted="{{ $comment->updated_at }}" style="display: flex; gap: 10px; align-items: center;">

                        <div class="comment" style="font-size: 10px; display: flex;">
                            @if($comment->employee)
                            @if(($comment->employee->image) &&$comment->employee->image !== 'null')
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                            @else
                            @if($comment->employee && $comment->employee->gender == "Male")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($comment->employee&& $comment->employee->gender == "Female")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif
                            <div class="comment-details" style="font-size: 10px;margin-left:10px">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">
                                    {{ ucwords(strtolower($comment->employee->first_name)) }} {{ ucwords(strtolower($comment->employee->last_name)) }}
                                </b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->addcomment) }}
                                </p>
                            </div>

                            @elseif ($comment->hr)
                            @if(($comment->hr->image) &&$comment->hr->image !== 'null')
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$data['employee']->image_url }}" alt="Employee Image">
                            @else
                            @if($comment->hr && $comment->hr->gender == "Male")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($comment->hr&& $comment->hr->gender == "Female")
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif
                            <div class="comment-details" style="font-size: 10px;">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">
                                    {{ ucwords(strtolower($hr->first_name)) }} {{ ucwords(strtolower($hr->last_name)) }}
                                </b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->addcomment) }}
                                </p>
                            </div>

                            @else
                            <div class="comment-details" style="font-size: 10px;">
                                <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown Employee</b>
                                <p class="mb-0" style="font-size: 11px;">
                                    {{ ucfirst($comment->addcomment) }}
                                </p>
                            </div>
                            @endif
                        </div>

                    </div>
                    @endforeach
                    @else
                    <p style="font-size: 11px;">No comments available for this card.</p>
                    @endif
                </div>
            </div>



        </div>

    </div>
</div>

@else($sortType==='interacted')
@php
// Group comments by card_id and count the number of comments per card
$cardCommentsCount = $addcomments->groupBy('card_id')->map(function ($comments) {
return $comments->count();
});

// Get card IDs with more than 2 comments
$validCardIds = $cardCommentsCount->filter(function ($count) {
return $count > 2;
})->keys();
$filteredComments = $addcomments->whereIn('card_id', $validCardIds);

// Check if the card is a birthday card based on your conditions,
// for example, checking if the card_id matches the employee's emp_id.
$hireCardId = $data['employee']->emp_id; // assuming this is your birthday card's ID
@endphp
<div class="hire-card mt-2 comment-item"
    data-created="{{ $data['created_at'] ?? '' }}" data-interacted="{{ $data['updated_at'] ?? '' }}">

    <!-- Upcoming Birthdays List -->

    @if ($filteredComments->where('card_id', $hireCardId)->count() > 0)
    <div class="F mb-4" style="padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #CFCACA; color: #3b4452; margin-top: 5px">
        <div class="row m-0">
            <div class="col-md-4 mb-2" style="text-align: center;">
                <img src="{{ $empCompanyLogoUrl }}" alt="Company Logo" style="width:120px">
            </div>
            <div class="col-md-4 m-auto" style="color: #677A8E; font-size: 14px;font-weight: 100px; text-align: center;">
                Group Events
            </div>
            <div class="c col-md-4 m-auto" style="font-size: 12px; font-weight: 100px; color: #9E9696; text-align: center;">
                {{ date('d M Y', strtotime($data['employee']->hire_date)) }}
            </div>
        </div>
        <div class="row m-0">
            <div class="col-md-3">
                <img src="{{ asset('images/New_team_members_gif.gif') }}" alt="Image Description" style="width: 120px;">
            </div>
            <div class="col-md-8 m-auto">
                <p style="font-size:12px;color:#778899;font-weight:normal;margin-top:10px;">
                    @php
                    $hireDate = $data['employee']->hire_date;
                    $yearsSinceHire = date('Y') - date('Y', strtotime($hireDate));
                    $yearText = $yearsSinceHire == 1 ? 'year' : 'years';
                    @endphp

                    Our congratulations to {{ ucwords(strtoupper($data['employee']->first_name)) }}
                    {{ ucwords(strtoupper($data['employee']->last_name)) }},on completing {{ $yearsSinceHire }} successful {{$yearText}}.


                </p>
                <div style="display: flex; align-items: center;">
                    @if(($data['employee']->image) &&$data['employee']->image !== 'null')
                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{$data['employee']->image_url }}" alt="Employee Image">
                    @else
                    @if($data['employee'] && $data['employee']->gender == "Male")
                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                    @elseif($data['employee'] && $data['employee']->gender == "Female")
                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                    @else
                    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                    @endif
                    @endif

                    <p style="margin-left: 10px; font-size: 12px; color: #47515b;margin-bottom:0;font-weight:600;">
                        Congratulations, {{ ucwords(strtoupper($data['employee']->first_name)) }}
                        {{ ucwords(strtoupper($data['employee']->last_name)) }}
                    </p>
                </div>
            </div>


        </div>

        <div class="col-md-2 p-0" style="margin-left:5px;">
            @php
            $currentCardEmojis = $emojis->where('emp_id', $data['employee']->emp_id);
            $emojisCount = $currentCardEmojis->count();
            $lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 2, 0))->reverse();
            $uniqueNames = [];
            @endphp
            @if($currentCardEmojis && $emojisCount > 0)
            <div style="white-space: nowrap;">
                @foreach($lastTwoEmojis as $index => $emoji_reaction)
                <span style="font-size: 16px;">{{ $emoji_reaction->emoji_reaction }}</span>
                @if (!$loop->last)
                <span>,</span>
                @endif
                @endforeach

                @foreach($lastTwoEmojis as $index => $emoji)
                @php
                $fullName = ucwords(strtolower($emoji->first_name)) . ' ' . ucwords(strtolower($emoji->last_name));
                @endphp
                @if (!in_array($fullName, $uniqueNames))
                @if (!$loop->first)
                <span>,</span>
                @endif
                <span style="font-size: 8px;"> {{ $fullName }}</span>
                @php $uniqueNames[] = $fullName; @endphp
                @endif
                @endforeach
                @if (count($uniqueNames) > 0)
                <span style="font-size:8px">reacted</span>
                @endif


            </div>




            @endif
        </div>
        <div class="w-90" style="border-top: 1px solid #E8E5E4; margin: 10px;"></div>
        <div class="row" style="display: flex;">
            <div class="col-md-3" style="display: flex;">
                <form wire:submit.prevent="add_emoji('{{ $data['employee']->emp_id }}')">
                    @csrf
                    <div class="emoji-container">
                        <span id="smiley-{{ $index }}" class="emoji-trigger" onclick="showEmojiList({{ $index }})" style="font-size: 16px;cursor:pointer">
                            😊




                            <!-- List of emojis -->
                            <div id="emoji-list-{{ $index }}" class="emoji-list" style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑 </span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128537')">😙</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                                </div>
                                <div class="emoji-row">
                                    <!-- Add more emojis here -->
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡 </span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                    <span class="emoji-option" style="font-size: 14px;" wire:click="selectEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍 </span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>

                                </div>
                                <div class="emoji-row">
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#129307','{{ $data['employee']->emp_id }}')">🤛</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#9996','{{ $data['employee']->emp_id }}')">✌</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#128076','{{ $data['employee']->emp_id }}')">👌</span>
                                    <span class="emoji-option" style="font-size: 14px;cursor:pointer" wire:click="selectEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>


                                </div>

                </form>
            </div>
        </div>
    </div>



    <div class="col-md-7 p-0">
        <div class="col-md-7 mb-2">
            <div style="display: flex; align-items: center;">
                <span>
                    <i class="comment-icon">💬</i>
                </span>
                <span style="margin-left: 5px;">
                    <a href="#" onclick="comment({{ $index }})" style="font-size: 10px;">Comment</a>
                </span>
            </div>
        </div>




    </div>
    <div class="col-md-10">
        <form wire:submit.prevent="createcomment('{{ $data['employee']->emp_id }}')">
            @csrf
            <div class="row m-0">


                <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                    <div class="replyDiv row m-0" id="replyDiv_{{ $index }}" style="display: none;">

                        <div class="col-md-1">
                            @if(($employeeDetails->image) && $employeeDetails->image !== 'null')
                            <img style="border-radius: 50%; " height="50" width="50" src="{{ $employeeDetails->image_url }}" alt="Employee Image">
                            @else
                            @if($employeeDetails && $employeeDetails->gender == "Male")
                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                            @elseif($employeeDetails && $employeeDetails->gender == "Female")
                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                            @else
                            <img style="border-radius: 50%; " height="50" width="50" src="{{asset("images/user.jpg")}}" alt="Default Image">
                            @endif
                            @endif


                        </div>
                        <div class="col-md-11" style="position: relative;">
                            <textarea
                                wire:model="newComment"
                                placeholder="Post your comments here.."
                                name="comment"
                                class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none"
                                style="overflow: hidden; height: 60px;margin-left:30px">
                    </textarea>
                            <input
                                type="submit"
                                class=" addcomment"
                                value="Comment" wire:target="addcomment">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


</div>


<div class="row m-0">
    @php
    $currentCardComments = $addcomments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at');
    // Group comments by card_id and count the number of comments per card
    $cardCommentsCount = $addcomments->groupBy('card_id')->map(function ($comments) {
    return $comments->count();
    });

    // Get card IDs with more than 2 comments
    $validCardIds = $cardCommentsCount->filter(function ($count) {
    return $count >= 2; // Use >= 2 to include cards with exactly 2 comments
    })->keys();

    // Filter comments to include only those for cards with at least 2 comments
    $filteredComments = $addcomments->whereIn('card_id', $validCardIds);

    // Sort the filtered comments based on the sortType
    if ($sortType === 'interacted') {
    $filteredComments = $filteredComments->sortByDesc('updated_at');
    }
    @endphp
    <div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
        @foreach ($filteredComments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at') as $comment)
        <div class="mb-3 comment-item" data-created="{{ $comment->created_at }}" data-interacted="{{ $comment->updated_at }}" style="display: flex; gap: 10px; align-items: center;">
            <div class="comment" style="font-size: 10px; display: flex;">


                @if ($comment->employee)
                @if(($comment->employee->image) &&$comment->employee->image !== 'null')
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                @else
                @if($comment->employee && $comment->employee->gender == "Male")
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                @elseif($comment->employee&& $comment->employee->gender == "Female")
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                @else
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                @endif
                @endif


                <div class="comment-details" style="font-size: 10px;margin-left:10px">
                    <b style="color:#778899; font-weight:500; font-size: 10px;">
                        {{ ucwords(strtolower($comment->employee->first_name)) }} {{ ucwords(strtolower($comment->employee->last_name)) }}
                    </b>
                    <p class="mb-0" style="font-size: 11px;">
                        {{ ucfirst($comment->addcomment) }}
                    </p>
                </div>

                @elseif ($comment->hr)
                @if(($comment->hr->image) &&$comment->hr->image !== 'null')
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{$comment->employee->image_url }}" alt="Employee Image">
                @else
                @if($comment->hr && $comment->hr->gender == "Male")
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/male-default.png")}}" alt="Default Male Image">
                @elseif($comment->hr&& $comment->hr->gender == "Female")
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="40" src="{{asset("images/female-default.jpg")}}" alt="Default Female Image">
                @else
                <img style="border-radius: 50%; margin-left: 10px" height="30" width="30" src="{{asset("images/user.jpg")}}" alt="Default Image">
                @endif
                @endif
                <div class="comment-details" style="font-size: 10px;">
                    <b style="color:#778899; font-weight:500; font-size: 10px;">
                        {{ ucwords(strtolower($comment->hr->first_name)) }} {{ ucwords(strtolower($comment->hr->last_name)) }}
                    </b>
                    <p class="mb-0" style="font-size: 11px;">
                        {{ ucfirst($comment->addcomment) }}
                    </p>
                </div>

                @else
                <div class="comment-details" style="font-size: 10px;">
                    <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown Employee</b>
                    <p class="mb-0" style="font-size: 11px;">
                        {{ ucfirst($comment->addcomment) }}
                    </p>
                </div>
                @endif
            </div>



        </div>
        @endforeach
    </div>
</div>




</div>
@endif
</div>

@endif


@endif
@endforeach
</div>



</div>

</div>
</div>




<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> -->
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
@push('scripts')
<script>
    Livewire.on('updateSortType', sortType => {
        Livewire.emit('refreshComments', sortType);
    });
</script>
@endpush
<script>
    function handleRadioChange(element) {
        const url = element.getAttribute('data-url');
        window.location.href = url;
    }
</script>


<script>
     function handleImageChange() {
        // Display a flash message
        showFlashMessage('File uploaded successfully!');
    }

    function showFlashMessage(message) {
        const container = document.getElementById('flash-message-container');
        container.textContent = message;
        container.style.fontSize = '0.75rem';
        container.style.display = 'block';

        // Hide the message after 3 seconds
        setTimeout(() => {
            container.style.display = 'none';
        }, 3000);
    }

    document.addEventListener('livewire:load', function() {
        // Listen for clicks on emoji triggers and toggle the emoji list
        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
            trigger.addEventListener('click', function() {
                var index = this.dataset.index;
                var emojiList = document.getElementById('emoji-list-' + index);
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style.display === "") ? "block" : "none";
            });
        });
    })

    // Hide emoji list when an emoji is selected
    document.querySelectorAll('.emoji-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.emoji-list').forEach(list => {
                list.style.display = "none";
            });
        });
    });

    function showEmojiList(index, cardId) {
        var emojiList = document.getElementById('emoji-list-' + index);
        if (emojiList.style.display === "none" || emojiList.style.display === "") {
            emojiList.style.display = "block";
        } else {
            emojiList.style.display = "none";
        }
    }

    function comment(index, cardId) {
        var div = document.getElementById('replyDiv_' + index);
        if (div.style.display === 'none') {
            div.style.display = 'flex';
        } else {
            div.style.display = 'none';
        }
    }






    function subReply(index) {
        var div = document.getElementById('subReplyDiv_' + index);
        if (div.style.display === 'none') {
            div.style.display = 'flex';
        } else {
            div.style.display = 'none';
        }
    }


    document.querySelector('emoji-picker').addEventListener('emoji-click', event => console.log(event.detail));
    // JavaScript function to toggle arrow icon visibility
    // JavaScript function to toggle arrow icon and dropdown content visibility
    // JavaScript function to toggle dropdown content visibility and arrow rotation
    function toggleDropdown(contentId, arrowId) {
        var dropdownContent = document.getElementById(contentId);
        var arrowSvg = document.getElementById(arrowId);

        if (dropdownContent.style.display === 'none') {
            dropdownContent.style.display = 'block';
            arrowSvg.style.transform = 'rotate(180deg)';
        } else {
            dropdownContent.style.display = 'none';
            arrowSvg.style.transform = 'rotate(0deg)';
        }
    }


    function reply(caller) {
        var replyDiv = $(caller).siblings('.replyDiv');
        $('.replyDiv').not(replyDiv).hide(); // Hide other replyDivs
        replyDiv.toggle(); // Toggle display of clicked replyDiv
    }


    function react(reaction) {
        // Handle reaction logic here, you can send it to the server or perform any other action
        console.log('Reacted with: ' + reaction);
    }
</script>

<script>
    function toggleEmojiDrawer() {
        let drawer = document.getElementById('drawer');

        if (drawer.classList.contains('hidden')) {
            drawer.classList.remove('hidden');
        } else {
            drawer.classList.add('hidden');
        }
    }

    function toggleDropdown(contentId, arrowId) {
        var content = document.getElementById(contentId);
        var arrow = document.getElementById(arrowId);

        if (content.style.display === 'block') {
            content.style.display = 'none';
            arrow.classList.remove('rotate');
        } else {
            content.style.display = 'block';
            arrow.classList.add('rotate');
        }

        // Close the dropdown when clicking on a link
        content.addEventListener('click', function(event) {
            if (event.target.tagName === 'A') {
                content.style.display = 'none';
                arrow.classList.remove('rotate');
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('input[name="radio"]').on('change', function() {
            var url = $(this).data('url');
            window.location.href = url;
        });

        // Ensures the corresponding radio button is selected based on current URL
        var currentUrl = window.location.pathname;
        $('input[name="radio"]').each(function() {
            if ($(this).data('url') === currentUrl) {
                $(this).prop('checked', true);
            }
        });

        // Click handler for the custom radio label to trigger the radio input change
        $('.custom-radio-label').on('click', function() {
            $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
        });
    });
</script>
@push('scripts')
<script>
    Livewire.on('commentAdded', () => {
        // Reload comments after adding a new comment
        Livewire.emit('refreshComments');
    });
</script>
@endpush
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownToggle = document.getElementById('dropdown-toggle');

        // Set the initial dropdown value based on the sort type
        var initialSortType = dropdownToggle.childNodes[0].textContent.trim();
        dropdownToggle.childNodes[0].textContent = initialSortType === 'Newest First' ? 'Newest First' : 'Most Recent Interacted';

        dropdownToggle.addEventListener('click', function(event) {
            event.stopPropagation();
            var dropdownMenu = this.nextElementSibling;
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', function() {
            var dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownMenus.forEach(function(menu) {
                menu.style.display = 'none';
            });
        });

        document.querySelectorAll('.dropdown-item').forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                var dropdownToggle = document.getElementById('dropdown-toggle');
                dropdownToggle.childNodes[0].textContent = this.textContent.trim();
                dropdownToggle.nextElementSibling.style.display = 'none';

                var sortType = this.getAttribute('data-sort');
                Livewire.emit('updateSortType', sortType); // Ensure this event exists and is handled
            });

            item.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#E3EBF9';
            });

            item.addEventListener('mouseout', function() {
                this.style.backgroundColor = 'white';
            });
        });

        Livewire.on('refreshComments', function(sortType) {
            sortComments(sortType);
        });

        function sortComments(type) {
            var commentsContainer = document.getElementById('comments-container');
            var comments = Array.from(commentsContainer.getElementsByClassName('comment-item'));

            if (sortType === 'newest') {
                comments.sort(function(a, b) {
                    return new Date(b.dataset.created) - new Date(a.dataset.created);
                });
            } else if (sortType === 'interacted') {
                comments = comments.filter(function(comment) {
                    return parseInt(comment.dataset.comments) > 2; // Only keep comments with more than 2 comments
                });

                comments.sort(function(a, b) {
                    return new Date(b.dataset.interacted) - new Date(a.dataset.interacted);
                });
            }

            commentsContainer.innerHTML = '';
            comments.forEach(function(comment) {
                commentsContainer.appendChild(comment);
            });
        }
    });
    document.addEventListener('livewire:load', function() {
        Livewire.hook('element.updated', (el, component) => {
            if (el.querySelector('.feeds-image')) {
                el.querySelectorAll('.feeds-image').forEach(function(img) {
                    img.onerror = function() {
                        const defaultImage = img.getAttribute('data-default-image');
                        img.src = defaultImage;
                        img.onerror = null; // Prevent infinite loop in case the default image also fails
                    };
                });
            }
        });
    });
</script>


<script>
    // Add event listener to menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove background color from all menu items
            menuItems.forEach(item => {
                item.classList.remove('selected');
            });
            // Add background color to the clicked menu item
            this.classList.add('selected');
        });
    });
</script>
<script>
    function createcomment(comment, empId, index) {
        // Your existing logic to select an emoji

        // Toggle the emoji list visibility using the showEmojiList function
        comment();
    }
    // Function to show the emoji list when clicking on the smiley emoji
    function comment(index, cardId) {
        var div = document.getElementById('replyDiv_' + index);
        if (div.style.display === 'none') {
            div.style.display = 'flex';
        } else {
            div.style.display = 'none';
        }
    }
</script>
<script>
    function add_comment(comment, empId, index) {
        // Your existing logic to select an emoji

        // Toggle the emoji list visibility using the showEmojiList function
        comment();
    }
    // Function to show the emoji list when clicking on the smiley emoji
    function comment(index, cardId) {
        var div = document.getElementById('replyDiv_' + index);
        if (div.style.display === 'none') {
            div.style.display = 'flex';
        } else {
            div.style.display = 'none';
        }
    }
</script>
<script>
    function selectEmoji(emoji, empId, index) {
        // Your existing logic to select an emoji

        // Toggle the emoji list visibility using the showEmojiList function
        showEmojiList();
    }
    // Function to show the emoji list when clicking on the smiley emoji
    function showEmojiList(index) {
        var emojiList = document.getElementById('emoji-list-' + index);
        if (emojiList.style.display === "none") {
            emojiList.style.display = "block";
        } else {
            emojiList.style.display = "none";
        }
    }
</script>
<script>
    function addEmoji(emoji_reaction, empId, cardId) {
        // Your existing logic to select an emoji

        // Toggle the emoji list visibility using the showEmojiList function
        showEmojiList();
    }
    // Function to show the emoji list when clicking on the smiley emoji
    function showEmojiList(index) {
        var emojiList = document.getElementById('emoji-list-' + index);
        if (emojiList.style.display === "none") {
            emojiList.style.display = "block";
        } else {
            emojiList.style.display = "none";
        }
    }
</script>






<script>
    document.addEventListener('livewire:load', function() {
        // Listen for clicks on emoji triggers and toggle the emoji list
        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
            trigger.addEventListener('click', function() {
                var index = this.dataset.index;
                var emojiList = document.getElementById('emoji-list-' + index);
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style.display === "") ? "block" : "none";
            });
        });

        // Hide emoji list when an emoji is selected
        document.querySelectorAll('.emoji-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.emoji-list').forEach(list => {
                    list.style.display = "none";
                });
            });
        });
    });
</script>


<script>
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.cus-button');
        dropdowns.forEach(function(dropdown) {
            if (!dropdown.contains(event.target)) {
                const dropdownContent = dropdown.nextElementSibling;
                dropdownContent.style.display = 'none';
            }
        });
    });

    function toggleDropdown(dropdownId, arrowId) {
        const dropdownContent = document.getElementById(dropdownId);
        const arrowSvg = document.getElementById(arrowId);

        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
            arrowSvg.classList.remove('arrow-rotate');
        } else {
            dropdownContent.style.display = 'block';
            arrowSvg.classList.add('arrow-rotate');
        }
    }
</script>
<script>
    window.addEventListener('post-creation-failed', event => {
        alert('Employees do not have permission to create a post.');
    });
</script>

<script>
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.cus-button');
        dropdowns.forEach(function(dropdown) {
            if (!dropdown.contains(event.target)) {
                const dropdownContent = dropdown.nextElementSibling;
                dropdownContent.style.display = 'none';
            }
        });
    });

    function toggleDropdown(dropdownId, arrowId) {
        const dropdownContent = document.getElementById(dropdownId);
        const arrowSvg = document.getElementById(arrowId);

        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
            arrowSvg.classList.remove('arrow-rotate');
        } else {
            dropdownContent.style.display = 'block';
            arrowSvg.classList.add('arrow-rotate');
        }
    }
    document.querySelectorAll('.custom-radio-label a').forEach(link => {
        link.addEventListener('click', function(e) {
            // Ensure no preventDefault() call is here unless necessary for custom handling
        });
    });
</script>
@push('scripts')
<script src="dist/emoji-popover.umd.js"></script>
<link rel="stylesheet" href="dist/style.css" />

<script>
    document.addEventListener('livewire:load', function() {
        const el = new EmojiPopover({
            button: '.picker',
            targetElement: '.emoji-picker',
            emojiList: [{
                    value: '🤣',
                    label: 'laugh and cry'
                },
                // Add more emoji objects here
            ]
        });

        el.onSelect(l => {
            document.querySelector(".emoji-picker").value += l;
        });

        // Toggle the emoji picker popover manually
        document.querySelector('.picker').addEventListener('click', function() {
            el.toggle();
        });
    });
</script>
@endpush


<script>
    function filterDropdowns() {
        var input, filter, dropdownContents, dropdownContent, menuItems, a, i, j, hasMatch;
        input = document.getElementById('filterSearch');
        filter = input.value.toUpperCase();

        // Select all dropdown content elements
        dropdownContents = [
            document.getElementById('dropdownContent1'),
            document.getElementById('dropdownContent2'),
            document.getElementById('dropdownContent3')
        ];

        // Loop through each dropdown content
        dropdownContents.forEach(function(dropdownContent) {
            menuItems = dropdownContent.getElementsByTagName('a');
            hasMatch = false; // Reset match flag

            // Loop through all menu items and hide/show based on the filter
            for (j = 0; j < menuItems.length; j++) {
                a = menuItems[j];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    a.style.display = ""; // Show matching item
                    hasMatch = true; // Found a match
                } else {
                    a.style.display = "none"; // Hide non-matching item
                }
            }

            // Show dropdown if there's at least one matching item
            dropdownContent.style.display = hasMatch ? "block" : "none"; // Show or hide based on match
        });
    }
</script>


</div>
@endif
</div>