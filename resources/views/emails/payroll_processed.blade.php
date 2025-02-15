<!DOCTYPE html>
<html>
<head>
    <title>Payslip Released</title>
</head>
<body>
    <p>Dear {{ $employee->first_name }} {{ $employee->last_name }},</p>

    <p>Your payslip  for the month of <strong>{{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
    </strong> has been released.</p>

    <p>Thank you</p>
   <p>This is an auto-generated mail. Please do not reply.</p> 
   
</body>
</html>
