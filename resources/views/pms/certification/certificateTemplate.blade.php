<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Work Completion Certificate</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
            color: #000;
            background: #fff;
            line-height: 1.2;
        }

        .certificate-page {
            position: relative;
            width: auto;
            height: 180mm;
            margin: 3mm 4mm;
            padding: 7mm 18mm 8mm;
            border: 3px solid #417dbe;
            overflow: hidden;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .watermark {
            position: absolute;
            top: 31mm;
            left: 82mm;
            width: 96mm;
            opacity: 0.16;
            z-index: 0;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
        }

        .main-title {
            margin: 0;
            text-align: center;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 30px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sub-title {
            margin: 6px 0 0;
            text-align: center;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 20px;
            font-weight: 700;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .certificate-number {
            margin: 22px 110px 18px 0;
            text-align: right;
            font-size: 17px;
            font-style: italic;
            line-height: 22px;
        }

        .certificate-number .label-no {
            display: inline-block;
            line-height: 22px;
            vertical-align: bottom;
        }

        .certificate-number .line {
            display: inline-block;
            min-width: 115px;
            padding: 0 8px;
            border-bottom: 1px solid #000;
            font-style: normal;
            line-height: 22px;
            text-align: center;
            vertical-align: bottom;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 18px;
        }

        .meta-table td {
            padding: 6px 0;
            vertical-align: bottom;
        }

        .label {
            width: 220px;
            font-weight: normal;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .separator {
            width: 24px;
            text-align: center;
        }

        .fill-line {
            display: inline-block;
            width: 100%;
            min-height: 20px;
            padding: 0 8px 2px;
            border-bottom: 1px solid #000;
            vertical-align: bottom;
        }

        .period-row {
            width: 100%;
            border-collapse: collapse;
        }

        .period-row td {
            padding: 0;
        }

        .period-line {
            width: 180px;
        }

        .period-to {
            width: 42px;
            text-align: center;
            font-weight: normal;
        }

        .body-copy {
            margin-top: 2px;
            font-size: 19px;
            line-height: 1.65;
        }

        .body-line {
            display: inline-block;
            min-width: 570px;
            padding: 0 8px 2px;
            border-bottom: 1px solid #000;
            line-height: 1.2;
            vertical-align: baseline;
            text-align: center;
        }

        .completion-date {
            display: inline-block;
            min-width: 190px;
            padding: 0 8px 2px;
            border-bottom: 1px solid #000;
            line-height: 1.2;
            text-align: center;
        }

        .footer-table {
            width: 100%;
            margin-top: 12px;
            border-collapse: collapse;
        }

        .future-text {
            width: 66%;
            font-size: 19px;
            vertical-align: top;
        }

        .issued-by {
            width: 34%;
            padding-left: 28px;
            font-size: 17px;
            line-height: 1.35;
            vertical-align: top;
        }
    </style>
</head>

<body>
@php
    $formatDate = function ($date) {
        if (empty($date)) {
            return '';
        }

        $timestamp = strtotime($date);

        return $timestamp ? date('d-m-Y', $timestamp) : '';
    };

    $projectName = $certificateData['name'] ?? '';
    $projectId = $certificateData['id'] ?? '';
    $contractor = $certificateData['contractor'] ?? '';
    $certificateNo = $certificateData['certificate_number'] ?? '';
    $workOrderNo = $certificateData['work_order_no'] ?? $certificateData['work_order_number'] ?? '';
    $startDate = $formatDate($certificateData['start_date'] ?? null);
    $endDate = $formatDate($certificateData['end_date'] ?? null);
    $completionDate = $formatDate($certificateData['end_date'] ?? null);
@endphp

<div class="certificate-page">
    <img
        src="{{ public_path('images/main_logo.png') }}"
        class="watermark"
        alt="Logo"
    >

    <div class="certificate-content">
        <h1 class="main-title">WORK COMPLETION CERTIFICATE</h1>

        <div class="sub-title">TO WHOM IT MAY CONCERN</div>

        <div class="certificate-number">
            <span class="label-no">NO :</span>
            <span class="line">{{ $certificateNo }}</span>
        </div>

        <table class="meta-table">
            <tr>
                <td class="label">NAME OF THE PROJECT</td>
                <td class="separator">:</td>
                <td>
                    <span class="fill-line">
                        {{ $projectName }}{{ $projectId ? ' (' . $projectId . ')' : '' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td class="label">WORK ORDER NO</td>
                <td class="separator">:</td>
                <td>
                    <span class="fill-line">{{ $workOrderNo }}</span>
                </td>
            </tr>

            <tr>
                <td class="label">WORKING PERIOD</td>
                <td class="separator">:</td>
                <td>
                    <table class="period-row">
                        <tr>
                            <td>
                                <span class="fill-line period-line">{{ $startDate }}</span>
                            </td>
                            <td class="period-to">TO</td>
                            <td>
                                <span class="fill-line period-line">{{ $endDate }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="body-copy">
            This is to certify that M/s
            <span class="body-line">{{ $contractor }}</span>
            has<br>
            successfully completed the work as per specification and requirement of the project.
            <br><br>
            The work is completed on
            <span class="completion-date">{{ $completionDate }}</span>
        </div>

        <table class="footer-table">
            <tr>
                <td class="future-text">
                    Wishing the firm success in their future endeavour.
                </td>
                <td class="issued-by">
                    Issued by<br>
                    The Chief Engineer PWD<br>
                    (R&amp;B), Government of Nagaland<br>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
