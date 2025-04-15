<!-- start: MAIN -->
<section>
    <!-- start: MAIN BODY -->
    <div>
        <section class="tab-section">
            <div class="container-fluid">
                <div class="tab-pane">
                    <button type="button" data-tab-pane="active" class="tab-pane-item {{ $activeTab === 'active' ? 'active' : '' }}" wire:click="setActiveTab('active')">
                        <span class="tab-pane-item-title">01</span>
                        <span class="tab-pane-item-subtitle">Active</span>
                    </button>
                    <button type="button" data-tab-pane="in-review" class="tab-pane-item {{ $activeTab === 'in-review' ? 'active' : '' }}" wire:click="setActiveTab('in-review')">
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
                <div class="tab-page {{ $activeTab === 'active' ? 'active' : '' }}" data-tab-page="active">

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
                                    <div wire:click="toggleContent" class="scroll-item blue-bg"
                                        style="width: 2em; text-align: center;">
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
                                            <a href="<?php echo $route; ?>"
                                                class="scroll-item <?php echo $contentList['bgClass']; ?> pt-3 ps-3 pe-3">
                                                <div class="row m-0">
                                                    <div class="col-6 p-0">
                                                        <i class=" <?php echo $contentList['iconClass']; ?> <?php echo $contentList['bgClass']; ?>-icon"></i>
                                                    </div>
                                                    <div class="col-6 p-0 text-end">
                                                        <i class="fa-solid fa-xmark closeIconFav"></i>
                                                    </div>
                                                </div>
                                                <p><?php echo $content; ?></p>
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
                                <a href="{{ route('emp-resign-requests') }}" style="text-decoration: none; color: inherit;">
                                    <div class="card-stat" style="cursor: pointer;">
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
                                            <span class="icon-stat"></span>
                                        </div>
                                        <div class="box-stat box4-stat"></div>
                                    </div>
                                </a>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-4 sec-3">
                            <div class="m-0 mb-3 row text-center" style="border-radius: 10px; border: 1px solid #ccc;">
                                <div class="row m-0 p-0 mb-3" style="border-radius: 10px;">
                                    <img class="p-0" style="width: 100%; border-radius: 10px;"
                                        src="{{ asset('/images/hr_image.jpg') }}" />
                                </div>
                                <h4>Hello {{ $loginEmployee->emp->first_name }} {{ $loginEmployee->emp->last_name }}
                                </h4>
                                <p class="fs12">Let our product experts help you get started and resolve any of your
                                    product-related problems.</p>
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
                                        <div class="d-flex align-items-center justify-content-between mb-3 ">
                                            <p class="fw-bold mb-0">Top 5 Leave Takers</p>
                                            <select id="dateRange" class="dropdown" wire:model="dateRange" wire:click="getTopLeaveTakers">
                                                <option value="thisMonth">This Month</option>
                                                <option value="lastMonth">Last Month</option>
                                                <option value="thisYear">This Year</option>
                                            </select>
                                        </div>
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
                                            @if ($mappedLeaveData)

                                            @foreach ($mappedLeaveData as $key => $value)
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @if ($value['image'] && $value['image'] !== 'null')
                                                        <div class="employee-profile-img">
                                                            <img class="rounded-circle" height="35"
                                                                width="35"
                                                                src="data:image/jpeg;base64,{{ $value['image'] }} ">
                                                        </div>
                                                        @else
                                                        <div class="employee-profile-img">
                                                            <img src="{{ asset('images/user.jpg') }}"
                                                                class=" rounded-circle" height="35"
                                                                width="35" alt="Default Image">
                                                        </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <span class="normalTextTruncated">
                                                                {{ ucwords(strtolower($value['first_name'])) }}
                                                                {{ ucwords(strtolower($value['last_name'])) }}
                                                            </span>
                                                            <span class="smallText">{{ $key }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $value['job_role'] }}</td>
                                                <td>
                                                    <span
                                                        class="text-danger fw-bold">{{ $value['totalLeave'] }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="4">No data found</td>
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
                                        <button class="cancel-btn"><a href="/hr/update-employee-details" style="color:#306cc6;">View All</a></button>
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

                                    @if ($dataEmp)
                                    @foreach ($dataEmp as $employee)
                                    <div class="m-0 mb-3 p-2 row border-bottom">
                                        <div style="display: flex; align-items: center;">
                                            @if ($employee->image && $employee->image !== 'null')
                                            <div class="employee-profile-img">
                                                <img class="rounded-circle" height="35" width="35"
                                                    src="data:image/jpeg;base64,{{ $employee->image }} ">
                                            </div>
                                            @else
                                            @if ($employee->gender == 'FEMALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/female-default.jpg') }}"
                                                    class=" rounded-circle" height="35"
                                                    width="35" alt="Default Image">
                                            </div>
                                            @elseif($employee->gender == 'MALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/male-default.png') }}"
                                                    class=" rounded-circle" height="35"
                                                    width="35" alt="Default Image">
                                            </div>
                                            @else
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/user.jpg') }}"
                                                    class=" rounded-circle" height="35"
                                                    width="35" alt="Default Image">
                                            </div>
                                            @endif
                                            @endif
                                            <div class="d-flex flex-column">
                                                <p class="normalTextTruncated"
                                                    title="{{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}">
                                                    {{ ucwords(strtolower($employee->first_name)) }}
                                                    {{ ucwords(strtolower($employee->last_name)) }}
                                                </p>
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
                                        <span class="fw-normal fs12 p-0">No data</span>
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
                                        <div class="form-group">
                                            <input type="date" class="form-control" wire:model="signInTime" wire:change="getSignInOutData"  max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row m-0 mt-3">
                                    @if($earlyOrOnTimeEmployees && $earlyOrOnTimeEmployees[0]->employee)
                                    <p class="fw-bold fs14 p-0">Early/On Time</p>
                                    @foreach (array_slice($earlyOrOnTimeEmployees, 0, 5) as $swipe)
                                    @if($swipe->employee)
                                    <div class="m-0 mb-3 p-2 row border">
                                        <div style="display: flex; align-items: center;">
                                            @if($swipe->employee->image && $swipe->employee->image !== 'null')
                                            <div class="employee-profile-img">
                                                <img class="rounded-circle" height="35" width="35" src="data:image/jpeg;base64,{{ $swipe->employee->image }}">
                                            </div>
                                            @else
                                            @if($swipe->employee->gender == 'FEMALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/female-default.jpg') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @elseif($swipe->employee->gender == 'MALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/male-default.png') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @else
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/user.jpg') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @endif
                                            @endif
                                            <div class="d-flex flex-column col">
                                                <p class="mb-0 normalTextTruncated">{{ ucwords(strtolower($swipe->employee->first_name ?? 'N/A')) }} {{ ucwords(strtolower($swipe->employee->last_name ?? 'N/A')) }}</p>
                                                <p class="smallText mb-0">{{ ucwords(strtolower($swipe->employee->job_role ?? 'N/A')) }}</p>
                                            </div>
                                            <div class="col d-flex justify-content-center align-items-center">
                                                <span class="normalText">{{ $swipe->in_or_out }}</span>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <span class="badge text-bg-success">{{ \Carbon\Carbon::parse($swipe->swipe_time)->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    <p class="fw-normal fs12 p-0">No Early/On Time Employees Found</p>
                                    @endif
                                    @if(count($lateEmployees) > 0 && isset($lateEmployees[0]->employee))
                                    <p class="fw-bold fs14 p-0">Late</p>
                                    @foreach(array_slice($lateEmployees, 0, 5) as $lateSwipe)
                                    @if($lateSwipe->employee)
                                    <div class="m-0 mb-3 p-2 row border">
                                        <div style="display: flex; align-items: center;">
                                            @if($lateSwipe->image && $lateSwipe->image !== 'null')
                                            <div class="employee-profile-img">
                                                <img class="rounded-circle" height="35" width="35" src="data:image/jpeg;base64,{{ $lateSwipe->employee->image }}">
                                            </div>
                                            @else
                                            @if($lateSwipe->employee->gender == 'FEMALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/female-default.jpg') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @elseif($lateSwipe->employee->gender == 'MALE')
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/male-default.png') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @else
                                            <div class="employee-profile-img">
                                                <img src="{{ asset('images/user.jpg') }}" class="rounded-circle" height="35" width="35" alt="Default Image">
                                            </div>
                                            @endif
                                            @endif
                                            <div class="d-flex flex-column col">
                                                <p class="mb-0 normalTextTruncated">{{ ucwords(strtolower($lateSwipe->employee->first_name ?? 'N/A')) }} {{ ucwords(strtolower($lateSwipe->employee->last_name ?? 'N/A')) }}</p>
                                                <p class="smallText mb-0">{{ ucwords(strtolower($lateSwipe->employee->job_role ?? 'N/A')) }}</p>
                                            </div>
                                            <div class="col d-flex justify-content-center align-items-center">
                                                <span class="normalText">{{ $lateSwipe->in_or_out }}</span>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <span class="badge text-bg-danger">{{ \Carbon\Carbon::parse($lateSwipe->swipe_time)->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    <p class="fw-normal fs12 p-0">No late employees today.</p>
                                    @endif
                                </div>


                                <div class="row m-0 my-3">
                                    <div class="d-grid gap-2 p-0">
                                        <a href="{{ route('employee-swipes-for-hr') }}" class="btn btn-outline-secondary btn-sm">
                                            View All Attendance
                                        </a>

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
                <div class="tab-page {{ $activeTab === 'in-review' ? 'active' : '' }}" data-tab-page="in-review">
                    <div class="row m-0 mb-3">
                        <div class="col-md-7">
                        <div class="border m-0 rounded row mb-3">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-9">
                                        <p class="fw-bold">Employees By Department</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-2 mt-2">
                                        <label for="departmentSelect">Select Departments:</label>
                                        <select class="dropdown" id="departmentSelect" wire:model="selectedDepartments" wire:change="filterDepartmentChart">
                                            <option value="">All Departments</option> {{-- Default option to show all --}}
                                            @foreach($allDepartments as $department)
                                                <option value="{{ $department->dept_id }}">{{ $department->department }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                <div >
                                    <canvas id="employeeChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                                  <div class="border m-0 rounded row mb-3 d-flex justify-content-center">
                                <div class="border-bottom m-0 mt-3 row ">
                                    <div class="col-md-12">
                                        <p class="fw-bold">Employee Gender Distribution</p>
                                    </div>
                                </div>
                                <div class="genderChart" >
                                    <canvas id="genderPieChart" width="400" height="400"></canvas>
                                    <div style="display:grid;grid-template-columns: repeat(2, 1fr);">
                                        <span class="normalText">Male : <strong>{{ $maleCount }}</strong> </span>
                                        <span class="normalText">Female : <strong>{{ $femaleCount }}</strong> </span>
                                        <span class="normalText">Other : <strong>{{ $otherCount }}</strong> </span>
                                        <span class="normalText">Not Available : <strong>{{ $notAvailableCount }}</strong> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-md-6 mb-3">


                            <div class="border m-0 rounded row d-flex justify-content-center">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-8">
                                        <p class="fw-bold">Attendance Overview</p>
                                    </div>
                                    <div class="col-md-4 text-end mb-3">
                                        <select id="attendance-range" class="form-select form-select-sm w-auto d-inline-block">
                                            <option value="this_week" selected>This Week</option>
                                            <option value="this_month">This Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="this_year">This Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="attendanceChart">
                                        <canvas id="attendanceDonutChart" style="width: 320px;height:320px;"></canvas>
                                        <div id="donutCenterText" style="
                                            position: absolute;
                                            top: 60%;
                                            left: 50%;
                                            transform: translate(-50%, -50%);
                                            font-weight: bold;
                                            font-size: 16px;
                                            text-align: center;
                                            pointer-events: none;
                                        ">

                                        </div>
                                    </div>

                                <div class="row m-0">
                                    <p class="mb-1 fw-bold">Status</p>
                                    <div class="col-6 pe-0">
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #008ffb"></i>
                                            Late</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #00e396"></i>
                                            OnTime/Early</p>
                                        <p class="mb-1"><i class="fa-solid fa-circle" style="color: #ff4560"></i>
                                            Absent</p>
                                    </div>
                                    <div class="col-6 ps-0 text-end">
                                        <p class="mb-1 fw-bold">{{ $late }}</p>
                                        <p class="mb-1 fw-bold">{{ $present }}</p>
                                        <p class="mb-1 fw-bold">{{ $absent }}</p>
                                    </div>
                                </div>
                                <div class="m-0 mb-3 mt-3 px-4 row">
                                    <div class="bg-light m-0 p-0 py-2 rounded row">
                                        <div class="col-md-8 mb-3 d-flex align-items-center">
                                            <p class="mb-0 me-2">Total Absenties</p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="/hr/user/who-is-in-chart-hr" class="perfColor"
                                                style="text-decoration: underline;">View Details</a>
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
                                    {{-- <div class="col-md-6 mb-3 text-end">
                                        <button class="btn btn-outline-primary btn-sm"><i
                                                class="fa-regular fa-calendar"></i> This Week</button>
                                    </div> --}}
                                    <div class="col-md-6 mb-3 text-end">
                                        <!-- Dropdown button -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-regular fa-calendar"></i> {{ $selectedOption }}
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item" href="#" wire:click="updateOption('This Week')">This Week</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click="updateOption('This Month')">This Month</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click="updateOption('Last Month')">Last Month</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click="updateOption('This Year')">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>


                                </div>
                                <div style="height: 200px;">
                                    @if (!empty($tasksData['series']) && array_sum($tasksData['series']) > 0)
                                    <div id="taskStatusChart" wire:ignore></div>
                                    @else
                                    <p>No data available for the chart.</p>
                                    @endif


                                </div>
                                <div class="row m-0 mt-3">
                                    <div class="col-md-4 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle"
                                                style="color: #306cc6"></i> Opened</p>
                                        <p class="fs14 mb-2 fw-bold">{{ $tasksData['series'][0] ?? 0 }}%</p>
                                    </div>
                                    <div class="col-md-4 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle"
                                                style="color: #00e396"></i> Closed</p>
                                        <p class="fs14 mb-2 fw-bold">{{ $tasksData['series'][1] ?? 0 }}%</p>
                                    </div>
                                    <div class="col-md-4 border-end text-center">
                                        <p class="fs14 mb-2"><i class="fa-solid fa-circle"
                                                style="color: #feb019"></i> Overdue</p>
                                        <p class="fs14 mb-2 fw-bold">{{ $tasksData['series'][2] ?? 0 }}%</p>
                                    </div>
                                </div>

                            </div>
                            <div class="border m-0 mt-4 rounded row empStatus">
                                <div class="border-bottom m-0 mt-3 row">
                                    <div class="col-md-6">
                                        <p class="fw-bold">Employee Status</p>
                                    </div>
                                </div>

                                <div class="m-0 mt-3 row">
                                    <div class="col-md-8">
                                        <p class="normalTextSubheading">Total Employee Count <strong style="font-size: 16px;">({{  $totalEmployees }})</strong></p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                    </div>
                                </div>

                                <div class="m-0 px-4 row">
                                        <div class="progress p-0" >
                                        @if (isset($employeeTypes))
                                            @foreach ($employeeTypes as $index => $emp)
                                                <div class="progress-bar" role="progressbar" title="{{ ucfirst($emp['type']) }} - {{ $emp['percentage'] }}%"
                                                    style="cursor:pointer;width: {{ $emp['percentage'] }}%; background-color: {{ $loop->index == 0 ? '#007bff' : ($loop->index == 1 ? '#2e8b57' : ($loop->index == 2 ? '#00d1ff' : '#d3d3d3')) }};"
                                                    aria-valuenow="{{ $emp['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            @endforeach
                                            @else
                                            <p>no data</p>
                                            @endif
                                        </div>

                                        <div class="row text-center mt-3 mb-3">
                                            @foreach ($employeeTypes as $emp)
                                                <div class="col-md-4 border py-3 d-flex flex-column align-items-center">
                                                    <div class="normalTextSubheading">{{ ucfirst($emp['type']) }} </div>
                                                    <span>({{ $emp['percentage'] }}%)</span>
                                                    <h2 >{{ $emp['count'] }}</h2>
                                                </div>
                                            @endforeach
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
    @if ($showDynamicContent)
    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Search</h6>
                    <button type="button" class="btn-close" wire:click="toggleContent"
                        style="cursor: pointer;"></button>
                </div>
                <div class="modal-body">
                    <span wire:click="setAction('delete')">check</span>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" wire:model.live="searchContent"
                            placeholder="Search here">
                    </div>
                    <div class="row m-0 mb-3">
                        <p class="mt-1x">
                            <span class="chipTextFav {{ $categoryFilter === '' ? 'active' : '' }}"
                                wire:click="setCategory('')">All</span>
                            <span class="chipTextFav {{ $categoryFilter === 'favorites' ? 'active' : '' }}"
                                wire:click="setCategory('favorites')">My Favourites</span>
                            <span class="chipTextFav {{ $categoryFilter === 'employee' ? 'active' : '' }}"
                                wire:click="setCategory('employee')">Employee</span>
                            <span class="chipTextFav {{ $categoryFilter === 'payroll' ? 'active' : '' }}"
                                wire:click="setCategory('payroll')">Payroll</span>
                            <span class="chipTextFav {{ $categoryFilter === 'leave' ? 'active' : '' }}"
                                wire:click="setCategory('leave')">Leave</span>
                            <span class="chipTextFav {{ $categoryFilter === 'attendance' ? 'active' : '' }}"
                                wire:click="setCategory('attendance')">Attendance</span>
                            <span class="chipTextFav {{ $categoryFilter === 'other' ? 'active' : '' }}"
                                wire:click="setCategory('other')">Other</span>
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
                                        <div class="col-6 p-0 text-end"
                                            wire:click="getModuleName('{{ $item['content'] }}', '{{ $item['category'] }}')">
                                            <i class="{{ $item['isStarred'] ? 'fa-solid active' : 'fa-regular' }} fa-star"
                                                style="cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <a href="{{ url($item['route']) }}" style="text-decoration: none;" class="text-truncate">
                                        <p class="text-truncate" title="<?php echo htmlspecialchars(is_array($item['content']) ? implode(' ', $item['content']) : $item['content']); ?>">
                                            <?php echo htmlspecialchars(is_array($item['content']) ? implode(' ', $item['content']) : $item['content']); ?>
                                        </p>
                                    </a>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log(@json($tasksData));
            setTimeout(() => {
                if (document.querySelector("#taskStatusChart")) {
                    var options = {
                        series: @json($tasksData['series']),
                        chart: {
                            type: 'donut',
                            height: 350
                        },
                        labels: @json($tasksData['labels']),
                        plotOptions: {
                            pie: {
                                startAngle: -90, // Start from the top
                                endAngle: 90, // Stop at the bottom
                                offsetY: 10,
                                customScale: 0.9,
                                borderRadius: 10,
                                 spacing: 5,
                                donut: {
                                    size: "65%", // Adjust thickness
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: "Total Tasks",
                                            fontSize: "16px",
                                            fontWeight: "bold",
                                            offsetY: -20,
                                            color: "#000",
                                            formatter: function() {
                                                return "{{ $tasksData['totalTasks'] ?? 0 }}";
                                            }
                                        }
                                    }
                                }
                            }
                        },

                        tooltip: {
                            enabled: true
                        },
                        stroke: {
                            width: 10, // Increase this for a stronger border effect // White border effect
                            lineCap: "round"
                        },
                        legend: {
                            show: false
                        },
                        colors: ["#008ffb", "#00e396", "#feb019"], // Custom colors
                    };

                    window.chart = new ApexCharts(document.querySelector("#taskStatusChart"), options);
                    window.chart.render();
                }
            }, 500);
        });

        // Livewire Hook to Refresh Chart
        document.addEventListener("livewire:load", function() {
            Livewire.hook("message.processed", (message, component) => {
                if (document.querySelector("#taskStatusChart") && window.chart) {
                    window.chart.updateSeries(@json($tasksData['series']));
                    console.log(@json($tasksData));
                }
            });
        });
    </script>
    <!-- end: MAIN BODY -->

</section>
<!-- end: MAIN -->

<!-- //employee by department -->
<script>
    const ctx = document.getElementById('employeeChart').getContext('2d');

    const labels = @json($departmentChartData->keys());
    const data = @json($departmentChartData->values());

    const employeeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Employees per Department',
                data: data,
                backgroundColor: '#29b6f6',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    barPercentage: 0.8, // You can experiment with this value to get a good look
                    categoryPercentage: 0.5 // This affects how wide the bar is relative to the available space
                },
                y: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
    window.addEventListener('admin-dashboard', event => {
    const chartData = event.detail.data;

    employeeChart.data.labels = Object.keys(chartData);
    employeeChart.data.datasets[0].data = Object.values(chartData);
    employeeChart.update();
});


</script>


<!-- //gender distribution -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script loaded");

    const genderCtx = document.getElementById('genderPieChart');

    if (!genderCtx) {
        console.error("genderPieChart element not found in DOM.");
        return;
    }

    const chart = new Chart(genderCtx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Gender Distribution',
                data: @json($data),
                backgroundColor: @json($backgroundColors),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Employee Gender Distribution'
                }
            }
        }
    });

});
</script>

<!-- //attendnace overview -->

<script>
    const donutCtx = document.getElementById('attendanceDonutChart').getContext('2d');
    const centerText = document.getElementById('donutCenterText');
    const attendanceData = {
                present: @json($present),
                late: @json($late),
                absent: @json($absent)
            };

    const present = attendanceData.present;
    const late = attendanceData.late;
    const absent = attendanceData.absent;
    const attendanceDonutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['OnTime/Early', 'Absent', 'Late'],
            datasets: [{
                data: [present, absent, late],
                backgroundColor: [
                    '#4caf50', '#f44336', '#2196f3'
                ],
                borderRadius: 10,
                spacing: 5
            }]
        },
        options: {
            responsive: true,
            cutout: '74%',
            rotation: -90,
            circumference: 180,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    external: function(context) {
                        const tooltipModel = context.tooltip;
                        const data = context.chart.data.datasets[0].data;
                        const labels = context.chart.data.labels;
                        const total = data.reduce((a, b) => a + b, 0);

                        if (tooltipModel.opacity === 0) {
                            centerText.innerHTML = '100%';
                            return;
                        }

                        if (tooltipModel.dataPoints) {
                            const dp = tooltipModel.dataPoints[0];
                            const total = data.reduce((a, b) => a + b, 0);
                            const value = dp.raw;
                            const label = labels[dp.dataIndex];
                            const percentage = ((value / total) * 100).toFixed(1);
                            centerText.innerHTML = `${label}: ${percentage}%`;
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: (value, context) => {
                        const data = context.chart.data.datasets[0].data;
                        const total = data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        const label = labels[context.dataIndex];
                        return `${percentage}%`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>





