@extends('layouts.app')
@section('content')
        <div class="content-header">
            <div class="container-fluid text-sm">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('manageRoad') }}">Manage Roads</a>
                    </li>
                    <li class="breadcrumb-item">Add Habitation</li>
                </ol>
            </div>
        </div>
        <!-- Main content -->
        <section class="content text-sm">
            <div class="container-fluid mainBody py-3">
                @if (session('failed'))
                    <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fa fa-info" aria-hidden="true"></i>
                        <strong>Failed!</strong> {{ session('failed') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <x-road-info :roadChainage="$roadChainage" />
                <x-road-tab-navigation />

                <form action="{{ route('road.add-habitation') }}" method="POST" autocomplete="off">
                    @csrf
                    <ul class="nav nav-tabs" id="habitationTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#habitationTabPane" role="tab">
                                <b>Habitation Details</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#facilitiesTabPane" role="tab">
                                <b>Facilities</b>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3">
                        <div class="tab-pane fade show active" id="habitationTabPane" role="tabpanel">
                            <fieldset class="border p-3">
                                <legend class="w-auto px-2" style="font-size:14px">Habitation DETAILS</legend>
                                <div style="line-height: 2px;" class="my-2">
                                    <p class="text-info text-sm">Completion of fields indicated by an asterisk (
                                        <span class="text-danger text-bold">*</span> ) is mandatory.
                                    </p><br>
                                    <p class="text-info text-sm">Decimal inputs are accepted with a precision of up to three
                                        decimal places. </p>
                                </div>
                                {{-- Hidden field --}}
                                <div class="row form-1-box">
                                    <div class="col-md-3">
                                        <input type="hidden" id="road_system_id" class="form-control form-control-sm"
                                            name="road_system_id" value="{{ session('system_id') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="district">District Name: <span class="star">*</span></label>
                                        <select class="form-select form-control-sm text-uppercase district-select"
                                            id="district" name="district">
                                            <option value="">Choose One</option>
                                            @foreach ($districts as $district)
                                                <option class="text-uppercase" value="{{ $district->dist_code }}">
                                                    {{ $district->dist_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('district')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="block">Block Name: <span class="star">*</span></label>
                                        <select class="form-select form-control-sm text-uppercase block-select" id="block"
                                            name="block" disabled>
                                            <option value="">Choose One</option>
                                        </select>
                                        @error('block')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="village">Village Name:<span class="star">*</span></label>
                                        <select class="form-select form-control-sm text-uppercase village-select"
                                            id="village" name="village" disabled>
                                            <option value="">Choose One</option>
                                        </select>
                                        @error('village')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="mla_constituency_cd">MLA Constituency:<span
                                                class="star">*</span></label>
                                        <select class="form-select form-control-sm text-uppercase mla-select"
                                            id="mla_constituency_cd" name="mla_constituency_cd">
                                            <option value="">Choose One</option>
                                            @foreach ($mlaConsts as $mlaConst)
                                                <option class="text-uppercase" value="{{ $mlaConst->const_cd }}">
                                                    {{ $mlaConst->const_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mla_constituency_cd')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="mp_constituency_cd">MP Constituency:<span class="star">*</span></label>
                                        <select class="form-select form-control-sm text-uppercase mp-select"
                                            id="mp_constituency_cd" name="mp_constituency_cd">
                                            <option value="">Choose One</option>
                                            @foreach ($mpConsts as $mpConst)
                                                <option class="text-uppercase" value="{{ $mpConst->const_cd }}">
                                                    {{ $mpConst->const_desc }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mp_constituency_cd')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="total_population">Total Population:<span class="star">*</span></label>
                                        <input type="number" step="0.001" id="total_population"
                                            class="form-control form-control-sm total_population" name="total_population">
                                        @error('total_population')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="row form-1-box">
                                        <div class="col-md-12">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control form-control-sm text-sm" id="remarks"
                                                name="remarks" rows="2"
                                                placeholder="Write habitation remarks...">{{ old('remarks') }}</textarea>
                                            @error('remarks')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="tab-pane fade" id="facilitiesTabPane" role="tabpanel">
                            <fieldset class="border p-3">
                                <legend class="w-auto px-2" style="font-size:14px">
                                    Facilities Details
                                </legend>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Facility</label>
                                        <select id="facility_id" class="form-select form-control-sm">
                                            <option value="">Choose Facility</option>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}">
                                                    {{ $facility->facility_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Sub Facility</label>
                                        <select id="sub_facility_id" class="form-select form-control-sm">
                                            <option value="">Choose Sub Facility</option>
                                            @foreach($subFacilities as $sub)
                                                <option value="{{ $sub->id }}" data-facility="{{ $sub->facility_id }}">
                                                    {{ $sub->sub_facility_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" id="addFacilityBtn" class="btn btn-success btn-sm">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <table class="table table-bordered table-sm" id="facilityTable">
                                    <thead>
                                        <tr>
                                            <th>Facility</th>
                                            <th>Sub Facility</th>
                                            <th width="80">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </fieldset>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                        <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Reset
                        </button>
                        <button class="btn btn-secondary btn-sm rounded-0 mt-2">
                            <i class="fa fa-backward"></i>
                            <a class="text-white" href="{{ route('manageRoad') }} ">
                            Cancel
                            </a>
                        </button>
                    </div>
                </form>
            </div>

            <!-- table content -->
            <div class="container-fluid mainBody">
                <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                    <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                        LIST OF DRAFT HABITATION DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                    </span>
                </h6>
                <div class="container-fluid border py-2">
                    <div class="d-flex text-sm justify-content-end mb-2">
                        <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            Send for finalization
                        </button>
                    </div>
                    <table class="table-responsive text-xs table table-bordered table-striped user_list"
                        id="habitation_details_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center">Serial Number</th>
                            <th class="text-center">Habitation Code</th>
                            <th class="text-center">District Name</th>
                            <th class="text-center">Block Name</th>
                            <th class="text-center">Village Name</th>
                            <th class="text-center">MLA Constituency</th>
                            <th class="text-center">MP Constituency</th>
                            <th class="text-center">Population Count</th>
                            <th class="text-center">Habitation Remarks</th>
                            <th class="text-center">Facilities</th>
                            <th class="text-center">Edit Habitation</th>
                            <th class="text-center">Select Habitation</th>
                            <th class="text-center">Action</th>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($habitationDetails as $habitationDetail)
                                <tr class="text-center">
                                    <td>{{ $i }}</td>
                                    <td>
                                        {{ $habitationDetail->habitation_cd }}
                                    </td>
                                    <td class="text-uppercase">
                                        {{ $habitationDetail->district }}
                                    </td>
                                    <td class="text-uppercase">
                                        {{ $habitationDetail->block }}
                                    </td>
                                    <td class="text-uppercase">
                                        {{ $habitationDetail->village }}
                                    </td>
                                    <td class="text-uppercase">
                                        {{ $habitationDetail->mla }}
                                    </td>
                                    <td class="text-uppercase">
                                        {{ $habitationDetail->mp }}
                                    </td>
                                    <td>
                                        {{ $habitationDetail->total_population }}
                                    </td>
                                    <td>
                                        {{ $habitationDetail->remarks }}
                                    </td>
                                    <td>
                                        @if(isset($habitationFacilities[$habitationDetail->habitation_cd]))

                                            @php
                                                $groupedFacilities = collect($habitationFacilities[$habitationDetail->habitation_cd])
                                                    ->groupBy('facility_name');
                                            @endphp

                                            <!-- Expand Button -->
                                            <a class="text-primary" data-toggle="collapse"
                                                href="#facility{{ $habitationDetail->habitation_cd }}" role="button">

                                                <i class="fas fa-plus-circle"></i> View
                                            </a>
                                            <div class="collapse mt-2" id="facility{{ $habitationDetail->habitation_cd }}">

                                                <table class="table table-bordered table-sm mb-0 text-xs">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:40%">Facility</th>
                                                            <th style="width:60%">Sub Facilities</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($groupedFacilities as $facilityName => $subs)

                                                            <tr>
                                                                <td class="text-left">
                                                                    {{ $facilityName }}
                                                                </td>

                                                                <td class="text-left">

                                                                    <ul class="mb-0 ps-3">

                                                                        @foreach($subs as $sub)

                                                                            <li>{{ $sub->sub_facility_name }}</li>

                                                                        @endforeach

                                                                    </ul>

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-muted text-center">
                                                No Facilities Added
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <a class="text-primary edit" data-toggle="modal"
                                            data-target="#editModal{{ $habitationDetail->habitation_cd }}"><i
                                                class="fas fa-edit"></i></a>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="selected-asset"
                                            data-habitation="{{ $habitationDetail->habitation_cd }}" />
                                    </td>
                                    <th>
                                        {{-- action="{{ route('road.destroy-habitation') }}" --}}
                                        <form action="{{ route('road.destroy-habitation') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="habitation_cd"
                                                value="{{ $habitationDetail->habitation_cd }}">
                                            <button type="submit" class="border-0 bg-transparent"
                                                onclick="return confirm('Are you sure you want to delete this Habitation?')">
                                                <i class="fa fa-trash text-xs text-danger"></i>
                                            </button>
                                        </form>
                                    </th>
                                </tr>
                                <?php    $i++; ?>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- edit Habitation Modal Start-->
                    @foreach ($habitationDetails as $habitationDetail)
                        <div class="modal fade" id="editModal{{ $habitationDetail->habitation_cd }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('road.edit-habitation') }}">
                                        @csrf
                                        <input type="hidden" name="habitation_cd"
                                            value="{{ $habitationDetail->habitation_cd }}">
                                        <!-- Modal Header -->
                                        <div class="modal-header bg-primary text-white">
                                            <h6 class="modal-title">
                                                Edit Habitation - {{ $habitationDetail->habitation_cd }}
                                            </h6>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Tabs -->
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab"
                                                        href="#general{{ $habitationDetail->habitation_cd }}">
                                                        Habitation
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab"
                                                        href="#habitation_facility{{ $habitationDetail->habitation_cd }}">
                                                        Facilities
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content mt-3">
                                                <!-- ================= GENERAL TAB ================= -->
                                                <div class="tab-pane fade show active"
                                                    id="general{{ $habitationDetail->habitation_cd }}">

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="district">District Name: <span
                                                                    class="star">*</span></label>{{ $habitationDetail->village_name}}
                                                            <select
                                                                class="form-select form-control-sm text-uppercase district-select"
                                                                id="district" name="district">
                                                                <option value="">Choose One</option>
                                                                @foreach ($districts as $district)
                                                                    <option class="text-uppercase"
                                                                        value="{{ $district->dist_code }}" {{ $habitationDetail->district_name == $district->dist_code ? 'selected' : '' }}>
                                                                        {{ $district->dist_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('district')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="block">Block Name: <span class="star">*</span></label>
                                                            <select
                                                                class="form-select form-select-sm text-uppercase block-select"
                                                                id="block" name="block" disabled>
                                                                <option value="">Choose One</option>
                                                                @foreach ($blocks as $block)
                                                                    <option class="text-uppercase"
                                                                        value="{{ $block->block_cd }}" {{ $habitationDetail->block_name == $block->block_cd ? 'selected' : '' }}>
                                                                        {{ $block->block_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('block')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="village">Village Name:<span
                                                                    class="star">*</span></label>
                                                            <select
                                                                class="form-select form-control-sm text-uppercase village-select"
                                                                id="village" name="village" disabled>
                                                                <option value="">Choose One</option>
                                                                @foreach ($villages as $vill)
                                                                    <option class="text-uppercase"
                                                                        value="{{ $vill->village_code }}" {{ $habitationDetail->village_name == $vill->village_code ? 'selected' : '' }}>
                                                                        {{ $vill->village_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('village')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="mla_constituency_cd">MLA Constituency:<span
                                                                    class="star">*</span></label>
                                                            <select
                                                                class="form-select form-control-sm text-uppercase mla-select"
                                                                id="mla_constituency_cd" name="mla_constituency_cd">
                                                                <option value="">Choose One</option>
                                                                @foreach ($mlaConsts as $mlaConst)
                                                                    <option class="text-uppercase"
                                                                        value="{{ $mlaConst->const_cd }}">
                                                                        {{ $mlaConst->const_descr }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('mla_constituency_cd')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="mp_constituency_cd">MP Constituency:<span
                                                                    class="star">*</span></label>
                                                            <select
                                                                class="form-select form-control-sm text-uppercase mp-select"
                                                                id="mp_constituency_cd" name="mp_constituency_cd">
                                                                <option value="">Choose One</option>
                                                                @foreach ($mpConsts as $mpConst)
                                                                    <option class="text-uppercase" value="{{ $mpConst->const_cd }}">
                                                                        {{ $mpConst->const_desc }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('mp_constituency_cd')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="total_population">Total Population:<span
                                                                    class="star">*</span></label>
                                                            <input type="number" step="0.001" id="total_population"
                                                                class="form-control form-control-sm total_population"
                                                                name="total_population"
                                                                value="{{ $habitationDetail->total_population}}">
                                                            @error('total_population')
                                                                <div class="text-danger text-xs">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="row form-1-box">
                                                            <div class="col-md-12">
                                                                <label for="remarks">Remarks</label>
                                                                <textarea class="form-control form-control-sm text-sm"
                                                                    id="remarks" name="remarks" rows="2"
                                                                    placeholder="Write habitation remarks..."
                                                                    value="{{ $habitationDetail->remarks }}">{{ old('remarks') }}</textarea>
                                                                @error('remarks')
                                                                    <div class="text-danger text-xs">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- ================= FACILITIES TAB ================= -->
                                                <div class="tab-pane fade"
                                                    id="habitation_facility{{ $habitationDetail->habitation_cd }}">
                                                    <!-- ADD NEW FACILITY -->
                                                    <div class="row mb-2">

                                                        <div class="col-md-5">
                                                            <select class="form-select form-control-sm facility-select"
                                                                id="facility_id">
                                                                <option value="">Select Facility</option>

                                                                @foreach($facilities as $facility)
                                                                    <option value="{{ $facility->id }}">
                                                                        {{ $facility->facility_name }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <select class="form-select form-control-sm subfacility-select"
                                                                id="sub_facility_id">
                                                                <option value="">Select Sub Facility</option>

                                                                @foreach($subFacilities as $sub)
                                                                    <option value="{{ $sub->id }}"
                                                                        data-facility="{{ $sub->facility_id }}">
                                                                        {{ $sub->sub_facility_name }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <button type="button"
                                                                class="btn btn-success btn-sm add-facility-btn">
                                                                Add
                                                            </button>
                                                        </div>

                                                    </div>
                                                    <table class="table table-bordered table-sm edit-facility-table">

                                                        <thead>
                                                            <tr>
                                                                <th>Facility</th>
                                                                <th>Sub Facility</th>
                                                                <th width="60">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($habitationFacilities[$habitationDetail->habitation_cd]))
                                                                @foreach($habitationFacilities[$habitationDetail->habitation_cd] as $facility)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $facility->facility_name }}
                                                                            <input type="hidden" name="facility_ids[]"
                                                                                value="{{ $facility->facility_id }}">
                                                                        </td>
                                                                        <td>
                                                                            {{ $facility->sub_facility_name }}
                                                                            <input type="hidden" name="sub_facility_ids[]"
                                                                                value="{{ $facility->sub_facility_id }}">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm remove-row">
                                                                                X
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Update
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- edit Habitation Modal End -->
                </div>
            </div>
        </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/habitation/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        #habitationTab .nav-link {
            background-color: #f1f3f5;
            color: #333;
            border: 1px solid #dee2e6;
            margin-right: 3px;
        }

        #habitationTab .nav-link:hover {
            background-color: #e9ecef;
        }

        #habitationTab .nav-link.active {
            background-color: #417DBE;
            color: #fff;
            border-color: #417DBE #417DBE #fff;
        }
        /* Target the Select2 container */
        .select2-container .select2-selection--single {
            font-size: 13px;
        }

        /* Target the dropdown options */
        .select2-results__option {
            font-size: 13px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/habitation/script.js') }}" defer></script>
@endpush