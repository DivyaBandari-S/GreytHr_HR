<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        .leave-transctions {
            background: #fff;
            margin: 0 auto;
            box-shadow: 0 3px 10px 0 rgba(0, 0, 0, 0.2);
        }

        .pdf-heading {
            text-align: center;
        }

        /* Header Styles */
        .pdf-heading h2 {
            color: black;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .pdf-heading span p {
            font-size: 0.700rem;
            font-weight: 500;
            margin-top: 2px;
            color: #36454F;
        }

        .pdf-heading h3 {
            font-weight: 500;
            margin-top: -5px;
            font-size: 0.925rem;
        }




        .header {
            text-align: center;
            font-size: 20px;
            font-weight: 500;
        }

        .header1 {
            text-align: center;
            font-size: 18px;
            font-weight: 500;
        }

        .paragraph {
            font-size: 14px;
            text-align: center;
        }

        .details {
            margin-bottom: 20px;
        }

        .details div {
            margin-bottom: 5px;
        }

        .emp-details {
            padding: 20px;

        }

        .emp-details p {
            font-weight: 500;
            font-size: 0.875rem;
            color: black;
        }

        .emp-details span {
            font-weight: 400;
            font-size: 0.855rem;
            color: #333;
        }

        .emp-info {
            display: flex;
            justify-content: center;
            border: 1px solid #333;
            margin: 20px 0;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1.5px solid #808080;
            padding: 8px;
            text-align: left;
        }

        td {
            font-size: 0.825rem;
        }

        th {
            font-size: 0.875rem;
            background-color: #C0C0C0;
        }
    </style>
    <div class="leave-transctions">
        <div class="pdf-heading">
            @php
            use App\Models\EmployeeDetails;
            use App\Models\Company;
            $employeeId = auth()->guard('hr')->user()->emp_id;

            // Fetch the company_ids for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Check if companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);
        @endphp
               @foreach ($companyIdsArray as $companyId)
               @php
                   // Fetch the company by company_id
                   $company = Company::where('company_id', $companyId)->where('is_parent', 'yes')->first();
               @endphp

               @if ($company)
                   <!-- Display company details if a matching company is found -->
                   @if ($company->company_id === 'XSS-12345')
                       <img src="data:image/jpeg;base64,{{ $company->company_logo }}">
                       <div>
                           <h2>XSILICA SOFTWARE SOLUTIONS P LTD <br>
                               <span>
                                   <p>3rd Floor, Unit No.4, Kapil Kavuri Hub IT Block, Nanakramguda Main Road,
                                       Hyderabad, Rangareddy, <br> Telangana, 500032</p>
                               </span>
                           </h2>
                           <h3>Leave Transactions From {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to
                               {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
                       </div>
                   @elseif($company->company_id === 'PAYG-12345')
                       <img src="data:image/jpeg;base64,{{ $company->company_logo }}" style="width:200px;height:125px;">
                       <div>
                           <h2> PayG <br>
                               <span>
                                   <p>3rd Floor, Unit No.4, Kapil Kavuri Hub IT Block, Nanakramguda Main Road,
                                       Hyderabad, Rangareddy, <br> Telangana, 500032</p>
                               </span>
                           </h2>
                           <h3>Leave Transactions From {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to
                               {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
                       </div>
                   @elseif($company->company_id === 'AGS-12345')
                       <img src="data:image/jpeg;base64,{{ $company->company_logo }}" alt=""
                           style="width:200px;height:125px;">
                       <div>
                           <h2>Attune Global Solutions<br>
                               <span>
                                   <p>3rd Floor, Unit No.4, Kapil Kavuri Hub IT Block, Nanakramguda Main Road,
                                       Hyderabad, Rangareddy, <br> Telangana, 500032</p>
                               </span>
                           </h2>
                           <h3>Leave Transactions From {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to
                               {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
                       </div>
                   @endif
               @endif
           @endforeach
        </div>
    </div>


    <div class="emp-info">
        <div class="emp-details">
            <p> Name <span style="padding-left: 180px;">: {{ ucwords(strtolower($employeeDetails->first_name))}} {{ ucwords(strtolower($employeeDetails->last_name))}}</span></p>
            <p>Date of Join <span style="padding-left: 142px;">: {{ optional(\Carbon\Carbon::parse($employeeDetails->hire_date))->format('d M, Y') }}
                </span></p>
            <p>Reporting Manager <span style=" padding-left: 94px;">: {{ ucwords($employeeDetails->report_to) }} ({{ $employeeDetails->manager_id}}) </span></p>
            <p>Employee No <span style="padding-left: 134px;">: {{ $employeeDetails->emp_id}}</span></p>
            <p>Date of Birth <span style="padding-left: 133px;">: {{ optional(\Carbon\Carbon::parse($employeeDetails->date_of_birth))->format('d M, Y') }}
                </span></p>
            <p>Gender <span style="padding-left: 166px;">: {{ $employeeDetails->gender}}</span></p>
        </div>
    </div>





    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>SI.No</th>
                <th>Posted Date</th>
                <th>From Date</th>
                <th>To Date</th>
                <th >Days</th>
                <th>Leave Type</th>
                <th>Transaction Type</th>
                <th>Reason</th>


            </tr>
        </thead>
        <tbody>
            @if($leaveTransactions->isEmpty())
            <tr>
                <td colspan="8" style="text-align: center;">No data found</td>
            </tr>
            @else
            @foreach ($leaveTransactions as $transaction)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->from_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->to_date)->format('d M Y') }}</td>
                <td>{{ $transaction->days }}</td>
                <td>{{ $transaction->leave_type }}</td>
                <td>{{ ucwords(strtolower($transaction->leave_status)) }}</td>
                <td>{{ ucwords(strtolower($transaction->reason)) }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</body>

</html>
          
            {{-- @foreach ($leaveRequests as $leaveRequest)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- This shows the index number -->
                <td>{{ $leaveRequest['emp_id'] ?? 'N/A' }}</td> <!-- Assuming emp_id is not present in the provided data -->
                <td>{{ $leaveRequest['leave_type'] }}</td>
                <td>{{ $leaveRequest['created_at'] }}</td>
            </tr>
        @endforeach --}}
        

