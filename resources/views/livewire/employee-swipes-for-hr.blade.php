<div>

    <body>
        <div>
            <div class="employee-swipes-fields d-flex align-items-center">
                <div class="dropdown-container1-employee-swipes">
                    <label for="start_date" style="color: #666;font-size:12px;">Start Date<span style="color: red;">*</span>:</label><br />
                    <input type="date" style="font-size: 12px;" id="start_date" wire:model="startDate" wire:change="checkDates">
                </div>
                <div class="dropdown-container1-employee-swipes">
                    <label for="end_date" style="color: #666;font-size:12px;">End Date<span style="color: red;">*</span>:</label><br />
                    <input type="date" style="font-size: 12px;" id="end_date" wire:model="endDate" wire:change="checkDates">
                </div>
                <div class="dropdown-container1-employee-swipes-for-date-type">
                    <label for="dateType" style="color: #666;font-size:12px;">Date Type<span style="color: red;">*</span>:</label><br />
                    <button class="dropdown-btn1" style="font-size: 12px;">Swipe Date</button>
                    <div class="dropdown-content1-employee-swipes">

                    </div>
                </div>
                <div class="dropdown-container1-employee-swipes-for-search-employee">
                    <label for="dateType" style="color: #666;font-size:12px;">Employee Search</label><br />

                    <div class="search-input-employee-swipes">
                        <div class="search-container" style="position: relative;">
                            <i class="fa fa-search search-icon-employee-swipes" aria-hidden="true" style="cursor:pointer;" wire:click="searchEmployee"></i>
                            <input wire:model="search" type="text" placeholder="Search Employee" class="search-text">

                        </div>

                    </div>
                </div>
                <div class="dropdown-container1-employee-swipes-for-download-and-filter d-flex">
                    <div class="dropdown-container1-employee-swipes">

                        <button type="btn" class="button2" data-toggle="modal" data-target="#exampleModalCenter">
                            <i class="fa-solid fa-download" wire:click="downloadFileforSwipes"></i>
                        </button>

                    </div>
                    <div class="dropdown-container1-employee-swipes">

                        <button type="button" class="button2" data-toggle="modal" data-target="#exampleModalCenter" style="margin-top:30px;border-radius:2px;">
                            <i class="fa-icon fas fa-filter" style="color:#666"></i>
                        </button>

                    </div>
                </div>
            </div>

    <div class="row m-0 p-0  mt-4" >
        <div class="col-md-9 mb-4" >
           <div class="bg-white border rounded">
             <div class="table-responsive bg-white rounded p-0 m-0">
                <table class="employee-swipes-table  bg-white" style="width: 100%;padding-right:10px;">
                    <thead>
                        <tr>
                            <th>Employee&nbsp;Name</th>
                            <th>Swipe&nbsp;Time&nbsp;&&nbsp;Date</th>
                            <th>Shift</th>
                            <th>In/Out</th>
                            <th>Received&nbsp;On</th>
                            <th>Door/Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <div>
                    <tbody>
                  
                         
                   
    <!-- Display the filtered collection or any other content -->
                    
                        
        <!-- Display swipe details -->
                       
                       <tr class="employee-swipes-table-container">
                              <td  class="employee-swipes-name-and-id">
                              <input type="checkbox" name="employeeCheckbox[]" class="employee-swipes-checkbox">
                                        <span style="width:100px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"data-toggle="tooltip"
                                        data-placement="top" title="{{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}">
                                               {{ ucwords(strtolower($swipe['employee']->first_name)) }} {{ ucwords(strtolower($swipe['employee']->last_name)) }}
                                        </span>

                                                <br />
                                                <span class="text-muted employee-swipes-emp-id">#{{$swipe['employee']->emp_id}}</span>
                                            </td>
                                            
                                            <td class="employee-swipes-swipe-details-for-signed-employees"> 10:00:00<br /> <span class="text-muted employee-swipes-swipe-date">  <span class="text-muted employee-swipes-swipe-date"> {{ \Carbon\Carbon::parse($swipe['swipe_log1']->created_at)->format('jS F Y')}}</span></td>
                                            
                                            <td class="employee-swipes-swipe-details-for-signed-employees">10:00 am to 19:00 pm</td>
                                         
                                            <td class="employee-swipes-swipe-details-for-signed-employees" style="text-transform:uppercase;">IN</td>
                                            
                                           

                                            
                                            <td class="employee-swipes-swipe-details-for-signed-employees">Swipe Time<br /> <span class="text-muted employee-swipes-swipe-date">23rd October,1999</span></td>
                                            
                                            <td class="empty-text">-</td>
                                            <td class="empty-text">-</td>
                                        </tr>
                                      
                                       
                                    
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="green-and-white-section-for-employee-swipes col-md-3 p-0 bg-white rounded border">
                    <div class="green-section-employee-swipes p-2">
                        <img src="https://cdn-icons-png.flaticon.com/512/2055/2055568.png"
                            class="container-employee-swipes-right-image">
                        <h6>Swipe-in Time</h6>
                        
                        <p>Not Swiped Yet</p>
                        
                    </div>
                    <h2 class="swipe-details-who-is-in p-2">Swipe Details</h2>
                    <hr class="swipe-details-who-is-in-horizontal-row">
                    <div class="p-2">
                        <p class="swipe-deatils-title">Device Name</p>
                        <p class="swipe-details-description"></p>
                        <p class="swipe-deatils-title">Access Card</p>
                        <p class="swipe-details-description">-</p>
                        <p class="swipe-deatils-title">Door/Address</p>

                        <p class="swipe-details-description">-</p>

                        <p class="swipe-deatils-title">Remarks</p>
                        <p class="swipe-details-description">-</p>
                        <p class="swipe-deatils-title">Device ID</p>
                        <p class="swipe-details-description">-</p>
                        <p class="swipe-deatils-title">Location Details</p>
                        <p class="swipe-details-description">-</p>

                    </div>
                </div>
            </div>

        </div>


    </body>
</div>