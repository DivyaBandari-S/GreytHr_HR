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
        td {
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
                <h4 style="font-weight: 600; margin-top: 10px;">Final Settlement</h4>
            </div>
        </div>
        <div>
            <table style="width: 100%;">
                <tr style="width:100%; padding:0px;border-top: 1px solid #000;">
                    <td class="p-0" style="width:100%;">
                        <table style="width:100%; border: none;">
                            <tr>
                                <td class="lableValues Labels ">Employee Number</td>
                                <td class="lableValues Labels">: </td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Name</td>
                                <td class="lableValues Labels">: </td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Department</td>
                                <td class="lableValues Labels">: </td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Department</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Designation</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels"> Location</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Date of Joining</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels"> Submission date of resignation</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Last date of working</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Last salary paid</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Notice period as per application letter</td>
                                <td class="lableValues Labels">: </td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Notice period adjustable</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels"> PL days payable </td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Number of days salary payable</td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                            <tr>
                                <td class="lableValues Labels">Number of days in the month </td>
                                <td class="lableValues Labels">:</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width:100%;">

                <tbody style="width:100%;">

                    <tr style="width: 100%;">
                        <td class=" p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td colspan="2" class="table_headers" style="width:50%; text-align: center;">Income</td>

                                </tr>
                            </table>
                        </td>
                        <td class=" p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td colspan="2" class="table_headers" style="width:50%; text-align: center;">Deductions</td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class=" p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">BASIC</td>
                                    <td class="lableValues Labels " style="width:50%; text-align: right;padding-right:3px;"></td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">HRA</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">CONVEYANCE</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;"> MEDICAL ALLOWANCE</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>
                                </tr>
                                <tr style="padding-left:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">SPECIAL ALLOWANCE</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>
                                </tr>
                            </table>
                        </td>
                        <td class=" p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">PF</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>

                                </tr>
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">ESI</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>

                                </tr>
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">PROF TAX</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class=" p-0" style="width:50%; border-top: 1px solid #000; border-right: 1px solid #000;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:50%; text-align: left;">Total Income:</td>
                                    <td class="lableValues Labels" style="width:50%; text-align: right;padding-right:3px"></td>
                                </tr>
                            </table>
                        </td>
                        <td class=" p-0" style="width:50%;border-top: 1px solid #000;vertical-align: top;">
                            <table style="width:100%; table-layout: fixed; border-collapse: collapse;">
                                <tr style="padding-right:3px;">
                                    <td class="lableValues Labels" style="width:70%; text-align: left;">Total Deductions:</td>
                                    <td class="lableValues Labels" style="width:30%; text-align: right;padding-right:3px"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="border: 1px solid #000; width: 100%;border-top:none; margin-top:-11px">
        <p style="font-size:11px;width:100%;padding-left:3px;margin-bottom:0px; ">Net Pay: INR (Rupees only) <span style="font-weight: 600;"></span></p>

        <table style="width: 100%; margin-top:50px;">
            <tr style=" width: 100%;justify-content: space-between;">
                <td style="font-size:11px">CHECKED BY</td>
                <td style="font-size:11px">AUTHORIZED BY</td>
                <td style="font-size:11px">RECEIVED AND SIGNED</td>
            </tr>
            <tr style="font-size:11px ; margin-top:20px;">
                <td>
                    Remarks :
                </td>
            </tr>
        </table>

    </div>

</body>


</html>