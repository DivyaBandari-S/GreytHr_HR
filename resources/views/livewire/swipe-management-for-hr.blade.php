<div>
     <style> 
           .container {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: left;
      margin-left: 10px;
      margin-top: 20px;
      display: flex;
      align-items: center; 
    }
    .container-body {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: left;
      margin-left: 10px;
      border-top:1px solid #ddd;
      display: flex;
      align-items: center; 
      width:1140px;
    }
    .container h1 {
      font-size: 16px;
      color: #333;
      margin: 0;
      margin-right: 20px; 
    }
    .container .arrow {
      font-size: 24px;
      color: #333;
    }
    .arrow-btn {
    color: var(--label-color);
    height: 22px;
    width: 22px;
    display: flex;
    margin-right: 10px;
    cursor: pointers;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    border: 1px solid #dcdcdc;
    margin-right: 10px;
}

.arrow-btn .fa {
    transition: transform 0.3s ease;
    /* Smooth rotation */
}

.arrow-btn .rotate {
    transform: rotate(180deg);
    color: rgb(2, 17, 79);
}
.arrow-btn .active-border-color {
    border: 1px solid rgb(2, 17, 79) ;
}
.arrow-btn {
      color: #778899;
      border: 1px solid #778899;
      padding: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .arrow-btn i {
      font-size: 20px;
    }

     </style>
<div class="attendance-overview-help">
                   
                   <p>The <span style="font-weight:bold;">Swipe Management</span> page helps you to resynchronize the swipes of your employees from the greytHR Admin portal anytime<br/> and anywhere without logging in to the Greytip Astra.</p>
                   <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                   <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
   </div> 
   <div class="container">
      <h1>ASTRA Configuration and Cleanup</h1>
  </div> 
  <div class="container">
      <p>A latest version of Astra is available, version 4.0.2. Please request your administrator to upgrade the same.</p>
  </div>   
  <div class="container">
      <h1>Health Status</h1>
      <div class="arrow-btn">
         <i class="fa fa-angle-down"wire:click="openAccordionForHealthStatus"></i> <!-- Down arrow icon -->
      </div>
  </div>
  @if($accordionForHealthStatus)
     <div class="container-body">
           
     </div>
  @endif
  <div class="container">
      <h1>Sync Attendance</h1>
      <div class="arrow-btn">
         <i class="fa fa-angle-down"wire:click="openAccordionForSyncAttendance"></i> <!-- Down arrow icon -->
      </div>
  </div> 
  @if($accordionForSyncAttendance)
     <div class="container-body"style="gap: 20px;">
               <div class="form-group">
                    <label for="from-date">From Date:</label>
                    <input type="date" id="from-date" name="from-date" required>
               </div>
               <div class="form-group">
                    <label for="to-date">To Date:</label>
                    <input type="date" id="to-date" name="to-date" required>
               </div>
               <div class="form-group">
                    <label for="office-location">Office Location:</label>
                    <input type="text" id="office-location" name="office-location" placeholder="Enter office location" required>
               </div>
          
          <button style="border:1px solid blue;margin-right:40px;background-color:blue;color:white;border-radius:5px;padding:8px 10px;font-size:14px;float:right;">
               <span style="font-size:12px;">Sync Data</span>
          </button>
     </div>
  @endif 
  <div class="container">
      <h1>Pending Request</h1>
      <div class="arrow-btn">
         <i class="fa fa-angle-down"wire:click="openAccordionForPendingRequest"></i> <!-- Down arrow icon -->
      </div>
  </div> 
  @if($accordionForRequestHistory)
     <div class="container-body">
     <table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Requested Date</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Requested By</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="30">No rows Found</td>
           
        </tr>
        
    </tbody>
</table>

     </div>
  @endif 
  <div class="container">
      <h1>Request History</h1>
      <div class="arrow-btn">
         <i class="fa fa-angle-down"wire:click="openAccordionForRequestHistory"></i> <!-- Down arrow icon -->
      </div>
  </div>
  @if($accordionForRequestHistory)
     <div class="container-body">
           hii welcome to request history page
     </div>
  @endif   
</div>
