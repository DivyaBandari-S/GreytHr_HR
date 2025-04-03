<div>
  <div wire:loading
        wire:target="addFeeds,submit,">
        <div class="loader-overlay">
            <div class="loader">
                <div></div>
            </div>

        </div>
    </div>
 
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
                    <div class="col-md-3 feeds-custom-menu bg-white p-3 mb-2" >
                        <p class="feeds-left-menu">Filters</p>
                        <hr style="width: 100%;border-bottom: 1px solid grey;">
                        <p class="feeds-left-menu">Activities</p>
                        <div class="activities">
                            <label class="custom-radio-label">
                                <input type="radio" name="radio" value="activities"  data-url="/hr/hrFeeds"
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

                                <input type="radio" id="radio-hr" name="radio" value="posts" checked
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

                <div class="col-md-9 m-0 " style="position: sticky;">
                    <div class="row">
                        <div class="col-md-5" style=" justify-content: flex-start;display:flex">
                            <div style="width: 2px; height: 40px; background-color: #97E8DF; margin-right: 10px;"></div>
                            <gt-heading _ngcontent-eff-c648="" size="md" class="ng-tns-c648-2 hydrated"></gt-heading>
                            <div class="medium-header border-cyan-200" style="margin-left:-1px">Posts</div>
                        </div>
                    </div>





                    <div class="col-md-12 col-md-8 mt-2" style="   position: sticky;">
                        <div class="eventsSection">
                            @if($posts->isEmpty())
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/business-failure-7626119-6210566.png" alt="Empty Image" style="width: 300px; height: auto; display: block;margin-top:-90px">
                            <p class="text-feed">It feels empty here!</p>
                            <p class="text-xs">Your feed is still in making as there's no post to show.</p>
                            <button style="background:#306cc6; width:110px; height:30px; border:1px solid grey; border-radius:5px; color:white;" wire:click="addFeeds">Create Post</button>
                            @if($showFeedsDialog)
                            <!-- Form content here -->
                            @endif
                            @else
                            @foreach($posts as $post)
                            <!-- Post Container -->
                            <div id="post-container " class="feeds-main-content">
                               
                                <div class="col-12 col-md-8 mt-2" id="post-{{ $post->id }}">
                                    <div class="post-card">
                                        <div class="row">
                                            <div class="col-12 col-md-2 text-start mb-2 mb-md-0">
                                                @php
                                                $employee = $post->employeeDetails;
                                                $manager = $post->managerDetails;
                                                @endphp
                                                @if($employee)
                                                @if(!empty($employee->image))
                                                <img src="data:image/jpeg;base64,{{$employee->image}}" alt="Employee Image" class="post-profile-img">
                                                @else
                                                <!-- Employee's Initials -->
                                                <div class="rounded-circle"
                                                    style="width: 45px; height: 45px; background-color: #e986ea;color: white; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                    {{ strtoupper(substr($employee->first_name, 0, 1)) . strtoupper(substr($employee->last_name, 0, 1)) }}
                                                </div>
                                                @endif
                                            </div>

                                            <!-- Second Column: Full Name, Employee ID, and Group (Post Type) -->
                                            <div class="col-6 col-md-7 text-start"
                                                style="font-size: 12px; margin-left: -14px;">
                                                <!-- Adjust padding-left for spacing -->
                                                <p class="p-0 m-0">
                                                    <strong>{{ ucwords(strtolower($employee->first_name . ' ' . $employee->last_name)) }}</strong>
                                                </p>
                                                <p class="p-0 m-0"><span>#{{ $post->emp_id }}</span></p>
                                                <p class="p-0 m-0">Group:
                                                    {{ ucwords(strtolower( $post->category)) }}
                                                </p>
                                                <!-- Post Type -->


                                            </div>
                                            <div class="col-md-3 text-left">
                                                <div class="updated-time">{{ $post->updated_at->diffForHumans() }}</div>
                                            </div>

                                            @elseif($manager)
                                            @if(!empty($manager->image))
                                            <img src="data:image/jpeg;base64,{{$manager->image}}" alt="Employee Image" class="post-profile-img">
                                            @else
                                            <div class="rounded-circle"
                                                style="width: 45px; height: 45px; background-color: #e986ea;color: white; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                                {{ strtoupper(substr($manager->first_name, 0, 1)) . strtoupper(substr($manager->last_name, 0, 1)) }}
                                            </div>
                                            @endif
                                        </div>

                                            <!-- Second Column: Full Name, Employee ID, and Group (Post Type) -->
                                            <div class="col-6 col-md-6 text-start"
                                                style="font-size: 12px; margin-left: -14px;">
                                                <!-- Adjust padding-left for spacing -->
                                                <p class="p-0 m-0">
                                                    <strong>{{ ucwords(strtolower($manager->first_name . ' ' . $manager->last_name)) }}</strong>
                                                </p>
                                                <p class="p-0 m-0"><span>#{{ $manager->emp_id }}</span></p>
                                                <p class="p-0 m-0">Group:
                                                    {{ ucwords(strtolower( $post->category)) }}</p>
                                                <!-- Post Type -->
                                            </div>
                                            <div class="col-md-3 text-left">
                                            <div class="updated-time">{{ $post->updated_at->diffForHumans() }}</div>
                                        </div>
                                        <!-- Post He
             ader -->
          @endif
          <div class="description">    
          {!! $post->description !!}
                </div>
          </div>

                                    <!-- Profile Section -->


            <!-- Post Content -->
            <div class="post-content">
                <!-- Post Description -->
             
                <!-- Post Image -->
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="Post Image" class="post-image" style="height:100px;width:100px">
                @endif
            </div>
        </div>
   

</div>
@endforeach




                        </div>






                        @endif
                    </div>
                    </div>
</div>
                </div>
                </div>
            
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> 
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
    document.addEventListener('livewire:update', function() {
        document.querySelectorAll('.emoji-trigger').forEach(trigger => {
            trigger.addEventListener('click', function() {
                var index = this.dataset.index;
                var emojiList = document.getElementById('emoji-list-' + index);
                emojiList.style.display = (emojiList.style.display === "none" || emojiList.style.display === "") ? "block" : "none";
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
        const button = document.querySelector('#emoji-picker-button');
        const editor = document.querySelector('#editor');
        const picker = new EmojiButton();

        // Initialize Picker
        picker.on('emoji', emoji => {
            console.log('Selected emoji:', emoji); // Debugging log
            editor.focus(); // Ensure focus on the editor
            document.execCommand('insertHTML', false, emoji);
        });

        // Toggle Picker
        button.addEventListener('click', () => {
            picker.togglePicker(button);
            console.log('Picker toggled'); // Debugging log
        });

        console.log('Picker initialized!'); // Debugging log
    </script>





                
             
                </div>