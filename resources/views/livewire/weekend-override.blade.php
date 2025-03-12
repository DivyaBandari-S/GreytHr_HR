<div>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #d3dadf; /* Sets the background color of the table headers to pink */
            font-weight: 500;
            font-size: 12px;
            border-right:1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .custom-select {
                height: 1rem; /* Increase the height of the dropdown */
                padding: 0.5rem;
                font-size: 1.2rem; /* Increase the font size */
                width: 150px;
        }
        .dropdown-button-for-employee {
    background-color: white; /* White background */
    color: #333; /* Text color */
    padding: 10px 15px; /* Space around text */
    border: 1px solid #d1d1d1; /* Light border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer on hover */
    display: flex; /* Flexbox for alignment */
    align-items: center; /* Center items vertically */
    margin-top: 40px;
}
.dropdown-content-for-employee {
    display: none; /* Hidden by default */
    position: absolute; /* Positioned absolutely */
    background-color: white; /* White background */
    min-width: 160px; /* Minimum width */
    border: 1px solid #d1d1d1; /* Light border */
    z-index: 1; /* On top of other elements */
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Shadow for dropdown */
}
.dropdown-content-for-employee {
    display: block; /* Show on hover */
}
.dropdown-content-for-employee a {
    color: #333; /* Text color */
    padding: 12px 16px; /* Space around items */
    text-decoration: none; /* No underline */
    display: block; /* Block display */
}

/* Change background on hover */
.dropdown-content-for-employee a:hover {
    background-color: #f1f1f1; /* Light gray background on hover */
}
.custom-dropdown {
    position: relative;
    display: inline-block;
    width: fit-content;
}
    </style>    
<div class="attendance-overview-help"style="width:85%;">

                   

<p>The <span style="font-weight:bold;">Weekend Override</span> page lists all the Leave Schemes where the weekend is not a Saturday and a Sunday (or the default). The listing icon<br/> gives you information on employees who may have already applied for a Leave before you changed the Weekend rule.</p> 

<p>Explore greytHR by <span style="color: #1fb6ff;cursor:pointer;">Help-Doc</span>, watching <span style="color: #1fb6ff;cursor:pointer;">How-to Videos</span> and <span style="color: #1fb6ff;cursor:pointer;">FAQ</span>.</p>

<span class="hide-attendance-help"style="margin-right:10px;"wire:click="hideHelp">Hide Help</span>

       
</div>

<button style="border:1px solid blue;margin-right:40px;margin-top:-10px;background-color:white;color:blue;border-radius:5px;padding:8px 10px;font-size:14px;float:right;"wire:click="addException">
        <span style="font-size:12px;">Add</span>
</button>
<div class="row m-0 p-0">
<div class="col-md-10 mb-6"style="margin-top:30px;">
<div class="table-container"style="max-height:300px;overflow-y:auto;width:100%; margin:0;padding:0 10px;">  
            <table id="employee-table">
                <thead style="position:sticky;top:0;">
                    <tr>
                        <th>#</th>
                        <th>Leave Scheme</th>
                        <th>Leave Type</th>
                        <th>Date</th>
                        <th>Session</th>
                        <th>Reason</th>
                        <th>Created On</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                 
                    <tr>
                        <td style="font-size:12px;">1.</td>
                        <td style="font-size:12px;">Contract Scheme</td>
                        <td style="text-decoration:underline;font-size:12px;">Compensatory Off</td>
                        <td style="white-space:nowrap;font-size:12px;">
                           01 Aug 2024
                        </td>
                        <td style="font-size:12px;">
                           Full Day   
                        </td>
                        <td style="font-size:12px;">
                            testing 
                        </td>
                        <td style="font-size:12px;">
                            22 Sep 2024  
                        </td>
                        <td style="font-size:12px;">
                           <i class="fas fa-bars"></i>
                        </td>
                        <td>
                           <a href="#"class="text-danger"title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                  
               
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
          </div>
          </div>
          </div>
</div>
