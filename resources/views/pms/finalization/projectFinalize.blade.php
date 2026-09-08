@extends('layouts.app')
@section('content')
    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-2 border border-info text-light bg-info">
            <span class="border border-info text-light bg-info text-xs text-uppercase">
                LIST OF PROJECT DETAILS READY FOR APPROVAL AND FINALIZATION UNDER GOVT. OF NAGALAND
            </span>
        </h6>
        <div class="container-fluid border">
            <div class=" text-xs">
                <table class="table w-100 table-bordered table-striped rounded-0" id="new_project_details_table">
                    <thead class="theader text-xs text-white" style="background-color:#417DBE">
                        <th class="text-center">SL. No.</th>
                        <th class="text-center">Project Code</th>
						<th class="text-center">Project Type</th>										 
                        <th class="text-center">Project Name</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Sub Division</th>
                        <th class="text-center">More Details</th>
                    </thead>
                    <tbody>
                        @foreach ($projects as $index => $draft)
                            @php
                                $other = json_decode($draft->others ?? '{}', true);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $draft->project_cd }}</td>
								<td class="text-center">{{ $draft->project_type }}</td>													   
                                <td class="text-center">{{ $draft->project_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $draft->division_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $draft->sub_div_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('view.project') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="project_cd" value="{{ $draft->project_cd }}">
                                        <button type='submit'
                                            class="text-xs outline-0 btn btn-xs btn-outline-primary inline fw-bold rounded-0">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Reject Reason Modal --}}
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Reason for Rejection</h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="rejectProjectCode" value="">
                        <textarea id="rejectReason" class="form-control" rows="4" placeholder="Enter reason..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submitRejectReason" class="btn btn-danger btn-sm">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-success-modal />
        <x-warning-modal />

        {{-- Work Items Modal --}}
        <div id="ItemsModal" class="ItemsModal">
            <div class="ItemsModalContent">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        List of Items of Work under the selected project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs m-1 p-0"
                    id="modalItemsContainer">
                </div>
            </div>
        </div>

        {{-- Modal to show asset items for new works --}}
        <div id="showAssetForNewWorks" class="showAssetForNewWorks">
            <!-- Modal content -->
            <div class="ItemsModalContent modal-md">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        New Assets Details for the New Works
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs p-1"
                    id="dataSectionForNewWorks">
                </div>
            </div>
        </div>

        {{-- Modal to show Upgradation Assets --}}
        <div id="showModalNewAsset" class="showModalNewAsset">
            <div class="showModalNewAssetContent">
                <span class="closeShowModalNewAsset">&times;</span>
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Existing & New Asset Details
                </p>

                <div id="modalValContainerNewAsset" class="row text-xs"></div>
            </div>
        </div>

{{-- by dipshikha --}}
        <div id="showModalNewAsset" class="showModalNewAsset">
            <div class="showModalNewAssetContent">
                <span class="closeShowModalNewAsset">&times;</span>
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Asset Details
                </p>

                <div id="modalValContainerNewAsset" class="row text-xs"></div>
            </div>
        </div>


        <div id="showModalMaintenance" class="showModalMaintenance">
            <div class="showModalContentMaintenanace">
                <span class="closeShowModalMaintenance">&times;</span>
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Asset Details
                </p>
                <div class="row text-xs" id="modalValContainerMaintenance">
                </div>
            </div>
        </div>
        {{-- end --}}

        {{-- Modal to show maintenance assets --}}
        <div id="showModalMaintenance" class="showModalMaintenance">
            <div class="showModalContentMaintenanace">
                <span class="closeShowModalMaintenance">&times;</span>
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Asset Details
                </p>
                <div class="row text-xs" id="modalValContainerMaintenance">
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pms/common/modal/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    @endpush

    @push('scripts')

        <script src="{{ asset('js/modal/script.js') }}" defer></script>
        <script src="{{ asset('js/pms/finalization/scirpt.js') }}" defer></script>

        <script>
            $(function() {
                $("#new_project_details_table, #upg_project_details_table, #mtn_project_details_table").DataTable();
            });

            // approve a single housing data
            $('.approveBtn').on('click', function() {
                const projectId = $(this).data('id');
                console.log(projectId);
                if (projectId) {
                    const final = confirm("Click OK to Approve");
                    if (final) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('freeze.project.details') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                project_id: projectId
                            },
                            cache: false,
                            success: function(response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                } else {
                                    showDashboardModal(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                alert('An error occurred while approving the project');
                            }
                        })
                    } else {
                        alert('Cancel to finalize');
                    }
                }
            });

            $(document).on('click', '.rejectBtn', function() {
                let projectCode = $(this).data('id');
                $('#rejectProjectCode').val(projectCode);
                $('#rejectReason').val("");
                $('#rejectReasonModal').modal('show');
            });


            $(document).on('click', '#submitRejectReason', function() {
                let projectCode = $('#rejectProjectCode').val();
                let reason = $('#rejectReason').val().trim();
                if (reason === "") {
                    showDashboardModal("Reason Of Rejection Not Entered!");
                    return;
                }
                $.ajax({
                    url: "{{ route('reject.project.details') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        project_cd: projectCode,
                        reason: reason,
                    },
                    success: function(response) {
                        $('#rejectReasonModal').modal('hide');
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        } else {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('An error occurred while rejecting the project');
                    }
                });
            });
        </script>
    @endpush
