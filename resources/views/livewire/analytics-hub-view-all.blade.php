<div>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .card-container {
            margin: 15px 30px;
        }

        .back {

            display: flex;
            align-items: center;
            cursor: pointer;

        }


        .back:hover .text-primary {
            text-decoration: underline;
            /* Change this to your desired hover color */
        }

        .back i {
            color: #42A5F5;
            margin-right: 10px;
        }

        .search-bar {
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

        .search-bar input[type="text"] {
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

        input[type="radio"] {
            display: none;
        }

        label {
            display: inline-block;
            padding: 10px 20px;
            margin-right: -1px;
            border: 1px solid #ccc;
            cursor: pointer;
            background-color: #f1f1f1;
        }

        label:hover {
            background-color: #ddd;
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
        }

        .accordion-collapse {
            width: 100%;
        }

        .accordion-body {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .list-group-item {
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
            padding: 1rem;
        }

        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
        }

        .nav-link {
            border: 1px solid transparent;
        }

        .list-group-item {
            padding: 0.5rem 1rem;
        }

        .star-icon {
            color: #c0c0c0;
            margin-right: 0.5rem;
        }

        .folder-icon {
            margin-right: 0.5rem;
        }
    </style>
    <div class="card-container">
        <div class="back" wire:click="goBack">
            <i class="fas fa-arrow-left"></i>
            <span class="text-primary">Go Back</span>
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
                    <a class="nav-link" id="add-folder" href="#">Add Folder</a>
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