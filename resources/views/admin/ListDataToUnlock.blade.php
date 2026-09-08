@extends('layouts.app')
@section('content')
    @php
        $zn_dtls = $zoneDetails;
        $crcl_dtls = $circleDetails;
        $dv_dtls = $divisionDetails;
        $sb_dv_dtls = $subDivisionDetails;
    @endphp
    <div class="unlock-page container-fluid">
        <div class="unlock-breadcrumb">
            <a href="{{ route('home') }}">Dashboard</a>
            <span>/</span>
            <span>Unlock Data</span>
        </div>

        <div class="unlock-hero">
            <div>
                <span class="unlock-eyebrow">Administration Control</span>
                <h1>Roads & Bridges Data Unlock</h1>
                <p>Review infrastructure assets and open specific data fields for authorised updates.</p>
            </div>
            <div class="unlock-hero-status">
                <span>Controlled access</span>
                <strong>Audit-ready workflow</strong>
            </div>
        </div>

        <section class="content unlock-content">
            <div class="container-fluid mainBody p-0">

            <div class="unlock-shell">
                <ul class="nav nav-pills nav-tabs nav-fill unlock-tabs">
                    <li class="nav-item active">
                        <a href="#rnb" data-toggle="tab" class="nav-link active" aria-current="page">Roads &
                            Bridges</a>
                    </li>
                    <li class="nav-item">
                        <a href="#nh" data-toggle="tab" class="nav-link">National Highway</a>
                    </li>
                    <li class="nav-item">
                        <a href="#housing" data-toggle="tab" class="nav-link">Housing</a>
                    </li>
                    <li class="nav-item">
                        <a href="#mech" data-toggle="tab" class="nav-link">Mechanicals</a>
                    </li>
                </ul>
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="rnb">
                        @if (session('failed'))
                            <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-info" aria-hidden="true"></i>
                                <strong>Failed!</strong> {{ session('failed') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="frm_unloack_for_rnb unlock-filter-card" id="frm_unloack_for_rnb" method="post"
                            action="{{ route('list_roads_n_bridges_to_unlock') }}">
                            @csrf
                            <div class="unlock-section-heading">
                                <div>
                                    <span>Asset Filters</span>
                                    <strong>Roads & Bridges</strong>
                                </div>
                                <small>Select jurisdiction and category to list assets eligible for unlock.</small>
                            </div>
                            <div class="row unlock-filter-grid">
                                <div class="col-sm-2 unlock-field">
                                    <label for="zone">Zone </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="zone_cd"
                                        id="zone_cd">
                                        <option value="A">All</option>
                                        @foreach ($zoneDetails as $zoneDetail)
                                            @if ($zoneDetail->dept_cd == 14)
                                                <option value={{ $zoneDetail->zone_cd }}>
                                                    {{ $zoneDetail->zone_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">

                                    <label for="circle">Circle</label>

                                    <select style="width:10rem;" class="custom_select text-uppercase" name="circle_cd"
                                        id="circle_cd">
                                        <option value="A">All</option>
                                        @foreach ($circleDetails as $circleDetail)
                                            @if ($circleDetail->dept_cd == 14)
                                                <option value={{ $circleDetail->circle_cd }}>
                                                    {{ $circleDetail->circle_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">

                                    <label for="division">Division</label>

                                    <select style="width:10rem;" class="custom_select text-uppercase" name="division_cd"
                                        id="division_cd">
                                        <option value="A">All</option>
                                        @foreach ($divisionDetails as $divisionDetail)
                                            @if ($divisionDetail->dept_cd == 14)
                                                <option value={{ $divisionDetail->division_cd }}>
                                                    {{ $divisionDetail->division_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="subDivision">Sub-Division </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                        name="sub_division_cd" id="sub_division_cd">
                                        <option value="A">All</option>
                                        @foreach ($subDivisionDetails as $subDivisionDetail)
                                            @if ($subDivisionDetail->dept_cd == 14)
                                                <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                    {{ $subDivisionDetail->sub_div_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-2 unlock-field">
                                    <label for="subDivision">Road Category </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                        name="rd_catg_rnb" id="rd_catg_rnb">
                                        <option value="A">All</option>
                                        @foreach ($road_categories as $item)
                                            <option value={{ $item->rd_catg_cd }}>
                                                {{ $item->rd_catg_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 unlock-field unlock-field-action">
                                    <button type="submit" id="btnGetRoadnBridges" name= "btnGetRoadnBridges"
                                        class="text-xs btn btn-sm btn-primary unlock-view-btn">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>

                        <section class="content unlock-results" id ="section_rnb">
                            <div id="loader">
                                <img src="{{ asset('images/loader2.gif') }}" alt="Loading..." width="60px;">
                            </div>
                            <div class="container-fluid mainBody p-0">

                                <span class="mis-btn-rd"></span>
                                @if ($initPage == false)
                                    <div class="unlock-table-card">
                                        <div class="unlock-table-title">
                                            <div>
                                                <span>Filtered Results</span>
                                                <strong>Road Assets Available for Unlock</strong>
                                            </div>
                                            <small>{{ count($roadDetails) }} record(s)</small>
                                        </div>
                                        <div class="table-responsive unlock-table-scroll">
                                            <table class="text-xs table table-bordered table-striped table-hover user_list unlock-table"
                                                id="roadDetail">
                                        <thead class="theader text-white">
                                            <th class="text-center">SlNo.</th>
                                            <th class="text-center">Road ID</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Road No.</th>
                                            <th class="text-center">Road Name</th>
                                            <th class="text-center">Road Type</th>
                                            <th class="text-center">Road Length</th>
                                            <th class="text-center">Road Owner</th>
                                            <th class="text-center">xxx</th>
                                            <th class="text-center">View CD Work</th>
                                            <th class="text-center">View Bridge</th>
                                            <th class="text-center">View PCI</th>
                                            <th class="text-center">View Suface Type</th>
                                            <th class="text-center">View Habitation</th>
                                            <th class="text-center">View Chainage</th>
                                            <th class="text-center">Unlock Road</th>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1; ?>

                                            @foreach ($roadDetails as $item)
                                                <tr class="text-center">
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        {{ $item->rd_system_id }}
                                                    </td>
                                                    <td>
                                                        {{ $item->rd_catg_descr }}
                                                    </td>
                                                    <td>
                                                        {{ $item->rd_number }}
                                                    </td>
                                                    <td>
                                                        {{ $item->rd_name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->rd_type_descr }}
                                                    </td>
                                                    <td>
                                                        {{ $item->road_length }}
                                                    </td>
                                                    <td>
                                                        {{ $item->district_name }}
                                                    </td>
                                                    <td>
                                                        {{ $item->owner_name }}
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-cd-works/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-bridge-data/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-bridge-data/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-bridge-data/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-bridge-data/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td>
                                                        <a class="unlock-lock-link" href="{{ url('/asset-management/road/show-bridge-data/' . $item->rd_system_id) }}"><i
                                                                class="fa fa-lock"></i></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <form class="fromViewUnlockDetails"
                                                            id="fromViewUnlockDetails{{ $item->rd_system_id }}"
                                                            action="{{ route('viewAssetToUnlock') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="asset_cd" id="asset_cd"
                                                                value="{{ $item->rd_system_id }}">
                                                            <input type="hidden" name="asset_type_cd" id="asset_type_cd"
                                                                value="10">

                                                            <button type="submit"
                                                                class="btn btn-success btn-sm unlock-action-btn">
                                                                <i class="fa fa-unlock" aria-hidden="true"></i>
                                                                Unlock
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php $i = $i + 1; ?>
                                            @endforeach
                                        </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>
                    <div class="tab-pane" id="nh">
                        <form class="frm_unloack_for_nh unlock-filter-card" id="frm_unloack_for_nh" method="POST" action="">
                            <div class="unlock-section-heading">
                                <div>
                                    <span>Asset Filters</span>
                                    <strong>National Highway</strong>
                                </div>
                                <small>Filter administrative hierarchy for highway assets.</small>
                            </div>
                            <div class="row unlock-filter-grid">
                                <div class="col-sm-2 unlock-field">
                                    <label for="zone">Zone </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="zone_nh"
                                        id="zone_nh">
                                        <option value="A">All</option>
                                        @foreach ($zoneDetails as $zoneDetail)
                                            @if ($zoneDetail->dept_cd == 3)
                                                <option value={{ $zoneDetail->zone_cd }}>
                                                    {{ $zoneDetail->zone_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="circle">Circle </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="circle_nh"
                                        id="circle_nh">
                                        <option value="A">All</option>
                                        @foreach ($circleDetails as $circleDetail)
                                            @if ($circleDetail->dept_cd == 3)
                                                <option value={{ $circleDetail->circle_cd }}>
                                                    {{ $circleDetail->circle_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">

                                    <label for="division">Division </label>

                                    <select style="width:10rem;" class="custom_select text-uppercase" name="division_nh"
                                        id="division_nh">
                                        <option value="A">All</option>
                                        @foreach ($divisionDetails as $divisionDetail)
                                            @if ($divisionDetail->dept_cd == 3)
                                                <option value={{ $divisionDetail->division_cd }}>
                                                    {{ $divisionDetail->division_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 unlock-field">

                                    <label for="subDivision">Sub-Division </label>

                                    <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                        name="subDivision_nh" id="subDivision_nh">
                                        <option value="A">All</option>
                                        @foreach ($subDivisionDetails as $subDivisionDetail)
                                            @if ($subDivisionDetail->dept_cd == 3)
                                                <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                    {{ $subDivisionDetail->sub_div_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 unlock-field unlock-field-action">
                                    <button type="submit" class="text-xs btn btn-sm btn-primary unlock-view-btn">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="housing">
                        <form class="frm_unloack_for_housing unlock-filter-card" id="frm_unloack_for_housing" method="POST"
                            action="">
                            <div class="unlock-section-heading">
                                <div>
                                    <span>Asset Filters</span>
                                    <strong>Housing</strong>
                                </div>
                                <small>Filter zones, circles, divisions, and sub-divisions.</small>
                            </div>
                            <div class="row unlock-filter-grid">
                                <div class="col-sm-2 unlock-field">
                                    <label for="zone">Zone </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="zone_housing"
                                        id="zone_housing">
                                        <option value="A">All</option>
                                        @foreach ($zoneDetails as $zoneDetail)
                                            @if ($zoneDetail->dept_cd == 6)
                                                <option value={{ $zoneDetail->zone_cd }}>
                                                    {{ $zoneDetail->zone_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="circle">Circle </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase"
                                        name="circle_housing" id="circle_housing">
                                        <option value="A">All</option>
                                        @foreach ($circleDetails as $circleDetail)
                                            @if ($circleDetail->dept_cd == 6)
                                                <option value={{ $circleDetail->circle_cd }}>
                                                    {{ $circleDetail->circle_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="division">Division </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase"
                                        name="division_housing" id="division_housing">
                                        <option value="A">All</option>
                                        @foreach ($divisionDetails as $divisionDetail)
                                            @if ($divisionDetail->dept_cd == 6)
                                                <option value={{ $divisionDetail->division_cd }}>
                                                    {{ $divisionDetail->division_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="subDivision">Sub-Division </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                        name="subDivision_housing" id="subDivision_housing">
                                        <option value="A">All</option>
                                        @foreach ($subDivisionDetails as $subDivisionDetail)
                                            @if ($subDivisionDetail->dept_cd == 6)
                                                <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                    {{ $subDivisionDetail->sub_div_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 unlock-field unlock-field-action">
                                    <button type="submit" class="text-xs btn btn-sm btn-primary unlock-view-btn">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="mech">
                        <form class="frm_unloack_for_mech unlock-filter-card" id="frm_unloack_for_mech" method="POST" action="">
                            <div class="unlock-section-heading">
                                <div>
                                    <span>Asset Filters</span>
                                    <strong>Mechanicals</strong>
                                </div>
                                <small>Filter administrative hierarchy for mechanical assets.</small>
                            </div>
                            <div class="row unlock-filter-grid">
                                <div class="col-sm-2 unlock-field">
                                    <label for="zone">Zone </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="zone_mech"
                                        id="zone_mech">
                                        <option value="A">All</option>
                                        @foreach ($zoneDetails as $zoneDetail)
                                            @if ($zoneDetail->dept_cd == 15)
                                                <option value={{ $zoneDetail->zone_cd }}>
                                                    {{ $zoneDetail->zone_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="circle">Circle </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase" name="circle_mech"
                                        id="circle_mech">
                                        <option value="A">All</option>
                                        @foreach ($circleDetails as $circleDetail)
                                            @if ($circleDetail->dept_cd == 15)
                                                <option value={{ $circleDetail->circle_cd }}>
                                                    {{ $circleDetail->circle_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="division">Division </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase"
                                        name="division_mech" id="division_mech">
                                        <option value="A">All</option>
                                        @foreach ($divisionDetails as $divisionDetail)
                                            @if ($divisionDetail->dept_cd == 15)
                                                <option value={{ $divisionDetail->division_cd }}>
                                                    {{ $divisionDetail->division_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2 unlock-field">
                                    <label for="subDivision">Sub-Division </label>
                                    <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                        name="subDivision_mech" id="subDivision_mech">
                                        <option value="A">All</option>
                                        @foreach ($subDivisionDetails as $subDivisionDetail)
                                            @if ($subDivisionDetail->dept_cd == 15)
                                                <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                    {{ $subDivisionDetail->sub_div_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 unlock-field unlock-field-action">
                                    <button type="submit" class="text-xs btn btn-sm btn-primary unlock-view-btn">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
    <style>
        .unlock-page {
            color: var(--oamis-ink);
            font-family: var(--oamis-font);
            padding-bottom: 28px;
        }

        .unlock-breadcrumb {
            align-items: center;
            color: var(--oamis-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 13px;
            font-weight: 700;
            gap: 8px;
            margin: 0 0 14px;
        }

        .unlock-breadcrumb a {
            color: var(--oamis-primary);
        }

        .unlock-hero {
            align-items: center;
            background:
                linear-gradient(135deg, rgba(11, 107, 74, .12), transparent 48%),
                var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            display: flex;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
            padding: 22px 24px;
        }

        .unlock-eyebrow,
        .unlock-section-heading span,
        .unlock-table-title span {
            color: var(--oamis-primary);
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .unlock-hero h1 {
            color: var(--oamis-ink);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.03em;
            margin: 4px 0 6px;
        }

        .unlock-hero p {
            color: var(--oamis-muted);
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .unlock-hero-status {
            background: rgba(11, 107, 74, .08);
            border: 1px solid rgba(11, 107, 74, .18);
            border-radius: 18px;
            min-width: 220px;
            padding: 14px 16px;
        }

        .unlock-hero-status span,
        .unlock-hero-status strong {
            display: block;
        }

        .unlock-hero-status span {
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .unlock-hero-status strong {
            color: var(--oamis-primary);
            font-size: 15px;
            margin-top: 3px;
        }

        .unlock-content {
            border-radius: 0;
        }

        .unlock-shell {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border) !important;
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            overflow: hidden;
        }

        .unlock-tabs {
            background: color-mix(in srgb, var(--oamis-soft) 72%, var(--oamis-card));
            border: 0;
            gap: 8px;
            padding: 12px;
        }

        .unlock-tabs .nav-link {
            border: 0 !important;
            border-radius: 999px !important;
            color: var(--oamis-muted) !important;
            font-size: 13px;
            font-weight: 800;
            padding: 10px 14px;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }

        .unlock-tabs .nav-link:hover {
            background: rgba(11, 107, 74, .08);
            color: var(--oamis-primary) !important;
            transform: translateY(-1px);
        }

        .unlock-tabs .nav-link.active {
            background: #0b6b4a !important;
            color: #fff !important;
        }

        .unlock-shell .tab-content {
            padding: 18px;
        }

        .unlock-filter-card {
            background: color-mix(in srgb, var(--oamis-card) 94%, var(--oamis-soft));
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            margin-bottom: 16px;
            padding: 16px;
        }

        .unlock-section-heading,
        .unlock-table-title {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .unlock-section-heading strong,
        .unlock-table-title strong {
            color: var(--oamis-ink);
            display: block;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .unlock-section-heading small,
        .unlock-table-title small {
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 700;
            text-align: right;
        }

        .unlock-filter-grid {
            row-gap: 14px;
        }

        .unlock-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .unlock-field label {
            color: var(--oamis-muted);
            font-size: 12px !important;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
        }

        .unlock-field select,
        .unlock-page .custom_select {
            font-family: var(--oamis-font) !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            min-height: 42px;
            width: 100% !important;
        }

        .unlock-field-action {
            justify-content: flex-end;
        }

        .unlock-view-btn {
            min-height: 42px;
            width: 100%;
        }

        .unlock-results {
            border-radius: 18px;
            padding: 0;
        }

        .unlock-table-card {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            overflow: hidden;
        }

        .unlock-table-title {
            background: var(--oamis-table-title-bg);
            border-bottom: 1px solid var(--oamis-border);
            margin: 0;
            padding: 15px 17px;
        }

        .unlock-table-title span,
        .unlock-table-title strong,
        .unlock-table-title small {
            color: var(--oamis-table-title-text) !important;
        }

        .unlock-table-scroll {
            border: 0 !important;
            border-radius: 0 !important;
        }

        .unlock-table {
            margin-bottom: 0 !important;
            white-space: nowrap;
        }

        .unlock-table td {
            font-size: 14px;
            font-weight: 600;
        }

        .unlock-lock-link {
            align-items: center;
            background: rgba(245, 158, 11, .12);
            border: 1px solid rgba(245, 158, 11, .25);
            border-radius: 999px;
            color: #b45309 !important;
            display: inline-flex;
            height: 32px;
            justify-content: center;
            width: 32px;
        }

        .unlock-lock-link:hover {
            background: #f59e0b;
            color: #111827 !important;
            transform: translateY(-1px);
        }

        .unlock-action-btn {
            min-width: 96px;
        }

        html[data-theme="dark"] .unlock-hero,
        html[data-theme="dark"] .unlock-shell,
        html[data-theme="dark"] .unlock-filter-card,
        html[data-theme="dark"] .unlock-table-card {
            background-color: var(--oamis-card) !important;
        }

        html[data-theme="dark"] .unlock-tabs {
            background: color-mix(in srgb, var(--oamis-soft) 78%, #000);
        }

        html[data-theme="dark"] .unlock-lock-link {
            background: rgba(245, 158, 11, .18);
            color: #fbbf24 !important;
        }

        @media (max-width: 991.98px) {
            .unlock-hero,
            .unlock-section-heading,
            .unlock-table-title {
                align-items: flex-start;
                flex-direction: column;
            }

            .unlock-hero-status {
                width: 100%;
            }

            .unlock-section-heading small,
            .unlock-table-title small {
                text-align: left;
            }
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/admin/UnlockData.js') }}" defer></script>
    <script>
        var zn_dtls = <?php echo json_encode($zn_dtls); ?>;
        var crcl_dtls = <?php echo json_encode($crcl_dtls); ?>;
        var dv_dtls = <?php echo json_encode($dv_dtls); ?>;
        var sub_dv_dtls = <?php echo json_encode($sb_dv_dtls); ?>;


        $("#zone_cd").on("change", function() {
            var zn_cd = $("#zone_cd").val();

            $("#circle_cd").empty();
            $('#circle_cd').append('<option value="A">All</option>');

            $("#division_cd").empty();
            $('#division_cd').append('<option value="A">All</option>');

            $("#sub_division_cd").empty();
            $('#sub_division_cd').append('<option value="A">All</option>');

            if (zn_cd == "A") {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#circle_cd').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#division_cd').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#sub_division_cd').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_cd').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                });
                $("#division_cd").empty();
                $('#division_cd').append('<option value="A">All</option>');
                $("#sub_division_cd").empty();
                $('#sub_division_cd').append('<option value="A">All</option>');
            }
        });

        $("#circle_cd").on("change", function() {
            var crcl_cd = $("#circle_cd").val();
            $("#division_cd").empty();
            $('#division_cd').append('<option value="A">All</option>');

            $("#sub_division_cd").empty();
            $('#sub_division_cd').append('<option value="A">All</option>');
            if (crcl_cd == "A") {
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#division_cd').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#sub_division_cd').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });
            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_cd').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                });

            }
        });

        $("#division_cd").on("change", function() {
            var div_cd = $("#division_cd").val();
            $("#sub_division_cd").empty();
            $('#sub_division_cd').append('<option value="A">All</option>');

            if (div_cd == "A") {

                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#sub_division_cd').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });
            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#sub_division_cd').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });

                // $("#sub_division_cd").empty();
                // $('#sub_division_cd').append('<option value="A">All</option>');
            }
        });


        $("#zone_nh").on("change", function() {
            var zn_cd = $("#zone_nh").val();

            $("#circle_nh").empty();
            $('#circle_nh').append('<option value="A">All</option>');

            $("#division_nh").empty();
            $('#division_nh').append('<option value="A">All</option>');

            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');

            if (zn_cd == "A") {

                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#circle_nh').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    console.log(value.zone_cd);
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_nh').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        });


        $("#circle_nh").on("change", function() {
            var crcl_cd = $("#circle_nh").val();
            $("#division_nh").empty();
            $('#division_nh').append('<option value="A">All</option>');

            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');

            if (crcl_cd == "A") {


                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_nh").on("change", function() {
            var div_cd = $("#division_nh").val();
            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });


        $("#zone_housing").on("change", function() {
            var zn_cd = $("#zone_housing").val();

            $("#circle_housing").empty();
            $('#circle_housing').append('<option value="A">All</option>');

            $("#division_housing").empty();
            $('#division_housing').append('<option value="A">All</option>');

            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');

            if (zn_cd == "A") {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#circle_housing').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_housing').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        });


        $("#circle_housing").on("change", function() {
            var crcl_cd = $("#circle_housing").val();
            $("#division_housing").empty();
            $('#division_housing').append('<option value="A">All</option>');

            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');
            if (crcl_cd == "A") {

                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_housing").on("change", function() {
            var div_cd = $("#division_housing").val();
            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });


        $("#zone_mech").on("change", function() {
            var zn_cd = $("#zone_mech").val();

            $("#circle_mech").empty();
            $('#circle_mech').append('<option value="A">All</option>');

            $("#division_mech").empty();
            $('#division_mech').append('<option value="A">All</option>');

            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');

            if (zn_cd == "A") {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#circle_mech').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_mech').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        });


        $("#circle_mech").on("change", function() {
            var crcl_cd = $("#circle_mech").val();
            $("#division_mech").empty();
            $('#division_mech').append('<option value="A">All</option>');

            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');

            if (crcl_cd == "A") {

                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_mech").on("change", function() {
            var div_cd = $("#division_mech").val();
            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });


        $('form.fromUnlockRoaddetails').on("submit", function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var asset_cd = $('#asset_cd').val();
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                cache: false,
                success: function(response) {
                    if (response.message == 'success') {
                        $('#unlockRoaddetails' + asset_cd).modal().hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: "Fields of Road Asset Unlocked Successfully!!!!",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Road Asset Field could not be Unlocked  Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(response) {
                    $('#unlockRoaddetails' + asset_cd).modal().hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'error',
                        text: "Some Technical issue Arrises!!!\nField could not be Unlocked  Successfully!!!!\nPlease Try Again...",
                        showConfirmButton: true,
                        timer: 5000
                    }).then(() => {
                        location.reload();
                        // window.location.replace(location);
                    });
                }
            });
        });
    </script>
@endpush
