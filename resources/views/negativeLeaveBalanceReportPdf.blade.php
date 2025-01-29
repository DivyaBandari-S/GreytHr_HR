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
                        <h3>Negative Balance As On {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
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
                        <h3>Negative Balance As On {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
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
                        <h3>Negative Balance As On {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</h3>
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
                <th>Join Date</th>
                <th>Leave Type</th>
                <th>Designation</th>
                <th>Negative Leave Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $filteredTransactions = $leaveTransactions->flatMap(function ($leaveTransaction) {
                    return collect($leaveTransaction['details'])->filter(function ($transaction) {
                        // Normalize the leave_type value by trimming and converting to lower case
                        return trim(strtolower($transaction['leave_type'])) === 'loss of pay';
                    });
                });
            @endphp


            @if ($filteredTransactions->isEmpty())
                <tr>
                    <td class="leavesdate" colspan="7"
                        style="text-align: center; font-weight: 600; font-size: 15px;">No data found</td>
                </tr>
            @else
                @foreach ($filteredTransactions as $transaction)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $transaction['emp_id'] }}</td>
                        <td>{{ ucwords(strtolower($transaction['first_name'])) }}
                            {{ ucwords(strtolower($transaction['last_name'])) }}</td>
                        <td>
                            @if (!empty($transaction['hire_date']))
                                {{ \Carbon\Carbon::parse($transaction['hire_date'])->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $transaction['leave_type'] }}</td>
                        @php
                            $jobTitle = $transaction['job_role'] ?? 'N/A'; // Default to 'N/A' if job_role is not set
                            $convertedTitle = preg_replace('/\bII\b/', 'I', $jobTitle);
                            $convertedTitle = preg_replace('/\bII\b/', 'II', $convertedTitle);
                            $convertedTitle = preg_replace('/\bIII\b/', 'III', $convertedTitle);
                        @endphp
                        <td>{{ $convertedTitle }}</td>
                        <td>{{ $transaction['leave_days'] }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>
</body>

</html>
