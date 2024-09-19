<div>

    <div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
             .attendance-overview-help {
                position: relative;
                width: 85%; /* Set your desired width */
                height: auto; /* Set your desired height */
                border-radius: 5px; /* Set your desired border-radius */
                border: 1px solid #ccc; /* Add border if needed */
                padding: 10px; /* Add padding if needed */
                margin: 20px 10px; /* Add margin if needed */
                background-color: #f3faff; /* Set background color if needed */
                font-size: 12px;
            }
            .hide-attendance-help {
                margin-top:40px;
                position: absolute;
                bottom: 80px;
                right: 10px;
                color: #0000FF;
                font-weight:500;
                cursor: pointer;
            }
            .with-white-background {
            background-color: white;
            border-radius: 5px;
            width: 85%; /* Set your desired width */
            height: auto;
            padding: 20px;
            margin: 20px 10px; /* Set white background color for the row */
        }
        .custom-list {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }
    
    .custom-list li {
        padding-left: 20px;
        position: relative;
        margin-bottom: 8px;
        line-height: 1.5;
    }
    
    .custom-list li::before {
        content: '\2022'; /* Bullet character */
        color: #3498db; /* Change the color as needed */
        font-size: 3em;
        position: absolute;
        left: 0;
        top: 50%; /* Adjust the top property to move the bullet upwards */
        transform: translateY(-50%); /* Center the bullet vertically */
    }
    
    .custom-list li:nth-child(2)::before {
        color: #2ecc71; /* Change the color for the second item */
    }
    
    .custom-list li:nth-child(3)::before {
        color: #e74c3c; /* Change the color for the third item */
    }
        </style>    
        @if($showHelp==false)
          
               <div class="attendance-overview-help">
                    
                    <p>On the<span style="font-weight: bold;"> Attendance Overview</span> page,get an overview of your teams' attendance information. Get quick answers to questions<br/> such as Number of work hours completed by the team in a month,Summary of work hours,Who is in,and Access card details.</p>
                    <p>To view frequently asked questions <span style="color: #1fb6ff;cursor:pointer;"> click here</span>.</p>
                    <span class="hide-attendance-help"  wire:click="hideHelp">Hide Help</span>
                </div>
        @else
          
       
        <button style="background-color: white; margin-top: -20px; float: right; color: #0000FF; border: 1px solid #ffff; border-radius: 5px; cursor: pointer; padding: 10px 20px;font-weight:bold;"wire:click="showhelp">Show&nbsp;&nbsp;Help</button>
           
        @endif
        <div class="row with-white-background">
        <!-- Heading above the row -->
       
                <div class="col-sm-12">
                    
                        <h4 style="text-align:left;font-weight:600;font-size:14px;">Access Card Details
                                                        <button style="border: 1px solid #0000FF; color: #0000FF; float:right; background-color: transparent; padding: 5px; border-radius: 5px; font-size: 13px;margin-top:-10px;"wire:click="downloadexcelForNotAssigned">
                                                           <i class="fas fa-download"></i>  Download  
                                                        </button>
                        </h4>
                </div>
                <div class="row"style="justify-content:space-between;">
                            <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;">
                                        <h4 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);font-size:14px;">Not Assigned</h4>
                    <!-- Table for the first container goes here -->
                                                    <div style="overflow-y:auto;max-height:210px;overflow-x:hidden;">
                                                        <table class="table">
                                                            <tbody style="overflow-y:auto;background-color:pink;">
                                                            @foreach($totalemployees as $te)    
                                                            <tr>
                                                                <td>
                                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNWoZhTRyuOwc2TBBgSHMzxK1Oj4KQInvuMBCSGeMJCNnGoRaH_RExpbQ5RaMJPxibMjQ&usqp=CAU" alt="User Icon" style="width: 30px; height: 30px; border-radius: 50%;margin-top:10px;">
    
                                                                </td>
                                                                <td style="vertical-align: middle;padding-right:220px;">
                                                                    <span style="color: rgba(103,122,142,1);font-size:10px;">{{ ucwords(strtolower($te->first_name))}}&nbsp;{{ucwords(strtolower($te->last_name))}}  <br/>({{$te->emp_id}})</span><!-- Replace with the actual name -->
                                                                </td>
                        
                                                            </tr>   
                                                            @endforeach
                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>   
                            </div>
                <!-- Second Container -->
                            <div class="col-sm-6"style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-right:-40px;">
                                <h4 style="text-align:left; font-weight:600;color: rgba(103,122,142,1);border-bottom: 1px solid #dddddd; padding-bottom: 5px;font-size:14px;">Validity Expired</h4>
                                <div style="text-align: center; margin-top: 100px;">
                                    <p style="color: rgba(103,122,142,1);font-size:14px;">No records found</p>
                                </div>
                                <!-- Content for the second container goes here -->
                            </div>
                </div>
        </div>
    </div>
    <div class="row with-white-background"style="background-color:white;">
        <!-- Heading above the row -->
        
                
                <div class="row"style="justify-content:space-between;">
                <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;">
                                        <h6 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);">Who's In?</h6>
                    <!-- Table for the first container goes here -->
                           <div style="display:flex;flex-direction:row;justify-content:space-between">
                                                        <div class="input-group" style="width: 200px;">
                                                                <select class="form-control">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="fas fa-caret-down"></i>
                                                                            </span>
                                                                        </div>
                                                                    <option value="all">Date: 14 Feb 2024</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                                
                                                        </div>   
                                            <div class="input-group" style="width: 200px;">
                                                        <select class="form-control">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-caret-down"></i>
                                                                    </span>
                                                                </div>
                                                            <option value="all">Category: All</option>
                                                            <!-- Add more options as needed -->
                                                        </select>
                                                        
                                            </div>  
                                               
                           </div>   
                           
                           <ul class="custom-list">
                                <li><p style="color:rgba(103,122,142,1);">Not Yet In:<span style="font-weight:bold;color:black;margin-left: 5px;">{{$absentemployeescount}}</span></p></li>
                                <li><p style="color:rgba(103,122,142,1);">On Time:<span style="font-weight:bold;color:black;margin-left: 5px;">{{$earlyemployeescount}}</span></p></li>
                                <li><p style="color:rgba(103,122,142,1);">Late In:<span style="font-weight:bold;color:black;margin-left: 5px;">{{$lateemployeescount}}</span></p></li>
                           </ul>
                                             
                </div>
                <div class="col-sm-6 mb-3" style="border: 1px solid #dddddd; padding: 5px; border-radius: 5px;margin-right:-40px;">
                                                        <button style="border: 1px solid #0000FF; color: #0000FF; float:right; font-weight:bold;background-color: transparent; padding: 5px; border-radius: 5px; font-size: 13px;"wire:click="downloadexcelForAttendanceType">
                                                           <i class="fas fa-download"></i>  Download  
                                                        </button>
                                        <h4 style="text-align:left; font-weight:600; color: rgba(103,122,142,1);font-size:14px;">Attendance Type</h4>
                                        
                    <!-- Table for the first container goes here -->
                           <div style="display:flex;flex-direction:row;justify-content:space-between">
                                                        <div class="input-group" style="width: 200px;">
                                                                <select class="form-control">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="fas fa-caret-down"></i>
                                                                            </span>
                                                                        </div>
                                                                    <option value="all">Date: 14 Feb 2024</option>
                                                                    <!-- Add more options as needed -->
                                                                </select>
                                                                
                                                        </div>   
                                            <div class="input-group" style="width: 200px;">
                                                        <select class="form-control">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-caret-down"></i>
                                                                    </span>
                                                                </div>
                                                            <option value="all">Category: All</option>
                                                            <!-- Add more options as needed -->
                                                        </select>
                                                        
                                            </div>  
                                               
                           </div>   
                           
                           <ul class="custom-list">
                                <li><p style="color:rgba(103,122,142,1);">Mobile Sign In:<span style="font-weight:bold;color:black;margin-left: 5px;">{{$mobileEmployeeCount}}</span></p></li>
                                <li><p style="color:rgba(103,122,142,1);">Web Sign In:<span style="font-weight:bold;color:black;margin-left: 5px;">{{$laptopEmployeeCount}}</span></p></li>
                                <li><p style="color:rgba(103,122,142,1);">Astra:<span style="font-weight:bold;color:black;margin-left: 5px;">0</span></p></li>
                           </ul>
         
                           <p style="color: blue; cursor: pointer;" data-toggle="modal" data-target="#moreModal">+2 More</p>
    <!----Open Modal---->                       
    <div class="modal fade" id="moreModal" tabindex="-1" role="dialog" aria-labelledby="moreModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="moreModalLabel">More Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Add content for the modal body here -->
            <p>This is additional information...</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- Add additional buttons if needed -->
          </div>
        </div>
      </div>
    </div>
    <!---Close Modal--->
    <!-- <canvas id="myChart1" width="800" height="400" style="max-width:100%;"></canvas>                   -->
                </div>
        </div>
        <!-- Trigger for Modal -->
    
    
    <!-- Modal -->
    
    
    </div> 
    
    
    </div>
    
