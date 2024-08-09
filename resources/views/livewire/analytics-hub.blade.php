<div>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .right {
            color: blue;
            cursor: pointer;
        }

        .right:hover {
            text-decoration: underline;
        }

        .main-container {
            background-color: white;
            margin: 20px;
            /* Adjust margin as needed */
            padding: 20px;
            
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .content-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .content-box {
            background-color: white;
            flex: 1;
            min-width: 200px;
            /* Ensures the boxes stack on smaller screens */
            padding: 10px;
            display: flex;
            /* Ensure contents are flex items */
            align-items: center;
            /* Center items vertically */
            border-radius: 10px;
            border: 1px solid grey;
            cursor: pointer;
        }

        .icon {
            font-size: 24px;
            margin-right: 8px;
            /* Adjust margin as needed */
        }

        .text {
            font-size: 14px;
            color: black;
        }

        @media (max-width: 600px) {
            .content-box {
                flex-direction: column;
                align-items: flex-start;
            }

            .icon {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }

        .container1 {

            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header1 {
            display: flex;
            padding: 10px;
            justify-content: space-between;
            align-items: center;

        }

        .header-left {
            font-size: 16px;
            font-weight: 400;
        }

        .add-employee-btn {

            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar {
            display: flex;
            padding: 10px;
            justify-content: space-between;
            /* Adjust spacing between items */
            align-items: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            width: 180px;
            padding: 6px 28px 6px 10px;
            /* Adjust padding for right space */
            border: 1px solid #ccc;
            border-radius: 15px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            color: #666;
            pointer-events: none;
        }

        .restore-text {
            margin-right: 10px;
            color: blue;
            cursor: pointer;
        }

        .restore-icon {
            font-style: normal;
            margin-left: 10px;
            color: blue;
            cursor: pointer;
        }

        /* Adjusted CSS for alternating row colors */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #CFD8DC;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-right: 1px solid #ddd;
            /* Add right border to all cells */
        }

        table th {
            background-color: #CFD8DC;
            color: black;
            font-size: 16px;
            font-weight: 400;
            position: relative;
            /* Ensure the position context for pseudo-element */
        }

        table th:last-child {
            border-right: none;
            /* Remove right border for the last header cell */
        }

        /* Alternating row colors */
        table tr:nth-child(even) {
            background-color: #F5F5F5;
            /* Grey background for even rows */
        }

        table tr:nth-child(odd) {
            background-color: #ffffff;
            /* White background for odd rows */
        }


        .grey-text {
            color: #616161;
        }

        table th:nth-child(2) {
            width: 400px;
            /* Adjust width as needed */
            text-align: center;
        }

        .highlighted {
            background-color: #E3F2FD;
            border: 1px solid #0288d1;
        }
    </style>

    <div class="main-container">
        <div class="header">
            <span class="left">Recent</span>
            <span class="right" wire:click="analyticsHubList">View All</span>
        </div>
        <div class="content-row">
            <div class="content-box @if($selectedCard == 'Basic Information') highlighted @endif" wire:click="selectCard('Basic Information')">
                <span class="icon">&#9734;</span>
                <span class="text">Basic Information</span>
            </div>
            <div class="content-box @if($selectedCard == 'Personal Information(PII Data)') highlighted @endif" wire:click="selectCard('Personal Information(PII Data)')">
                <span class="icon">&#9734;</span>
                <span class="text">Personal Information(PII Data)</span>
            </div>
            <div class="content-box @if($selectedCard == 'All Employee Info') highlighted @endif" wire:click="selectCard('All Employee Info')">
                <span class="icon">&#9734;</span>
                <span class="text">All Employee Info</span>
            </div>
            <div class="content-box @if($selectedCard == 'Gender-wise Headcount') highlighted @endif" wire:click="selectCard('Gender-wise Headcount')">
                <span class="icon">&#9734;</span>
                <span class="text">Gender-wise Headcount</span>
            </div>
        </div>
    </div>
    <div class="container1">
        <div class="header1">
            <div class="header-left">
                {{ $selectedCard }}
            </div>
            <div class="header-right">
                <button class="btn btn-primary">Add Employee</button>
            </div>
        </div>
        <hr>
        <div class="search-bar">
            <div class="search-wrapper">
                <input type="text" placeholder="Search...">
                <i class="search-icon fas fa-search"></i>
            </div>
            <div>
                <span class="restore-text">Restore</span>
                <i class="restore-icon fas fa-cloud"></i>
            </div>
        </div>

        <div class="table-container">
            @if($selectedCard == 'Basic Information')
            <table>
                <thead>
                    <tr>
                        <th>Emp ID <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Emp Name</th>
                        <th>Date of Joining</th>
                        <th>Gender</th>
                        <th>Email ID</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically generated -->
                    <tr>
                        <td class="grey-text">T0001</td>
                        <td class="text-primary">John Doe</td>
                        <td class="grey-text">2023-01-01</td>
                        <td class="grey-text">Male</td>
                        <td class="grey-text">john.doe@example.com</td>
                    </tr>
                    <tr>
                        <td class="grey-text">T00012</td>
                        <td class="text-primary">Jane Smith</td>
                        <td class="grey-text">2023-02-15</td>
                        <td class="grey-text">Female</td>
                        <td class="grey-text">jane.smith@example.com</td>
                    </tr>
                    <tr>
                        <td class="grey-text">T00012</td>
                        <td class="text-primary">Jane Smith</td>
                        <td class="grey-text">2023-02-15</td>
                        <td class="grey-text">Female</td>
                        <td class="grey-text">jane.smith@example.com</td>
                    </tr>
                    <tr>
                        <td class="grey-text">T00012</td>
                        <td class="text-primary">Jane Smith</td>
                        <td class="grey-text">2023-02-15</td>
                        <td class="grey-text">Female</td>
                        <td class="grey-text">jane.smith@example.com</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
            @elseif($selectedCard == 'Personal Information(PII Data)')
            <table>
                <thead>
                    <tr>
                        <th>Name <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Date of Birth</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Blood Group</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically generated -->
                    <tr>
                        <td class="grey-text">John Doe</td>
                        <td class="text-primary">1999-01-09</td>
                        <td class="grey-text">9876543210</td>
                        <td class="grey-text">Hyderabad</td>
                        <td class="grey-text">o+</td>
                    </tr>
                    <tr>
                        <td class="grey-text">John Doe</td>
                        <td class="text-primary">1999-01-09</td>
                        <td class="grey-text">9876543210</td>
                        <td class="grey-text">Hyderabad</td>
                        <td class="grey-text">AB+</td>
                    </tr>
                    <tr>
                        <td class="grey-text">John Doe</td>
                        <td class="text-primary">1999-01-09</td>
                        <td class="grey-text">9876543210</td>
                        <td class="grey-text">Hyderabad</td>
                        <td class="grey-text">B+</td>
                    </tr>
                    <tr>
                        <td class="grey-text">John Doe</td>
                        <td class="text-primary">1999-01-09</td>
                        <td class="grey-text">9876543210</td>
                        <td class="grey-text">Hyderabad</td>
                        <td class="grey-text">o+</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
            @elseif($selectedCard == 'All Employee Info')
            <div class="p-3">
                <p>This is All Employee Info Section</p>
            </div>
            @elseif($selectedCard == 'Gender-wise Headcount')
            <div class="p-3">
                <p>This is Gender-wise Headcount section</p>
            </div>
            @endif
        </div>
    </div>
</div>