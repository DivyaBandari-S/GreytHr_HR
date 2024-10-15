<div>
    <style>
        /* analytics hub view all styles started */

        .card-container {
            margin: 15px 30px;
        }

        .back {
            display: flex;
            align-items: center;
        }

        .analytic-blue-text:hover {
            text-decoration: underline;
            cursor: pointer;
            /* Change this to your desired hover color */
        }

        .back i {
            color: var(--main-button-color);
            margin-right: 10px;
            font-size: var(--sub-headings-font-size);
            cursor: pointer;
        }

        .analytic-view-all-search-bar {
            display: flex;
            padding: 20px 0px;
            justify-content: space-between;
            /* Adjust spacing between items */
            align-items: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .analytic-view-all-search-bar input[type="text"] {
            width: 280px;
            padding: 6px 28px 6px 10px;
            /* Adjust padding for right space */
            border: 1px solid #ccc;
            border-radius: 18px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            color: #666;
            pointer-events: none;
        }

        .tab-container {
            margin-bottom: 48px;
        }


        input[type="radio"]:checked+label {
            background-color: white;
            border-bottom: 1px solid white;
        }

        .accordion-item {
            width: 100%;
        }

        .accordion-button {
            width: 100%;
            color: black;
            font-size: var(--sub-headings-font-size);
        }

        .accordion-collapse {
            width: 100%;
        }

        .accordion-body {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        a .list-group-item {
            width: 100%;
        }

        .custom-accordion .accordion-button:after {
            display: none;
        }

        .custom-accordion .accordion-button {
            padding-left: 1.5rem;
        }

        .custom-accordion .accordion-item {
            border: none;
        }

        .custom-accordion .accordion-body {
            padding-left: 3rem;
        }

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            /* padding: 1rem; */
        }

        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
            color: var(--main-button-color);
        }

        .nav-tabs .nav-link {
            font-size: var(--sub-headings-font-size);
            color: black;
        }

        .nav-tabs .nav-link.folder-active {
            font-size: var(--sub-headings-font-size);
            color: var(--main-button-color);
        }

        .nav-link {
            border: 1px solid transparent;
        }

        .list-group-item {
            padding: 0.5rem 1rem;
            color: var(--main-button-color);
            font-size: var(--sub-headings-font-size);
        }

        .star-icon {
            color: #c0c0c0;
            margin-right: 0.5rem;
        }

        .star-icon.text-warning {
            color: yellow;
            /* or your desired yellow shade */
        }

        .folder-icon {
            margin-right: 0.5rem;
        }
    </style>
    <div class="card-container">
        <div class="back">
            <i class="bx bx-arrow-back" wire:click="goBack"></i>
            <span class="analytic-blue-text" wire:click="goBack">Go Back</span>
        </div>
        <div class="analytic-view-all-search-bar">
            <div class="search-wrapper">
                <input type="text" placeholder="Search...">
                <i class="search-icon bx bx-search"></i>
            </div>
        </div>


        <div class=" p-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" wire:click="$set('activeTab', 'all')"
                        id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all"
                        aria-selected="true">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'starred' ? 'active' : '' }}"
                        wire:click="$set('activeTab', 'starred')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                        role="tab" aria-controls="starred" aria-selected="false">Starred</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link folder-active" href="#" wire:click.prevent="openModal">Add Folder</a>
                </li>
            </ul>
            <!-- Modal -->
            @if ($isModalOpen)
                <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Folder</h5>
                                <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"
                                    data-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="folderName" class="form-label analytic-folder-label">Folder Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control placeholder-small" id="folderName"
                                        wire:model.debounce.0ms="folderName" wire:input="autoValidate">
                                    @error('folderName')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="fileName" class="form-label analytic-folder-label">File Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control placeholder-small" id="fileName"
                                        wire:model.debounce.0ms="fileName" wire:input="autoValidate">
                                    @error('fileName')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="submit-btn" wire:click="saveFolder">Save</button>
                                <button type="button" class="cancel-btn" wire:click="resetFields">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show blurred-backdrop"></div>
            @endif
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show {{ $activeTab === 'all' ? 'active' : '' }}" id="all"
                    role="tabpanel" aria-labelledby="all-tab">
                    <div class="accordion" id="accordionExample">
                        @foreach ($folders as $index => $folder)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button
                                        class="accordion-button {{ $isOpenEventList === $index ? '' : 'collapsed' }}"
                                        type="button" wire:click="toggleAccordion({{ $index }})"
                                        aria-expanded="{{ $isOpenEventList === $index ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $index }}">
                                        <i class="bx bxs-folder folder-icon"></i> {{ $folder['name'] }}
                                        ({{ count($folder['files']) }})
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $isOpenEventList === $index ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @foreach ($folder['files'] as $file)
                                                <li class="list-group-item">
                                                    <i class="bx bxs-star star-icon {{ $file['isStarred'] ? 'text-warning' : '' }}"
                                                        wire:click="toggleStar('{{ $file['name'] }}', '{{ $folder['name'] }}')"></i>
                                                    {{ $file['name'] }}
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button {{ !$isOpenEventList ? 'collapsed' : '' }}" type="button" wire:click="toggleAccordion('eventList')" aria-expanded="{{ $isOpenEventList ? 'true' : 'false' }}" aria-controls="collapseOne">
                                    <i class="fas fa-folder folder-icon"></i> Event List (6)
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse {{ $isOpenEventList ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Confirmation Dues</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Recent New Joiners</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Upcoming Birthdays</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Upcoming Anniversaries</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Recent Transfers</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Recent Resignees</li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button {{ !$isOpenMySheets ? 'collapsed' : '' }}" type="button" wire:click="toggleAccordion('mySheets')" aria-expanded="{{ $isOpenMySheets ? 'true' : 'false' }}" aria-controls="collapseTwo">
                                    <i class="fas fa-folder folder-icon"></i> My Sheets (1)
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse {{ $isOpenMySheets ? 'show' : '' }}" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Sheet Name</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button {{ !$isOpenEmployeeList ? 'collapsed' : '' }}" type="button" wire:click="toggleAccordion('employeeList')" aria-expanded="{{ $isOpenEmployeeList ? 'true' : 'false' }}" aria-controls="collapseOne">
                                    <i class="fas fa-folder folder-icon"></i> Employee List (6)
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse {{ $isOpenEmployeeList ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Work Experience</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Category Information</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Contact List of All Employees</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Blood Group Details</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Department List With Manager Details</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Basic Information</li>
                                        <li class="list-group-item"><i class="far fa-star star-icon"></i> Personal Information(PII Data)</li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'starred' ? 'show active' : '' }}" id="starred" role="tabpanel" aria-labelledby="starred-tab">
                    <div class="accordion" id="starredAccordion">
                        @foreach ($folders as $index => $folder)
                            @php
                                // Filter starred files for the current folder
                                $starredFiles = collect($folder['files'])->filter(fn($file) => $file['isStarred']);
                            @endphp
                
                            @if ($starredFiles->isNotEmpty())
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingStarred{{ $index }}">
                                        <button
                                            class="accordion-button {{ $isOpenStarred === $index ? '' : 'collapsed' }}"
                                            type="button" wire:click="toggleAccordionStarred({{ $index }})"
                                            aria-expanded="{{ $isOpenStarred === $index ? 'true' : 'false' }}"
                                            aria-controls="collapseStarred{{ $index }}">
                                            <i class="bx bxs-folder folder-icon"></i> {{ $folder['name'] }}
                                            ({{ $starredFiles->count() }})
                                        </button>
                                    </h2>
                                    <div id="collapseStarred{{ $index }}"
                                        class="accordion-collapse collapse {{ $isOpenStarred === $index ? 'show' : '' }}"
                                        aria-labelledby="headingStarred{{ $index }}" data-bs-parent="#starredAccordion">
                                        <div class="accordion-body">
                                            <ul class="list-group">
                                                @foreach ($starredFiles as $file)
                                                    <li class="list-group-item">
                                                        <i class="bx bxs-star star-icon text-warning"></i>
                                                        {{ $file['name'] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
