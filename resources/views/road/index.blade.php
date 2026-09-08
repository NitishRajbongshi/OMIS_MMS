@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-sm">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Manage Roads</li>
                    </ol>
                </div>
                <div class="d-flex justify-content-end align-items-center flex-wrap">
                    @if (session('finalised') === 1)
                        <div class="mb-1 ms-2">
                            <a href="{{ route('finalize.road') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-circle"></i>
                                Finalize Road
                            </a>
                        </div>
                    @endif
                    @if (session('officeType') != 'SDO')
                        <div class="mb-1 mx-1">
                            <a href="{{ route('chainage') }}" class="btn btn-sm btn-outline-success"><i class="fas fa-plus"></i>
                                Chainage</a>
                        </div>
                    @endif
                    @if (session('officeType') === 'HQ')
                        <div class="mb-1 ">
                            <a href="{{ route('HqChainage') }}" class="btn btn-sm btn-outline-info"><i
                                    class="fas fa-plus"></i>
                                Push road to lower level
                            </a>
                        </div>
                    @endif
                    @if (session('dataEntry') === 1 || session('dataEntry') === 'Y')
                        <div class="mb-1 mx-1">
                            <a href="{{ route('road.add-road') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-road"></i>
                                Add Road</a>
                        </div>
                    @endif
                    @if (session('dataEntry') === 1 || session('dataEntry') === 'Y')
                        <div class="mb-1 mx-1">
                            <a href="{{ route('project.list.assets') }}" class="btn btn-sm btn-outline-primary"><i
                                    class="fas fa-road"></i>
                                Create Asset from Completed Projects</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="content p-1">
        @if (session('failed'))
            <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <strong>Failed!</strong> {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h6 class="p-2 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF FINALIZED ROADS UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid py-2 mainBody">
            <span class="mis-btn-rd"></span>
            <table class="table-responsive text-xs table table-bordered table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th style="min-width: 3rem;" class="text-center">Sl No.</th>
                    <th style="min-width: 6rem;" class="text-center">Road ID</th>
                    <th style="min-width: 8rem;" class="text-center">Road Name</th>
                    <!-- saiful # 21-04-2026 # Start -->
                    <th style="min-width: 8rem;" class="text-center">Project CD</th>
                    <!-- saiful # 21-04-2026 # End -->
                    <th style="min-width: 6rem;" class="text-center">Length(Km)</th>
                    <th style="min-width: 6rem;" class="text-center">Category</th>
                    <th style="min-width: 6rem;" class="text-center">Road Type</th>
                    <th style="min-width: 6rem;" class="text-center">Road Owner</th>
                    <th style="min-width: 5rem;" class="text-center">CD Work</th>
                    <th style="min-width: 5rem;" class="text-center">Bridge</th>
                    <th style="min-width: 8rem;" class="text-center">Protection Wall</th>
                    <th style="min-width: 5rem;" class="text-center">PCI</th>
                    <th style="min-width: 5rem;" class="text-center">Pavement</th>
                    <th style="min-width: 5rem;" class="text-center">Habitation</th>
                    <th style="min-width: 5rem;" class="text-center">Chainage</th>
                    <th style="min-width: 5rem;" class="text-center">View</th>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($roadDetails as $roadDetail)
                        <tr class="text-center">
                            <td>{{ $i }}</td>
                            <td>
                                {{ $roadDetail->rd_system_id }}
                            </td>
                            <td>
                                {{ $roadDetail->rd_name }}
                            </td>
                            <!-- saiful # 21-04-2026 # Start -->
                            <td>
                                {{ $roadDetail->project_cd }}
                            </td>
                            <!-- saiful # 21-04-2026 # End -->
                            <td>
                                {{ $roadDetail->road_length }}
                            </td>
                            <td>
                                {{ $roadDetail->rd_catg_descr }}
                            </td>
                            <td>
                                {{ $roadDetail->rd_type_descr }}
                            </td>
                            <td>
                                {{ $roadDetail->owner_name }}
                            </td>
                            <td class="cd-work-details">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('manageCDWorks', ['id' => $roadDetail->rd_system_id]) }}"><i class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-cd-works/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="cd-bridge-details">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('road.cd-bridge-details', ['id' => $roadDetail->rd_system_id]) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-bridge-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="cd-bridge-details">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('road.protection.wall', ['id' => $roadDetail->rd_system_id]) }}"><i class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-bridge-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="cd-pavement-details">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('road.pci', ['id' => $roadDetail->rd_system_id]) }}"><i class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-pci-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('managePavement', ['id' => $roadDetail->rd_system_id]) }}"><i class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-pavement-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="">
                                @if (session('users_office_type_cd') === 'DO' || session('users_office_type_cd') === 'SDO')
                                    <a href="{{ route('road.habitation', ['id' => $roadDetail->rd_system_id]) }}"><i class="fa fa-plus"></i></a>
                                @else
                                    @if (session('viewed') == 1)
                                        <a href="{{ url('/asset-management/road/show-habitation-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>

                            <td class="">
                                <a href="{{ url('/asset-management/chainage/' . $roadDetail->rd_system_id) }}"><i class="fa fa-eye"></i></a>
                            </td>

                            <td class="">
                                @if (session('inserted') == 0 && session('deleted ') == 0 && session('updated') == 0 && session('viewed') == 0)
                                    <i class="fa fa-lock"></i>
                                @else
                                    <a href="{{ route('showRoad', ['id' => $roadDetail->rd_system_id]) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <?php    $i++; ?>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2 d-flex justify-content-start align-items-center gap-1">
                @if ($roadDetails->hasPages())
                    {{ $roadDetails->links() }}
                    <span class="text-sm">Click next to load other 200 roads.</span>
                @endif
            </div>
        </div>


        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT ROADS READY FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid py-2 mainBody mt-1">
            <div class="d-flex text-sm justify-content-end mb-2">
                <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    Send data for finalization
                </button>
            </div>
            {{-- <span class="mis-btn-rd-draft"></span> --}}
            <table class="table-responsive text-xs table table-bordered table-striped" id="roadDetailDraft">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">Sl No.</th>
                    <th class="text-center">Road ID</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Length(Km)</th>
                    <th class="text-center">Road Category</th>
                    <th class="text-center">Road Type</th>
                    <th class="text-center">Road Owner</th>
                    <!-- Saiful # 20-04-2026 # Start -->
                    <th class="text-center">Project CD</th>
                    <!-- Saiful # 20-04-2026 # End -->
                    <th class="text-center">Is Rejected?</th>
                    <th class="text-center">Reason of Rejection</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Select</th>
                    <th class="text-center">Delete</th>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($roadDraftDetails as $roadDraftDetail)
                        <tr class="text-center">
                            <td>{{ $i }}</td>
                            <td>
                                {{ $roadDraftDetail->rd_system_id }}
                            </td>
                            <td class="text-uppercase">
                                {{ $roadDraftDetail->rd_name }}
                            </td>
                            <td>
                                {{ $roadDraftDetail->road_length }}
                            </td>
                            <td class="text-uppercase">
                                {{ $roadDraftDetail->rd_catg_descr }}
                            </td>
                            <td class="text-uppercase">
                                {{ $roadDraftDetail->rd_type_descr }}
                            </td>
                            <td class="text-uppercase">
                                {{ $roadDraftDetail->owner_name }}
                            </td>
                            <!-- Saiful # 20-04-2026 # Start -->
                            <td class="text-uppercase">
                                {{ $roadDraftDetail->project_cd }}
                            </td>
                            <!-- Saiful # 20-04-2026 # End -->
                            <td>
                                {{ $roadDraftDetail->is_rejected == 'Y' ? 'YES' : 'NO' }}
                            </td>
                            <td>
                                {{ $roadDraftDetail->reason_of_rejection }}
                            </td>
                            <th>
                                <a class="text-warning edit" data-toggle="modal" data-target="#roadEditModal"
                                    data-rd-id="{{ $roadDraftDetail->rd_system_id }}"
                                    data-rd-name="{{ $roadDraftDetail->rd_name }}"
                                    data-rd-length="{{ $roadDraftDetail->road_length }}"
                                    data-rd-catg="{{ $roadDraftDetail->rd_category_cd }}"
                                    data-rd-type="{{ $roadDraftDetail->rd_type_cd }}"
                                    data-rd-owner="{{ $roadDraftDetail->rd_owner_cd }}"
                                    data-rejection="{{ $roadDraftDetail->reason_of_rejection }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </th>
                            <td>
                                <input type="checkbox" class="selected-asset"
                                    data-road-id="{{ $roadDraftDetail->rd_system_id }}" />
                            </td>
                            <th>
                                <form action="{{ route('destroy.road') }}" method="post">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" name="road_id" value="{{ $roadDraftDetail->rd_system_id }}">
                                    <button type="submit" class="border-0 bg-transparent"
                                        onclick="return confirm('Are you sure you want to delete this road?')">
                                        <i class="fa fa-trash text-xs text-danger"></i>
                                    </button>
                                </form>
                            </th>
                        </tr>
                        <?php    $i++; ?>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2 ">
                @if ($roadDraftDetails->hasPages())
                    {{ $roadDraftDetails->links() }}
                    <span class="text-sm">Click next to load other 200 roads.</span>
                @endif
            </div>
        </div>
    </section>
    <!-- The Edit Modal -->
    <div class="modal" id="roadEditModal">
        <div class="modal-dialog modal-lg text-xs">
            <form class="editRoadForm" id="editRoadForm" name="editRoadForm" method="POST" action="#"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="edit_road_id" value="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                        <strong class="text-md text-uppercase">Update Road Details</strong>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row text-xs">
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_name">Road Name<span class="star">*</span></label>
                                <input type="text" class="form-control" id="edit_road_name" name="road_name" value="">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_category">Road Category <span class="star">*</span></label>
                                <select class="form-control" id="edit_road_category" name="road_category">
                                    @foreach ($roadCategories as $roadCategory)
                                        <option value="{{ $roadCategory->rd_catg_cd }}">
                                            {{ $roadCategory->rd_catg_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_type">Road Type <span class="star">*</span></label>
                                <select class="form-control" id="edit_road_type" name="road_type">
                                    @foreach ($roadTypes as $roadType)
                                        <option value="{{ $roadType->rd_type_cd }}">
                                            {{ $roadType->rd_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_length">Road Length (Km) <span class="star">*</span></label>
                                <input type="number" step="0.001" id="edit_road_length" class="form-control"
                                    name="road_length" placeholder="0.000" value="">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_owner">Road Owner <span class="star">*</span></label>
                                <select class="form-control" id="edit_road_owner" name="road_owner">
                                    @foreach ($roadOwners as $roadOwner)
                                        <option value="{{ $roadOwner->owner_cd }}">
                                            {{ $roadOwner->owner_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <label for="edit_road_kml_file">
                                    Upload KML File for this Road:
                                </label>
                                <input type="file" class="text-xs text-success" id="edit_road_kml_file"
                                    name="edit_road_kml_file">
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm text-md mt-2" onclick="editDraftData()">
                            <i class="fa fa-save"></i>
                            Update
                        </button>
                        <button class="btn btn-sm modalClose btn-danger text-md mt-2" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i> CLOSE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/mis/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>


    <script type="text/javascript">
        // $(function() {
        //     $("#roadDetail")
        //         .DataTable({
        //             "paging": false,
        //             info: false,
        //             buttons: [
        //                 "csv",
        //                 "excel",
        //             ],
        //         })
        //         .buttons()
        //         .container()
        //         .appendTo(".mis-btn-rd");
        // });
        $(function () {
            $("#roadDetail")
                .DataTable({
                    "oLanguage": {
                        "sSearch": "Filter: "
                    },
                    buttons: [
                        "csv",
                        "excel",
                    ],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-rd");
        });
        $(function () {
            $("#roadDetailDraft")
                .DataTable({
                    "oLanguage": {
                        "sSearch": "Filter: "
                    },
                    buttons: [
                        // "copy",
                        "csv",
                        "excel",
                        // ,"pdf"
                    ],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-rd-draft");
        });

        $('.modalClose').click(function () {
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function () {
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
        $(document).ready(function () {
            // manipulate the edit form
            $('#roadEditModal').on('show.bs.modal ', function (event) {
                var button = $(event.relatedTarget); // get the button property
                let road_id = button.data('rd-id');
                let road_name = button.data('rd-name');
                let road_length = button.data('rd-length');
                let road_category = button.data('rd-catg');
                let road_type = button.data('rd-type');
                let road_owner = button.data('rd-owner');
                let rejection = button.data('rejection');

                $("#edit_road_id").val(road_id);
                $("#edit_road_name").val(road_name);
                $("#edit_road_length").val(road_length);
                $("#edit_road_category").val(road_category);
                $("#edit_road_type").val(road_type);
                $("#edit_road_owner").val(road_owner);

            });

            // send data for finalization
            $("#freezeBtn").on("click", function () {
                const status = confirm("Are you sure?");
                if (status) {
                    var selectedAsset = $(".selected-asset:checked")
                        .map(function () {
                            return $(this).data("road-id");
                        })
                        .get();

                    if (selectedAsset.length === 0) {
                        showDashboardModal("Select atleast one road to send for finalization!");
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "/asset-management/send-road-details-finalization",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                assetList: selectedAsset
                            },
                            cache: false,
                            success: function (response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                }
                                if (response.status === 503) {
                                    showDashboardModal(response.message);
                                }

                                if (response.status === 401) {
                                    showDashboardModal(response.message);
                                }

                                if (response.status === 500) {
                                    showDashboardModal(response.message);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                            },
                        });
                    }
                }
            });

            $('form.update-user-form1').on("submit", function (e) {
                e.preventDefault();
                let loc = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            //location.reload();
                            $('.editModal1').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form2').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal2').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form3').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal3').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form4').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal4').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form5').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal5').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form6').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal6').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form7').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal7').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form8').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal8').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form9').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal9').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form10').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal10').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form11').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal11').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form12').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal12').modal('hide');
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('.modifyReq').on("submit", function (e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success') {
                            alert("Request Sent Successfully");
                            location.reload();
                        } else if (response.message == 'notExist') {
                            alert("Update at least one column");
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('select[name="district_name"]').change(function () {
                const dataId = $(this).data('id');
                const district_cd = $(this).val();
                // console.log("data id: ", dataId);
                // console.log("Id: ", district_cd);
                $.ajax({
                    url: '/asset-management/block/' + district_cd,
                    type: 'GET',
                    cache: false,
                    success: function (response) {
                        if (response.status == 'success') {
                            const selectBlock = $(`#block_${dataId}`);
                            selectBlock.empty();
                            selectBlock.append('<option value="">Choose One</option>');
                            $.each(response.result, function (index, block) {
                                selectBlock.append('<option value="' + block
                                    .block_cd +
                                    '">' + block.block_name + '</option>');
                            });
                            $(`#block_${dataId}`).prop('disabled', false);
                        } else {
                            alert("failed to fetch the Block list!");
                        }
                    }
                });

            });
        });

        function editDraftData() {
            console.log('clicked');
            var formData = $("#editRoadForm").serialize();
            $.ajax({
                type: "POST",
                url: "/asset-management/edit-road",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: formData,
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === "failed") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === 500 || response.status === 409) {
                        console.log(response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        location.reload(true);
                    });
                },
            });
        }
    </script>
@endpush