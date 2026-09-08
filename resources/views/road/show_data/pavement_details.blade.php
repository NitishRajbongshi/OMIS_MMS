<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pavement</title>
    <style>
        .mainBody {
            background-color: #FEFBFA;
            margin: 0 10px;
        }
        legend {
            line-height: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: black;
            padding: 2px 2px 2px 2px;
            border: 2px solid #5677E7;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('layouts/header')
        @include('sweet::alert')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row text-sm">
                        <div class="col-sm-6 col-md-10">
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                                </li>
                                <li class="breadcrumb-item">Show Pavement Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <x-show-road-info />
                <x-show-road-nav-link />
                <div class="container-fluid mainBody py-3 text-sm">
                    <!-- draft table content -->
                    <!-- table content -->
                    <div class="container-fluid mainBody py-3">
                        <div class="mt-3">
                            <h6 class="text-center text-sm"> <span class="border text-blue-800 px-3 py-1">Draft Pavement
                                    Details</span>
                            </h6>
                            <table class="table-responsive text-xs table table-bordered table-striped user_list"
                                id="pavement_details_table">
                                <thead class="theader text-white" style="background-color:#417DBE">
                                    <th class="text-center">SlNo.</th>
                                    <th class="text-center">Pavement Code</th>
                                    <th class="text-center">Pavement No.</th>
                                    <th class="text-center">Start Chainage</th>
                                    <th class="text-center">End Chainage</th>
                                    <th class="text-center">Condition</th>
                                    <th class="text-center">Pavement Rough</th>
                                    <th class="text-center">Pavement Crack</th>
                                    <th class="text-center">Pavement Pot</th>
                                    <th class="text-center">Pavement Ravelling</th>
                                    <th class="text-center">Pavement Deformation</th>
                                    <th class="text-center">Pavement Age</th>
                                    <th class="text-center">Pavement Drainage</th>
                                    <th class="text-center">Total Motorized Traffic/Day</th>
                                    <th class="text-center">Commercial Vehicle Traffic/Day </th>
                                    <th class="text-center">Traffice Light?</th>
                                    <th class="text-center">Pavement Weight Rating</th>
                                    <th class="text-center">Pavement Rating Value</th>
                                    <th class="text-center">Treatment Type</th>
                                    <th class="text-center">Topography</th>
                                    <th class="text-center">Remarks</th>
                                </thead>

                                <tbody>
                                    <?php $i = 1; ?>

                                    @foreach ($pavementDetailsDraft as $item)
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td>
                                                {{ $item->rd_pavement_cd }}
                                            </td>
                                            <td>
                                                {{ $item->pv_pr }}
                                            </td>
                                            <td>
                                                {{ $item->pv_chainage_fr }}
                                            </td>
                                            <td>
                                                {{ $item->pv_chainage_to }}
                                            </td>
                                            <td>
                                                {{ $item->pv_condition_descr }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_rough }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_crack }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_pot }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_ravelling }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_deformation }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_age }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_drainage }}
                                            </td>
                                            <td>
                                                {{ $item->tot_motorized_traffic_per_day }}
                                            </td>
                                            <td>
                                                {{ $item->tot_comm_veh_traffic_per_day }}
                                            </td>
                                            <td>
                                                @if ($item->pv_traffic_light == 'Y')
                                                    {{ 'YES' }}
                                                @else
                                                    {{ 'NO' }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->pv_weight_rating }}
                                            </td>
                                            <td>
                                                {{ $item->pv_rating_value }}
                                            </td>
                                            <td>
                                                {{ $item->pv_treatment_type }}
                                            </td>
                                            <td>
                                                {{ $item->pv_topography }}
                                            </td>
                                            <td>
                                                {{ $item->pv_remarks }}
                                            </td>
                                            <td class="text-center">
                                                @if ($updated == 1)
                                                    <a class="text-primary edit" data-toggle="modal"
                                                        data-target="#editModal{{ $item->rd_pavement_cd }}"><i
                                                            class="fas fa-edit"></i></a>
                                                @else
                                                    <span class="text-danger text-bold"><i
                                                            class="fas fa-ban"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container-fluid mainBody py-3">
                        <div class="mt-3">
                            <h6 class="text-center text-sm"> <span class="border text-blue-800 px-3 py-1">Freezed Pavement
                                    Details</span>
                            </h6>
                            <table class="table-responsive text-xs table table-bordered table-striped user_list"
                                id="pavement_details_table_final">
                                <thead class="theader text-white" style="background-color:#417DBE">
                                    <th class="text-center">SlNo.</th>
                                    <th class="text-center">Pavement Code</th>
                                    <th class="text-center">Pavement No.</th>
                                    <th class="text-center">Start Chainage</th>
                                    <th class="text-center">End Chainage</th>
                                    <th class="text-center">Condition</th>
                                    <th class="text-center">Pavement Rough</th>
                                    <th class="text-center">Pavement Crack</th>
                                    <th class="text-center">Pavement Pot</th>
                                    <th class="text-center">Pavement Ravelling</th>
                                    <th class="text-center">Pavement Deformation</th>
                                    <th class="text-center">Pavement Age</th>
                                    <th class="text-center">Pavement Drainage</th>
                                    <th class="text-center">Total Motorized Traffic/Day</th>
                                    <th class="text-center">Commercial Vehicle Traffic/Day </th>
                                    <th class="text-center">Traffice Light?</th>
                                    <th class="text-center">Pavement Weight Rating</th>
                                    <th class="text-center">Pavement Rating Value</th>
                                    <th class="text-center">Treatment Type</th>
                                    <th class="text-center">Topography</th>
                                    <th class="text-center">Remarks</th>
                                </thead>

                                <tbody>
                                    <?php $i = 1; ?>

                                    @foreach ($pavementDetailsFinal as $item)
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td>
                                                {{ $item->rd_pavement_cd }}
                                            </td>
                                            <td>
                                                {{ $item->pv_pr }}
                                            </td>
                                            <td>
                                                {{ $item->pv_chainage_fr }}
                                            </td>
                                            <td>
                                                {{ $item->pv_chainage_to }}
                                            </td>
                                            <td>
                                                {{ $item->pv_condition_descr }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_rough }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_crack }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_pot }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_ravelling }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_deformation }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_age }}
                                            </td>
                                            <td>
                                                {{ $item->pavement_drainage }}
                                            </td>
                                            <td>
                                                {{ $item->tot_motorized_traffic_per_day }}
                                            </td>
                                            <td>
                                                {{ $item->tot_comm_veh_traffic_per_day }}
                                            </td>
                                            <td>
                                                @if ($item->pv_traffic_light == 'Y')
                                                    {{ 'YES' }}
                                                @else
                                                    {{ 'NO' }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->pv_weight_rating }}
                                            </td>
                                            <td>
                                                {{ $item->pv_rating_value }}
                                            </td>
                                            <td>
                                                {{ $item->pv_treatment_type }}
                                            </td>
                                            <td>
                                                {{ $item->pv_topography }}
                                            </td>
                                            <td>
                                                {{ $item->pv_remarks }}
                                            </td>
                                            <td class="text-center">
                                                @if ($updated == 1)
                                                    <a class="text-primary edit" data-toggle="modal"
                                                        data-target="#editModal{{ $item->rd_pavement_cd }}"><i
                                                            class="fas fa-edit"></i></a>
                                                @else
                                                    <span class="text-danger text-bold"><i
                                                            class="fas fa-ban"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('layouts/footer')

    <script>
        // script for draft data table
        $(function() {
            $("#pavement_details_table").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function() {
            $("#pavement_details_table_final").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_final_wrapper .col-md-11:eq(1)');
        });
    </script>
</body>

</html>
