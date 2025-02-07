<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include Bootstrap CSS -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha512-RgHh2hAiqIyb7OiXJ2mdyvgcmQ4jaixn1KJZ9T2EyNeIeeULenpVi+v3XnRxkoi0JvUHyja0kXgQVDiRhTskwQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
</head>

<body style="font-family: 'Montserrat', sans-serif; text-align:center">
    <style>
        html,
        body {
            margin: 10px 10px;
            padding: 0;
            width: 100%;
            height: 100%;
            /* background-color: grey; */

        }

        .lableValues {
            width: 50%;
            font-size: 11px;
            font-weight: 500;
        }

        .Labels {
            padding-left: 3px;
        }

        .tabledata tr th {
            font-size: 10px;
            border: 1px solid black;
            text-align: center;

        }

        .tabledata tbody tr td {
            font-size: 11px;
            border: 1px solid black;


        }

        .headings {
            text-align: start;
        }

        .table-data {
            text-align: right;
        }

        table {
            margin-right: 20px;
        }

        .secondtable th,th {
            padding: 3px 1px;
            height: 16px;
            border: 1px solid black;
            font-size: 9;
            font-weight: 600;


        }
        .secondtable tbody tr td{
            padding: 3px 1px;
            height: 16px;
            border: 1px solid black;
            font-size: 10px;
            font-weight: 500;
            text-align: right;

        }
    </style>
    <div style=" width: 98%; text-align:center">
        <div style="position: relative; width: 100%; margin-bottom: 15px;">
            <!-- Company Logo -->
            <div style="position: absolute; left: 1%; top: 3%; transform: translateY(-50%);">
                <img src="https://media.licdn.com/dms/image/C4D0BAQHZsEJO8wdHKg/company-logo_200_200/0/1677514035093/xsilica_software_solutions_logo?e=2147483647&v=beta&t=rFgO4i60YIbR5hKJQUL87_VV9lk3hLqilBebF2_JqJg" alt="Company Logo" style="width: 120px;">
            </div>

            <!-- Company Details -->
            <div style="text-align: center; margin: 0 auto; width: 100%; position: relative;">
                <h2 style="font-weight: 700; font-size: 16px; margin: 0;">XSILICA SOFTWARE SOLUTIONS P LTD</h2>
                <p style="font-size: 10px; margin: 0;">3rd Floor, Unit No.4, Kapil Kavuri Hub IT Block, Nanakramguda Main Road, Hyderabad, Rangareddy,</p>
                <p style="font-size: 10px; margin: 0;">500032, Telangana, India</p>
                <h5 style="font-weight: 600; margin-top: 10px;font-size: 12px;">YTD Summary for the year {{$startDate}} - {{$endDate}} </h5>
            </div>
        </div>
        <table style="width:100%;border: 1px solid #000; border-bottom:none">
            <tbody style="width:100%;">
                <tr style="width:100%;">
                    <td class="w-50 p-0" style="width:50%; border-right: 1px solid #000;">

                        <table style="width:100%; border: none;">
                            <tr>
                                <td class="lableValues Labels ">Employee No:</td>
                                <td class="lableValues Labels">{{$employees->emp_id}} </td>
                            </tr>

                            <tr>
                                <td class="lableValues Labels">Joining Date:</td>
                                <td class="lableValues Labels">{{ \Carbon\Carbon::parse($employees->hire_date)->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">UAN:</td>
                                <td class="lableValues Labels">-</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-50 p-0" style="width:50%;vertical-align: top;">
                        <table style="width:100%; border: none;">
                            <tr>
                                <td class="lableValues Labels">Name:</td>
                                <td class="lableValues Labels">{{ ucwords(strtolower($employees->first_name)) . ' ' . ucwords(strtolower($employees->last_name)) }} </td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">PF No:</td>
                                <td class="lableValues Labels">-</td>
                            </tr>
                        </table>
                    </td>
                </tr>




            </tbody>
        </table>
        <table class="secondtable" style=" width: 100%;border-collapse: collapse;text-align: center;">
            <thead>
                <tr>
                    <th style="width: 20%;" rowspan="2">Month</th>
                    <th style="width: 20%;" rowspan="1"></th>
                    <th style="width: 20%;" colspan="1">Employee Contribution</th>
                    <th style="width: 40%;" colspan="2">Employer Contribution</th>
                </tr>
                <tr>
                    <th>Earnings</th>
                    <th>PF</th>
                    <th>PF</th>
                    <th>Pension Fund</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pfData as $month=> $data)
                <tr>
                    <td style="text-align:left">{{\Carbon\Carbon::parse($month)->format('M Y') }}</td>
                    <td>{{$data['basic']}}</td>
                    <td>{{$data['pf']}}</td>
                    <td>{{$data['employeer_pf']}}</td>
                    <td>{{$data['employeer_pension']}}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="font-size:9 ;font-weight:600">Total:</td>
                    <td style="font-size:9 ;font-weight:600">{{$pfTotals['basic']}}</td>
                    <td style="font-size:9 ;font-weight:600">{{$pfTotals['pf']}}</td>
                    <td style="font-size:9 ;font-weight:600">{{$pfTotals['employeer_pf']}}</td>
                    <td style="font-size:9 ;font-weight:600">{{$pfTotals['employeer_pension']}}</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>


</html>
