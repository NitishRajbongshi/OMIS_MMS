@extends('layouts.app')
@section('content')
    <div class="content-header mb-1">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">Verified Project List</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-3 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF VERIFIED NEW PROJECT DETAILS UNDER GOVERNMENT OF NAGALAND
            </span>
        </h6>
        <table class="table-responsive text-xs table table-bordered table-striped user_list" id="project_details_table">
            <thead class="theader text-white" style="background-color:#417DBE">
                <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                <th class="text-center" style="min-width: 6rem;">Project Code</th>
                <th class="text-center" style="min-width: 10rem;">Project Name</th>
                <th class="text-center" style="min-width: 5rem;">Project Type</th>
                <th class="text-center" style="min-width: 4rem;">Owner Department</th>
                <th class="text-center" style="min-width: 6rem;">Division</th>
                <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                <th class="text-center" style="min-width: 8rem;">Project Start Date</th>
                <th class="text-center" style="min-width: 8rem;">Project End Date</th>
                <th class="text-center" style="min-width: 8rem;">Project Status</th>
                <th class="text-center" style="min-width: 8rem;">Project Awarded To</th>
                <th class="text-center" style="min-width: 8rem;">Estimated Project Cost</th>
                <th class="text-center" style="min-width: 8rem;">Defect Liability Period (in Month)</th>
                <th class="text-center" style="min-width: 8rem;">Work Order Amount(Rs.)</th>
                <th class="text-center" style="min-width: 8rem;">Work Plan Details</th>
            </thead>
            <tbody>
                @foreach ($projectDetails as $index => $project)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $project->project_cd }}</td>
                        <td class="text-center">{{ $project->project_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->project_type ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->owner_department ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->division_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->sub_div_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->project_start_date ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->project_end_date ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->project_status ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->contractor_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->est_proj_cost ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->defect_liability_period ?? 'N/A' }}</td>
                        <td class="text-center">{{ $project->work_order_amount ?? 'N/A' }}</td>
                        <td class="text-center">
                            @if (in_array($project->project_cd, $workItems_exist))
                                <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                    data-target="#ItemsModal{{ $project->project_cd }}"
                                    onclick="showItemsDetail('{{ $project->project_cd }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <span class="text-danger text-bold">NA</span>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Items Modal -->
    <div id="ItemsModal" class="ItemsModal">
        <!-- Modal content -->
        <div class="ItemsModalContent">
            <span class="closeItemsModal">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                List of Sub Asset Details
            </p>
            <div class="row text-xs" id="modalItemsContainer">
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pms/common/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        :root {
            --primary: #007bff;
            --secondary: #9fc9f6ff;
            --accent: #9fc9f6ff;
        }

        .table {
            border-radius: 12px;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background: linear-gradient(135deg, var(--accent) 0%, #9fc9f6ff 100%);
        }

        .table thead th {
            color: var(--primary);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.6rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.2s;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function showItemsDetail(id) {
            const showItemsModal = document.getElementById("ItemsModal");
            const modalItemsContainer = $("#modalItemsContainer");

            modalItemsContainer.empty();

            $.ajax({
                type: "GET",
                url: "/project-management/get-items-detail/" + encodeURIComponent(id),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                cache: false,
                success: function(response) {
                    if (response.status === "success") {
                        if (response.value.length === 0) {
                            modalItemsContainer.append(
                                `<p class="text-danger small">No Items Found</p>`,
                            );
                            return;
                        }

                        // TABLE
                        let table = `
                                <table class="table table-bordered table-striped table-sm mt-2">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Item of Work</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>BOQ Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        `;

                        $.each(response.value, function(index, item) {
                            table += `
                                    <tr id="item-row-${index}">
                                        <td>${index + 1}</td>
                                        <td>${item.name}</td>
                                        <td>${item.qty}</td>
                                        <td>${item.rate}</td>
                                        <td>${item.amount}</td>
                                        <td>${item.item_cd}</td>
                                    </tr>
                                    `;
                        });

                        table += `
                                </tbody>
                            </table>`;
                        modalItemsContainer.append(table);

                        if (showItemsModal) showItemsModal.style.display = "block";
                    }
                },
                error: function() {
                    console.error("AJAX Error");
                },
            });

            // Close modal
            const span = document.querySelector(".closeItemsModal");
            if (span) {
                span.onclick = function() {
                    if (showItemsModal) showItemsModal.style.display = "none";
                };
            }

            window.onclick = function(event) {
                if (showItemsModal && event.target === showItemsModal) {
                    showItemsModal.style.display = "none";
                }
            };
        }
    </script>
@endpush
