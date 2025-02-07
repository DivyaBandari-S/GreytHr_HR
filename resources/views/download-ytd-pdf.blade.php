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
        .tabledata tbody tr td{
            font-size: 11px;
            border: 1px solid black;
            border-top: none;
            font-weight: 500;

        }
        .headings{
            text-align:start;
        }
        .table-data{
            text-align: right;
        }
        table{
            margin-right: 20px;
        }
        th, td {
           padding: 3px 1px;
           height: 16px;

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
                                <td class="lableValues Labels">Bank:</td>
                                <td class="lableValues Labels">{{$empBankDetails['bank_name']}}</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Joining Date:</td>
                                <td class="lableValues Labels">{{ \Carbon\Carbon::parse($employees->hire_date)->format('d M, Y') }}</td>
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
                                <td class="lableValues Labels">Bank Account No:</td>
                                <td class="lableValues Labels"> {{$empBankDetails['account_number']}}</td>
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
        <table class="tabledata" style="width:100%;border-collapse: collapse; ">
            <thead>
                <tr style="width:100%;">
                    <th>Item</th>
                    @foreach(array_keys($salaryData) as $month)
                    <th>{{\Carbon\Carbon::parse($month)->format('M Y') }}</th>
                   @endforeach
                    <th> Grand Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="headings"></td>
                    @foreach($salaryData as $data)
                    <td class="table-data"></td>
                    @endforeach
                    <td class="table-data"></td>
                </tr>
                <tr>
                    <td class="headings">BASIC</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['basic'] != 0 ? $data['basic'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['basic'] }}</td>
                </tr>
                <tr>
                    <td class="headings">HRA</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['hra'] != 0 ? $data['hra'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['hra'] }}</td>
                </tr>
                <tr>
                    <td class="headings">CONVEYANCE</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['conveyance'] != 0 ? $data['conveyance'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['conveyance'] }}</td>
                </tr>
                <tr>
                    <td class="headings"> MEDICAL ALLOWANCE</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['medical_allowance'] != 0 ? $data['medical_allowance'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['medical_allowance'] }}</td>

                </tr>
                <tr>
                    <td class="headings"> SPECIAL ALLOWANCE</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['special_allowance'] != 0 ? $data['special_allowance'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['special_allowance'] }}</td>

                </tr>
                <tr>
                    <td class="headings"> OTHER ALLOWANCES</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['special_allowance'] != 0 ? $data['special_allowance'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['special_allowance'] }}</td>

                </tr>
                <tr>
                    <td class="headings">TOTAL EARNING</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['gross'] != 0 ? $data['gross'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['gross'] }}</td>

                </tr>
                <tr>
                    <td class="headings">PF</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['pf'] != 0 ? $data['pf'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['pf'] }}</td>

                </tr>
                <tr>
                    <td class="headings">ESI</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['esi'] != 0 ? $data['esi'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['esi'] }}</td>

                </tr>
                <tr>
                    <td class="headings">PROF TAX</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['professional_tax'] != 0 ? $data['professional_tax'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['professional_tax'] }}</td>

                </tr>
                <tr>
                    <td class="headings">TOTAL DEDUCTION</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['total_deductions'] != 0 ? $data['total_deductions'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['total_deductions'] }}</td>

                </tr>
                <tr>
                    <td class="headings"> NET PAY</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['net_pay'] != 0 ? $data['net_pay'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['net_pay'] }}</td>


                </tr>
                <tr>
                    <td class="headings">EMP EFFECTIVE WORKDAYS</td>
                    @foreach($salaryData as $data)
                    <td class="table-data">{{ $data['working_days'] != 0 ? $data['working_days'] : '' }}</td>
                    @endforeach
                    <td class="table-data">{{ $salaryTotals['working_days'] }}</td>

                </tr>
                <tr>
                    <td class="headings">DAYS IN MONTH</td>
                    @foreach($salaryData as $data)
                    <td class="table-data" >30</td>
                    @endforeach
                    <td class="table-data">360</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>


</html>
