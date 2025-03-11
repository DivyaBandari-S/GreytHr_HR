<!-- start: MAIN -->
<section>
    <!-- start: MAIN BODY -->
    <div a>
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

                            <div class="row m-0 mb-3">
                                <div class="col-md-4">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>Available Position</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                24
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat"> 
                                            <span class="icon-stat">
                                                <p>4</p>
                                            </span>
                                        </div>
                                        <div class="box-stat box3-stat">
                                            <span class="icon-stat">
                                            </span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>Job Open</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                24
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat"> 
                                            <span class="icon-stat">
                                                <p>4</p>
                                            </span>
                                        </div>
                                        <div class="box-stat box3-stat">
                                            <span class="icon-stat">
                                            </span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>New Employee(s)</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                24
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat"> 
                                            <span class="icon-stat">
                                                <p>4</p>
                                            </span>
                                        </div>
                                        <div class="box-stat box3-stat">
                                            <span class="icon-stat">
                                            </span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row m-0">
                                <div class="col-md-6">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>Total Employees</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                24
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat"> 
                                            <span class="icon-stat">
                                                <p>4</p>
                                            </span>
                                        </div>
                                        <div class="box-stat box3-stat">
                                            <span class="icon-stat">
                                            </span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>Resignation Request(s)</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                24
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat"> 
                                            <span class="icon-stat">
                                                <p>4</p>
                                            </span>
                                        </div>
                                        <div class="box-stat box3-stat">
                                            <span class="icon-stat">
                                            </span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </div>
                                
                            </div>

                            <!-- <div class="border m-0 rounded row mb-3">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employees By Department</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="employeeByDep"></div>
                            </div> -->


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

                            <div class="border mb-3 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-12">
                                        <p class="fw-bold mb-0">Top 5 Leave Taker for CL</p>
                                        <p class="fs12">01 Nov 2024 to 28 Feb 2025</p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table fs12">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Team</th>
                                                <th scope="col">Priority</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>PRO-001</td>
                                                <td>Alok BD</td>
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
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge text-bg-danger">High</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>PRO-002</td>
                                                <td>T Akash</td>
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
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge text-bg-danger">High</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>PRO-003</td>
                                                <td>Akhil KC</td>
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
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge text-bg-danger">High</span>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
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
                        
                        <!-- <div class="col-md-6 ps-0">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Projects</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>

                                <div class="table-responsive">

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
                        </div> -->
                    </div>

                </div>
                <div class="tab-page" data-tab-page="in-review">
                    <h1 class="tab-page-title ms-3">In Review</h1>
                    <div class="row m-0 mb-3">
                        <div class="col-md-6 mb-3">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Attendance Overview</p>
                                    </div>
                                    <div class="col-md-6 text-end mb-3">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="attendanceDiv"></div>
                                <div class="row m-0">
                                    <p class="mb-1 fw-bold">Status</p>
                                    <div class="col-6 pe-0">
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #008ffb"></i> Present</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #00e396"></i> Late</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #feb019"></i> Permission</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #ff4560"></i> Absent</p>
                                    </div>
                                    <div class="col-6 ps-0 text-end">
                                        <p class="mb-1 fw-bold">59%</p>
                                        <p class="mb-1 fw-bold">21%</p>
                                        <p class="mb-1 fw-bold">2%</p>
                                        <p class="mb-1 fw-bold">15%</p>
                                    </div>
                                </div>
                                <div class="m-0 mb-3 mt-3 px-4 row">
                                    <div class="bg-light m-0 p-0 py-2 rounded row">
                                        <div class="col-md-8 mb-3 d-flex align-items-center">
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

                        <div class="col-md-6 mb-3">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Tasks Statistics</p>
                                    </div>
                                    <div class="col-md-6 mb-3 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="taskDiv"></div>
                                <div class="row m-0 mt-3">
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
                                        <div class="col-md-8 mb-3">
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
                    </div>
                </div>
                
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
