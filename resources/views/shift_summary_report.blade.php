<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Summary Report</title>
    <style>
        /* Set the font to DejaVu Sans for better Unicode support */
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Optional: Add custom styling for the PDF */
    </style>
</head>
<body>

    <h1>Shift Summary Report</h1>
    <p><strong>From Date:</strong> {{ $fromDate }}</p>
    <p><strong>To Date:</strong> {{ $toDate }}</p>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Total Days</th>
                <th>Work Days</th>
                <th>Off Days</th>
                <th>Shift Schedule</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $transaction)
                <tr>
                    <td>{{ $transaction['emp_id'] }}</td>
                    <td>{{ $transaction['name'] }}</td>
                    <td>{{ $transaction['total_days'] }}</td>
                    <td>{{ $transaction['work_days'] }}</td>
                    <td>{{ $transaction['off_day'] }}</td>
                    <td>{{ $transaction['shift_schedule'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
