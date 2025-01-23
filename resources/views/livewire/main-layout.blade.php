<div>
    <!-- start: SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <!-- <i class="ph-flame-fill"></i> -->
            <img src="{{ asset('images/user-group.png') }}" alt="" style="width: 1.3em;">
        </a>

        <ul class="sidebar__menu">
            <li>
                <a href="#" class="active"><i class="ph-house-fill"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Home</li>
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li><a href="{{route('request')}}">Request</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="ph ph-rss-simple"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Engage</li>
                    <li><a href="/hr/hrFeeds">Feeds</a></li>
                </ul>
            </li>


            <li>
                <a href="#"><i class="ph-clipboard-fill"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Forms</li>
                    <li><a href="#">Basic</a></li>
                    <li><a href="#">Input group</a></li>
                    <li><a href="#">Layout</a></li>
                    <li><a href="#">Validation</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="ph-fill ph-user"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Employee</li>
                    <li>
                        <a href="#">Main <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/user/main-overview">Overview</a></li>
                            <li><a href="/hr/user/analytics-hub">Analytics Hub</a></li>
                            <li><a href="/hr/user/hremployeedirectory">Employee Directory</a></li>
                            <li><a href="/user">Organization Chart</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Information <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/employee-profile">Employee Profile</a></li>
                            <li><a href="/hr/position-history">Postion History</a></li>
                            <li><a href="/hr/employee-asset">Assets</a></li>
                            <li><a href="/hr/bank-account">Bank/PF/ESI</a></li>
                            <li><a href="/hr/parent-details">Family Details</a></li>
                            <li><a href="/hr/emp-document">Employee Documents</a></li>

                            <li><a href="/user">Previous Employement</a></li>
                            <li><a href="/user">Separration</a></li>
                            <li><a href="/user">Acess card details</a></li>

                            <li><a href="/user">Employee Contracts</a></li>
                            <li><a href="/user">Employee Salary</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Admin <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/home">Overview</a></li>
                            <li><a href="/user">Analytics Hub</a></li>
                            <li><a href="/user">Employee Directory</a></li>
                            <li><a href="/user">Organization Chart</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Setup <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/home">Overview</a></li>
                            <li><a href="/user">Analytics Hub</a></li>
                            <li><a href="/user">Employee Directory</a></li>
                            <li><a href="/user">Organization Chart</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="ph-fill ph-money"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Accounts</li>
                    <li><a href="#">User settings</a></li>
                    <li><a href="#">Change password</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="ph-fill ph-calendar-blank"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Leave</li>
                    <li>
                        <a href="#">Main <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/user/leave-overview">Leave Overview</a></li>
                            <li><a href="/hr/user/hr-attendance-overview">Attendance Overview</a></li>
                            <li><a href="/hr/user/hremployeedirectory">Leave Calendar</a></li>
                            <li><a href="/hr/user/who-is-in-chart-hr">Who is in?</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Information <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/user/employee-leave">Employee Leave</a></li>
                            <li><a href="/hr/user/shift-roster-hr">Shift Roaster</a></li>
                            <li><a href="/user">Employee Swipes</a></li>
                            <li><a href="/hr/user/attendance-muster-hr">Attendance Muster</a></li>
                            <li><a href="/hr/user/attendance-info">Attendance Info</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Admin <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/user/grant-summary">Leave Granter</a></li>
                            <li><a href="/hr/user/leaveYearEndProcess">Year End Process</a></li>
                            <li><a href="/user">Assign Attendnace Scheme</a></li>
                            <li><a href="/user">Process Attendance</a></li>
                            <li><a href="/home">Attendance Period Finalisation</a></li>
                            <li><a href="/user">Attendance Exception</a></li>
                            <li><a href="/user">Lock Configuartion</a></li>
                            <li><a href="/user">Manual Override</a></li>
                            <li><a href="/user">Shift Override</a></li>
                            <li><a href="/user">Leave Recalculator</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Setup <i class="ph-caret-right-fill"></i></a>
                        <ul class="sidebar__dropdown-menu">
                            <li><a href="/hr/user/holidayList">Holiday List</a></li>
                            <li><a href="/user">weekend Override</a></li>
                            <li><a href="/hr/user">Swipe Managment</a></li>
                            <li><a href="/user">Shift Rotation Calendar</a></li>
                            <li><a href="/home">Employee Week Days</a></li>
                            <li><a href="/user">Leave Type Reviewer</a></li>
                            <li><a href="/user">Ip Address Mapping</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="ph-fill ph-airplay"></i></a>
                <ul class="sidebar__submenu">
                    <li class="title">Accounts</li>
                    <li><a href="#">User settings</a></li>
                    <li><a href="#">Change password</a></li>
                </ul>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#"><i class="ph-gear-fill"></i></a>
            </li>
            <div class="pointer m-auto">@livewire('log-out')</div>
        </ul>
    </section>

    <!-- start: SIDEBAR OVERLAY -->
    <div class="sidebar-overlay"></div>
    <!-- end: SIDEBAR OVERLAY -->
    <!-- end: SIDEBAR -->

    <!-- start: SIDEBAR MOBILE -->
    <section id="sidebar-mobile">
        <i class="ph-squares-four-fill toggle-sidebar"></i>
        <a href="#" class="brand">
            <img src="{{ asset('images/user-group.png') }}" alt="Image Description" style="width: 1.3em;">
            <!-- <i class="ph-flame-fill"></i> -->
            Hr Track
        </a>
    </section>
    <!-- end: SIDEBAR MOBILE -->
    <section>
        <!-- start: MAIN TOP -->
        <div class="main__top">
            <div class="main__top__title">
                <h3>Admin Dashboard</h3>
                <ul class="breadcrumbs">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li class="divider">/</li>
                    <li><a href="#" class="active">Dashboard</a></li>
                </ul>
            </div>
            <ul class="main__top__menu">
                <!-- <li class="search">
                    <a href="#">
                        <i class="ph-magnifying-glass"></i>
                    </a>
                    <div class="main__dropdown">
                        <form action="#">
                            <input type="text" name="" placeholder="Search">
                        </form>
                        <span>Recent Search</span>
                        <ul class="recent-search">
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <h5>Keyword</h5>
                                    <p>Lorem ipsum dolor sit amet consectetur...</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <li class="notification">
                    <a href="#">
                        <i class="ph-bell"></i>
                    </a>
                    <div class="main__dropdown">
                        <div class="notification__top">
                            <h4>Notifications</h4>
                            <span>6</span>
                        </div>
                        <ul class="notification__item">
                            <li>
                                <a href="#">
                                    <div class="left">
                                        <h5>Notification title</h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                    </div>
                                    <span>3 hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="left">
                                        <h5>Notification title</h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                    </div>
                                    <span>3 hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="left">
                                        <h5>Notification title</h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                    </div>
                                    <span>3 hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="left">
                                        <h5>Notification title</h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                    </div>
                                    <span>3 hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="left">
                                        <h5>Notification title</h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                    </div>
                                    <span>3 hours</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="inbox">
                    <a href="#">
                        <i class="ph-chat-circle-dots"></i>
                    </a>
                    <span></span>
                </li>
                <li class="profile">
                    <a href="#">
                        <img class="clientImg" src="{{ $companiesLogo->company_logo }}">
                        @if ($loginAdminDetails->image !== null && $loginAdminDetails->image != "null" && $loginAdminDetails->image != "Null" && $loginAdminDetails->image != "")
                        <img src="data:image/jpeg;base64,{{($loginAdminDetails->image)}} " alt="">
                        @else
                        @if($loginAdminDetails->gender=='Female')
                        <img src="{{ asset('images/female-default.jpg') }}" alt="">
                        @elseif($loginAdminDetails->gender=='Male')
                        <img src="{{ asset('images/male-default.png') }}" alt="">
                        @else
                        <img src="{{ asset('images/user.jpg') }}" alt="">
                        @endif
                        @endif
                    </a>
                    <div class="main__dropdown">
                        <div class="profile__top">
                            @if ($loginAdminDetails->image !== null && $loginAdminDetails->image != "null" && $loginAdminDetails->image != "Null" && $loginAdminDetails->image != "")
                            <img src="data:image/jpeg;base64,{{($loginAdminDetails->image)}} " alt="">
                            @else
                            @if($loginAdminDetails->gender=='Female')
                            <img src="{{ asset('images/female-default.jpg') }}" alt="">
                            @elseif($loginAdminDetails->gender=='Male')
                            <img src="{{ asset('images/male-default.png') }}" alt="">
                            @else
                            <img src="{{ asset('images/user.jpg') }}" alt="">
                            @endif
                            @endif
                            <div class="name">
                                <h6>{{ ucwords(strtolower(($loginAdminDetails->first_name))) }} {{ ucwords(strtolower(($loginAdminDetails->last_name))) }}</h6>
                                <p>{{ ucwords(strtolower(($loginAdminDetails->job_role))) }}</p>
                            </div>
                        </div>
                        <ul class="profile__menu">
                            <li><a href="#"><i class="ph-user-circle-fill"></i> Edit profile</a></li>
                            <li><a href="#"><i class="ph-gear-fill"></i> Settings</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- end: MAIN TOP -->



        <!-- Logout Modal -->

    </section>
</div>