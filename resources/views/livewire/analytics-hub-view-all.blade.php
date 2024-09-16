<div>
    <div class="card-container">
        <div class="back" wire:click="goBack">
            <i class="fas fa-arrow-left"></i>
            <span class="analytic-blue-text">Go Back</span>
        </div>
        <div class="search-bar">
            <div class="search-wrapper">
                <input type="text" placeholder="Search...">
                <i class="search-icon fas fa-search"></i>
            </div>
        </div>


        <div class=" p-0">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" wire:click="$set('activeTab', 'all')" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'starred' ? 'active' : '' }}" wire:click="$set('activeTab', 'starred')" id="starred-tab" data-bs-toggle="tab" href="#starred" role="tab" aria-controls="starred" aria-selected="false">Starred</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link folder-active" id="add-folder" href="#">Add Folder</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show {{ $activeTab === 'all' ? 'active' : '' }}" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
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
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade {{ $activeTab === 'starred' ? 'show active' : '' }}" id="starred" role="tabpanel" aria-labelledby="starred-tab">
                <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
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
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>