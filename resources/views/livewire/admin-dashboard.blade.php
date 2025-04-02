<!-- start: MAIN -->
<section>
    <!-- start: MAIN BODY -->
    <div>
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

                    <div class="row m-0">

                        <div class="col-md-8 sec-2">

                            <div class="container-sec">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold">My Favourites</h5>
                                    <div class="navigation">
                                        <button id="prev" disabled>&larr;</button>
                                        <button id="next">&rarr;</button>
                                    </div>
                                </div>

                                <div class="scroll-container" id="scrollContainer">
                                    <div wire:click="toggleContent" class="scroll-item blue-bg" style="width: 2em; text-align: center;">
                                        <i class="fa-solid fa-plus" style="padding-top: 4.5em;"></i>
                                    </div>
                                    <?php
                                    // Initialize a counter to track the order of items
                                    $counter = 0;
                                    $maxItemsToShow = 10; // Number of items to show in the main container

                                    // Check if $overviewItems is an array
                                    if (is_array($overviewItems)) {
                                        // Loop through your overview content list
                                        foreach ($overviewItems as $contentList) {
                                            // Ensure that the necessary keys exist
                                            if (!isset($contentList['content']) || !isset($contentList['bgClass']) || !isset($contentList['iconClass'])) {
                                                continue; // Skip if any of the required fields are missing
                                            }

                                            // Stop after 10 items across all categories (breaking both loops)
                                            if ($counter >= $maxItemsToShow) {
                                                break; // Exit the loop when the maximum number of items is reached
                                            }

                                            // Ensure content is a string (in case it's an array)
                                            $content = is_array($contentList['content']) ? implode(' ', $contentList['content']) : $contentList['content'];
                                            $route = ($contentList['route']); // Make sure route is properly escaped

                                    ?>
                                            <a href="<?php echo $route; ?>" class="scroll-item <?php echo ($contentList['bgClass']); ?> pt-3 ps-3 pe-3">
                                                <div class="row m-0">
                                                    <div class="col-6 p-0">
                                                        <i class=" <?php echo ($contentList['iconClass']); ?> <?php echo ($contentList['bgClass']); ?>-icon"></i>
                                                    </div>
                                                    <div class="col-6 p-0 text-end">
                                                        <i class="fa-solid fa-xmark closeIconFav"></i>
                                                    </div>
                                                </div>
                                                <p><?php echo ($content); ?></p>
                                            </a>
                                    <?php
                                            // Increment counter for the next item
                                            $counter++;
                                        }
                                    } else {
                                        // Handle case if $overviewItems is not an array (error or unexpected data)
                                        echo 'No overview items available.';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row m-0 mb-3">
                                <div class="col-md-4">
                                    <div class="card-stat">
                                        <div class="background-stat">
                                            <p>Total Employees</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                {{ $activeEmployeesCount ?? 0 }}
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat">
                                            <span class="icon-stat">
                                                <p></p>
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
                                            <p>New Employee</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                {{ $newEmployees ?? 0 }}
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat">
                                            <span class="icon-stat">
                                                <p></p>
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
                                            <p>Resignation Request(s)</p>
                                        </div>
                                        <div class="logo-stat">
                                            <p class="logo-svg-stat">
                                                {{ $hrRequestsCount ?? 0 }}
                                            </p>
                                        </div>
                                        <div class="box-stat box2-stat">
                                            <span class="icon-stat">
                                                <p></p>
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

                        </div>

                        <div class="col-md-4 sec-3">
                            <div class="m-0 mb-3 row text-center" style="border-radius: 10px; border: 1px solid #ccc;">
                                <div class="row m-0 p-0 mb-3" style="border-radius: 10px;">
                                    <img class="p-0" style="width: 100%; border-radius: 10px;" src="{{ asset('/images/hr_image.jpg') }}" />
                                </div>
                                <h4>Hello {{ $loginEmployee->emp->first_name }} {{ $loginEmployee->emp->last_name }} </h4>
                                <p class="fs12">Let our product experts help you get started and resolve any of your product-related problems.</p>
                                <div class="m-0 mb-3">
                                    <button class="submit-btn" href="#">Review it</button>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row m-0 mt-3">

                        <div class="col-md-6 ps-0 empTab">
                            <div class="border mb-3 rounded row m-0">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-12">
                                        <p class="fw-bold mb-0">Top 5 Leave Takers for All Leave Types</p>
                                        <p class="fs12">01 Nov 2024 to 28 Feb 2025</p>
                                    </div>
                                </div>
                                <div class="table-responsive p-0">
                                    <table class="table fs12 ">
                                        <thead class="table-sec">
                                            <tr>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Designation</th>
                                                <th scope="col">Total Leaves</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($mappedLeaveData)
                                            @foreach ($mappedLeaveData as $key => $value)
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @if($value['image'] && $value['image'] !=='null')
                                                        <div class="employee-profile-img">
                                                            <img class="rounded-circle" height="35" width="35" src="data:image/jpeg;base64,{{($value['image'])}} ">
                                                        </div>
                                                        @else
                                                        <div class="employee-profile-img">
                                                            <img src="{{ asset('images/user.jpg') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                                        </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <span class="normalTextTruncated">
                                                                {{ ucwords(strtolower($value['first_name'])) }} {{ ucwords(strtolower($value['last_name'])) }}
                                                            </span>
                                                            <span class="smallText">{{ $key }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $value['job_role'] }}</td>
                                                <td>
                                                    <span class="text-danger fw-bold">{{ $value['totalLeave'] }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr colspan="4">
                                                <td>No data found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="border m-0 rounded row mb-3 empTab">
                                <div class="m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employees</p>
                                    </div>
                                    <div class="col-md-6 text-end mb-3">
                                        <button class="btn btn-outline-primary btn-sm">View All</button>
                                    </div>
                                </div>

                                <div class="row m-0 py-2 bg-light">
                                    <div class="col-md-6">
                                        <p class="mb-0 fw-bold fs14">Name</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p class="mb-0 fw-bold fs14">Department</p>
                                    </div>
                                </div>

                                <div class="row m-0 mt-3">

                                    @if($dataEmp)
                                    @foreach ($dataEmp as $employee)
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div style="display: flex; align-items: center;">
                                            @if($employee->image && $employee->image !=='null')
                                            <div class="employee-profile-img">
                                                <img class="rounded-circle" height="35" width="35" src="data:image/jpeg;base64,{{($employee->image)}} ">
                                            </div>
                                            @else
                                            @if($employee->gender=='FEMALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/female-default.jpg') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @elseif($employee->gender=='MALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/male-default.png') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @else
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/user.jpg') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @endif
                                            @endif
                                            <div class="d-flex flex-column">
                                                <p class="normalTextTruncated" title="{{ ucwords(strtolower( $employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}">{{ ucwords(strtolower( $employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}</p>
                                                <p style="margin: 0; font-size: 12px; color: #666;">
                                                    {{ preg_replace('/\bii\b/i', 'II', ucwords(strtolower($employee->job_role))) }}
                                                </p>

                                            </div>
                                            <span class="deptDetails">
                                                {{ ucwords(strtolower($employee->empDepartment->department)) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div>
                                        <span>No data</span>
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6 pe-0 empTab2">
                            <div class="border m-0 rounded row">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Clock-In/Out</p>
                                    </div>
                                    <div class="col-md-6 text-end mb-3">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>

                                <div class="row m-0 mt-3">
                                    @if($swipes)
                                    @foreach ($swipes as $swipe)
                                    <div class="m-0 mb-3 p-2 row border">
                                        <div style="display: flex; align-items: center;">
                                            @if($swipe->employee->image && $swipe->employee->image !=='null')
                                            <div class="employee-profile-img">
                                                <img class="rounded-circle" height="35" width="35" src="data:image/jpeg;base64,{{($swipe->employee->image)}} ">
                                            </div>
                                            @else
                                            @if($swipe->employee->gender=='FEMALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/female-default.jpg') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @elseif($swipe->employee->gender=='MALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/male-default.png') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @else
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/user.jpg') }}" class=" rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @endif
                                            @endif
                                            <div class="d-flex flex-column col">
                                                <p class="mb-0 normalTextTruncated">{{ ucwords(strtolower($swipe->employee->first_name)) }} {{ ucwords(strtolower($swipe->employee->last_name)) }}</p>
                                                <p class="smallText mb-0">{{ ucwords(strtolower($swipe->employee->job_role)) }}</p>
                                            </div>
                                            <div class="col d-flex justify-content-center align-items-center">
                                                <span class="normalText"> {{ $swipe->in_or_out }} </span>
                                            </div>
                                            <div class="col d-flex justify-content-center align-items-center">
                                                <span class="badge col" >{{ \Carbon\Carbon::parse($swipe->swipe_time)->format('H:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="fw-bold fs14 p-0">No Data Found</p>
                                    @endif
                                    <p class="fw-bold fs14 p-0">Late</p>
                                    <div class="m-0 mb-3 p-2 row border">
                                        <div style="display: flex; align-items: center;">
                                            <img src="https://smarthr.dreamstechnologies.com/react/template/assets/img/profiles/avatar-24.jpg"
                                                style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;" />
                                            <div style="display: flex; flex-direction: column;">
                                                <p style="font-weight: bold; margin: 0; font-size: 14px;">Daniel Esbella</p>
                                                <p style="margin: 0; font-size: 12px; color: #666;">UI/UX Designer</p>
                                            </div>
                                            <i class="fa-regular fa-clock me-2" style="vertical-align: middle; margin-left: auto"></i>
                                            <span class="badge text-bg-danger" style="margin-left: auto;">09 : 06</span>
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
                            <div class="border m-0 rounded row mb-3">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employees By Department</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>
                                <div id="employeeByDep"></div>
                            </div>
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
                        <div class="col-md-6">
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
                            <div class="border m-0 rounded row empStatus">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employee Status</p>
                                    </div>
                                    <div class="col-md-6 text-end mb-3">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-calendar"></i> This Week</button>
                                    </div>
                                </div>

                                <div class="m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p>Total Employee</p>
                                    </div>
                                    <div class="col-md-6 text-end">
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
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </div>



    <!-- Modal -->
    @if($showDynamicContent)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Search</h6>
                    <button type="button" class="btn-close" wire:click="toggleContent" style="cursor: pointer;"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" wire:model.live="searchContent" placeholder="Search here">
                    </div>
                    <div class="row m-0 mb-3">
                        <p class="mt-1x">
                            <span class="chipTextFav {{ $categoryFilter === '' ? 'active' : '' }}" wire:click="setCategory('')">All</span>
                            <span class="chipTextFav {{ $categoryFilter === 'favorites' ? 'active' : '' }}" wire:click="setCategory('favorites')">My Favourites</span>
                            <span class="chipTextFav {{ $categoryFilter === 'employee' ? 'active' : '' }}" wire:click="setCategory('employee')">Employee</span>
                            <span class="chipTextFav {{ $categoryFilter === 'payroll' ? 'active' : '' }}" wire:click="setCategory('payroll')">Payroll</span>
                            <span class="chipTextFav {{ $categoryFilter === 'leave' ? 'active' : '' }}" wire:click="setCategory('leave')">Leave</span>
                            <span class="chipTextFav {{ $categoryFilter === 'attendance' ? 'active' : '' }}" wire:click="setCategory('attendance')">Attendance</span>
                            <span class="chipTextFav {{ $categoryFilter === 'other' ? 'active' : '' }}" wire:click="setCategory('other')">Other</span>
                        </p>

                    </div>

                    <div class="row m-0" style="max-height:350px;overflow-y:auto;">
                        <?php
                        foreach ($this->overviewItems as $item) {
                        ?>
                            <div class="col-md-3 homeContainers d-flex flex-column">
                                <div class="<?php echo $item['bgClass']; ?> pt-3 ps-3 pe-3 rounded-3 mb-3 flex-grow-1">
                                    <div class="row m-0">
                                        <div class="col-6 p-0">
                                            <!-- Display the dynamic icon for each item -->
                                            <i class="<?php echo $item['iconClass']; ?> <?php echo $item['bgClass']; ?>-icon"></i>
                                        </div>
                                        <div class="col-6 p-0 text-end" wire:click="getModuleName('{{ $item['content'] }}', '{{ $item['category'] }}')">
                                            <i class="{{ $item['isStarred'] ? 'fa-solid active' : 'fa-regular' }} fa-star" style="cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <p class="text-truncate" title="<?php echo htmlspecialchars(is_array($item['content']) ? implode(' ', $item['content']) : $item['content']); ?>">
                                        <?php echo htmlspecialchars(is_array($item['content']) ? implode(' ', $item['content']) : $item['content']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" wire:click="toggleContent">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    <!-- end: MAIN BODY -->

</section>
<!-- end: MAIN -->