@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">View Mechanicals</li>
            </ol>
        </div>
    </div>

    <div id="loader">
        <img src="{{ asset('images/loader2.gif') }}" alt="Loading..." width="60px;">
    </div>

    <section class="content">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <div class="d-flex justify-content-start flex-wrap text-xs">
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a id="cd_work_link" class="wing_btn px-2 rounded-0 btn btn-sm text-light"
                            style="width: 10rem; border-top: 1px solid #417dbe; border-left: 1px solid #417dbe; border-right: 1px solid #417dbe; background-color: #417dbe;"
                            href={{ route('viewEquipmentWings') }}>
                            Equipment Details
                        </a>
                    </span>
                </div>

                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a id="cd_work_link"
                            class="wing_btn px-2 rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 10rem;" href={{ route('viewVehicleWings') }}>
                            Vehicle Details
                        </a>
                    </span>
                </div>
            </div>
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF EQUIPMENT UNDER NAGALAND P W D (MECHANICAL).
                </span>
            </h6>
            <div class="border border-primary px-1 rounded loaderContainer">
                <div class="row justify-content-between align-item-center">
                    <form id="wing_mechanical_equipment" class="mt-2 mb-1">
                        @csrf
                        <div class="row justify-content-center align-item-center">
                            <div class="d-flex flex-wrap col-sm-12 col-md-11">
                                <div class="d-flex mr-2 justify-content-start align-item-center ">
                                    <div class="mr-1">
                                        <label for="condition">Equipment Condition </label>
                                    </div>
                                    <div class="mr-1">
                                        <select class="custom_select" name="condition" id="condition" style="width: 6rem;">
                                            <option value="null">All</option>
                                            @foreach ($conditions as $condition)
                                                <option value={{ $condition->condition_cd }}>
                                                    {{ $condition->condition_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex mr-2 justify-content-start align-item-center ">
                                    <div class="mr-1">
                                        <label for="purchased_year">Purchased Year </label>
                                    </div>
                                    <div class="mr-1">
                                        <select class="custom_select" name="purchased_year" id="purchased_year"
                                            style="width: 6rem;">
                                            <option value="null">All</option>
                                            <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex mr-2 justify-content-start align-item-center ">
                                    <div class="mr-1">
                                        <label for="warranty">Under Warranty?</label>
                                    </div>
                                    <div class="mr-1">
                                        <select class="custom_select" name="warranty" id="warranty" style="width: 6rem;">
                                            <option value="null">All</option>
                                            <option value="Y">YES</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-sm-12 col-md-1">
                                <div class="col-md-2 col-sm-6">
                                    <button type="submit" class="text-xs btn btn-sm btn-outline-primary">
                                        View
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div id="road_container" class="container-fluid mainBody border mb-1" style="border-radius: .3rem;">
                    <div class="d-flex justify-content-end py-1">
                        <i class="fas fa-caret-left" id="toggleBtn"></i>
                    </div>
                    <div class="container-fluid mt-3" id="tableContent" style="display: none;">
                        <div class="row my-2 justify-content-end align-item-center">
                            <div class="col-sm-6 col-md-3 d-flex justify-content-end mb-1">
                                <span class="mis-btn-road"></span>
                            </div>
                        </div>
                        <table class="table-responsive text-xs table table-bordered table-striped user_list"
                            id="mechanical_equipment_details_table">
                            <thead class="theader text-xs text-white" style="background-color:#417dbe">
                                <th class="text-center">Serial Number</th>
                                <th class="text-center">Equipment Code</th>
                                <th class="text-center">Equipment Name</th>
                                <th class="text-center">Serial Number</th>
                                <th class="text-center">Model Number</th>
                                <th class="text-center">Purchased year</th>
                                <th class="text-center">Purchased Cost</th>
                                <th class="text-center">Equipment Condition</th>
                                <th class="text-center">Under Warranty?</th>
                                <th class="text-center">Equipment Remarks</th>
                            </thead>
                            <tbody class="text-center">
                                {{-- dynamic table body --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
    <style>
        a.wing_btn:hover {
            background-color: rgba(65, 125, 190, 0.719);
            color: white;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/wings/mechanical/script.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            $("#toggleBtn").click(function() {
                $("#tableContent").slideToggle('slow');
                $(this).toggleClass("fa-caret-left fa-caret-down");
            });
        });
    </script>
@endpush
