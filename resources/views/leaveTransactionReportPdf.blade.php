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

        td:last-child,
        th:last-child {
            width: 200px;
            /* Adjust this value as needed */
            white-space: normal;
        }

        /* Add more width for the table overall */
        .table {
            width: 100%;
            table-layout: fixed;
        }

        .leavesdate {
            font-weight: 500;
            font-size: 12px;
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
                        <img src="data:image/jpeg;base64,{{ $company->company_logo }}"
                            style="width:200px;height:125px;">
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

    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>SI.No</th>
                <th>Employee No </th>
                <th>Name </th>
                <th>Manager No</th>
                <th>Manager Name</th>
                <th>Leave Type</th>
                <th>Transaction Type</th>
                <th>Posted Date</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Days</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>

            {{-- Check if leaveTransactions is empty --}}
            @if ($leaveTransactions->isEmpty())
                <tr>
                    <td class="leavesdate" colspan="12" style="text-align: center; font-weight:600; font-size:15px ;">
                        No data found
                    </td>
                </tr>
            @else
                @php $siNo = 1; @endphp
                {{-- Loop through each leave transaction --}}
                @foreach ($leaveTransactions as $leaveTransaction)
               
                    @if (!isset($leaveTransaction['date']) || empty($leaveTransaction['date']))
                        {{-- Loop through each leave details if it's not a granted leave --}}
                        @foreach ($leaveTransaction['leave_details'] as $transactions)
                            {{-- Loop through each transaction and check for 'Granted' status --}}
                            @foreach ($transactions as $transaction)
                                {{-- Process only Granted leave transactions --}}
                                <tr>
                                    <td>{{ $siNo++ }}</td>
                                    <td>{{ $leaveTransaction['emp_id'] }}</td>
                                    <td>{{ ucwords(strtolower($leaveTransaction['first_name'])) }}
                                        {{ ucwords(strtolower($leaveTransaction['last_name'])) }}</td>
                                    <td>{{ $transaction['manager_id'] }}</td>
                                    <td>{{ ucwords(strtolower($transaction['manager_first_name'])) }}
                                        {{ ucwords(strtolower($transaction['manager_last_name'])) }}</td>
                                    <td>{{ $transaction['leave_name'] }}</td> {{-- Leave Type --}}
                                    <td>{{ $transaction['status'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y H:i') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($transaction['from_date'])->format('d M Y H:i') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($transaction['to_date'])->format('d M Y H:i') }}</td>
                                    <td>{{ $transaction['grant_days'] }}</td> {{-- Grant Days --}}
                                    <td>Annual Grant for the year</td> {{-- Static reason for granted leaves --}}
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        {{-- Process other (non-Granted) transactions --}}


                        @foreach ($leaveTransaction['leave_details'] as $transaction)
                            {{-- Loop through the leave details --}}
                            <tr>
                                <td>{{ $siNo++ }}</td>

                                {{-- Access emp_id, first_name, last_name from the first transaction in leave_details --}}
                                <td>{{ $leaveTransaction['emp_id'] ?? 'No Emp ID' }}</td> {{-- If emp_id doesn't exist, show 'No Emp ID' --}}
                                <td>
                                    {{ ucwords(strtolower($leaveTransaction['first_name'] ?? 'No First Name')) }}
                                    {{ ucwords(strtolower($leaveTransaction['last_name'] ?? 'No Last Name')) }}
                                </td> {{-- Full Name --}}

                                {{-- You can also display the employee's own details --}}
                                <td>{{ $transaction['manager_id'] }}</td>
                                <td>{{ ucwords(strtolower($transaction['manager_first_name'])) }}
                                    {{ ucwords(strtolower($transaction['manager_last_name'])) }}</td>

                                <td>{{ $transaction['leave_type'] }}</td> {{-- Leave Type --}}

                                {{-- Status: Adjust this based on your logic (approved, rejected, etc.) --}}
                                <td>
                                    @if ($transaction['leave_status'] == 2)
                                        Approved
                                    @elseif ($transaction['leave_status'] == 3)
                                        Rejected
                                    @elseif ($transaction['leave_status'] == 4)
                                        Withdrawn
                                    @else
                                        {{($transaction['leave_status'])}}
                                    @endif
                                </td>

                                <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction['leave_from_date'])->format('d M Y H:i') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($transaction['leave_to_date'])->format('d M Y H:i') }}
                                </td>
                                <td>{{ $transaction['leave_days'] }}</td> {{-- Leave Days --}}

                                {{-- If the leave is granted, show a static reason or dynamic reason --}}
                                <td>
                                    @if ($transaction['leave_status'] == 1)
                                        Annual Grant for the year
                                    @else
                                        {{ ucwords(strtolower($transaction['reason'] ?? 'No reason provided')) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif

        </tbody>

    </table>
</body>

</html>
