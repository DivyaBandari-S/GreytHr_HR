<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip for {{ $salMonth }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .payslip-container {
            width: 80%;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }
        .summary {
            margin-top: 20px;
            font-weight: bold;
        }
        .net-pay {
            font-size: 18px;
            color: green;
        }
    </style>
</head>
<body onload="window.print()"> <!-- Automatically triggers print -->

<div class="payslip-container">
    <!-- Payslip Header -->
    <div class="header">
        <h2>Payslip for {{ $salMonth }}</h2>
    </div>

    <!-- Employee Details Section -->
    <p><strong>Employee Name:</strong> {{ $employeeDetails->name }}</p>
    <p><strong>Employee ID:</strong> {{ $employeeDetails->emp_id }}</p>
    <p><strong>Department:</strong> {{ $employeeDetails->department }}</p>
    <p><strong>Designation:</strong> {{ $employeeDetails->designation }}</p>

    <!-- Salary Details Section -->
    <table>
        <thead>
            <tr>
                <th>Earnings</th>
                <th>Deductions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>₹{{ number_format($salaryRevision['earnings'], 2) }}</td>
                <td>₹{{ number_format($salaryRevision['deductions'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Net Pay Summary -->
    <div class="summary">
        <p class="net-pay">Net Pay: ₹{{ number_format($salaryRevision['net_pay'], 2) }}</p>
        <p>In Words: {{ $rupeesInText }}</p>
    </div>

    <!-- Bank Details Section -->
    <p><strong>Bank Name:</strong> {{ $empBankDetails->bank_name }}</p>
    <p><strong>Account Number:</strong> {{ $empBankDetails->account_number }}</p>
    <p><strong>IFSC Code:</strong> {{ $empBankDetails->ifsc_code }}</p>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
    </div>
</div>

</body>
</html>
