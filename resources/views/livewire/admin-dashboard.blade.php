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

                            </div>

                            <div class="row ms-3 me-3 annocmentCard">

                                <h6 class="mt-3 announcemnt">Announcement</h6>

                                <div class="m-0 mb-3 row totalEmpCard" style="padding: 10px 0px;">

                                    <div class="col-8">

                                        <p class="m-0">Outing schedule for every departement</p>

                                        <p class="m-0 text-time">5 Minutes ago</p>

                                    </div>

                                    <div class="col-4 m-auto" style="text-align: right">

                                        <a href="#">View</a>

                                    </div>

                                </div>

                                <div class="m-0 mb-3 row hrReqCard" style="padding: 10px 0px;">

                                    <div class="col-8">

                                        <p class="m-0">Outing schedule for every departement</p>

                                        <p class="m-0 text-time">5 Minutes ago</p>

                                    </div>

                                    <div class="col-4 m-auto" style="text-align: right">

                                        <a href="#">View</a>

                                    </div>

                                </div>

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
