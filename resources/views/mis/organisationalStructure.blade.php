<!DOCTYPE html>
{{-- @extends('layouts.app') --}}
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script>
        try {
            document.documentElement.setAttribute('data-theme', localStorage.getItem('oamis-theme') || 'light');
        } catch (error) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <link rel="icon" href="{!! asset('images/main_logo.png') !!}" />
    <title>OMIS</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="dist/css/adminlte.min.css"> -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/treeView/mainnew.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/treeView/menunew.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/treeView/treeviewnew.css') }}">
    <link href="{{ asset('plugins/treeView/slicknav.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/oamis-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/org-structure.css') }}">
    <link rel="stylesheet" href="{{ asset('css/oamis-controls.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/treeView/jquery-1.11.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/treeView/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/treeView/jquery.slicknav.js') }}"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        #mainHeader {
            min-height: 20% !important;
            background-color: red;
        }

        .mainBody {
            background-color: #FEFBFA;
        }

        label {
            font-size: 10px;
        }

        /* custom pagination styles */
        ul.pagination {
            font-size: .6rem;
            padding: 0;
            margin: 0;
        }

        div.equipment-btn:hover {
            cursor: pointer;
        }

        div.equipment-items {
            display: none;
        }

        .footer-container {
            padding: 1% 1% 1% 4%;
            background-color: #E88806;
            color: white;
            font-size: 15px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            position: relative;
        }

        legend {
            line-height: 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            color: black;
            border: 2px solid #5677E7;
        }

        .main-sidebar {
            background-image: linear-gradient(to bottom, #164863, #EEF5FF, #EEF5FF, #EEF5FF, #EEF5FF, #EEF5FF, #EEF5FF)
        }

        .nav-link:hover {
            background-color: #cecfcf;
        }

        .roadPendingCount {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2% 4% 2% 4%;
        }

        .visit {
            padding: 8px 15px 8px 15px;
            background-color: white;
            color: black;
            border-radius: 20px;
        }

        .star {
            color: red;
        }

        @media only screen and (max-width: 600px) {
            img.mainLogo {
                width: 50%;
            }
        }

        .collapseButton {
            vertical-align: text-top;
        }

        th,
        td {
            padding: 1em;
        }

        #hidden {
            display: none;
        }

        .Table {
            margin-left: 12em;
            margin-top: 1em;
            margin-right: 1em;
        }

        table {
            border-collapse: collapse;
        }

        table tr:nth-child(even) {
            /* background-color: #5873C1;
            color: white; */
        }

        table tr:nth-child(odd) {
            /* background-color: #3756B1;
            color: white; */
        }

        p {
            font-size: 14px;
        }
    </style>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.header')
        @include('layouts.sidebar')
        @include('sweet::alert')
        @yield('content')
    </div>

    <div class="content-wrapper org-page">
        <div class="org-hero">
            <div>
                <div class="org-breadcrumb">
                    <a href="{{ route('dashboard') }}">MIS</a>
                    <span>/</span>
                    <strong>Organisational Structure</strong>
                </div>
                <span class="org-eyebrow">Nagaland Public Works Department</span>
                <h1>Organisational Structure</h1>
                <p>
                    Explore department-wise hierarchy from chief engineer offices to zones,
                    circles, divisions, sub-divisions and officer assignments.
                </p>
            </div>
            <div class="org-hero-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-chart-line mr-1"></i>Dashboard
                </a>
            </div>
        </div>

        <div class="org-summary-grid">
            <article>
                <span>Roads & Bridges</span>
                <strong>{{ optional($count_div->first())->total ?? 0 }}</strong>
                <small>Divisions</small>
            </article>
            <article>
                <span>Housing</span>
                <strong>{{ optional($count_div_hsng->first())->total ?? 0 }}</strong>
                <small>Divisions</small>
            </article>
            <article>
                <span>Mechanical</span>
                <strong>{{ optional($count_div_mech->first())->total ?? 0 }}</strong>
                <small>Divisions</small>
            </article>
            <article>
                <span>National Highway</span>
                <strong>{{ optional($count_div_nh->first())->total ?? 0 }}</strong>
                <small>Divisions</small>
            </article>
        </div>

        <section class="content org-structure-shell" id="roadDistressDtlsSection" name="roadDistressDtlsSection">
            <div class="container-fluid">
                <div class="org-tabs-card">
                    <div class="org-section-heading">
                        <div>
                            <span>Department Browser</span>
                            <h2>Administrative hierarchy</h2>
                        </div>
                        <p>Expand rows to drill into offices and officer details.</p>
                    </div>
                    <ul class="nav nav-pills nav-tabs nav-fill" id="organisations_tabs">
                        <li class="nav-item" onclick="setTabName(14);">
                            <a href="#rnb" id="tab_rnb" data-toggle="tab" class="nav-link active" aria-current="page"><i
                                    class="fas fa-road"></i>Roads & Bridges</a>
                        </li>
                        <li class="nav-item" onclick="setTabName(6);">
                            <a href="#housing" id="tab_housing" data-toggle="tab" class="nav-link"><i
                                    class="fas fa-building"></i>Housing</a>
                        </li>
                        <li class="nav-item" onclick="setTabName(15);">
                            <a href="#mech" id="tab_mech" data-toggle="tab" class="nav-link"><i
                                    class="fas fa-gears"></i>Mechanicals</a>
                        </li>
                        <li class="nav-item" onclick="setTabName(3);">
                            <a href="#nh" id="tab_nh" data-toggle="tab" class="nav-link"><i
                                    class="fas fa-route"></i>National Highway</a>
                        </li>
                        <li class="nav-item" onclick="setTabName(19);">
                            <a href="#pep" id="tab_pep" data-toggle="tab" class="nav-link"><i
                                    class="fas fa-shield-halved"></i>Police Engineering
                                Project</a>
                        </li>
                        <li class="nav-item" onclick="setTabName(20);">
                            <a href="#pnd" id="tab_pep" data-toggle="tab" class="nav-link"><i
                                    class="fas fa-drafting-compass"></i>Planning And Design</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content clearfix org-tree-card">
                <div class="tab-pane active" id="rnb">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_rnb">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="housing">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_housing">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="mech">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_mech">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="nh">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_nh">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pep">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_pep">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pnd">
                    <div class="css-treeview">
                        <table class="table_Border_Tabular1" id="table_tree_pnd">
                            <thead>
                                <tr>
                                    <th class="text-right">Name</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>From</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>


    @include('layouts/footer')
    @stack('scripts')

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>



    <!-- collapsible Table Start-->
    <script></script>
    <script type="text/javascript">
        var selected_dept_cd = 14;


        // $(document).ready(function() {
        var arr_additional_ofis_data = [];


        // });

        function setTabName(dept_cd) {

            selected_dept_cd = dept_cd;
            loadENCData();
        }

        // var obj = $('#item-1');
        // obj.prop('checked', true);

        let rowIndex = 1;
        var encOfisRows;
        var hqOfisRows;
        var hqUserRows;
        var hqUserRowsHsng;
        var addlHQOfsRows;
        var zoUserRows;
        var coUserRows;
        var coOfsRows;
        var divUserRows;
        var divOfsRows;
        var subDivUserRows;
        var subDivOfsRows;
        loadENCData();


        function loadENCData() {

            $('.' + 'enc_ofis_class_14').remove();
            $('.' + 'enc_users_class_14').remove();
            $('.' + 'enc_ofis_class_6').remove();
            $('.' + 'enc_users_class_6').remove();
            $('.' + 'enc_ofis_class_15').remove();
            $('.' + 'enc_users_class_15').remove();
            $('.' + 'enc_ofis_class_3').remove();
            $('.' + 'enc_users_class_3').remove();
            $('.' + 'enc_ofis_class_19').remove();
            $('.' + 'enc_users_class_20').remove();
            $('#table_tree_rnb tbody tr').empty();
            $('#table_tree_housing tbody tr').empty();
            $('#table_tree_mech tbody tr').empty();
            $('#table_tree_nh tbody tr').empty();
            $('#table_tree_pep tbody tr').empty();
            $('#table_tree_pnd tbody tr').empty();

            const enc_ofs_dtls = @json($enc_ofs_dtls);
            const enc_user_data = @json($enc_user_dtls);


            const count_zn = @json($count_zn); //This is for R&B
            const count_zn_hsng = @json($count_zn_hsng);
            const count_zn_mech = @json($count_zn_mech);
            const count_zn_nh = @json($count_zn_nh);
            const count_zn_pep = @json($count_zn_pep);
            const count_zn_pnd = @json($count_zn_pnd);


            const count_crl = @json($count_crl);
            const count_crl_hsng = @json($count_crl_hsng);
            const count_crl_mech = @json($count_crl_mech);
            const count_crl_nh = @json($count_crl_nh);
            const count_crl_pep = @json($count_crl_pep);
            const count_crl_pnd = @json($count_crl_pnd);


            const count_div = @json($count_div);
            const count_div_hsng = @json($count_div_hsng);
            const count_div_mech = @json($count_div_mech);
            const count_div_nh = @json($count_div_nh);
            const count_div_pep = @json($count_div_pep);
            const count_div_pnd = @json($count_div_pnd);


            const count_sdiv = @json($count_sdiv);
            const count_sdiv_hsng = @json($count_sdiv_hsng);
            const count_sdiv_mech = @json($count_sdiv_mech);
            const count_sdiv_nh = @json($count_sdiv_nh);
            const count_sdiv_pep = @json($count_sdiv_pep);
            const count_sdiv_pnd = @json($count_sdiv_pnd);

            encOfisRows = '';
            if (selected_dept_cd == 14) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_14">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_14_' + rowIndex +
                    '" name="enc_ofis_14_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd + ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_14_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn[0].total + ", " + " Circles: " +
                    count_crl[0].total + "," + " Divisions: " + count_div[0].total + ", Sub Divisions: " +
                    count_sdiv[0].total + "]" + '</label></td>';
                encOfisRows += '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {

                    hqUserRows += '<tr class="enc_users_class_14">' +
                        '<td align= "right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';

                    rowIndex++;
                }
            }

            if (selected_dept_cd == 6) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_6">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_6_' + rowIndex +
                    '" name="enc_ofis_6_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd + ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_6_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn_hsng[0].total + ", " +
                    " Circles: " + count_crl_hsng[0].total + "," + " Divisions: " +
                    count_div_hsng[0].total + ", Sub Divisions: " + count_sdiv_hsng[0].total + "]" + '</label>' +
                    '</td>';
                encOfisRows += '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {

                    hqUserRows += '<tr class="enc_users_class_6">' +
                        '<td align= "right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';
                    rowIndex++;
                }
            }

            if (selected_dept_cd == 15) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_15">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_15_' + rowIndex +
                    '" name="enc_ofis_15_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd + ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_15_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn_mech[0].total + ", " +
                    " Circles: " + count_crl_mech[0].total + "," + " Divisions: " +
                    count_div_mech[0].total + ", Sub Divisions: " + count_sdiv_mech[0].total + "]" + '</label>' +
                    '</td>';
                encOfisRows += '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {

                    hqUserRows += '<tr class="enc_users_class_15">' +
                        '<td align= "right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';
                    rowIndex++;
                }
            }

            if (selected_dept_cd == 3) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_3">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_3_' + rowIndex +
                    '" name="enc_ofis_3_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd +
                    ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_3_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn_nh[0].total + ", " +
                    " Circles: " + count_crl_nh[0].total + "," + " Divisions: " +
                    count_div_nh[0].total + ", Sub Divisions: " + count_sdiv_nh[0].total + "]" + '</label>' +
                    '</td>';
                encOfisRows +=
                    '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {
                    hqUserRows += '<tr class="enc_users_class_3_' + selected_dept_cd + '">' +
                        '<td align="right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';
                    rowIndex++;
                }
            }

            if (selected_dept_cd == 19) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_19">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_19_' + rowIndex +
                    '" name="enc_ofis_19_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd +
                    ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_19_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn_pep[0].total + ", " +
                    " Circles: " + count_crl_pep[0].total + "," + " Divisions: " +
                    count_div_pep[0].total + ", Sub Divisions: " + count_sdiv_pep[0].total + "]" + '</label>' +
                    '</td>';
                encOfisRows += '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {
                    hqUserRows += '<tr class="enc_users_class_19_' + selected_dept_cd + '">' +
                        '<td align="right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';
                    rowIndex++;
                }
            }

            if (selected_dept_cd == 20) {
                encOfisRows += '<tr id="' + rowIndex + selected_dept_cd + '" class="enc_ofis_class_20">';
                encOfisRows +=
                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                    '<input class="hide-report-input" type="checkbox" id="enc_ofis_20_' + rowIndex +
                    '" name="enc_ofis_20_"' + rowIndex + ' class="leftside-sevicelinks_heading" value="' + rowIndex +
                    '" onclick="loadHQData(this, ' + selected_dept_cd +
                    ');"/>' +
                    '<label class="leftside-sevicelinks_heading" for="enc_ofis_20_' + rowIndex + '">' +
                    enc_ofs_dtls[0].office_name + "  [ Zones : " + count_zn_pnd[0].total + ", " +
                    " Circles: " + count_crl_pnd[0].total + "," + " Divisions: " +
                    count_div_pnd[0].total + ", Sub Divisions: " + count_sdiv_pnd[0].total + "]" + '</label>' +
                    '</td>';
                encOfisRows += '</tr>';

                rowIndex++;

                hqUserRows = '';
                for (var i = 0; i < enc_user_data.length; i++) {
                    hqUserRows += '<tr class="enc_users_class_20_' + selected_dept_cd + '">' +
                        '<td align="right" align="center">' + enc_user_data[i].name + '</td>' +
                        '<td align="center">' + enc_user_data[i].desg_name + '</td>' +
                        '<td align="center">' + enc_user_data[i].email + '</td>' +
                        '<td align="center">' + enc_user_data[i].phoneno + '</td>' +
                        '<td align="center">' + enc_user_data[i].since_current_position + '</td>' +
                        '</tr>';
                    rowIndex++;
                }
            }

            if (selected_dept_cd == 14) {
                $('#table_tree_rnb').append(encOfisRows);
                $('#table_tree_rnb').append(hqUserRows);
            }
            if (selected_dept_cd == 6) {
                $('#table_tree_housing').append(encOfisRows);
                $('#table_tree_housing').append(hqUserRows);
            }
            if (selected_dept_cd == 15) {
                $('#table_tree_mech').append(encOfisRows);
                $('#table_tree_mech').append(hqUserRows);
            }
            if (selected_dept_cd == 3) {
                $('#table_tree_nh').append(encOfisRows);
                $('#table_tree_nh').append(hqUserRows);
            }
            if (selected_dept_cd == 19) {
                $('#table_tree_pep').append(encOfisRows);
                $('#table_tree_pep').append(hqUserRows);
            }

            if (selected_dept_cd == 20) {
                $('#table_tree_pnd').append(encOfisRows);
                $('#table_tree_pnd').append(hqUserRows);
            }

            $.ajax({
                type: "GET",
                url: "/asset-management/getAdditionalOfficeChargeDetails",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                cache: false,
                success: function (response) {
                    if (response.status === true) {
                        arr_additional_ofis_data = response.additional_ofis_data;
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }

        function loadHQData(element, dept_cd) {
            // alert("Clicked on ENC");
            const count_zn_for_hq = @json($count_zn_for_hq);
            const count_crl_for_hq = @json($count_crl_for_hq);
            const count_div_for_hq = @json($count_div_for_hq);
            const count_sdiv_for_hq = @json($count_sdiv_for_hq);
            var tot_zn = 0;
            var tot_crl = 0;
            var tot_div = 0;
            var tot_sdiv = 0;
            for (var i = 0; i < count_zn_for_hq.length; i++) {

                if (selected_dept_cd == 14 && count_zn_for_hq[i].dept_cd == 14) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }

                if (selected_dept_cd == 6 && count_zn_for_hq[i].dept_cd == 6) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 15 && count_zn_for_hq[i].dept_cd == 15) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 3 && count_zn_for_hq[i].dept_cd == 3) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 19 && count_zn_for_hq[i].dept_cd == 19) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 20 && count_zn_for_hq[i].dept_cd == 20) {
                    tot_zn = count_zn_for_hq[i].total;
                    break;
                }
            }
            for (var i = 0; i < count_crl_for_hq.length; i++) {
                if (selected_dept_cd == 14 && count_crl_for_hq[i].dept_cd == 14) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 6 && count_crl_for_hq[i].dept_cd == 6) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 15 && count_crl_for_hq[i].dept_cd == 15) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 3 && count_crl_for_hq[i].dept_cd == 3) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 19 && count_crl_for_hq[i].dept_cd == 19) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 20 && count_crl_for_hq[i].dept_cd == 20) {
                    tot_crl = count_crl_for_hq[i].total;
                    break;
                }
            }
            for (var i = 0; i < count_div_for_hq.length; i++) {
                if (selected_dept_cd == 14 && count_div_for_hq[i].dept_cd == 14) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 6 && count_div_for_hq[i].dept_cd == 6) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 15 && count_div_for_hq[i].dept_cd == 15) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 3 && count_div_for_hq[i].dept_cd == 3) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 19 && count_div_for_hq[i].dept_cd == 19) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 20 && count_div_for_hq[i].dept_cd == 20) {
                    tot_div = count_div_for_hq[i].total;
                    break;
                }
            }
            for (var i = 0; i < count_sdiv_for_hq.length; i++) {
                if (selected_dept_cd == 14 && count_sdiv_for_hq[i].dept_cd == 14) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 6 && count_sdiv_for_hq[i].dept_cd == 6) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 15 && count_sdiv_for_hq[i].dept_cd == 15) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 3 && count_sdiv_for_hq[i].dept_cd == 3) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }

                if (selected_dept_cd == 19 && count_sdiv_for_hq[i].dept_cd == 19) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }
                if (selected_dept_cd == 20 && count_sdiv_for_hq[i].dept_cd == 20) {
                    tot_sdiv = count_sdiv_for_hq[i].total;
                    break;
                }
            }
            if ($(element).is(":checked")) {
                hqOfisRows = '';
                hqOfisRows_hsng = '';
                hqOfisRows_mech = '';
                hqOfisRows_nh = '';
                hqOfisRows_pep = '';
                hqOfisRows_pnd = '';
                hqUserRows = '';

                zoUserRows = '';
                coUserRows = '';
                divUserRows = '';
                subDivUserRows = '';
                addlHQOfsRows = '';
                // rowIndex = 1;
                if (selected_dept_cd == 14) {
                    hqOfisRows += '<tr id="' + rowIndex + '" class="hq_ofis_class_14">';
                    hqOfisRows +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_14_' + rowIndex + '"' +
                        ' name="hq_ofis_14_" + ' + rowIndex + ' class="leftside-sevicelinks_heading1" value="' + rowIndex +
                        '"' +
                        ' onclick="getCEUserDataAndAddlCEOfficeData(this, ' + selected_dept_cd + ');"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_14_' + rowIndex +
                        '">' +
                        "OFFICE OF THE CHIEF ENGINEER (R&B)" +
                        "[ Zones : " + tot_zn + ", " + "Circles: " + tot_crl + "," + "Divisions: " + tot_div +
                        ", Sub Divisions: " + tot_sdiv + "]" + '</label>' +
                        '</td>';
                    hqOfisRows += '</tr>';
                }

                if (selected_dept_cd == 6) {
                    hqOfisRows_hsng += '<tr id="' + rowIndex + selected_dept_cd + '" class="hq_ofis_class_6">';
                    hqOfisRows_hsng +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_6_' + rowIndex +
                        '" name="hq_ofis_6_" ' + +rowIndex + ' class="leftside-sevicelinks_heading1" value="' +
                        rowIndex +
                        '" onclick="getCEUserDataAndAddlCEOfficeData(this, 6);"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_6_' + rowIndex + '">' +
                        "OFFICE OF THE CHIEF ENGINEER (HOUSING)" +
                        "[ Zones : " + tot_zn + ", " + "Circles: " + tot_crl + "," + "Divisions: " + tot_div +
                        ", Sub Divisions: " + tot_sdiv + "]" + '</label>' +
                        '</td>';
                    hqOfisRows_hsng += '</tr>';
                }

                if (selected_dept_cd == 15) {
                    hqOfisRows_mech += '<tr id="' + rowIndex + selected_dept_cd + '" class="hq_ofis_class_15">';
                    hqOfisRows_mech +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_15_' + rowIndex +
                        '" name="hq_ofis_15_"' + rowIndex + ' class="leftside-sevicelinks_heading1" value="' +
                        rowIndex + selected_dept_cd +
                        '" onclick="getCEUserDataAndAddlCEOfficeData(this, ' + selected_dept_cd + ');"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_15_' + rowIndex + '">' +
                        "OFFICE OF THE CHIEF ENGINEER (MECHANICAL)" +
                        "[ Zones : " + tot_zn + ", " + "Circles: " + tot_crl + "," + "Divisions: " + tot_div +
                        ", Sub Divisions: " + tot_sdiv + "]" + '</label>' +
                        '</td>';
                    hqOfisRows_mech += '</tr>';
                }

                if (selected_dept_cd == 3) {
                    hqOfisRows_nh += '<tr id="' + rowIndex + selected_dept_cd +
                        '" class="hq_ofis_class_3">';
                    hqOfisRows_nh +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_3_' + rowIndex +
                        '" name="hq_ofis_3_"' + rowIndex + ' class="leftside-sevicelinks_heading1" value="' +
                        rowIndex + selected_dept_cd +
                        '" onclick="getCEUserDataAndAddlCEOfficeData(this, ' + selected_dept_cd + ');"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_3_' + rowIndex + '">' +
                        "OFFICE OF THE CHIEF ENGINEER (NATIONAL HIGHWAY)" +
                        "[ Zones : " + tot_zn + ", " + "Circles: " + tot_crl + "," +
                        "Divisions: " + tot_div + ", Sub Divisions: " + tot_sdiv + "]" + '</label>' +
                        '</td>';
                    hqOfisRows_nh += '</tr>';
                }
                if (selected_dept_cd == 19) {
                    hqOfisRows_pep += '<tr id="' + rowIndex +
                        '" class="hq_ofis_class_19">';
                    hqOfisRows_pep +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_19_' + rowIndex +
                        '" name="hq_ofis_19_"' + rowIndex + ' class="leftside-sevicelinks_heading1" value="' +
                        rowIndex + selected_dept_cd +
                        '" onclick="getCEUserDataAndSEOffficeData(this, ' + selected_dept_cd + ',' + rowIndex + ');"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_19_' + rowIndex + '">' +
                        "OFFICE OF THE CHIEF ENGINEER" + '</label>' +
                        '</td>';
                    hqOfisRows_pep += '</tr>';
                }

                if (selected_dept_cd == 20) {
                    hqOfisRows_pnd += '<tr id="' + rowIndex + selected_dept_cd +
                        '" class="hq_ofis_class_20">';
                    hqOfisRows_pnd +=
                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                        '<input class="hide-report-input" type="checkbox" id="hq_ofis_20_' + rowIndex +
                        '" name="hq_ofis_20_"' + rowIndex + ' class="leftside-sevicelinks_heading1" value="' +
                        rowIndex + selected_dept_cd +
                        '" onclick="getCEUserDataAndAddlCEOfficeData(this, ' + selected_dept_cd + ');"/>' +
                        '<label class="leftside-sevicelinks_heading1" for="hq_ofis_20_' + rowIndex + '">' +
                        "OFFICE OF THE CHIEF ARCHITECH" + '</label>' +
                        '</td>';
                    hqOfisRows_pnd += '</tr>';
                }

                rowIndex++;
                if (selected_dept_cd == 14)
                    $('#table_tree_rnb').append(hqOfisRows);
                if (selected_dept_cd == 6)
                    $('#table_tree_housing').append(hqOfisRows_hsng);
                if (selected_dept_cd == 15)
                    $('#table_tree_mech').append(hqOfisRows_mech);
                if (selected_dept_cd == 3)
                    $('#table_tree_nh').append(hqOfisRows_nh);
                if (selected_dept_cd == 19)
                    $('#table_tree_pep').append(hqOfisRows_pep);
                if (selected_dept_cd == 20)
                    $('#table_tree_pnd').append(hqOfisRows_pnd);
            } else {
                hqOfisRows = '';

                if (selected_dept_cd == 14) {
                    $('.' + 'hq_ofis_class_14').remove();
                    $('.' + 'hq_user_class_14').remove();
                    $('tr[class^="zo_ofis_class_14_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_14_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_14_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }

                if (selected_dept_cd == 6) {
                    $('.' + 'hq_ofis_class_6').remove();
                    $('.' + 'hq_user_class_6').remove();
                    $('tr[class^="zo_ofis_class_6_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_6_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_6_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'hq_ofis_class_15').remove();
                    $('.' + 'hq_user_class_15').remove();
                    $('tr[class^="zo_ofis_class_15_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_15_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_15_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'hq_ofis_class_3').remove();
                    $('.' + 'hq_user_class_3').remove();
                    $('tr[class^="zo_ofis_class_3_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_3_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_3_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 19) {
                    $('.' + 'hq_ofis_class_19').remove();
                    $('.' + 'hq_user_class_19').remove();
                    $('tr[class^="zo_ofis_class_19_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_19_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_19_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 20) {
                    $('.' + 'hq_ofis_class_20').remove();
                    $('.' + 'hq_user_class_20').remove();
                    $('tr[class^="zo_ofis_class_20_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_20_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_20_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
            }
        }


        function getCEUserDataAndAddlCEOfficeData(element, dept_cd) {
            // alert("clicked on Cheif Eng");
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getHQTreeData",
                    data: {
                        dept_id: dept_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {
                        if (response.status === "1") {
                            hq_user_data = response.hq_user_data;
                            addl_cf_eng_ofs_data = response.addl_cf_eng_ofs_data;
                            count_crl = response.count_crl;
                            count_div = response.count_div;
                            count_sdiv = response.count_sdiv;
                            hqUserRows = '';
                            hqUserRowsHousing = '';
                            hqUserRowsMech = '';
                            hqUserRowsNH = '';
                            hqUserRowsPep = '';
                            hqUserRowsPnd = '';
                            var tot_zn = 0;
                            var tot_crl = 0;
                            var tot_div = 0;
                            var tot_sdiv = 0;


                            for (var i = 0; i < hq_user_data.length; i++) {
                                if (hq_user_data[i].department_id == 14) {
                                    hqUserRows += '<tr class="hq_user_class_14">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td>' +
                                        '<td align="center">' + hq_user_data[i].desg_name + '</td>' +
                                        '<td align="center">' + hq_user_data[i].email + '</td>' +
                                        '<td align="center">' + hq_user_data[i].phoneno + '</td>' +
                                        '<td align="center">' + hq_user_data[i].since_current_position +
                                        '</td>' +
                                        '</tr>';
                                    rowIndex++;
                                }
                                if (hq_user_data[i].department_id == 6) {
                                    hqUserRowsHousing += '<tr class="hq_user_class_6">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td><td align="center">' + hq_user_data[i].desg_name +
                                        '</td><td align="center">' + hq_user_data[i].email +
                                        '</td><td align="center">' + hq_user_data[i].phoneno +
                                        '</td><td align="center">' + hq_user_data[i]
                                            .since_current_position +
                                        '</td></tr>';
                                    rowIndex++;
                                }
                                if (hq_user_data[i].department_id == 15) {
                                    hqUserRowsMech += '<tr class="hq_user_class_15">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td><td align="center">' + hq_user_data[i].desg_name +
                                        '</td><td align="center">' + hq_user_data[i].email +
                                        '</td><td align="center">' + hq_user_data[i].phoneno +
                                        '</td><td align="center">' + hq_user_data[i]
                                            .since_current_position +
                                        '</td></tr>';
                                    rowIndex++;
                                }
                                if (hq_user_data[i].department_id == 3) {

                                    hqUserRowsNH += '<tr class="hq_user_class_3">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td><td align="center">' + hq_user_data[i].desg_name +
                                        '</td><td align="center">' + hq_user_data[i].email +
                                        '</td><td align="center">' + hq_user_data[i].phoneno +
                                        '</td><td align="center">' + hq_user_data[i]
                                            .since_current_position +
                                        '</td></tr>';
                                    rowIndex++;
                                }

                                if (hq_user_data[i].department_id == 19) {

                                    hqUserRowsPep += '<tr class="hq_user_class_19">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td><td align="center">' + hq_user_data[i].desg_name +
                                        '</td><td align="center">' + hq_user_data[i].email +
                                        '</td><td align="center">' + hq_user_data[i].phoneno +
                                        '</td><td align="center">' + hq_user_data[i]
                                            .since_current_position +
                                        '</td></tr>';
                                    rowIndex++;
                                }

                                if (hq_user_data[i].department_id == 20) {

                                    hqUserRowsPnd += '<tr class="hq_user_class_20">' +
                                        '<td align= "right" align="center">' + hq_user_data[i].name +
                                        '</td><td align="center">' + hq_user_data[i].desg_name +
                                        '</td><td align="center">' + hq_user_data[i].email +
                                        '</td><td align="center">' + hq_user_data[i].phoneno +
                                        '</td><td align="center">' + hq_user_data[i]
                                            .since_current_position +
                                        '</td></tr>';
                                    rowIndex++;
                                }
                            }



                            if (dept_cd == 14)
                                $('#table_tree_rnb').append(hqUserRows);
                            if (dept_cd == 6)
                                $('#table_tree_housing').append(hqUserRowsHousing);
                            if (dept_cd == 15)
                                $('#table_tree_mech').append(hqUserRowsMech);
                            if (dept_cd == 3)
                                $('#table_tree_nh').append(hqUserRowsNH);

                            if (dept_cd == 19)
                                $('#table_tree_pep').append(hqUserRowsPep);
                            if (dept_cd == 20)
                                $('#table_tree_pnd').append(hqUserRowsPnd);
                            addlHQOfsRows = '';
                            addlHQOfsRows_housing = '';
                            addlHQOfsRows_mech = '';
                            addlHQOfsRows_nh = '';
                            addlHQOfsRows_pep = '';
                            addlHQOfsRows_pnd = '';
                            for (var i = 0; i < addl_cf_eng_ofs_data.length; i++) {
                                for (var j = 0; j < count_crl.length; j++) {

                                    if (selected_dept_cd == 14 && count_crl[j].dept_cd == 14 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_crl = count_crl[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 && count_crl[j].dept_cd == 6 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_crl = count_crl[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 && count_crl[j].dept_cd == 15 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_crl = count_crl[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 3 && count_crl[j].dept_cd == 3 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {

                                        tot_crl = count_crl[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 19 && count_crl[j].dept_cd == 19 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {

                                        tot_crl = count_crl[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 20 && count_crl[j].dept_cd == 20 &&
                                        count_crl[j]
                                            .zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {

                                        tot_crl = count_crl[j].total;
                                        break;
                                    }
                                }

                                for (var j = 0; j < count_div.length; j++) {

                                    if (selected_dept_cd == 14 && count_div[j].dept_cd == 14 &&
                                        count_div[j].zone_cd === addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 && count_div[j].dept_cd == 6 &&
                                        count_div[j].zone_cd === addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 && count_div[j].dept_cd == 15 &&
                                        count_div[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 3 && count_div[j].dept_cd == 3 &&
                                        count_div[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 19 && count_div[j].dept_cd == 19 &&
                                        count_div[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 20 && count_div[j].dept_cd == 20 &&
                                        count_div[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_div = count_div[j].total;
                                        break;
                                    }
                                }

                                for (var j = 0; j < count_sdiv.length; j++) {

                                    if (selected_dept_cd == 14 && count_sdiv[j].dept_cd == 14 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 && count_sdiv[j].dept_cd == 6 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 && count_sdiv[j].dept_cd == 15 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 3 && count_sdiv[j].dept_cd == 3 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 19 && count_sdiv[j].dept_cd == 19 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 20 && count_sdiv[j].dept_cd == 20 &&
                                        count_sdiv[j].zone_cd == addl_cf_eng_ofs_data[i].zone_cd) {
                                        tot_sdiv = count_sdiv[j].total;
                                        break;
                                    }
                                }
                                if (addl_cf_eng_ofs_data[i].department_id == 14) {
                                    addlHQOfsRows += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_14_' +
                                        addl_cf_eng_ofs_data[i].zone_cd + '">';
                                    addlHQOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_14_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' + rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading2" for="zonal_ofis_14_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name + ' [Circles: ' + tot_crl +
                                        ', Divisions: ' + tot_div + ', Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label>' +
                                        '</td>';
                                    addlHQOfsRows += '</tr>';
                                    rowIndex++;
                                }

                                if (addl_cf_eng_ofs_data[i].department_id == 6) {
                                    console.log("Housing Dept");
                                    addlHQOfsRows_housing += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_6_' + addl_cf_eng_ofs_data[i].zone_cd +
                                        '">';
                                    addlHQOfsRows_housing +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_6_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' + rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading2" for="zonal_ofis_6_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name + ' [Circles: ' + tot_crl +
                                        ', Divisions: ' + tot_div + ', Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label>' +
                                        '</td>';
                                    addlHQOfsRows_housing += '</tr>';
                                    rowIndex++;
                                }
                                if (addl_cf_eng_ofs_data[i].department_id == 15) {
                                    console.log("mechni");
                                    addlHQOfsRows_mech += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_15_' + addl_cf_eng_ofs_data[i].zone_cd +
                                        '">';
                                    addlHQOfsRows_mech +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_15_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' +
                                        rowIndex +
                                        ');"/><label class="leftside-sevicelinks_heading2" for="zonal_ofis_15_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name + ' [Circles: ' + tot_crl +
                                        ', Divisions: ' + tot_div + ', Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label></td>';
                                    addlHQOfsRows_mech += '</tr>';
                                    rowIndex++;
                                }

                                if (addl_cf_eng_ofs_data[i].department_id == 3) {
                                    addlHQOfsRows_nh += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_3_' + addl_cf_eng_ofs_data[i].zone_cd +
                                        '">';
                                    addlHQOfsRows_nh +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_3_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' +
                                        rowIndex +
                                        ');"/><label class="leftside-sevicelinks_heading2" for="zonal_ofis_3_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name + ' [Circles: ' + tot_crl +
                                        ', Divisions: ' + tot_div + ', Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label></td>';
                                    addlHQOfsRows_nh += '</tr>';
                                    rowIndex++;
                                }

                                if (addl_cf_eng_ofs_data[i].department_id == 19) {
                                    addlHQOfsRows_pep += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_19_' + addl_cf_eng_ofs_data[i].zone_cd +
                                        '">';
                                    addlHQOfsRows_pep +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_19_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' +
                                        rowIndex +
                                        ');"/><label class="leftside-sevicelinks_heading2" for="zonal_ofis_19_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name +
                                        '</label></td>';
                                    addlHQOfsRows_pep += '</tr>';
                                    rowIndex++;
                                }

                                if (addl_cf_eng_ofs_data[i].department_id == 20) {
                                    addlHQOfsRows_pnd += '<tr id="' + rowIndex +
                                        '" class="zo_ofis_class_20_' + addl_cf_eng_ofs_data[i].zone_cd +
                                        '">';
                                    addlHQOfsRows_pnd +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="zonal_ofis_20_' +
                                        rowIndex +
                                        '" onclick="getAddlCEUserDataAndSEOffficeData(this, ' +
                                        addl_cf_eng_ofs_data[i].id +
                                        ',' + addl_cf_eng_ofs_data[i].department_id + ',' +
                                        addl_cf_eng_ofs_data[i].zone_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading2" for="zonal_ofis_20_' +
                                        rowIndex + '">' +
                                        addl_cf_eng_ofs_data[i].office_name +
                                        '</label></td>';
                                    addlHQOfsRows_pnd += '</tr>';
                                    rowIndex++;
                                }
                            }

                            if (dept_cd == 14)
                                $('#table_tree_rnb').append(addlHQOfsRows);
                            if (dept_cd == 6)
                                $('#table_tree_housing').append(addlHQOfsRows_housing);
                            if (dept_cd == 15)
                                $('#table_tree_mech').append(addlHQOfsRows_mech);
                            if (dept_cd == 3)
                                $('#table_tree_nh').append(addlHQOfsRows_nh);
                            if (dept_cd == 19)
                                $('#table_tree_pep').append(addlHQOfsRows_pep);
                            if (dept_cd == 20)
                                $('#table_tree_pnd').append(addlHQOfsRows_pnd);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (selected_dept_cd == 14) {
                    $('.' + 'hq_user_class_14').remove();
                    $('tr[class^="zo_ofis_class_14_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_14_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_14_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();

                }

                if (selected_dept_cd == 6) {
                    $('.' + 'hq_user_class_6').remove();
                    $('tr[class^="zo_ofis_class_6_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_6_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_6_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'hq_user_class_15').remove();
                    $('tr[class^="zo_ofis_class_15_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_15_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_15_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'hq_user_class_3').remove();
                    $('tr[class^="zo_ofis_class_3_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_3_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_3_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 19) {
                    $('.' + 'hq_user_class_19').remove();
                    $('tr[class^="zo_ofis_class_19_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_19_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_19_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
                if (selected_dept_cd == 20) {
                    $('.' + 'hq_user_class_20').remove();
                    $('tr[class^="zo_ofis_class_20_"]').remove();
                    $('tr[class^="ZO_user_class_"]').remove();
                    $('tr[class^="co_ofis_class_"]').remove();
                    $('tr[class^="co_user_class_"]').remove();
                    $('tr[class^="do_ofis_class_20_"]').remove();
                    $('tr[class^="DO_user_class_"]').remove();
                    $('tr[class^="sdo_ofis_class_20_"]').remove();
                    $('tr[class^="SDO_user_class_"]').remove();
                }
            }
        }

        function getCEUserDataAndSEOffficeData(element, dept_cd, rowNum) {
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getCEUserDataAndSEOffficeData",
                    data: {
                        dept_id: dept_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {

                        zoUserRows = '';
                        coOfsRows = '';
                        zoUserRowsHousing = '';
                        coOfsRowsHousing = '';
                        if (response.status === "1") {
                            hq_user_data = response.hq_user_data;
                            supd_eng_ofs_data = response.supd_eng_ofs_data;
                            div_dtls = response.div_dtls;
                            sub_div_dtls = response.sub_div_dtls;
                            var tot_div = 0;
                            var tot_sdiv = 0;
                            var i_zo_user = 0;
                            for (var i = 0; i < supd_eng_ofs_data.length; i++) {

                                coOfsRows += '<tr id="' + rowIndex +
                                    '" class="co_ofis_class_' + supd_eng_ofs_data[i].zone_cd + '">';

                                coOfsRows +=
                                    '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                    '<input class="hide-report-input" type="checkbox"' +
                                    ' id="circle_Ofis_' + rowIndex + '"' +
                                    ' onclick="getSEUserDataAndEEOffficeData(this,' + supd_eng_ofs_data[i]
                                        .id +
                                    ',' +
                                    supd_eng_ofs_data[i].department_id + ',' +
                                    supd_eng_ofs_data[i].zone_cd + ',' +
                                    supd_eng_ofs_data[i].circle_cd + ',' +
                                    rowIndex + ');"/>' +
                                    '<label class="leftside-sevicelinks_heading3" for="circle_Ofis_' +
                                    rowIndex + '">' +
                                    supd_eng_ofs_data[i].office_name + '</label></td>';
                                coOfsRows += '</tr>';
                                coOfsRows += '</tr>';
                                rowIndex++;
                            }
                            var row = $('#' + (rowNum));

                            $(row).after(coOfsRows);

                            for (i_hq_user = 0; i_hq_user < hq_user_data.length; i_hq_user++) {
                                zoUserRows += '<tr class="ZO_user_class_' + hq_user_data[i_hq_user]
                                    .zone_cd +
                                    '">' +
                                    '<td align="right">' + hq_user_data[i_hq_user].name +
                                    '</td><td align="center">' + hq_user_data[i_hq_user].desg_name +
                                    '</td><td align="center">' + hq_user_data[i_hq_user].email +
                                    '</td><td align="center">' + hq_user_data[i_hq_user].phoneno +
                                    '</td><td align="center">' + hq_user_data[i_hq_user]
                                        .since_current_position +
                                    '</td></tr>';

                            }

                            //check if any additional officer is posted in this office -- Start
                            for (var i = 0; i < arr_additional_ofis_data.length; i++) {
                                var addlofsjson = arr_additional_ofis_data[i];
                                if (addlofsjson.status == "A" && addlofsjson.office == "19") {
                                    zoUserRows += '<tr class="ZO_user_class_">' +
                                        '<td align= "right" align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.designation_name +
                                        ' (in Additional Charge)</td>' +
                                        '<td align="center">' + addlofsjson.userEmail + '</td>' +
                                        '<td align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.from + '</td>' +
                                        '</tr>';
                                    console.log("Found one Additional Office Charge: " + addlofsjson.userName);
                                }
                            }
                            //check if any additional officer is posted in this office -- End
                            rowIndex++;
                            var row = $('#' + rowNum);
                            $(row).after(zoUserRows);
                        }

                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (selected_dept_cd == 14) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_14_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_14_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 6) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_6_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_6_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_15_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_15_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_3_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_3_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 19) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_19_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_19_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 20) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_20_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_20_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
            }
        }

        function getAddlCEUserDataAndSEOffficeData(element, office_cd, dept_id, zone_cd, rowNum) {
            // alert("clicked on ZO: " + zone_cd);
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getZOtreeData",
                    data: {
                        office_cd: office_cd,
                        dept_id: dept_id,
                        zone_cd: zone_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {
                        zoUserRows = '';
                        coOfsRows = '';
                        zoUserRowsHousing = '';
                        coOfsRowsHousing = '';
                        if (response.status === "1") {
                            zo_user_data = response.zo_user_data;
                            supd_eng_ofs_data = response.supd_eng_ofs_data;
                            div_dtls = response.div_dtls;
                            sub_div_dtls = response.sub_div_dtls;
                            var tot_div = 0;
                            var tot_sdiv = 0;
                            var i_zo_user = 0;
                            for (var i = 0; i < supd_eng_ofs_data.length; i++) {
                                tot_div = 0;
                                tot_sdiv = 0;

                                for (var j = 0; j < div_dtls.length; j++) {

                                    if (selected_dept_cd == 14 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 3 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 19 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 20 &&
                                        div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_div = div_dtls[j].total;
                                        break;
                                    }
                                }

                                for (var j = 0; j < sub_div_dtls.length; j++) {
                                    if (selected_dept_cd == 14 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 3 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 19 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 20 &&
                                        sub_div_dtls[j].zone_cd == supd_eng_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == supd_eng_ofs_data[i].circle_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                }
                                coOfsRows += '<tr id="' + rowIndex +
                                    '" class="co_ofis_class_' + supd_eng_ofs_data[i].zone_cd + '">';
                                if (dept_id == 19 || dept_id == 20) {
                                    coOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox"' +
                                        ' id="circle_Ofis_' + rowIndex + '"' +
                                        ' onclick="getSEUserDataAndEEOffficeData(this,' + supd_eng_ofs_data[i]
                                            .id +
                                        ',' +
                                        supd_eng_ofs_data[i].department_id + ',' +
                                        supd_eng_ofs_data[i].zone_cd + ',' +
                                        supd_eng_ofs_data[i].circle_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading3" for="circle_Ofis_' +
                                        rowIndex + '">' +
                                        supd_eng_ofs_data[i].office_name + '</label></td>';
                                    coOfsRows += '</tr>';
                                } else
                                    coOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox"' +
                                        ' id="circle_Ofis_' + rowIndex + '"' +
                                        ' onclick="getSEUserDataAndEEOffficeData(this,' + supd_eng_ofs_data[i].id +
                                        ',' +
                                        supd_eng_ofs_data[i].department_id + ',' +
                                        supd_eng_ofs_data[i].zone_cd + ',' +
                                        supd_eng_ofs_data[i].circle_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading3" for="circle_Ofis_' +
                                        rowIndex + '">' +
                                        supd_eng_ofs_data[i].office_name + '  [Divisions: ' + tot_div +
                                        ', Sub Divisions: ' + tot_sdiv + ']' + '</label></td>';
                                coOfsRows += '</tr>';
                                rowIndex++;
                            }
                            var row = $('#' + (rowNum));

                            $(row).after(coOfsRows);

                            for (i_zo_user = 0; i_zo_user < zo_user_data.length; i_zo_user++) {
                                zoUserRows += '<tr class="ZO_user_class_' + zo_user_data[i_zo_user]
                                    .zone_cd +
                                    '">' +
                                    '<td align="right">' + zo_user_data[i_zo_user].name +
                                    '</td><td align="center">' + zo_user_data[i_zo_user].desg_name +
                                    '</td><td align="center">' + zo_user_data[i_zo_user].email +
                                    '</td><td align="center">' + zo_user_data[i_zo_user].phoneno +
                                    '</td><td align="center">' + zo_user_data[i_zo_user]
                                        .since_current_position +
                                    '</td></tr>';

                            }

                            //check if any additional officer is posted in this office -- Start
                            for (var i = 0; i < arr_additional_ofis_data.length; i++) {
                                var addlofsjson = arr_additional_ofis_data[i];

                                if (addlofsjson.status == "A" && addlofsjson.office.trim() == office_cd) {
                                    console.log("Hiiiiii");
                                    zoUserRows += '<tr class="ZO_user_class_">' +
                                        '<td align= "right" align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.designation_name +
                                        ' (in Additional Charge)</td>' +
                                        '<td align="center">' + addlofsjson.userEmail + '</td>' +
                                        '<td align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.from + '</td>' +
                                        '</tr>';
                                    console.log("Found one Additional Office Charge: " + addlofsjson.userName);
                                }

                            }
                            //check if any additional officer is posted in this office -- End
                            rowIndex++;
                            var row = $('#' + rowNum);
                            $(row).after(zoUserRows);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (selected_dept_cd == 14) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_14_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_14_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 6) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_6_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_6_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_15_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_15_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_3_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_3_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 19) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_19_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_19_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }

                if (selected_dept_cd == 20) {
                    $('.' + 'ZO_user_class_' + zone_cd).remove();
                    $('.' + 'co_ofis_class_' + zone_cd).remove();
                    $('.' + 'co_user_class_' + zone_cd).remove();
                    $('tr[class^="do_ofis_class_20_' + zone_cd + '_"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_20_' + zone_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_"]').remove();
                }
            }

        }

        function getSEUserDataAndEEOffficeData(element, office_cd, dept_id, zone_cd, circle_cd, rowNum) {
            // alert("Clicked on Circle");
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getCOtreeData",
                    data: {
                        office_cd: office_cd,
                        dept_id: dept_id,
                        zone_cd: zone_cd,
                        circle_cd: circle_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {
                        coUserRows = '';
                        doOfsRows = '';
                        if (response.status === "1") {
                            co_user_data = response.co_user_data;
                            do_ofs_data = response.do_ofs_data;
                            sub_div_dtls = response.sub_div_dtls;
                            var tot_sdiv = 0;
                            var i_co_user = 0;
                            for (var i = 0; i < do_ofs_data.length; i++) {
                                for (var j = 0; j < sub_div_dtls.length; j++) {

                                    if (selected_dept_cd == 14 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 6 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 15 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                    if (selected_dept_cd == 3 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 19 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }

                                    if (selected_dept_cd == 20 &&
                                        sub_div_dtls[j].zone_cd == do_ofs_data[i].zone_cd &&
                                        sub_div_dtls[j].circle_cd == do_ofs_data[i].circle_cd &&
                                        sub_div_dtls[j].div_cd == do_ofs_data[i].division_cd) {
                                        tot_sdiv = sub_div_dtls[j].total;
                                        break;
                                    }
                                }
                                if (do_ofs_data[i].department_id == 14) {
                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_14_' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="do_ofis_14_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex +
                                        ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4" for="do_ofis_14_' +
                                        rowIndex + '">' +
                                        do_ofs_data[i].office_name + '[Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }

                                if (do_ofs_data[i].department_id == 6) {
                                    console.log("div ofs name : " + do_ofs_data[i].office_name);
                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_6_' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="do_ofis_6_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex +
                                        ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4" for="do_ofis_6_' +
                                        rowIndex +
                                        '">' +
                                        do_ofs_data[i].office_name + '[Sub Divisions: ' + tot_sdiv + ']' +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }
                                if (do_ofs_data[i].department_id == 15) {

                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_15_"+' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="do_ofis_15_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4"' +
                                        ' for="do_ofis_15_' + rowIndex + '">' +
                                        do_ofs_data[i].office_name + '[Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }
                                if (do_ofs_data[i].department_id == 3) {

                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_3_' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="div_ofis_3_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4" for="div_ofis_3_' +
                                        rowIndex + '">' +
                                        do_ofs_data[i].office_name + '[Sub Divisions: ' + tot_sdiv +
                                        ']' +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }


                                if (do_ofs_data[i].department_id == 19) {

                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_19_' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="div_ofis_19_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4" for="div_ofis_19_' +
                                        rowIndex + '">' +
                                        do_ofs_data[i].office_name +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }

                                if (do_ofs_data[i].department_id == 20) {

                                    doOfsRows += '<tr id="' + rowIndex + '"' +
                                        ' class="do_ofis_class_20_' + do_ofs_data[i].zone_cd + "_" +
                                        do_ofs_data[i].circle_cd + '">';
                                    doOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="div_ofis_20_' +
                                        rowIndex +
                                        '" onclick="getDOUserDataAndSDOOffficeData(this, ' + do_ofs_data[i].id +
                                        ',' +
                                        do_ofs_data[i].department_id + ',' +
                                        do_ofs_data[i].zone_cd + ',' +
                                        do_ofs_data[i].circle_cd + ',' +
                                        do_ofs_data[i].division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading4" for="div_ofis_20_' +
                                        rowIndex + '">' +
                                        do_ofs_data[i].office_name +
                                        '</label></td>';
                                    doOfsRows += '</tr>';
                                    rowIndex++;
                                }
                            }
                            var row = $('#' + (rowNum));
                            $(row).after(doOfsRows);
                            for (i_co_user = 0; i_co_user < co_user_data.length; i_co_user++) {
                                coUserRows += '<tr class="co_user_class_' + co_user_data[i_co_user]
                                    .zone_cd + "_" + co_user_data[i_co_user]
                                        .circle_cd +
                                    '">' +
                                    '<td align="right">' + co_user_data[i_co_user].name +
                                    '</td><td align="center">' + co_user_data[i_co_user].desg_name +
                                    '</td><td align="center">' + co_user_data[i_co_user].email +
                                    '</td><td align="center">' + co_user_data[i_co_user].phoneno +
                                    '</td><td align="center">' + co_user_data[i_co_user]
                                        .since_current_position +
                                    '</td></tr>';

                            }


                            rowIndex++;
                            // $('.table_Border_Tabular1').append(zoUserRows);
                            var row = $('#' + rowNum);
                            $(row).after(coUserRows);
                        }
                        //check if any additional officer is posted in this office -- Start
                        for (var i = 0; i < arr_additional_ofis_data.length; i++) {
                            var addlofsjson = arr_additional_ofis_data[i];
                            if (addlofsjson.status == "A" && addlofsjson.office == office_cd) {
                                zoUserRows += '<tr class="co_user_class_">' +
                                    '<td align= "right" align="center">' + addlofsjson.userName + '</td>' +
                                    '<td align="center">' + addlofsjson.designation_name +
                                    ' (in Additional Charge)</td>' +
                                    '<td align="center">' + addlofsjson.userEmail + '</td>' +
                                    '<td align="center">' + addlofsjson.userName + '</td>' +
                                    '<td align="center">' + addlofsjson.from + '</td>' +
                                    '</tr>';
                                console.log("Found one Additional Office Charge: " + addlofsjson.userName);
                            }
                        }
                        //check if any additional officer is posted in this office -- End
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (selected_dept_cd == 14) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_14_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_14_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }

                if (selected_dept_cd == 6) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_6_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_6_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_15_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_15_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_3_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_3_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }

                if (selected_dept_cd == 19) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_19_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_19_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }

                if (selected_dept_cd == 20) {
                    $('.' + 'co_user_class_' + zone_cd + '_' + circle_cd).remove();
                    $('tr[class^="do_ofis_class_20_' + zone_cd + '_' + circle_cd + '"]').remove();
                    $('tr[class^="DO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="sdo_ofis_class_20_' + zone_cd + '_' + circle_cd + '_"]').remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_"]').remove();
                }
            }
        }


        function getDOUserDataAndSDOOffficeData(element, office_cd, dept_id, zone_cd, circle_cd, division_cd, rowNum) {
            // alert('clicked on div');
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getDOtreeData",
                    data: {
                        office_cd: office_cd,
                        dept_id: dept_id,
                        zone_cd: zone_cd,
                        circle_cd: circle_cd,
                        division_cd: division_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {
                        doUserRows = '';
                        sdoOfsRows = '';
                        if (response.status === "1") {
                            do_user_data = response.do_user_data;
                            sdo_ofs_data = response.sdo_ofs_data;
                            var i_do_user = 0;
                            for (var i = 0; i < sdo_ofs_data.length; i++) {
                                if (sdo_ofs_data[i].department_id == 14) {

                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_14_' + sdo_ofs_data[i].zone_cd + "_" +
                                        +
                                        sdo_ofs_data[i].circle_cd + "_" + sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_14_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_14_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }
                                if (sdo_ofs_data[i].department_id == 6) {

                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_6_' + sdo_ofs_data[i].zone_cd + "_" + +
                                        sdo_ofs_data[i].circle_cd + "_" +
                                        sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_6_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex +
                                        ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_6_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }
                                if (sdo_ofs_data[i].department_id == 15) {

                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_15_' + sdo_ofs_data[i].zone_cd + "_" +
                                        +
                                        sdo_ofs_data[i].circle_cd + "_" +
                                        sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_15_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_15_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }
                                if (sdo_ofs_data[i].department_id == 3) {
                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_3_' + sdo_ofs_data[i].zone_cd + "_" + +
                                        sdo_ofs_data[i].circle_cd + "_" + sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_3_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_3_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }

                                if (sdo_ofs_data[i].department_id == 19) {
                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_19_' + sdo_ofs_data[i].zone_cd + "_" + +
                                        sdo_ofs_data[i].circle_cd + "_" + sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_19_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_19_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }

                                if (sdo_ofs_data[i].department_id == 20) {
                                    sdoOfsRows += '<tr id="' + rowIndex +
                                        '" class="sdo_ofis_class_20_' + sdo_ofs_data[i].zone_cd + "_" + +
                                        sdo_ofs_data[i].circle_cd + "_" + sdo_ofs_data[i].division_cd +
                                        '">';
                                    sdoOfsRows +=
                                        '<td colspan="5" class="light-td-blue-bg" align="left">' +
                                        '<input class="hide-report-input" type="checkbox" id="sdo_ofis_20_' +
                                        rowIndex +
                                        '" onclick="getSDOUserData(this, ' + sdo_ofs_data[i].id + ',' +
                                        sdo_ofs_data[i].department_id + ',' +
                                        sdo_ofs_data[i].zone_cd + ',' +
                                        sdo_ofs_data[i].circle_cd + ',' +
                                        sdo_ofs_data[i].division_cd + ',' +
                                        sdo_ofs_data[i].sub_division_cd + ',' +
                                        rowIndex + ');"/>' +
                                        '<label class="leftside-sevicelinks_heading5" for="sdo_ofis_20_' +
                                        rowIndex + '">' +
                                        sdo_ofs_data[i].office_name +
                                        '</label></td>';
                                    sdoOfsRows += '</tr>';
                                    rowIndex++;
                                }
                            }
                            var row = $('#' + (rowNum));
                            $(row).after(sdoOfsRows);
                            for (i_do_user = 0; i_do_user < do_user_data.length; i_do_user++) {
                                doUserRows += '<tr class="DO_user_class_' +
                                    do_user_data[i_do_user].zone_cd + "_" +
                                    do_user_data[i_do_user].circle_cd + "_" +
                                    do_user_data[i_do_user].division_cd + '">' +
                                    '<td align="right">' + do_user_data[i_do_user].name +
                                    '</td><td align="center">' + do_user_data[i_do_user].desg_name +
                                    '</td><td align="center">' + do_user_data[i_do_user].email +
                                    '</td><td align="center">' + do_user_data[i_do_user].phoneno +
                                    '</td><td align="center">' + do_user_data[i_do_user]
                                        .since_current_position +
                                    '</td></tr>';

                            }

                            //check if any additional officer is posted in this office -- Start
                            for (var i = 0; i < arr_additional_ofis_data.length; i++) {
                                var addlofsjson = arr_additional_ofis_data[i];
                                if (addlofsjson.status == "A" && addlofsjson.office == office_cd) {
                                    zoUserRows += '<tr class="DO_user_class_">' +
                                        '<td align= "right" align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.designation_name +
                                        ' (in Additional Charge)</td>' +
                                        '<td align="center">' + addlofsjson.userEmail + '</td>' +
                                        '<td align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.from + '</td>' +
                                        '</tr>';
                                    console.log("Found one Additional Office Charge: " + addlofsjson.userName);
                                }
                            }
                            //check if any additional officer is posted in this office -- End
                            rowIndex++;
                            // $('.table_Border_Tabular1').append(zoUserRows);
                            var row = $('#' + rowNum);
                            $(row).after(doUserRows);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (selected_dept_cd == 14) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_14_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }
                if (selected_dept_cd == 6) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_6_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }
                if (selected_dept_cd == 15) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_15_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }
                if (selected_dept_cd == 3) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_3_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }

                if (selected_dept_cd == 19) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_19_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }

                if (selected_dept_cd == 20) {
                    $('.' + 'DO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('.' + 'sdo_ofis_class_20_' + zone_cd + "_" + circle_cd + "_" + division_cd).remove();
                    $('tr[class^="SDO_user_class_' + zone_cd + '_' + circle_cd + '_' + division_cd + '_"]').remove();
                }
            }
        }

        function getSDOUserData(element, office_cd, dept_id, zone_cd, circle_cd, division_cd, sub_division_cd,
            rowNum) {
            if ($(element).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/getSDOtreeData",
                    data: {
                        office_cd: office_cd,
                        dept_id: dept_id,
                        zone_cd: zone_cd,
                        circle_cd: circle_cd,
                        division_cd: division_cd,
                        sub_division_cd: sub_division_cd
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    cache: false,
                    success: function (response) {
                        sdoUserRows = '';

                        if (response.status === "1") {
                            sdo_user_data = response.sdo_user_data;
                            // console.log(sdo_user_data);
                            var i_sdo_user = 0;

                            for (i_sdo_user = 0; i_sdo_user < sdo_user_data.length; i_sdo_user++) {
                                sdoUserRows += '<tr ' +
                                    'class="SDO_user_class_' + sdo_user_data[i_sdo_user].zone_cd + "_" +
                                    sdo_user_data[i_sdo_user].circle_cd + "_" + sdo_user_data[i_sdo_user]
                                        .division_cd + "_" + sdo_user_data[i_sdo_user]
                                        .sub_division_cd + '">' +
                                    '<td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;' +
                                    sdo_user_data[i_sdo_user].name +
                                    '</td><td align="center">' + sdo_user_data[i_sdo_user].desg_name +
                                    '</td><td align="center">' + sdo_user_data[i_sdo_user].email +
                                    '</td><td align="center">' + sdo_user_data[i_sdo_user].phoneno +
                                    '</td><td align="center">' + sdo_user_data[i_sdo_user]
                                        .since_current_position +
                                    '</td></tr>';

                            }

                            //check if any additional officer is posted in this office -- Start
                            for (var i = 0; i < arr_additional_ofis_data.length; i++) {

                                var addlofsjson = arr_additional_ofis_data[i];
                                if (addlofsjson.status === "A" && (addlofsjson.office).toString() == office_cd
                                    .toString()) {

                                    sdoUserRows += '<tr class="SDO_user_class_' + addlofsjson.zone_cd + "_" +
                                        addlofsjson.circle_cd + "_" + addlofsjson.division_cd + "_" +
                                        addlofsjson.sub_division_cd + '">' +
                                        '<td align="right">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;' +
                                        addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.designation_name +
                                        ' (in Additional Charge)</td>' +
                                        '<td align="center">' + addlofsjson.userEmail + '</td>' +
                                        '<td align="center">' + addlofsjson.userName + '</td>' +
                                        '<td align="center">' + addlofsjson.from + '</td>' +
                                        '</tr>';
                                    console.log("Found one Additional Office Charge: " + addlofsjson.userName);
                                }
                            }
                            //check if any additional officer is posted in this office -- End
                            rowIndex++;
                            var row = $('#' + rowNum);
                            $(row).after(sdoUserRows);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                $('.' + 'SDO_user_class_' + zone_cd + "_" + circle_cd + "_" + division_cd + "_" + sub_division_cd).remove();
            }
        }
    </script>
    <script src="{{ asset('js/oamis-ui.js') }}"></script>
</body>

</html>