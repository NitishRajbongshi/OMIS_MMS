@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Project List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody">

            <h6 class="p-2 mt-3 border border-primary text-light bg-primary text-uppercase text-sm">
                List of Projects – Roads and Bridges Department
            </h6>

            <!-- {{-- Search Bar --}}
            <div class="mb-3">
                <input type="text"
                       id="projectSearch"
                       class="form-control"
                       placeholder="Search by Project Name..." />
            </div> -->

            {{-- Project Table --}}
            <div class="border py-2">
                <table class="table text-sm table-bordered table-striped projectTable" id="project_details_table">

                    <thead style="background-color: #7BBFD4; color: #1a1a2e; font-weight: 700;">
                        <tr>
                            <th class="text-center">Project Code</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Project Type</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Sub Division</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Start Date</th>
                            <th class="text-center">Location</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="projectTableBody">

                        @forelse ($project_list as $index => $prj)

                            {{-- Main Row --}}
                            <tr class="project-row" data-name="{{ strtolower($prj->project_name ?? '') }}">

                                <td class="text-center">
                                    {{ $prj->project_cd ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $prj->project_name ?? 'N/A' }}
                                </td>
                                <td class="text-center">
                                    {{ $prj->proj_type_descr ?? 'N/A' }}
                                <td class="text-center">
                                    {{ $prj->division_name ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $prj->sub_division_name ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $prj->owner_dept_name ?? 'Roads and Bridges' }}
                                </td>

                                <td class="text-center">
                                    @if(!empty($prj->project_start_date))
                                        {{ \Carbon\Carbon::parse($prj->project_start_date)->format('d-M-Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $prj->location ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('pms.progress', $prj->project_cd) }}"
                                        class="btn btn-primary btn-sm px-4 project-submit-btn">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Submit Progress
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    No Project Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </section>
@endsection

@push('styles')
    <style>
        #projectSearch {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .detail-row td {
            border-top: none;
        }

        .toggle-detail {
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .project-row td {
            vertical-align: middle;
        }

        .project-submit-btn {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            border-radius: 999px !important;
            color: #ffffff !important;
            font-family: var(--oamis-font) !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            min-height: 36px;
            padding: 9px 16px !important;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
        }

        .project-submit-btn i,
        .project-submit-btn span {
            color: #ffffff !important;
        }

        .project-submit-btn:hover,
        .project-submit-btn:focus {
            background: #07583d !important;
            border-color: #07583d !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        html[data-theme="dark"] .project-submit-btn {
            background: #0b6b4a !important;
            border-color: #34d399 !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>

        $(document).ready(function () {

            // DataTable Init
            $('.projectTable').DataTable({
                pageLength: 25,
                ordering: true,
                columnDefs: [
                    {
                        orderable: false,
                        targets: 7
                    }
                ]
            });

            // Toggle Detail Row
            $(document).on('click', '.toggle-detail', function () {

                let target = $(this).data('target');
                let $row = $('#' + target);
                let $icon = $(this).find('i');

                if ($row.is(':visible')) {

                    $row.hide();

                    $icon
                        .removeClass('fa-chevron-up')
                        .addClass('fa-chevron-down');

                } else {

                    $('.detail-row').hide();

                    $('.toggle-detail i')
                        .removeClass('fa-chevron-up')
                        .addClass('fa-chevron-down');

                    $row.show();

                    $icon
                        .removeClass('fa-chevron-down')
                        .addClass('fa-chevron-up');
                }
            });

            // Search Filter
            $('#projectSearch').on('keyup', function () {

                let val = $(this).val().toLowerCase();

                $('.projectTable')
                    .DataTable()
                    .search(val)
                    .draw();
            });

        });

    </script>
@endpush