<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <p>Hi,</p>
    @if($leaveRequest->category_type === 'Leave')
    @if($leaveRequest->leave_status === 4)
    <p><strong>{{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }} [{{ $employeeDetails->emp_id }}]</strong>has withdrawn the leave.</p>
    @else
    <p><strong>{{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }} [{{ $employeeDetails->emp_id }}]</strong> has applied for a leave.</p>
    <p>Please log on to and review the leave application.</p>
    @endif
    @else
    @if($leaveRequest->cancel_status === 4)
    <p><strong>{{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }} [{{ $employeeDetails->emp_id }}]</strong>has withdrawn the leave cancel.</p>
    @else
    <p><strong>{{ ucwords(strtolower($employeeDetails->first_name)) }} {{ ucwords(strtolower($employeeDetails->last_name)) }} [{{ $employeeDetails->emp_id }}]</strong> has applied for a leave cancel.</p>
    <p>Please log on to and review the leave cancel application.</p>
    @endif
    @endif

    <h3>Following are the leave details:</h3>
    <ul>
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; font-weight: bold;">Leave Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-500">Category</td>
                    <td>{{ $leaveRequest->category_type }}</td>
                </tr>
                <tr>
                    <td class="fw-500">Leave Type</td>
                    <td>{{ $leaveRequest->leave_type }}</td>
                </tr>
                <tr>
                    <td class="fw-500">From Date</td>
                    <td>{{ $leaveRequest->from_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="fw-500">To Date</td>
                    <td>{{ $leaveRequest->to_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="fw-500">From Session</td>
                    <td>{{ $leaveRequest->from_session }}</td>
                </tr>
                <tr>
                    <td class="fw-500">To Session</td>
                    <td>{{ $leaveRequest->to_session }}</td>
                </tr>
                <tr>
                    <td class="fw-500">Number of Days</td>
                    <td>{{ $numberOfDays }}</td>
                </tr>
                <tr>
                    <td class="fw-500">Reason</td>
                    <td>
                        @if($leaveRequest->category_type === 'Leave')
                        {{ $leaveRequest->reason }}
                        @else
                        {{ $leaveRequest->leave_cancel_reason }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        @if($leave_status === 4)
        <p></p>
        @else
        <p><a href="https://s6.payg-india.com/employees-review">Click here </a>to approve/reject/view this request.</p>
        @endif
        <p>Regards</p>

        <p>Note: This is an auto-generated mail. Please do not reply.</p>

        <p>PS: "This e-mail is generated from info@s6.payg-india.com"</p>
</body>

</html>