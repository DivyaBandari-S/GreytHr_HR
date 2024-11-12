<div>
<div class="row m-0 mt-3">

<div class="row m-0 d-flex justify-content-center text-center">

        <div class="col-12 col-md-4">

            <div class="row m-0 topMsg-attendance-info d-flex align-items-center">



                <div class="col-8 p-0">

                    <!-- Small box with the text -->

                    <div style="white-space:nowrap;text-align:center;margin-left:30px;font-size:12px;">Access card details not available</div>



                </div>



                <!-- Blue info icon on the right -->

                <div class="info-icon-container-attendance-info col-4">

                    <i class="fa fa-info-circle" aria-hidden="true" style="margin-left:66px;font-size: 14px; color: blue;text-decoration:none;"></i>

                    <div class="info-box-attendance-info">

                        Contact administrator to get access card assigned.

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-2"></div>

    </div>

    <div class="row m-0 mt-3">

        <div class="row m-0 d-flex justify-content-center" style="display:flex;justify-content:center;">

       

            <div class="penalty-and-average-work-hours-card col-md-3 mb-3">

            <div class="insight-card bg-white pt-2 pb-2"style="{{ $percentageinworkhrsforattendance == 0 ? 'height: 135px;' : '' }}">
                        <h6 class="text-secondary text-regular text-center" style="font-size:12px;border-bottom:1px solid #ccc;padding-bottom:5px;">
                            Avg.&nbsp;Actual&nbsp;Work&nbsp;Hrs</h6>
                        <section class="text-center">

                                            <p class="text-2" style="margin-top:30px;">{{$averageWorkHrsForCurrentMonth}}</p>


                            <div>


                                   @if($percentageinworkhrsforattendance>0)
                                            <span class="text-success ng-star-inserted" style="font-size:10px;"> +{{intval($percentageinworkhrsforattendance)}}%
                                            </span>
                                            <span class="text-muted" style="font-size:10px;margin-left:0px;"> From {{ \Carbon\Carbon::createFromDate($year, $month, 1)->subMonth()->format('F') }}
                                            </span>
                                   @elseif($percentageinworkhrsforattendance<0) 
                                       <span class="text-danger ng-star-inserted" style="font-size:10px;"> {{intval($percentageinworkhrsforattendance)}}%
                                        </span>
                                        <span class="text-muted" style="font-size:10px;margin-left:0px;"> From {{ \Carbon\Carbon::createFromDate($year, $month, 1)->subMonth()->format('F') }}
                                        </span> 
                                       
                                    @endif
                            </div>

                        </section>
                    </div>
            </div>

            <div class="penalty-and-average-work-hours-card mb-3 col-md-3">

                <div class="insight-card bg-white pt-2 pb-2"style="{{ $percentageinworkhrsforattendance == 0 ? 'height: 135px;' : '' }}">

                    <h6 class="text-secondary text-regular text-center" style="font-size:12px;border-bottom:1px solid #ccc;padding-bottom:5px;">

                        Avg.&nbsp;Actual&nbsp;Work&nbsp;Hrs</h6>

                    <section class="text-center">

                               

                                        <p class="text-2" style="margin-top:30px;">{{$averageWorkHrsForCurrentMonth}}</p>

                               



                        <div>

                               @if($avgWorkHoursPreviousMonth==0)

                               <span class="text-success ng-star-inserted" style="font-size:10px;">

                                        </span>

                                        <span class="text-muted" style="font-size:10px;margin-left:0px;">

                                        </span>



                               @elseif($percentageinworkhrsforattendance>0)

                                        <span class="text-success ng-star-inserted" style="font-size:10px;"> +{{intval($percentageinworkhrsforattendance)}}%

                                        </span>

                                        <span class="text-muted" style="font-size:10px;margin-left:0px;"> From {{ \Carbon\Carbon::createFromDate($year, $month, 1)->subMonth()->format('F') }}

                                        </span>

                               @elseif($percentageinworkhrsforattendance<0)

                                   <span class="text-danger ng-star-inserted" style="font-size:10px;"> {{intval($percentageinworkhrsforattendance)}}%

                                    </span>

                                    <span class="text-muted" style="font-size:10px;margin-left:0px;"> From {{ \Carbon\Carbon::createFromDate($year, $month, 1)->subMonth()->format('F') }}

                                    </span>

                                @endif

                        </div>



                    </section>

                </div>

            </div>



            <div class="penalty-and-average-work-hours-card mb-3 col-md-3">

                <div class="insight-card  bg-white pt-2 pb-2" style="height: 135px;">

                    <h6 class="text-secondary text-regular text-center" style="font-size:12px;border-bottom:1px solid #ccc;padding-bottom:5px;"> Penalty Days </h6>

                    <section class="text-center">

                        <p class="text-2" style="margin-top:30px;"> 0 </p>

                    </section>

                </div>

            </div>

            <div class="col-md-2 mt-5" style="text-align: center">

                <a href="#" class="attendanceperiod" wire:click="öpenattendanceperiodModal">

                    Insights

                </a>

            </div>
            @if ($öpenattendanceperiod==true)
            
            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
                    <div class="modal-content attendance-period">
                        <div class="modal-header" style="background-color: var(--main-button-color); height: 50px;display: flex; justify-content: space-between; align-items: center;">
                            <p class="modal-title attendance-period-header" style="color:white;">
                                {{$modalTitle}}

                            </p>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeattendanceperiodModal" style="background: none; border: none;">
                                <span aria-hidden="true" class="close-btn" style="color: white; font-size: 30px;">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          @if ($errors->has('date_range'))
                                     <span class="text-danger text-center"style="padding-left:200px;">{{ $errors->first('date_range') }}</span>
                          @endif
                            <div class="form-row" style="display: flex; justify-content: center;">
                                <div class="form-group col-md-3 col-sm-6 start-date-for-attend-period">
                                    <label for="fromDate" style="color: #778899; font-size: 12px; font-weight: 500;">From
                                        Date</label>
                                    <input type="date" class="form-control" id="fromDate" wire:model="start_date_for_insights" name="fromDate" wire:change="calculateTotalDays" style="color: #778899;">
                                </div>
                                <div class="form-group col-md-3 col-sm-6">
                                    <label for="toDate" style="color: #778899; font-size: 12px; font-weight: 500;">To
                                        Date</label>
                                    <input type="date" class="form-control" id="toDate" name="toDate" wire:model="to_date" wire:change="calculateTotalDays" style="color: #778899;">
                                </div>
                            </div>
                            <p style="font-size:12px;margin-top:3px">Total Working Days:&nbsp;&nbsp;<span style="font-weight:bold;">{{$totalWorkingDays}}</span></p>

                            <div class="table-responsive">


                                <table class="attendence-period-table">
                                    <thead>
                                        <tr>
                                            <th class="insights-for-attendance-period-avg-working-hours">Avg. Work Hrs</th>
                                            <th class="insights-for-attendance-period-avg-working-hours">Avg. Actual Work Hrs</th>
                                            <th class="insights-for-attendance-period">Penalty Days</th>
                                            <th class="insights-for-attendance-period">Late In</th>
                                            <th class="insights-for-attendance-period">Early Out</th>
                                            <th class="insights-for-attendance-period">Leave Taken</th>
                                            <th class="insights-for-attendance-period">Absent Days</th>
                                            <th class="insights-for-attendance-period">Exception Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="insights-for-attendance-period-avg-working-hours">
                                                      <section class="text-center">
                                                               <p class="text-2" style="margin-top:30px;">{{$averageWorkHoursForModalTitle}}</p>
                                                                        

                                                     </section>
                                            </td>
                                            <td class="insights-for-attendance-period-avg-working-hours">

                                            <section class="text-center">
                                                               <p class="text-2" style="margin-top:30px;">{{$averageWorkHoursForModalTitle}}</p>
                                                                       
                                                     </section>
                                            </td>
                                            <td class="insights-for-attendance-period">0</td>
                                            <td class="insights-for-attendance-period">{{$totalLateInSwipes}}</td>
                                            <td class="insights-for-attendance-period">{{$totalnumberofEarlyOut}}</td>
                                            <td class="insights-for-attendance-period">{{$totalnumberofLeaves}}</td>
                                            <td class="insights-for-attendance-period">{{$totalnumberofAbsents}}</td>
                                            <td class="insights-for-attendance-period">-</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div class="row m-0 mt-3 average-first-and-last-time">
                                <div class="col-md-3 col-sm-6 p-0">
                                    <p style="font-size:12px;color:#778899;">Avg First In Time:&nbsp;&nbsp;<span style="font-weight:600;color:black;">{{$avergageFirstInTime}}</span></p>
                                </div>
                                <div class="col-md-3 col-sm-6 p-0">
                                    <p style="font-size:12px;color:#778899;">Avg Last Out Time:&nbsp;&nbsp;<span style="font-weight:600;color:black;">{{$averageLastOutTime}}</span></p>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif            
        </div>

        </div>

        <div class="col-6" style="text-align: left">

            <a href="#" id="toggleSidebar" class="gt-overlay-toggle" style="margin-top:69px;color:rgb(2, 17, 79); display: none">Legend</a>

        </div>

        <div class="col-12">

            <div class="toggle-box-attendance-info">

                <i class="fas fa-calendar" id="calendar-icon" style="cursor:pointer;padding:2px 2px;color: {{ ($defaultfaCalendar == 1 )? '#fff' : 'rgb(2,17,79)' }};background-color: {{ ($defaultfaCalendar == 1 )? 'rgb(2,17,79)' : '#fff' }};" wire:click="showBars"></i>

                <i class="fas fa-bars" id="bars-icon" style="cursor:pointer;padding:2px 2px;color: {{ ($defaultfaCalendar == 0 )? '#fff' : 'rgb(2,17,79)' }};background-color: {{ ($defaultfaCalendar == 0 )? 'rgb(2,17,79)' : '#fff' }};" wire:click="showTable"></i>

            </div>

        </div>

    </div>

<div class="row m-0 p-0">

    @if($defaultfaCalendar==1)

        <div class="col-12 col-md-7 m-0 p-1 calendar custom-scrollbar">

            <div class="d-flex justify-content-between align-items-center">

                <div class="calendar-heading-container">

                    <button wire:click="beforeMonth" class="nav-btn">&lt; Prev</button>

                    <p style="font-size: 14px;color:black;font-weight:500;margin-bottom:0;">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>

                    <button wire:click="nextMonth" class="nav-btn">Next &gt;</button>

                </div>

            </div>

            <!-- Calendar -->

            <div class="table-responsive">

                <table class="table-1 table-bordered">

                    <thead class="calender-header bg-white">

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

                        @if(!empty($calendar))

                        @foreach($calendar as $week)

                        <tr>

                            @foreach($week as $day)

                            @php

                            $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $day['day']);



                            $formattedDate = $carbonDate->format('Y-m-d');

                            $formattedDate1 = $carbonDate->format('Y-m-d');

                            $isCurrentMonth = $day['isCurrentMonth'];

                            $isWeekend = in_array($carbonDate->dayOfWeek, [0, 6]); // 0 for Sunday, 6 for Saturday

                            $isActiveDate = ($selectedDate === $carbonDate->toDateString());

                            @endphp





                            @if ($day)

                            @if(strtotime($formattedDate) < strtotime(date('Y-m-d'))) @php $flag=1; @endphp @else @php $flag=0; @endphp @endif @if($day['status']=='CLP' ||$day['status']=='SL' ||$day['status']=='LOP' ||$day['status']=='CL' ||$day['status']=='ML' ||$day['status']=='PL' ||$day['status']=='L' ) @php $leave=1; @endphp @else @php $leave=0; @endphp @endif <td wire:click="dateClicked('{{$formattedDate}}')" wire:model="dateclicked" class="attendance-calendar-date {{ $isCurrentMonth && !$isWeekend ? 'clickable-date' : '' }}" style="text-align:start;color: {{ $isCurrentMonth ? ($isWeekend ? '#c5cdd4' : 'black')  : '#c5cdd4'}};background-color:  @if($isCurrentMonth && !$isWeekend && $flag==1 ) @if($day['isPublicHoliday'] ) #f3faff @elseif($leave == 1) rgb(252, 242, 255) @elseif($day['status'] == 'A') #fcf0f0 @elseif($day['status'] == 'P') #edfaed @endif @elseif($isCurrentMonth && $isWeekend && $flag==1)rgb(247, 247, 247) @endif ;">

                                <div>





                                    @if ($day['isToday'])

                                    <div style="background-color: rgb(2,17,79); color: white; border-radius: 50%; width: 24px; height: 24px; text-align: center; line-height: 24px;">

                                        {{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}

                                    </div>

                                    @else

                                    {{ str_pad($day['day'], 2, '0', STR_PAD_LEFT) }}

                                    @endif





                                    <div class="{{ $isWeekend ? '' : 'circle-grey' }}">

                                        <!-- Render your grey circle -->

                                        @if ($isWeekend&&$isCurrentMonth)

                                        <i class="fas fa-tv" style="float:right;padding-left:8px;margin-top:-15px;"></i>



                                        <span style="text-align:center;color: #7f8fa4; padding-left:21px;padding-right:26px;margin-left: 6px;white-space: nowrap;">

                                            O

                                        </span>

                                        @elseif($isCurrentMonth)





                                        @if(strtotime($formattedDate) < strtotime(date('Y-m-d'))) <span style="display: flex; justify-content: center; align-items: center; width: 20px; height: 20px; border-radius: 50%; white-space: nowrap;">



                                            @if($day['isPublicHoliday'])

                                            <span style="background-color: #f3faff;text-align:center;color: #7f8fa4; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">H</span>

                                            @elseif($day['status'] == 'CLP')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">CLP</span>

                                            @elseif($day['status'] == 'SL')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">SL</span>

                                            @elseif($day['status'] == 'LOP')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">LOP</span>

                                            @elseif($day['status'] == 'CL')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">CL</span>

                                            @elseif($day['status'] == 'ML')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">ML</span>

                                            @elseif($day['status'] == 'PL')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">PL</span>

                                            @elseif($day['status'] == 'L')

                                            <span style="background-color:  rgb(252, 242, 255);color: #7f8fa4;text-align:center; padding-left: 30px; margin-left: 37px;white-space: nowrap;padding-top:5px">L</span>

                                            @elseif($day['status'] == 'A')

                                            <span style="color:#ff6666; background-color: #fcf0f0;text-align:center;padding-left:30px;margin-left: 37px;white-space: nowrap;padding-top:5px">A</span>

                                            @elseif($day['status'] == 'P')

                                            <span style="background-color:#edfaed; text-align:center; color: #7f8fa4; padding-left:30px; margin-left: 37px;white-space: nowrap;padding-top:10px">P</span>

                                            @endif





                                            </span>

                                            @endif

                                            @if($day['isRegularised']==true&&($day['status']=='CLP' ||$day['status']=='SL' ||$day['status']=='LOP'||$day['status']=='CL'||$day['status']=='ML'||$day['status']=='PL'||$day['status']=='MTL'||$day['status']=='L'))

                                            @php

                                            $Regularised=true;

                                            @endphp

                                            <span style="display:flex;text-align:start;width:10px;height:10px;border-radius:50%;padding-right: 10px; margin-right:25px;">

                                                <p class="me-2 mb-0">

                                                <div class="down-arrow-reg"></div>

                                                </p>

                                            </span>

                                            @endif

                                            @if(strtotime($formattedDate) >= strtotime(date('Y-m-d')))

                                            <span style="display: flex; text-align:end;width:10px;height:10px;border-radius:50%;padding-left: 60px; margin-right:12px;white-space: nowrap;">

                                                <p style="color: #a3b2c7;margin-top:30px;font-weight: 400;">{{$employee->shift_type}}</p>

                                            </span>

                                            @elseif($isCurrentMonth)

                                            <span style="display: flex; text-align:end;width:10px;height:10px;border-radius:50%;padding-left: 60px; margin-right:12px;white-space: nowrap;">

                                                <p style="color: #a3b2c7;margin-top:10px;font-weight: 400;">{{$employee->shift_type}}</p>

                                            </span>

                                            @endif

                                            @endif

                                    </div>

                                </div>

                                @endif

                                </td>



                                @endforeach

                        </tr>

                        @endforeach



                    </tbody>



                </table>

                @else

                <p>No calendar data available</p>

                @endif

            </div>



            <button class="accordion" wire:click="openlegend">Legends

                <span class="accordion-icon">

                    @if($legend)

                    &#x2796; <!-- Unicode for minus sign -->

                    @else

                    &#x2795; <!-- Unicode for plus sign -->

                    @endif

                </span>

            </button>

            <div class="panel" style="display: {{ $legend ? 'block' : 'none' }};">

                <div class="row m-0 mt-3 mb-3">

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon presentIcon">P</span>

                        </p>

                        <p class="legendtext m-0">Present</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon absentIcon">A</span>

                        </p>

                        <p class="legendtext m-0">Absent</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon offDayIcon">O</span>

                        </p>

                        <p class="legendtext m-0">Off Day</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon offDayIcon">R</span>

                        </p>

                        <p class="legendtext m-0">Rest Day</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon leaveIcon">L</span>

                        </p>

                        <p class="legendtext m-0">Leave</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon onDutyIcon">OD</span>

                        </p>

                        <p class="legendtext m-0">On Duty</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon holidayIcon">H</span>

                        </p>

                        <p class="legendtext m-0">Holiday</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon deductionIcon">&nbsp;&nbsp;</span>

                        </p>

                        <p class="legendtext m-0" style="word-break: break-all;"> Deduction</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon alertForDeIcon">&nbsp;&nbsp;</span>

                        </p>

                        <p class="legendtext m-0">Allert for Deduction</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <span class="legendsIcon absentIcon">?</span>

                        </p>

                        <p class="legendtext m-0">Status Unknown</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <i class="far fa-clock"></i>

                        </p>

                        <p class="legendtext m-0">Overtime</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="me-2 mb-0">

                            <i class="far fa-edit"></i>

                        </p>

                        <p class="legendtext m-0">Override</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="legendTriangleIcon me-2 mb-0">

                        <div class="down-arrow-ign-attendance-info"></div>

                        </p>

                        <p class="legendtext m-0">Ignored</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="legendTriangleIcon me-2 mb-0">

                        <div class="down-arrow-gra"></div>

                        </p>

                        <p class="legendtext m-0">Grace</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="legendTriangleIcon me-2 mb-0">

                        <div class="down-arrow-reg"></div>

                        </p>

                        <p class="legendtext m-0">Regularized</p>

                    </div>

                </div>

                <div class="row m-0 mb-3">

                    <h6 class="m-0 p-2 mb-2" style="background-color: #f1f4f7">Day Type</h6>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                           <i class="ph-coffee"></i>

                        </p>

                        <p class="m-1 pb-2 attendance-legend-text">Rest Day</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                           <i class="ph-television"></i>

                        </p>

                        <p class="m-1 attendance-legend-text" style="margin-bottom:14px;">Off Day</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                           <i class="ph-umbrella"></i>

                        </p>

                        <p class="m-1 pb-2 attendance-legend-text">Holiday</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                            <i class="ph-calendar"></i>



                        </p>

                        <p class="m-1  pb-2 attendance-legend-text">Half Day</p>

                    </div>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                            <i class="fas fa-battery-empty"></i>

                        </p>

                        <p class="m-1 attendance-legend-text">IT Maintanance</p>

                    </div>

                </div>

                <div class="row m-0 mb-3">

                    <h6 class="m-0 p-2 mb-2" style="background-color: #f1f4f7">Leave Type</h6>

                    <div class="col-md-3 mb-2 pe-0" style="display: flex">

                        <p class="mb-0">

                            <span class="legendsIcon sickleaveIcon">SL</span>

                        </p>

                        <p class="m-1 attendance-legend-text">Sick Leave</p>

                    </div>







                </div>

            </div>



        </div>



        <div class="col-md-5 custom-scrollbar-for-right-side-container" style="height: 600px;">

            <div class="container1" style="background-color:white;">

                <div class="row m-0">

                    <div class="col-2 pb-1 pt-1 p-0" style="border-right: 1px solid #ccc; text-align: center;">

                        <p class="mb-1" style="font-weight:bold;font-size:14px;color:#778899;">{{ \Carbon\Carbon::parse($currentDate2)->format('d') }}</p>

                        <p class="m-0" style="font-weight:600;font-size:12px;color:#778899;">{{ \Carbon\Carbon::parse($currentDate2)->format('D') }}</p>

                    </div>

                    <div class="col-5 pb-1 pt-1">

                        <p class="text-overflow mb-1" style="font-size:12px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-weight: 500;">10:00 am to 07:00 pm</p>

                        <p class="text-muted m-0" style="font-size:12px;">Shift:10:00 to 19:00</p>

                    </div>

                    <div class="col-5 pb-1 pt-1">

                        <p class="mb-1" style="font-size:12px;overflow: hidden;font-weight: 500;text-overflow: ellipsis;white-space: nowrap;font-weight: 500;">10:00 am to 07:00 pm</p>

                        <p class="text-muted m-0" style="font-size:12px;">Attendance Scheme</p>

                    </div>

                </div>

                <div class="horizontal-line-attendance-info"></div>

                @if($changeDate==1)

                @php

                $nextDayDate = \Carbon\Carbon::parse($CurrentDate)->addDay()->setTime(0, 0, 0);

                @endphp

                <div class="text-muted" style="margin-left:20px;font-weight: 400;font-size: 12px;">Processed On {{ $nextDayDate->format('jS M') }}</div>

                @else

                <div class="text-muted" style="margin-left:20px;font-weight: 400;font-size: 12px;">Processed On</div>

                @endif

                <div class="horizontal-line1-attendance-info"></div>



                <div class="table-responsive" style=" overflow-x: auto; max-width: 100%;">



                    <table>

                        <thead>

                            <tr>

                                <th style="font-weight:normal;font-size:12px;">First&nbsp;In</th>

                                <th style="font-weight:normal;font-size:12px;">Last&nbsp;Out</th>

                                <th style="font-weight:normal;font-size:12px;">Total&nbsp;Work&nbsp;Hrs</th>

                                <th style="font-weight:normal;font-size:12px;">Break&nbsp;Hrs</th>

                                <th style="font-weight:normal;font-size:12px;">Actual&nbsp;Work&nbsp;Hrs</th>

                                <th style="font-weight:normal;font-size:12px;">Work&nbsp;Hours&nbsp;in&nbsp;Shift&nbsp;Time</th>

                                <th style="font-weight:normal;font-size:12px;">Shortfall&nbsp;Hrs</th>

                                <th style="font-weight:normal;font-size:12px;">Excess&nbsp;Hrs</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td style="font-size:12px;">

                                    @if($changeDate==1)

                                    {{$this->first_in_time}}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td style="font-size:12px;">

                                    @if($changeDate==1)

                                    {{$this->last_out_time}}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td style="font-size:12px;">

                                    @if($this->first_in_time!=$this->last_out_time)

                                    {{str_pad($this->hours, 2, '0', STR_PAD_LEFT)}}:{{str_pad($this->minutesFormatted,2,'0',STR_PAD_LEFT)}}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td>-</td>

                                <td>-</td>

                                <td>{{$this->work_hrs_in_shift_time}}</td>

                                <td>{{$this->shortFallHrs}}</td>

                                <td>-</td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="container2">

                <h3 style="padding-left:10px;margin-top:10px;color: #7f8fa4;font-size:14px;">Status Details</h3>



                <div style=" overflow-x: auto; max-width: 100%;">

                    <table style="margin-top:-10px;">

                        <thead>

                            <tr>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">Status</th>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">Remarks</th>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;"></th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    @php





                                    $CurrentDate = $currentDate2;

                                    $swiperecord = App\Models\SwipeRecord::where('emp_id', $employeeIdForRegularisation)->where('is_regularized',1)->get(); // Example query



                                    if ($swiperecord && is_iterable($swiperecord)) {

                                    $swipeRecordExists = $swiperecord->contains(function ($record) use ($CurrentDate) {

                                    return \Carbon\Carbon::parse($record->created_at)->toDateString() === $CurrentDate;

                                    });

                                    } else {

                                    $swipeRecordExists = false;

                                    }

                                    @endphp



                                    @if($swipeRecordExists==true)

                                    <span style="font-size:12px;">Regularisation</span>

                                    @else

                                    -

                                    @endif

                                </td>

                                <td>-</td>

                                @if($swipeRecordExists==true)

                                <td>

                                    <button type="button" class="info-button" wire:click="checkDateInRegularisationEntries('{{$CurrentDate}}')">

                                        Info

                                    </button>

                                    @if($showRegularisationDialog==true)



                                    <div class="modal" tabindex="-1" role="dialog" style="display: block;">

                                        <div class="modal-dialog modal-dialog-centered" role="document">

                                            <div class="modal-content">



                                                <div class="modal-header" style="background-color: #eef7fa; height: 50px">

                                                    <h5 style="padding: 5px; color: #778899; font-size: 15px;" class="modal-title"><b>Regularisation&nbsp;&nbsp;Details</b></h5>

                                                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeRegularisationModal" style="background-color: white; height:10px;width:10px;">

                                                    </button>

                                                </div>

                                                <div class="modal-body" style="max-height:300px;overflow-y:auto">

                                                    <div class="row m-0 mt-3">



                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Status : <br /><span style="color: #000000;">Regularization</span></div>

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Regularized By: <br /><span style="color: #000000;">{{ucwords(strtolower($regularised_by))}}</span></div>

                                                    </div>

                                                    <div class="row m-0 mt-3">

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Regularized Date : <br /><span style="color: #000000;">{{ date('jS M,Y', strtotime($regularised_date)) }}</span></div>

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Regularized Time: <br /><span style="color: #000000;">{{ date('H:i:s', strtotime($regularised_date)) }}</span></div>

                                                    </div>

                                                    <div class="row m-0 mt-3">

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;"> Reason:<br /> <span style="color: #000000;">{{$regularised_reason}}</span></div>

                                                    </div>

                                                    <div style="display: flex; justify-content: center; margin-top: 20px;">

                                                        <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79);" wire:click="closeRegularisationModal">Close</button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-backdrop fade show blurred-backdrop"></div>



                                    @endif

                                </td>

                                @endif

                            </tr>

                            <!-- Add more rows with dashes as needed -->

                        </tbody>

                        <!-- Add table rows (tbody) and data here if needed -->

                    </table>

                </div>

            </div>

            <div class="container3">

                <h3 style="padding-left:10px;margin-top:20px;color: #7f8fa4;font-size:14px;">Session Details</h3>



                <div style=" overflow-x: auto; max-width: 100%;">

                    <table style="margin-top:-10px">

                        <thead>

                            <tr>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">Session</th>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">Session&nbsp;Timing</th>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">First&nbsp;In</th>

                                <th style="font-weight:normal;font-size:12px;padding-top:16px;">Last&nbsp;Out</th>



                            </tr>

                        </thead>



                        <tbody>



                            <tr style="border-bottom: 1px solid #ddd;">

                                <td style="font-weight:normal;font-size:12px;">Session&nbsp;1</td>

                                <td style="font-weight:normal;font-size:12px;">10:00 - 14:00</td>

                                <td style="font-weight:normal;font-size:12px;">

                                    @if($changeDate==1)

                                    {{$this->first_in_time}}

                                    @else

                                    -

                                    @endif

                                </td>

                                <td style="font-weight:normal;font-size:12px;">-</td>



                            </tr>

                            <tr style="border-bottom: 1px solid #ddd;">

                                <td style="font-weight:normal;font-size:12px;">Session&nbsp;2</td>

                                <td style="font-weight:normal;font-size:12px;">14:01 - 19:00</td>

                                <td style="font-weight:normal;font-size:12px;">-</td>

                                <td style="font-weight:normal;font-size:12px;">

                                    @if($changeDate==1)

                                    {{$this->last_out_time}}

                                    @else

                                    -

                                    @endif

                                </td>



                            </tr>

                            <!-- Add more rows with dashes as needed -->

                        </tbody>

                        <!-- Add table rows (tbody) and data here if needed -->

                    </table>

                </div>



            </div>

            <div class="container6">

                <h3 style="margin-left:20px;color: #7f8fa4;font-size:14px;margin-top:15px;align-items:center;">Swipe Details</h3>

                <div class="arrow-btn" style="float:right;margin-top:-30px;margin-right:20px;cursor:pointer;color:{{ $toggleButton ? '#3a9efd' : '#778899'}};border:1px solid {{ $toggleButton ? '#3a9efd' : '#778899'}}" wire:click="opentoggleButton">

                    <i class="fa fa-angle-{{ $toggleButton ? 'down' : 'up'}}" style="color: {{ $toggleButton ? '#3a9efd' : '#778899'}}"></i>

                </div>

                <div class="container-body" style="margin-top:2px;height:auto;border-top:1px solid #ccc;display: {{ $toggleButton ? 'block' : 'none' }};">

                    <!-- Content of the container body -->

                    <div class="table-responsive" style="max-width: 100%; text-align: center;">



                        <table>

                            @if ($SwiperecordsCount > 0)

                            <thead>



                                <tr>

                                    <th style="font-weight:normal;font-size:12px;">In/Out</th>

                                    <th style="font-weight:normal;font-size:12px;">Swipe&nbsp;Time</th>

                                    <th style="font-weight:normal;font-size:12px;">Location</th>

                                    <th></th>

                                </tr>

                            </thead>

                            <tbody>



                                @foreach ($swiperecordsfortoggleButton as $index =>$swiperecord)

                                <tr>

                                    <td style="font-weight:normal;font-size:12px;">{{ $swiperecord->in_or_out }}</td>

                                    <td>

                                        <div style="display:flex;flex-direction:column;">

                                            <p style="margin-bottom: 0;font-weight:normal;font-size:12px;white-space:nowrap;">

                                                {{ date('H:i:s A', strtotime($swiperecord->swipe_time)) }}

                                            </p>

                                            <p style="margin-bottom: 0;font-size: 10px;color: #a3b2c7;">

                                                {{ date('d M Y', strtotime($currentDate2)) }}

                                            </p>

                                        </div>

                                    </td>

                                    <td style="font-size:10px;">{{$this->city}},{{$this->country}},{{$this->postal_code}}</td>



                                    <td>

                                        <button class="info-button" wire:click="viewDetails('{{$swiperecord->id}}')">Info</button>

                                        @if($showSR==true)



                                    <div class="modal" tabindex="-1" role="dialog" style="display: block;">

                                        <div class="modal-dialog modal-dialog-centered" role="document">

                                            <div class="modal-content">



                                                <div class="modal-header" style="background-color: #eef7fa; height: 50px">

                                                    <h5 style="padding: 5px; color: #778899; font-size: 15px;" class="modal-title"><b>Swipe&nbsp;&nbsp;Details</b></h5>

                                                    <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeSWIPESR" style="background-color: white; height:10px;width:10px;">

                                                    </button>

                                                </div>

                                                <div class="modal-body" style="max-height:300px;overflow-y:auto">

                                                    <div class="row m-0 mt-3">



                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Employee Name: <br /><span style="color: #000000;">{{$view_student_first_name}}&nbsp;{{$view_student_last_name}}</span></div>

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Employee Id: <br /><span style="color: #000000;">{{$view_student_emp_id}}</span></div>

                                                    </div>

                                                    <div class="row m-0 mt-3">

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Swipe Date : <br /><span style="color: #000000;">{{\Carbon\Carbon::parse($swiperecord->created_at)->format('jS F, Y')}}</span></div>

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Swipe Time: <br /><span style="color: #000000;">{{$view_student_swipe_time}}</span></div>

                                                    </div>

                                                    <div class="row m-0 mt-3">

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">In/Out: <br /><span style="color: #000000;">{{$view_student_in_or_out}}</span></div>

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Access Card Number: <br /><span style="color: #000000;">-</span></div>

                                                    </div>

                                                    <div class="row m-0 mt-3">

                                                        <div class="col" style="font-size: 11px;color:#778899;font-weight:500;">Location:<br /> <span style="color: #000000;">NA</span></div>

                                                    </div>

                                                    <div style="display: flex; justify-content: center; margin-top: 20px;">

                                                        <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79);" wire:click="closeSWIPESR">Close</button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="modal-backdrop fade show blurred-backdrop"style="background-color: rgba(0, 0, 0, 0.05);"></div>



                                    @endif

                                    </td>



                                </tr>

                                @if (($index + 1) % 2 == 0)

                                <!-- Add a small container after every two records -->

                                <tr>

                                    <td colspan="4" style="height:1px; background-color: #f0f0f0; text-align: left;font-size:10px;">

                                        Actual Hrs:{{ $actualHours[($index + 1) / 2 - 1] }}</td>

                                </tr>



                                @endif



                                @endforeach





                                <!-- Add more rows with dashes as needed -->

                            </tbody>

                            <!-- Add table rows (tbody) and data here if needed -->

                            @else

                            <img src="https://linckia.cdn.greytip.com/static-ess-v6.3.0-prod-1543/attendace_swipe_empty.svg" style="margin-top:30px;">

                            <div class="text-muted">No record Found</div>

                            @endif

                        </table>



                    </div>



                </div>

            </div>

        </div>

    @endif

    @if($defaultfaCalendar==0)

        @livewire('hr-attendance-table', ['selectedEmployeeId' => $selectedEmployeeId])

       



    @endif  

</div>


                                </div>