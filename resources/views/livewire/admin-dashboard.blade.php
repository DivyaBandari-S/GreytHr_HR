<!-- start: MAIN -->
<section>
    <!-- start: MAIN BODY -->
    <div class="main__body" style="overflow: auto; height: calc(100vh - 84px)">
        <section class="tab-section">
            <div class="container-fluid">
                <div class="tab-pane">
                    <button
                        type="button"
                        data-tab-pane="active"
                        class="tab-pane-item active"
                        onclick="tabToggle()">
                        <span class="tab-pane-item-title">01</span>
                        <span class="tab-pane-item-subtitle">Active</span>
                    </button>
                    <button
                        type="button"
                        data-tab-pane="in-review"
                        class="tab-pane-item after"
                        onclick="tabToggle()">
                        <span class="tab-pane-item-title">02</span>
                        <span class="tab-pane-item-subtitle">In Review</span>
                    </button>
                    <!-- <button
                            type="button"
                            data-tab-pane="pending"
                            class="tab-pane-item after"
                        >
                            <span class="tab-pane-item-title">03</span>
                            <span class="tab-pane-item-subtitle">Pending</span>
                        </button>
                        <button
                            type="button"
                            data-tab-pane="paused"
                            class="tab-pane-item after"
                        >
                            <span class="tab-pane-item-title">04</span>
                            <span class="tab-pane-item-subtitle">Paused</span>
                        </button> -->
                </div>
                <div class="tab-page active" data-tab-page="active">
                    <h1 class="tab-page-title">Active</h1>
                    <div class="row m-0 mb-4">
                        <div class="col-md-7 m-auto " style="text-align: center;line-height:2;">

                            <h4 style="background: linear-gradient(90deg, #0F3057, #045575); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome Divya Bandari, your dashboard is ready!</h4>

                            <p class="p-0 " style="color: #565656;font-size:12px;">Congratulations! Your affiliate dashboard is now ready for use. You have access to view profiles, requests, and job details. Get started and make the most out of your affiliate opportunities.</p>

                        </div>

                        <div class="col-md-5">

                            <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 100%;">

                        </div>

                    </div>

                    <div class="row m-0">

                        <div class="col-md-8">

                            <div class="container-sec">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold">My Favourites</h5>
                                    <div class="navigation">
                                        <button id="prev" disabled>&larr;</button>
                                        <button id="next">&rarr;</button>
                                    </div>
                                </div>
                                <div class="scroll-container" id="scrollContainer">
                                    <div data-bs-toggle="modal" data-bs-target="#addFavModal" class="scroll-item blue-bg" style="width: 2em; text-align: center;">
                                        <i class="fa-solid fa-plus" style="padding-top: 4.5em;"></i>
                                    </div>
                                    <div class="scroll-item blue-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user blue-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        
                                        <p>Update Employee Data</P>
                                    </div>

                                    <div class="scroll-item blue-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user blue-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>
                                    <div class="scroll-item blue-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user blue-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>

                                    <div class="scroll-item orag-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user orag-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>
                                    <div class="scroll-item orag-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user orag-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>
                                    <div class="scroll-item orag-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user orag-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>
                                    <div class="scroll-item orag-bg pt-3 ps-3 pe-3">
                                        <div class="row m-0">
                                            <div class="col-6 p-0">
                                                <i class="fa-regular fa-user orag-bg-icon"></i>
                                            </div>
                                            <div class="col-6 p-0 text-end">
                                                <i class="fa-solid fa-xmark closeIconFav"></i>
                                            </div>
                                        </div>
                                        <p>Update Employee Data</P>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-0">
                                <!-- From Uiverse.io by Smit-Prajapati --> 
                                <div class="card-stat">
                                    <div class="background-stat">
                                        <p>Available Position</p>
                                    </div>
                                    <div class="logo-stat">
                                        <p class="logo-svg-stat">
                                            24
                                        </p>
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.667 31.69" class="logo-svg-stat">
                                        <path id="Path_6" data-name="Path 6" d="M12.827,1.628A1.561,1.561,0,0,1,14.31,0h2.964a1.561,1.561,0,0,1,1.483,1.628v11.9a9.252,9.252,0,0,1-2.432,6.852q-2.432,2.409-6.963,2.409T2.4,20.452Q0,18.094,0,13.669V1.628A1.561,1.561,0,0,1,1.483,0h2.98A1.561,1.561,0,0,1,5.947,1.628V13.191a5.635,5.635,0,0,0,.85,3.451,3.153,3.153,0,0,0,2.632,1.094,3.032,3.032,0,0,0,2.582-1.076,5.836,5.836,0,0,0,.816-3.486Z" transform="translate(0 0)"></path>
                                        <path id="Path_7" data-name="Path 7" d="M75.207,20.857a1.561,1.561,0,0,1-1.483,1.628h-2.98a1.561,1.561,0,0,1-1.483-1.628V1.628A1.561,1.561,0,0,1,70.743,0h2.98a1.561,1.561,0,0,1,1.483,1.628Z" transform="translate(-45.91 0)"></path>
                                        <path id="Path_8" data-name="Path 8" d="M0,80.018A1.561,1.561,0,0,1,1.483,78.39h26.7a1.561,1.561,0,0,1,1.483,1.628v2.006a1.561,1.561,0,0,1-1.483,1.628H1.483A1.561,1.561,0,0,1,0,82.025Z" transform="translate(0 -51.963)"></path>
                                        </svg> -->
                                    </div>
                                    <!-- <div class="box-stat box1-stat"><span class="icon-stat"><svg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" class="svg">
                                            <path d="M 9.9980469 3 C 6.1390469 3 3 6.1419531 3 10.001953 L 3 20.001953 C 3 23.860953 6.1419531 27 10.001953 27 L 20.001953 27 C 23.860953 27 27 23.858047 27 19.998047 L 27 9.9980469 C 27 6.1390469 23.858047 3 19.998047 3 L 9.9980469 3 z M 22 7 C 22.552 7 23 7.448 23 8 C 23 8.552 22.552 9 22 9 C 21.448 9 21 8.552 21 8 C 21 7.448 21.448 7 22 7 z M 15 9 C 18.309 9 21 11.691 21 15 C 21 18.309 18.309 21 15 21 C 11.691 21 9 18.309 9 15 C 9 11.691 11.691 9 15 9 z M 15 11 A 4 4 0 0 0 11 15 A 4 4 0 0 0 15 19 A 4 4 0 0 0 19 15 A 4 4 0 0 0 15 11 z"></path>
                                        </svg></span></div> -->
                                    <div class="box-stat box2-stat"> 
                                        <span class="icon-stat">
                                            <!-- <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" class="svg">
                                                <path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path>
                                            </svg> -->
                                            <p>4</p>
                                        </span>
                                    </div>
                                    <div class="box-stat box3-stat"><span class="icon-stat"><svg viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg" class="svg">
                                            <path d="M524.531,69.836a1.5,1.5,0,0,0-.764-.7A485.065,485.065,0,0,0,404.081,32.03a1.816,1.816,0,0,0-1.923.91,337.461,337.461,0,0,0-14.9,30.6,447.848,447.848,0,0,0-134.426,0,309.541,309.541,0,0,0-15.135-30.6,1.89,1.89,0,0,0-1.924-.91A483.689,483.689,0,0,0,116.085,69.137a1.712,1.712,0,0,0-.788.676C39.068,183.651,18.186,294.69,28.43,404.354a2.016,2.016,0,0,0,.765,1.375A487.666,487.666,0,0,0,176.02,479.918a1.9,1.9,0,0,0,2.063-.676A348.2,348.2,0,0,0,208.12,430.4a1.86,1.86,0,0,0-1.019-2.588,321.173,321.173,0,0,1-45.868-21.853,1.885,1.885,0,0,1-.185-3.126c3.082-2.309,6.166-4.711,9.109-7.137a1.819,1.819,0,0,1,1.9-.256c96.229,43.917,200.41,43.917,295.5,0a1.812,1.812,0,0,1,1.924.233c2.944,2.426,6.027,4.851,9.132,7.16a1.884,1.884,0,0,1-.162,3.126,301.407,301.407,0,0,1-45.89,21.83,1.875,1.875,0,0,0-1,2.611,391.055,391.055,0,0,0,30.014,48.815,1.864,1.864,0,0,0,2.063.7A486.048,486.048,0,0,0,610.7,405.729a1.882,1.882,0,0,0,.765-1.352C623.729,277.594,590.933,167.465,524.531,69.836ZM222.491,337.58c-28.972,0-52.844-26.587-52.844-59.239S193.056,219.1,222.491,219.1c29.665,0,53.306,26.82,52.843,59.239C275.334,310.993,251.924,337.58,222.491,337.58Zm195.38,0c-28.971,0-52.843-26.587-52.843-59.239S388.437,219.1,417.871,219.1c29.667,0,53.307,26.82,52.844,59.239C470.715,310.993,447.538,337.58,417.871,337.58Z"></path>
                                        </svg></span></div>
                                    <div class="box-stat box4-stat"></div>
                                </div>
                            </div>

                            <!-- <div class="row m-0">

                                <div class="col-md-4 mb-4">

                                    <div class="row m-0 avaPosCard">

                                        <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">

                                            <div class="col-8 m-0 p-0" style="text-align: start;">

                                                <h4 class="countOfNum m-0 p-0">24</h4>

                                            </div>

                                            <div class="col-4 p-0">

                                                <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                            </div>

                                        </div>

                                        <p class="p-0 subHeading">Available Position</p>

                                        <span class="p-0 pb-3" style="color: #778899;font-size:12px;">4 Urgently needed</span>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-4">

                                    <div class="row m-0 jobOpenCard">

                                        <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">

                                            <div class="col-8 m-0 p-0" style="text-align: start;">

                                                <h4 class=" countOfNum m-0 p-0">4</h4>

                                            </div>

                                            <div class="col-4 p-0 ">

                                                <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                            </div>

                                        </div>

                                        <p class="p-0 subHeading">Job Open</p>

                                        <span class="p-0 pb-3" style="color: #778899;font-size:12px;">4 Active hiring</span>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-4">

                                    <div class="row m-0 newEpmCard">

                                        <div class="d-flex align-items-center justify-content-center mb-4 p-0 pt-2">

                                            <div class="col-8 p-0 m-0" style="text-align: start;">

                                                <h4 class="countOfNum m-0">{{$newEmployees}}</h4>

                                            </div>

                                            <div class="col-4 p-0 " style="text-align: right">

                                                <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                            </div>

                                        </div>

                                        <p class="p-0  subHeading">New Employee(s)</p>

                                        <span class="p-0 pb-3" style="color: #778899;font-size:12px;">{{$newEmployeedeparts}} Department</span>

                                    </div>

                                </div>

                            </div>

                            <div class="row m-0">

                                <div class="col-md-6 mb-4 ">

                                    <div class="row m-0 totalEmpCard">

                                        <div class="col-8  p-0 pb-5 pt-3">

                                            <p class="totalEmpText">Total Employees</p>

                                            <h4 class="countOfNum">{{ $this->activeEmployeesCount }}</h4>

                                        </div>

                                        <div class="col-4 p-0 pb-5 pt-3" style="text-align: right">

                                            <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                        </div>

                                    </div>

                                </div>

                                <a class="col-md-6 mb-4" href='/hr/resig-requests'  style="cursor: pointer;">

                                    <div class="row m-0 hrReqCard">

                                        <div class="col-8 p-0 pb-5 pt-3">

                                            <p class="totalEmpText">Resignation Request(s)</p>

                                            <h4 class="countOfNum">{{$hrRequestsCount}}</h4>

                                        </div>

                                        <div class="col-4 p-0 pb-5 pt-3" style="text-align: right">

                                            <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                        </div>

                                    </div>

                                </a>

                            </div> -->

                            <div class="row ms-3 me-3 annocmentCard">

                                <h6 class="mt-3 announcemnt">Announcement</h6>
                                <div class="cards-announcement">
                                    <div class="card-announcement">
                                        <div class="m-0 mb-3 row totalEmpCard" style="padding: 10px 0px;">

                                            <div class="col-8">

                                                <p class="m-0">Outing schedule for every departement</p>

                                                <p class="m-0 text-time">5 Minutes ago</p>

                                            </div>

                                            <div class="col-4 m-auto" style="text-align: right">

                                                <a href="#">View</a>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-announcement">
                                        <div class="m-0 mb-3 row hrReqCard" style="padding: 10px 0px;">

                                            <div class="col-8">

                                                <p class="m-0">Outing schedule for every departement</p>

                                                <p class="m-0 text-time">5 Minutes ago</p>

                                            </div>

                                            <div class="col-4 m-auto" style="text-align: right">

                                                <a href="#">View</a>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-announcement">
                                        <div class="m-0 mb-3 row newEpmCard" style="padding: 10px 0px;">

                                            <div class="col-8">

                                                <p class="m-0">Outing schedule for every departement</p>

                                                <p class="m-0 text-gray">5 Minutes ago</p>

                                            </div>

                                            <div class="col-4 m-auto" style="text-align: right">

                                                <a href="#">View</a>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="row ms-3 me-3">

                                <h6 class="mt-4 p-0 mx-2 announcemnt" style="text-align: start;">Help Links</h6>

                                <div class="m-0 p-0 " style="text-align: start;">

                                    <span class="helpChip">Attune Global Community</span>

                                    <span class="helpChip">Statutory Compliances</span>

                                    <span class="helpChip">How to Videos</span>

                                    <span class="helpChip">Attune Global Knowledge Center</span>

                                    <span class="helpChip">Resource Center</span>

                                    <span class="helpChip">Attune Global Academy</span>

                                    <span class="helpChip">Attune Global FM</span>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="m-0 mb-3 row" style="background-color: #fcf2ff; border-radius: 10px;">
                                <img class="ps-2" style="width: 10em" src="images/onboarding.png"/>
                                <h4 class="ps-4">Onboarding Session</h4>
                                <p class="ps-4 pink-color">Worried about setting up your account?</p>
                                <p class="ps-4 pink-color">Let our product experts help you get started and resolve any of your product-related problems.</p>
                                <p class="ps-4 pink-color">Hurry Up! Book your free 30-min onboarding call now!</p>
                                <hr></hr>
                                <a class="ps-4 text-primary mb-3" href="#">Get Guided By The Best!</a>
                            </div>

                            <div class="row m-0 mb-4 annocmentCard" style="padding: 10px 15px;">

                                <div class="p-0 m-0 d-flex justify-content-between align-items-center mb-4">

                                    <div style="text-align: start;">

                                        <h6 class="m-0 pt-1">Recently Activity</h6>

                                    </div>

                                    <div>

                                        <img src="{{ asset('images/admin_banner.png') }}" alt="Image Description" style="width: 3em;">

                                    </div>

                                </div>

                                <div class="mt-2 p-0" style="text-align: center;">

                                    <p class="p-0 m-0" style="font-size: 14px;">You Posted a New Job</p>

                                    <p class="p-0 pb-3" style="color: #778899; font-size: 12px;">10.40 AM, Fri 10 Dec 2023</p>

                                </div>

                                <button class="btn btn-primary" style="background: #02114f;font-size:12px;">See All Activity</button>

                            </div>



                            <div class="row m-0 mb-4 annocmentCard">

                                <h6 class="mt-3 announcemnt">Upcoming Schedule</h6>

                                <p>Priority</p>

                                <div class="m-0 mb-3 row totalEmpCard" style="padding: 10px 0px;">

                                    <div class="col-9">

                                        <p class="m-0">Review candidate applications</p>

                                        <p class="m-0 text-time">Today - 11.30 AM</p>

                                    </div>

                                    <div class="col-3 m-auto" style="text-align: right">

                                        <a href="#">Action</a>

                                    </div>

                                </div>

                                <p>Other</p>

                                <div class="m-0 mb-3 row hrReqCard" style="padding: 10px 0px;">

                                    <div class="col-8">

                                        <p class="m-0">Outing schedule for every departement</p>

                                        <p class="m-0 text-time">Today - 11.30 AM</p>

                                    </div>

                                    <div class="col-4 m-auto" style="text-align: right">

                                        <a href="#">Action</a>

                                    </div>

                                </div>

                                <div class="m-0 mb-3 row newEpmCard" style="padding: 10px 0px;">

                                    <div class="col-8">

                                        <p class="m-0">Outing schedule for every departement</p>

                                        <p class="m-0 text-time">Today - 11.30 AM</p>

                                    </div>

                                    <div class="col-4 m-auto" style="text-align: right">

                                        <a href="#">Action</a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="border m-0 rounded row">
                        <div class="border-bottom m-0 mt-3 row">
                            <div class="col-md-6">
                                <p class="fw-bold">Employee Status</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                            </div>
                        </div>
                        <div class="m-0 mt-3 row">
                            <div class="col-md-6">
                                <p>Total Employee</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p>154</p>
                            </div>
                        </div>
                        <div class="m-0 px-4 row">
                            <div class="p-0 progress-stacked">
                                <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 15%">
                                    <div class="progress-bar"></div>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                                    <div class="progress-bar bg-success"></div>
                                </div>
                                <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <div class="progress-bar bg-info"></div>
                                </div>
                            </div>
                        </div>

                        <div class="m-0 mt-3 px-4 row">
                            <div class="border col-md-6 pt-3">
                                <p class="mb-0">Fulltime (48%)</p>
                                <p class="fs-1 fw-bold mb-1">112</p>
                            </div>
                            <div class="border-bottom border-end border-top col-md-6 pt-3 text-end">
                                <p class="mb-0">Contract (20%)</p>
                                <p class="fs-1 fw-bold mb-1">112</p>
                            </div>
                        </div>

                        <div class="m-0 px-4 row">
                            <div class="border-bottom border-end border-start col-md-6 pt-3">
                                <p class="mb-0">Probation (22%)</p>
                                <p class="fs-1 fw-bold mb-1">112</p>
                            </div>
                            <div class="border-bottom border-end col-md-6 pt-3 text-end">
                                <p class="mb-0">WFH (20%)</p>
                                <p class="fs-1 fw-bold mb-1">112</p>
                            </div>
                        </div>

                        <div class="row m-0 mt-3">
                            <p class="mb-1">Top Performer</p>
                            <div class="row m-0">
                                <div class="row m-0 p-2 rounded-2 performerDiv">
                                    <div class="col-md-1 p-0 m-auto">
                                        <p class="mb-0">
                                            <i class="fa-solid fa-award fs-3 me-3 perfColor" style="vertical-align: middle;"></i>
                                            <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                        </p>
                                    </div>
                                    <div class="col-md-11 p-0">
                                        <div class="m-0 row">
                                            <div class="col-md-6 p-0">
                                                <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                <p class="fs12 mb-0">IOS Developer</p>
                                            </div>
                                            <div class="col-md-6 text-end p-0">
                                                <p class="mb-0 fs14">Performance</p>
                                                <p class="fs12 fw-bold mb-0 perfColor">99%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0 my-3">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button">View All</button>
                            </div>
                        </div>

                    </div>

                    <div class="row m-0 mt-3">
                        <div class="col-md-6 ps-0">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Attendance Overview</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="attendanceDiv"></div>
                                <div class="row m-0">
                                    <p class="mb-1 fw-bold">Status</p>
                                    <div class="col-md-6">
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #008ffb"></i> Present</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #00e396"></i> Late</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #feb019"></i> Permission</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #ff4560"></i> Absent</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="mb-1 fw-bold">59%</p>
                                        <p class="mb-1 fw-bold">21%</p>
                                        <p class="mb-1 fw-bold">2%</p>
                                        <p class="mb-1 fw-bold">15%</p>
                                    </div>
                                </div>
                                <div class="m-0 mb-3 mt-3 px-4 row">
                                    <div class="bg-light m-0 p-0 py-2 rounded row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <p class="mb-0 me-2">Total Absenties</p>
                                            <div class="avatar-list-stacked avatar-group-sm">
                                                <span class="avatar avatar-rounded">
                                                    <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-27.jpg" alt="img">
                                                </span>
                                                <span class="avatar avatar-rounded">
                                                    <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-30.jpg" alt="img">
                                                </span>
                                                <span class="avatar avatar-rounded">
                                                    <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-14.jpg" alt="img">
                                                </span>
                                                <span class="avatar avatar-rounded">
                                                    <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-29.jpg" alt="img">
                                                </span>
                                                <a class="avatar bg-primary avatar-rounded text-fixed-white fs10" href="/react/template/index" data-discover="true">+1</a>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="#" class="perfColor" style="text-decoration: underline;">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pe-0">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Clock-In/Out</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>

                                <div class="row m-0 mt-3">
                                    <div class="border m-0 mb-3 p-2 rounded-2 row">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 text-end p-0 m-auto">
                                                    <p class="mb-0 fs14"><i class="fa-regular fa-clock me-2" style="vertical-align: middle;"></i> <span class="badge text-bg-success">09 : 06</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border m-0 mb-3 p-2 rounded-2 row">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 text-end p-0 m-auto">
                                                    <p class="mb-0 fs14"><i class="fa-regular fa-clock me-2" style="vertical-align: middle;"></i> <span class="badge text-bg-success">09 : 06</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border m-0 mb-3 p-2 rounded-2 row">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 text-end p-0 m-auto">
                                                    <p class="mb-0 fs14"><i class="fa-regular fa-clock me-2" style="vertical-align: middle;"></i> <span class="badge text-bg-success">09 : 06</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border m-0 mb-3 p-2 rounded-2 row">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 text-end p-0 m-auto">
                                                    <p class="mb-0 fs14"><i class="fa-regular fa-clock me-2" style="vertical-align: middle;"></i> <span class="badge text-bg-success">09 : 06</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border m-0 mt-2 py-3 rounded row">
                                            <div class="col-md-4">
                                                <p class="fs12 text-secondary mb-0">Clock in</p>
                                                <p class="fs12 mb-0">10:30 AM</p>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <p class="fs12 text-secondary mb-0">Clock out</p>
                                                <p class="fs12 mb-0">10:30 AM</p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <p class="fs12 text-secondary mb-0">Production</p>
                                                <p class="fs12 mb-0">10:30 Hrs</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="fw-bold fs14 p-0">Late</p>
                                    <div class="border m-0 mb-3 p-2 rounded-2 row">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 text-end p-0 m-auto">
                                                    <p class="mb-0 fs14"><i class="fa-regular fa-clock me-2" style="vertical-align: middle;"></i> <span class="badge text-bg-danger">09 : 06</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row m-0 my-3">
                                    <div class="d-grid gap-2 p-0">
                                        <button class="btn btn-outline-secondary btn-sm" type="button">View All Attendance</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0 mt-3">
                        <div class="col-md-6">
                            <div class="border m-0 rounded row">
                                <div class="m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employees</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm">View All</button>
                                    </div>
                                </div>

                                <div class="row m-0 py-2 bg-light">
                                    <div class="col-md-6">
                                        <p class="mb-0 fw-bold fs14">Name</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0 fw-bold fs14">Department</p>
                                    </div>
                                </div>

                                <div class="row m-0 mt-3">
                                    
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 p-0 m-auto">
                                                    <p class="mb-0 fs14"><span class="badge text-bg-success">Finance</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 p-0 m-auto">
                                                    <p class="mb-0 fs14"><span class="badge text-bg-success">Finance</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 p-0 m-auto">
                                                    <p class="mb-0 fs14"><span class="badge text-bg-success">Finance</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 p-0 m-auto">
                                                    <p class="mb-0 fs14"><span class="badge text-bg-success">Finance</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div class="col-md-1 p-0 m-auto">
                                            <p class="mb-0">
                                                <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg" style="width: 2em; border-radius: 50%;" />
                                            </p>
                                        </div>
                                        <div class="col-md-11 p-0">
                                            <div class="m-0 row">
                                                <div class="col-md-6 p-0">
                                                    <p class="fw-bold mb-0 fs14">Daniel Esbella</p>
                                                    <p class="fs12 mb-0">UI/UX Designer</p>
                                                </div>
                                                <div class="col-md-6 p-0 m-auto">
                                                    <p class="mb-0 fs14"><span class="badge text-bg-success">Finance</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 pe-0">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Projects</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>

                                <table class="table fs12">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Team</th>
                                            <th scope="col">Hours</th>
                                            <th scope="col">Deadline</th>
                                            <th scope="col">Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PRO-001</td>
                                            <td>Office Management App</td>
                                            <td>
                                                <div class="avatar-list-stacked avatar-group-sm">
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-27.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-30.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-14.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-29.jpg" alt="img">
                                                    </span>
                                                    <!-- <a class="avatar bg-primary avatar-rounded text-fixed-white fs10" href="/react/template/index" data-discover="true">+1</a> -->
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1">15/255 Hrs</p>
                                                <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                                    <div class="progress-bar" style="width: 25%"></div>
                                                </div>
                                            </td>
                                            <td>12/09/2024</td>
                                            <td>
                                                <span class="badge text-bg-danger">High</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>PRO-002</td>
                                            <td>Office Management App</td>
                                            <td>
                                                <div class="avatar-list-stacked avatar-group-sm">
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-27.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-30.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-14.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-29.jpg" alt="img">
                                                    </span>
                                                    <!-- <a class="avatar bg-primary avatar-rounded text-fixed-white fs10" href="/react/template/index" data-discover="true">+1</a> -->
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1">15/255 Hrs</p>
                                                <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                                    <div class="progress-bar" style="width: 25%"></div>
                                                </div>
                                            </td>
                                            <td>12/09/2024</td>
                                            <td>
                                                <span class="badge text-bg-danger">High</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>PRO-003</td>
                                            <td>Office Management App</td>
                                            <td>
                                                <div class="avatar-list-stacked avatar-group-sm">
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-27.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img class="border border-white" src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-30.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-14.jpg" alt="img">
                                                    </span>
                                                    <span class="avatar avatar-rounded">
                                                        <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-29.jpg" alt="img">
                                                    </span>
                                                    <!-- <a class="avatar bg-primary avatar-rounded text-fixed-white fs10" href="/react/template/index" data-discover="true">+1</a> -->
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-1">15/255 Hrs</p>
                                                <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                                    <div class="progress-bar" style="width: 25%"></div>
                                                </div>
                                            </td>
                                            <td>12/09/2024</td>
                                            <td>
                                                <span class="badge text-bg-danger">High</span>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row m-0 mt-3">
                    <div class="col-md-6 ps-0">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Tasks Statistics</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="taskDiv"></div>
                                <div class="row m-0">
                                    <div class="col-md-3 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle" style="color: #008ffb"></i> Ongoing</p>
                                        <p class="fs14 mb-2 fw-bold">24%</p>
                                    </div>
                                    <div class="col-md-3 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle" style="color: #00e396"></i> On Hold</p>
                                        <p class="fs14 mb-2 fw-bold">10%</p>
                                    </div>
                                    <div class="col-md-3 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle" style="color: #feb019"></i> Overdue</p>
                                        <p class="fs14 mb-2 fw-bold">16%</p>
                                    </div>
                                    <div class="col-md-3 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle" style="color: #ff4560"></i> Ongoing</p>
                                        <p class="fs14 mb-2 fw-bold">24%</p>
                                    </div>
                                </div>
                                <div class="row m-0">
                                    <div class="row m-0 bg-dark rounded py-2 my-3">
                                        <div class="col-md-8">
                                            <p class="mb-1 fw-bold" style="color: #00e396">389/689 hrs</p>
                                            <p class="text-white fs14 mb-0">Spent on Overall Tasks This Week</p>
                                        </div>
                                        <div class="col-md-4 text-end m-auto">
                                            <button class="btn btn-sm btn-light">View All</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>

                </div>
                <div class="tab-page" data-tab-page="in-review">
                    <h1 class="tab-page-title">In Review</h1>
                    <p class="tab-page-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Incidunt ipsum totam vero deleniti? Facere?
                    </p>
                    <p class="tab-page-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Excepturi deleniti, culpa eum ratione similique
                        est magni magnam vitae beatae accusantium, modi,
                        accusamus quisquam! Quos.
                    </p>
                    <img
                        src="https://github.com/shadcn.png"
                        alt=""
                        class="tab-page-image" />
                    <p class="tab-page-text">
                        Lorem ipsum dolor sit, amet consectetur adipisicing
                        elit. Quos laboriosam earum maiores architecto obcaecati
                        eligendi, atque accusamus sed dolores beatae ea minima
                        sint itaque soluta doloribus neque quasi id ratione
                        saepe amet.
                    </p>
                </div>
                <!-- <div class="tab-page" data-tab-page="pending">
                        <h1 class="tab-page-title">Pending</h1>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Incidunt ipsum totam vero deleniti? Facere?
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div>
                    <div class="tab-page" data-tab-page="paused">
                        <h1 class="tab-page-title">Paused</h1>
                        <img
                            src="https://github.com/shadcn.png"
                            alt=""
                            class="tab-page-image"
                        />
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div> -->
            </div>
        </section>
    </div>



<!-- Modal -->
<div class="modal fade" id="addFavModal" tabindex="-1" aria-labelledby="addFavModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addFavModalLabel">Search</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" class="form-control" placeholder="Search here" aria-label="Username" aria-describedby="addon-wrapping">
        </div>
        <div class="row m-0">
            <p class="mt-1x">
                <span class="chipTextFav">All</span>
                <span class="chipTextFav">My Favourites</span>
                <span class="chipTextFav">Employee</span>
                <span class="chipTextFav">Payroll</span>
                <span class="chipTextFav">Leave</span>
                <span class="chipTextFav">Other</span>
            </p>
        </div>
        <div class="row m-0">
            <div class="col-md-3">
                <div class="blue-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user blue-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="blue-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user blue-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="blue-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user blue-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>
            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>

            <div class="col-md-3">
                <div class="orag-bg pt-3 ps-3 pe-3 rounded-3 mb-3">
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <i class="fa-regular fa-user orag-bg-icon"></i>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p>Update Employee Data</P>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
    <!-- end: MAIN BODY -->

</section>
<!-- end: MAIN -->
