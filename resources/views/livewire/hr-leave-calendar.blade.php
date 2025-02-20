<div class=" position-relative" >
    <style>
        .table thead {
            border: none;
        }

        .table .text {
            font-size: 0.875rem;
            color: #778899;
            font-weight: 600;
        }

        .table th {
            text-align: center;
            height: 15px;
            border: none;
        }

        .table td:hover {
            background-color: #ecf7fe;
            cursor: pointer;
        }

        .table tbody td {
            width: 75px;
            height: 80px;
            border-color: #c5cdd4;
            font-weight: 500;
            font-size: 13px;
            vertical-align: top;
            position: relative;
            text-align: left;
        }
    </style>

    <div class="container-leave">
        <div class="m-0 py-2 px-3 row">
            <!-- Dropdown for filter selection -->
            <div class="col-md-4">
            </div>
            <div class="col-md-5"></div>
            <div class="col-md-3 pr-2 d-flex justify-content-end">
                <button class="submit-btn">
                    <i class="fa fa-download" aria-hidden="true" wire:click="downloadexcelforLeave" style="font-size:16px;"></i>
                </button>
            </div>
        </div>
        <div class="row m-0 px-3">
            <div class="col-md-7">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="calendar-heading-container">
                        <button wire:click="previousMonth" class="nav-btn">&lt; Prev</button>
                        <h5>{{ date('F Y', strtotime("$year-$month-1")) }}</h5>
                        <button wire:click="nextMonth" class="nav-btn">Next &gt;</button>
                    </div>
                </div>
                <!-- Calendar -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text">Sun</th>
                                <th class="text">Mon</th>
                                <th class="text">Tue</th>
                                <th class="text">Wed</th>
                                <th class="text">Thu</th>
                                <th class="text">Fri</th>
                                <th class="text">Sat</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                            @foreach ($calendar as $week)
                            <tr>
                                @foreach ($week as $day)
                                @php
                                $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $day['day']);
                                $isCurrentMonth = $day['isCurrentMonth'];
                                $isWeekend = in_array($carbonDate->dayOfWeek, [0, 6]); // 0 for Sunday, 6 for Saturday
                                $isActiveDate = ($selectedDate === $carbonDate->toDateString());
                                $leaveCount = $day['leaveCount']; // Just use the leaveCount directly
                                @endphp
                                <td wire:click="dateClicked('{{ $day['day'] }}')" class="calendar-date{{ $isActiveDate ? ' active-date' : '' }}" data-date="{{ $day['day'] }}" style="color: {{ $isCurrentMonth ? ($isWeekend ? '#9da4a9' : 'black') : '#9da4a9' }};">
                                    @if ($day)
                                    <div>
                                        @if ($day['isToday'])
                                        <div class="isToday">
                                            {{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        @else
                                        {{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}
                                        @endif
                                        <div class="circle-holiday{{ $day['isPublicHoliday'] ? ' IRIS' : '' }}">
                                            <!-- Render your content -->
                                        </div>
                                        @if (!$isWeekend && $leaveCount > 0) {{-- Only display leave count for weekdays --}}
                                        <div class="circle-greys">
                                            <span class="d-flex justify-content-center align-items-center rounded-circle" >
                                                {{ $leaveCount }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="tol-calendar-legend mt-1 mb-3">
                    <div>
                        Team on Leave
                        <span class="legend-circle" style="background: #306cc6; font-size: 0.75rem;">
                            0
                        </span>
                    </div>
                    <div>
                        Restricted Holiday
                        <span class="legend-circle circle-pale-yellow"></span>
                    </div>
                    <div>
                        General Holiday
                        <span class="legend-circle circle-pale-pink"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <!-- Inside the event-container div -->
                <div class="event-details">
                    @if($holidays->count() > 0)
                    <div class="date-day">
                        <span class="fw-500">{{ \Carbon\Carbon::parse($selectedDate)->format('D') }} <br>
                            <span class="fw-normal normalTextValueSubheading" style="margin-top:-5px;">{{ \Carbon\Carbon::parse($selectedDate)->format('d') }}</span>
                        </span>

                    </div>
                    <div class="holiday-con">
                        @foreach($holidays as $holiday)
                        <span class="normalTextValue">General Holiday <br>
                            <span class="normalTextValueSubheading">{{ $holiday->festivals }}</span>
                        </span>

                        @endforeach
                    </div>
                    @endif
                </div>
                <!-- end -->
                <div class="cont d-flex justify-content-end  mt-4 ">
                    <div class="search-containers d-flex">
                        <div class="form-group">
                            <div class="search-input-leave">
                                <div class="search-cont d-flex align-items-center gap-2">
                                    <input wire:input="searchData" wire:model="searchTerm" type="text" placeholder="Search..." class="placeholder-small" style="padding: 0.37rem 0.2rem;">
                                    <!-- Search button -->
                                    <!-- <button class="btn-3" wire:click="searchData"><i class="fa fa-search"></i></button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="filter-container1">
                        <div id="main" style="margin-left: {{ $showDialog ? '250px' : '0' }}">
                            <button class="openbtn" wire:click="open">
                                <i class="fas fa-filter" style="color:#778899;"></i>
                            </button>
                        </div>
                    </div> -->
                </div>
                @if($showAccordion)
                <div class=" rounded mt-3">
                    <div class="bg-white border active rounded">
                        <div class="bg-white  p-2">
                            <div class=" bg-white ">
                                <span class="normalTextSubheading">Leave transactions({{ count($this->leaveTransactions) ?: 0 }})</span>
                            </div>
                        </div>
                    </div>
                    <div class=" border rounded p-0 m-0">
                        <div class="col-md-12 scroll-table">
                            <table class="leave-table p-2" >
                                <thead>
                                    <tr>
                                        <th class="employee-id">Employee ID</th>
                                        <th class="num-days">No of days</th>
                                        <th class="from-to">From-To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (empty($this->leaveTransactions))
                                    <tr>
                                        <td colspan="3">
                                            <p>No data found</p>
                                        </td>
                                    </tr>
                                    @else
                                    @if (!empty($selectedDate))
                                    @forelse($this->leaveTransactions as $transaction)
                                    <tr>
                                        <td class="employee-info">
                                            <span title="{{ ucwords(strtolower($transaction->employee->first_name)) }} {{ ucwords(strtolower($transaction->employee->last_name)) }}: {{ $transaction->emp_id }}">
                                                {{ ucwords(strtolower($transaction->employee->first_name)) }} {{ ucwords(strtolower($transaction->employee->last_name)) }} <span style="font-size: 11px; color: #778899;">(#{{ $transaction->emp_id }})</span>
                                            </span> <br>
                                            <span title="{{ $transaction->employee->job_location ? $transaction->employee->job_location . ', ' : '' }}{{ $transaction->employee->job_title }}" class="normalTextSmall fw-normal">
                                                {{ $transaction->employee->job_location ? $transaction->employee->job_location . ($transaction->employee->job_title ? ', ' : '') : '' }}{{ $transaction->employee->job_title }}
                                            </span>
                                        </td>

                                        <td class="num-days">{{ $this->calculateNumberOfDays($transaction->from_date, $transaction->from_session, $transaction->to_date, $transaction->to_session,$transaction->leave_type) }}</td>
                                        <td class="date-range">
                                            @if($transaction->from_date === $transaction->to_date)
                                            <span>{{ \Carbon\Carbon::parse($transaction->from_date)->format('d M') }}</span>
                                            @else
                                            <span>{{ \Carbon\Carbon::parse($transaction->from_date)->format('d M') }} - {{ \Carbon\Carbon::parse($transaction->to_date)->format('d M') }}</span><br>
                                            <span class="session-info">{{$transaction->from_session}}&nbsp;&nbsp;&nbsp;&nbsp;{{$transaction->to_session}}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="3" style="border:none;" >
                                            <div class="no-data">
                                                <img src="/images/pending.png" alt="Pending Image" style="width: 80%;">
                                                <span>No Employees are on leave</span>
                                            </div>
                                        </td>
                                    </tr>

                                    @endforelse

                                    @else
                                    <tr>
                                        <td colspan="3" style="border:none;">
                                            <div class="no-data">
                                                <img src="/images/pending.png"  alt="Pending Image" style="width: 80%;">
                                                <span>No Employees are on leave</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                @endif
            </div>
        </div>


        <script>
            // Function to toggle the accordion
            function toggleAccordion(element) {
                const accordionBody = element.closest('.accordion').querySelector('.accordion-body');
                const arrowIcon = element.querySelector('i');
                const isOpen = accordionBody.style.display === 'block';

                if (isOpen) {
                    accordionBody.style.display = 'none';
                    arrowIcon.classList.remove('fa-chevron-up');
                    arrowIcon.classList.add('fa-chevron-down');
                    localStorage.setItem('accordionState', 'close');
                } else {
                    accordionBody.style.display = 'block';
                    arrowIcon.classList.remove('fa-chevron-down');
                    arrowIcon.classList.add('fa-chevron-up');
                    localStorage.setItem('accordionState', 'open');
                }
            }

            // Event delegation for toggling the accordion
            document.addEventListener('click', function(event) {
                if (event.target && event.target.closest('.arrow-btn')) {
                    toggleAccordion(event.target.closest('.arrow-btn'));
                }
            });

            // Check the accordion state on page load and set it accordingly
            window.addEventListener('load', function() {
                const accordionState = localStorage.getItem('accordionState');
                const accordionBodies = document.querySelectorAll('.accordion-body');

                if (accordionState === 'closed') {
                    accordionBodies.forEach(body => {
                        body.style.display = 'none';
                        const arrowIcon = body.previousElementSibling.querySelector('.arrow-btn i');
                        arrowIcon.classList.remove('fa-chevron-up');
                        arrowIcon.classList.add('fa-chevron-down');
                    });
                } else {
                    accordionBodies.forEach(body => {
                        body.style.display = 'block';
                        const arrowIcon = body.previousElementSibling.querySelector('.arrow-btn i');
                        arrowIcon.classList.remove('fa-chevron-down');
                        arrowIcon.classList.add('fa-chevron-up');
                    });
                }
            });
        </script>
    </div>