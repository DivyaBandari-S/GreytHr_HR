<div class="p-2 m-2">
    <style>
        .nav-tabs .nav-link.active {
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
            color: var(--main-button-color);
        }

        .nav-tabs .nav-link {
            font-size: var(--sub-headings-font-size);
            color: black;
        }

        .nav-tabs .nav-link.folder-active {
            font-size: var(--sub-headings-font-size);
            color: var(--main-button-color);
        }

        .nav-link {
            border: 1px solid transparent;
        }

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 1rem;
        }

        .task-date-range-picker {
            display: flex;
            justify-content: flex-end;
        }

        .task-custom-select-width {
            width: 170px;
            font-size: var(--normal-font-size);
            padding: 7px;
        }

        .leave-overview-month {
            font-size: var(--normal-font-size);
            color: var(--sub-heading-color);
        }

        .leave-overview-year {
            font-size: var(--normal-font-size);
            color: var(--label-color);
        }

        .leave-overview-month-container {
            cursor: pointer;
            padding: 5px;
        }

        .leave-overview-month-container.selected {
            background-color: var(--main-button-color);

            /* Background color when selected */
            border-radius: 5px;
            padding: 5px;
            /* Optional border for selected */
        }

        .leave-overview-text-white {
            color: white;
        }

        .leave-overview-details-container {
            background: white;
            overflow: hidden;
        }

        .leave-overview-div {
            background-color: rgb(228, 238, 245);
        }

        .leave-overview-selected-date {
            font-size: var(--main-headings-font-size);
            color: var(--main-heading-color);
            font-weight: 500;
            padding: 10px 20px;
        }

        .leave-circle {
            width: 8px;
            /* Circle width */
            height: 8px;
            /* Circle height */
            border-radius: 50%;
            /* Make it circular */
            display: inline-block;
            margin-right: 10px;
            /* Space between circle and text */
            vertical-align: middle;
            /* Align circles with text */
        }

        .table-header {
            background-color: rgb(129, 125, 125);
        }
    </style>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'Main' ? 'active' : '' }}" wire:click="$set('activeTab', 'Main')"
                    id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all"
                    aria-selected="true">Main</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'Activity' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'Activity')" id="starred-tab" data-bs-toggle="tab" href="#starred"
                    role="tab" aria-controls="starred" aria-selected="false">Activity</a>
            </li>
            <li class="nav-item ms-auto">
                <a class="nav-link folder-active" href="#">Select</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show {{ $activeTab === 'Main' ? 'active' : '' }}" id="all" role="tabpanel"
            aria-labelledby="all-tab">
            <div>
                @if ($showHelp == false)
                    <div class="row main-overview-help">
                        <div class="col-md-11 col-10 d-flex flex-column">
                            <p class="main-overview-text">This page displays a summary of your employee's leave-related
                                activities-the pattern of leave usage in the
                                organization across the months and the type of leave availed by your employees.
                            </p>
                            <p class="main-overview-text">Explore greytHR by <span class="main-overview-highlited-text">
                                    Help-Doc</span>, watching<span class="main-overview-highlited-text"> How-to
                                    Videos</span>
                                and<span class="main-overview-highlited-text"> FAQ</span>.</p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                @else
                    <div class="row main-overview-help">
                        <div class="col-11 d-flex flex-column">
                            <p class="main-overview-text">This page displays a summary of your employee's leave-related
                                activities-the pattern of leave usage in the
                                organization across the months and the type of leave availed by your employees.</p>

                        </div>
                        <div class="hide-main-overview-help col-1">
                            <span wire:click="showhelp">Show Help</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group task-date-range-picker">
                <select class="form-select task-custom-select-width" wire:model.defer="filterPeriodValue"
                    wire:change="filterPeriodChanged">
                    <option value="this_year">Jan {{ date('Y') }} - Dec {{ date('Y') }}</option>
                    <option value="current_year">Jan {{ date('Y') - 1 }} - Dec {{ date('Y') - 1 }}</option>
                </select>

            </div>

            <div class="leave-overview-details-container mt-3">
                <div class="m-3">
                    @if ($months)
                        <div class="row">
                            @foreach ($months as $month => $year)
                                <div class="col text-center">
                                    <div class="leave-overview-month-container {{ $selectedMonth === $month ? 'selected' : '' }}"
                                        wire:click="selectMonth('{{ $month }}')">
                                        <strong
                                            class="leave-overview-month {{ $selectedMonth === $month ? 'leave-overview-text-white' : '' }}">{{ $month }}</strong><br>
                                        <span
                                            class="leave-overview-year {{ $selectedMonth === $month ? 'leave-overview-text-white' : '' }}">{{ $year }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="p-0 m-0 leave-overview-div">
                    @if ($selectedMonth && $months)
                        <p class="leave-overview-selected-date">{{ $selectedMonth }} {{ $months[$selectedMonth] }}</p>
                    @endif
                </div>
                <div class="p-2">
                    <div class="d-flex justify-content-between p-4">
                        <h3 style="color: var(--label-color); font-size: 14px;">Type of leaves taken</h3>

                        <select wire:model="selectedLeaveType" class="form-select mb-3 task-custom-select-width"
                            wire:change="filterLeaveType">
                            <option value="all">All</option>
                            @foreach (array_keys($leaveTypeColors) as $leaveType)
                                <option value="{{ $leaveType }}">{{ $leaveType }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        @if (!empty($leaveData) && count($leaveData) > 0)
                            <div class="col-12 col-md-3 d-flex justify-content-center">
                                <div style="height: 150px; width: 150px;">
                                    <canvas wire:ignore id="leaveChart"></canvas>
                                </div>
                            </div>
                            <div class="col-12 col-md-9">
                                <div class="row">
                                    @if ($selectedLeaveType === 'all')
                                        @foreach ($leaveTypeColors as $leaveType => $color)
                                            <div class="col-md-4 d-flex flex-column">
                                                <div class="mb-2">
                                                    <div class="leave-circle"
                                                        style="background-color: {{ $color }};"></div>
                                                    <span>{{ $leaveTypeAbbreviations[$leaveType] ?? $leaveType }}:
                                                        {{ $leaveData[$leaveType] ?? 0 }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach ($leaveData as $leaveType => $count)
                                            <div class="col-md-4 d-flex flex-column">
                                                <div class="mb-2 mt-2">
                                                    <div class="leave-circle"
                                                        style="background-color: {{ $leaveTypeColors[$leaveType] ?? 'rgba(200, 200, 200, 1)' }};">
                                                    </div>
                                                    <span>{{ $leaveTypeAbbreviations[$leaveType] ?? $leaveType }}:
                                                        {{ $count }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="col-12 text-center">
                                <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                                    style="width: 150px; height:150px;">
                                <p style="font-size: 15px; color: gray;">Wow! There are no leaves.</p>
                            </div>
                        @endif
                    </div>


                </div>
            </div>
            <div class="row leave-overview-details-container mt-3">
                <div class="col-md-6 col-12">
                    <div class="d-flex justify-content-between p-4">
                        <h3 style="color: var(--label-color); font-size: 14px;">Total leaves taken this year</h3>

                        <div class="form-group">

                            <select class="form-select task-custom-select-width" wire:model.defer="monthFilterLeaveType"
                                wire:change="monthLeaveTypeFilter">
                                <option value="all" selected>All</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Casual Leave Probation">Casual Leave Probation </option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Loss Of Pay">Loss Of Pay</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Marriage Leave">Marriage Leave</option>
                                <option value="Petarnity Leave">Petarnity Leave</option>
                            </select>

                        </div>
                    </div>
                    @if (array_sum($leavesCount) > 0)
                        <canvas id="barChart" width="400" height="200"></canvas>
                    @else
                    <div class="col-12 text-center">
                        <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                            style="width: 150px; height:150px;">
                        <p style="font-size: 15px; color: gray;">Wow! There are no leaves.</p>
                    </div>
                    @endif
                </div>
                <div class="col-md-6 col-12">
                    <div class="d-flex justify-content-between p-4">
                        <h3 style="color: var(--label-color); font-size: 14px;">Number of Employees on Leave for each
                            RH</h3>

                            <div class="form-group">

                                <select class="form-select task-custom-select-width"
                                    >
                                    <option value="all" selected>All</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Casual Leave Probation">Casual Leave Probation </option>
                                    <option value="Maternity Leave">Maternity Leave</option>
                                    <option value="Loss Of Pay">Loss Of Pay</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Marriage Leave">Marriage Leave</option>
                                    <option value="Petarnity Leave">Petarnity Leave</option>
                                </select>
    
                            </div>
                    </div>
                    <div class="col-12 text-center">
                        <img src="{{ asset('images/not_found.png') }}" alt="No Leaves"
                            style="width: 150px; height:150px;">
                        <p style="font-size: 15px; color: gray;">No Records Found</p>
                    </div>
                </div>

            </div>

            <div class="leave-overview-details-container mt-3">
                <h1 class="p-3" style="font-size: var(--normal-font-size); color: var(--label-color);">Team on
                    Leave
                </h1>
                <div class="d-flex p-3">
                    <div class="form-group" style="margin-right: 10px;">

                        <select class="form-select task-custom-select-width" wire:model.defer="filterPeriod"
                            wire:change="monthFilter">
                            <option value="this_week" selected>This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                        </select>

                    </div>
                    <div class="form-group">

                        <select class="form-select task-custom-select-width" wire:model.defer="teamOnLeaveType"
                            wire:change="leaveTypeFilter">
                            <option value="all" selected>All</option>
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Casual Leave Probation">Casual Leave Probation </option>
                            <option value="Maternity Leave">Maternity Leave</option>
                            <option value="Loss Of Pay">Loss Of Pay</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Marriage Leave">Marriage Leave</option>
                            <option value="Petarnity Leave">Petarnity Leave</option>
                        </select>

                    </div>
                </div>
                <div class="p-2" style="max-height: 250px; overflow-y: auto; overflow-x: hidden;">
                    <table class="table table-responsive">
                        <thead class="table-header">
                            <tr style="background-color: grey;">
                                <th
                                    style="font-size: var(--normal-font-size); color: var(--label-color);width: 400px;">
                                    Details</th>
                                <th style="font-size: var(--normal-font-size); color: var(--label-color)">Leave Type
                                </th>
                                <th style="font-size: var(--normal-font-size); color: var(--label-color)">No of Days
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($leaveRequests->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <img class="analytic-no-items-found"
                                            src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                            alt="No items found" style="max-width: 100px; height: auto;">
                                    </td>
                                </tr>
                            @else
                                @foreach ($leaveRequests as $leaveRequest)
                                    <tr>
                                        <td
                                            style="font-size: var( --sub-headings-font-size); color: var(--main-heading-color); font-weight: 500; width: 400px;">
                                            {{ ucwords(strtolower($leaveRequest->employee->first_name)) }}
                                            {{ ucwords(strtolower($leaveRequest->employee->last_name)) }}
                                            ({{ $leaveRequest->emp_id }})
                                            <br>
                                            <span
                                                style="font-size: var(--normal-font-size); color: var(--label-color);">
                                                {{ \Carbon\Carbon::parse($leaveRequest->from_date)->format('d M Y') }}
                                                - {{ $leaveRequest->from_session }}
                                                {{ \Carbon\Carbon::parse($leaveRequest->to_date)->format('d M Y') }} -
                                                {{ $leaveRequest->to_session }}
                                            </span>
                                        </td>
                                        <td
                                            style="font-size: var( --sub-headings-font-size); color: var(--main-heading-color); font-weight: 500;">
                                            {{ $leaveRequest->leave_type }}</td>
                                        <td
                                            style="font-size: var( --sub-headings-font-size); color: var(--main-heading-color); font-weight: 500;">
                                            @if (isset($leaveRequest->calculated_days))
                                                {{ $leaveRequest->calculated_days }}
                                            @else
                                                N/A <!-- Or any other default message -->
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('leaveChart').getContext('2d');
        let leaveChart;

        const leaveTypes = [
            'Loss Of Pay',
            'Sick Leave',
            'Maternity Leave',
            'Casual Leave',
            'Marriage Leave',
            'Casual Leave Probation',
            'Paternity Leave'
        ];
        const leaveTypeColors = {
            'Loss Of Pay': '#ffadad', // Lighter shade of red
            'Sick Leave': '#ffd6a5', // Lighter shade of orange
            'Maternity Leave': '#fdffb6', // Lighter shade of yellow
            'Casual Leave': '#caffbf', // Lighter shade of green
            'Marriage Leave': '#a0c4ff', // Lighter shade of blue
            'Casual Leave Probation': '#bdb2ff', // Lighter shade of purple
            'Paternity Leave': '#8996cb', // Lighter shade of teal
        };


        function renderChart(leaveData) {
            const labels = Object.keys(leaveData);
            const data = Object.values(leaveData);
            const backgroundColors = labels.map(leaveType => leaveTypeColors[leaveType] ||
                'rgba(200, 200, 200, 0.5)');

            // Destroy previous chart instance if it exists
            if (leaveChart) {
                leaveChart.destroy();
            }

            if (labels.length === 0) {
                // If no data, show a single "disabled" colored donut
                leaveChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['No Leaves'],
                        datasets: [{
                            label: 'Types of Leaves',
                            data: [1], // Dummy value to create a visible segment
                            backgroundColor: [
                                'rgba(200, 200, 200, 0.5)'
                            ], // Disabled color // Darker border
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '75%',
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: () => 'No Leaves' // Custom tooltip message
                                }
                            },
                            legend: {
                                display: true, // Show legend
                                labels: {
                                    color: 'grey', // Set legend label color
                                    font: {
                                        size: 14 // Adjust font size if needed
                                    }
                                }
                            } // Hide legend since there's only one entry

                        },
                        interaction: {
                            mode: 'nearest',
                            intersect: true // Ensure hover shows tooltip
                        }
                    }
                });
            } else if (labels.length === 1) {
                // When there is only one leave type
                const leaveTypeIndex = 0; // Since there's only one leave type
                const leaveCount = data[leaveTypeIndex];

                leaveChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [labels[leaveTypeIndex], ''],
                        datasets: [{
                            label: 'Types of Leaves',
                            data: [leaveCount, 3], // Leave type takes 1/4 and grey takes 3/4
                            backgroundColor: [
                                leaveTypeColors[labels[leaveTypeIndex]] ||
                                'rgba(200, 200, 200, 0.5)',
                                'rgba(200, 200, 200, 0.5)'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '75%',
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: (tooltipItem) => {
                                        // Check which segment is being hovered
                                        if (tooltipItem.dataIndex === 0) {
                                            return `${labels[leaveTypeIndex]}: ${leaveCount}`;
                                        } else {
                                            return 'No Leaves'; // Show this for the grey segment
                                        }
                                    }
                                }
                            },
                            legend: {
                                display: false,
                            }
                        }
                    }
                });
            } else {
                // Render the normal chart with data
                leaveChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Types of Leaves',
                            data: data,
                            backgroundColor: backgroundColors,
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    }
                });
            }
        }

        // Initially render the chart with the data from Livewire
        renderChart(@json($leaveData));

        // Watch for changes in leaveData and re-render the chart
        Livewire.hook('message.processed', () => {
            renderChart(@json($leaveData));
        });

        const barCtx = document.getElementById('barChart').getContext('2d');
        const leavesCount = @json($leavesCount);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const colors = ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300', '#DAF7A6',
            '#900C3F', '#C70039', '#581845', '#FFC0CB', '#FF4500', '#00FF7F'
        ];
        const maxCount = Math.max(...leavesCount); // Get the highest count
        const yAxisMax = Math.ceil(maxCount / 15) * 15;

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    data: leavesCount,
                    backgroundColor: colors,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false // Hides the legend
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false // Hides x-axis grid lines
                        }
                    },
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: yAxisMax,
                        ticks: {
                            // Set custom step size and labels
                            stepSize: 15, // Set the step size
                            callback: function(value) {
                                // Custom label formatting if needed
                                return value; // You can format this if needed
                            }
                        }
                    }
                }
            }
        });

    });
</script>
