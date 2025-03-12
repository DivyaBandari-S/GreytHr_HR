<!DOCTYPE html>
<html>
<head>
    <title>Regularisation Rejection Mail</title>
</head>
<body>

    <h1>Shift Override Mail</h1>
    <p>Your shift Timings has been mentioned for the following dates:</p>
    
    <div style="margin-bottom: 20px;">
        <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Shift Type</th>
                </tr>
             
            </thead>
            <tbody>
            @if(!empty($details['shiftEntries']) && is_array($details['shiftEntries']))
                    @foreach($details['shiftEntries'] as $entry)
                        <tr>
                            <td class="text-center"style="width:10%;white-space:nowrap;text-align:center;">{{ $details['employee_id']}}</td>
                            <td class="text-center"style="width:30%;white-space:nowrap;text-align:center;">{{ $details['employee_name']}}</td>
                            <td class="text-center"style="width:30%;white-space:nowrap;text-align:center;">{{ \Carbon\Carbon::parse($entry['from_date'])->format('jS F,Y') }}</td>
                            <td class="text-center"style="width:30%;white-space:nowrap;text-align:center;">{{ \Carbon\Carbon::parse($entry['to_date'])->format('jS F,Y') }}</td>
                            <td class="text-center"style="width:30%;text-align:center;">
                                @if($entry['shift_type']=='AS')
                                    <span style="white-space:nowrap;">Afternoon Shift</span>
                                @elseif($entry['shift_type']=='ES')    
                                     <span style="white-space:nowrap;">Evening Shift</span>
                                @elseif($entry['shift_type']=='GS')    
                                     <span style="white-space:nowrap;">General Shift</span>   
                                @endif       
                            </td>
                        </tr>
                    @endforeach
                  
            @else
                <tr>
                    <td colspan="5">No shift details available.</td>
                </tr>
            @endif
        
            </tbody>
            </table>
            @if(!empty($details['']) && is_array($details['regularisationRequests']))
            <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr colspan="5">
                    <th>Employee Remarks:<span style="font-weight:normal;">{{ $details['sender_remarks']}}</span></th>
                    
                </tr>
             
            </thead>
           
            </table>
            @endif
            @if(!empty($details['regularisationRequests']) && is_array($details['regularisationRequests']))
            <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr colspan="5">
                    <th>Approver Remarks:<span style="font-weight:normal;">{{ $details['receiver_remarks']}}</span></th>
                    
                </tr>
             
            </thead>
           
            </table>
            @endif
            <p style="font-size: 12px; color: gray; margin-top: 20px;">
                <strong>Note:</strong> This is an auto-generated mail. Please do not reply.
            </p>
            </div>
</body>
</html>
