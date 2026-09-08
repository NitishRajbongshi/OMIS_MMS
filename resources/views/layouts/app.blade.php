<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script>
        try {
            document.documentElement.setAttribute('data-theme', localStorage.getItem('oamis-theme') || 'light');
            var oamisFontSize = localStorage.getItem('oamis-font-size') || 'normal';
            var oamisFontScale = { small: .9, normal: 1, large: 1.1, xlarge: 1.2 }[oamisFontSize] || 1;
            document.documentElement.setAttribute('data-font-size', oamisFontSize);
            document.documentElement.style.setProperty('--oamis-font-scale', oamisFontScale);
        } catch (error) {
            document.documentElement.setAttribute('data-theme', 'light');
            document.documentElement.setAttribute('data-font-size', 'normal');
            document.documentElement.style.setProperty('--oamis-font-scale', 1);
        }
    </script>
    <link rel="icon" href="{!! asset('images/main_logo.png') !!}" />
    <title>OMIS</title>
    <!-- Font Awesome -->
    {{--
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 and 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/oamis-theme.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- dataTable -->
    <link rel="stylesheet" href="{{ asset('dataTables/datatables.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('dataTables/datatables.min.css') }}" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Nitish 22-08-23 --}}
    <!-- jQuery library -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.6.0.min.js') }}"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css') }}">
    <!-- DataTables JS -->
    <script src="{{ asset('https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js') }}"></script>

    <!-- DataTables ColVis extension -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>

    <!-- DataTables Buttons extension (CSV, PDF, Copy) -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>

    {{-- Alpine js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>

    <!-- Pako JS for decompress and decode json data -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>

    <!-- sweetalert -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    {{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" /> --}}
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
            font-size: 12px;
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

        /* Highlighted blinking text */
        .blinking-text {
            /* font-size: 24px; */
            font-weight: bold;
            color: red;
            /* background-color: yellow; */
            padding: 5px 10px;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/oamis-theme.css') }}">
    {{-- It will include extra links only for particular page --}}
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/oamis-controls.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.preloader')
        @include('layouts.header')
        @include('layouts.sidebar')
        @include('sweet::alert')
        <div class="content-wrapper">
            @yield('content')
            @include('layouts/footer')
        </div>
    </div>
    {{-- It will include extra script only for particular page --}}
    @stack('scripts')
    <script>
        window.addEventListener('load', function () {
            const preloader = document.getElementById('preloader');
            preloader.style.display = 'none';
        });
    </script>
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <!-- jQuery Knob Chart -->
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
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dataTables/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dataTables/datatables.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>
    <script src="{{ asset('js/oamis-ui.js') }}"></script>

    <script>
        $(document).ready(function () {

            // // Restore open menu
            // let openMenu = localStorage.getItem('openMenu');
            // if (openMenu) {
            //     $('#' + openMenu).addClass('menu-open');
            //     $('#' + openMenu + ' > a').addClass('active');
            // }

            // // Store clicked menu
            // $('.nav-item > a').on('click', function () {
            //     let parent = $(this).parent();

            //     if (parent.find('.nav-treeview').length > 0) {
            //         let id = parent.attr('id');

            //         if (!parent.hasClass('menu-open')) {
            //             localStorage.setItem('openMenu', id);
            //         }
            //     }
            // });



            // Restore ALL open menus
            let openMenus = JSON.parse(localStorage.getItem('openMenus')) || [];

            openMenus.forEach(function (id) {
                $('#' + id).addClass('menu-open');
                $('#' + id + ' > a').addClass('active');
            });

            // Store ALL parent menus on click
            $('.nav-sidebar a').on('click', function () {

                let parents = $(this).parents('.nav-item');
                let openMenus = [];

                parents.each(function () {
                    let id = $(this).attr('id');
                    if (id) {
                        openMenus.push(id);
                    }
                });

                localStorage.setItem('openMenus', JSON.stringify(openMenus));

                $('.nav-sidebar a').removeClass('active');
                $(this).addClass('active');
            });

            function normalizeNavPath(url) {
                let parser = document.createElement('a');
                parser.href = url;

                return parser.pathname.replace(/\/+$/, '') || '/';
            }

            let currentPath = normalizeNavPath(window.location.href);

            $('.nav-sidebar a').each(function () {
                if (this.getAttribute('href') !== '#' && normalizeNavPath(this.href) === currentPath) {
                    $(this).addClass('active');
                    let parentMenu = $(this).closest('.nav-item').parent().closest('.nav-item');
                    if (parentMenu.length) {
                        parentMenu.addClass('menu-open');
                    }
                }
            });

        });
    </script>
</body>

</html>
