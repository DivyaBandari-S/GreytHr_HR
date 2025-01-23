<div>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        thead tr:first-child th {
            border: none;
            border-top: 1px solid #ddd;
        }
        .settings-icon {
            font-size: 18px;
            color: blue;
            margin-left: 10px;
            cursor: pointer;
        }
    </style>    
<div class="attendance-overview-help">
                   
                   <p>The <span style="font-weight:bold;">Process Attendance</span> page enables you to view changes related to an employee's attendance data for a date in the past as<br/> displayed on the <span style="font-weight:bold">Attendance Info</span> page, the <span style="font-weight:bold;">Shift Roster</span> page, or the <span style="font-weight:bold;">Attendance Muster</span> page. To view your changes, you have to<br/> first process the attendance data. Click <span style="font-weight:bold;">Process Attendance</span> to process the changes.</p>
                   <p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>
                   <span class="hide-attendance-help"style="bottom: 100px;"  wire:click="hideHelp">Hide Help</span>
   </div>
   <button style="border:1px solid blue;margin-right:40px;margin-top:40px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;">
         <i class="fa fa-cog" aria-hidden="true"></i>
        <span style="font-size:12px;">Process Attendance</span>
    </button>
   <div>
   <table>
        <thead>
            <tr>
               <th>Attendance Process History</th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
            </tr>
            <tr>
                <th>#</th>
                <th>Processed For</th>
                <th>Processed On</th>
                <th>Attendance Period</th>
                <th>Selected Date</th>
                <th>Status</th>
                <th>Attendance Cycle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="12"style="text-align:center;">No Data Processed</td>
            </tr>
        </tbody>
    </table>
   </div>
</div>
