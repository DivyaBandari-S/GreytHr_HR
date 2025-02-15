<div class="main " style="margin: 10px;background-color:var(--light);">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .previous_next {
            border: none;
            font-weight: bold;
        }

        .month_name {
            color: #677a8e;
            font-size: 14px;
        }

        .year {
            color: #677a8e;
        }

        .month-button {
            border: none;
            padding: 5px 0px;
            background-color: white;
            /*  */
        }

        .month-button.active {
            background-color: #5473e3;
            color: white;
            border-radius: 5px;
        }

        .month-button.active .month_name,
        .month-button.active .year {
            color: white;
        }

        .info-icon {
            width: 20px;
            height: 20px;
            background-color: #5473e3;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .emp_details_count {

            line-height: 13px;
            color: #677a8e;
            padding-left: 5px;
            margin: 0px
        }

        .emp_details_label {
            color: #677a8e;
            padding-left: 5px;
            font-size: 12px
        }


        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Space between cards */
            padding: 20px;
            justify-content: space-between;
        }

        .card {
            background: white;
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); */
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            text-align: left;
            flex: 1 1 calc(33.33% - 15px);
        }

        .card h5 {
            color: #677a8e;
            font-size: 18px;

        }

        .card div {
            min-height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            color: #7f8fa4;

        }

        .profile-image {
            height: 25px;
            width: 25px;
        }


        .employee-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            color: #171e25;
            font-size: 13px;
            /* max-width: 200px; */
            /* Adjust as needed */
        }

        .anchor {
            text-decoration: none;
            color: inherit;
            /* Ensures it inherits text color from parent */
        }

        .anchor:hover {
            color: #1d6af4;
            text-decoration: underline;
        }

        .employee-card {
            gap: 10px;
            padding: 10px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .card-container {
                grid-template-columns: repeat(2, 1fr);
                /* 2 cards per row */
            }

            .card {
                flex: 1 1 calc(50% - 15px);
                /* 2 cards per row */
            }
        }

        @media (max-width: 768px) {
            .card-container {
                grid-template-columns: repeat(1, 1fr);
                /* 1 card per row */
            }

            .card {
                flex: 1 1 100%;
                /* 1 card per row */
            }
        }

        /* .previous_next:hover{
            background-color:white;
        } */
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items"> Overview </span> page gives you a summary of the current month's payroll. All data that you view and activities you perform, such as Locking or Unlocking the Payroll Inputs, Payroll, and IT Statement, are for the current month's payroll. Once done, all data you view and the activities you perform are specific to that chosen payroll month. You can also view details related to the Net Payout Amount for the chosen payroll month.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div class="flex items-center justify-center  bg-white-100" style="background-color: white;padding:10px; margin-top: 10px;">
        <div class="flex justify-between bg-white " style="display: contents;">
            <button wire:click="previousFinancialYear" class="previous_next  bg-gray-200 mr-1  hover:bg-gray-300" style="margin-right: 10px; padding: 3px 6px;">
                &lt;
            </button>
            @foreach ($months as $month)

            <button
                wire:click="selectMonth({{ $month['number'] }}, {{ $month['year'] }})"
                class="w-full month-button
                       {{ $selectedMonth == $month['number'] && $selectedYear == $month['year'] ? 'active' : '' }}">
                <div class="month_name" style="font-weight:bold ">{{ $month['name'] }}</div>
                <div class="year" style="font-size:12px">{{ $month['year'] }}</div>
            </button>
            @endforeach
            <button wire:click="nextFinancialYear" class="previous_next ml-1 bg-gray-200 rounded-md hover:bg-gray-300" style="margin-left: 10px;padding: 3px 6px;">
                &gt;
            </button>
        </div>

    </div>


    <div class="d-flex" style="background-color: #edf3ff;padding:20px; margin-top: 10px;">
        <div class="div">
            <p class="bold-items" style="font-size: 20px;margin-bottom: 5px;color:#394657">{{$selectedMonthName}} {{$selectedYear}}</p>
            <p style="font-size: 13px; margin:0px">Cutoff from {{$cutoffStart}} to {{$cutoffEnd}}</p>
        </div>
        <div class="d-flex " style="margin-left:auto;align-items:center;gap:15px">
            <div class="info-icon">
                <i class="fas fa-info" title="Processed Payroll on "></i> <!-- Font Awesome icon -->
            </div>
            <button class="btn btn-primary" style="height: fit-content; background-color:#5473e3; font-size:13px"> Process Payroll</button>
        </div>

    </div>
    <div class="d-flex bg-white" style="padding:20px; margin-top: 10px;">
        <div class="row w-100">
            <div class="col-md-4">
                <h5 class="bold-items" style="color:#394657">Payout Details</h5>

                <div style="width: 250px; height: 250px; margin: auto;">
                    <canvas id="payoutChart"></canvas>
                </div>


            </div>
            <div class="col-md-8">
                <h5 class="bold-items" style="color:#394657">Employee Details</h5>
                <div class="row">
                    <div class="col-md-4" style="padding: 0px 10px;">
                        <div class="div" style="background-color:#fff7eb;padding:10px;border-radius:5px">
                            <p style="font-weight:500;margin:0px;color:#394657">37</p>
                            <p style="font-size: 12px;color:#677a8e">Total Employees</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <p class="emp_details_count" style="border-left: 3px solid #36A2EB ;">00</p>
                                <p class="emp_details_label">Addition</p>
                            </div>
                            <div class="col-6">
                                <p class="emp_details_count" style="border-left: 3px solid #FF6384;">00</p>
                                <p class="emp_details_label">Settlements</p>
                            </div>
                            <div class="col-6">
                                <p class="emp_details_count" style="border-left: 3px solid #FFCE56 ;">{{ str_pad(count($salaryStoppedEmployees), 2, '0', STR_PAD_LEFT) }}</p>
                                <p class="emp_details_label">Exclusion</p>
                            </div>
                            <div class="col-6">
                                <p class="emp_details_count" style="border-left: 3px solid #4CAF50;">00</p>
                                <p class="emp_details_label">Separation</p>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8" style="padding-left: 40px;">
                        <div class="row">
                            <div class="col d-flex" style="align-items: center;gap:30px">
                                <label style="width:150px ; font-size:14px">Payroll Inputs</label>
                                <div class="" style=" height:fit-content;margin-bottom:0px">
                                    <div class="tabButtons">
                                        <button class="tab-button {{ $activeTab1 === 'unlock' ? 'active' : '' }}">
                                            Unlock
                                        </button>
                                        <button class="tab-button {{ $activeTab1 === 'lock' ? 'active' : '' }}">
                                            Lock
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex" style="align-items: center;gap:30px">
                                <label style="width:150px ; font-size:14px">Employee View Release</label>
                                <div class="" style=" height:fit-content;margin-bottom:0px">
                                    <div class="tabButtons">
                                        <button class="tab-button {{ $activeTab2 === 'release' ? 'active' : '' }}">
                                            Release
                                        </button>
                                        <button class="tab-button {{ $activeTab2 === 'hold' ? 'active' : '' }}">
                                            Hold
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex" style="align-items: center;gap:30px">
                                <label style="width:150px ; font-size:14px">IT Statement Employee View</label>
                                <div class="" style=" height:fit-content;margin-bottom:0px">
                                    <div class="tabButtons">
                                        <button class="tab-button {{ $activeTab3 === 'release' ? 'active' : '' }}">
                                            Release
                                        </button>
                                        <button class="tab-button {{ $activeTab3 === 'hold' ? 'active' : '' }}">
                                            Hold
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex" style="align-items: center;gap:30px">
                                <label style="width:150px ; font-size:14px">Payroll Inputs</label>
                                <div class="" style=" height:fit-content;margin-bottom:0px">
                                    <div class="tabButtons">
                                        <button class="tab-button {{ $activeTab4 === 'unlock' ? 'active' : '' }}">
                                            Unlock
                                        </button>
                                        <button class="tab-button {{ $activeTab4 === 'lock' ? 'active' : '' }}">
                                            Lock
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex" style="padding:20px; margin-top: 10px;">

        <div class="card-container">
            <div class="card">
                <h5>Negative Salary</h5>
                <div>
                    <p style="color:#7f8fa4">No Records</p>
                </div>

            </div>

            <div class="card">
                <h5>Stop Salary Processing ({{count($salaryStoppedEmployees)}})</h5>
                <div>
                    @if($salaryStoppedEmployees->isNotEmpty())
                    <ul>
                        @foreach($salaryStoppedEmployees->take(2) as $employee)
                        <li class="d-flex employee-card">
                            <span style="height: fit-content;">
                                @if ($employee->image)
                                <img src="data:image/jpeg;base64,{{ $employee->image }}" alt="base" class="profile-image" />
                                @else
                                @if ($employee->gender == 'Male')
                                <img class="profile-image" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                                @elseif ($employee->gender == 'Female')
                                <img class="profile-image" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                                @else
                                <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                @endif
                                @endif
                            </span>
                            <span class="text-truncate employee-name">
                                <a class="anchor" href="{{ url('/hr/user/stop-salaries') }}">
                                    {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }} ({{ ucfirst(strtolower($employee->emp_id)) }})
                                </a>
                            </span>
                        </li>
                        @endforeach

                        @if(count($salaryStoppedEmployees) > 2)
                        <li class="text-center">
                            <span class="employee-name" style="color:#1d6af4;">
                                <a href="{{ url('/hr/user/stop-salaries') }}" class="anchor">
                                    +{{ count($salaryStoppedEmployees) - 2 }} more
                                </a>
                            </span>
                        </li>
                        @endif
                    </ul>
                    @else
                    <p style="color:#7f8fa4">No Records</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <h5>Settled Employees</h5>
                <div>
                    <p style="color:#7f8fa4">No Records</p>
                </div>

            </div>

            <div class="card">
                <h5>Hold Salary Payout ({{count($salaryHoldedEmployees)}})</h5>
                <div>
                    @if($salaryStoppedEmployees->isNotEmpty())
                    <ul>
                        @foreach($salaryHoldedEmployees->take(2) as $employee)
                        <li class="d-flex  employee-card">
                            <span style="height: fit-content;">
                                @if ($employee->image)
                                <img src="data:image/jpeg;base64,{{ $employee->image }}"
                                    alt="base" class="profile-image" />
                                @else
                                @if ($employee->gender == 'Male')
                                <img class="profile-image"
                                    src="{{ asset('images/male-default.png') }}"
                                    alt="Default Male Image">
                                @elseif ($employee->gender == 'Female')
                                <img class="profile-image"
                                    src="{{ asset('images/female-default.jpg') }}"
                                    alt="Default Female Image">
                                @else
                                <img class="profile-image"
                                    src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                @endif
                                @endif
                            </span>
                            <span class="text-truncate employee-name">
                                <a class="anchor" href="{{ url('/hr/user/hold-salaries') }}">
                                    {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }} ({{ ucfirst(strtolower($employee->emp_id)) }})
                                </a>
                            </span>
                        </li>
                        @endforeach
                        @if(count($salaryHoldedEmployees) > 2)
                        <li class="text-center">
                            <span class="employee-name" style="color:#1d6af4;">
                                <a href="{{ url('/hr/user/hold-salaries') }}" class="anchor">
                                    +{{ count($salaryHoldedEmployees) - 2 }} more
                                </a>
                            </span>
                        </li>
                        @endif
                    </ul>
                    @else
                    <p style="color:#7f8fa4">No Records</p>
                    @endif
                </div>

            </div>

            <div class="card">
                <h5>Payout Pending</h5>
                <div>
                    <p style="color:#7f8fa4">No Records</p>
                </div>
            </div>

            <div class="card">
                <h5>Locations Without PT State</h5>
                <div>
                    <p style="color:#7f8fa4">No Records</p>
                </div>
            </div>
        </div>

    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let ctx = document.getElementById('payoutChart').getContext('2d');

        let payoutChart = new Chart(ctx, {
            type: 'doughnut',
            options: {
                cutout: '60%',
            },

            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    data: @json($chartData['values']),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', ],
                    hoverOffset: 4
                }]
            }
        });
    });
</script>
