<div>
    <div wire:loading
        wire:target="addFeeds,submit,file_path,openEmojiDialog,openDialog,closeEmojiDialog,handleRadioChange,updateSortType,closeFeeds,removeReaction,removeEmojiReaction,addEmoji,selectEmoji">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>

        </div>
    </div>
    @if ($employeeDetails->isEmpty())
        <p>No employee details found.</p>
    @else
        <div class="px-4 " style="position: relative;">
            <div class="col-md-12  mb-3 mt-1">
                <div class="row bg-white rounded border py-1 d-flex align-items-center">
                    <div class="d-flex mt-2 flex-row align-items-center row m-0">
                        <div class="align-items-center col-md-10 d-flex gap-2 mb-2">
                            <div class="d-flex align-items-center">
                            @if (auth('hr')->check())
    @php
        // Get the employee ID of the logged-in user
        $empEmployeeId = auth('hr')->user()->emp_id;

        // Fetch employee details including first_name, last_name, gender, and image
        $employeeDetails = DB::table('employee_details')
            ->where('emp_id', $empEmployeeId)
            ->select('first_name', 'last_name', 'gender', 'image') // Include gender and image
            ->first();

        // Set defaults
        $firstName = $employeeDetails->first_name ?? 'User';
        $lastName = $employeeDetails->last_name ?? '';

        // Determine the profile image
        if ($employeeDetails && $employeeDetails->image && $employeeDetails->image !== 'null') {
            $profileImage = 'data:image/jpeg;base64,' . $employeeDetails->image;
        } elseif ($employeeDetails && $employeeDetails->gender === 'Male') {
            $profileImage = asset('images/male-default.png');
        } elseif ($employeeDetails && $employeeDetails->gender === 'Female') {
            $profileImage = asset('images/female-default.jpg');
        } else {
            $profileImage = asset('images/user.jpg');
        }
    @endphp

<img class="navProfileImgFeeds " src="{{ $profileImage }}" alt="Profile Image" style="height:40px;width:40px">
    <div class="drive-in justify-content-center align-items-start">

        <span class="text-feed">Hey
            {{ ucwords(strtolower($firstName)) }}
            {{ ucwords(strtolower($lastName)) }}
        </span>
        <p class="text-xs mb-0">Ready to dive in?</p>
    </div>
@else
    <p>User is not authenticated.</p>
@endif

                            </div>
                           
                            <div wire:click="addKudos"
                                class="kudos-container d-flex flex-column justify-content-between"
                                style="width: 90px; height: 90px; border: 1px solid rgb(238 139 153); border-radius: 5px; margin-left: 20px;padding: 10px; background-color: rgb(255 244 246); ">
                                <img src="{{ asset('images/kudos.png') }}"
                                    style="width: 30px; height: 30px; margin-bottom: 20px;" alt="">
                                <p style="font-size: 10px; font-weight: 600;">Give <br>Kudos</p>
                            </div>
                            <div wire:click="addFeeds"
                                class="kudos-container d-flex flex-column justify-content-between bg-purple-50 border-purple-200"
                                style="width: 90px; height: 90px; border: 1px solid rgb(182, 147, 194); border-radius: 5px; margin-left: 20px;padding: 10px; background:rgb(234, 225, 237);cursor:pointer">
                                <img src="{{ asset('images/postheader.jpeg') }}"
                                    style="width: 30px; height: 30px; margin-bottom: 20px;" alt="">
                                <p style="font-size: 10px; font-weight: 600;margin-top:-5px">Create <br >Posts</p>
                            </div>
                            @if ($showKudosDialog)
                                <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <b>Give Kudos</b>
                                                </h5>
                                                <button type="button" class="btn-close btn-primary"
                                                    data-dismiss="modal" aria-label="Close" wire:click="close">
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="d-flex col-12 mb-2">
                                                        <label for="from-date">You are posting in:</label>
                                                        <select id="postType"
                                                            wire:change="updatePostType($event.target.value)"
                                                            wire:model.lazy="postType"
                                                            class="Employee-select-leave placeholder-big"
                                                            style="border: none; margin-left: 10px;">
                                                            <option value="appreciations" selected>Appreciations</option>
                                                            <option value="buysellrent">Buy/Sell/Rent</option>
                                                            <option value="companynews">Company News</option>
                                                            <option value="events">Events</option>
                                                            <option value="everyone">Everyone</option>
                                                            <option value="hyderabad">Hyderabad</option>
                                                            <option value="technology">Technology</option>


                                                            <!-- Add other leave types as needed -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12 mb-2">
                                                        <label for="search1">Search Employee<span
                                                                style="color: var(--requiredAlert);">*</span></label>
                                                        <div class="analytic-view-all-search-bar">
                                                            <div class="search-wrapper">
                                                                @if ($selectedEmployee)
                                                                    <!-- Show the selected employee's initials and full name -->
                                                                    <div class="selected-initials-circle">
                                                                        {{ strtoupper(substr($selectedEmployee->first_name, 0, 1)) . strtoupper(substr($selectedEmployee->last_name, 0, 1)) }}
                                                                    </div>
                                                                    <div class="selected-employee-details">
                                                                        <div class="selected-full-name">
                                                                            {{ $selectedEmployee->first_name }}
                                                                            {{ $selectedEmployee->last_name }}</div>
                                                                        <div class="selected-employee-id">
                                                                            #{{ $selectedEmployee->emp_id }}</div>
                                                                    </div>
                                                                @else
                                                                    <!-- Default search icon -->
                                                                    <i class="search-icon-user fas fa-user"></i>
                                                                @endif

                                                                <!-- Search input field -->
                                                                <input wire:model.debounce="search1" wire:change="validateKudos"
                                                                    wire:input="searchEmployees" type="text"
                                                                    placeholder="">

                                                                @if ($selectedEmployee)
                                                                    <i wire:click="removeSelectedEmployee"
                                                                        wire:key="remove-selected-employee-{{ $selectedEmployee->emp_id }}"
                                                                        class="search-icon-search fas fa-times"></i>
                                                                @else
                                                                    <i class="search-icon-search fas fa-search"></i>
                                                                @endif

                                                            </div>
                                                        </div>

                                                        @if (!empty($search1))
                                                            <div class="search-results-container">
                                                                @foreach ($employees1 as $employee)
                                                                    <div class="search-result-item"
                                                                        wire:click="selectEmployee('{{ $employee->emp_id }}')">
                                                                        <!-- Initials in a circle -->
                                                                        <div class="initials-circle">
                                                                            {{ strtoupper(substr($employee->first_name, 0, 1)) . strtoupper(substr($employee->last_name, 0, 1)) }}
                                                                        </div>

                                                                        <!-- Full name and employee ID -->
                                                                        <div class="employee-details">
                                                                            <div class="full-name">
                                                                                {{ $employee->first_name }}
                                                                                {{ $employee->last_name }}</div>
                                                                            <div class="employee-id">
                                                                                #{{ $employee->emp_id }}</div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        @error('selectedEmployee')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group col-12 mb-2">
                                                        <label for="recognizeType" class="mb-2">Recognize
                                                            Values</label>
                                                        <div class="dropdown-container input-wrapper"
>

                                                          
                                                            <input wire:model="recognizeType"
                                                                class="form-select placeholder-small input-field"
                                                                wire:click="recognizeToggleDropdown"
                                                                placeholder="Select">

                                                            <div class="selected-items-container">
                                                                @foreach ($recognizeType as $type)
                                                                    <div class="selected-item">
                                                                        <span style="font-size: 11px;color: var(--main-heading-color);font-weight: 500;">{{ $type }}</span>
                                                                        <button type="button"
                                                                            wire:click="removeItem('{{ $type }}')"
                                                                            class="remove-item-btn">x</button>
                                                                    </div>
                                                                @endforeach
                                                            </div>



                                                            <!-- Dropdown Content -->
                                                            @if ($dropdownOpen)
                                                                <div class="dropdown-content"
                                                                    style="position: absolute; top: 100%; width: 100%; z-index: 10;">

                                                                    <!-- Search Field inside Dropdown -->
                                                                    <input wire:model="searchTerm"
                                                                        class="search-input"
                                                                        wire:input="searchRecognizeValues"
                                                                        placeholder="Search..." />

                                                                    <!-- Options List -->
                                                                    <div class="options-container">
                                                                        @if (!empty($searchTerm))
                                                                            @if (count($recognizeOptions) > 0)
                                                                                <!-- Display the options if there are search results -->
                                                                                @foreach ($recognizeOptions as $key => $value)
                                                                                    <label class="option-item">
                                                                                        <input type="checkbox"
                                                                                            wire:model="recognizeType"
                                                                                            value="{{ $key }}">
                                                                                        <div class="option-label">
                                                                                            <div class="option-title">
                                                                                                {{ $key }}
                                                                                            </div>
                                                                                            <div
                                                                                                class="option-description">
                                                                                                {{ $value }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </label>
                                                                                @endforeach
                                                                            @else
                                                                                <!-- No results found for the search -->
                                                                                <div class="no-results">
                                                                                    <img src="{{ asset('/images/norecognizedata.png') }}"
                                                                                        alt="No results"
                                                                                        class="no-results-image" />
                                                                                    <p
                                                                                        style="font-size: 14px; font-weight: 500; margin: 0px;">
                                                                                        Uh oh!</p>
                                                                                    <span
                                                                                        class="no-results-text">Nothing
                                                                                        to show here</span>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <!-- Display all options when there is no search term -->
                                                                            @foreach ($options as $key => $value)
                                                                                <label class="option-item">
                                                                                    <input type="checkbox"
                                                                                        wire:model="recognizeType"
                                                                                        value="{{ $key }}">
                                                                                    <div class="option-label">
                                                                                        <div class="option-title">
                                                                                            {{ $key }}</div>
                                                                                        <div
                                                                                            class="option-description">
                                                                                            {{ $value }}</div>
                                                                                    </div>
                                                                                </label>
                                                                            @endforeach
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 mb-2">
                                                        <label for="message" class="mb-2">Your message<span
                                                                style="color: var(--requiredAlert);">*</span></label>

                                                        <!-- Full-width text area for the rich text editor -->
                                                        <textarea id="message" wire:model="message" wire:change="validateKudos" rows="4" class="w-100" placeholder=""></textarea>
                                                        @error('message')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    </div>
                                                    <div class="form-group col-12 mb-2">
                                                        <label for="reactions" class="mb-2">Reactions</label>
                                                        <div class="reaction-section">
                                                            <!-- Default Emoji Button with Plus Icon -->
                                                            <button type="button" wire:click="toggleKudosEmojiPicker" class="reaction-btn">
                                                                😊 <span class="plus-icon">+</span>
                                                            </button>
                                                            
                                                            <!-- Emoji Picker (Hidden by default) -->
                                                            @if ($showKudoEmojiPicker)
                                                                <div class="kudos-emoji-picker">
                                                                    <!-- Emojis for reactions -->
                                                                    @foreach ($this->getReactionEmojis() as $reaction => $emoji)
                                                                        <button type="button" wire:click="addReaction('{{ $reaction }}')" class="emoji-btn">
                                                                            {{ $emoji }}
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                    
                                                            <!-- Display Selected Reactions -->
                                                            <div class="selected-reactions mt-2">
                                                                @foreach ($reactions as $reaction)
                                                                    <span class="selected-reaction">
                                                                        <!-- Display the emoji using the getEmoji method -->
                                                                        {!! $this->getEmoji($reaction) !!}
                                                                        <!-- Remove button (cross icon) for each selected reaction -->
                                                                        <button wire:click="removeKudosReaction('{{ $reaction }}')" class="remove-btn">×</button>
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                            

                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="submit" class="submit-btn"
                                                        wire:click="submitKudos">Give</button>
                                                    <button type="button" class="cancel-btn"
                                                        wire:click="resetFields"
                                                        style="border:1px solid rgb(2,17,79);">Cancel</button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-backdrop fade show blurred-backdrop"></div>
                            @endif
                        </div>
                        <div class="align-items-center col-md-2 createpost d-flex ms-auto">
                            <!-- <button wire:click="addFeeds"
                                class="ms-auto btn-post flex flex-col justify-center items-center group w-20 p-1 rounded-md border border-purple-200">
                                <div class="w-6 h-6 rounded bg-purple-200 flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-file stroke-current group-hover:text-purple-600 stroke-1 text-purple-400">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                </div>
                                <div class="row mt-1">
                                    <div class="text-left text-xs ms-1 text-center" wire:click="addFeeds">Create Posts
                                    </div>
                                </div>
                            </button> -->
                        </div>
                    </div>
                    <div class=" mt-2 bg-white d-flex align-items-center ">
                        <div class="d-flex ms-auto">
                        @if($showFeedsDialog)
<div class="modal" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <p class="mb-0" style="color:white">Create a Post</p>
                <span class="img d-flex align-items-end">
                    <img src="{{ asset('images/Posts.jpg') }}" class="img rounded custom-height-30">
                </span>
            </div>

            <div>
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
    <div class="modal-body" style="padding: 20px; width: 80%;"> 

        <!-- Category Selection -->
        <div class="form-group mb-15">
            <label for="category">You are posting in:</label>
            <select wire:model.lazy="category" class="form-select" id="category">
                <option value="" hidden>Select Category</option>
                <option value="Appreciations">Appreciations</option>
                <option value="Companynews">Company News</option>
                <option value="Events">Events</option>
                <option value="Everyone">Everyone</option>
                <option value="Hyderabad">Hyderabad</option>
                <option value="US">US</option>
            </select>
            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Quill Editor -->
        <div class="row mt-3">
            <label for="description">Write something here:</label>
        </div>
        <div id="quill-toolbar-container" style="margin-top:10px;background:#F7F7F7">
            <div id="quill-toolbar" class="ql-toolbar ql-snow">
                <span class="ql-formats">
                    <button type="button" onclick="execCmd('bold')"><b>B</b></button>
                    <button type="button" onclick="execCmd('italic')"><i>I</i></button>
                    <button type="button" onclick="execCmd('underline')"><u>U</u></button>
                    <button type="button" onclick="execCmd('strikeThrough')"><s>S</s></button>
                    <button type="button" onclick="execCmd('insertUnorderedList')" style="display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button type="button" onclick="execCmd('insertOrderedList')">  <i class="fas fa-list-ol"></i></button>
                    <button type="button" onclick="insertVideo()">🎥</button>

                </span>
            </div>
        </div>
        <!-- Content Editable div with wire:ignore -->
        <div 
                                id="richTextEditor" 
                                contenteditable="true"
                                wire:ignore
                                class="form-control" 
                                style="border: 1px solid #ccc; border-radius: 6px; padding: 10px; min-height: 150px; background-color: #fff;"
                                oninput="updateDescription(this.innerHTML)">
                                {!! $description !!}
                            </div>



        @error('description') 
            <span class="text-danger">{{ $message }}</span> 
        @enderror
        <div class="form-group mt-3">
            <label for="file_path">Upload Attachment:</label>
            <div style="text-align: start;">
                <input type="file" wire:model="file_path" class="form-control" id="file_path" style="margin-top: 5px">
                @error('file_path') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

    </div>
    


   
    <!-- Submit & Cancel Buttons -->
    <div class="modal-footer border-top">
        <div class="d-flex justify-content-center w-100">
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" wire:click="closeFeeds" class="cancel-btn ms-2">Cancel</button>
        </div>
    </div>
</form>




    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
</div>
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
                    <div class="col-md-3 feeds-custom-menu bg-white p-3 mb-2" style="  position: relative;">
                        <p class="feeds-left-menu">Filters</p>
                        <hr style="width: 100%;border-bottom: 1px solid grey;">
                        <p class="feeds-left-menu">Activities</p>
                        <div class="activities">
                            <label class="custom-radio-label">
                                <input type="radio" name="radio" value="activities" checked data-url="/hr/hrFeeds"
                                    wire:click="handleRadioChange('activities')">
                                <div class="feed-icon-container" style="margin-left: 10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-file stroke-current text-purple-400 stroke-1">
                                        <rect x="3" y="3" width="18" height="18" rx="2"
                                            ry="2"></rect>
                                        <rect x="7" y="7" width="3" height="9"></rect>
                                        <rect x="14" y="7" width="3" height="5"></rect>
                                    </svg>
                                </div>
                                <span class="custom-radio-button bg-blue"></span>
                                <span class="custom-radio-content ">All Activities</span>
                            </label>
                        </div>

                        <div class="posts">
                            <label class="custom-radio-label">

                                <input type="radio" id="radio-hr" name="radio" value="posts"
                                    data-url="/kudos" wire:click="handleRadioChange('kudos')">

                                <div class="feed-icon-container" style="margin-left: 10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award stroke-current text-pink-400 stroke-1" _ngcontent-ng-c2218295350 style="width: 1rem; height: 1rem;">

                                        <circle cx="12" cy="8" r="7"></circle>
                                        
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                </div>
                                <span class="custom-radio-button bg-blue"></span>
                                <span class="custom-radio-content ">Kudos</span>
                            </label>
                        </div>


                        <div class="posts">
                            <label class="custom-radio-label">

                                <input type="radio" id="radio-hr" name="radio" value="posts"
                                    data-url="/hr/everyone" wire:click="handleRadioChange('posts')">

                                <div class="feed-icon-container" style="margin-left: 10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-file stroke-current text-purple-400 stroke-1">
                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                        <polyline points="13 2 13 9 20 9"></polyline>
                                    </svg>
                                </div>
                                <span class="custom-radio-button bg-blue"></span>
                                <span class="custom-radio-content ">Posts</span>
                            </label>
                        </div>
                        @if ($isManager)
                            <div class="post-requests">
                                <label class="custom-radio-label">

                                    <input type="radio" id="radio-emp" name="radio" value="post-requests"
                                        data-url="/emp-post-requests" wire:click="handleRadioChange('post-requests')">

                                    <div class="feed-icon-container" style="margin-left: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-file stroke-current text-purple-400 stroke-1"
                                            style="width: 1rem; height: 1rem;">
                                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                    </div>
                                    <span class="custom-radio-button bg-blue"></span>
                                    <span class="custom-radio-content ">Post Requests</span>
                                </label>
                            </div>
                        @endif


                        <hr style="width: 100%;border-bottom: 1px solid grey;">
                        <div>
                            <div class="row" style="max-height:auto">
                                <div class="col " style="margin: 0px;">
                                    <div class="input-group">
                                        <input wire:model="search" id="filterSearch" onkeyup="filterDropdowns()"
                                            id="searchInput" type="text"
                                            class="form-control  task-search-input placeholder-small"
                                            placeholder="Search...." aria-label="Search"
                                            aria-describedby="basic-addon1">
                                        <button class="helpdesk-search-btn" type="button">
                                            <i style="text-align: center;color:white;margin-left:10px"
                                                class="fa fa-search"></i>
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="w-full custom-dropdown visible mt-1">
                                <div class="cus-button"onclick="toggleDropdown('dropdownContent1', 'arrowSvg1')">
                                    <span class="text-base leading-4">Groups</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400"
                id="arrowSvg1" style="margin-left: auto;">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
                                    </span>
                                </div>
                                <div id="dropdownContent1" class="Feeds-Dropdown">
                                    <ul class="d-flex flex-column m-0 p-0">
                                        <a class="menu-item" href="/hr/hrFeeds">All Feeds</a>

                                        <a class="menu-item" href="/hr/everyone">Every One</a>

                                        <a class="menu-item" href="/hr/hrFeeds">Events</a>

                                        <a class="menu-item" href="/hr/everyone">Company News</a>

                                        <a class="menu-item" href="/hr/everyone">Appreciation</a>




                                    </ul>
                                </div>
                            </div>


                            <div class="w-full custom-dropdown visible mt-1">
                                <div class="cus-button">
                                    <span class="text-base leading-4 ">Location</span>
                                    <span class="arrow-icon" id="arrowIcon2"
                                        onclick="toggleDropdown('dropdownContent2', 'arrowSvg2')"
                                        style="margin-top:-5px;color:#3b4452;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400"
                                            id="arrowSvg2" style="color:#3b4452;margin-top:-5px">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <div id="dropdownContent2" class="Feeds-Dropdown">
                                    <ul class="d-flex flex-column p-0 m-0">
                                        <a class="menu-item" style="font-weight: 700;">India</a>


                                        <a class="menu-item" href="/hr/everyone">Adilabad</a>





                                        <a class="menu-item" href="/hr/everyone">Doddaballapur</a>


                                        <a class="menu-item" href="/hr/everyone">Guntur</a>

                                        <a class="menu-item" href="/hr/everyone">Hoskote</a>

                                        <a class="menu-item" href="/hr/everyone">Hyderabad</a>

                                        <a class="menu-item" href="/hr/everyone">Mandya
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Mangalore
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Mumbai
                                        </a>


                                        <a class="menu-item" href="/hr/everyone">Mysore
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Pune
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Sirsi
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Thumkur
                                        </a>

                                        <a class="menu-item" href="/hr/everyone">Tirupati</a>

                                        <a class="menu-item" href="/hr/everyone">Trivandrum</a>

                                        <a class="menu-item" href="/hr/everyone">Udaipur</a>

                                        <a class="menu-item" href="/hr/everyone">Vijayawada</a>

                                        <a class="menu-item" style="font-weight: 700;">USA</a>

                                        <a class="menu-item" href="/hr/everyone">California</a>

                                        <a class="menu-item" href="/hr/everyone">New York</a>

                                        <a class="menu-item" href="/hr/everyone">Hawaii</a>


                                    </ul>
                                </div>
                            </div>
                            <div class="w-full visible custom-dropdown  mt-1">
                                <div class="cus-button">
                                    <span class="text-base leading-4">Department</span>
                                    <span class="arrow-icon" id="arrowIcon3"
                                        onclick="toggleDropdown('dropdownContent3', 'arrowSvg3')"
                                        style="margin-top:-5px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-chevron-down h-1.2x w-1.2x text-secondary-400"
                                            id="arrowSvg3" style="color:#3b4452;">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                </div>
                                <div id="dropdownContent3" class="Feeds-Dropdown">
                                    <ul class="d-flex flex-column" style="font-size: 12px; margin: 0; padding: 0;">

                                        <a class="menu-item" href="/hr/everyone">HR</a>




                                        <a class="menu-item" href="/hr/everyone">Operations</a>


                                        <a class="menu-item" href="/hr/everyone">Production Team</a>


                                        <a class="menu-item" href="/hr/everyone">QA</a>


                                        <a class="menu-item" href="/hr/everyone">Sales Team</a>


                                        <a class="menu-item" href="/hr/everyone">Testing Team</a>

                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-md-9  m-0" >
                        <div class="row align-items-center ">
                            <div class="col-md-5" style=" justify-content: flex-start;display:flex">
                                <div style="width: 2px; height: 38px; background-color: #97E8DF; margin-right: 10px;">
                                </div>
                                <gt-heading _ngcontent-eff-c648="" size="md"
                                    class="ng-tns-c648-2 hydrated"></gt-heading>
                                <div class="medium-header border-cyan-200 " style="margin-left:-1px">All Activities -
                                    All Groups</div>
                            </div>

                            <div class="col-md-4 d-flex justify-content-end align-items-center custom-feed">
                                <p class="medium-header me-2 ">Sort:</p>
                                <div class="dropdown" style="margin-top:-7px">
                                    <button id="dropdown-toggle" class="dropdown-toggle custom-feed-btn">
                                        {{ $sortType === 'newest' ? 'Newest First' : 'Most Recent Interacted' }}
                                    </button>
                                    <div class="dropdown-menu custom-feed-menu "
                                        style="display: {{ $dropdownVisible ? 'block' : 'none' }}">
                                        <a href="#" data-sort="newest"
                                            wire:click.prevent="updateSortType('newest')"
                                            class="dropdown-item custom-feed-item">Newest First</a>
                                        <a href="#" data-sort="interacted"
                                            wire:click.prevent="updateSortType('interacted')"
                                            class="dropdown-item custom-feed-item">Most Recent Interacted</a>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="feeds-main-content">
                        <div class="col-md-9 ">

@php
    // Assuming $comments is fetched correctly as a collection of Comment models
    // Convert comments to a collection if it's not already
$commentsCollection = collect($comments);

// Group comments by card_id and count the number of comments per card
$cardCommentsCount = $commentsCollection->groupBy('card_id')->map(function ($comments) {
        return $comments->count();
    });
@endphp

@foreach ($combinedData as $index => $data)


    @if (isset($data['type']) && $data['type'] === 'date_of_birth')

        @if ($sortType === 'newest')
            <div class="birthday-card mt-2 comment-item"
                data-created="{{ $data['created_at'] ?? '' }}"
                data-interacted="{{ $data['updated_at'] ?? '' }}">

                <div class="cards mb-4">

                    <div class="row m-0">
                        <div class="col-md-4 mb-2" style="text-align: center;">
                            <img src="data:image/jpeg;base64,{{ $empCompanyLogoUrl }}"
                                alt="Company Logo" style="width:120px">
                        </div>
                        <div class="col-md-4 group-events m-auto">
                            Group Events
                        </div>
                        <div class=" col-md-3 group-events  m-auto">
                            @if (isset($data['employee']->created_at) || isset($data['employee']->personalInfo->date_of_birth))
                                {{-- Determine which date to use --}}
                                @php
                                    $currentDate = \Carbon\Carbon::now();

                                    // Use created_at if available, otherwise use date_of_birth
                                    $referenceDate = isset($data['employee']->created_at)
                                        ? \Carbon\Carbon::parse(
                                            $data['employee']->created_at,
                                        )
                                        : \Carbon\Carbon::parse(
                                            $data['employee']->personalInfo->date_of_birth,
                                        );

                                    // Calculate the difference without considering the year
                                    $ageMonths = $currentDate->diffInMonths(
                                        $referenceDate->copy()->year($currentDate->year),
                                    );
                                    $ageDays = $currentDate->diffInDays(
                                        $referenceDate->copy()->year($currentDate->year),
                                    );
                                    $ageHours = $currentDate->diffInHours(
                                        $referenceDate->copy()->year($currentDate->year),
                                    );

                                    // Determine the output based on the age
                                    if ($ageMonths > 0) {
                                        echo "{$ageMonths} month" .
                                            ($ageMonths > 1 ? 's' : '') .
                                            ' ago';
                                    } elseif ($ageDays > 0) {
                                        echo "{$ageDays} day" .
                                            ($ageDays > 1 ? 's' : '') .
                                            ' ago';
                                    } else {
                                        echo "{$ageHours} hour" .
                                            ($ageHours > 1 ? 's' : '') .
                                            ' ago';
                                    }
                                @endphp
                            @endif

                        </div>
                        <div class="col-md-1">
    <!-- Three Dots Button -->
    <button class="three-dots-btn"
        style="border: none; background: none; font-size: 18px; cursor: pointer; margin-left: 10px;margin-top:10px"
        wire:click="toggleCardDropdown('{{ $data['employee']->emp_id }}')" wire:poll>
    &#8942; <!-- Unicode for vertical three dots -->
</button>

  
@if (isset($showFeedsDropdown[$data['employee']->emp_id]) && $showFeedsDropdown[$data['employee']->emp_id] === true)
    <div class="card-option-container"  style="position: absolute;background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 5px;height:50px; width: 120px; z-index: 999;border:1px solid silver">
       
                <p  class="card-option"  wire:click="deleteCard('{{ $data['employee']->emp_id }}', 'date_of_birth')" >
                    Delete Post
</p>
        
    </div>
@endif





</div>
                    </div>
                    <div class="row m-0 mt-2">
                        <div class="col-md-4">
                            <img src="{{ asset('images/Blowing_out_Birthday_candles_Gif.gif') }}"
                                alt="Image Description" style="width: 120px;">
                        </div>
                        <div class="col-md-6 m-auto" >
                            <p style="color: #778899;font-size: 12px;font-weight:normal;">
                                Happy Birthday
                                {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                {{ ucwords(strtoupper($data['employee']->last_name)) }},
                                Have a great year ahead!
                            </p>
                            <div style="display: flex; align-items: center;">
                                @if ($data['employee']->image && $data['employee']->image !== 'null')
                                    <img style="border-radius: 50%; margin-left: 10px"
                                        height="50" width="50"
                                        src="data:image/jpeg;base64,{{ $data['employee']->image }}">
                                @else
                                    @if ($data['employee'] && $data['employee']->gender == 'Male')
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="50" width="50"
                                            src="{{ asset('images/male-default.png') }}"
                                            alt="Default Male Image">
                                    @elseif($data['employee'] && $data['employee']->gender == 'Female')
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="50" width="50"
                                            src="{{ asset('images/female-default.jpg') }}"
                                            alt="Default Female Image">
                                    @else
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="50" width="50"
                                            src="{{ asset('images/user.jpg') }}"
                                            alt="Default Image">
                                    @endif
                                @endif







                                <p
                                    style="margin-left: 10px; font-size: 12px; color:#3b4452;margin-bottom:0;font-weight:600;">
                                    Happy Birthday
                                    {{ ucwords(strtoupper($data['employee']->first_name)) }}
                                    {{ ucwords(strtoupper($data['employee']->last_name)) }}!
                                    🎂
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-2 p-0" style="margin-left:5px;">
                        @php
                            $currentCardEmojis = $emojis->where(
                                'card_id',
                                $data['employee']->emp_id,
                            );
                            $emojisCount = $currentCardEmojis->count();
                            $lastTwoEmojis = $currentCardEmojis
                                ->slice(max($emojisCount - 3, 0))
                                ->reverse();
                            $uniqueNames = [];
                            $backgroundColors = ['#87CEEB', '#ffb6c1', '#eebf7b'];

                            $allEmojis = $currentCardEmojis->reverse();
                        @endphp


                        @if ($currentCardEmojis && $emojisCount > 0)
                        <div style="display: flex; align-items: center; white-space: nowrap; position: relative;">
    @foreach ($lastTwoEmojis as $index => $emoji_reaction)
        <div class="reaction-circle" 
             style="width: 25px; height: 22px; background-color: {{ $backgroundColors[$index % count($backgroundColors)] }};
             border-radius: 50%; display: flex; align-items: center; justify-content: center; 
             position: relative; margin-left: -8px;">
            <span style="font-size: 16px;">{{ $emoji_reaction->emoji_reaction }}</span>
        </div>
    @endforeach
                            

    @php
$lastEmoji = $lastTwoEmojis->last(); // Get the last reacted emoji
@endphp

@if ($lastEmoji)
    @php
        $fullName = ucwords(strtolower($lastEmoji->first_name)) . ' ' . ucwords(strtolower($lastEmoji->last_name));
    @endphp
    <span style="font-size: 10px; color: blue; margin-left: 8px;">
        {{ $fullName }}
    </span>
@endif

                          

                                @if ($emojisCount > 1)
                                    <span
                                        style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
                                        wire:click="openEmojiDialog('{{ $data['employee']->emp_id }}')">and{{ $emojisCount - 1 }}
                                        others reacted</span>

                                    @if ($showDialogEmoji && $emp_id == $data['employee']->emp_id)
                                        <div class="modal fade show" tabindex="-1"
                                            role="dialog"
                                            style="display: block; overflow-y: auto;"
                                            wire:key="emojiModal">
                                            <div class="modal-dialog modal-dialog-centered"
                                                role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"> Reactions
                                                        </h5>
                                                        <button type="button"
                                                            class="btn-close"
                                                            wire:click="closeEmojiDialog"
                                                            aria-label="Close"
                                                            style="margin-left:auto;">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body"
                                                        style="overflow-y:auto;max-height:300px">
                                                        {{-- Display all emojis and their reactions --}}
                                                        @foreach ($allEmojis as $emoji)
                                                            <div
                                                                style="display: flex; align-items: center;margin-top:10px;">
                                                                <span>
                                                                    @php
                                                                        // Assuming $emoji has an 'emp_id' to fetch the correct employee data
                                                                        $employee = \App\Models\EmployeeDetails::where(
                                                                            'emp_id',
                                                                            $emoji->emp_id,
                                                                        )->first();
                                                                    @endphp

                                                                    @if ($employee && $employee->image && $employee->image !== 'null')
                                                                        <img style="border-radius: 50%; margin-left: 10px"
                                                                            height="30"
                                                                            width="30"
                                                                            src="data:image/jpeg;base64,{{ $employee->image }}">
                                                                    @else
                                                                        @if ($employee && $employee->gender == 'Male')
                                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                                height="30"
                                                                                width="30"
                                                                                src="{{ asset('images/male-default.png') }}"
                                                                                alt="Default Male Image">
                                                                        @elseif($employee && $employee->gender == 'Female')
                                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                                height="30"
                                                                                width="30"
                                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                                alt="Default Female Image">
                                                                        @else
                                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                                height="30"
                                                                                width="30"
                                                                                src="{{ asset('images/user.jpg') }}"
                                                                                alt="Default Image">
                                                                        @endif
                                                                    @endif
                                                                </span>

                                                                <span
                                                                    style="font-size: 12px; margin-left: 10px;width:50%">
                                                                    {{ ucwords(strtolower($emoji->first_name)) }}
                                                                    {{ ucwords(strtolower($emoji->last_name)) }}</span>

                                                                <div
                                                                    style="display: flex; justify-content: center;">
                                                                    <span
                                                                        style="font-size: 16px; cursor: pointer; color: inherit;"
                                                                        wire:click="removeEmojiReaction('{{ $emoji->id }}')"
                                                                        onmouseover="this.innerHTML='{{ $emoji->emp_id === auth()->user()->id ? 'Remove Emoji Reaction' : $emoji->emoji_reaction }}';"
                                                                        onmouseout="this.innerHTML='{{ $emoji->emoji_reaction }}';">
                                                                        {{ $emoji->emoji_reaction }}
                                                                    </span>
                                                                </div>


                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div
                                            class="modal-backdrop fade show blurred-backdrop">
                                        </div>
                                    @endif
                                    @else
                                    <span
                                        style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
                                        wire:click="openEmojiDialog('{{ $data['employee']->emp_id }}')">
                                         reacted</span>

                                 

                                @endif

                            </div>
                        @endif


                    </div>

                    <div class="w-90"
                        style="border-top: 1px solid #E8E5E4; margin: 10px;"></div>
                    <div class="row" style="display: flex;">
                        <div class="col-md-5" style="display: flex;">
                            <form
                                wire:submit.prevent="createemoji('{{ $data['employee']->emp_id }}')">
                                @csrf
                                <div class="emoji-container">
                                    <span id="smiley-{{ $index }}"
                                        class="emoji-trigger"
                                        onclick="showEmojiList({{ $index }})"
                                        style="font-size: 16px;cursor:pointer">
                                        😊




                                        <!-- List of emojis -->
                                        <div id="emoji-list-{{ $index }}"
                                            class="emoji-list"
                                            style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑
                                                </span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128537','{{ $data['employee']->emp_id }}')">😙</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                                            </div>
                                            <div class="emoji-row">
                                                <!-- Add more emojis here -->
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡
                                                </span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                                            </div>
                                            <div class="emoji-row">
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍
                                                </span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                                                <span class="emoji-option"
                                                    wire:click="addEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>

                                            </div>


                            </form>
                        </div>
                    </div>
                </div>





                <div class="col-md-7">
                    <div class="col-md-7 mb-2">
                        <div class="d-flex align-items-center">
                            <span>
                                <i class="comment-icon">💬</i>
                            </span>
                            <span class="ml-5">
                                <a href="#" onclick="comment({{ $index }})"
                                    style="font-size: 10px;">Comment</a>
                            </span>
                        </div>
                    </div>




                </div>
                <div class="col-md-10">
                    <form
                        wire:submit.prevent="add_comment('{{ $data['employee']->emp_id }}')">
                        @csrf
                        <div class="row m-0">


                            <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                                <div class="replyDiv row m-0"
                                    id="replyDiv_{{ $index }}"
                                    style="display: none;">

                                    <div class="col-md-1">
                                        @if ($employeeDetails->image && $employeeDetails->image !== 'null')
                                            <img style="border-radius: 50%; "
                                                height="50" width="50"
                                                src="data:image/jpeg;base64,{{ $employeeDetails->image }}">
                                        @else
                                            @if ($employeeDetails && $employeeDetails->gender == 'Male')
                                                <img style="border-radius: 50%; "
                                                    height="50" width="50"
                                                    src="{{ asset('images/male-default.png') }}"
                                                    alt="Default Male Image">
                                            @elseif($employeeDetails && $employeeDetails->gender == 'Female')
                                                <img style="border-radius: 50%; "
                                                    height="50" width="50"
                                                    src="{{ asset('images/female-default.jpg') }}"
                                                    alt="Default Female Image">
                                            @else
                                                <img style="border-radius: 50%; "
                                                    height="50" width="50"
                                                    src="{{ asset('images/user.jpg') }}"
                                                    alt="Default Image">
                                            @endif
                                        @endif


                                    </div>
                                    <div class="col-md-11" style="position: relative;">
                                        <textarea wire:model.lazy="newComment" placeholder="Post your comments here.." name="comment"
                                            class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none">
</textarea>
<button type="submit" class="addcomment" value="Comment"  wire:target="add_comment">   Comment
</button>
                                    </div>
                                 
 
                                 
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row m-0">
                    @php
                        $currentCardComments = $comments
                            ->where('card_id', $data['employee']->emp_id)
                            ->sortByDesc('created_at');
                    @endphp
                    <div class="m-0 mt-2 px-2" id="comments-container"
                        style="overflow-y:auto; max-height:150px;">
                        @if ($currentCardComments && $currentCardComments->count() > 0)
                            @foreach ($currentCardComments as $comment)
                                <div class="mb-3 comment-item"
                                    data-created="{{ $comment->created_at }}"
                                    data-interacted="{{ $comment->updated_at }}"
                                    style="display: flex; gap: 10px; align-items: center;">
                                    @if ($comment->employee)
                                        @if ($comment->employee->image && $comment->employee->image !== 'null')
                                            <img style="border-radius: 50%; margin-left: 10px"
                                                height="30" width="30"
                                                src="data:image/jpeg;base64,{{ $comment->employee->image }}">
                                        @else
                                            @if ($comment->employee && $comment->employee->gender == 'Male')
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="30"
                                                    src="{{ asset('images/male-default.png') }}"
                                                    alt="Default Male Image">
                                            @elseif($comment->employee && $comment->employee->gender == 'Female')
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="40"
                                                    src="{{ asset('images/female-default.jpg') }}"
                                                    alt="Default Female Image">
                                            @else
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="30"
                                                    src="{{ asset('images/user.jpg') }}"
                                                    alt="Default Image">
                                            @endif
                                        @endif






                                        <div class="comment" style="font-size: 10px;">
                                            <b
                                                style="color:#778899; font-weight:500; font-size: 10px;">
                                                {{ ucwords(strtolower($comment->employee->first_name)) }}
                                                {{ ucwords(strtolower($comment->employee->last_name)) }}
                                            </b>
                                            <p class="mb-0" style="font-size: 11px;">
                                                {{ ucfirst($comment->comment) }}
                                            </p>
                                        </div>
                                    @elseif ($comment->hr)
                                        @if ($comment->hr->image && $comment->hr->image !== 'null')
                                            <img style="border-radius: 50%; margin-left: 10px"
                                                height="50" width="50"
                                                src="data:image/jpeg;base64,{{ $comment->hr->image }}">
                                        @else
                                            @if ($comment->hr && $comment->hr->gender == 'Male')
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="30"
                                                    src="{{ asset('images/male-default.png') }}"
                                                    alt="Default Male Image">
                                            @elseif($comment->hr && $comment->hr->gender == 'Female')
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="30"
                                                    src="{{ asset('images/female-default.jpg') }}"
                                                    alt="Default Female Image">
                                            @else
                                                <img style="border-radius: 50%; margin-left: 10px"
                                                    height="30" width="30"
                                                    src="{{ asset('images/user.jpg') }}"
                                                    alt="Default Image">
                                            @endif
                                        @endif
                                        <div class="comment" style="font-size: 10px;">
                                            <b
                                                style="color:#778899; font-weight:500; font-size: 10px;">
                                                {{ ucwords(strtolower($comment->hr->first_name)) }}
                                                {{ ucwords(strtolower($comment->hr->last_name)) }}
                                            </b>
                                            <p class="mb-0" style="font-size: 11px;">
                                                {{ ucfirst($comment->comment) }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="comment" style="font-size: 10px;">
                                            <b
                                                style="color:#778899; font-weight:500; font-size: 10px;">Unknown
                                                Employee</b>
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
$validCardIds = $cardCommentsCount
->filter(function ($count) {
    return $count > 0;
})
->keys();
$filteredComments = $comments->whereIn('card_id', $validCardIds);

// Check if the card is a birthday card based on your conditions,
// for example, checking if the card_id matches the employee's emp_id.
$birthdayCardId = $data['employee']->emp_id; // assuming this is your birthday card's ID
@endphp
<div class="birthday-card mt-2 comment-item" data-created="{{ $data['created_at'] ?? '' }}"
data-interacted="{{ $data['updated_at'] ?? '' }}">
@if ($filteredComments->where('card_id', $birthdayCardId)->count() > 0)
<div class="cards mb-4">
    <div class="row m-0">
        <div class="col-md-3 mb-2 text-align-center">
            <img src="data:image/jpeg;base64,{{ $empCompanyLogoUrl }}"
                alt="Company Logo" style="width:120px">
        </div>
        <div class="col-md-4 group-events m-auto">
            Group Events
        </div>
        <div class=" col-md-4 group-events m-auto">
            @if (isset($data['employee']->created_at) || isset($data['employee']->personalInfo->date_of_birth))
                {{-- Determine which date to use --}}
                @php
                    $currentDate = \Carbon\Carbon::now();

                    // Use created_at if available, otherwise use date_of_birth
                    $referenceDate = isset($data['employee']->created_at)
                        ? \Carbon\Carbon::parse($data['employee']->created_at)
                        : \Carbon\Carbon::parse(
                            $data['employee']->personalInfo->date_of_birth,
                        );

                    // Calculate the difference without considering the year
                    $ageMonths = $currentDate->diffInMonths(
                        $referenceDate->copy()->year($currentDate->year),
                    );
                    $ageDays = $currentDate->diffInDays(
                        $referenceDate->copy()->year($currentDate->year),
                    );
                    $ageHours = $currentDate->diffInHours(
                        $referenceDate->copy()->year($currentDate->year),
                    );

                    // Determine the output based on the age
                    if ($ageMonths > 0) {
                        echo "{$ageMonths} month" . ($ageMonths > 1 ? 's' : '') . ' ago';
                    } elseif ($ageDays > 0) {
                        echo "{$ageDays} day" . ($ageDays > 1 ? 's' : '') . ' ago';
                    } else {
                        echo "{$ageHours} hour" . ($ageHours > 1 ? 's' : '') . ' ago';
                    }
                @endphp
            @endif

        </div>
    </div>
    <div class="row m-0 mt-2">
        <div class="col-md-3">
            <img src="{{ asset('images/Blowing_out_Birthday_candles_Gif.gif') }}"
                alt="Image Description" style="width: 120px;">
        </div>
        <div class="col-md-8 m-auto">
            <p style="color: #778899;font-size: 12px;font-weight:normal;">
                Happy Birthday {{ ucwords(strtoupper($data['employee']->first_name)) }}
                {{ ucwords(strtoupper($data['employee']->last_name)) }},
                Have a great year ahead!
            </p>
            <div style="display: flex; align-items: center;">
                @if ($data['employee']->image && $data['employee']->image !== 'null')
                    <img style="border-radius: 50%; margin-left: 10px" height="50"
                        width="50"
                        src="data:image/jpeg;base64,{{ $data['employee']->image }}">
                @else
                    @if ($data['employee'] && $data['employee']->gender == 'Male')
                        <img style="border-radius: 50%; margin-left: 10px" height="50"
                            width="50" src="{{ asset('images/male-default.png') }}"
                            alt="Default Male Image">
                    @elseif($data['employee'] && $data['employee']->gender == 'Female')
                        <img style="border-radius: 50%; margin-left: 10px" height="50"
                            width="50"
                            src="{{ asset('images/female-default.jpg') }}"
                            alt="Default Female Image">
                    @else
                        <img style="border-radius: 50%; margin-left: 10px" height="50"
                            width="50" src="{{ asset('images/user.jpg') }}"
                            alt="Default Image">
                    @endif
                @endif

                <p
                    style="margin-left: 10px; font-size: 12px; color: #47515b;margin-bottom:0;font-weight:600;">
                    Happy Birthday
                    {{ ucwords(strtoupper($data['employee']->first_name)) }}
                    {{ ucwords(strtoupper($data['employee']->last_name)) }}! 🎂
                </p>
            </div>
        </div>

    </div>
    <div class="col-md-2 p-0" style="margin-left:5px;">
        @php
            $currentCardEmojis = $emojis->where('emp_id', $data['employee']->emp_id);
            $emojisCount = $currentCardEmojis->count();
            $lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 3, 0))->reverse();
            $uniqueNames = [];
            $allEmojis = $currentCardEmojis->reverse();
            $backgroundColors = ['#87CEEB', '#ffb6c1', '#eebf7b'];

        @endphp
        @if ($currentCardEmojis && $emojisCount > 0)
        <div style="display: flex; align-items: center; white-space: nowrap; position: relative;">
    @foreach ($lastTwoEmojis as $index => $emoji_reaction)
        <div class="reaction-circle" 
             style="width: 25px; height: 22px; background-color: {{ $backgroundColors[$index % count($backgroundColors)] }};
             border-radius: 50%; display: flex; align-items: center; justify-content: center; 
             position: relative; margin-left: -8px;">
            <span style="font-size: 16px;">{{ $emoji_reaction->emoji_reaction }}</span>
        </div>
    @endforeach
                 

                @foreach ($lastTwoEmojis as $index => $emoji)
                    @php
                        $fullName =
                            ucwords(strtolower($emoji->first_name)) .
                            ' ' .
                            ucwords(strtolower($emoji->last_name));
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

                @if ($emojisCount > 1)
                    <span style="cursor: pointer; color: blue; font-size: 10px;margin-left:10px"
                        wire:click="openEmojiDialog('{{ $data['employee']->emp_id }}')">and {{ $emojisCount - 1 }}
                        others reacted</span>

                    @if ($showDialogEmoji && $emp_id == $data['employee']->emp_id)
                        <div class="modal fade show" tabindex="-1" role="dialog"
                            style="display: block; overflow-y: auto;"
                            wire:key="emojiModal">
                            <div class="modal-dialog modal-dialog-centered"
                                role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"> Reactions</h5>
                                        <button type="button" class="btn-close"
                                            wire:click="closeEmojiDialog"
                                            aria-label="Close" style="margin-left:auto;">

                                        </button>
                                    </div>
                                    <div class="modal-body"
                                        style="overflow-y:auto;max-height:300px">
                                        {{-- Display all emojis and their reactions --}}
                                        @foreach ($allEmojis as $emoji)
                                            <div
                                                style="display: flex; align-items: center;margin-top:10px;">
                                                <span>
                                                    @php
                                                        // Assuming $emoji has an 'emp_id' to fetch the correct employee data
                                                        $employee = \App\Models\EmployeeDetails::where(
                                                            'emp_id',
                                                            $emoji->emp_id,
                                                        )->first();
                                                    @endphp

                                                    @if ($employee && $employee->image && $employee->image !== 'null')
                                                        <img style="border-radius: 50%; margin-left: 10px"
                                                            height="30" width="30"
                                                            src="data:image/jpeg;base64,{{ $employee->image }}">
                                                    @else
                                                        @if ($employee && $employee->gender == 'Male')
                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                height="30"
                                                                width="30"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($employee && $employee->gender == 'Female')
                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                height="30"
                                                                width="30"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img style="border-radius: 50%; margin-left: 10px"
                                                                height="30"
                                                                width="30"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </span>

                                                <span
                                                    style="font-size: 12px; margin-left: 10px;width:50%">
                                                    {{ ucwords(strtolower($emoji->first_name)) }}
                                                    {{ ucwords(strtolower($emoji->last_name)) }}</span>
                                                <span
                                                    style="font-size: 16px; cursor: pointer; color: inherit;"
                                                    wire:click="removeEmojiReaction('{{ $emoji->id }}')"
                                                    onmouseover="this.innerHTML='{{ $emoji->emp_id === auth()->user()->id ? 'Remove Emoji Reaction' : $emoji->emoji_reaction }}';"
                                                    onmouseout="this.innerHTML='{{ $emoji->emoji_reaction }}';">
                                                    {{ $emoji->emoji_reaction }}
                                                </span>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                    @endif
                    @else
                    <span style="cursor: pointer; color: blue; font-size: 10px;margin-left:10px"
                        wire:click="openEmojiDialog('{{ $data['employee']->emp_id }}')">
                         reacted</span>
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
                    <span id="smiley-{{ $index }}" class="emoji-trigger"
                        onclick="showEmojiList({{ $index }})"
                        style="font-size: 16px;cursor:pointer">
                        😊




                        <!-- List of emojis -->
                        <div id="emoji-list-{{ $index }}" class="emoji-list"
                            style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑
                                </span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128537','{{ $data['employee']->emp_id }}')">😙</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

                            </div>
                            <div class="emoji-row">
                                <!-- Add more emojis here -->
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡
                                </span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


                            </div>
                            <div class="emoji-row">
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍
                                </span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                                <span class="emoji-option"
                                    wire:click="addEmoji('&#129295','{{ $data['employee']->emp_id }}')">🤏</span>


                            </div>


            </form>
        </div>
    </div>
</div>





<div class="col-md-7">
    <div class="col-md-7 mb-2">
        <div style="display: flex; align-items: center;">
            <span>
                <i class="comment-icon">💬</i>
            </span>
            <span style="margin-left: 5px;">
                <a href="#" onclick="comment({{ $index }})"
                    style="font-size: 10px;">Comment</a>
            </span>
        </div>
    </div>




</div>
<div class="col-md-10">
    <form wire:submit.prevent="add_comment('{{ $data['employee']->emp_id }}')">
        @csrf
        <div class="row m-0">


            <div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
                <div class="replyDiv row m-0" id="replyDiv_{{ $index }}"
                    style="display: none;">

                    <div class="col-md-1">
                        @if ($employeeDetails->image && $employeeDetails->image !== 'null')
                            <img style="border-radius: 50%; " height="50"
                                width="50"
                                src="data:image/jpeg;base64,{{ $employeeDetails->image }}">
                        @else
                            @if ($employeeDetails && $employeeDetails->gender == 'Male')
                                <img style="border-radius: 50%; " height="50"
                                    width="50"
                                    src="{{ asset('images/male-default.png') }}"
                                    alt="Default Male Image">
                            @elseif($employeeDetails && $employeeDetails->gender == 'Female')
                                <img style="border-radius: 50%; " height="50"
                                    width="50"
                                    src="{{ asset('images/female-default.jpg') }}"
                                    alt="Default Female Image">
                            @else
                                <img style="border-radius: 50%; " height="50"
                                    width="50"
                                    src="{{ asset('images/user.jpg') }}"
                                    alt="Default Image">
                            @endif
                        @endif


                    </div>
                    <div class="col-md-11" style="position: relative;">
                        <textarea wire:model="newComment" placeholder="Post your comments here.." name="comment"
                            class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none">
</textarea>
<button type="submit" class="addcomment" value="Comment"  wire:target="add_comment">
    Comment
</button>
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
$validCardIds = $cardCommentsCount
    ->filter(function ($count) {
        return $count >= 0; // Use >= 2 to include cards with exactly 2 comments
    })
    ->keys();

// Filter comments to include only those for cards with at least 2 comments
$filteredComments = $comments->whereIn('card_id', $validCardIds);

// Sort the filtered comments based on the sortType
if ($sortType === 'interacted') {
    $filteredComments = $filteredComments->sortByDesc('updated_at');
}
@endphp
<div class="m-0 mt-2 px-2" id="comments-container"
style="overflow-y:auto; max-height:150px;">
@foreach ($filteredComments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at') as $comment)
    <div class="mb-3 comment-item" data-created="{{ $comment->created_at }}"
        data-interacted="{{ $comment->updated_at }}"
        style="display: flex; gap: 10px; align-items: center;">
        @if ($comment->employee)
            @if ($comment->employee->image && $comment->employee->image !== 'null')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30"
                    src="data:image/jpeg;base64,{{ $comment->employee->image }}">
            @else
                @if ($comment->employee && $comment->employee->gender == 'Male')
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="30" src="{{ asset('images/male-default.png') }}"
                        alt="Default Male Image">
                @elseif($comment->employee && $comment->employee->gender == 'Female')
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="40" src="{{ asset('images/female-default.jpg') }}"
                        alt="Default Female Image">
                @else
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="30" src="{{ asset('images/user.jpg') }}"
                        alt="Default Image">
                @endif
            @endif





            <div class="comment" style="font-size: 10px;">
                <b style="color:#778899; font-weight:500; font-size: 10px;">
                    {{ ucwords(strtolower($comment->employee->first_name)) }}
                    {{ ucwords(strtolower($comment->employee->last_name)) }}
                </b>
                <p class="mb-0" style="font-size: 11px;">
                    {{ ucfirst($comment->comment) }}
                </p>
            </div>
        @elseif ($comment->hr)
            @if ($comment->hr->image && $comment->hr->image !== 'null')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30"
                    src="data:image/jpeg;base64,{{ $comment->employee->image }}">
            @else
                @if ($comment->hr && $comment->hr->gender == 'Male')
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="30" src="{{ asset('images/male-default.png') }}"
                        alt="Default Male Image">
                @elseif($comment->hr && $comment->hr->gender == 'Female')
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="30" src="{{ asset('images/female-default.jpg') }}"
                        alt="Default Female Image">
                @else
                    <img style="border-radius: 50%; margin-left: 10px" height="30"
                        width="30" src="{{ asset('images/user.jpg') }}"
                        alt="Default Image">
                @endif
            @endif
            <div class="comment" style="font-size: 10px;">
                <b style="color:#778899; font-weight:500; font-size: 10px;">
                    {{ ucwords(strtolower($comment->hr->first_name)) }}
                    {{ ucwords(strtolower($comment->hr->last_name)) }}
                </b>
                <p class="mb-0" style="font-size: 11px;">
                    {{ ucfirst($comment->comment) }}
                </p>
            </div>
        @else
            <div class="comment" style="font-size: 10px;">
                <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown
                    Employee</b>
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
@if ($sortType === 'newest')
<div class="hire-card mt-2 comment-item" data-created="{{ $data['created_at'] ?? '' }}"
data-interacted="{{ $data['updated_at'] ?? '' }}">

<div class="cards mb-4">

<div class="row m-0">
<div class="col-md-3 mb-2" style="text-align: center;">
<img src="data:image/jpeg;base64,{{ $empCompanyLogoUrl }}" alt="Company Logo"
style="width:120px">
</div>
<div class="col-md-4 group-events m-auto">
Group Events
</div>
<div class=" col-md-3 group-events m-auto">
@if (isset($data['employee']->created_at) || isset($data['employee']->hire_date))
{{-- Determine which date to use --}}
@php
$dateToUse = isset($data['employee']->created_at)
    ? $data['employee']->created_at
    : $data['employee']->hire_date;
$dateToUse = \Carbon\Carbon::parse($dateToUse);
$currentDate = \Carbon\Carbon::now();

// Calculate the difference without considering the year
$ageMonths = $currentDate->diffInMonths($dateToUse->copy()->year($currentDate->year));
$ageDays = $currentDate->diffInDays($dateToUse->copy()->year($currentDate->year));
$ageHours = $currentDate->diffInHours($dateToUse->copy()->year($currentDate->year));

// Determine the output based on the age
if ($ageMonths > 0) {
    echo "{$ageMonths} month" . ($ageMonths > 1 ? 's' : '') . ' ago';
} elseif ($ageDays > 0) {
    echo "{$ageDays} day" . ($ageDays > 1 ? 's' : '') . ' ago';
} else {
    echo "{$ageHours} hour" . ($ageHours > 1 ? 's' : '') . ' ago';
}
@endphp
@endif

</div>

<div class="col-md-1">
    <!-- Three Dots Button -->
    <button class="three-dots-btn"
        style="border: none; background: none; font-size: 18px; cursor: pointer; margin-left: 10px;margin-top:10px"
        wire:click="toggleCardDropdown('{{ $data['employee']->emp_id }}')" wire:poll>
    &#8942; <!-- Unicode for vertical three dots -->
</button>

  
@if (isset($showFeedsDropdown[$data['employee']->emp_id]) && $showFeedsDropdown[$data['employee']->emp_id] === true)
    <div class="card-option-container"  style="position: absolute;background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 5px;height:50px; width: 120px; z-index: 999;border:1px solid silver">
       
                <p  class="card-option"  wire:click="deleteCard('{{ $data['employee']->emp_id }}', 'hire_date')" >
                    Delete Post
</p>
        
    </div>
@endif





</div>





</div>
<div class="row m-0">
<div class="col-md-3">
<img src="{{ asset('images/New_team_members_gif.gif') }}" alt="Image Description"
style="width: 120px;">
</div>
<div class="col-md-8 m-auto">
@php
$hireDate = $data['employee']->hire_date;
$currentDate = date('Y-m-d');
$hireDateTimestamp = strtotime($hireDate);
$diffInDays = (strtotime($currentDate) - $hireDateTimestamp) / (60 * 60 * 24);
$diffInYears = $diffInDays / 365;
$yearsSinceHire = floor($diffInYears);
$yearText = $yearsSinceHire == 1 ? 'year' : 'years';
@endphp

@if ($yearsSinceHire < 1)
<p style="font-size:12px;color:#778899;font-weight:normal;margin-top:10px;padding-left:10px">
{{ ucwords(strtoupper($data['employee']->first_name)) }}
{{ ucwords(strtoupper($data['employee']->last_name)) }} has joined us in the company on
{{ date('d M Y', strtotime($hireDate)) }},
Please join us in welcoming our newest team member.
</p>
@else
<p style="font-size:12px;color:#778899;font-weight:normal;margin-top:10px;padding-left:10px">
Our congratulations to {{ ucwords(strtoupper($data['employee']->first_name)) }}
{{ ucwords(strtoupper($data['employee']->last_name)) }},
on completing {{ $yearsSinceHire }} successful {{ $yearText }}.
</p>
@endif

<div style="display: flex; align-items: center;">
@if ($data['employee']->image && $data['employee']->image !== 'null')
<img style="border-radius: 50%; margin-left: 10px" height="50" width="50"
    src="data:image/jpeg;base64,{{ $data['employee']->image }}">
@else
@if ($data['employee'] && $data['employee']->gender == 'Male')
    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50"
        src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
@elseif($data['employee'] && $data['employee']->gender == 'Female')
    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50"
        src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
@else
    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50"
        src="{{ asset('images/user.jpg') }}" alt="Default Image">
@endif
@endif

<p style="margin-left: 10px; font-size: 12px;color:#3b4452;margin-bottom:0;font-weight:600;">
Congratulations, {{ ucwords(strtoupper($data['employee']->first_name)) }}
{{ ucwords(strtoupper($data['employee']->last_name)) }}
</p>
</div>
</div>


</div>

<div class="col-md-2 p-0" style="margin-left: 9px;">
@php
$currentCardEmojis = $storedemojis->where('card_id', $data['employee']->emp_id);
$emojisCount = $currentCardEmojis->count();
$lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 3, 0))->reverse();
$allEmojis = $currentCardEmojis->reverse(); // This will get all emojis in reverse order
 // Define background colors for the emojis
 $backgroundColors = ['#87CEEB', '#ffb6c1', '#eebf7b'];
    $uniqueNames = [];
@endphp

@if ($emojisCount > 0)

{{-- Display the last two emojis --}}
<div style="display: flex; align-items: center; white-space: nowrap; position: relative;">
    @foreach ($lastTwoEmojis as $index => $emoji)
        <div class="reaction-circle" 
             style="width: 25px; height: 22px; background-color: {{ $backgroundColors[$index % count($backgroundColors)] }};
             border-radius: 50%; display: flex; align-items: center; justify-content: center; 
             position: relative; margin-right:-8px ;">
            <span style="font-size: 16px;">{{ $emoji->emoji }}</span>
        </div>
    @endforeach



{{-- Display the names of people who reacted --}}
@php
$uniqueNames = [];
$nameList = [];
@endphp
                          @php
                            $lastEmoji = $lastTwoEmojis->last(); // Get the last reacted emoji
                            @endphp

                            @if ($lastEmoji)
                            @php
                            $fullName = ucwords(strtolower($lastEmoji->first_name)) . ' ' . ucwords(strtolower($lastEmoji->last_name));
                            @endphp
                            <span style="font-size: 10px; color: blue; margin-left: 10px;">{{ $fullName }}</span>
                            @endif


{{-- Show +more if there are more than 2 emojis --}}
@if ($emojisCount > 1)
<span style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
    wire:click="openDialog('{{ $data['employee']->emp_id }}')">
    and {{ $emojisCount - 1 }} others reacted
</span>
@else
<span style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
    wire:click="openDialog('{{ $data['employee']->emp_id }}')">
     reacted
</span>
@endif

{{-- Show the last name(s) of people who reacted and their count --}}
@if (count($uniqueNames) > 1)
<span style="font-size: 8px;">
    @foreach ($nameList as $key => $name)
        @if ($key > 1)
            @if ($key == 1)
                ,
            @endif
            {{ ucwords(strtolower(explode(' ', $name)[1])) }}
        @endif
    @endforeach
</span>
@endif

{{-- Show modal with all reactions if clicked on +more --}}
@if ($showDialog && $emp_id == $data['employee']->emp_id)
<div class="modal fade show" tabindex="-1" role="dialog"
    style="display: block; overflow-y: auto;" wire:key="emojiModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Reactions</h5>
                <button type="button" class="btn-close" wire:click="closeDialog"
                    aria-label="Close" style="margin-left:auto;"></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;max-height:300px">
                {{-- Display all emojis and their reactions --}}
                @foreach ($allEmojis as $emoji)
                    <div style="display: flex; align-items: center; margin-top:10px;"
                        wire:key="emoji-{{ $emoji->id }}">
                        <span>
                            @php
                                $employee = \App\Models\EmployeeDetails::where(
                                    'emp_id',
                                    $emoji->emp_id,
                                )->first();
                            @endphp

                            @if ($employee && $employee->image && $employee->image !== 'null')
                                <img style="border-radius: 50%; margin-left: 10px"
                                    height="30" width="30"
                                    src="data:image/jpeg;base64,{{ $employee->image }}">
                            @else
                                <img style="border-radius: 50%; margin-left: 10px"
                                    height="30" width="30"
                                    src="{{ asset($employee && $employee->gender == 'Male' ? 'images/male-default.png' : ($employee && $employee->gender == 'Female' ? 'images/female-default.jpg' : 'images/user.jpg')) }}"
                                    alt="Default Image">
                            @endif
                        </span>

                        <span style="font-size: 12px; margin-left: 10px; width:50%">
                            {{ ucwords(strtolower($emoji->first_name)) }}
                            {{ ucwords(strtolower($emoji->last_name)) }}
                        </span>

                        <span style="font-size: 16px; cursor: pointer; color: inherit;"
                            wire:click="removeReaction('{{ $emoji->id }}')"
                            onmouseover="this.innerHTML='{{ $emoji->emp_id === auth()->user()->id ? 'Remove Reaction' : $emoji->emoji }}';"
                            onmouseout="this.innerHTML='{{ $emoji->emoji }}';">
                            {{ $emoji->emoji }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show blurred-backdrop"></div>
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
<span id="smiley-{{ $index }}" class="emoji-trigger"
    onclick="showEmojiList({{ $index }})"
    style="font-size: 16px;cursor:pointer">
    😊




    <!-- List of emojis -->
    <div id="emoji-list-{{ $index }}" class="emoji-list"
        style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑
            </span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128537','{{ $data['employee']->emp_id }}')">😙</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

        </div>
        <div class="emoji-row">
            <!-- Add more emojis here -->
            <span class="emoji-option"
                wire:click="selectEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡
            </span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
            <span class="emoji-option"
                wire:click="addEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>
        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


        </div>
        <div class="emoji-row">
            <span class="emoji-option"
                wire:click="selectEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍
            </span>
            <span class="emoji-option"
                wire:click="selectEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                <span class="emoji-option"
                wire:click="selectEmoji('&#128076','{{ $data['employee']->emp_id }}')">👌</span>
        </div>
      

</form>
</div>
</div>
</div>
<div class="col-md-7">
<div class="col-md-7 mb-2">
<div style="display: flex; align-items: center;">
<span>
<i class="comment-icon">💬</i>
</span>
<span style="margin-left: 5px;">
<a href="#" onclick="comment({{ $index }})"
style="font-size: 10px;">Comment</a>
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
    @if ($employeeDetails->image && $employeeDetails->image !== 'null')
        <img style="border-radius: 50%; " height="50" width="50"
            src="data:image/jpeg;base64,{{ $employeeDetails->image }}"
            alt="Employee Image">
    @else
        @if ($employeeDetails && $employeeDetails->gender == 'Male')
            <img style="border-radius: 50%; " height="50" width="50"
                src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
        @elseif($employeeDetails && $employeeDetails->gender == 'Female')
            <img style="border-radius: 50%; " height="50" width="50"
                src="{{ asset('images/female-default.jpg') }}"
                alt="Default Female Image">
        @else
            <img style="border-radius: 50%; " height="50" width="50"
                src="{{ asset('images/user.jpg') }}" alt="Default Image">
        @endif
    @endif


</div>
<div class="col-md-11 position-relative">
    <textarea wire:model="newComment" placeholder="Post your comments here.." name="comment"
        class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none">
</textarea>
<button type="submit" class="addcomment" value="Comment"  wire:target="addcomment">
    Comment
</button>
</div>


</div>
</div>
</div>
</form>
</div>




<div class="row m-0">
@php
$currentCardComments = $addcomments
->where('card_id', $data['employee']->emp_id)
->sortByDesc('created_at');
@endphp

<div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
@if ($currentCardComments && $currentCardComments->count() > 0)
@foreach ($currentCardComments as $comment)
<div class="mb-3 comment-item" data-created="{{ $comment->created_at }}"
data-interacted="{{ $comment->updated_at }}"
style="display: flex; gap: 10px; align-items: center;">

<div class="comment" style="font-size: 10px; display: flex;">
    @if ($comment->employee)
        @if ($comment->employee->image && $comment->employee->image !== 'null')
            <img style="border-radius: 50%; margin-left: 10px" height="30"
                width="30"
                src="data:image/jpeg;base64,{{ $comment->employee->image }}"
                alt="Employee Image">
        @else
            @if ($comment->employee && $comment->employee->gender == 'Male')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30" src="{{ asset('images/male-default.png') }}"
                    alt="Default Male Image">
            @elseif($comment->employee && $comment->employee->gender == 'Female')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="40" src="{{ asset('images/female-default.jpg') }}"
                    alt="Default Female Image">
            @else
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30" src="{{ asset('images/user.jpg') }}"
                    alt="Default Image">
            @endif
        @endif
        <div class="comment-details" style="font-size: 10px;margin-left:10px">
            <b style="color:#778899; font-weight:500; font-size: 10px;">
                {{ ucwords(strtolower($comment->employee->first_name)) }}
                {{ ucwords(strtolower($comment->employee->last_name)) }}
            </b>
            <p class="mb-0" style="font-size: 11px;">
                {{ ucfirst($comment->addcomment) }}
            </p>
        </div>
    @elseif ($comment->hr)
        @if ($comment->hr->image && $comment->hr->image !== 'null')
            <img style="border-radius: 50%; margin-left: 10px" height="30"
                width="30" src="{{ $data['employee']->image_url }}"
                alt="Employee Image">
        @else
            @if ($comment->hr && $comment->hr->gender == 'Male')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30" src="{{ asset('images/male-default.png') }}"
                    alt="Default Male Image">
            @elseif($comment->hr && $comment->hr->gender == 'Female')
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="40" src="{{ asset('images/female-default.jpg') }}"
                    alt="Default Female Image">
            @else
                <img style="border-radius: 50%; margin-left: 10px" height="30"
                    width="30" src="{{ asset('images/user.jpg') }}"
                    alt="Default Image">
            @endif
        @endif
        <div class="comment-details" style="font-size: 10px;">
            <b style="color:#778899; font-weight:500; font-size: 10px;">
                {{ ucwords(strtolower($hr->first_name)) }}
                {{ ucwords(strtolower($hr->last_name)) }}
            </b>
            <p class="mb-0" style="font-size: 11px;">
                {{ ucfirst($comment->addcomment) }}
            </p>
        </div>
    @else
        <div class="comment-details" style="font-size: 10px;">
            <b style="color:#778899; font-weight:500; font-size: 10px;">Unknown
                Employee</b>
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
$validCardIds = $cardCommentsCount
->filter(function ($count) {
return $count > 0;
})
->keys();
$filteredComments = $addcomments->whereIn('card_id', $validCardIds);

// Check if the card is a birthday card based on your conditions,
// for example, checking if the card_id matches the employee's emp_id.
$hireCardId = $data['employee']->emp_id; // assuming this is your birthday card's ID
@endphp
<div class="hire-card mt-2 comment-item" data-created="{{ $data['created_at'] ?? '' }}"
data-interacted="{{ $data['updated_at'] ?? '' }}">

<!-- Upcoming Birthdays List -->

@if ($filteredComments->where('card_id', $hireCardId)->count() > 0)
<div class="F mb-4"
style="padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #CFCACA; color: #3b4452; margin-top: 5px">
<div class="row m-0">
<div class="col-md-4 mb-2" style="text-align: center;">
<img src="data:image/jpeg;base64,{{ $empCompanyLogoUrl }}" alt="Company Logo"
style="width:120px">
</div>
<div class="col-md-4 group-events  m-auto">
Group Events
</div>
<div class="col-md-4 m-auto"
style="font-size: 12px; font-weight: 100px; color: #9E9696; text-align: center;">
@if (isset($data['employee']->created_at) || isset($data['employee']->hire_date))
{{-- Determine which date to use --}}
@php
    $dateToUse = isset($data['employee']->created_at)
        ? $data['employee']->created_at
        : $data['employee']->hire_date;
    $dateToUse = \Carbon\Carbon::parse($dateToUse);
    $currentDate = \Carbon\Carbon::now();

    // Calculate the difference without considering the year
    $ageMonths = $currentDate->diffInMonths($dateToUse->copy()->year($currentDate->year));
    $ageDays = $currentDate->diffInDays($dateToUse->copy()->year($currentDate->year));
    $ageHours = $currentDate->diffInHours($dateToUse->copy()->year($currentDate->year));

    // Determine the output based on the age
    if ($ageMonths > 0) {
        echo "{$ageMonths} month" . ($ageMonths > 1 ? 's' : '') . ' ago';
    } elseif ($ageDays > 0) {
        echo "{$ageDays} day" . ($ageDays > 1 ? 's' : '') . ' ago';
    } else {
        echo "{$ageHours} hour" . ($ageHours > 1 ? 's' : '') . ' ago';
    }
@endphp
@endif
</div>
</div>
<div class="row m-0">
<div class="col-md-3">
<img src="{{ asset('images/New_team_members_gif.gif') }}" alt="Image Description"
style="width: 120px;">
</div>
<div class="col-md-8 m-auto">
<p style="font-size:12px;color:#778899;font-weight:normal;margin-top:10px;">
@php
    $hireDate = $data['employee']->hire_date;
    $yearsSinceHire = date('Y') - date('Y', strtotime($hireDate));
    $yearText = $yearsSinceHire == 1 ? 'year' : 'years';
@endphp

Our congratulations to {{ ucwords(strtoupper($data['employee']->first_name)) }}
{{ ucwords(strtoupper($data['employee']->last_name)) }},on completing
{{ $yearsSinceHire }} successful {{ $yearText }}.


</p>
<div style="display: flex; align-items: center;">
@if ($data['employee']->image && $data['employee']->image !== 'null')
    <img style="border-radius: 50%; margin-left: 10px" height="50" width="50"
        src="data:image/jpeg;base64,{{ $data['employee']->image }}"
        alt="Employee Image">
@else
    @if ($data['employee'] && $data['employee']->gender == 'Male')
        <img style="border-radius: 50%; margin-left: 10px" height="50"
            width="50" src="{{ asset('images/male-default.png') }}"
            alt="Default Male Image">
    @elseif($data['employee'] && $data['employee']->gender == 'Female')
        <img style="border-radius: 50%; margin-left: 10px" height="50"
            width="50" src="{{ asset('images/female-default.jpg') }}"
            alt="Default Female Image">
    @else
        <img style="border-radius: 50%; margin-left: 10px" height="50"
            width="50" src="{{ asset('images/user.jpg') }}" alt="Default Image">
    @endif
@endif

<p
    style="margin-left: 10px; font-size: 12px; color: #47515b;margin-bottom:0;font-weight:600;">
    Congratulations, {{ ucwords(strtoupper($data['employee']->first_name)) }}
    {{ ucwords(strtoupper($data['employee']->last_name)) }}
</p>
</div>
</div>
<div class="col-md-1">
    <!-- Three Dots Button -->
    <button class="three-dots-btn"
        style="border: none; background: none; font-size: 18px; cursor: pointer; margin-left: 10px;margin-top:10px"
        wire:click="toggleCardDropdown('{{ $data['employee']->emp_id }}')" wire:poll>
    &#8942; <!-- Unicode for vertical three dots -->
</button>

  
@if (isset($showFeedsDropdown[$data['employee']->emp_id]) && $showFeedsDropdown[$data['employee']->emp_id] === true)
    <div class="card-option-container"  style="position: absolute;background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 5px;height:50px; width: 120px; z-index: 999;border:1px solid silver">
       
                <p  class="card-option"  wire:click="deleteCard('{{ $data['employee']->emp_id }}', 'hire_date')" >
                    Delete Post
</p>
        
    </div>
@endif





</div>

</div>

<div class="col-md-2 p-0" style="margin-left:5px;">
@php
$currentCardEmojis = $emojis->where('card_id', $data['employee']->emp_id);
$emojisCount = $currentCardEmojis->count();
$lastTwoEmojis = $currentCardEmojis->slice(max($emojisCount - 3, 0))->reverse();
$allEmojis = $currentCardEmojis->reverse();
$backgroundColors = ['#87CEEB', '#ffb6c1', '#eebf7b'];
$uniqueNames = [];
@endphp
@if ($emojisCount > 0)
<div style="display: flex; align-items: center; white-space: nowrap; position: relative;">
    @foreach ($lastTwoEmojis as $index => $emoji)
        <div class="reaction-circle" 
             style="width: 25px; height: 22px; background-color: {{ $backgroundColors[$index % count($backgroundColors)] }};
             border-radius: 50%; display: flex; align-items: center; justify-content: center; 
             position: relative; margin-right: -8px">
            <span style="font-size: 16px;">{{ $emoji->emoji }}</span>
        </div>
    @endforeach

{{-- Display the names of people who reacted --}}
@php
    $uniqueNames = [];
    $nameList = [];
@endphp
@foreach ($lastTwoEmojis as $emoji)
    @php
        $fullName =
            ucwords(strtolower($emoji->first_name)) .
            ' ' .
            ucwords(strtolower($emoji->last_name));
    @endphp
    @if (!in_array($fullName, $uniqueNames))
        @if (!$loop->first)
            <span>,</span>
        @endif
        <span style="font-size: 10px;color:blue;margin-left:10px">{{ $fullName }} </span>
        @php
            $uniqueNames[] = $fullName;
            $nameList[] = $fullName;
        @endphp
    @endif
@endforeach



{{-- Show +more if there are more than 2 emojis --}}
@if ($emojisCount > 1)
    <span style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
        wire:click="openDialog('{{ $data['employee']->emp_id }}')">
        and {{ $emojisCount - 1 }} others reacted 
    </span>
    @else
<span style="cursor: pointer; color: blue; font-size: 10px;margin-left:5px"
    wire:click="openDialog('{{ $data['employee']->emp_id }}')">
     reacted
</span>
@endif

{{-- Show the last name(s) of people who reacted and their count --}}
@if (count($uniqueNames) > 1)
    <span style="font-size: 8px;">
        @foreach ($nameList as $key => $name)
            @if ($key > 1)
                @if ($key == 1)
                    ,
                @endif
                {{ ucwords(strtolower(explode(' ', $name)[1])) }}
            @endif
        @endforeach
    </span>
@endif

{{-- Show modal with all reactions if clicked on +more --}}
@if ($showDialog && $emp_id == $data['employee']->emp_id)
    <div class="modal fade show" tabindex="-1" role="dialog"
        style="display: block; overflow-y: auto;" wire:key="emojiModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Reactions</h5>
                    <button type="button" class="btn-close" wire:click="closeDialog"
                        aria-label="Close" style="margin-left:auto;"></button>
                </div>
                <div class="modal-body" style="overflow-y:auto;max-height:300px">
                    {{-- Display all emojis and their reactions --}}
                    @foreach ($allEmojis as $emoji)
                        <div style="display: flex; align-items: center;margin-top:10px;">
                            <span>
                                @php
                                    // Fetch employee data based on emp_id
                                    $employee = \App\Models\EmployeeDetails::where(
                                        'emp_id',
                                        $emoji->emp_id,
                                    )->first();
                                @endphp

                                @if ($employee && $employee->image && $employee->image !== 'null')
                                    <img style="border-radius: 50%; margin-left: 10px"
                                        height="30" width="30"
                                        src="data:image/jpeg;base64,{{ $employee->image }}">
                                @else
                                    @if ($employee && $employee->gender == 'Male')
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="30" width="30"
                                            src="{{ asset('images/male-default.png') }}"
                                            alt="Default Male Image">
                                    @elseif($employee && $employee->gender == 'Female')
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="30" width="30"
                                            src="{{ asset('images/female-default.jpg') }}"
                                            alt="Default Female Image">
                                    @else
                                        <img style="border-radius: 50%; margin-left: 10px"
                                            height="30" width="30"
                                            src="{{ asset('images/user.jpg') }}"
                                            alt="Default Image">
                                    @endif
                                @endif
                            </span>

                            <span style="font-size: 12px; margin-left: 10px;width:50%">
                                {{ ucwords(strtolower($emoji->first_name)) }}
                                {{ ucwords(strtolower($emoji->last_name)) }}
                            </span>

                            <span
                                style="font-size: 16px; cursor: pointer; color: inherit;"
                                wire:click="removeReaction('{{ $emoji->id }}')"
                                onmouseover="this.innerHTML='{{ $emoji->emp_id === auth()->user()->id ? 'Remove Reaction' : $emoji->emoji }}';"
                                onmouseout="this.innerHTML='{{ $emoji->emoji }}';">
                                {{ $emoji->emoji }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show blurred-backdrop"></div>
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
    <span id="smiley-{{ $index }}" class="emoji-trigger"
        onclick="showEmojiList({{ $index }})"
        style="font-size: 16px;cursor:pointer">
        😊




        <!-- List of emojis -->
        <div id="emoji-list-{{ $index }}" class="emoji-list"
            style="display: none;background:white; border-radius:5px; border:1px solid silver; max-height:170px;width:220px; overflow-y: auto;">
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128512','{{ $data['employee']->emp_id }}')">😀</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128513','{{ $data['employee']->emp_id }}')">😁</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128514','{{ $data['employee']->emp_id }}')">😂</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128515','{{ $data['employee']->emp_id }}')">😃</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128516','{{ $data['employee']->emp_id }}')">😄</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128517','{{ $data['employee']->emp_id }}')">😅</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128518','{{ $data['employee']->emp_id }}')">😆</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128519','{{ $data['employee']->emp_id }}')">😇</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128520','{{ $data['employee']->emp_id }}')">😈</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128521','{{ $data['employee']->emp_id }}')">😉</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128522','{{ $data['employee']->emp_id }}')">😊</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128523','{{ $data['employee']->emp_id }}')">😋</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128525','{{ $data['employee']->emp_id }}')">😍</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128524','{{ $data['employee']->emp_id }}')">😌</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128526','{{ $data['employee']->emp_id }}'))">😎</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128527','{{ $data['employee']->emp_id }}'))">😏</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128528','{{ $data['employee']->emp_id }}')">😐</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128529','{{ $data['employee']->emp_id }}')">😑
                </span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128530','{{ $data['employee']->emp_id }}')">😒</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128531','{{ $data['employee']->emp_id }}')">😓</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128532','{{ $data['employee']->emp_id }}')">😔</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128533','{{ $data['employee']->emp_id }}')">😕</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128534','{{ $data['employee']->emp_id }}')">😖</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128535','{{ $data['employee']->emp_id }}')">😗</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128536','{{ $data['employee']->emp_id }}')">😘</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128537','{{ $data['employee']->emp_id }}')">😙</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128538','{{ $data['employee']->emp_id }}')">😚</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128539','{{ $data['employee']->emp_id }}')">😛</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128540','{{ $data['employee']->emp_id }}')">😜</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128541','{{ $data['employee']->emp_id }}')">😝</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128542','{{ $data['employee']->emp_id }}')">😞</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128543','{{ $data['employee']->emp_id }}')">😟</span>

            </div>
            <div class="emoji-row">
                <!-- Add more emojis here -->
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128544','{{ $data['employee']->emp_id }}')">😠</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128545','{{ $data['employee']->emp_id }}')">😡
                </span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128546','{{ $data['employee']->emp_id }}')">😢</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128547','{{ $data['employee']->emp_id }}')">😣</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128548','{{ $data['employee']->emp_id }}')">😤</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128549','{{ $data['employee']->emp_id }}')">😥</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128550','{{ $data['employee']->emp_id }}')">😦</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128551','{{ $data['employee']->emp_id }}')">😧</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128552','{{ $data['employee']->emp_id }}')">😨</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128553','{{ $data['employee']->emp_id }}')">😩</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128554','{{ $data['employee']->emp_id }}')">😪</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128555','{{ $data['employee']->emp_id }}')">😫</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128556','{{ $data['employee']->emp_id }}')">😬</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128557','{{ $data['employee']->emp_id }}')">😭</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128558','{{ $data['employee']->emp_id }}')">😮</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128559','{{ $data['employee']->emp_id }}')">😯</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128560','{{ $data['employee']->emp_id }}')">😰</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128561','{{ $data['employee']->emp_id }}')">😱</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128562','{{ $data['employee']->emp_id }}')">😲</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128563','{{ $data['employee']->emp_id }}')">😳</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128564','{{ $data['employee']->emp_id }}')">😴</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128565','{{ $data['employee']->emp_id }}')">😵</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128566','{{ $data['employee']->emp_id }}')">😶</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128567','{{ $data['employee']->emp_id }}')">😷</span>

            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128075','{{ $data['employee']->emp_id }}')">👋</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#9995','{{ $data['employee']->emp_id }}')">✋</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128400','{{ $data['employee']->emp_id }}')">🖐</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128406','{{ $data['employee']->emp_id }}'))">🖖</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#129306','{{ $data['employee']->emp_id }}'))">🤚</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#9757','{{ $data['employee']->emp_id }}'))">☝</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128070','{{ $data['employee']->emp_id }}')">👆</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128071','{{ $data['employee']->emp_id }}')">👇</span>


            </div>
            <div class="emoji-row">
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128072','{{ $data['employee']->emp_id }}')">👈</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128073','{{ $data['employee']->emp_id }}')">👉</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128405','{{ $data['employee']->emp_id }}')">🖕</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#9994','{{ $data['employee']->emp_id }}')">✊</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128074','{{ $data['employee']->emp_id }}'))">👊</span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128077','{{ $data['employee']->emp_id }}'))">👍
                </span>
                <span class="emoji-option"
                    wire:click="selectEmoji('&#128078','{{ $data['employee']->emp_id }}')">👎</span>
                    <span class="emoji-option"
                    wire:click="selectEmoji('&#128076','{{ $data['employee']->emp_id }}')">👌</span>
            </div>
       

</form>
</div>
</div>
</div>



<div class="col-md-7">
<div class="col-md-7 mb-2">
<div style="display: flex; align-items: center;">
<span>
<i class="comment-icon">💬</i>
</span>
<span style="margin-left: 5px;">
<a href="#" onclick="comment({{ $index }})"
    style="font-size: 10px;">Comment</a>
</span>
</div>
</div>




</div>
<div class="col-md-10">
<form wire:submit.prevent="createcomment('{{ $data['employee']->emp_id }}')">
@csrf
<div class="row m-0">


<div class="col-md-12 p-0 mb-2" style="margin-left:2px;">
<div class="replyDiv row m-0" id="replyDiv_{{ $index }}"
    style="display: none;">

    <div class="col-md-1">
        @if ($employeeDetails->image && $employeeDetails->image !== 'null')
            <img style="border-radius: 50%; " height="50" width="50"
                src="data:image/jpeg;base64,{{ $employeeDetails->image }}"
                alt="Employee Image">
        @else
            @if ($employeeDetails && $employeeDetails->gender == 'Male')
                <img style="border-radius: 50%; " height="50" width="50"
                    src="{{ asset('images/male-default.png') }}"
                    alt="Default Male Image">
            @elseif($employeeDetails && $employeeDetails->gender == 'Female')
                <img style="border-radius: 50%; " height="50" width="50"
                    src="{{ asset('images/female-default.jpg') }}"
                    alt="Default Female Image">
            @else
                <img style="border-radius: 50%; " height="50" width="50"
                    src="{{ asset('images/user.jpg') }}" alt="Default Image">
            @endif
        @endif


    </div>
    <div class="col-md-11" style="position: relative;">
        <textarea wire:model="newComment" placeholder="Post your comments here.." name="comment"
            class="comment-box px-1.5x py-0.5x pb-3x text-secondary-600 border-secondary-200 placeholder-secondary-300 focus:border-primary-300 w-full rounded-sm border font-sans text-xs outline-none">
</textarea>
<button type="submit" class="addcomment" value="Comment"  wire:target="addcomment">
    Comment
</button>
      
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
$validCardIds = $cardCommentsCount
->filter(function ($count) {
return $count >= 1; // Use >= 2 to include cards with exactly 2 comments
})
->keys();

// Filter comments to include only those for cards with at least 2 comments
$filteredComments = $addcomments->whereIn('card_id', $validCardIds);

// Sort the filtered comments based on the sortType
if ($sortType === 'interacted') {
$filteredComments = $filteredComments->sortByDesc('updated_at');
}
@endphp
<div class="m-0 mt-2 px-2" id="comments-container" style="overflow-y:auto; max-height:150px;">
@foreach ($filteredComments->where('card_id', $data['employee']->emp_id)->sortByDesc('created_at') as $comment)
<div class="mb-3 comment-item" data-created="{{ $comment->created_at }}"
data-interacted="{{ $comment->updated_at }}"
style="display: flex; gap: 10px; align-items: center;">
<div class="comment" style="font-size: 10px; display: flex;">


@if ($comment->employee)
@if ($comment->employee->image && $comment->employee->image !== 'null')
    <img style="border-radius: 50%; margin-left: 10px" height="30" width="30"
        src="data:image/jpeg;base64,{{ $comment->employee->image }}"
        alt="Employee Image">
@else
    @if ($comment->employee && $comment->employee->gender == 'Male')
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="30" src="{{ asset('images/male-default.png') }}"
            alt="Default Male Image">
    @elseif($comment->employee && $comment->employee->gender == 'Female')
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="40" src="{{ asset('images/female-default.jpg') }}"
            alt="Default Female Image">
    @else
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="30" src="{{ asset('images/user.jpg') }}" alt="Default Image">
    @endif
@endif


<div class="comment-details" style="font-size: 10px;margin-left:10px">
    <b style="color:#778899; font-weight:500; font-size: 10px;">
        {{ ucwords(strtolower($comment->employee->first_name)) }}
        {{ ucwords(strtolower($comment->employee->last_name)) }}
    </b>
    <p class="mb-0" style="font-size: 11px;">
        {{ ucfirst($comment->addcomment) }}
    </p>
</div>
@elseif ($comment->hr)
@if ($comment->hr->image && $comment->hr->image !== 'null')
    <img style="border-radius: 50%; margin-left: 10px" height="30" width="30"
        src="data:image/jpeg;base64,{{ $comment->employee->image }}"
        alt="Employee Image">
@else
    @if ($comment->hr && $comment->hr->gender == 'Male')
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="30" src="{{ asset('images/male-default.png') }}"
            alt="Default Male Image">
    @elseif($comment->hr && $comment->hr->gender == 'Female')
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="40" src="{{ asset('images/female-default.jpg') }}"
            alt="Default Female Image">
    @else
        <img style="border-radius: 50%; margin-left: 10px" height="30"
            width="30" src="{{ asset('images/user.jpg') }}" alt="Default Image">
    @endif
@endif
<div class="comment-details" style="font-size: 10px;">
    <b style="color:#778899; font-weight:500; font-size: 10px;">
        {{ ucwords(strtolower($comment->hr->first_name)) }}
        {{ ucwords(strtolower($comment->hr->last_name)) }}
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

                        </div>
       



<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script> -->
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

@push('scripts')
    <script>
        Livewire.on('updateSortType', sortType => {
            Livewire.emit('refreshComments', sortType);
        });
    </script>
@endpush



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
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style
                    .display === "") ? "block" : "none";
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
    document.addEventListener('livewire:update', function() {
        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
            trigger.addEventListener('click', function() {
                var index = this.dataset.index;
                var emojiList = document.getElementById('emoji-list-' + index);
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style
                    .display === "") ? "block" : "none";
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
    document.addEventListener('DOMContentLoaded', function() {
        // Get all radio buttons with name="radio"
        var radios = document.querySelectorAll('input[name="radio"]');

        // Add change event listener to each radio button
        radios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                var url = this.dataset.url; // Get the data-url attribute
                if (url) {
                    window.location.href = url; // Redirect to the URL
                }
            });
        });
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


    // Ensures the corresponding radio button is selected based on current URL
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
        dropdownToggle.childNodes[0].textContent = initialSortType === 'Newest First' ? 'Newest First' :
            'Most Recent Interacted';

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
                Livewire.emit('updateSortType',
                    sortType); // Ensure this event exists and is handled
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
                    return parseInt(comment.dataset.comments) >
                        2; // Only keep comments with more than 2 comments
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
                        img.onerror =
                            null; // Prevent infinite loop in case the default image also fails
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
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style
                    .display === "") ? "block" : "none";
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '#comment-textarea',
            plugins: 'lists link image preview',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image | preview',
            height: 300,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Save content back to the textarea
                    @this.set('newComment', editor
                        .getContent()); // Update Livewire property
                });
            }
        });
    });
</script>
<script>
    window.addEventListener('emoji-removed', event => {
        // Display the message
        alert(event.detail
            .message); // You can replace this with a nicer UI message (e.g., using a modal or toast)
    });
</script>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<!-- Quill.js JS -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>







<script>
    function execCmd(command) {
        document.execCommand(command, false, null);
    }

    function updateDescription(content) {
        @this.set('description', content); // Update Livewire description property
    }
    function insertVideo() {
    const url = prompt('Enter YouTube Video URL:');
    if (url) {
        // Match standard YouTube or shortened URLs
        const match = url.match(/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
        if (match && match[1]) {
            const embedUrl = `https://www.youtube.com/embed/${match[1]}`;
            const iframe = `<iframe src="${embedUrl}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:50%; height:200px;"></iframe>`;
            document.execCommand('insertHTML', false, iframe);
        } else {
            alert('Invalid YouTube URL. Please use a valid link.');
        }
    }
}



</script>
<script>
    document.addEventListener('livewire:load', function() {
        tinymce.init({
            selector: '#message',
            menubar: false,
            plugins: 'lists advlist',
            toolbar: 'undo redo | bold italic underline | numlist bullist | alignleft aligncenter alignright',
            setup: function(editor) {
                editor.on('change', function() {
                    @this.set('message', editor.getContent());
                });
            }
        });
    });


document.querySelectorAll('.three-dots-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        const empId = this.getAttribute('wire:click').split("'")[1];
        const dropdown = document.querySelector(`#dropdown-menu-${empId}`);
        dropdown.classList.toggle('show'); // Toggle visibility class
    });
});
</script>
</div>
@endif
</div>
