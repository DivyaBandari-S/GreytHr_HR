<!DOCTYPE html>
<html>
<head>
    <title>Leave Approval Reminder</title>
</head>
<body>
    <p>Dear Approver,</p>

    <p>This is a reminder that your team member, <strong>{{ $employeeName }}</strong>, has applied for leave. Please review and take the necessary action on their leave request.</p>

    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th colspan="2" style="text-align: center; font-weight: bold;">Leave Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Category</td>
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

    <p>Thank you.</p>
</body>
</html>
