<div style="padding-top: 20px; display: flex; flex-direction: column; align-items: center;">
    <style>
        .attendance-overview-help {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 5px 5px;
            margin: 10px 0px 30px 0px;
            background-color: #f3faff;
        }

        .hide-attendance-help {
            color: var(--main-button-color);
            font-size: var(--normal-font-size);
            cursor: pointer;
            white-space: nowrap;
            font-weight: 600;
        }

        @media (max-width: 455px) {
            .hide-attendance-help {
                white-space: normal;
            }
        }

        .overview-text {
            font-size: var(--normal-font-size);
            color: var(--label-color);
        }

        .custom-border {
            position: relative;
            margin-left: 10px;
        }

        .overview-main-title {
            font-size: var(--sub-headings-font-size);
            color: var(--main-heading-color);
            font-weight: 600;
            white-space: nowrap;
        }

        .custom-border::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 18%;
            background-color: #ccc;
        }

        .custom-solved-border {
            position: relative;
            margin-left: 10px;
        }

        .custom-solved-border::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 18%;
            background-color: green;
        }

        .employee-profile {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .profile-picture {
            flex-shrink: 0;
            margin-right: 10px;
        }

        .profile-picture img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .employee-details {
            flex-grow: 1;
        }

        .name {
            display: block;
            font-size: 14px;
            font-weight: bold;
        }

        .id {
            display: block;
            font-size: 12px;
            color: #666;
        }

        .days-since-joining {
            display: block;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }
        .overview-profile-image {
    height: 33px;

    width: 33px;

    background-color: lightgray;

    border-radius: 50%;
}
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    max-width: 200px;
    margin-bottom: 0;
    color: #000;
    font-size: 10px;
}
.equal-height-container {
    display: flex;
    flex-direction: column;
    height: 100%; 
}

.equal-height-container > * {
    flex: 1;
    overflow-y: auto;
}
.list-overview-container{
    transition: background-color 0.3s ease;
    margin: 0px;
        }

        .list-overview-container:hover {
            background-color: #f0f8ff; 
            margin: 0px; 
        }
        .chart-container {
        width: 100px; 
        height: 105px;
        position: relative; 
        margin-top: 10px;
    }
    </style>
    <div>
        @if ($showHelp == false)
            <div class="row attendance-overview-help">
                <div class="col-md-11 col-10 d-flex flex-column">
                    <p class="overview-text">The page guides you through an overview of your organization's day-to-day HR
                        activities including lifecycle events like additions, seperations, etc</p>
                    <p class="overview-text">Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">
                            Help-Doc</span>, watching<span style="color: #1fb6ff;cursor:pointer;"> How-to Videos</span>
                        and<span style="color: #1fb6ff;cursor:pointer;"> FAQ</span>.</p>
                </div>
                <div class="hide-attendance-help col-md-1 col-2 d-flex align-items-start">
                    <span wire:click="hideHelp">Hide Help</span>
                </div>
            </div>
        @else
            <div class="row attendance-overview-help">
                <div class="col-11 d-flex flex-column">
                    <p class="overview-text">The page guides you through an overview of your organization's day-to-day
                        HR activities including lifecycle events like additions, seperations, etc</p>
                    <p class="overview-text">Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">
                            Help-Doc</span>, watching<span style="color: #1fb6ff;cursor:pointer;"> How-to Videos</span>
                        and<span style="color: #1fb6ff;cursor:pointer;"> FAQ</span>.</p>
                </div>
                <div class="hide-attendance-help col-1">
                    <span wire:click="showhelp">Show Help</span>
                </div>
            </div>
        @endif
    </div>

    <div class="w-100 p-3" style="border-radius: 5px; background-color: white; margin-bottom: 10px;">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-7 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 style="font-size: var(--normal-font-size); color: var(--label-color)">Employees Head Count
                        </h2>
                        <canvas id="employeeChart" height="200" width="400"></canvas>
                    </div>
                </div>
                <!-- Swipe Records of Employees (Second Column) -->
                <div class="col-md-5 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 style="font-size: var(--sub-headings-font-size); color: var(--label-color);">Employees Login
                            Stats of 2 Months</h2>
                        <h2 style="font-size: var(--normal-font-size); color: var(--label-color);"><span
                                style="color: var(--requiredAlert);">-8%</span> vs Previous month</h2>
                        <canvas id="swipeRecordChart" height="200"></canvas>
                    </div>
                </div>

            </div>


            <div class="row pt-3">

                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white" style="height: 220px;">
                        <h2
                            style="font-size: var(--main-headings-font-size); font-weight: 500; color: var(--main-heading-color);">
                            {{$hrRequestSolvedCount}}</h2>
                        <h2 style="font-size: var(--sub-headings-font-size); color: var(--label-color);">HelpDesk Stats
                            of 3 Months</h2>
                        <h2 style="font-size: var(--normal-font-size); color: var(--label-color);"><span
                                style="color: green;">0%</span> vs Previous month</h2>
                        <div class="row p-0" style="margin-top: 30px;">
                            <div class="col-md-5 d-flex flex-column custom-border">
                                <h1
                                    style="color: var(--main-heading-color); font-size: var(--normal-font-size);margin-top: -1px;">
                                    {{$hrRequestCount}}</h1>
                                <p class="overview-text">Ticket Raised</p>
                            </div>
                            <div class="col-md-5 d-flex flex-column custom-solved-border">
                                <h1
                                    style="color: var(--main-heading-color); font-size: var(--normal-font-size);margin-top: -1px;">
                                    {{$hrRequestSolvedCount}}</h1>
                                <p class="overview-text">Ticket Solved</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2
                            style="font-size: var(--main-headings-font-size); font-weight: 500; color: var(--main-heading-color);">
                            4</h2>
                        <h2 style="font-size: var(--sub-headings-font-size); color: var(--label-color);">Letters
                            Generated Stats of 3 Months</h2>
                        <h2 style="font-size: var(--normal-font-size); color: var(--label-color);"><span
                                style="color: var(--requiredAlert);">0%</span> vs Previous month</h2>
                        <canvas id="swipeRecordChart"></canvas>
                    </div>
                </div> --}}
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2
                            style="font-size: var(--main-headings-font-size); font-weight: 500; color: var(--main-heading-color);">
                            {{ $mobileUsersCount }}</h2>
                        <h2 style="font-size: var(--sub-headings-font-size); color: var(--label-color);">Mobile App
                            Users</h2>
                        <h2 style="font-size: var(--normal-font-size); color: var(--label-color);"><span
                                style="color: var(--requiredAlert);">-25%</span> vs Previous month</h2>
                                <div class="d-flex align-items-center" style="gap: 10px;"> <!-- Adjust margin or padding as needed -->
                                    <div class="chart-container">
                                        <canvas id="MobileUsers"></canvas>
                                    </div>
                            
                                    <div>
                                        <p class="p-0 m-0"  style="font-size: var(--normal-font-size); color: var(--main-heading-color);font-weight: 500;">{{ $mobileUsersCount }} out of {{ $allEmpCount }} </p>
                                        <span style="font-size: var(--normal-font-size); color: var(--label-color);">employees are using the ESS mobile app.</span>
                                        <a href="#" class="btn btn-link p-0" style="font-size: var(--normal-font-size); color: var(--main-button-color); text-decoration: none; font-weight: 500;">Invite More</a>
                                    </div>
                                </div>
                    </div>
                </div>




            </div>

            <!-- Third Row -->

        </div>

    </div>
    <div class="w-100 p-0 mt-3">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <!-- New Joiners for Last 1 Month (First Column) -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded  bg-white" style="height: 100%;">
                        <div class="d-flex justify-content-between p-2">
                            <h2 class="overview-main-title">New Joiners for Last 1 Month</h2>
                            <span
                                style="font-size: var(--sub-headings-font-size);color: var(--main-button-color);font-weight: 500;cursor: pointer;">Add</span>
                        </div>
                        <hr style="border: 1px solid grey; width: 100%; margin-top: 10px;">

                        @if ($newJoiners->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center p-2">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    style="width: 5em; margin: 10px 0px;">
                                <p class="text-muted" style="font-size: 12px;">No Joins in Last Month</p>
                            </div>
                            
                        @else
                        @php
                        $shouldScroll = $newJoiners->count() > 2;
                    @endphp
                    <div style="{{ $shouldScroll ? 'height: 160px; overflow-y: auto; overflow-x: hidden;' : '' }}"> 
                                    <ul class="d-felx flex-column" >
                                        @foreach ($newJoiners as $joiner)
                                        <li class="mb-2 row p-2 list-overview-container" >
                                            <div class="d-flex col-8"  style="gap: 10px;">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>
                                                    @if (!empty($joiner->image) && $joiner->image !== 'null')
                                                        <img class="overview-profile-image"
                                                            src="{{ 'data:image/jpeg;base64,' . base64_encode($joiner->image) }}">
                                                    @else
                                                        @if ($joiner && $joiner->gender == 'Male')
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($joiner && $joiner->gender == 'Female')
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-truncate" style="font-size: var(--sub-headings-font-size); color: var(--label-color);" title="{{ ucwords(strtolower($joiner->first_name)) }} {{ ucwords(strtolower($joiner->last_name)) }}">
                                                        {{ ucwords(strtolower($joiner->first_name)) }}
                                                        {{ ucwords(strtolower($joiner->last_name)) }}</span>
                                                    <span style="font-size: var(--sub-headings-font-size); color: var(--label-color);">{{ $joiner->emp_id }}</span>
                                                </div>
                                                
                                            </div>
                                            <div class="col-4 d-flex justify-content-end">
    
                                                <span style="font-size: var(--normal-font-size); color: var(--label-color);">
                                                    {{ \Carbon\Carbon::parse($joiner->hire_date)->diffInDays(\Carbon\Carbon::now()) }}
                                                    days ago
                                                </span>
                                            </div>
                                        </li>
                                        @endforeach
        
                                    </ul>
                                </div>
                        @endif
                    </div>
                </div>



                <!-- Upcoming Birthdays for the week (Third Column) -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded  bg-white" style="height: 100%;">
                        <div class="p-2">
                            <h2 class="overview-main-title">Upcoming Birthdays for a week</h2>
                           
                        </div>
                        <hr style="border: 1px solid grey; width: 100%; margin-top: 10px;">
                 
                        @if ($employeesBirthdays->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    style="width: 5em; margin: 10px 0px;">
                                <p class="text-muted" style="font-size: 12px;">No birthday reminders to show.</p>
                            </div>
                        @else
                        @php
                $shouldScroll = $employeesBirthdays->count() > 2;
            @endphp
            <div style="{{ $shouldScroll ? 'height: 160px; overflow-y: auto; overflow-x: hidden;' : '' }}"> 
                            <ul class="d-felx flex-column" >
                                @foreach ($employeesBirthdays as $birthday)
                                    <li class="mb-2 row p-2 list-overview-container" >
                                        <div class="d-flex col-8"  style="gap: 10px;">
                                            <!-- You can replace this placeholder with the actual profile image URL -->
                                            <div>
                                                @if (!empty($birthday->image) && $birthday->image !== 'null')
                                                    <img class="overview-profile-image"
                                                        src="{{ 'data:image/jpeg;base64,' . base64_encode($birthday->image) }}">
                                                @else
                                                    @if ($birthday && $birthday->gender == 'Male')
                                                        <img class="overview-profile-image"
                                                            src="{{ asset('images/male-default.png') }}"
                                                            alt="Default Male Image">
                                                    @elseif($birthday && $birthday->gender == 'Female')
                                                        <img class="overview-profile-image"
                                                            src="{{ asset('images/female-default.jpg') }}"
                                                            alt="Default Female Image">
                                                    @else
                                                        <img class="overview-profile-image"
                                                            src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-truncate" style="font-size: var(--sub-headings-font-size); color: var(--label-color);" title="{{ ucwords(strtolower($birthday->first_name)) }} {{ ucwords(strtolower($birthday->last_name)) }}">
                                                    {{ ucwords(strtolower($birthday->first_name)) }}
                                                    {{ ucwords(strtolower($birthday->last_name)) }}</span>
                                                <span style="font-size: var(--sub-headings-font-size); color: var(--label-color);">{{ $birthday->emp_id }}</span>
                                                <span style="font-size: var(--normal-font-size); color: var(--label-color);"> {{ \Carbon\Carbon::parse($birthday->date_of_birth)->format('M, d') }}</span>
                                            </div>
                                            
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Inactive Employees in a Month (First Column) -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded  bg-white" style="height: 100%;">
                        <div class="p-2">
                            <h2 class="overview-main-title">Confirmation due for next 1 month</h2>
                        </div>
                       
                        <hr style="border: 1px solid grey; width: 100%; margin-top: 10px;">
                        @if ($confirmationDue->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    style="width: 5em; margin: 10px 0px;">
                                <p class="text-muted" style="font-size: 12px;">No Confirmation This Month</p>
                            </div>
                        @else
                        @php
                        $shouldScroll = $confirmationDue->count() > 2;
                    @endphp
                    <div style="{{ $shouldScroll ? 'height: 160px; overflow-y: auto; overflow-x: hidden;' : '' }}"> 
                                    <ul class="d-felx flex-column" >
                                        @foreach ($confirmationDue as $confirmation)
                                        <li class="mb-2 row p-2 list-overview-container" >
                                            <div class="d-flex col-8"  style="gap: 10px;">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>
                                                    @if (!empty($confirmation->image) && $confirmation->image !== 'null')
                                                        <img class="overview-profile-image"
                                                            src="{{ 'data:image/jpeg;base64,' . base64_encode($confirmation->image) }}">
                                                    @else
                                                        @if ($confirmation && $confirmation->gender == 'Male')
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($confirmation && $confirmation->gender == 'Female')
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-truncate" style="font-size: var(--sub-headings-font-size); color: var(--label-color);" title="{{ ucwords(strtolower($confirmation->first_name)) }} {{ ucwords(strtolower($confirmation->last_name)) }}">
                                                        {{ ucwords(strtolower($confirmation->first_name)) }}
                                                        {{ ucwords(strtolower($confirmation->last_name)) }}</span>
                                                    <span style="font-size: var(--sub-headings-font-size); color: var(--label-color);">{{ $confirmation->emp_id }}</span>
                                                </div>
                                                
                                            </div>
                                            <div class="col-4 d-flex justify-content-end">
    
                                                <span style="font-size: var(--normal-font-size); color: var(--label-color);">
                                                   in {{ \Carbon\Carbon::parse($confirmation->probation_end_date)->diffInDays(\Carbon\Carbon::now()) }}
                                                    days
                                                </span>
                                            </div>
                                        </li>
                                        @endforeach
        
                                    </ul>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 p-0 mt-3">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <!-- New Joiners for Last 1 Month (First Column) -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded bg-white" style="height: 100%;">
                        <div class="d-flex justify-content-between p-2">
                            <h2 class="overview-main-title">Resigned Employees for Last 1 Month</h2>
                            <span
                                style="font-size: var(--sub-headings-font-size);color: var(--main-button-color);font-weight: 500;cursor: pointer;">Add</span>
                        </div>
                        <hr style="border: 1px solid grey; width: 100%; margin-top: 10px;">

                        @if ($inactiveEmployees->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('images/norecordstoshow.png') }}" alt="Image Description"
                                    style="width: 5em; margin: 10px 0px;">
                                <p class="text-muted" style="font-size: 12px;">No employee resignation to show.</p>
                            </div>
                        @else
                        @php
                        $shouldScroll = $inactiveEmployees->count() > 2;
                    @endphp
                    <div style="{{ $shouldScroll ? 'height: 160px; overflow-y: auto; overflow-x: hidden;' : '' }}"> 
                                    <ul class="d-felx flex-column" >
                                        @foreach ($inactiveEmployees as $inactive)
                                            <li class="mb-2 row p-2 list-overview-container" >
                                                <div class="d-flex col-8"  style="gap: 10px;">
                                                    <!-- You can replace this placeholder with the actual profile image URL -->
                                                    <div>
                                                        @if (!empty($inactive->image) && $inactive->image !== 'null')
                                                            <img class="overview-profile-image"
                                                                src="{{ 'data:image/jpeg;base64,' . base64_encode($inactive->image) }}">
                                                        @else
                                                            @if ($inactive && $inactive->gender == 'Male')
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/male-default.png') }}"
                                                                    alt="Default Male Image">
                                                            @elseif($inactive && $inactive->gender == 'Female')
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/female-default.jpg') }}"
                                                                    alt="Default Female Image">
                                                            @else
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-truncate" style="font-size: var(--sub-headings-font-size); color: var(--label-color);" title="{{ ucwords(strtolower($inactive->first_name)) }} {{ ucwords(strtolower($inactive->last_name)) }}">
                                                            {{ ucwords(strtolower($inactive->first_name)) }}
                                                            {{ ucwords(strtolower($inactive->last_name)) }}</span>
                                                        <span style="font-size: var(--sub-headings-font-size); color: var(--label-color);">{{ $inactive->emp_id }}</span>
                                                        <span style="font-size: var(--normal-font-size); color: var(--label-color);"> {{ \Carbon\Carbon::parse($inactive->resignation_date)->format('d M, Y') }}
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </li>
                                        @endforeach
        
                                    </ul>
                                </div>
                        @endif
                    </div>
                </div>
                <!-- Employee Anniversaries for Current Month (Second Column) -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded bg-white" style="height: 100%;">
                        <div class="p-2">
                            <h2 class="overview-main-title">Joining Anniversary for a week</h2>
                        </div>
                        <hr style="border: 1px solid grey; width: 100%; margin-top: 10px;">
                       
                        @if ($employeesWithAnniversaries->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    style="width: 5em; margin: 10px 0px;">
                                <p class="text-muted" style="font-size: 12px;">No Anniversaries for a week</p>
                            </div>
                        @else
                        @php
                        $shouldScroll = $employeesWithAnniversaries->count() > 2;
                    @endphp
                    <div style="{{ $shouldScroll ? 'height: 160px; overflow-y: auto; overflow-x: hidden;' : '' }}"> 
                                    <ul class="d-felx flex-column" >
                                        @foreach ($employeesWithAnniversaries as $anniversary)
                                            <li class="mb-2 row p-2 list-overview-container" >
                                                <div class="d-flex col-8"  style="gap: 10px;">
                                                    <!-- You can replace this placeholder with the actual profile image URL -->
                                                    <div>
                                                        @if (!empty($anniversary->image) && $anniversary->image !== 'null')
                                                            <img class="overview-profile-image"
                                                                src="{{ 'data:image/jpeg;base64,' . base64_encode($anniversary->image) }}">
                                                        @else
                                                            @if ($anniversary && $anniversary->gender == 'Male')
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/male-default.png') }}"
                                                                    alt="Default Male Image">
                                                            @elseif($anniversary && $anniversary->gender == 'Female')
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/female-default.jpg') }}"
                                                                    alt="Default Female Image">
                                                            @else
                                                                <img class="overview-profile-image"
                                                                    src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-truncate" style="font-size: var(--sub-headings-font-size); color: var(--label-color);" title="{{ ucwords(strtolower($anniversary->first_name)) }} {{ ucwords(strtolower($anniversary->last_name)) }}">
                                                            {{ ucwords(strtolower($anniversary->first_name)) }}
                                                            {{ ucwords(strtolower($anniversary->last_name)) }}</span>
                                                        <span style="font-size: var(--sub-headings-font-size); color: var(--label-color);">{{ $anniversary->emp_id }}</span>
                                                        <span style="font-size: var(--normal-font-size); color: var(--label-color);"> {{ \Carbon\Carbon::parse($anniversary->hire_date)->format('d M, Y') }}
                                                            @if ($anniversary->anniversary_years > 1)
                                                                -{{ $anniversary->anniversary_years }} years
                                                            @elseif ($anniversary->anniversary_years == 1)
                                                            - a year
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </li>
                                        @endforeach
        
                                    </ul>
                                </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- JavaScript for Chart -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var months = @json($months);
    var employeeCounts = @json($employeeCounts);
    console.log(employeeCounts);

    var monthNames = months.map(function(month) {
        return month.split(' ')[0]; // Get the month part only
    });
  

    // Slice data to only show the last 12 months if more than 12 months are available
    if (monthNames.length > 12) {
        months = monthNames.slice(-12);

        employeeCounts = employeeCounts.slice(-12);
    }

    var ctx = document.getElementById('employeeChart').getContext('2d');
    var employeeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,  // X-axis labels (month names)
            datasets: [{
                label: 'Employee Count',
                data: employeeCounts,  // Y-axis data (employee counts)
                borderColor: 'rgba(75, 192, 192, 1)',  // Line color
                borderWidth: 2,  // Line thickness
                fill: false  // Do not fill the area under the line
            }]
        },
        options: {
            responsive: true, // Make chart responsive
            plugins: {
                legend: {
                    display: false  // Disable the legend
                }
            },
            scales: {
                y: {
                    beginAtZero: true,  // Start the y-axis at zero
                    title: {
                        display: true, // Y-axis title
                    },
                    min: 0, 
                    max: 164,
                    grid: {
                color: 'rgba(0, 0, 0, 0.1)', // Set the color of the grid lines
                lineWidth: 0.5,// Set the width of the grid lines
             
            },
                    ticks: {
                        // Set custom step size and labels
                        stepSize: 41, // Set the step size
                        callback: function(value) {
                            // Custom label formatting if needed
                            return value;  // You can format this if needed
                        }
                    }
                },
                x: {
                    title: {
                        display: true, // X-axis title
                    },
                    grid: {
                display: false // Optionally hide grid lines
            },
                 
                }
            }
        }
    });




        // Fetch data for last month
        var selectedDays = [5, 10, 15, 20, 25, 30];
        var lastMonthData = {!! json_encode(array_values($data['lastMonth'] ?? [])) !!};
        // Fetch data for current month
        var currentMonthData = {!! json_encode(array_values($data['currentMonth'] ?? [])) !!};
        var filteredCurrentMonthData = selectedDays.map(day => currentMonthData[day - 1] || 0); // Adjust for 0-based index
        var filteredLastMonthData = selectedDays.map(day => lastMonthData[day - 1] || 0);

     

        // Calculate the number of days in the current month
        // var daysInCurrentMonth = {!! json_encode(now()->daysInMonth) !!};

        // Generate labels for the days of the current month
        // var labels = Array.from({
        //     length: daysInCurrentMonth
        // }, (_, i) => i + 1);
        var labels = selectedDays;

        console.log('Last Month Data:', lastMonthData);
        console.log('Current Month Data:', currentMonthData);
        console.log('Labels:', labels);

        var ctx = document.getElementById('swipeRecordChart').getContext('2d');
        var signInChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Last Month',
                        data: filteredLastMonthData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Blue color
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue color
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'Current Month',
                        data: filteredCurrentMonthData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Pink color
                        borderColor: 'rgba(255, 99, 132, 1)', // Pink color
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                  responsive: true, // Make chart responsive
            plugins: {
                legend: {
                    display: true, // Ensure the legend is displayed
                    position: 'bottom', // Position the legend below the chart
                    labels: {
                        // Optional: Customize the legend labels
                        boxWidth: 10,
                        padding: 15
                    }
                }
            },
                scales: {
                    x: {
                        type: 'linear',
                    min: 5,
                    max: 30,
                    ticks: {
                        stepSize: 5
                    },

                        title: {
                            display: true,
                            text: 'Day of Month'
                        },
                        grid: {
                display: false // Optionally hide grid lines
            }
                    },
                    y: {
                        min: 0,
                    max: 120,
                    ticks: {
                        stepSize: 24,
                        autoSkip: false
                    },
                    grid: {
                color: 'rgba(0, 0, 0, 0.1)', // Set the color of the grid lines
                lineWidth: 0.5,// Set the width of the grid lines
             
            },
       
                        title: {
                            display: true,
                            text: 'Employee Count'
                        },
                        beginAtZero: true
                    }
                }
            }
        });


        var mobileUsersCount = @json($mobileUsersCount);
        var allEmpCount =@json($allEmpCount);

        // Debug logs to check values
        console.log('Mobile Users Count:', mobileUsersCount);
        console.log('All Employees Count:', allEmpCount);

     
            var ctx = document.getElementById('MobileUsers').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Mobile Users', 'Other Users'],
                    datasets: [{
                        data: [mobileUsersCount, allEmpCount - mobileUsersCount], // Use actual counts
                        backgroundColor: ['#f9ecfa', '#be9fc1'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                }
            });
       
    });


</script>
