<div style="padding-top: 20px; display: flex; flex-direction: column; align-items: center;width:970px">

    <!-- First Row -->
    <div style="display: flex; justify-content: center; width: 100%;">

        <!-- New Joiners for Last 1 Month (First Column) -->
        <div style="flex: 1; margin-right: 20px;">
            <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); height: 250px; @if(!$newJoiners->isEmpty()) overflow-y: auto; @endif">
                <h2 style="font-size: 14px; margin-bottom: 10px; text-align: center;">New Joiners for Last 1 Month</h2>
                @if($newJoiners->isEmpty())
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;">
                        <img src="/images/not_found.png" alt="Image Description" style="width: 5em; margin-bottom: 10px;">
                        <p style="color: #677A8E; font-size: 12px; text-align: center;">No Joins in Last Month</p>
                    </div>
                @else
                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                        @foreach($newJoiners as $joiner)
                            <li style="margin-bottom: 5px;">
                                <div style="font-size: 12px;">
                                    <strong>Name:</strong> {{ $joiner->first_name }} {{ $joiner->last_name }}<br>
                                    <span style="color: #778899;">Employee ID: {{ $joiner->emp_id }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Spacer -->
        <div style="width:10px;"></div>

        <!-- Employee Anniversaries for Current Month (Second Column) -->
        <div style="flex: 1; margin-right: 20px;">
            <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); height: 250px; @if(!$employeesWithAnniversaries->isEmpty()) overflow-y: auto; @endif">
                <h2 style="font-size: 12px; margin-bottom: 10px; text-align: center;">Employee Anniversaries for This Month</h2>
                @if($employeesWithAnniversaries->isEmpty())
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;">
                        <img src="/images/not_found.png" alt="Image Description" style="width: 5em; margin-bottom: 10px;">
                        <p style="color: #677A8E; font-size: 12px; text-align: center;">No Anniversaries This Month</p>
                    </div>
                @else
                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                        @foreach($employeesWithAnniversaries as $anniversary)
                            <li style="margin-bottom: 5px;">
                                <div style="font-size: 12px;">
                                   <div style="display: flex; justify-content: space-between;font-size: 11px;">
                                        <div style="font-size: 10px;flex: 1;">
                                            @if($anniversary->anniversary_years != 0)
                                             <strong>Name:</strong> {{ $anniversary->first_name }} {{ $anniversary->last_name }}
                                            @endif
                                        </div>
                                        <div style="font-size: 10px;width:30%;text-align: right;">
                                            @if($anniversary->anniversary_years != 0)
                                             <span style="color: #778899;">Years: {{ $anniversary->anniversary_years }}</span>
                                            @endif 
                                        </div>
                                    </div>
                                    @if($anniversary->anniversary_years != 0)
                                        <span style="color: #778899;">Employee ID: {{ $anniversary->emp_id }}</span><br>
                                        <span style="color: #778899;">Hire Date: {{ \Carbon\Carbon::parse($anniversary->hire_date)->format('Y-m-d') }}</span>
                                    @endif    
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Spacer -->
        <div style="width:10px;"></div>

        <!-- Upcoming Birthdays for the week (Third Column) -->
        <div style="flex: 1;">
            <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); height: 250px; @if(!$employeesBirthdays->isEmpty()) overflow-y: auto; @endif">
                <h2 style="font-size: 14px; margin-bottom: 10px; text-align: center;">Upcoming Birthdays for the week</h2>
                @if($employeesBirthdays->isEmpty())
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;">
                        <img src="/images/not_found.png" alt="Image Description" style="width: 5em; margin-bottom: 10px;">
                        <p style="color: #677A8E; font-size: 12px; text-align: center;">No Birthdays This Month</p>
                    </div>
                @else
                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                        @foreach($employeesBirthdays as $birthday)
                            <li style="margin-bottom: 5px;">
                                <div style="font-size: 12px;">
                                    <strong>Name:</strong> {{ $birthday->first_name }} {{ $birthday->last_name }}<br>
                                    <span style="color: #778899;">Employee ID: {{ $birthday->emp_id }}</span><br>
                                    <span style="color: #778899;">Birthday: {{ \Carbon\Carbon::parse($birthday->date_of_birth)->format('Y-m-d') }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div style="padding-top: 20px; display: flex; justify-content: center; width: 100%; flex-wrap: wrap;">

        <!-- First Column (Inactive Employees in a Month) -->
        <div style="flex: 1; margin-right:20px; min-width: 200px; max-width: 300px;">
            <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); height: 250px; overflow-y: auto;">
                <h2 style="font-size: 14px; margin-bottom: 10px; text-align: center;">Confirmation due in 1 Month</h2>
                @if($inactiveEmployees->isEmpty())
                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 95%;">
                        <img src="/images/not_found.png" alt="Image Description" style="width: 5em; margin-bottom: 10px;">
                        <p style="color: #677A8E; font-size: 12px; text-align: center;">No Confirmation This Month</p>
                    </div>
                    @else
                        <ul style="list-style-type: none; padding: 0; margin: 0;">
                            @foreach($inactiveEmployees as $inactive)
                                <li style="margin-bottom: 10px;">
                                    <div style="font-size: 12px;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="font-size: 10px;flex: 1;">
                                                <strong>Name:</strong> {{ $inactive->first_name }} {{ $inactive->last_name }}
                                            </div>
                                            <div style="color: #778899;font-size: 10px;width:30%;text-align: right;">
                                                <span>Notice Period: {{ $inactive->notice_period }}</span>
                                            </div>
                                        </div>
                                        <div style="margin-top: 5px; color: #778899;">
                                            <span>{{ $inactive->emp_id }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
            </div>
        </div>

        <!-- Second Column (Swipe Records of Employees) -->
        <div style="flex: 2;margin: 5px 10px 10px 10px; min-width: 400px;">
            <div style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); height: 250px; margin-top: -5px;">
                <h2 style="font-size: 14px; text-align: center;">Swipe Records of Employees</h2>
                <canvas id="swipeRecordChart" style="height:95%; width: 100%;"></canvas>
            </div>
        </div>

    </div>

    <!-- Third Row -->
    <div style="flex: 1; width: 100%;">
        <canvas id="employeeChart" style="height: 250px; width: 100%;"></canvas>
    </div>

</div>
<!-- JavaScript for Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Employee Count Chart
        var months = @json($months);
        var employeeCounts = @json($employeeCounts);

        var ctx = document.getElementById('employeeChart').getContext('2d');
        var employeeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Employee Count',
                    data: employeeCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Employee Count'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });

        // Fetch data for last month
        var lastMonthData = {!! json_encode(array_values($data['lastMonth'] ?? [])) !!};
        // Fetch data for current month
        var currentMonthData = {!! json_encode(array_values($data['currentMonth'] ?? [])) !!};

        // Calculate the number of days in the current month
        var daysInCurrentMonth = {!! json_encode(now()->daysInMonth) !!};

        // Generate labels for the days of the current month
        var labels = Array.from({ length: daysInCurrentMonth }, (_, i) => i + 1);

        console.log('Last Month Data:', lastMonthData);
        console.log('Current Month Data:', currentMonthData);
        console.log('Labels:', labels);

        var ctx = document.getElementById('swipeRecordChart').getContext('2d');
        var signInChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Last Month',
                        data: lastMonthData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Blue color
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue color
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'Current Month',
                        data: currentMonthData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Pink color
                        borderColor: 'rgba(255, 99, 132, 1)', // Pink color
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Day of Month'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Employee Count'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

    });
</script>

