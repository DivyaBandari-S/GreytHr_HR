<div class=" m-0 p-0">

  @php
  $notyetin=0;
  $lateArrival=0;
  $onTime=0;
  $onLeave=0;
  @endphp
  @foreach($Swipes as $s1)
  @php
  $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
  $isLateBy10AM = $swipeTime->format('H:i') > $s1->shift_start_time;
  $isEarlyBy10AM= $swipeTime->format('H:i') <= $s1->shift_start_time ; @endphp @if($isLateBy10AM) @php $notyetin++; $lateArrival++; @endphp @endif @if($isEarlyBy10AM) @php $onTime++; @endphp @endif @endforeach @php $CalculatePresentOnTime=($EarlySwipesCount/$TotalEmployees)*100; $CalculatePresentButLate=($LateSwipesCount/$TotalEmployees)*100; @endphp <div class="date-form-who-is-in">
    <input type="date" wire:model="from_date" wire:change="updateDate" class="form-control" id="fromDate" name="fromDate" style="color: #778899;margin-left: 12px;
    margin-top: 10px;">
</div>
<div class="shift-selector-container-who-is-in">
  <input type="text" class="shift-selector-who-is-in" placeholder="Select Shifts">
  <div class="arrow-who-is-in" style="cursor:pointer;" wire:click="openSelector"></div>
</div>
@if($openshiftselector==true)
<div class="modal" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #eef7fa; height: 50px">
        <h5 style="padding: 5px; color: #778899; font-size: 15px;" class="modal-title"><b>Shift</b></h5>
        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="closeShiftSelector" style="background-color: white; height:10px;width:10px;">
        </button>
      </div>
      <div class="modal-body" style="max-height:300px;overflow-y:auto">
        <div class="toggle-box-container-who-is-in">
          <span style="margin-right: 10px;margin-top:-5px;font-size:12px;color:#778899">
            @if($isToggled)
            Active(1)
            @else
            All(1)
            @endif
          </span>
          <label class="switch-who-is-in">
            <input type="checkbox" wire:click="toggle" {{ $isToggled ? 'checked' : '' }}>
            <span class="slider round"></span>
          </label>
        </div>
        
        <div class="wide-short-container-who-is-in">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <label class="checkbox-label-who-is-in">
              <input type="radio"wire:model="selectedShift" value="GS">
              10:00 Am to 07:00 Pm(GS)
            </label>
            <span class="total-employee-count-who-is-in">{{$dayShiftEmployeesCount}} employee(s)</span>
          </div>
          <div class="time-range-who-is-in">
            <span class="start-time-who-is-in">10:00</span>
            <hr class="time-separator-who-is-in">
            <span class="end-time-who-is-in">19:00</span>
          </div>

        </div>
        <div class="wide-short-container-who-is-in">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <label class="checkbox-label-who-is-in">
              <input type="radio"wire:model="selectedShift" value="AS">
              02:00 Pm to 11:00 Pm(AS)
            </label>
            <span class="total-employee-count-who-is-in">{{$afternoonShiftEmployeesCount}} employee(s)</span>
          </div>
          <div class="time-range-who-is-in">
            <span class="start-time-who-is-in">02:00</span>
            <hr class="time-separator-who-is-in">
            <span class="end-time-who-is-in">11:00</span>
          </div>

        </div>
        <div class="wide-short-container-who-is-in">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <label class="checkbox-label-who-is-in">
              <input type="radio"wire:model="selectedShift" value="ES">
              05:00 Pm to 01:00 Am(ES)
            </label>
            <span class="total-employee-count-who-is-in">{{$eveningShiftEmployeesCount}} employee(s)</span>
          </div>
          <div class="time-range-who-is-in">
            <span class="start-time-who-is-in">05:00</span>
            <hr class="time-separator-who-is-in">
            <span class="end-time-who-is-in">01:00</span>
          </div>

        </div>
        <!-- Collapsible Content -->
        <div class="text-center" style="margin-top: 70px;">
          <button type="button" class="btn save-selectshift-button-who-is-in"wire:click="checkShiftForEmployees">Save</button>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="modal-backdrop fade show blurred-backdrop"></div>
@endif
<div class="cont m-0 p-0 " style="display:flex; justify-content: end;">
  <div class="search-container-who-is-in">

    <div class="form-group-who-is-in">
      <div class="search-input-who-is-in">
        <input wire:model="search" type="text" placeholder="Search Employee" class="search-text"style="font-size: 12px;">
        <div class="search-icon-who-is-in" wire:click="searchFilters">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
      </div>
    </div>



  </div>

</div>
<div class="mx-1 p-0">
  <div class="container-box-for-employee-information-who-is-in">
    <!-- Your content goes here -->
    <div style="margin-top:5px;display:flex;align-items:center; text-align:center;justify-content:center;padding:0;">
      <p style="text-align:center;font-size:14px;">Employees Information for <span style="font-weight: 500; ">{{\Carbon\Carbon::parse($currentDate)->format('jS F Y')}}</span></p>
    </div>

    <div class="content-who-is-in">
      <div class="col-md-5 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 500;font-size:14px;">{{number_format($CalculateAbsentees,2)}}%</div>

        @if($employeesCount1>0)
        <div class="employee-count-who-is-in">{{$employeesCount1}}&nbsp;Employee(s)&nbsp;are&nbsp;Absent</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;Absent</div>
        @endif
      </div>
      <div class="col-md-5 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 500;font-size:14px;">{{number_format($CalculatePresentButLate,2)}}%</div>
        @if($LateSwipesCount>0)
        <div class="employee-count-who-is-in">{{$LateSwipesCount}}&nbsp;Employee(s)&nbsp;are&nbsp;Late&nbsp;In</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;Late&nbsp;In</div>
        @endif
      </div>
      <div class="col-md-5 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 500;font-size:14px;">{{number_format($CalculatePresentOnTime,2)}}%</div>
        @if($EarlySwipesCount>0)
        <div class="employee-count-who-is-in">{{$EarlySwipesCount}}&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Time</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Time</div>
        @endif

      </div>
      <div class="col-md-5 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 500;font-size:14px;">{{number_format($CalculateApprovedLeaves,2)}}%</div>

        @if($ApprovedLeaveRequestsCount>0)
        <div class="employee-count-who-is-in">{{$ApprovedLeaveRequestsCount}}&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Leave</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Leave</div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- containers for attendace -->
<div class="row m-0 p-0" style=" display:flex;">

  <div class="col-md-3">
    <div class="container5-who-is-in-absent">
      <div class="heading-who-is-in">
        <h3>Absent&nbsp;({{ str_pad($absentEmployeesCount, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForAbsent" style="cursor:pointer;"></i>

      </div >
        <div class="table-responsive">
        <table class="who-is-in-table-for-late-employee" style="width: 100%;">
          <thead>
            <tr>
            <th>Employee</th>
            <th>Expected InTime</th>
            <th></th>
           
            </tr>
          </thead>
          <tbody>
            @if($notFound)
            <tr>
              <td colspan="2" style="text-align: center;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;">
                <p style="font-weight: normal; font-size: 12px; color:#778899;margin-top:5px;">No employees are absent today</p>
              </td>
            </tr>
            @else
            @foreach($Employees1 as $index=>$e1)
            <tr class="row-for-absent-employee">
              <td style="font-size:10px;font-weight:700;padding-left: 15px;max-width:120px;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;"data-toggle="tooltip"
              data-placement="top" title="{{ ucwords(strtolower($e1->first_name)) }} {{ ucwords(strtolower($e1->last_name)) }}">
                {{ ucwords(strtolower($e1->first_name)) }} {{ ucwords(strtolower($e1->last_name)) }}<br />
                <span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$e1->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;">{{$e1->shift_start_time}}</td>
              
              <td style="text-align:right;">
                     <button class="arrow-btn" style="background-color:#fff;float:right;margin-top:-2px;margin-right:20px;cursor:pointer;color:{{ in_array($index, $openAccordionsForAbsentees) ? '#3a9efd' : '#778899' }};border:1px solid {{ in_array($index, $openAccordionsForAbsentees) ? '#3a9efd' : '#778899'}}" wire:click="toggleAccordionForAbsent({{ $index }})">
                          <i class="fa fa-angle-{{ in_array($index, $openAccordionsForAbsentees)? 'down' : 'up' }}"style="color:{{ in_array($index, $openAccordionsForAbsentees) ? '#3a9efd' : '#778899' }}"></i>
                    </button>
                </td>

            </tr>
            @if( in_array($index, $openAccordionsForAbsentees))
            <tr class="row-for-absent-employee">
                <td colspan="4">
                    <div>
                        <!-- Add more details here -->
                        <div style="margin: 10px;padding: 10px;width: 90%;height: 60px;background-color: #f0f0f0; margin-top: 10px; ">
                            <div style="font-size: 10px;">
                              <span>{{ \Carbon\Carbon::parse($e1->shift_start_time)->format('h:i A') }} to
                              {{ \Carbon\Carbon::parse($e1->shift_end_time)->format('h:i A') }}</span>
                            </div>  
                              <div style="font-size: 8px; margin-top: 5px;">
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($e1->shift_start_time)->format('H:i') }}</span>
                                        <span class="time-separator-who-is-in"style="display: inline-block;width:60%;"></span>
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($e1->shift_end_time)->format('H:i') }}</span>
                              </div>
                        </div>
                        <p style="font-size:12px;font-weight:700;padding-left: 10px;">Contact Details</p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Email&nbsp;ID:&nbsp;&nbsp;<span style="font-weight:500;">{{$e1->email}}</span></p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Phone Number:&nbsp;&nbsp;<span style="font-weight:500;">{{$e1->emergency_contact}}</span></p>
                        <p style="font-size:12px;font-weight:700;padding-left: 10px;">Categories Details</p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Designation:&nbsp;&nbsp;<span style="font-weight:500;">{{$e1->job_role}}</span></p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Location:&nbsp;&nbsp;<span style="font-weight:500;">{{$e1->job_location}}</span></p>
                       
                    </div>
                </td>
            </tr>
            @endif
          
            @endforeach
            @endif
          </tbody><!-- Add table rows (tbody) and data here if needed -->
        </table>
        </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="container5-who-is-in">
      <div class="heading-who-is-in">
        <h3>Late&nbsp;Arrivals&nbsp;({{ str_pad($lateArrival, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForLateArrivals" style="cursor:pointer;"></i>

      </div>

      <div class="table-responsive">
        <table class="who-is-in-table-for-late-employee" style="width:100%;">
          
          <thead>
            <tr>
              <th>Employee</th>
              <th>Late By</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if($lateArrival > 0)
            @foreach($Swipes as $index=>$s1)

            @php
            $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
            $lateArrivalTime = $swipeTime->diff(\Carbon\Carbon::parse($s1->shift_start_time))->format('%H:%I:%S');
            $isLateBy10AM = $swipeTime->format('H:i') > $s1->shift_start_time;
            @endphp

            @if($isLateBy10AM)

            <tr class="row-for-absent-employee">
              <td style="font-size:10px;font-weight:700;max-width:110px;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;padding-left:15px;"data-toggle="tooltip"
              data-placement="top" title="{{ ucwords(strtolower($s1->first_name)) }} {{ ucwords(strtolower($s1->last_name)) }}">
                @php
                $firstNameParts = explode(' ', strtolower($s1->first_name));
                $lastNameParts = explode(' ', strtolower($s1->last_name));
                @endphp

                @foreach($firstNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach

                @foreach($lastNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach

                <br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$s1->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;padding-left:12px;text-align: center;">{{$lateArrivalTime}}<br /><span class="text-muted" style="font-size:10px;font-weight:300;">{{$s1->swipe_time}}</span></td>
              <td style="text-align:right;">
              <button class="arrow-btn" style="background-color:#fff;cursor:pointer;color:{{ in_array($index, $openAccordionForLateComers) ? '#3a9efd' : '#778899' }};border:1px solid {{ in_array($index, $openAccordionForLateComers) ? '#3a9efd' : '#778899'}}" wire:click="toggleAccordionForLate({{ $index }})">
                          <i class="fa fa-angle-{{ in_array($index, $openAccordionForLateComers) ? 'down' : 'up' }}"style="color:{{ in_array($index, $openAccordionForLateComers) ? '#3a9efd' : '#778899' }}"></i>
                    </button>
                </td>
            </tr>
            @endif
            @if(in_array($index, $openAccordionForLateComers))
            <tr class="row-for-absent-employee">
                <td colspan="4">
                    <div>
                        <!-- Add more details here -->
                        <div style="margin: 10px;padding: 10px;width: 90%;height: 60px;background-color: #f0f0f0; margin-top: 10px; ">
                            <div style="font-size: 10px;">
                              <span>{{ \Carbon\Carbon::parse($s1->shift_start_time)->format('h:i A') }} to 
                              {{ \Carbon\Carbon::parse($s1->shift_end_time)->format('h:i A') }}</span>
                            </div>  
                              <div style="font-size: 8px; margin-top: 5px;">
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($s1->shift_start_time)->format('H:i') }}</span>
                                        <span class="time-separator-who-is-in"style="display: inline-block;width:60%;"></span>
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($s1->shift_end_time)->format('H:i') }}</span>
                              </div>
                        </div>
                        <p style="font-size:12px;font-weight:700;padding-left: 10px;">Contact Details</p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Email&nbsp;ID:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->email}}</span></p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Phone Number:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->emergency_contact}}</span></p>
                        <p style="font-size:12px;font-weight:700;padding-left: 10px;">Categories Details</p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Designation:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->job_role}}</span></p>
                        <p style="font-size:10px;font-weight:600;padding-left: 10px;">Location:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->job_location}}</span></p>
                        
                    </div>
                </td>
            </tr>
            @endif
  
            

            @endforeach
             @else

          <tr>
            <td colspan="2" style="text-align: center;margin-top:40px;margin-left:60px;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No employees are late today</p>
            </td>
          </tr>

          @endif
          </tbody><!-- Add table rows (tbody) and data here if needed -->
         
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="container5-who-is-in">
      <div class="heading-who-is-in">
        <h3>On&nbsp;Time&nbsp;({{ str_pad($onTime, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForEarlyArrivals" style="cursor:pointer;"></i>

      </div>

      <div class="table-responsive">
        <!-- <table class="who-is-in-table-for-early-employee" style="width:100%;"> -->
        <table class="who-is-in-table-for-late-employee" style="width:100%;">
        
        <thead>
            <tr>
              <th style="padding-right:30px;">Employee</th>
              <th>Early By</th>
              <th></th>
            </tr>
          </thead>

         
          <tbody>
             @if($onTime > 0)
            @foreach($Swipes as $index=>$s1)
            @php
            $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
            $earlyArrivalTime = $swipeTime->diff(\Carbon\Carbon::parse($s1->shift_start_time))->format('%H:%I:%S');
            $isEarlyBy10AM = $swipeTime->format('H:i') <= $s1->shift_start_time ; 
            @endphp 
            @if($isEarlyBy10AM) 
            <tr class="row-for-absent-employee">
              <td style="font-size:10px;font-weight:700;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;max-width:100px;"data-toggle="tooltip"
              data-placement="top" title="{{ ucwords(strtolower($s1->first_name)) }} {{ ucwords(strtolower($s1->last_name)) }}">{{ ucwords(strtolower($s1->first_name)) }} {{ ucwords(strtolower($s1->last_name)) }}<br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$s1->emp_id}}</span></td>
              <td style="font-weight:700;font-size:10px;text-align: center;">{{$earlyArrivalTime}}<br /><span class="text-muted" style="font-size:10px;font-weight:300;">{{$s1->swipe_time}}</span></td>
              <td style="text-align:right;">
              <button class="arrow-btn" style="background-color:#fff;float:right;margin-top:-2px;margin-right:20px;cursor:pointer;color:{{ in_array($index, $openAccordionForEarlyComers) ? '#3a9efd' : '#778899' }};border:1px solid {{ in_array($index, $openAccordionForEarlyComers) ? '#3a9efd' : '#778899'}}" wire:click="toggleAccordionForEarly({{ $index }})">
                          <i class="fa fa-angle-{{ in_array($index, $openAccordionForEarlyComers) ? 'down' : 'up' }}"style="color:{{ in_array($index, $openAccordionForEarlyComers) ? '#3a9efd' : '#778899' }}"></i>
                    </button>
                </td>
            </tr>

              @endif
              @if(in_array($index, $openAccordionForEarlyComers))
            <tr class="row-for-absent-employee">
                <td colspan="4">
                    <div>
                    <div style="height: 50px; background-color: #f0f0f0; padding: 10px; margin:10px;   width: 85%;">
                            <div style="font-size: 10px;">
                              <span>{{ \Carbon\Carbon::parse($s1->shift_start_time)->format('h:i A') }} to 
                              {{ \Carbon\Carbon::parse($s1->shift_end_time)->format('h:i A') }}</span>
                            </div>  
                              <div style="font-size: 8px; margin-top: 5px;">
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($s1->shift_start_time)->format('H:i') }}</span>
                                        <span class="time-separator-who-is-in"style="display: inline-block;width:60%;"></span>
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($s1->shift_end_time)->format('H:i') }}</span>
                              </div>
                        </div>
                        <!-- Add more details here -->
                        <p style="font-size:12px;font-weight:700;">Contact Details</p>
                        <p style="font-size:10px;font-weight:600;">Email&nbsp;ID:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->email}}</span></p>
                        <p style="font-size:10px;font-weight:600;">Phone Number:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->emergency_contact}}</span></p>
                        <p style="font-size:12px;font-weight:700;">Categories Details</p>
                        <p style="font-size:10px;font-weight:600;">Designation:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->job_role}}</span></p>
                        <p style="font-size:10px;font-weight:600;">Location:&nbsp;&nbsp;<span style="font-weight:500;">{{$s1->job_location}}</span></p>
                        
                    </div>
                </td>
            </tr>
            @endif

              @endforeach
              @else
          <tr>
            <td colspan="2 mt-2" style="text-align: center;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px;">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No&nbsp;employees&nbsp;are&nbsp;on&nbsp;time&nbsp;today</p>
            </td>
          </tr>
          @endif
          </tbody><!-- Add table rows (tbody) and data here if needed -->
     
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="container5-who-is-in">
      <div class="heading-who-is-in">
        <h3>On&nbsp;Leave&nbsp;({{ str_pad($employeesOnLeaveCount, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForLeave" style="cursor: pointer;"></i>

      </div>

      <div class="table-responsive">
      <table class="who-is-in-table-for-late-employee" style="width:100%;">
        
        <thead>
            <tr>
              <th>Employee</th>
              <th>Number of days</th>
              <th></th>
            </tr>
          </thead>

         
          <tbody>
          @if($notFound3)
            <tr>
              <td colspan="2" style="text-align: center;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;">
                <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No employees are on leave today</p>
              </td>
            </tr>
 
            @else
            @if($ApprovedLeaveRequestsCount > 0)
 
            @foreach($ApprovedLeaveRequests as $index=>$alr)
 
 
            <tr class="row-for-absent-employee"style="margin: 10px;">
              <td style="font-size:10px;font-weight:700;padding-left: 15px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;max-width:120px;"data-toggle="tooltip"
              data-placement="top" title="{{ ucwords(strtolower($alr->first_name)) }} {{ ucwords(strtolower($alr->last_name)) }}">
                @php
                $firstNameParts = explode(' ', strtolower($alr->first_name));
                $lastNameParts = explode(' ', strtolower($alr->last_name));
                @endphp
                @foreach($firstNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach
 
                @foreach($lastNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach
                <br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$alr->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;padding-left: 15px;">{{$alr->number_of_days}} Day(s)<br />
                <div style="background-color: rgb(176, 255, 176); border: 1px solid green; color: green;border-radius:15px; padding: 2px; text-align: center;">
                  Approved
                </div>
              </td>
              <td style="text-align:right;">
                    <button class="arrow-btn" style="background-color:#fff;float:right;margin-top:-2px;margin-right:20px;cursor:pointer;color:{{ in_array($index, $openAccordionsForEmployeesOnLeave) ? '#3a9efd' : '#778899' }};border:1px solid {{ in_array($index, $openAccordionsForEmployeesOnLeave) ? '#3a9efd' : '#778899'}}" wire:click="toggleAccordionForLeave({{ $index }})">
                          <i class="fa fa-angle-{{ in_array($index, $openAccordionsForEmployeesOnLeave) ? 'down' : 'up' }}"style="color:{{ in_array($index, $openAccordionsForEmployeesOnLeave) ? '#3a9efd' : '#778899' }}"></i>
                    </button>
                </td>
            </tr>
            @if(in_array($index, $openAccordionsForEmployeesOnLeave))
            <tr class="row-for-absent-employee">
                <td colspan="4">
                    <div>
                        <!-- Add more details here -->
                        <p style="font-size:10px;display:flex;font-weight:600;">Leave Dates:<span style="margin-left:5px;font-weight:500;">
                        {{ implode(', ', array_map(function($date) {
                                      return \Carbon\Carbon::parse($date)->format('jS M Y');
                                  }, $alr->leave_dates)) }}

                        </span></p> 
                        <p style="font-size:10px;font-weight:600;">Leave Type:<span style="margin-left:5px;font-weight:500;">{{$alr->leave_type}}</span></p> 
                        <div style="height: 50px; background-color: #f0f0f0; padding: 5px; margin-top: 10px; width: 100%;">
                            <div style="font-size: 8px;">
                              <span style="font-size: 8px;">{{ \Carbon\Carbon::parse($alr->shift_start_time)->format('h:i A') }} to 
                              {{ \Carbon\Carbon::parse($alr->shift_end_time)->format('h:i A') }}</span>
                            </div>  
                              <div style="font-size: 8px; margin-top: 5px;">
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($alr->shift_start_time)->format('H:i') }}</span>
                                        <span class="time-separator-who-is-in"style="display: inline-block;width:60%;"></span>
                                        <span style="display: inline-block;">{{ \Carbon\Carbon::parse($alr->shift_end_time)->format('H:i') }}</span>
                              </div>
                        </div>
                        <p style="font-size:12px;font-weight:700;">Contact Details</p>
                        <p style="font-size:10px;font-weight:600;">Email&nbsp;ID:<span style="margin-left:5px;font-weight:500;">{{$alr->email}}</span></p>
                        <p style="font-size:10px;font-weight:600;">Phone Number:<span style="margin-left:5px;font-weight:500;">{{$alr->emergency_contact}}</span></p>
                        <p style="font-size:12px;font-weight:700;">Categories Details</p>
                        <p style="font-size:10px;font-weight:600;">Designation:<span style="margin-left:5px;font-weight:500;">{{$alr->job_role}}</span></p>
                        <p style="font-size:10px;font-weight:600;">Location:<span style="margin-left:5px;font-weight:500;">{{$alr->job_location}}</span></p>
                        
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
            @else
          <tr>
            <td colspan="2" style="text-align: center;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px;">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No employees are on leave&nbsp;today</p>
            </td>
          </tr>
          @endif
            @endif
          </tbody><!-- Add table rows (tbody) and data here if needed -->
     
        </table>
 
      </div>
    </div>
  </div>
</div>

<!-- third col -->

<script>
  document.addEventListener('livewire:load', function() {
    Livewire.on('updatePlaceholder', value => {
      const input = document.getElementById('fromDate');
      if (value) {
        input.setAttribute('placeholder', value);
      } else {
        input.setAttribute('placeholder', 'Select Date Range');
      }
    });
  });
</script>


</div>