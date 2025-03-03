<div style="height: {{ $legend ? '400px' : '100px' }};">
    <style>
        .date-range-container12-attendance-info {
            margin-right: 62px;
        }

       .fixed-column
       {
        position: sticky;
    left: 0; /* Keeps the column fixed on the left */
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); 
    background: white;
    z-index: 2; /* Keeps it above other columns */
    border-right: 1px solid #ddd; /* Optional: Adds a separator */
       }
        .my-button-attendance-info {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            border-color: rgb(2, 17, 79);
            background: rgb(2, 17, 79);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;

        }

        .my-button-attendance-info:hover {
            /* Styles for hover state */
            text-decoration: none;
            background-color: #24a7f8 !important;
            color: #fff !important;
            /* Remove underline on hover */
        }

        .my-button-attendance-info:active {
            /* Styles for active/clicked state */
            text-decoration: none;
            /* Remove underline when clicked */
        }

        .topMsg-attendance-info {
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            background-color: #FFFFFF;
        }

        .container-attendance-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 500px;
            /* Adjust the total width as needed */
            height: 40px;
            /* Adjust the height as needed */
            /* Background color of the container */
            border: 1px solid #ccc;
            /* Border style for the container */
            padding: 10px;
            /* Padding inside the container */
            font-size: 14px;
            margin-left: 170px;
            margin-bottom: -100px;
            background-color: #FFFFFF;
            /* Font size for the text */
        }

        .insight-card[_ngcontent-hbw-c670] {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            /* margin-right: 15px;
min-height: 102px;
width: 170px; */
        }

        .insight-card[_ngcontent-hbw-c670] h6[_ngcontent-hbw-c670] {
            border-bottom: 1px solid #cbd5e1;
            margin: 0;
            padding: 7px 20px;
            text-transform: uppercase;
        }

        .text-regular {
            font-weight: 400;
        }

        .text {
            color: #000;
        }
       

        .arrow-icon-attendance-info {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 5px;
            /* Adjust the margin as needed */
        }

        .arrow-icon-attendance-info::after {
            content: '\2192';
            /* Unicode right arrow character */
            margin-left: 5px;
            /* Adjust the margin as needed */
        }

        .text-secondary {
            color: #7f8fa4;
        }

        .text-center {
            text-align: center;
        }

        .info-icon-container-attendance-info {
            position: relative;
            display: inline-block;
        }

        .info-icon-attendance-info {
            font-size: 14px;
            color: blue;
        }

        .info-icon-container-attendance-info .fa-info-circle:hover+.info-box-attendance-info {
            display: block;
        }

        .info-box-attendance-info {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            color: #fff;
            transform: translateX(-50%);
            background-color: #808080;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
            Z-index: 1
        }



        .text-2 {
            font-size: 18px;
        }

        .bg-white {
            --tw-bg-opacity: 1;
            background-color: rgba(255, 255, 255, var(--tw-bg-opacity));
        }

        .text-success {
            color: #5bc67e;
        }
        

        .text-muted {
            color: #a3b2c7;
        }

        a {
            color: #24a7f8;
        }

        .text-5 {
            font-size: 12px;
            margin-top: 50px;
            margin-bottom: 0;
        }

        .btn-group {
            position: relative;
            display: inline-block;
            vertical-align: middle;
        }

        .btn-group>.btn:first-child:not(:last-child):not(.dropdown-toggle) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .btn-group .btn.icon-btn {
            min-width: 30px;
            padding: 0;
        }

        .btn-group .btn.active {
            background-color: #24a7f8;
            border: 1px solid #24a7f8;
            color: #fff;
        }

        .btn-group>.btn:first-child {
            margin-left: 0;
        }

        [_nghost-exg-c673] .details[_ngcontent-exg-c673] {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .bg-white {
            --tw-bg-opacity: 1;
            background-color: rgba(255, 255, 255, var(--tw-bg-opacity));
        }

        .calendar-attendance-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* width: 700px;
    margin-left: 20px;
    margin-top: 10px; */
        }

        .large-box-attendance-info {
            max-width: 900px;
            height: 220px;
            margin: 0 auto;

            margin-left: 10px;
            margin-top: 10px;
            border: 1px solid #cbd5e1;


        }

        /* Month header */
        .calendar-header-attendance-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }


        #calendar-icon-attendance-info {
            border-top-left-radius: 5px;
            /* Adjust the value as needed */
            border-bottom-left-radius: 5px;
            /* Adjust the value as needed */
        }

        #bars-icon-attendance-info {
            border-top-right-radius: 5px;
            /* Adjust the value as needed */
            border-bottom-right-radius: 5px;
            /* Adjust the value as needed */
        }

        .calendar-weekdays-attendance-info {
            display: flex;
            justify-content: space-around;
            color: #02114f;
            gap: 5px;
            padding: 5px 10px;
            /* margin-bottom: 10px; */
            border: 1px solid #dedede;
        }

        .container-leave {
            padding: 0;
            margin: 0;
        }

        .table thead {
            border: none;
         
        }

        .table th {
            text-align: center;
            height: 15px;
            border: none;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            font-weight: normal;
            color: #778899;
        }

        .table td:hover {
            background-color: #ecf7fe;
            /* Hover background color */
            cursor: pointer;
        }

        /* Add styles for the navigation buttons */
        .nav-btn {
            background: none;
            border: none;
            color: #778899;
            font-size: 12px;
            margin-top: -6px;
            cursor: pointer;
        }

        .nav-btn:hover {
            color: blue;
        }

        /* Increase the size of tbody cells and align text to top-left */
        .table-1 tbody td {
            width: 75px;
            height: 80px;
            border-color: #c5cdd4;
            font-weight: 500;
            font-size: 13px;
            /* Adjust font size as needed */
            vertical-align: top;
            position: relative;
            text-align: left;
        }

        .table-1 thead {
            border: none;
        }

        .table-1 th {
            text-align: center;
            /* Center days of the week */
            height: 15px;
            border: none;
            color: #778899;
            font-size: 12px;
        }

        .table-1 {
            overflow-x: hidden;
        }

        /* Add style for the current date cell */
        .current-date {
            background-color: #ff0000;
            /* Highlight color for the current date */
            color: #fff;
            /* Text color for the current date */
            font-weight: bold;
        }

        .calendar-heading-container {
            background: #fff;
            padding: 10px 10px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            /* Add spacing between heading and icons */
        }

        .calendar-heading-container h5 {
            font-size: 0.975rem;
            color: black;
            font-weight: 500;
        }

        .table {
            overflow-x: hidden;
            /* Add horizontal scrolling if the table overflows the container */
        }

        .tol-calendar-legend {
            display: flex;
            font-size: 0.875rem;
            width: 100%;
            justify-content: space-between;
            font-weight: 500;
            color: #778899;
        }

        /* CSS for legend circles */
        .legend-circle {
            display: inline-block;
            width: 15px;
            /* Adjust the width as needed */
            height: 15px;
            /* Adjust the height as needed */
            border-radius: 50%;
            text-align: center;
            line-height: 15px;
            /* Vertically center the text */
            margin-right: 2px;
            /* Add some spacing between the circle and text */
            font-weight: bold;
            /* Make the text bold */
            color: white;
            /* Text color */
        }

        .circle-pale-yellow {
            background-color: #ffeb3b;
            /* Define the yellow color */
        }

        /* CSS for the pink circle */
        .circle-pale-pink {
            background-color: #d29be1;
            /* Define the pink color */
        }

        .accordion {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            /* Adjust the width as needed */
            top: 100px;
            overflow-x: auto;
            left: 0;
            /* Adjust the top position as needed */
            /* Adjust the right position as needed */
        }

        .accordion-heading {
            background-color: #fff;
            cursor: pointer;
        }

        .accordion-body {
            background-color: #fff;
            padding: 0;
            display: block;
            width: 100%;
            overflow: auto;
        }

        .accordion-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .accordion-title {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .active .leave-container {
            border-color: #3a9efd;
            /* Blue border when active */
        }

        .accordion-button {
            color: #DCDCDC;
            border: 1px solid #DCDCDC;
        }

        .active .accordion-button {
            color: #3a9efd;
            border: 1px solid #3a9efd;
        }

        @media (max-width: 760px) {


            .accordion {
                width: 65%;
                top: auto;
                right: auto;
                margin-top: 20px;
            }
        }

        .centered-modal {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Calendar days */
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            /* gap: 5px; */
            justify-items: center;
            padding: 0px;
        }

        .calendar-date {
            width: 100%;
            height: 4em;
            font-weight: normal;
            font-size: 12px;
            /* border-radius: 50%; */
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            /* padding: 14px; */
        }



        .attendance-calendar-date:hover {
            background-color: #f3faff;
        }

        .attendance-calendar-date.clicked {
            background-color: #f3faff;
            border-color: blue;
            border: 2px solid #24a7f8;

        }

        .clickable-date:active {
            background-color: #f3faff;
            /* Set the desired background color when clicked */
            border: 1px solid #c5cdd4;
            /* Set the desired border color */
        }

        .custom-container {
            margin-top: 20px;
            background-color: white;
            /* White background */
            width: 100%;
            /* Adjust width as needed */
            height: auto;
            /* Adjust height as needed */
            padding: 20px;
            /* Optional padding for content spacing */
            border: 1px solid #ddd;
            /* Optional border for visual distinction */
            box-sizing: border-box;
            /* Ensure padding and border are included in width and height */
        }

        @media screen and (max-height:320px) {

          

        }

        .clickable-date1 {
            background-color: pink;
            /* Set the desired background color when clicked */
            /* Set the desired border color */
        }

        .calendar-day {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: white;
        }

        #prevMonth,
        #nextMonth {
            /* background-color: rgb(2, 17, 79);
    color: white; */
            border: none;
            padding: 2px 5px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;

        }

        #currentMonth {
            font-size: 12px;
            margin: 0;
        }

        .today {
            background-color: rgb(2, 17, 79);
            color: white;
        }

        /* Today's date */
        .calendar-day.today {
            background-color: #0099cc;
            color: white;
        }

        .container1 {
            /* width: 600px;  */
            /* height: 200px; */
            /* margin-right: 300px; */
            background-color: #FFFFFF;
            margin-top: 15px;
            /* margin-top: 420px; */
            border-radius: 10px;
            /* float: right; */
            border: 1px solid #ccc;
        }

        .container2 {
            /* width: 600px;
    /* Adjust the width as needed */
            /* height: 140px; */
            /* margin-right: 300px; */
            background-color: #FFFFFF;
            margin-top: 40px;
            border-radius: 10px;
            /* padding-bottom: -70px; */
            /* float: right; */
            /* Adjust the height as needed */
            /* Background color of the container */
            border: 1px solid #ccc;
            /* Border style for the container */
        }

        .container1,
        .container2,
        .container3,
        .container6 {
            display: block;
        }

        .container6 {
            /* width: 600px; */
            /* Adjust the width as needed */
            /* height: 45px; */
            /* margin-right: 300px; */
            background-color: #FFFFFF;
            margin-top: 30px;
            border-radius: 10px;
            /* float: right; */
            /* Adjust the height as needed */
            /* Background color of the container */
            border: 1px solid #ccc;
            /* Border style for the container */
        }

        .container-body {
            /* width: 600px; */
            /* Adjust the width as needed */
            /* height: 105px; */
            /* margin-right: 0px; */
            /* margin-bottom: 30px; */
            background-color: #FFFFFF;
            border-radius: 10px;
            display: none;
            /* border-radius:10px; */
            /* float: right; */
            /* Adjust the height as needed */
            /* Background color of the container */
            border: 1px solid #ccc;
        }

        /* Basic styles for the input container */
        .date-range-container {
            display: flex;
            align-items: center;
            width: 300px;
            /* Adjust the width as needed */
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-left: 198px;

            margin-top: -80px;
        }

        .chart-range-container {
            display: flex;
            align-items: center;
            /* width: 600px; */
            /* Adjust the width as needed */
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            /* margin-left: -98px; */
            overflow-x: auto;
            height: 120px;
            /* margin-top: 40px; */
        }

        /* Style for the calendar icon */
        .calendar-icon {
            margin-right: 10px;
            color: #888;
        }

        /* Style for the input field */
        .date-range-input {
            border: none;
            outline: none;
            width: 100%;
        }

        .container3 {
            /* width: 600px; */
            /* Adjust the width as needed */
            /* height: 180px; */
            /* margin-right: 300px; */
            background-color: #FFFFFF;
            margin-top: 30px;
            border-radius: 10px;
            /* float: right; */
            /* Adjust the height as needed */
            /* Background color of the container */
            border: 1px solid #ccc;
            /* Border style for the container */
        }

        /* CSS for the table */
        .large-box-attendance-info {
            width: 100%;
            overflow-x: auto;
        }
       
        .second-header-row th.date {
            background-color: #ebf5ff;
            color: #778899;
            /* Adjust the width of the Date column as needed */
        }

        .second-header-row th.date {

            /* Adjust the width of the Date column as needed */
        }

        .scrollable-container {
            overflow-x: auto;
            /* Enables horizontal scrolling */
            white-space: nowrap;
            /* Prevents the text from wrapping */
            padding: 10px;
            /* Adds some padding */
            border: 1px solid #ddd;
            /* Optional: Adds a border */
            background-color: #f9f9f9;
            /* Optional: Adds a background color */
            height: auto;
        }

        .second-header-row th:not(.date) {

            /* Adjust the width of the Shift and Shift Timings columns as needed */
        }

        .large-box-attendance-info table {

          
            margin-top: -20px;
           
            /* Fix the table layout */
            width: auto;
            /* Set the table to an appropriate width or it will expand to the container's full width */
            white-space: nowrap;
            /* Prevent table cell content from wrapping */
            border-collapse: collapse;

        }

        .large-box-attendance-info td {
            padding: 10px;


        }

        .date {

            border-bottom: 1px solid #cbd5e1;
            margin-left: 10px;

        }

        .large-box-attendance-info {

            height: 200px;
        }

        .large-box-attendance-info th {
            background-color: rgb(2, 17, 79);
            color: white;
            width: 600px;
            /* Adjust the width as needed */
            padding-right: 50px;
        }

        /* CSS for the second header row */
        .large-box-attendance-info .second-header-row {

            background-color: rgb(2, 17, 79);
            color: white;
        }

        .large-box-attendance-info .third-header-row {

            color: black;
        }

        .large-box-attendance-info .first-header-row {
            background-color: rgb(2, 17, 79);
            color: #778899;


        }


        .large-box-attendance-info .fourth-header-row {
            background-color: #fff;
            color: black;
        }

        .vertical-line-attendance-info {
            border-left: 1px solid black;
            /* Adjust the width and color as needed */
            /* Adjust the height as needed */
            margin-top: -68px;
            height: 70px;
            padding: 0;
            margin-left: 70px;
        }


        .chart-column-attendance-info {
            flex: 1;
            /* Distribute available space equally among columns */
            padding: 70px;
            margin-top: -40px;
            text-align: center;
            border-right: 1px solid #ccc;
        }

        /* Remove the right border for the last column */
        .chart-column-attendance-info:last-child {
            border-right: none;
            margin-top: -40px;
        }

        .horizontal-line-attendance-info {
            width: 100%;
            /* Set the width to the desired value */
            border-top: 1px solid #ccc;
            /* You can adjust the color and thickness */
            margin: 0px 0;
            /* Adjust the margin as needed */
        }


        .horizontal-line2-attendance-info {
            width: 100%;
            /* Set the width to the desired value */
            border-top: 1px solid #000;
            /* You can adjust the color and thickness */
            margin: -10px 0;
            /* Adjust the margin as needed */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            

        }

        /* CSS for the table header (thead) */
        thead {
            background-color: #eef7fa;
            color: #778899;
            border-top: 1px solid #ccc;
        }

        /* CSS for the table header cells (th) */
        th {
            padding: 10px;
            text-align: left;
        }

        td {
            /* Add borders to separate cells */
            padding: 10px;
            text-align: left;
        }

        .toggle-box-attendance-info {
            display: flex;
            align-items: center;
            background-color: transparent;

            width: 73px;
            /* margin-left: 850px; */
            /* margin-top: -40px; */
            padding: 5.5px 6px;
            /* Adjust padding as needed */
        }


        .toggle-box-attendance-info i {
            color: grey;
            /* Set the icon color */
            background-color: white;
            /* Set the background color for icons */
            padding: 7px 7px;
            /* Set padding for icons */
            margin-right: 0px;
            /* Add spacing between icons if desired */
        }

        .toggle-box-attendance-info i.fas.fa-calendar {
            /* Initial icon color */
            /* Initial background color for icon */
            padding: 7px 7px;
            /* Initial padding for icon */
            margin-right: 0px;
            /* Initial spacing between icons */
            border: 2px solid transparent;

            /* Initial border color (transparent) */
        }


        .toggle-box-attendance-info i.fas.fa-calendar:hover {
            color: rgb(2, 17, 79);
            /* Icon color on hover */
            /* Background color for icon on hover */
            border-color: rgb(2, 17, 79);
            /* Border color on hover */
        }

        .toggle-box-attendance-info i.fas.fa-bars {
            color: grey;
            /* Initial icon color */
            /* Initial background color for icon */
            padding: 7px 7px;
            /* Initial padding for icon */
            margin-right: 0px;
            /* Initial spacing between icons */
            border: 2px solid transparent;
            /* Initial border color (transparent) */
        }

        .accordion-icon {
            font-size: 13px;
            color: #fff;
            float: right;
            margin-left: 5px;
        }

        .toggle-box-attendance-info i.fas.fa-bars:hover {
            color: rgb(2, 17, 79);
            /* Icon color on hover */
            /* Background color for icon on hover */
            border-color: rgb(2, 17, 79);
            /* Border color on hover */
        }

        .toggle-box-attendance-info i.fas.fa-calendar.active {
            color: white;
            /* Icon color when active (clicked) */
            background-color: rgb(2, 17, 79);
            /* Background color when active (clicked) */
        }

        .toggle-box-attendance-info i.fas.fa-bars.active {
            color: white;
            /* Icon color when active (clicked) */
            background-color: rgb(2, 17, 79);
            /* Background color when active (clicked) */
        }

        .box-attendance-info {
            width: 160px;
            height: 30px;
            /* You can change the border color here */
            text-align: center;
            display: inline-block;
            margin-top: 14px;
            margin-left: 8px;


        }





        .custom-modal .modal-header {
            padding: 10px;
            background-color: #e9edf1;
            /* Decrease header padding */
        }

        .date-picker-container {
            position: relative;
            display: none;

        }

        .date-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .calendar-icon1 {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        #calendar4 {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1000;
        }

        #calendar4 .calendar {
            display: inline-block;
            margin: 10px;
        }


        /* .custom-modal .modal-body {
    padding: 100px;
} */

        /* CSS for the icons */

        /* Style for the row container */


        /* Style for individual values */

        /* Style for individual values */
        .chart-value {
            flex: 1;
            /* Distribute available space equally among values */
            text-align: center;
            margin-top: 40px;
            padding: 10px;


        }

        .chart-column-attendance-info>div {
            margin: 0 auto;
        }

        /* CSS for the lines icon */
        .lines-icon::before {
            content: "\2630";
            background-color: white;
            padding-top: 5px;
            padding-right: 12px;
            padding-bottom: 7px;
            margin-left: -10px;
            /* Unicode character for the three lines icon */
        }

        .rotate-arrow {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
            /* Add a smooth transition effect */
        }

        /* CSS for the calendar icon */
        .calendar-icon::before {
            content: "\1F4C5";
            background-color: white;
            /* Add a blue background color */
            color: white;
            /* Set the text color to white */
            padding-top: 5px;
            padding-right: 6px;
            padding-bottom: 6px;
            /* Add padding for spacing */
            /* Unicode character for the calendar icon */
        }

        .arrow-button::after {
            content: "\25B6";
            /* Unicode character for right-pointing triangle (arrow) */
            font-size: 18px;

        }

        .modal-box-attendance-info {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            /* Adjust as needed for spacing */
        }

        .attendance-calendar-date {
            cursor: pointer;
            padding: 3px;
            margin: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }


        .custom-modal-lg {
            max-width: 90%;

        }

        .modal-content-attendance-info {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .fa-info-circle:hover {
            text-decoration: underline;
        }

        .circle.IRIS {
            background-color: #d29be1;
        }

        .container11 {
            display: flex;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            /* Initially, hide the sidebar off-screen */
            width: 250px;
            height: 100%;
            background-color: #fff;
            color: #fff;
            transition: right 0.3s ease-in-out;
        }

        .close-sidebar {
            margin-left: 205px;
            margin-bottom: -50px;
        }

        .content {
            margin-right: 20px;

        }

        .accordion:before {

            /* Unicode character for "plus" sign (+) */
            font-size: 13px;
            color: #fff;
            float: right;
            margin-left: 5px;
        }


        .panel {
            /* padding: 0 18px; */
            display: none;
            background-color: white;
            overflow: hidden;
            border: 1px solid #cecece;
            font-size: 14px;
        }

        .accordion {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            /* Adjust the width as needed */
            top: 100px;
            overflow-x: auto;
            left: 0;
            /* Adjust the top position as needed */
            /* Adjust the right position as needed */
        }

        /* Existing styles for sidebar */


        /* Styles for sidebar header */
        .sidebar .sidebar-header {
            background-color: #e9edf1;
            padding: 10px;
            text-align: center;
        }

        .sidebar .sidebar-header h2 {
            color: #7f8fa4;
            font-size: 24px;
            margin: 0;
        }

        .sidebar-content h3 {
            color: #7f8fa4;
            margin-left: 30px;
        }

        .sidebar-content p {
            color: #7f8fa4;
            font-size: 12px;
            margin-left: 30px;
        }

        /* Hover styles */

        .text-overflow {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .accordion {
            background-color: #dae0f7;
            color: #444;
            cursor: pointer;
            padding: 21px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
            margin-top: 10px;
            border: 1px solid #cecece;
        }

        /* .active, .accordion:hover {
background-color: #02114f;
color: #fff;
} */

        .panel {
            /* padding: 0 18px; */
            display: none;
            background-color: white;
            overflow: hidden;
            border: 1px solid #cecece;
            font-size: 14px;
        }

        .accordion:after {

            /* Unicode character for "plus" sign (+) */
            font-size: 13px;
            color: #fff;
            float: right;
            margin-left: 5px;
        }

        /* .active:after {
            content: "\2796";
        } */

        .legendsIcon {
            padding: 1px 6px;
            font-weight: 500;
        }

        .presentIcon {
            background-color: #edfaed;
        }

        .absentIcon {
            background-color: #fcf0f0;
            color: #ff6666;
        }

        .offDayIcon {
            background-color: #f7f7f7;
        }

        .leaveIcon {
            background-color: #fcf2ff;
        }

        .sickleaveIcon {
            background-color: transparent;
        }

        .onDutyIcon {
            background-color: #fff7eb;
        }

        .holidayIcon {
            background-color: #f2feff;
        }

        .alertForDeIcon {
            background-color: #edf3ff;
        }

        .deductionIcon {
            background-color: #fcd2ca;
        }

        .down-arrow-reg {
            width: 0;
            height: 0;
            /* border-left: 20px solid transparent; */
            border-right: 17px solid transparent;
            border-bottom: 17px solid #f09541;
            margin-right: 5px;
        }

        .down-arrow-gra {
            width: 0;
            height: 0;
            /* border-left: 20px solid transparent; */
            border-right: 17px solid transparent;
            border-bottom: 17px solid #5473e3;
            margin-right: 5px;
        }

        .down-arrow-ign-attendance-info {
            width: 0;
            height: 0;
            /* border-left: 20px solid transparent; */
            border-right: 17px solid transparent;
            border-bottom: 17px solid #677a8e;
            margin-right: 5px;
        }

        .emptyday {
            color: #aeadad;
            pointer-events: none;
        }

        .scrollable-table {
            display: block;
            max-height: 400px;
            /* Adjust as needed */
            overflow-y: scroll;
            border: 1px solid #cbd5e1;
        }

        .info-button:hover {
            text-decoration: underline;
        }

        .down-arrow-reg {
            width: 0;
            height: 0;
            /* border-left: 20px solid transparent; */
            border-right: 17px solid transparent;
            border-bottom: 17px solid #f09541;
            margin-right: 5px;
        }
    </style>
    @php
    $offCount=0;
    $presentCount=0;
    $absentCount=0;
    $leaveCount=0;
    $holidaycountforcontainer=0;
    $totalShortFallHoursWorked=0;
    $totalshortfallMinutesWorked=0;
    $currentYear = date('Y');
    $currentMonth=date('n');
    $currentMonthRep=date('F');
    $holidayNote=false;
    $totalHoursWorked = 0;
    $totalMinutesWorked = 0;
    $currentMonthRep = DateTime::createFromFormat('F', $currentMonthRep)->format('M');
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    $totalSecondsWorked = 0;
    $totalexcessHoursWorked=0;
    $totalexcessMinutesWorked=0;
    @endphp
    
    <div class="date-filters p-0 row d-flex align-items-center mb-2">
        <div class="col-md-3 m-0">
            <div class="fromDatefield d-flex align-items-center gap-2">
                <label class="normalTextValue" for="from-date">From Date:</label>
                <input type="date" id="from-date" wire:model="fromDate" wire:change="updatefromDate" class="form-control">
            </div>
        </div>
        <div class="col-md-3 m-0 p-0">
            <div class="toDatefield d-flex gap-2 align-items-center mobile-gap">
                <label class="normalTextValue" for="to-date">To Date:</label>
                <input type="date" id="to-date" wire:model="toDate" wire:change="updatetoDate" class="form-control">
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
    <div>
        <div class="table-container scrollable-table">
        <table>
            <thead style="position:sticky;top:0;">
                <tr class="first-header-row" style="background-color:#ebf5ff;border-bottom: 1px solid #cbd5e1;">
                    <th class="date fixed-column" style="font-weight:normal;font-size:12px;padding-top:16px;color:#778899;background-color:#eef7fa;border-right:1px solid #cbd5e1;">General&nbsp;Details</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="font-weight:normal;font-size:12px;padding-top:16px;color:#778899;">Session&nbsp;1<i class="fa fa-caret-{{ $this->moveCaretLeftSession1 ? 'left' : 'right' }}" style="cursor:pointer;" wire:click="toggleCaretDirectionForSession1"></i></th>
                    @if($this->moveCaretLeftSession1==true)
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @endif
                    <th style="font-weight:normal;font-size:12px;padding-top:16px;color:#778899;">Session&nbsp;2<i class="fa fa-caret-{{ $this->moveCaretLeftSession2 ? 'left' : 'right' }}" style="cursor:pointer;" wire:click="toggleCaretDirectionForSession2"></i></th>
                    @if($this->moveCaretLeftSession2==true)
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    @endif


                </tr>
            
              
                <tr class="second-header-row" style="border-bottom: 1px solid #cbd5e1;">
                    <th class="date fixed-column" style="font-weight:normal;font-size:12px;padding-top:16px;border-right:1px solid #cbd5e1;">Date</th>

                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Shift</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Attendance&nbsp;Scheme</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">First&nbsp;In</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Last&nbsp;Out</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Work&nbsp;Hrs</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Actual&nbsp;Hrs</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Status</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Swipe&nbsp;Details</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Exception</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Shortfall&nbsp;Hrs</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Excess&nbsp;Hrs</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Shift&nbsp;Timings</th>
                    @if($this->moveCaretLeftSession1==true)
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Start&nbsp;Time</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Late&nbsp;In</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">End&nbsp;Time</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Early&nbsp;Out</th>
                    @endif
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Shift&nbsp;Timings</th>
                    @if($this->moveCaretLeftSession2==true)
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Start&nbsp;Time</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Late&nbsp;In</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">End&nbsp;Time</th>
                    <th class="date" style="font-weight:normal;font-size:12px;padding-top:16px;">Early&nbsp;Out</th>
                    @endif

                </tr>
            </thead>     
                @php
                use Carbon\Carbon;

                $fromDate = Carbon::parse($fromDate); // Assuming $fromDate is in 'Y-m-d' format
                $toDate = Carbon::parse($toDate); // Assuming $toDate is in 'Y-m-d' format
                $currentMonthRep = $fromDate->format('M');
                $currentYear = $fromDate->year;
                @endphp
                
                @for ($date = $fromDate; $date->lte($toDate); $date->addDay())
                @php
                $dateKey = $date->format('d M Y');
                $dateKeyForLookup = $date->format('Y-m-d');
                $dayName = $date->format('D');
                $isWeekend = ($dayName == 'Sat' || $dayName == 'Sun');
                $isPresent = $distinctDates->has($dateKeyForLookup);
                $isRegularised = $this->isEmployeeRegularisedOnDate($date->toDateString());
                $isOnLeave=$this->isEmployeeLeaveOnDate($date,$employeeIdForTable);
                $isOnFullDayLeave=$this->isEmployeeFullDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['fullsessionCheck'];
                $isOnFullDayLeaveType=$this->isEmployeeFullDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['leaveRecordType'];
                $isOnHalfDayLeave=$this->isEmployeeHalfDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['sessionCheck'];
                $isOnHalfDayLeaveType=$this->isEmployeeHalfDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['sessionCheckleaveType'];
                $isOnHalfDayLeaveForDifferentSessions=$this->isEmployeeHalfDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['doubleSessionCheck'];
                $session1leaveTypeForHalfDay1=$this->isEmployeeHalfDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['session1leaveType'];
                $session2leaveTypeForHalfDay2=$this->isEmployeeHalfDayLeaveOnDate($date->toDateString(),$employeeIdForTable)['session2leaveType'];
                $leaveType=$this->detectEmployeeLeaveType($date,$employeeIdForTable);
                $holidayNote = in_array($dateKeyForLookup, $holiday);
                $isDate = ($dateKeyForLookup < $todaysDate);
                    $swipeRecordExists=$swiperecord->contains(function ($record) use ($dateKeyForLookup) {
                    return \Carbon\Carbon::parse($record->created_at)->toDateString() === $dateKeyForLookup;
                    });

                   if($distinctDates->has($dateKeyForLookup)||$isRegularised)
                   {
                    $record = $distinctDates[$dateKeyForLookup];
                    $standardHours = 9;
                    $standardMinutes = 0;
                   
                        $firstInTimestamp = strtotime($record['first_in_time']);
                        $lastOutTimestamp = strtotime($record['last_out_time']);
                    
                    
                    $standardWorkingMinutes = ($standardHours * 60) + $standardMinutes;
                    $differenceInSeconds = $lastOutTimestamp - $firstInTimestamp;
                    $totalSecondsWorked += $differenceInSeconds;
                    $hours = floor($differenceInSeconds / 3600);
                    $minutes = floor(($differenceInSeconds % 3600) / 60);
                    $totalWorkedMinutes = ($hours * 60) + $minutes;
                    $checkRecord=!empty($firstInTimestamp)||!empty($lastOutTimestamp);
                    }
                    
                    @endphp
                <tbody>   
                    <tr style="border-bottom: 1px solid #cbd5e1;background-color:{{$isDate ? ($isWeekend ? '#f8f8f8' : ($holidayNote ? '#f3faff' : ($isOnLeave||$isOnFullDayLeave||$isOnHalfDayLeaveForDifferentSessions ? 'rgb(252, 242, 255)':($isOnHalfDayLeave|| ($totalWorkedMinutes < 270 && $totalWorkedMinutes != null && $totalWorkedMinutes > 0)? '#fff':(($isPresent || $swipeRecordExists)&& $totalWorkedMinutes  > 0 && $checkRecord ? '#edfaed' : '#fcf0f0')))))  : 'white'}};">
                        <td class="date fixed-column" style="font-weight:normal;font-size:12px;padding-top:16px;border-right:1px solid #cbd5e1;background-color:{{$isDate ? ($isWeekend ? '#f8f8f8' : ($holidayNote ? '#f3faff' : ($isOnLeave||$isOnFullDayLeave||$isOnHalfDayLeaveForDifferentSessions ? 'rgb(252, 242, 255)':($isOnHalfDayLeave|| ($totalWorkedMinutes < 270 && $totalWorkedMinutes != null && $totalWorkedMinutes > 0)? '#fff':(($isPresent || $swipeRecordExists)&& $totalWorkedMinutes  > 0 && $checkRecord ? '#edfaed' : '#fcf0f0')))))  : 'white'}}; ">
                            <p style="white-space:nowrap;">
                                {{ $date->format('d') }}&nbsp;&nbsp;{{$currentMonthRep}}&nbsp;{{$currentYear}}({{$dayName}})
                                @if($swipeRecordExists)
                            <div class="down-arrow-reg"></div>
                            @endif
                            </p>
                        </td>

                        <td style="font-weight:normal;font-size:12px;padding-top:16px;white-space:nowrap; ">{{\Carbon\Carbon::parse($employeeShiftDetails->shift_start_time)->format('H :')}}({{$employeeShiftDetails->shift_name}})</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;white-space:nowrap; ">{{\Carbon\Carbon::createFromFormat('H:i:s', $employeeShiftDetails->shift_start_time)->format('H:i A') }} to {{\Carbon\Carbon::createFromFormat('H:i:s', $employeeShiftDetails->shift_end_time)->format('H:i A') }} </td>

                        @if($distinctDates->has($dateKeyForLookup))
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">
                            @if($isDate&&!$isWeekend&&!$isOnLeave&&!$holidayNote)
                            {{ date('H:i', strtotime($record['first_in_time'])) }}
                            @else
                            00:00
                            @endif
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">
                            @if($isDate&&!$isWeekend&&!$isOnLeave&&!$holidayNote)
                            @if(empty($record['last_out_time']))
                            {{ date('H:i', strtotime($record['first_in_time'])) }}
                            @else
                            {{ date('H:i', strtotime($record['last_out_time'])) }}
                            @endif
                            @else
                            00:00
                            @endif
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">
                            @if($isDate == false||$isWeekend||$isOnLeave||$holidayNote)
                            00:00
                            @elseif(empty($record['last_out_time']))
                            00:00
                            @else
                            {{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">  
                            @if($isDate == false||$isWeekend||$isOnLeave||$holidayNote)
                            00:00
                            @elseif(empty($record['last_out_time']))
                            00:00
                            @else
                            @php
                            $totalHoursWorked+=$hours;
                            $totalMinutesWorked+=$minutes;
                            @endphp
                            {{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        </td>
                        @else
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px; ">00:00</td>
                        @endif

                        <td style="margin-left:10px; margin-top:20px; font-size:12px;color: {{ $isDate ? ($isWeekend ? 'black' : ($holidayNote ? 'black' : ($isOnLeave||$isOnFullDayLeave ? 'black' : ($distinctDates->has($dateKeyForLookup) ? 'black' : '#ff6666')))) : 'black'}};">
                            @if($isDate)
                            @if($isWeekend)
                            O
                            @php $offCount++; @endphp
                            @elseif($holidayNote)
                            H 
                            @php $holidaycountforcontainer++; @endphp
                            @elseif($isOnFullDayLeave==true)
                              @if($isOnFullDayLeaveType=='Casual Leave')
                                 CL
                              @elseif($isOnFullDayLeaveType=='Casual Leave Probation')
                                 CLP   
                              @elseif($isOnFullDayLeaveType=='Loss Of Pay')   
                                 LOP
                              @elseif($isOnFullDayLeaveType=='Marriage Leave') 
                                 ML 
                              @elseif($isOnFullDayLeaveType=='Paternity Leave') 
                                 PL 
                              @elseif($isOnFullDayLeaveType=='Maternity Leave') 
                                 MTL    
                              @elseif($isOnFullDayLeaveType=='Sick Leave') 
                                 SL
                              @endif
                            
                            @php $leaveCount++; @endphp
                            @elseif($isOnHalfDayLeaveForDifferentSessions==true)
                            <span style="white-space:nowrap;">
                               @if($session1leaveTypeForHalfDay1!=null)
                                                @if($session1leaveTypeForHalfDay1=='Casual Leave')

                                                CL

                                                @elseif($session1leaveTypeForHalfDay1=='Casual Leave Probation')

                                                CLP  

                                                @elseif($session1leaveTypeForHalfDay1=='Loss Of Pay')  

                                                LOP

                                                @elseif($session1leaveTypeForHalfDay1=='Marriage Leave')

                                                ML

                                                @elseif($session1leaveTypeForHalfDay1=='Paternity Leave')

                                                PL

                                                @elseif($session1leaveTypeForHalfDay1=='Maternity Leave')

                                                MTL    

                                                @elseif($session1leaveTypeForHalfDay1=='Sick Leave')

                                                SL

                                                @endif
                                @endif
                                :
                               @if($session2leaveTypeForHalfDay2!=null)
                               @if($session2leaveTypeForHalfDay2=='Casual Leave')

                                            CL

                                            @elseif($session2leaveTypeForHalfDay2=='Casual Leave Probation')

                                            CLP  

                                            @elseif($session2leaveTypeForHalfDay2=='Loss Of Pay')  

                                            LOP

                                            @elseif($session2leaveTypeForHalfDay2=='Marriage Leave')

                                            ML

                                            @elseif($session2leaveTypeForHalfDay2=='Paternity Leave')

                                            PL

                                            @elseif($session2leaveTypeForHalfDay2=='Maternity Leave')

                                            MTL    

                                            @elseif($session2leaveTypeForHalfDay2=='Sick Leave')

                                            SL

                                            @endif
                                           
                               @endif     
                               </span>
                               @php $leaveCount++; @endphp
                            @elseif($isOnHalfDayLeave==true)
                              
                            @if($isPresent)
                               @if($isOnHalfDayLeaveType=='Casual Leave')
                                  CL:P
                                @elseif($isOnHalfDayLeaveType=='Casual Leave Probation')
                                  CLP:P
                                @elseif($isOnHalfDayLeaveType=='Loss Of Pay')
                                  LOP:P
                                @elseif($isOnHalfDayLeaveType=='Marriage Leave')
                                  ML:P
                                @elseif($isOnHalfDayLeaveType=='Paternity Leave')
                                  PL:P  
                                @elseif($isOnHalfDayLeaveType=='Maternity Leave')
                                  MTL:P
                                @elseif($isOnHalfDayLeaveType=='Sick Leave')
                                  SL:P 
                                @endif                
                                @php 
                                  $leaveCount+=0.5;
                                  $presentCount+=0.5;
                                @endphp
                            @else
                               @if($isOnHalfDayLeaveType=='Casual Leave')
                                  CL:<span style="color:#f66;">A</span>
                                @elseif($isOnHalfDayLeaveType=='Casual Leave Probation')
                                  CLP:<span style="color:#f66;">A</span>
                                @elseif($isOnHalfDayLeaveType=='Loss Of Pay')
                                  LOP:<span style="color:#f66;">A</span>
                                @elseif($isOnHalfDayLeaveType=='Marriage Leave')
                                  ML:<span style="color:#f66;">A</span>
                                @elseif($isOnHalfDayLeaveType=='Paternity Leave')
                                  PL:<span style="color:#f66;">A</span> 
                                @elseif($isOnHalfDayLeaveType=='Maternity Leave')
                                  MTL:<span style="color:#f66;">A</span>
                                @elseif($isOnHalfDayLeaveType=='Sick Leave')
                                  SL:<span style="color:#f66;">A</span> 
                                @endif                
                                @php 
                                  $leaveCount+=0.5;
                                  $absentCount+=0.5;
                                @endphp

                            @endif
                           
                            
                            @elseif($isOnLeave)
                              @if($leaveType=='Casual Leave')
                                 CL
                              @elseif($leaveType=='Casual Leave Probation')
                                 CLP   
                              @elseif($leaveType=='Loss Of Pay')   
                                 LOP
                              @elseif($leaveType=='Marriage Leave') 
                                 ML 
                              @elseif($leaveType=='Paternity Leave') 
                                 PL 
                              @elseif($leaveType=='Maternity Leave') 
                                 MTL    
                              @elseif($leaveType=='Sick Leave') 
                                 SL
                              @endif
                            @php $leaveCount++; @endphp
                            
                            @elseif($totalWorkedMinutes < 270 && $totalWorkedMinutes > 0 )
                            
                               P:<span style="color:#f66;">A</span>
                               @php 
                                 $presentCount+=0.5;
                                 $absentCount+=0.5;
                               @endphp
                            @elseif($distinctDates->has($dateKeyForLookup) && $totalWorkedMinutes > 270)
                            P
                            @php $presentCount++; @endphp
                           
                            @else
                               <span style="color:#f66;">A</span>
                               @php 
                                 
                                 $absentCount++;
                               @endphp  
                            @endif
                            @else
                            -
                            @endif
                        </td>

                        <td>
                            <button type="button" style="font-size:12px;background-color: 
           transparent
           ;color:#24a7f8;border:none;text-decoration:underline;" wire:click="viewDetails('{{ $dateKeyForLookup }}')">
                                Info
                            </button>
                        </td>

                        <td style="font-weight:normal;font-size:12px;padding-top:16px;color:#778899;">No&nbsp;attention&nbsp;required</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @if($totalWorkedMinutes < $standardWorkingMinutes && !empty($record['last_out_time']) && !$isWeekend && !$holidayNote && $isPresent &&$isDate)
                                @php
                                $shortfalltime=$standardWorkingMinutes - $totalWorkedMinutes;
                                $shortfallHours=floor($shortfalltime / 60);
                                $shortfallMinutes=$shortfalltime % 60;

                                $totalshortfallHoursWorked+=$shortfallHours;
                                $totalshortfallMinutesWorked+=$shortfallMinutes;

                                @endphp
                                {{ str_pad($shortfallHours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($shortfallMinutes, 2, '0', STR_PAD_LEFT) }}
                                @else
                                00:00
                                @endif
                                </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @if($totalWorkedMinutes > $standardWorkingMinutes && !empty($record['last_out_time']) && !$isWeekend && !$holidayNote && $isPresent&& $isDate)
                            @php
                            $excesstime = $totalWorkedMinutes - $standardWorkingMinutes;
                            $excessHours = floor($excesstime / 60);
                            $excessMinutes = $excesstime % 60;

                            $totalexcessHoursWorked+=$excessHours;
                            $totalexcessMinutesWorked+=$excessMinutes;
                            @endphp
                            {{ str_pad($excessHours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($excessMinutes, 2, '0', STR_PAD_LEFT) }}
                            @else
                            00:00
                            @endif
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">10:00-14:00</td>
                        @if($this->moveCaretLeftSession1)
                        @if($distinctDates->has($dateKeyForLookup))
                        @php
                        $record = $distinctDates[$dateKeyForLookup];
                        $firstInTime = \Carbon\Carbon::parse($record['first_in_time']);
                        $lateArrivalTime = $firstInTime->diff(\Carbon\Carbon::parse('10:00'))->format('%H:%I');
                        $isLateBy10AM = $firstInTime->format('H:i') > '10:00';
                        @endphp
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @if($isDate)
                            {{ date('H:i', strtotime($record['first_in_time'])) }}
                            @else
                            00:00
                            @endif

                        </td>
                        @if($isDate==false)
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        @elseif($isLateBy10AM)
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">{{$lateArrivalTime}}</td>
                        @else
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        @endif
                        @else
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        @endif

                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>

                        @endif
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">14:01-19:00</td>
                        @if($this->moveCaretLeftSession2==true)


                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                        @if($distinctDates->has($dateKeyForLookup)&&$isDate)
                        @php

                        $record = $distinctDates[$dateKeyForLookup];
                        $firstInTime = \Carbon\Carbon::parse($record['last_out_time']);
                        $lateArrivalTime = $firstInTime->diff(\Carbon\Carbon::parse('19:00'))->format('%H:%I');
                        $isEarlyBy07PM = $firstInTime->format('H:i') < '19:00' ; @endphp @if(empty($record['last_out_time'])) @php $record['last_out_time']=$record['first_in_time']; $firstInTime=\Carbon\Carbon::parse($record['last_out_time']); $lateArrivalTime=$firstInTime->diff(\Carbon\Carbon::parse('19:00'))->format('%H:%I');
                            $isEarlyBy07PM = $firstInTime->format('H:i') < '19:00' ; @endphp <td style="font-weight:normal;font-size:12px;padding-top:16px;">{{ date('H:i', strtotime($record['last_out_time'])) }}</td>
                                @else

                                <td style="font-weight:normal;font-size:12px;padding-top:16px;">{{ date('H:i', strtotime($record['last_out_time'])) }}</td>
                                @endif
           @if($isEarlyBy07PM)
          <td style="font-weight:normal;font-size:12px;padding-top:16px;">{{$lateArrivalTime}}</td>
                                @else
                                <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                                @endif

                                @else
                                <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                                <td style="font-weight:normal;font-size:12px;padding-top:16px;">00:00</td>
                                @endif
                                @endif
                                @php
                                $holidayNote=false;
                                $totalWorkedMinutes=0;
                                @endphp

                    </tr>
                    @endfor
                 
                    <tr style="border-bottom: 1px solid #cbd5e1;background-color:white;">
                        <td class="date fixed-column" style="font-weight:normal;font-size:12px;padding-top:16px;border-right:1px solid #cbd5e1;">Total </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @php
                            $extraHours = floor($totalMinutesWorked / 60);
                            $totalHoursWorked += $extraHours;
                            $remainingMinutes = $totalMinutesWorked % 60;

                            // Format total hours and minutes as HH:MM
                            $formattedTotalHours = str_pad($totalHoursWorked, 2, '0', STR_PAD_LEFT);
                            $formattedTotalMinutes = str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT);
                            @endphp
                            
                            {{ $formattedTotalHours }}:{{ $formattedTotalMinutes }}
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;"> {{ $formattedTotalHours }}:{{ $formattedTotalMinutes }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @php
                            $extraShortFallHours = floor($totalshortfallMinutesWorked / 60);
                            $totalShortFallHoursWorked += $extraShortFallHours;
                            $remainingShortFallMinutes = $totalshortfallMinutesWorked % 60;

                            // Format total hours and minutes as HH:MM
                            $formattedShortFallTotalHours = str_pad($totalShortFallHoursWorked, 2, '0', STR_PAD_LEFT);
                            $formattedShortFallTotalMinutes = str_pad($remainingShortFallMinutes, 2, '0', STR_PAD_LEFT);
                            @endphp
                            {{$formattedShortFallTotalHours}}:{{$formattedShortFallTotalMinutes}}
                        </td>
                        <td style="font-weight:normal;font-size:12px;padding-top:16px;">
                            @php
                            $extraexcessHours = floor($totalexcessMinutesWorked / 60);
                            $totalexcessHoursWorked += $extraexcessHours;
                            $remainingExcessMinutes = $totalexcessMinutesWorked % 60;

                            // Format total hours and minutes as HH:MM
                            $formattedExcessTotalHours = str_pad($totalexcessHoursWorked, 2, '0', STR_PAD_LEFT);
                            $formattedExcessTotalMinutes = str_pad($remainingExcessMinutes, 2, '0', STR_PAD_LEFT);
                            @endphp
                            {{$formattedExcessTotalHours}}:{{$formattedExcessTotalMinutes}}
                        </td>
                        <td></td>
                        @if($this->moveCaretLeftSession1==true)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                        <td></td>
                        @if($this->moveCaretLeftSession2==true)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                    </tr>
                    </tbody>
            </table>
        </div>
        @if ($showAlertDialog)
        @php
        $formattedDate = \Carbon\Carbon::parse($dateforpopup)->format('d M');
        @endphp
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Swipe Details for {{$formattedDate}}</b></h5>
                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="close" style="background-color: white; height:10px;width:10px;">
                        </button>
                    </div>
                    <div class="modal-body">

                        @if($viewDetailsInswiperecord&&$dateforpopup<$todaysDate)
                            @php

                            $firstInTimestamp=strtotime($viewDetailsInswiperecord->swipe_time);
                            if($viewDetailsOutswiperecord)
                            {
                            $lastOutTimestamp = strtotime($viewDetailsOutswiperecord->swipe_time);
                            $differenceInSeconds = $lastOutTimestamp - $firstInTimestamp;
                            // Calculate hours and minutes
                            $hours = floor($differenceInSeconds / 3600);
                            $minutes = floor(($differenceInSeconds % 3600) / 60);
                            }
                            @endphp
                            <div class="row">
                                <div class="col" style="font-size: 12px;color:#778899;font-weight:500;white-space:nowrap;">Employee Name : <span style="color: #333;">{{ucwords(strtolower($employeeDetails->first_name))}}&nbsp;{{ucwords(strtolower($employeeDetails->last_name))}}</span></div>
                                <div class="col" style="font-size: 12px;color:#778899;font-weight:500;">Employee ID : <span style="color: #333;">{{$employeeDetails->emp_id}}</span></div>
                                <div class="col" style="font-size: 12px;color:#778899;font-weight:500;">Access Card Number:</div>
                            </div>
                            <div class="swipes-table mt-4 border" style="max-height: 300px; overflow-y: auto; display: block;">
                                <table style="width: 100%;">
                                    <tr style="background-color: #f6fbfc;">
                                        <th style="width:15%;font-size: 12px; text-align:start;padding:5px;color:#778899;font-weight:500;white-space:nowrap;">In/Out</th>
                                        <th style="width:15%;font-size: 12px; text-align:start;padding:5px;color:#778899;font-weight:500;white-space:nowrap;">Swipe Time</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Location</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Status</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Last Updated On</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Modified Time</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Updated By</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Longitude</th>
                                        <th style="width:30%;font-size: 12px; text-align:start;padding:10px 10px;color:#778899;font-weight:500;white-space:nowrap;">Latitude</th>
                                    </tr>


                                    <tr style="border:1px solid #ccc;">

                                        <td style="width:15%;font-size: 10px; color: #778899;text-align:start;padding:10px 10pxwhite-space:nowrap;"> {{$viewDetailsInswiperecord->in_or_out}} </td>
                                        <td style="width:15%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px;"> {{$viewDetailsInswiperecord->swipe_time}} </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> -</td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> -</td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                    </tr>
                                    <tr style="border:1px solid #ccc;">

                                        @if($viewDetailsOutswiperecord)
                                        <td style="width:15%;font-size: 10px; color: #778899;text-align:start;padding:5px;white-space:nowrap;"> {{$viewDetailsOutswiperecord->in_or_out}} </td>
                                        <td style="width:15%;font-size: 10px; color: #778899;text-align:start;padding:10px"> {{$viewDetailsOutswiperecord->swipe_time}} </td>
                                        @else
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px;white-space:nowrap;"> OUT </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> {{$viewDetailsInswiperecord->swipe_time}} </td>
                                        @endif
                                        @if($viewDetailsOutswiperecord)
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px">-</td>
                                        @else
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px;white-space:nowrap;">System inserted out</td>
                                        @endif
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> -</td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> - </td>
                                    </tr>


                                    <tr style="border:1px solid #ccc; background-color: #f0f0f0;">
                                        @if($viewDetailsOutswiperecord)
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px;white-space:nowrap;">Actual Hours :{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }} hrs {{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }} mins</td>
                                        @else
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px;white-space:nowrap;">Actual Hours :00 hrs 00 mins </td>
                                        @endif
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"></td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                        <td style="width:30%;font-size: 10px; color: #778899;text-align:start;padding:10px 10px"> </td>
                                    </tr>


                                </table>
                            </div>
                            @else
                            <div style="text-align:center;">
                                <img src="https://linckia.cdn.greytip.com/static-ess-v6.3.0-prod-1543/attendace_swipe_empty.svg" style="margin-top:30px;">
                                <div class="text-muted">No record available</div>
                            </div>
                            @endif
                            <div style="display: flex; justify-content: center; margin-top: 20px;">
                                <button class="cancel-btn" style="border:1px solid rgb(2, 17, 79);" wire:click="close">Close</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>

        @endif
    </div>
    <div class="custom-container">
        <div class="row">
            <div class="col p-0 m-2" style="white-space:nowrap;background-color:#edfaed;color:#778899;text-align:center;font-size:12px;">Present:<span style="font-weight:600;"> {{$presentCount}}</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color:rgb(252, 242, 255);color:#778899;text-align:center;font-size:12px;">Leave:<span style="font-weight:600;"> {{$leaveCount}}</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color:#f3faff;color:#778899;text-align:center;font-size:12px;">Holiday:<span style="font-weight:600;"> {{$holidaycountforcontainer}}</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color:#f8f8f8;color:#778899;text-align:center;font-size:12px;">Rest Day:<span style="font-weight:600;"> 0</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color: #fcf0f0;color:#778899;text-align:center;font-size:12px;">Absent:<span style="font-weight:600;"> {{$absentCount}}</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color: #fff7eb;color:#778899;text-align:center;font-size:12px;">On Duty:<span style="font-weight:600;"> 0</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color: rgba(252, 217, 151,1);color:#778899;text-align:center;font-size:12px;">Shutdown:<span style="font-weight:600;"> 0</span></div>
            <div class="col p-0 m-2" style="white-space:nowrap;background-color:#f8f8f8;color:#778899;text-align:center;font-size:12px;">Off Day:<span style="font-weight:600;"> {{$offCount}}</span></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto p-1 m-2" style="white-space:nowrap;background-color:#f3faff;color:#778899;text-align:center;font-size:12px;">Restricted Holiday:<span style="font-weight:600;"> 0</span></div>
            <div class="col-auto p-1 m-2" style="white-space:nowrap;background-color: #fcf0f0;color:#778899;text-align:center;font-size:12px;">Status Unknown:<span style="font-weight:600;"> 0</span></div>
        </div>
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
                <p class="me-2 mb-0">
                <div class="down-arrow-ign-attendance-info"></div>
                </p>
                <p class="legendtext m-0">Ignored</p>
            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="me-2 mb-0">
                <div class="down-arrow-gra"></div>
                </p>
                <p class="legendtext m-0">Grace</p>
            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="me-2 mb-0">
                <div class="down-arrow-reg"></div>
                </p>
                <p class="legendtext m-0">Regularized</p>
            </div>
        </div>
        <div class="row m-0 mb-3">
            <h6 class="m-0 p-2 mb-2" style="background-color: #f1f4f7">Day Type</h6>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="mb-0">
                    <i class="fas fa-mug-hot"></i>
                </p>
                <p class="m-1 attendance-legend-text">Rest Day</p>
            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="mt-1">
                    <i class="fas fa-tv"></i>
                </p>
                <p class="m-1 attendance-legend-text">Off Day</p>
            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="mt-0">
                    <i class="fas fa-umbrella"></i>
                </p>
                <p class="m-1 attendance-legend-text">Holiday</p>
            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex;margin-left:-10px">
                    <p class="mb-0">
                        <img src="{{ asset('images/half-day-session1-present.png') }}"  height="20" width="20">
                    </p>
                    <p class="m-1  pb-2 attendance-legend-text">Half Day(Session 1)</p>
            </div>
            <div class="d-flex" style="gap: 10px;"> 
            <div class="col-md-3 mb-2" style="display: flex;margin-left:-2px">
                                <p class="mb-0">
                                <img src="{{ asset('images/half-day-session2-present.png') }}"  height="20" width="20">
                                </p>
                                <p class="m-1  pb-2 attendance-legend-text">Half Day(Session 2)</p>
                            </div>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="mb-0">
                    <i class="fas fa-battery-empty"></i>
                </p>
                <p class="m-1 attendance-legend-text">IT Maintanance</p>
            </div>
            </div>
        </div>
        <div class="row m-0 mb-3">
            <h6 class="m-0 p-2 mb-2" style="background-color: #f1f4f7">Leave Type</h6>
            <div class="col-md-3 mb-2 pe-0" style="display: flex">
                <p class="me-2 mb-0">
                    <span class="legendsIcon sickleaveIcon">SL</span>
                </p>
                <p class="legendtext m-0">Sick Leave</p>
            </div>



        </div>
    </div>

</div>
</div>