<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
            color: #2c3e50;
        }

        .certificate-container {
            border: 15px solid #417DBE;
            padding: 15px;
        }

        .inner-border {
            border: 2px solid #b3cce6;
            padding: 20px 30px;
        }

        .cert-number {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .header {
            text-align: center;
        }

        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 5px;
        }

        .department-name {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2c3e50;
        }

        .cert-title {
            margin: 5px 0 15px;
            font-size: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #417DBE;
        }

        .content {
            text-align: center;
            font-size: 15px;
            line-height: 1.8;
        }

        .project-name {
            font-size: 20px;
            font-weight: bold;
            margin: 8px 0;
        }

        .contractor-name {
            font-size: 18px;
            font-weight: bold;
            color: #2980b9;
            margin: 8px 0;
        }

        .details-table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px dashed #ccc;
            text-align: left;
        }

        .details-table td:first-child {
            width: 45%;
            font-weight: bold;
        }

        .footer-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .footer-left {
            width: 50%;
            text-align: left;
            vertical-align: bottom;
            font-size: 13px;
        }

        .footer-right {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-line {
            width: 180px;
            border-top: 2px solid #2c3e50;
            margin: 0 auto 5px;
        }

        .signature-title {
            font-weight: bold;
            font-size: 13px;
        }

        .signature-sub {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>

<body>

<div class="certificate-container">

    <div class="inner-border">

        <div class="cert-number">
            Certificate No:
            <strong>{{ $certificateData['certificate_number'] }}</strong>
        </div>

        <div class="header">

            <img
                src="{{ public_path('images/main_logo.png') }}"
                class="logo"
                alt="Logo"
            >

            <h2 class="department-name">
                NAGALAND PUBLIC WORKS DEPARTMENT
            </h2>

            <div class="cert-title">
                CERTIFICATE OF COMPLETION
            </div>

        </div>

        <div class="content">

            This is to certify that the project

            <div class="project-name">
                {{ $certificateData['name'] }}
            </div>

            (Project ID: {{ $certificateData['id'] }})

            <br>

            has been successfully completed by

            <div class="contractor-name">
                {{ $certificateData['contractor'] }}
            </div>

            in accordance with the department's specifications and standards.

            <table class="details-table">

                <tr>
                    <td>Project Start Date</td>
                    <td>
                        <strong>
                            {{ date('F d, Y', strtotime($certificateData['start_date'])) }}
                        </strong>
                    </td>
                </tr>

                <tr>
                    <td>Project End Date</td>
                    <td>
                        <strong>
                            {{ date('F d, Y', strtotime($certificateData['end_date'])) }}
                        </strong>
                    </td>
                </tr>

               <tr>
                    <td>Total Project Cost</td>
                    <td>
                        <strong>Rs {{$certificateData['cost']}}</strong>
                    </td>
                </tr>

            </table>

        </div>

        <table class="footer-table">

            <tr>

                <td class="footer-left">

                    Date of Issue<br>

                    <strong>
                        {{ date('F d, Y', strtotime($certificateData['generation_date'])) }}
                    </strong>

                </td>

                <td class="footer-right">

                    <div class="signature-line"></div>

                    <div class="signature-title">
                        Chief Engineer
                    </div>

                    <div class="signature-sub">
                        Nagaland PWD
                    </div>

                </td>

            </tr>

        </table>

    </div>

</div>

</body>
</html>