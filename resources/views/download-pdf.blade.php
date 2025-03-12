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

<body style="font-family: 'Montserrat', sans-serif;">
    <style>
        .lableValues {
            width: 50%;
            font-size: 12px;
            font-weight: 400;
        }

        .Labels {
            padding-left: 3px;
        }

        .table_headers {
            font-size: 11px;
            font-weight: 600;
        }
        tr,
        td{
            padding-left: 0px;
        }
    </style>
    <div style="border: 1px solid #000; width: 100%;" class="margin-bottom:0px">
        <div style="position: relative; width: 100%; margin-bottom: 20px;">
            <!-- Company Logo -->
            <div style="position: absolute; left: 1%; top: 6%; transform: translateY(-50%);">
            <img src="{{ public_path('images/xsilica_software_solutions_logo.jpg') }}" style="width: 100px;">

            </div>

            <!-- Company Details -->
            <div style="text-align: center; margin: 0 auto; width: 100%; position: relative;">
                <h2 style="font-weight: 700; font-size: 18px; margin: 0;">XSILICA SOFTWARE SOLUTIONS P LTD</h2>
                <p style="font-size: 9px; margin: 0;">3rd Floor, Unit No.4, Kapil Kavuri Hub IT Block, Nanakramguda Main Road, Hyderabad, Rangareddy,</p>
                <p style="font-size: 9px; margin: 0;">500032, Telangana, India</p>
                <h4 style="font-weight: 600; margin-top: 10px;">Payslip for the month of {{\Carbon\Carbon::parse($salMonth)->format('F Y')}}</h4>
            </div>
        </div>
        <div>
            <table style="width:100%;">
                <tbody style="width:100%;">
                    <tr style="width:100%;padding:0px;border-top: 1px solid #000">
                        <td class="w-50 p-0 " style="width:50%;border-top: 1px solid #000; border-right: 1px solid #000;">

                            <table style="width:100%; border: none;">
                                <tr>
                                    <td class="lableValues Labels ">Name:</td>
                                    <td class="lableValues Labels"> {{ ucwords(strtolower($employees->first_name)) . ' ' . ucwords(strtolower($employees->last_name)) }}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Joining Date:</td>
                                    <td class="lableValues Labels"> {{ \Carbon\Carbon::parse($employees->hire_date)->format('d M, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Designation:</td>
                                    <td class="lableValues Labels"> {{$employees->job_role}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Department:</td>
                                    <td class="lableValues Labels">{{$employees->department}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Location:</td>
                                    <td class="lableValues Labels">{{$employees->job_location}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels"> Effective Work Days:</td>
                                    <td class="lableValues Labels">{{$salaryRevision['full_days']}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">LOP:</td>
                                    <td class="lableValues Labels">{{$salaryRevision['lop_days']}}</td>
                                </tr>
                            </table>

                        </td>
                        <td class="w-50 p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; border: none;">
                                <tr>
                                    <td class="lableValues Labels"> Employee No:</td>
                                    <td class="lableValues Labels"> {{$employees->emp_id}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Bank Name:</td>
                                    <td class="lableValues Labels"> {{$empBankDetails['bank_name']}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">Bank Account No:</td>
                                    <td class="lableValues Labels"> {{$empBankDetails['account_number']}}</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">PAN Numbe:</td>
                                    <td class="lableValues Labels">- </td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels">PF No:</td>
                                    <td class="lableValues Labels"> -</td>
                                </tr>
                                <tr>
                                    <td class="lableValues Labels"> PF UAN:</td>
                                    <td class="lableValues Labels">-</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-50 p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="table_headers" style="width:40%; text-align: center;">Earnings</td>
                                    <td class="table_headers" style="width:30%; text-align: right;">Full</td>
                                    <td class="table_headers" style="width:30%; text-align: right;padding-right:3px">Actual</td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-50 p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="table_headers" style="width:50%; text-align: center;">Deductions</td>
                                    <td class="table_headers" style="width:50%; text-align: right;padding-right:3px">Actual</td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-50 p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;">BASIC</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['full_basic'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_basic'],2)}}</td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;">HRA</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['full_hra'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_hra'],2)}}</td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;">CONVEYANCE</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['full_conveyance'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_conveyance'],2)}}</td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;"> MEDICAL ALLOWANCE</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['full_medical'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_medical'],2)}}</td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;">SPECIAL ALLOWANCE</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['full_special'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_special'],2)}}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-50 p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">PF</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_pf'],2)}}</td>

                                </tr>
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">ESI</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_esi'],2)}}</td>

                                </tr>
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">PROF TAX</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_prof_tax'],2)}}</td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-50 p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:40%; text-align: left;">Total Earnings:INR.</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;">{{number_format($salaryRevision['actual_gross'],2)}}</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['full_gross'],2)}}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="w-50 p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:70%; text-align: left;">Total Deductions:INR.</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px">{{number_format($salaryRevision['actual_total_deductions'],2)}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="border: 1px solid #000; width: 100%;border-top:none; margin-top:-11px">
        <p style="font-size:11px;width:100%;padding-left:3px;margin-bottom:0px; "> Net Pay for the month ( Total Earnings - Total Deductions): <span style="font-weight: 600;">{{ number_format($salaryRevision['actual_net_salary'],2)}}</span></p>
        <p style="font-size:11px;width:100%;padding-left:3px;margin-bottom:0px;">(Rupees {{$rupeesInText}} only) </p>
    </div>
    <p style="font-size: 11px;text-align: center;">
        This is a system generated payslip and does not require signature
    </p>

</body>


</html>
