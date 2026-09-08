@extends('layouts.app')
@section('content')

    <div class="card-header container-fluid mainBody my-2 text-sm">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 text-xs">PROJECT DETAILS</h6>
            </div>

            <div class="card-body">

                {{-- Basic Info --}}
                <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Project Code:</strong> {{ $project->project_cd }}</div>
                    <div class="col-md-4"><strong>Project Name:</strong> {{ $project->project_name }}</div>
                    <div class="col-md-4"><strong>Project Type:</strong> {{ $project->project_type }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4"><strong>Department:</strong> {{ $project->owner_department }}</div>
                    <div class="col-md-4"><strong>Division:</strong> {{ $project->division_name }}</div>
                    <div class="col-md-4"><strong>Sub Division:</strong> {{ $project->sub_div_name }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4"><strong>Status:</strong> {{ $project->project_status }}</div>
                    <div class="col-md-4"><strong>Contractor:</strong> {{ $project->contractor_name }}</div>
					<div class="col-md-4"><strong>Project Technology:</strong> {{ json_decode($project->others, true)['tech_type_descr'] ?? 'N/A' }}</div>
                </div>

                {{-- Dates --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Timeline</h6>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <strong>Start Date:</strong>
                        {{ \Carbon\Carbon::parse($project->project_start_date)->format('d M Y') }}
                    </div>
                    <div class="col-md-4">
                        <strong>End Date:</strong> {{ \Carbon\Carbon::parse($project->project_end_date)->format('d M Y') }}
                    </div>
                    <div class="col-md-4"><strong>DLP:</strong> {{ $project->defect_liability_period }} Months</div>
                </div>

                {{-- Financial --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Financial Details</h6>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Estimated Cost:</strong> ₹{{ $project->est_proj_cost }}</div>
                    <div class="col-md-4"><strong>Work Order Amount:</strong> ₹{{ $project->work_order_amount }}</div>
                    <div class="col-md-4"><strong>Work Order Number:</strong> {{ $project->work_order_no }}</div>
				</div>

                 <div class="row mb-2">
                    <div class="col-md-4"><strong>Work Order Issued:</strong> {{ $project->work_order_issue_date }}</div>
                    <div class="col-md-4"><strong>Scheme Name:</strong> {{ $project->scheme_name }}</div>
                </div>

                <div id="fundingAgencyContainer" class="text-xs mt-2">
                    <h6 class="border-bottom pb-2 mt-4 mb-3">Funding Agency Details</h6>
                    @if ($fundingAgencies->isEmpty())
                        <p class="text-muted">No funding agencies found.</p>
                    @else
                        @foreach ($fundingAgencies as $agency)
                            <div class="border col-12 mb-2 row pt-2">
                                <div class="col-6 col-md-4 mb-2">
                                    <strong>Agency Name:</strong> {{ $agency->agency_name ?? 'N/A' }}
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <strong>Funding Amount:</strong> {{ $agency->funding_amount ?? 0 }}
                                </div>
                                <div class="col-6 col-md-4 mb-2">
                                    <strong>Funding Percentage:</strong> {{ $agency->funding_percentage ?? 0 }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Others JSON --}}
                @php
                    $others = json_decode($project->others, true);
                @endphp

                {{-- New project for R&B --}}
                @if ($project->project_type_cd == 'NEW' && $project->owner_dept_cd == '14')
                    <h6 class="border-bottom pb-2 mt-4 mb-3">New Works Details</h6>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Road Name:</strong>
                            {{ $others['new_road_name'] ?? '-' }}</div>

                        <div class="col-md-4"><strong>Road Category:</strong>
                            {{ $roadCategoryName ?? '-' }}</div>

                        <div class="col-md-4"><strong>Road Type:</strong>
                            {{ $roadTypeName ?? '-' }}</div>

                        <div class="col-md-4"><strong>Road Length:</strong>
                            {{ $others['new_road_length'] ?? '-' }} km</div>

						<div class="col-md-4"><strong>Road Owner:</strong>
                            {{ $roadOwnerName ?? '-' }}</div>
					</div>

                    <div class="mt-3">
                        <button
                            class="viewInMapBtn btn btn-primary shadow-sm px-4"
                            data-id="{{ $project->project_cd }}"
                            data-road-name-id="{{ $others['new_road_name'] }}"
                            data-div-name-id="{{ $project->division_name }}">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            View Road on Map
                        </button>
                    </div>
                @endif

                @if ($project->project_type_cd == 'NEW' && $project->owner_dept_cd == '6')
                    @php
                        $new = $others ?? [];
                    @endphp

                    <h6 class="border-bottom pb-2 mt-4 mb-3">New Building Details</h6>

                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Building Latitude:</strong> {{ $new['new_building_lat'] ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Building Longitude:</strong> {{ $new['new_building_lng'] ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Building Location:</strong> {{ $new['new_building_location_name'] ?? 'N/A' }}
                                </div>
                            </div>

                            {{-- Basic Info --}}
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Category:</strong> {{ $new['new_building_class_name'] ?? 'N/A' }}
                                </div>
                                 <div class="col-md-4">
									<strong>Maintained by NPWD:</strong> {{ (($new['new_building_maintain_by_npwd'] ?? null) == 'Y') ? 'Yes' : 'No' }}
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
                {{-- Upgradation for R&B --}}
                @if ($project->project_type_cd == 'UPG' && $project->owner_dept_cd == '14')
                    @if (isset($others['upgradation']))
                        <h6 class="border-bottom pb-2 mt-4 mb-3">Upgradation Details</h6>

                        {{-- New Asset --}}
                        @if (!empty($others['upgradation']['new_asset']))
                            @php $new = $others['upgradation']['new_asset']; @endphp

                            <div class="card mb-3">
                                <div class="card-header fw-bold text-white bg-primary">New Asset</div>
                                <div class="card-body">

                                    <div class="row mb-2">
									@if(!empty($new['road_name']))
                                            <div class="col-md-4">
                                                <strong>Road Name:</strong> {{ $new['road_name'] }}
                                            </div>
                                        @endif
                                        <div class="col-md-4"><strong>Road Category: </strong>{{ $roadCategoryName ?? '-' }}</div>
                                        <div class="col-md-4"><strong>Road Type: </strong>{{ $roadTypeName ?? '-' }}</div>
										@if(!empty($new['road_length']))
                                            <div class="col-md-4">
                                                <strong>Road Length:</strong> {{ $new['road_length'] }} km
                                            </div>
                                        @endif
                                        <div class="col-md-4"><strong>Road Owner: </strong>{{ $roadOwnerName ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        {{-- Upgraded Roads --}}
                        @if (!empty($others['upgradation']['upgraded_roads']))
                            <div class="card mb-3">
                                <div class="card-header fw-bold text-white bg-primary">Upgraded Roads</div>
                                <div class="card-body">
                                    <ul class="mb-0">
                                        @foreach ($others['upgradation']['upgraded_roads'] as $road)
                                            <li>{{ $road }}</li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-3">
                                        <button
                                            class="viewInMapBtn btn btn-primary shadow-sm px-4"
                                            data-id="{{ $project->project_cd }}"
                                            data-div-name-id="{{ $project->division_name }}">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            View Road on Map
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif


                        {{-- Upgraded Asset Details --}}
                        @if (!empty($others['upgradation']['upgraded_asset_dtls']))
                            <div class="card mb-3">
                                <div class="card-header fw-bold text-white bg-primary">Upgraded Asset Details</div>
                                <div class="card-body">

                                    @foreach ($others['upgradation']['upgraded_asset_dtls'] as $index => $asset)
                                        <div class="border rounded p-3 mb-3">

                                            <h6 class="mb-2 text-rose-primary">
                                                Parent Asset: {{ $asset['parent_asset_id'] ?? 'N/A' }}
                                            </h6>
											@if(!empty($asset['start_chainage']) || !empty($asset['end_chainage']))
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <strong>Upgradation From Chainage (KM):</strong>
                                                        {{ $asset['start_chainage'] ?? 'N/A' }}

                                                        &nbsp;&nbsp;&nbsp;&nbsp;

                                                        <strong>Upgradation To Chainage (KM):</strong>
                                                        {{ $asset['end_chainage'] ?? 'N/A' }}
                                                    </div>
												</div>
											@endif

                                            {{-- Existing Assets Lists --}}
                                            <div class="row">
                                                @if (!empty($asset['bridges']))
                                                    <div class="col-md-3">
                                                        <strong>Bridges:</strong>
                                                        <ul>
                                                            @foreach ($asset['bridges'] as $b)
                                                                <li>{{ $b }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if (!empty($asset['culverts']))
                                                    <div class="col-md-3">
                                                        <strong>Culverts:</strong>
                                                        <ul>
                                                            @foreach ($asset['culverts'] as $c)
                                                                <li>{{ $c }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if (!empty($asset['retaining_walls']))
                                                    <div class="col-md-3">
                                                        <strong>Retaining Walls:</strong>
                                                        <ul>
                                                            @foreach ($asset['retaining_walls'] as $r)
                                                                <li>{{ $r }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
						@if (isset($others['upgradation']['road_sequence']) && count($others['upgradation']['road_sequence']) > 1)
                            <div class="card mb-3">
                                <div class="card-header fw-bold text-white bg-primary">Upgraded Roads Sequence</div>
                                <div class="card-body">
                                    @foreach (collect($others['upgradation']['road_sequence'])->sortBy('sequence') as $road)

                                        @php
                                            $displayRoadName = $road['road_id'];

                                            if (
                                                !empty($others['upgradation']['new_asset']['road_name']) &&
                                                !empty($others['upgradation']['new_asset']['road_id']) &&
                                                $others['upgradation']['new_asset']['road_id'] == $road['road_id']
                                            ) {
                                                $displayRoadName = $others['upgradation']['new_asset']['road_name'];
                                            }
                                        @endphp

                                        <div class="list-group-item d-flex align-items-center mb-1">
                                            <span class="badge text-white me-2"
                                                style="background-color: var(--primary);">
                                                {{ $road['sequence'] }}
                                            </span>

                                            <strong>{{ $displayRoadName }}</strong>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                {{-- Upgradation for Mechanical --}}
                @if (isset($others['project_type']) && $others['project_type'] === 'UPG' && $project->owner_dept_cd == '15')
                    <h6 class="border-bottom pb-2 mt-4 mb-3">Upgradation Details (Vehicles & Equipments)</h6>

                    <div class="accordion" id="assetAccordion">

                        {{-- Vehicles --}}
                        @if (!empty($vehicleDetails))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVehicles">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseVehicles">
                                        Vehicles ({{ count($vehicleDetails) }})
                                    </button>
                                </h2>

                                <div id="collapseVehicles" class="accordion-collapse collapse"
                                    data-bs-parent="#assetAccordion">
                                    <div class="accordion-body">

                                        @foreach ($vehicleDetails as $index => $vehicle)
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <strong>{{ $vehicle->vehicle_name }}</strong>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><strong>Registration No:</strong> {{ $vehicle->vehicle_regn_no }}</div>
                                                        <div class="col-md-6"><strong>Chassis No:</strong> {{ $vehicle->chassis_no }}</div>

                                                        <div class="col-md-6"><strong>Engine No:</strong> {{ $vehicle->engine_no }}</div>
                                                        <div class="col-md-6"><strong>Vehicle Type:</strong> {{ $vehicle->veh_type_descr }}</div>

                                                        <div class="col-md-6"><strong>Seating Capacity:</strong> {{ $vehicle->seating_capacity }}</div>
                                                        <div class="col-md-6"><strong>Wheel Count:</strong> {{ $vehicle->no_of_wheels }}</div>

                                                        <div class="col-md-6"><strong>Maker:</strong> {{ $vehicle->maker_name }}</div>
                                                        <div class="col-md-6"><strong>Model:</strong> {{ $vehicle->model }}</div>

                                                        <div class="col-md-6"><strong>Fuel Type:</strong> {{ $vehicle->fuel_type_descr }}</div>
                                                        <div class="col-md-6"><strong>Purchase Date:</strong> {{ $vehicle->date_of_purchase }}</div>

                                                        <div class="col-md-6"><strong>Purchase Cost:</strong> ₹{{ $vehicle->purchase_cost }}</div>
                                                        <div class="col-md-6"><strong>Condition:</strong> {{ $vehicle->condition_descr }}</div>

                                                        <div class="col-md-6"><strong>Laden Weight:</strong> {{ $vehicle->laden_weight }}</div>
                                                        <div class="col-md-6"><strong>Unladen Weight:</strong> {{ $vehicle->unladen_weight }}</div>

                                                        <div class="col-md-6"><strong>Alloted To:</strong> {{ $vehicle->alloted_to }}</div>
                                                        <div class="col-md-6"><strong>Alloted from:</strong> {{ $vehicle->alloted_from }}</div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Equipments --}}
                        @if (!empty($equipmentDetails))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEquipments">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseEquipments">
                                        Equipments ({{ count($equipmentDetails) }})
                                    </button>
                                </h2>

                                <div id="collapseEquipments" class="accordion-collapse collapse"
                                    data-bs-parent="#assetAccordion">
                                    <div class="accordion-body">

                                        @foreach ($equipmentDetails as $equipment)
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <strong>{{ $equipment->equipment_name }}</strong>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><strong>Serial No:</strong> {{ $equipment->serial_number }}</div>
                                                        <div class="col-md-6"><strong>Model No:</strong> {{ $equipment->model_no }}</div>

                                                        <div class="col-md-6"><strong>Purchase Year:</strong> {{ $equipment->purchase_year }}</div>
                                                        <div class="col-md-6"><strong>Purchase Cost:</strong> ₹{{ number_format($equipment->purchase_cost) }}</div>

                                                        <div class="col-md-6"><strong>Warranty:</strong> {{ $equipment->is_under_waranty }}</div>
                                                        <div class="col-md-6"><strong>Condition:</strong> {{ $equipment->condition_descr }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (empty($vehicleDetails) && empty($equipmentDetails))
                            <div class="alert alert-info mb-0">
                                No assets available.
                            </div>
                        @endif

                    </div>
                @endif
                {{-- Upgradation for Housing --}}
                @if (isset($others['project_type']) && $others['project_type'] === 'UPG' && $project->owner_dept_cd == '6')
                    @php
                        $category = $building->building_class_cd ?? null;
                    @endphp

                    <h6 class="border-bottom pb-2 mt-4 mb-3">Upgradation Details (Building)</h6>

                    <div class="card mb-3">
                        <div class="card-body">

                                <div class="row mb-2">
									<div class="col-md-4">
                                    <strong>Building Latitude:</strong>
                                    {{ $building->lat ?? 'N/A' }}
                                    </div>

                                <div class="col-md-4">
                                    <strong>Building Longitude:</strong>
                                    {{ $building->lon ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Building Location:</strong>
                                    {{ $building->building_location_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Building Type:</strong>
                                    {{ $building->building_type_name ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Owning Dept:</strong>
                                    {{ $building->asset_owning_dept_name ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Category:</strong>
                                    {{ $building->building_class_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Maintained by NPWD:</strong>
                                    {{ (($building->is_maintained_by_npwd ?? null) == 'Y') ? 'Yes' : 'No' }}
                                </div>

                                @if ($category == '1')
                                    <div class="col-md-4">
                                        <strong>Building Name:</strong>
                                        {{ $building->bld_qtr_name ?? 'N/A' }}
                                    </div>
                                @elseif($category == '0')
                                    <div class="col-md-4">
                                        <strong>Quarter No:</strong>
                                        {{ $building->qtr_no ?? 'N/A' }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif

                {{-- Maintenance for R&B --}}
                @if (isset($others['project_type']) && $others['project_type'] === 'MTN' && $project->owner_dept_cd == '14')
                    <h6 class="border-bottom pb-2 mt-4 mb-3">Maintenance Details</h6>
                    {{-- Asset Details --}}
                    @if (!empty($others['maintenance']['asset_dtls']))
                        @php $asset = $others['maintenance']['asset_dtls']; @endphp
                        <div class="card mb-3">
                            <div class="card-header fw-bold text-white bg-primary">Asset Details</div>
                            <div class="card-body">
                                <div class="mb-2">
                                    @php
                                        $subAssetName = '';
                                        foreach ($assetLists as $assetList) {
                                            if ($assetList['sub_asset_cd'] == $asset['asset_type_cd']) {
                                                $subAssetName = $assetList['sub_assets_descr'];
                                                break;
                                            }
                                        }
                                    @endphp
                                    <strong>Asset Type:</strong> {{ $subAssetName ?? 'N/A' }}
                                </div>
                                @if (!empty($asset['asset_list']))
                                    <strong>Assets:</strong>
                                    <ul class="mb-0">
                                        @foreach ($asset['asset_list'] as $a)
                                            <li>{{ $a }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Sub Asset Details --}}
                    @if (!empty($others['maintenance']['sub_asset_dtls']))
                        <div class="card mb-3">
                            <div class="card-header fw-bold text-white bg-primary">Sub Asset Details</div>
                            <div class="card-body">

                                @foreach ($others['maintenance']['sub_asset_dtls'] as $index => $sub)
                                    <div class="border rounded p-3 mb-3">
                                        <h6 class="text-rose-primary">
                                            @php
                                                $subAssetName = '';
                                                foreach ($assetLists as $assetList) {
                                                    if ($assetList['sub_asset_cd'] == $sub['sub_asset_type_cd']) {
                                                        $subAssetName = $assetList['sub_assets_descr'];
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            Sub Asset: {{ $subAssetName ?? 'N/A' }}
                                        </h6>
                                        @if (!empty($sub['sub_asset_list']))
                                            <ul class="mb-0">
                                                @foreach ($sub['sub_asset_list'] as $s)
                                                    <li>{{ $s }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Maintenance for mechanical --}}
                @if (isset($others['project_type']) && $others['project_type'] === 'MTN' && $project->owner_dept_cd == '15')
                    <h6 class="border-bottom pb-2 mt-4 mb-3">Maintenance Details (Vehicles & Equipments)</h6>
                    <div class="accordion" id="assetAccordion">
                        {{-- Vehicles --}}
                        @if (!empty($vehicleDetails))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingVehicles">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseVehicles">
                                        Vehicles ({{ count($vehicleDetails) }})
                                    </button>
                                </h2>

                                <div id="collapseVehicles" class="accordion-collapse collapse"
                                    data-bs-parent="#assetAccordion">
                                    <div class="accordion-body">

                                        @foreach ($vehicleDetails as $index => $vehicle)
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <strong>{{ $vehicle->vehicle_name }}</strong>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><strong>Registration No:</strong> {{ $vehicle->vehicle_regn_no }}</div>
                                                        <div class="col-md-6"><strong>Chassis No:</strong> {{ $vehicle->chassis_no }}</div>

                                                        <div class="col-md-6"><strong>Engine No:</strong> {{ $vehicle->engine_no }}</div>
                                                        <div class="col-md-6"><strong>Vehicle Type:</strong> {{ $vehicle->veh_type_descr }}</div>

                                                        <div class="col-md-6"><strong>Seating Capacity:</strong> {{ $vehicle->seating_capacity }}</div>
                                                        <div class="col-md-6"><strong>Wheel Count:</strong> {{ $vehicle->no_of_wheels }}</div>

                                                        <div class="col-md-6"><strong>Maker:</strong> {{ $vehicle->maker_name }}</div>
                                                        <div class="col-md-6"><strong>Model:</strong> {{ $vehicle->model }}</div>

                                                        <div class="col-md-6"><strong>Fuel Type:</strong> {{ $vehicle->fuel_type_descr }}</div>
                                                        <div class="col-md-6"><strong>Purchase Date:</strong> {{ $vehicle->date_of_purchase }}</div>

                                                        <div class="col-md-6"><strong>Purchase Cost:</strong> ₹{{ $vehicle->purchase_cost }}</div>
                                                        <div class="col-md-6"><strong>Condition:</strong> {{ $vehicle->condition_descr }}</div>

                                                        <div class="col-md-6"><strong>Laden Weight:</strong> {{ $vehicle->laden_weight }}</div>
                                                        <div class="col-md-6"><strong>Unladen Weight:</strong> {{ $vehicle->unladen_weight }}</div>

                                                        <div class="col-md-6"><strong>Alloted To:</strong> {{ $vehicle->alloted_to }}</div>
                                                        <div class="col-md-6"><strong>Alloted from:</strong> {{ $vehicle->alloted_from }}</div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Equipments --}}
                        @if (!empty($equipmentDetails))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEquipments">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseEquipments">
                                        Equipments ({{ count($equipmentDetails) }})
                                    </button>
                                </h2>

                                <div id="collapseEquipments" class="accordion-collapse collapse"
                                    data-bs-parent="#assetAccordion">
                                    <div class="accordion-body">

                                        @foreach ($equipmentDetails as $equipment)
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <strong>{{ $equipment->equipment_name }}</strong>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6"><strong>Serial No:</strong> {{ $equipment->serial_number }}</div>
                                                        <div class="col-md-6"><strong>Model No:</strong> {{ $equipment->model_no }}</div>

                                                        <div class="col-md-6"><strong>Purchase Year:</strong> {{ $equipment->purchase_year }}</div>
                                                        <div class="col-md-6"><strong>Purchase Cost:</strong> ₹{{ number_format($equipment->purchase_cost) }}</div>

                                                        <div class="col-md-6"><strong>Warranty:</strong> {{ $equipment->is_under_waranty }}</div>
                                                        <div class="col-md-6"><strong>Condition:</strong> {{ $equipment->condition_descr }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (empty($vehicleDetails) && empty($equipmentDetails))
                            <div class="alert alert-info mb-0">
                                No assets available.
                            </div>
                        @endif

                    </div>
                @endif

                {{-- Maintenance for Housing --}}
                @if (isset($others['project_type']) && $others['project_type'] === 'MTN' && $project->owner_dept_cd == '6')
                    <h6 class="border-bottom pb-2 mt-4 mb-3">Maintenance Details (Building)</h6>
                    <div class="card mb-3">
                        <div class="card-body">
							<div class="row mb-2">
                               <div class="col-md-4">
								<strong>Building Latitude:</strong>
                                    {{ $building->lat ?? 'N/A' }}
                                    </div>

                                <div class="col-md-4">
                                    <strong>Building Longitude:</strong>
                                    {{ $building->lon ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Building Location:</strong>
                                    {{ $building->building_location_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Building Type:</strong>
                                    {{ $building->building_type_name ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Owning Dept:</strong>
                                    {{ $building->asset_owning_dept_name ?? 'N/A' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Category:</strong>
                                    {{ $building->building_class_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Maintained by NPWD:</strong>
                                    {{ (($building->is_maintained_by_npwd ?? null) == 'Y') ? 'Yes' : 'No' }}
                                </div>

                                @if ($building->building_class_cd == '1')
                                    <div class="col-md-4">
                                        <strong>Building Name:</strong>
                                        {{ $building->bld_qtr_name ?? 'N/A' }}
                                    </div>
                                @elseif($building->building_class_cd == '0')
                                    <div class="col-md-4">
                                        <strong>Quarter No:</strong>
                                        {{ $building->qtr_no ?? 'N/A' }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif

                {{-- Work Items --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Work Items</h6>
                @if ($items->isEmpty())
                    <p class="text-muted">No work items found.</p>
                @else
                  @foreach ($items as $index => $item)
                        <div class="card mb-3">
                            <div class="card-header text-white">
                                Item {{ $index + 1 }} : {{ $item->name }}
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <strong>Unit:</strong> {{ $item->unit }}
                                    </div>

                                    <div class="col-md-3">
                                        <strong>Quantity:</strong> {{ $item->qty }}
                                    </div>
                                </div>
                                {{-- Sub Items --}}
                                @if ($item->sub_items->count())
                                    <div class="mb-3">
                                        <strong>Sub Items:</strong>
                                        @foreach ($item->sub_items as $subItem)
                                            <span class="badge bg-secondary me-1">
                                                {{ $subItem }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                {{-- Work Plans --}}
                                @if ($item->work_plans->count())
                                    <h6 class="mt-3">Work Plans</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
									<th>Predecessor</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($item->work_plans as $plan)
                                    <tr>
										<td>{{ $plan->plan_start_date }}</td>
										<td>{{ $plan->plan_end_date }}</td>
										<td>{{ $plan->precedence_item_name ?? 'No predecessor' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
					</div>
				</div>
				@endforeach

                @endif

				{{-- by dipshikha --}}
                {{-- Images --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Project Images</h6>

                @if($images->isEmpty())
                    <p class="text-muted">No images uploaded.</p>
                @else
                    <div class="row">
                        @foreach($images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <a href="{{ $image['image_url'] }}" target="_blank">
                                        <img src="{{ $image['image_url'] }}"
                                            class="card-img-top img-fluid"
                                            style="height:200px; object-fit:cover;"
                                            alt="Project Image">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Documents --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Documents</h6>

                @if($documents->isEmpty())
                    <p class="text-muted">No documents uploaded.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="60%">Document Type</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $index => $document)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $document['label'] }}</td>
                                        <td>
                                            <a href="{{ $document['file_url'] }}"
                                            target="_blank"
                                            class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>

                                            <a href="{{ $document['file_url'] }}"
                                            download
                                            class="btn btn-sm btn-success">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                {{-- end --}}

				@php
                    $disableButtons = (($project->project_type_cd == 'NEW' && $project->owner_dept_cd == '14') || ($project->project_type_cd == 'UPG' && $project->owner_dept_cd == '14' && !empty($others['upgradation']['upgraded_roads'])));
                @endphp



                {{-- Action Buttons --}}
                <h6 class="border-bottom pb-2 mt-4 mb-3">Actions</h6>
                <div class="d-flex gap-1 justify-content-end">
                    <div style="margin-bottom: 0.1rem;">
                        <button class="approveBtn btn btn-outline-primary btn-md text-xs fw-bold rounded-0"
                            id="btnApprove_{{ $project->project_cd }}" data-id="{{ $project->project_cd }}"
                            {{ $disableButtons ? 'disabled' : '' }}>
                            <i class="fa-solid fa-circle-check"></i>
                            Approve Project
                        </button>
                    </div>
                    <div>
                        <button class="rejectBtn btn btn-outline-danger btn-md text-xs fw-bold rounded-0"
                            id="btnReject_{{ $project->project_cd }}" data-id="{{ $project->project_cd }}"
                            {{ $disableButtons ? 'disabled' : '' }}>
                            <i class="fa-solid fa-circle-xmark"></i>
                            Reject Project
                        </button>
                    </div>
                </div>
            </div>
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

     <div class="modal fade" id="mapModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title" id="mapTitle">Nagaland PWD Road Map, </label>
                    &nbsp;&nbsp;&nbsp;
                    <label id="lblRoadId" name="lblRoadId">

                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label id="lblRoadName" name="lblRoadName">

                    </label>

                    &nbsp;&nbsp;&nbsp;
                    <label id="lblDivName" name="lblDivName">

                    </label>
                    <button type="button" class="btn-close" id="btnClose" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="row justify-content-center align-item-center">
                    <div class="col-md-10 mb-1">
                        <div class="modal-body modal-dialog-centered" id='map' style='width: 100%; height: 500px;'>
                            <form action="#" id="frm_map_modal">
                                @csrf

                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id='mapFooter'>

                    <div class="col-md-7">
                        <div class="form-check form-switch">

                            <input class="form-check-input form-control" type="checkbox" name="mapShowHideSwitch"
                                id="mapShowHideSwitch" role="switch" checked />
                            <label for="mapShowHideSwitch" id="lblShowHideMap">Show/Hide Current
                                Road</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label id="lblMapOK">Is the Road Correct:</label>
                        <input type="hidden" id="hdnRoadId" name="hdnRoadId" value="" />
                        <button class="mapCorrectBtn btn btn-outline-primary btn-xs text-xs" style="width: 4rem;">
                            Yes
                        </button>
                        <button class="mapNotCorrectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    :root {
        --primary: var(--oamis-primary, #0b6b4a);
        --secondary: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
        --accent: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
    }

    .text-rose-primary {
        color: var(--primary) !important;
    }

    .card-header {
        background: #0b6b4a !important;
    }

</style>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/finalize/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script>
        const finalizeUrl = "{{ route('finalize.project') }}";
    </script>
    <script>
	var approved_geojson_data = null;
        var draft_road_geojson_data = null;
        var data_layer = null;
        var data_layer_2 = null;
        var all_states_geojson_data = null;
        var sh_nh_mdr_geojson_data = null;
        let map;
        $(function () {

            (g => {
                var h, a, k, p = "The Google Maps JavaScript API",
                    c = "google",
                    l = "importLibrary",
                    q = "__ib__",
                    m = document,
                    b = window;
                b = b[c] || (b[c] = {});
                var d = b.maps || (b.maps = {}),
                    r = new Set,
                    e = new URLSearchParams,
                    u = () => h || (h = new Promise(async (f, n) => {
                        await (a = m.createElement("script"));
                        e.set("libraries", [...r] + "");
                        for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[
                            k]);
                        e.set("callback", c + ".maps." + q);
                        a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                        d[q] = f;
                        a.onerror = () => h = n(Error(p + " could not load."));
                        a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                        m.head.append(a)
                    }));
                d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u()
                    .then(
                        () => d[l](f, ...n))
            })({
                key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
                v: "beta",
                // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
                // Add other bootstrap parameters as needed, using camel case.
            });

            fetch('/getAllStatesRoadsGeoJsonData')
                .then(response => {
                    console.log("HTTP Response Status:", response.status); // Debug status

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    if (data && data.status) {
                        let compressedArray = Uint8Array.from(atob(data.all_states_geojson_data), c => c
                            .charCodeAt(0));

                        // Decompress using pako
                        let decompressedData = pako.inflate(compressedArray, {
                            to: 'string'
                        });
                        // all_states_geojson_data = JSON.parse(decompressedData);
                        all_states_geojson_data = decompressedData;
                    } else {
                        console.error("Invalid response format");
                    }
                })
                .catch(error => console.error("Fetch error:", error));


            fetch('/getAllSHNHMDRRoadsGeoJsonData')
                .then(response => {
                    console.log("HTTP Response Status:", response.status); // Debug status

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    if (data && data.status) {
                        let compressedArray = Uint8Array.from(atob(data.all_sh_nh_mdr_geojson_data), c => c
                            .charCodeAt(0));

                        // Decompress using pako
                        let decompressedData = pako.inflate(compressedArray, {
                            to: 'string'
                        });
                        // sh_nh_mdr_geojson_data = JSON.parse(decompressedData);
                        sh_nh_mdr_geojson_data = decompressedData;
                    } else {
                        console.error("Invalid response format");
                    }
                })
                .catch(error => console.error("Fetch error:", error));
        });


        $("#mapShowHideSwitch").change(function () {

            var ischecked = $(this).is(':checked');
            if (!ischecked)
                data_layer_2.setStyle({
                    visible: false
                });
            else {
                data_layer_2.setStyle({
                    visible: true
                });
                data_layer_2.setStyle(function (feature) {
                    var roadCatg = feature.getProperty('road_category');
                    var strokeColor;
                    var fillColor;
                    var strokeWeight;
                    var strokeOpacity;
                    switch (roadCatg) {
                        case 'NH':
                            strokeColor = '#ffff00';
                            fillColor = '#ffff00';
                            strokeWeight = 2.0;
                            break;
                        case 'SH':
                        case 'State Highway':
                            strokeColor = '#005500';
                            fillColor = '#005500';
                            strokeWeight = 1.7;
                            break;
                        case 'MDR':
                        case 'Major District Roads':
                            strokeColor = '#000000';
                            fillColor = '#000000';
                            strokeWeight = 1.7;
                            break;
                        case 'ODR':
                            strokeColor = '#00007f';
                            fillColor = '#00007f';
                            strokeWeight = 1.0;
                            break;
                        case 'VR':
                            strokeColor = '#ff5500';
                            fillColor = '#ff5500';
                            strokeWeight = 1.0;
                            break;
                        case 'ALR':
                            strokeColor = '#55ff7f';
                            fillColor = '#55ff7f';
                            strokeWeight = 1.0;
                            break;
                        case 'UR':
                            strokeColor = '#aa00ff';
                            fillColor = '#aa00ff';
                            strokeWeight = 1.0;
                            break;
                        case 'RD':
                            strokeColor = '#ffaa7f';
                            fillColor = '#ffaa7f';
                            strokeWeight = 1.0;
                            break;
                        case 'INTER':
                            strokeColor = '#FA0017';
                            fillColor = '#FA0017';
                            strokeWeight = 1.0;
                            break;


                        default:
                            strokeColor = "green";
                            fillColor = "green";
                            strokeWeight = 2.0;

                    }

                    return {
                        strokeColor: strokeColor,
                        fillColor: fillColor,
                        strokeWeight: 4,
                        strokeOpacity: 1.0,
                        fillOpacity: 0.3
                    };
                });
            }
        });

        // approve a single housing data
        $('.approveBtn').on('click', function() {
            const projectId = $(this).data('id');
            console.log(projectId);
            if (projectId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this project?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                if (result.isConfirmed) {
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
                            if (response.status === 200) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = finalizeUrl;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Notice',
                                    text: response.message,
                                    icon: 'info',
                                    confirmButtonText: 'Okay'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('An error occurred while approving the project');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'Project approval has been cancelled.',
                        icon: 'info',
                        confirmButtonText: 'Okay'
                    });
                }
            });
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
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = finalizeUrl;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Notice',
                            text: response.message,
                            icon: 'info',
                            confirmButtonText: 'Okay'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('An error occurred while rejecting the project');
                }
			});
		});

        $('.viewInMapBtn').on('click', function () {
            const projectID = $(this).data('id');
            const divName = $(this).data('div-name-id');
            if (projectID) {

                $.ajax({
                    type: 'GET',
                    url: "/project-management/getDraftedRoadsKml/" + projectID,
                    contentType: "application/json; charset=utf-8",
                    crossDomain: true,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    success: function (response, status, jqXHR) {
                        if (response.status === true) {
                            approved_geojson_data = response.approved_geojson_data;
                            draft_road_geojson_data = response.draft_road_geojson_data;
                            div_lat = response.div_lat;
                            div_lng = response.div_lng;

                            if (approved_geojson_data == null) {
                                approved_geojson_data = all_states_geojson_data;
                                console.log(
                                    "Division Data could not fetched, Hence Showing All States Geo Json data"
                                );
                            }
                            $('#mapModal').on('shown.bs.modal', function (e) {

                                $('#hdnRoadId').prop("value", projectID);
                                $('#lblRoadId').text("  Road Id : " + projectID);
                                $('#lblDivName').text("  Division Name : " + divName);
                                initMap(JSON.parse(approved_geojson_data),
                                    JSON.parse(draft_road_geojson_data), JSON.parse(
                                        sh_nh_mdr_geojson_data), div_lat, div_lng);
                            }).modal('show');

                        }
                    },
                    error: function (error) {
                        console.log(error);
                        console.log(
                            "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
                        );
                    }
                });
            }
        });

        const styleNH = {
            strokeColor: "#ffff00",
            strokeWeight: 2.0,
            strokeOpacity: 1.0,
            fillColor: "#ffff00",
            fillOpacity: 0.3,
        };
        const styleSH = {
            strokeColor: "#005500",
            strokeWeight: 1.7,
            strokeOpacity: 1.0,
            fillColor: "#005500",
            fillOpacity: 0.3,
        };
        const styleMDR = {
            strokeColor: "#000000",
            strokeWeight: 1.7,
            strokeOpacity: 1.0,
            fillColor: "#000000",
            fillOpacity: 0.3,
        };
        const styleODR = {
            strokeColor: "#00007f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#00007f",
            fillOpacity: 0.3,
        };
        const styleVR = {
            strokeColor: "#ff5500",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#ff5500",
            fillOpacity: 0.3,
        };
        const styleALR = {
            strokeColor: "#55ff7f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#55ff7f",
            fillOpacity: 0.3,
        };
        const styleUR = {
            strokeColor: "#aa00ff",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#aa00ff",
            fillOpacity: 0.3,
        };
        const styleRD = {
            strokeColor: "#ffaa7f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#ffaa7f",
            fillOpacity: 0.3,
        };
        const styleINTER = {
            strokeColor: "#FA0017",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#FA0017",
            fillOpacity: 0.3,
        };

        async function initMap(approved_geojson_data, draft_road_geojson_data, sh_nh_mdr_geojson_data, lt, ln) {
            const {
                Map
            } = await google.maps.importLibrary("maps");


            let startPosition = {
                lat: 26.094757374299146,
                lng: 94.58979407214116,
            };

            if (lt != null && ln != null)
                startPosition = {
                    lat: parseFloat(lt),
                    lng: parseFloat(ln),
                };

            if (map) {
                map = null;
            }
            map = new Map(document.getElementById('map'), {
                center: startPosition,
                zoom: 9
            });

            map.setZoom(Math.max(map.getZoom(), 10));
            try {

                data_layer = new google.maps.Data({
                    map: map
                });
                data_layer_2 = new google.maps.Data({
                    map: map
                });
                data_layer_3 = new google.maps.Data({
                    map: map
                });
                data_layer.addGeoJson(approved_geojson_data);
                data_layer_2.addGeoJson(draft_road_geojson_data);
                data_layer_3.addGeoJson(sh_nh_mdr_geojson_data);
            } catch (e) {
                console.log(e);
            }

            data_layer.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {

                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: strokeWeight,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });


            data_layer_2.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {
                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: 4,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });

            data_layer_3.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {

                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: strokeWeight,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });
        }

        $('.mapCorrectBtn').on('click', function () {

            var rdid = $('#hdnRoadId').val();

            $('#btnApprove_' + rdid).prop("disabled", false);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('.mapNotCorrectBtn').on('click', function () {
            var rdid = $('#hdnRoadId').val();
            $('#btnApprove_' + rdid).prop("disabled", true);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('#btnClose').on('click', function () {
            var rdid = $('#hdnRoadId').val();
            $('#btnApprove_' + rdid).prop("disabled", true);
            $('#btnReject_' + rdid).prop("disabled", true);
        });

        $("#mapModal").on("fade.bs.modal", function () {
            alert("fading");
        });

        $(function () {
            $(".enableMouseEvent:disabled").wrap(function () {
                // $(this).css('cursor', 'pointer').attr('title', 'This is a hover textxxxxxxxxxxxxx.');
                return '<div onmouseover="' + $(this).attr('onmouseover') + '" />';

            });
        });

        function showToolTip(el) {
            val = $(el).next(".counter").html(function (i, val) {
                return val + 1

            });
            $(this).css('cursor', 'pointer').attr('title', 'This is a hover.');
        }
	</script>
@endpush
