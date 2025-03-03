<div>
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
        }
        .hide-attendance-help {
            margin-top:40px;
            position: absolute;
            bottom: 110px;
            right: 10px;
            color: #0000FF;
            font-weight:500;
            cursor: pointer;
        }
        .container {
    display: flex;
    align-items: center;
    background-color: #f8f9fa; /* Light gray background */
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #007bff; /* Light border */
  }
  .topic {
    margin: 0 10px;
    color: black; /* Blue color */
    font-weight: 400; /* Semi-bold font */
    cursor: pointer;
    transition: color 0.3s;
  }
  .topic:hover {
    color: #0056b3; /* Darker blue on hover */
  }
  .active {
    color: #0056b3; /* Darker blue for active topic */
    border: 1px solid #007bff; /* Blue border for active topic */
    padding: 5px 10px;
    border-radius: 3px;
    background-color: #ffffff; /* White background for active topic */
  }


    </style>    
      <div class="attendance-overview-help">
            <p style="font-size:13px;">On the <span style="font-weight: bold;">Fines / Damages</span> page, you can track the fines and damages collected or to be collected from an employee.<br/>Fines are collected from regular employees. At the same time, damages are collected from the contract employees. The collection can be<br/> in installments or at one time. Click <span style="font-weight: bold;">Add Fine</span> under the <span style="font-weight:bold;">Fines</span> tab and <span style="font-weight:bold;">Add Damage</span> under the <span style="font-weight:bold;">Damages</span> tab to add a new fine or damage. </p>
            <p style="font-size:13px;">Explore greytHR by <a href="https://admin-help.greythr.com/admin/answers/123712613" target="_blank" style="color: #1fb6ff;cursor:pointer;">Help-Doc</a>.Watching<a href="https://youtu.be/drrSfJrjHz0?si=BrqYlKmNr9kFrHLT" target="_blank" style="color: #1fb6ff;cursor:pointer;">&nbsp;How-to Videos&nbsp;</a>and<a href="https://greythr.freshdesk.com/support/search/solutions?term=attendance+overview"style="color: #1fb6ff;cursor:pointer;">&nbsp;FAQ&nbsp;</a>.</p>
            <span class="hide-attendance-help"wire:click="hideHelp">Hide Help</span>
      </div>
      <div class="container">
        <div class="topic {{ ($finepage==1&&$damagepage==0) ? 'active':'none'}}" wire:click="openFinePage()">Fines</div>
        <div class="topic {{ ($finepage==0&&$damagepage==1) ? 'active':'none'}}" wire:click="openDamagePage()">Damages</div>
      </div>
      @if($finepage==1&&$damagepage==0)
          @livewire('fine-component', ['finepage' => $finepage, 'damagepage' => $damagepage])
      @elseif($finepage==0&&$damagepage==1)
          @livewire('damage-component', ['finepage' => $finepage, 'damagepage' => $damagepage])
      @endif  
            
</div>
