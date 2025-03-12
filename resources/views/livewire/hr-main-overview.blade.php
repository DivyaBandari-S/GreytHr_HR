<div >
<div class="p-2 m-0 main-overview-container">

    <div>a
        @if ($showHelp == false)
            <div class="row main-overview-help">
                <div class="col-md-11 col-10 d-flex flex-column">
                    <p class="main-overview-text">The page guides you through an overview of your organization's
                        day-to-day HR
                        activities including lifecycle events like additions, seperations, etc</p>
                    <p class="main-overview-text">Explore greytHR by <span class="main-overview-highlited-text">
                            Help-Doc</span>, watching<span class="main-overview-highlited-text"> How-to Videos</span>
                        and<span class="main-overview-highlited-text"> FAQ</span>.</p>
                </div>
                <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                    <span wire:click="hideHelp">Hide Help</span>
                </div>
            </div>
        @else
            <div class="row main-overview-help">
                <div class="col-11 d-flex flex-column">
                    <p class="main-overview-text">The page guides you through an overview of your organization's
                        day-to-day
                        HR activitwies including lifecycle events like additions, seperations, etc</p>

                </div>
                <div class="hide-main-overview-help col-1">
                    <span wire:click="showhelp">Show Help</span>
                </div>
            </div>
        @endif
    </div>

    <div class="w-100 p-3 main-overview-graphs-container">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-7 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 class="main-overview-heading-text">Employees Head Count
                        </h2>
                        <canvas id="employeeChart" height="200" width="400"></canvas>
                    </div>
                </div>
                <!-- Swipe Records of Employees (Second Column) -->
                <div class="col-md-5 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 class="main-overview-heading-text">Employees Login
                            Stats of 2 Months</h2>
                        <h2 class="main-overview-subheading-text"><span
                                class="main-overview-red-text">{{ $data['percentageChangeText'] }}</span> vs Previous
                            month</h2>
                        <canvas id="swipeRecordChart" height="200"></canvas>
                    </div>
                </div>

            </div>


            <div class="row pt-3">

                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white main-overview-requests-container">
                        <h2 class="main-overview-count-text">
                            {{ $hrRequestSolvedCount }}</h2>
                        <h2 class="main-overview-heading-text">HelpDesk Stats
                            of 3 Months</h2>
                        <h2 class="main-overview-subheading-text"><span
                                class="main-overview-green-text">{{ $percentageChangeText }}</span> vs Previous month
                        </h2>
                        <div class="row p-0 main-overview-count-container">
                            <div class="col-md-5 d-flex flex-column main-overview-custom-border ">
                                <h1 class="main-overview-tickets-count">
                                    {{ $hrRequestCount }}</h1>
                                <p class="main-overview-text">Ticket Raised</p>
                            </div>
                            <div class="col-md-5 d-flex flex-column main-overview-custom-solved-border">
                                <h1 class="main-overview-tickets-count">
                                    {{ $hrRequestSolvedCount }}</h1>
                                <p class="main-overview-text">Ticket Solved</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 class="main-overview-count-text">
                            4</h2>
                        <h2 class="main-overview-heading-text">Letters
                            Generated Stats of 3 Months</h2>
                        <h2 class="main-overview-subheading-text"><span class="main-overview-red-text">0%</span> vs Previous month</h2>
                        <canvas id="swipeRecordChart"></canvas>
                    </div>
                </div> --}}
                <div class="col-md-4 col-12 mb-3">
                    <div class="border rounded p-3 bg-white">
                        <h2 class="main-overview-count-text">
                            {{ $mobileUsersCount }}</h2>
                        <h2 class="main-overview-heading-text">Mobile App
                            Users</h2>
                        <h2 class="main-overview-subheading-text"><span class="main-overview-red-text">-25%</span> vs
                            Previous month</h2>
                        <div class="d-flex align-items-center main-overview-list-div-container">
                            <!-- Adjust margin or padding as needed -->
                            <div class="main-overview-chart-container">
                                <canvas id="MobileUsers" wire:ignore></canvas>
                            </div>

                            <div>
                                <p class="p-0 m-0 main-overview-mobile-users-count">
                                    {{ $mobileUsersCount }} out of {{ $allEmpCount }} </p>
                                <span class="main-overview-subheading-text">employees
                                    are using the ESS mobile app.</span>
                                <a href="#" class="btn btn-link p-0 main-overview-invite-text">Invite
                                    More</a>
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
                    <div class="border rounded  bg-white h-100">
                        <div class="d-flex justify-content-between p-2">
                            <h2 class="main-overview-title">New Joiners for Last 1 Month</h2>
                            <span class="main-overview-add">Add</span>
                        </div>
                        <hr class="main-overview-hr-line">

                        @if ($newJoiners->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center p-2">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    class="main-overview-no-records-img">
                                <p class="text-muted main-overview-no-records-text">No Joins in Last Month</p>
                            </div>
                        @else
                            @php
                                $shouldScroll = $newJoiners->count() > 2;
                            @endphp
                            <div class="{{ $shouldScroll ? 'main-overview-scroll-container' : '' }}">
                                <ul class="d-felx flex-column">
                                    @foreach ($newJoiners as $joiner)
                                        <li class="mb-2 row p-2 main-overview-list-overview-container">
                                            <div class="d-flex col-8 main-overview-list-div-container">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>

                                                    @if ($joiner->image !== null && $joiner->image != 'null' && $joiner->image != 'Null' && $joiner->image != '')
                                                        <!-- It's binary, convert to base64 -->
                                                        <img src="data:image/jpeg;base64,{{ $joiner->image }}"
                                                            alt="base" class="main-overview-profile-image" />
                                                    @else
                                                        @if ($joiner && $joiner->gender == 'Male')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($joiner && $joiner->gender == 'Female')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="main-overview-text-truncate main-overview-heading-text"
                                                        title="{{ ucwords(strtolower($joiner->first_name)) }} {{ ucwords(strtolower($joiner->last_name)) }}">
                                                        {{ ucwords(strtolower($joiner->first_name)) }}
                                                        {{ ucwords(strtolower($joiner->last_name)) }}</span>
                                                    <span
                                                        class="main-overview-heading-text">{{ $joiner->emp_id }}</span>
                                                </div>

                                            </div>
                                            <div class="col-4 d-flex justify-content-end">

                                                <span class="main-overview-subheading-text">
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
                    <div class="border rounded  bg-white h-100">
                        <div class="p-2">
                            <h2 class="main-overview-title">Upcoming Birthdays for a week</h2>

                        </div>
                        <hr class="main-overview-hr-line">

                        @if ($employeesBirthdays->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    class="main-overview-no-records-img">
                                <p class="text-muted main-overview-no-records-text">No birthday reminders to show.</p>
                            </div>
                        @else
                            @php
                                $shouldScroll = $employeesBirthdays->count() > 2;
                            @endphp
                            <div class="{{ $shouldScroll ? 'main-overview-scroll-container' : '' }}">
                                <ul class="d-felx flex-column">
                                    @foreach ($employeesBirthdays as $birthday)
                                        <li class="mb-2 row p-2 main-overview-list-overview-container">
                                            <div class="d-flex col-8 main-overview-list-div-container">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>
                                                    @if ($birthday->image !== null && $birthday->image != 'null' && $birthday->image != 'Null' && $birthday->image != '')
                                                        <!-- It's binary, convert to base64 -->
                                                        <img src="data:image/jpeg;base64,{{ $birthday->image }}"
                                                            alt="base" class="main-overview-profile-image" />
                                                    @else
                                                        @if ($birthday && $birthday->gender == 'Male')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($birthday && $birthday->gender == 'Female')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="main-overview-text-truncate main-overview-heading-text"
                                                        title="{{ ucwords(strtolower($birthday->first_name)) }} {{ ucwords(strtolower($birthday->last_name)) }}">
                                                        {{ ucwords(strtolower($birthday->first_name)) }}
                                                        {{ ucwords(strtolower($birthday->last_name)) }}</span>
                                                    <span
                                                        class="main-overview-heading-text">{{ $birthday->emp_id }}</span>
                                                    <span class="main-overview-subheading-text">
                                                        {{ \Carbon\Carbon::parse($birthday->date_of_birth)->format('M, d') }}</span>
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
                    <div class="border rounded  bg-white h-100">
                        <div class="p-2">
                            <h2 class="main-overview-title">Confirmation due for next 1 month</h2>
                        </div>

                        <hr class="main-overview-hr-line">
                        @if ($confirmationDue->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    class="main-overview-no-records-img">
                                <p class="text-muted main-overview-no-records-text">No Confirmation This Month</p>
                            </div>
                        @else
                            @php
                                $shouldScroll = $confirmationDue->count() > 2;
                            @endphp
                            <div class="{{ $shouldScroll ? 'main-overview-scroll-container' : '' }}">
                                <ul class="d-felx flex-column">
                                    @foreach ($confirmationDue as $confirmation)
                                        <li class="mb-2 row p-2 main-overview-list-overview-container">
                                            <div class="d-flex col-8 main-overview-list-div-container">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>

                                                    @if (
                                                        $confirmation->image !== null &&
                                                            $confirmation->image != 'null' &&
                                                            $confirmation->image != 'Null' &&
                                                            $confirmation->image != '')
                                                        <!-- It's binary, convert to base64 -->
                                                        <img src="data:image/jpeg;base64,{{ $confirmation->image }}"
                                                            alt="base" class="main-overview-profile-image" />
                                                    @else
                                                        @if ($confirmation && $confirmation->gender == 'Male')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($confirmation && $confirmation->gender == 'Female')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="main-overview-text-truncate main-overview-heading-text"
                                                        title="{{ ucwords(strtolower($confirmation->first_name)) }} {{ ucwords(strtolower($confirmation->last_name)) }}">
                                                        {{ ucwords(strtolower($confirmation->first_name)) }}
                                                        {{ ucwords(strtolower($confirmation->last_name)) }}</span>
                                                    <span
                                                        class="main-overview-heading-text">{{ $confirmation->emp_id }}</span>
                                                </div>

                                            </div>
                                            <div class="col-4 d-flex justify-content-end">

                                                <span class="main-overview-subheading-text">
                                                    in
                                                    {{ \Carbon\Carbon::parse($confirmation->probation_end_date)->diffInDays(\Carbon\Carbon::now()) }}
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
                    <div class="border rounded bg-white h-100">
                        <div class="d-flex justify-content-between p-2">
                            <h2 class="main-overview-title">Resigned Employees for Last 1 Month</h2>
                            <span class="main-overview-add">Add</span>
                        </div>
                        <hr class="main-overview-hr-line">

                        @if ($inactiveEmployees->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('images/norecordstoshow.png') }}" alt="Image Description"
                                    class="main-overview-no-records-img">
                                <p class="text-muted main-overview-no-records-text">No employee resignation to show.
                                </p>
                            </div>
                        @else
                            @php
                                $shouldScroll = $inactiveEmployees->count() > 2;
                            @endphp
                            <div class="{{ $shouldScroll ? 'main-overview-scroll-container' : '' }}">
                                <ul class="d-felx flex-column">
                                    @foreach ($inactiveEmployees as $inactive)
                                        <li class="mb-2 row p-2 main-overview-list-overview-container">
                                            <div class="d-flex col-8 main-overview-list-div-container">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>
                                                    @if (
                                                        $inactive->image !== null &&
                                                            $inactive->image != 'null' &&
                                                            $inactive->image != 'Null' &&
                                                            $inactive->image != '')
                                                        <!-- It's binary, convert to base64 -->
                                                        <img src="data:image/jpeg;base64,{{ $inactive->image }}"
                                                            alt="base" class="main-overview-profile-image" />
                                                    @else
                                                        @if ($inactive && $inactive->gender == 'Male')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($inactive && $inactive->gender == 'Female')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="main-overview-text-truncate main-overview-heading-text"
                                                        title="{{ ucwords(strtolower($inactive->first_name)) }} {{ ucwords(strtolower($inactive->last_name)) }}">
                                                        {{ ucwords(strtolower($inactive->first_name)) }}
                                                        {{ ucwords(strtolower($inactive->last_name)) }}</span>
                                                    <span
                                                        class="main-overview-heading-text">{{ $inactive->emp_id }}</span>
                                                    <span class="main-overview-subheading-text">
                                                        {{ \Carbon\Carbon::parse($inactive->resignation_date)->format('d M, Y') }}
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
                    <div class="border rounded bg-white h-100">
                        <div class="p-2">
                            <h2 class="main-overview-title">Joining Anniversary for a week</h2>
                        </div>
                        <hr class="main-overview-hr-line">

                        @if ($employeesWithAnniversaries->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/norecordstoshow.png" alt="Image Description"
                                    class="main-overview-no-records-img">
                                <p class="text-muted main-overview-no-records-text">No Anniversaries for a week</p>
                            </div>
                        @else
                            @php
                                $shouldScroll = $employeesWithAnniversaries->count() > 2;
                            @endphp
                            <div class="{{ $shouldScroll ? 'main-overview-scroll-container' : '' }}">
                                <ul class="d-felx flex-column">
                                    @foreach ($employeesWithAnniversaries as $anniversary)
                                        <li class="mb-2 row p-2 main-overview-list-overview-container">
                                            <div class="d-flex col-8 main-overview-list-div-container">
                                                <!-- You can replace this placeholder with the actual profile image URL -->
                                                <div>
                                                    @if (
                                                        $anniversary->image !== null &&
                                                            $anniversary->image != 'null' &&
                                                            $anniversary->image != 'Null' &&
                                                            $anniversary->image != '')
                                                        <!-- It's binary, convert to base64 -->
                                                        <img src="data:image/jpeg;base64,{{ $anniversary->image }}"
                                                            alt="base" class="main-overview-profile-image" />
                                                    @else
                                                        @if ($anniversary && $anniversary->gender == 'Male')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/male-default.png') }}"
                                                                alt="Default Male Image">
                                                        @elseif($anniversary && $anniversary->gender == 'Female')
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/female-default.jpg') }}"
                                                                alt="Default Female Image">
                                                        @else
                                                            <img class="main-overview-profile-image"
                                                                src="{{ asset('images/user.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="main-overview-text-truncate main-overview-heading-text"
                                                        title="{{ ucwords(strtolower($anniversary->first_name)) }} {{ ucwords(strtolower($anniversary->last_name)) }}">
                                                        {{ ucwords(strtolower($anniversary->first_name)) }}
                                                        {{ ucwords(strtolower($anniversary->last_name)) }}</span>
                                                    <span
                                                        class="main-overview-heading-text">{{ $anniversary->emp_id }}</span>
                                                    <span class="main-overview-subheading-text">
                                                        {{ \Carbon\Carbon::parse($anniversary->hire_date)->format('d M, Y') }}
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
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var months = @json($months);
        var employeeCounts = @json($employeeCounts);
        var maxEmployeeCount = {{ $maxEmployeeCount }};

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
                labels: monthNames, // X-axis labels (month names)
                datasets: [{
                    label: 'Employee Count',
                    data: employeeCounts, // Y-axis data (employee counts)
                    borderColor: 'rgba(75, 192, 192, 1)', // Line color
                    borderWidth: 2, // Line thickness
                    fill: false // Do not fill the area under the line
                }]
            },
            options: {
                responsive: true, // Make chart responsive
                plugins: {
                    legend: {
                        display: false // Disable the legend
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true, // Start the y-axis at zero
                        title: {
                            display: true, // Y-axis title
                        },
                        min: 0,
                        max: maxEmployeeCount,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)', // Set the color of the grid lines
                            lineWidth: 0.5, // Set the width of the grid lines

                        },
                        ticks: {
                            // Set custom step size and labels
                            stepSize: 41, // Set the step size
                            callback: function(value) {
                                // Custom label formatting if needed
                                return value; // You can format this if needed
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
        var filteredCurrentMonthData = selectedDays.map(day => currentMonthData[day - 1] ||
            0); // Adjust for 0-based index
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
                            lineWidth: 0.5, // Set the width of the grid lines

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
        var allEmpCount = @json($allEmpCount);

        // Debug logs to check values
        console.log('Mobile Users Count:', mobileUsersCount);
        console.log('All Employees Count:', allEmpCount);


        var ctx = document.getElementById('MobileUsers').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Mobile Users', 'Other Users'],
                datasets: [{
                    data: [mobileUsersCount, allEmpCount -
                        mobileUsersCount
                    ], // Use actual counts
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
