@extends('layouts.app')
@section('content')
    <div class="content-header asset-plan-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item">ASSET PLAN</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="container-fluid mainBody asset-plan-main">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF ASSETS TO BE CREATED FROM COMPLETED PROJECTS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2 asset-plan-table-card">
                <table class="table-responsive text-xs table table-bordered table-striped assetSummaryTable"
                    id="project_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" colspan="7">Project Type: New</th>
                    </thead>
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 2rem;">Project Code</th>
                        <th class="text-center" style="min-width: 2rem;">Project Name</th>
                        <th class="text-center" style="min-width: 2rem;">Division </th>
                        <th class="text-center" style="min-width: 2rem;">Sub Division </th>
                        <th class="text-center" style="min-width: 2rem;">Start Date </th>
                        <th class="text-center" style="min-width: 2rem;">End Date </th>
                        <th class="text-center" style="min-width: 2rem;">Action </th>
                    </thead>
                    <tbody>
                        @foreach($projects as $project_cd => $projData)

                            <tr style="background:#f2f2f2;">
                                <td><b>{{ $project_cd }}</b></td>
                                <td>{{ $projData['info']['project_name'] }}</td>
                                <td>{{ $projData['info']['division'] }}</td>
                                <td>{{ $projData['info']['sub_division'] }}</td>
                                <td>
                                    {{ !empty($projData['info']['start_date']) ? \Carbon\Carbon::parse($projData['info']['start_date'])->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    {{ !empty($projData['info']['end_date']) ? \Carbon\Carbon::parse($projData['info']['end_date'])->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    <button onclick="toggleRow(this, 'proj-{{ $project_cd }}')"
                                        class="btn btn-sm btn-primary float-right">
                                        <span>+</span>
                                    </button>
                                </td>
                            </tr>
                            <tr id="proj-{{ $project_cd }}" style="display:none;">
                                <td colspan="7">

                                    <table class="table table-sm table-bordered">
                                        <thead class="theader text-white" style="background-color:#417DBE">
                                            <th class="text-center">Asset Type</th>
                                            <th class="text-center">Asset Name</th>
                                            <th class="text-center">Asset CD</th>
                                            <th class="text-center">Parent Asset CD</th>
                                            <th class="text-center">Count</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach($projData['summary'] as $typeKey => $data)
                                                <tr>
                                                    <td>{{ $data['name'] }}</td>
                                                    <td>{{ $data['asset_name'] ?? '-' }}</td>
                                                    <td>{{ $data['asset_cd'] ?? '-' }}</td>
                                                    <td>{{ $data['parent_asset_cd'] ?? '-' }}</td>
                                                    <td><b>{{ $data['count'] }}</b></td>
                                                    <td>
                                                        <button onclick='createAsset({
                                                        asset_plan_id: "{{$data["asset_plan_id"] }}",
                                                        dept_cd: "{{$projData["info"]["dept_cd"] }}",
                                                        project_cd: "{{ $project_cd }}",
                                                        division_cd : "{{ $projData["info"]["division_cd"] }}",
                                                        sub_division_cd : "{{ $projData["info"]["sub_division_cd"] }}",
                                                        typeKey: "{{ $typeKey }}",
                                                        asset_cd: "{{ $data["asset_cd"] }}",
                                                        parent_cd: "{{ $data["parent_asset_cd"] ?? "-" }}",
                                                        asset_name : "{{ $data["asset_name"] ?? null }}",
                                                        new_asset_length : "{{ $data["new_asset_length"] ?? null }}",
                                                        createGroup: "NEW"
                                                        })'
                                                            class="btn btn-success btn-sm" {{ !$data["is_asset_exist_in_main_table"] ? 'disabled' : '' }}>
                                                            Create 
                                                        </button>
                                                        @if(!$data['is_asset_exist_in_main_table'])
                                                            <span class="text-danger ms-2">
                                                                Parent Asset {{ $data["parent_asset_cd"] ?? "-" }} is Not Yet Created!!
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="container-fluid border py-2 asset-plan-table-card">
                <table class="table-responsive text-xs table table-bordered table-striped assetSummaryTable"
                    id="project_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" colspan="7">Project Type : Upgrade</th>
                    </thead>
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 3rem;">Project Code</th>
                        <th class="text-center" style="min-width: 3rem;">Project Name</th>
                        <th class="text-center" style="min-width: 3rem;">Division </th>
                        <th class="text-center" style="min-width: 3rem;">Sub Division </th>
                        <th class="text-center" style="min-width: 3rem;">Start Date </th>
                        <th class="text-center" style="min-width: 3rem;">End Date </th>
                        <th class="text-center" style="min-width: 3rem;">Action </th>
                    </thead>
                    <tbody>
                        @foreach($projectsUpgrade as $project_cd => $projData)

                            <tr style="background:#f2f2f2;">
                                <td><b>{{ $project_cd }}</b></td>
                                <td>{{ $projData['info']['project_name'] }}</td>
                                <td>{{ $projData['info']['division'] }}</td>
                                <td>{{ $projData['info']['sub_division'] }}</td>
                                <td>{{ !empty($projData['info']['start_date']) ? \Carbon\Carbon::parse($projData['info']['start_date'])->format('d-m-Y') : '-' }}</td>
                                <td>{{ !empty($projData['info']['end_date']) ? \Carbon\Carbon::parse($projData['info']['end_date'])->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <button onclick="toggleRow(this, 'projUPG-{{ $project_cd }}')"
                                        class="btn btn-sm btn-primary float-right">
                                        <span>+</span>
                                    </button>
                                </td>
                            </tr>
                            <tr id="projUPG-{{ $project_cd }}" style="display:none;">
                                <td colspan="7">
                                    {{-- 🔹 NEW TEMP --}}
                                    @if(!empty($projData['new_temp']))
                                        <h6 class="text-primary">New Assets (Temp Road)</h6>
                                        <table class="table table-sm table-bordered mb-3">
                                            <thead>
                                                <th>Asset Type</th>
                                                <th> Asset Name</th>
                                                <th>Temp Asset</th>
                                                <th>Parent Asset</th>
                                                <th>Count</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($projData['new_temp'] as $typeKey => $data)
                                                    @if($data['count'] > 0)
                                                  
                                                        <tr>
                                                            <td>{{ $data['name'] }}</td>
                                                            <td>{{ $data['asset_name'] ?? '-' }}</td>
                                                            <td>{{ $data['temp_asset_cd'] }}</td>
                                                            <td>{{ $data['parent_asset_cd'] }}</td>
                                                            <td><b>{{ $data['count'] }}</b></td>
                                                            <td>
                                                                <button
                                                                onclick='createAsset({
                                                                asset_plan_id: "{{$data["asset_plan_id"] }}",
                                                                dept_cd: "{{$projData["info"]["dept_cd"] }}",
                                                                project_cd: "{{ $project_cd }}",
                                                                division_cd : "{{ $projData["info"]["division_cd"] }}",
                                                                sub_division_cd : "{{ $projData["info"]["sub_division_cd"] }}",
                                                                typeKey: "{{ $typeKey }}",
                                                                asset_cd: "{{ $data["temp_asset_cd"] }}",
                                                                parent_cd: "{{ $data["parent_asset_cd"] ?? "-" }}",
                                                                asset_name : "{{ $data["asset_name"] ?? null }}",
                                                                new_asset_length : "{{ $data["new_asset_length"] ?? null }}",
                                                                createGroup: "UPG_NEW"
                                                                })'
                                                                class="btn btn-success btn-sm" {{ !$data["is_asset_exist_in_main_table"] ? 'disabled' : '' }}>
                                                                    Create  <!--Function Completed-->
                                                                </button>
                                                                
                                                                @if(!$data['is_asset_exist_in_main_table'])
                                                                    <span class="text-danger ms-2">
                                                                        Parent Asset {{ $data["parent_asset_cd"] ?? "-" }} is Not Yet Created!!
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                   @endif
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif


                                    {{-- 🔹 REDEFINE --}}
                                    @if(!empty($projData['redefine']))
                                        <h6 class="text-warning">Redefine Existing Assets</h6>
                                        <table class="table table-sm table-bordered mb-3">
                                            <thead>
                                                <th>Asset Type</th>
                                                <th>Asset Name</th>
                                                <th>Asset CD</th>
                                                <th>Parent</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($projData['redefine'] as $data)
                                                    <tr>
                                                        <td>{{ $data['name'] }}</td>
                                                        <td>{{ $data['asset_name'] ?? '-' }}</td>
                                                        <td>{{ $data['asset_cd'] }}</td>
                                                        <td>{{ $data['parent'] }}</td>
                                                        <td>
                                                            <button
                                                            onclick='createAsset({
                                                            asset_plan_id: "{{$data["asset_plan_id"] }}",
                                                            dept_cd: "{{$projData["info"]["dept_cd"] }}",
                                                            project_cd: "{{ $project_cd }}",
                                                            division_cd : "{{ $projData["info"]["division_cd"] }}",
                                                            sub_division_cd : "{{ $projData["info"]["sub_division_cd"] }}",
                                                            typeKey: "{{ $data["type_key"] }}",
                                                            asset_cd: "{{ $data["asset_cd"] }}",
                                                            parent_cd: "{{ $data["parent"] ?? "-" }}",
                                                            asset_name : "{{ $data["asset_name"] ?? null }}",
                                                            createGroup: "UPG_REDEFINE"
                                                            })'
                                                            class="btn btn-warning btn-sm">Update</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif


                                    {{-- 🔹 NEW UNDER EXISTING --}}
                                    @if(!empty($projData['new_existing']))
                                        <h6 class="text-success">New Assets Under Existing Road</h6>
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <th>Asset Type</th>
                                                <th>Parent</th>
                                                <th>Count</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @foreach($projData['new_existing'] as $typeKey => $data)
                                                    <tr>
                                                        <td>{{ $data['name'] }}</td>
                                                        <td>{{ $data['parent'] }}</td>
                                                        <td><b>{{ $data['count'] }}</b></td>
                                                        <td>
                                                            <button
                                                            onclick='createAsset({
                                                            asset_plan_id: "{{$data["asset_plan_id"] }}",
                                                            dept_cd: "{{$projData["info"]["dept_cd"] }}",
                                                            project_cd: "{{ $project_cd }}",
                                                            division_cd : "{{ $projData["info"]["division_cd"] }}",
                                                            sub_division_cd : "{{ $projData["info"]["sub_division_cd"] }}",
                                                            typeKey: "{{ $typeKey }}",
                                                            asset_cd: "{{ $data["asset_cd"] ?? null}}",
                                                            parent_cd: "{{ $data["parent"] ?? "-" }}",
                                                            asset_name : "{{ $data["asset_name"] ?? null }}",
                                                            createGroup: "UPG_NEW_EXISTING"
                                                            })'
                                                            class="btn btn-success btn-sm" {{ !$data["is_asset_exist_in_main_table"] ? 'disabled' : '' }}>
                                                            Create <!--Function Completed-->
                                                            </button>
                                                            @if(!$data['is_asset_exist_in_main_table'])
                                                                <span class="text-danger ms-2">
                                                                    Parent Asset {{ $data["parent_asset_cd"] ?? "-" }} is Not Yet Created!!
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="container-fluid border py-2 asset-plan-table-card">
                <table class="table-responsive text-xs table table-bordered table-striped assetSummaryTable"
                    id="project_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" colspan="7">Project Type : Maintenance</th>
                    </thead>
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 1rem;">Project Code</th>
                        <th class="text-center" style="min-width: 1rem;">Project Name</th>
                        <th class="text-center" style="min-width: 1rem;">Division </th>
                        <th class="text-center" style="min-width: 1rem;">Sub Division </th>
                        <th class="text-center" style="min-width: 1rem;">Start Date </th>
                        <th class="text-center" style="min-width: 1rem;">End Date </th>
                        <th class="text-center" style="min-width: 1rem;">Action </th>
                    </thead>
                    <tbody>
                        @foreach($projectsMaintenance as $project_cd => $projData)

                            <tr style="background:#f2f2f2;">
                                <td><b>{{ $project_cd }}</b></td>
                                <td>{{ $projData['info']['project_name'] }}</td>
                                <td>{{ $projData['info']['division'] }}</td>
                                <td>{{ $projData['info']['sub_division'] }}</td>
                                <td>{{ !empty($projData['info']['start_date']) ? \Carbon\Carbon::parse($projData['info']['start_date'])->format('d-m-Y') : '-' }}</td>
                                <td>{{ !empty($projData['info']['end_date']) ? \Carbon\Carbon::parse($projData['info']['end_date'])->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <button onclick="toggleRow(this, 'projMNT-{{ $project_cd }}')"
                                        class="btn btn-sm btn-primary float-right">+</button>
                                </td>
                            </tr>

                            <tr id="projMNT-{{ $project_cd }}" style="display:none;">
                                <td colspan="7">
                                    <h6 class="text-warning">Redefine Existing Assets</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <th>Asset Type</th>
                                            <th>Asset CD</th>
                                            <th>Parent</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach($projData['redefine'] as $data)
                                                <tr>
                                                    <td>{{ $data['name'] }}</td>
                                                    <td>{{ $data['asset_cd'] }}</td>
                                                    <td>{{ $data['parent'] }}</td>
                                                    <td>
                                                        <button
                                                            onclick='createAsset({
                                                            asset_plan_id: "{{$data["asset_plan_id"] }}",
                                                            dept_cd: "{{$projData["info"]["dept_cd"] }}",
                                                            project_cd: "{{ $project_cd }}",
                                                            division_cd : "{{ $projData["info"]["division_cd"] }}",
                                                            sub_division_cd : "{{ $projData["info"]["sub_division_cd"] }}",
                                                            typeKey: "{{ $typeKey }}",
                                                            asset_cd: "{{ $data["asset_cd"] }}",
                                                            parent_cd: "{{ $data["parent"] ?? "-" }}",
                                                            asset_name : "{{ $data["asset_name"] ?? null }}",
                                                            createGroup: "MTN"
                                                            })'
                                                            class="btn btn-warning btn-sm">
                                                            Update
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </section>
@endsection
    @push('styles')
        <style>
            .asset-plan-page,
            .asset-plan-page * {
                font-family: var(--oamis-font, "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif) !important;
            }

            .asset-plan-main {
                background: transparent !important;
                border: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .asset-plan-table-card {
                background: var(--oamis-card) !important;
                border: 1px solid var(--oamis-border) !important;
                border-radius: 22px !important;
                box-shadow: var(--oamis-shadow);
                margin-bottom: 18px;
                overflow: hidden;
                padding: 0 !important;
            }

            .asset-plan-table-card > .table,
            .asset-plan-table-card > table {
                margin-bottom: 0 !important;
            }

            .asset-plan-page .assetSummaryTable {
                border-collapse: separate !important;
                border-spacing: 0;
                color: var(--oamis-ink) !important;
                display: table !important;
                font-size: 14px !important;
                margin: 0 !important;
                min-width: 980px;
                width: 100% !important;
            }

            .asset-plan-page .assetSummaryTable > thead:first-child th {
                background: var(--oamis-table-title-bg) !important;
                border-bottom: 1px solid var(--oamis-border) !important;
                color: var(--oamis-table-title-text) !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                letter-spacing: .08em;
                padding: 16px 18px !important;
                text-align: left !important;
                text-transform: uppercase;
            }

            .asset-plan-page .assetSummaryTable > thead:nth-child(2) th,
            .asset-plan-page .assetSummaryTable > thead:not(:first-child) th,
            .asset-plan-page table.table-sm thead th {
                background: var(--oamis-table-head-bg) !important;
                border-color: var(--oamis-border) !important;
                color: var(--oamis-table-head-text) !important;
                font-size: 12px !important;
                font-weight: 700 !important;
                letter-spacing: .05em;
                padding: 13px 12px !important;
                text-transform: uppercase;
                white-space: nowrap;
            }

            .asset-plan-page .assetSummaryTable td,
            .asset-plan-page .assetSummaryTable th,
            .asset-plan-page table.table-sm td,
            .asset-plan-page table.table-sm th {
                border-color: var(--oamis-border) !important;
                color: var(--oamis-ink) !important;
                vertical-align: middle !important;
            }

            .asset-plan-page .assetSummaryTable > tbody > tr[style*="background"] > td {
                background: var(--oamis-card) !important;
                font-size: 14px !important;
                padding: 14px 12px !important;
            }

            .asset-plan-page .assetSummaryTable > tbody > tr[style*="background"]:hover > td {
                background: rgba(11, 107, 74, .06) !important;
            }

            .asset-plan-page .assetSummaryTable > tbody > tr[id^="proj"] > td {
                background: color-mix(in srgb, var(--oamis-soft) 55%, var(--oamis-card)) !important;
                padding: 14px !important;
            }

            .asset-plan-page table.table-sm {
                background: var(--oamis-card) !important;
                border: 1px solid var(--oamis-border) !important;
                border-radius: 18px;
                color: var(--oamis-ink) !important;
                margin: 10px 0 14px !important;
                overflow: hidden;
                width: 100%;
            }

            .asset-plan-page table.table-sm td {
                background: var(--oamis-card) !important;
                font-size: 13px !important;
                padding: 11px 12px !important;
            }

            .asset-plan-page table.table-sm tbody tr:nth-child(odd) td {
                background: color-mix(in srgb, var(--oamis-soft) 44%, var(--oamis-card)) !important;
            }

            .asset-plan-page h6.text-primary,
            .asset-plan-page h6.text-warning,
            .asset-plan-page h6.text-success {
                color: var(--oamis-ink) !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                letter-spacing: .06em;
                margin: 8px 0 10px !important;
                text-transform: uppercase;
            }

            .asset-plan-page .btn {
                font-size: 12px !important;
                min-height: 32px;
                padding: 7px 11px !important;
            }

            .asset-plan-page button[onclick^="toggleRow"] {
                border-radius: 999px !important;
                height: 32px;
                min-width: 32px;
                padding: 0 !important;
            }

            .asset-plan-page .text-danger {
                color: var(--oamis-danger) !important;
                display: inline-block;
                font-size: 12px !important;
                font-weight: 700;
                margin-top: 6px;
            }

            html[data-theme="dark"] .asset-plan-page .assetSummaryTable > thead:first-child th {
                background: var(--oamis-table-title-bg) !important;
                color: var(--oamis-table-title-text) !important;
            }

            html[data-theme="dark"] .asset-plan-page .assetSummaryTable > tbody > tr[id^="proj"] > td,
            html[data-theme="dark"] .asset-plan-page table.table-sm tbody tr:nth-child(odd) td {
                background: #162235 !important;
                color: #f8fafc !important;
            }

            @media (max-width: 767px) {
                .asset-plan-table-card {
                    border-radius: 18px !important;
                    overflow-x: auto;
                }

                .asset-plan-page .assetSummaryTable {
                    min-width: 920px;
                }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            // $(document).ready(function () {
            //     $('.assetSummaryTable').DataTable({
            //         "pageLength": 25,
            //         "ordering": true
            //     });
            // });
        </script>

        <script>
            function toggleRow(btn, id) {

                let row = document.getElementById(id);
                if (!row) return;
                if (row.style.display === 'none' || row.style.display === '') {
                    row.style.display = 'table-row';
                    btn.innerText = '-';
                } else {
                    row.style.display = 'none';
                    btn.innerText = '+';
                }
            }
        </script>

        <script>
            function createAsset(data) {
                const asset_plan_id = data.asset_plan_id;
                const project_cd = data.project_cd;
                const dept_cd = data.dept_cd;
                const typeKey = data.typeKey;
                const create_asset_method = data.createGroup;
                if (!project_cd || !typeKey) {
                    alert("Invalid data!");
                    return;
                }
                const params = new URLSearchParams(data).toString();
                const searchParams = new URLSearchParams(data);
                const parentCd = searchParams.get('parent_cd');
                
                console.log("params: " + params);
                let url = null;

                if (create_asset_method == "NEW" || create_asset_method == "UPG_NEW" || create_asset_method =="UPG_NEW_EXISTING") {
                    switch (typeKey) {
                        case '10': //New Road
                            url = `{{ route('road.add-road') }}?${params}`;
                            window.location.href = url;
                            break;
                        case '0': //New Culvert
                            url = `{{ url('/asset-management/manage-cdWorks') }}/${parentCd}?asset_plan_id=${asset_plan_id}`;
                            window.location.href = url;
                            break;
                        case '1': //New Bridge
                            url = `{{ url('/asset-management/add-cd-bridge-details') }}/${parentCd}?asset_plan_id=${asset_plan_id}`;
                            window.location.href = url;
                            break;
                         case '2': //New Pavement
                            url = `{{ url('/asset-management/manage-pavement') }}/${parentCd}?asset_plan_id=${asset_plan_id}`;
                            window.location.href = url;
                            break;
                        case '12': //New Retain Wall/Protection Wall
                            url = `{{ url('/asset-management/add-protection-wall') }}/${parentCd}?asset_plan_id=${asset_plan_id}`;
                            window.location.href = url;
                            break;
                    }
                }
                if(create_asset_method == "UPG_REDEFINE")
                {
                    switch (typeKey) {
                        case '10': //Upgrade Road
                            // redefine_asset_project_upgradation
                            url = `{{ route('project.upgrade.asset.redefine') }}?${params}`;
                            window.location.href = url;
                            break;
                        case '0': //Upgrade Culvert
                            url = `{{ route('project.upgrade.asset.redefine') }}?${params}`;
                            window.location.href = url;
                            break;
                        case '1': //Upgrade Bridge
                            url = `{{ route('project.upgrade.asset.redefine') }}?${params}`;
                            window.location.href = url;
                            break;
                         case '2': //Upgrade Pavement
                            
                            break;
                        case '12': //Upgrade Retain Wall/Protection Wall
                            url = `{{ route('project.upgrade.asset.redefine') }}?${params}`;
                            window.location.href = url;
                            break;
                    }
                }
                if(create_asset_method == "MTN")
                {
                    
                }
                

            }
        </script>
    @endpush
