@extends('layouts.app')
@section('content')
    <div class="p-1">
        <div class="p-0 m-0">
            <button class="px-3 py-1 m-0 border-0 outline-0 bg-primary" onclick="openCity('equipment')">Equipment</button>
            <button class="px-3 py-1 m-0 border-0 outline-0 bg-primary" onclick="openCity('vehicle')">Vehicle</button>
        </div>
        <div class="border p-1">
            <div id="equipment" class="city">
                <form id="equipmentForm" class="">
                    @csrf
                    <div class="pb-2 border bg-light shadow d-flex flex-wrap justify-content-between align-items-end">
                        <div class="row col-12 col-md-11">
                            <div class="col-md-2">
                                <h6 class="py-1 text-sm border-bottom">Equipemnt Condition
                                </h6>
                                <select id="equip_condition" name="equip_condition"style="width: 100%;"
                                    class="custom_select text-uppercase text-xs">
                                    <option value='A'>All</option>
                                    @foreach ($equipmentConditions as $equipmentCondition)
                                        <option style="font-size: 0.2rem;" class="text-xs"
                                            value="{{ $equipmentCondition->condition_cd }}">
                                            {{ $equipmentCondition->condition_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1">
                                <h6 class="py-1 border-bottom text-sm">Fuel Type</h6>
                                <select id="equip_fuel_type" name="equip_fuel_type"style="width: 100%;"
                                    class="custom_select text-uppercase text-xs">
                                    <option value='A'>All</option>
                                    @foreach ($fuelTypes as $fuelType)
                                        <option style="font-size: 0.2rem;" class="text-xs"
                                            value="{{ $fuelType->fuel_type_cd }}">
                                            {{ $fuelType->fuel_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-1">
                            <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                                View
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="vehicle" class="city" style="display:none">
                <form id="vehicleForm" class="">
                    @csrf
                    <div class="pb-2 border bg-light shadow d-flex flex-wrap justify-content-between align-items-end">
                        <div class="row col-12 col-md-11">
                            <div class="col-md-2">
                                <h6 class="py-1 text-sm border-bottom"> Vehicle Condition
                                </h6>
                                <select id="veh_condition" name="veh_condition"style="width: 100%;"
                                    class="custom_select text-uppercase text-xs">
                                    <option value='A'>All</option>
                                    @foreach ($equipmentConditions as $equipmentCondition)
                                        <option style="font-size: 0.2rem;" class="text-xs"
                                            value="{{ $equipmentCondition->condition_cd }}">
                                            {{ $equipmentCondition->condition_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1">
                                <h6 class="py-1 border-bottom text-sm">Fuel Type</h6>
                                <select id="veh_fuel_type" name="veh_fuel_type"style="width: 100%;"
                                    class="custom_select text-uppercase text-xs">
                                    <option value='A'>All</option>
                                    @foreach ($fuelTypes as $fuelType)
                                        <option style="font-size: 0.2rem;" class="text-xs"
                                            value="{{ $fuelType->fuel_type_cd }}">
                                            {{ $fuelType->fuel_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-1">
                            <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                                View
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="mx-1 border bg-light shadow row">
        <div class="col-12 col-md-9 my-1">
            {{-- <h6 class="py-1 border-bottom">Mechanical Dashboard</h6> --}}
            <img src="{{ asset('images/mechanical.jpg') }}" alt="dashboard_image" width="100%">
        </div>
        <div class="col-12 col-md-3">
            <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Mechanical Summary</h6>
            <div id="equipmentSummaryContainer">
                <h6 class="py-1 border-bottom">
                    <i class="fa fa-circle-dot text-xs"></i>
                    Equipemnt
                </h6>
                {{-- <div id="road-sum-info" class="text-xs"></div> --}}
                <div id="tabBtn">
                    <table class="table-responsive text-xs table table-bordered table-striped"
                        id="divisionWiseAbstractTable">
                        <thead class="theader text-white" style="background-color:#417DBE;">
                            <th class="text-center">
                                Equipment Type
                            </th>
                            <th class="text-center">
                                Total Equipment
                            </th>
                        </thead>

                        <tbody id="equipmentAbstractSummaryBody">
                            @foreach ($equipmentSummaries as $equipmentSummary)
                                <tr>
                                    <td>
                                        {{ $equipmentSummary->equipment_type_descr }}
                                    </td>
                                    <td class="text-center">
                                        {{ $equipmentSummary->equipment_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="vehicleSummaryContainer">
                <h6 class="py-1 border-bottom">
                    <i class="fa fa-circle-dot text-xs"></i>
                    Vehicle
                </h6>
                {{-- <div id="road-sum-info" class="text-xs"></div> --}}
                <div id="tabBtn">
                    <table class="table-responsive text-xs table table-bordered table-striped"
                        id="divisionWiseAbstractTable">
                        <thead class="theader text-white" style="background-color:#417DBE;">
                            <th class="text-center">
                                Vehicle Type
                            </th>
                            <th class="text-center">
                                Total Vehicle
                            </th>
                        </thead>

                        <tbody id="vehicleAbstractSummaryBody">
                            @foreach ($vehicleSummaries as $vehicleSummary)
                                <tr>
                                    <td>
                                        {{ $vehicleSummary->veh_type_descr }}
                                    </td>
                                    <td class="text-center">
                                        {{ $vehicleSummary->equipment_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        function openCity(cityName) {
            var i;
            var x = document.getElementsByClassName("city");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            document.getElementById(cityName).style.display = "block";
        }

        $(document).on('submit', '#equipmentForm', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/asset-management/equipment-abstract",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: $("#equipmentForm").serialize(),
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status === 200) {
                        $("#vehicleSummaryContainer").hide();
                        $("#equipmentSummaryContainer").show();
                        $("#equipmentAbstractSummaryBody").empty();
                        $.each(response.message, function(index, data) {
                            var newEquipmentData =
                                "<tr>" +
                                "<td>" + data.equipment_type_descr + "</td>" +
                                "<td class='text-center'>" + data.equipment_count + "</td>" +
                                "</tr>";
                            $("#equipmentAbstractSummaryBody").append(newEquipmentData);
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: '',
                            text: response["message"],
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }

            });
        })

        $(document).on('submit', '#vehicleForm', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/asset-management/vehicle-abstract",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: $("#vehicleForm").serialize(),
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status === 200) {
                        $("#equipmentSummaryContainer").hide();
                        $("#vehicleSummaryContainer").show();
                        $("#vehicleAbstractSummaryBody").empty();
                        $.each(response.message, function(index, data) {
                            var newEquipmentData =
                                "<tr>" +
                                "<td>" + data.veh_type_descr + "</td>" +
                                "<td class='text-center'>" + data.equipment_count + "</td>" +
                                "</tr>";
                            $("#vehicleAbstractSummaryBody").append(newEquipmentData);
                        })
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: '',
                            text: response["message"],
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }

            });
        })
    </script>
@endpush
