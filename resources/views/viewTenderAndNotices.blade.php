@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Information</span>
                    <h1>Circulars</h1>
                    <p>Nagaland PWD</p>
                </div>
            </div>
            <div class="public-list-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ route('getWelcomeDashBoard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </section>

        <section class="public-list-card">
            <div class="public-list-title">
                <h2>Circular details</h2>
                <span>Active Circulars</span>
            </div>
            <div class="public-list-table-wrap">
                <table class="table-responsive table text-s table-hover table-bordered table-striped user_list"
                    id="tblTenderDtls" style="width:100%;">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">SL No.</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Circular No.</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Subject</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($tender_dtls as $key)
                            <tr class="table-primary">
                                <td class="text-center">{{ $i }}</td>
                                <td style="position: relative">
                                    {{ $key->tender_cd }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->tender_title }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->tender_desc }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->department_name }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        {{-- <div class="tab-pane" id="notice">
                <table class="table-responsive table text-s table-hover table-bordered table-striped user_list"
                    id="tblNotificationDtls">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">SL No.</th>
                        <th class="text-center">Tender Code</th>
                        <th class="text-center">Tender Title</th>
                        <th class="text-center">Tender Desc</th>
                        <th class="text-center">Department Name</th>
                        <th class="text-center">Date of Expiry</th>
                        <th class="text-center">Download</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($notice_dtls as $key)
                            <tr class="table-primary">
                                <td class="text-center">{{ $i }}</td>
                                <td style="position: relative">
                                    {{ $key->notice_cd }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->notice_title }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->notice_desc }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->department_name }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->date_expiry }}
                                </td>
                                <td style="position: relative">
                                    <a href="{{ route('download_notification', ['id' => $key->notice_cd]) }}">Download</a>
                                </td>

                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
    </main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-list.css') }}">
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#tblTenderDtls').DataTable({
                // "buttons": [{
                //         extend: 'copy',
                //         className: 'btn btn-primary glyphicon glyphicon-duplicate'
                //     },
                //     {
                //         extend: 'csv',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }, ,
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }, ,
                //     {
                //         extend: 'pdf',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }
                // ]
            }).buttons().container().appendTo('.spanRoadDistressDetail');

            $('#tblNotificationDtls').DataTable({
                // "buttons": [{
                //         extend: 'copy',
                //         className: 'btn btn-primary glyphicon glyphicon-duplicate'
                //     },
                //     {
                //         extend: 'csv',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }, ,
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }, ,
                //     {
                //         extend: 'pdf',
                //         className: 'btn btn-primary glyphicon glyphicon-save-file'
                //     }
                // ]
            }).buttons().container().appendTo('.spanRoadDistressDetail');
        });


        $('.modalClose').click(function() {
            //location.reload();
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var year = dateObj.getFullYear();
            var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
            var day = ('0' + dateObj.getDate()).slice(-2);
            var formattedDate = year + '-' + month + '-' + day;
            $(this).val(formattedDate);
        });
    </script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
