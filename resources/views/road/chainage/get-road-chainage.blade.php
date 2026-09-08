@extends('layouts.app')
@section('content')
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
                        <li class="breadcrumb-item">View Chainage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody border py-3">
            @if (session('failed'))
                <div class="alert alert-danger">
                    {{ session('failed') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('invalid'))
                <div class="alert alert-warning text-white">
                    {{ session('invalid') }}
                </div>
            @endif
            <x-road-info :roadChainage="$roadChainage" />
        </div>

        <!-- table content -->
        <div class="container-fluid border mainBody py-3">
            <div class="container-fluid mt-3">
                <h6 class="text-center text-sm">
                    <span class="border text-blue-800 px-3 py-1">
                        Chainage Details
                    </span>
                </h6>
                <table class="table-responsive text-xs table table-bordered table-striped user_list" id="cbr_value_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Start Chainage Value (Km)</th>
                        <th class="text-center">End Chainage Value (Km)</th>
                        <th class="text-center">Remaining Chainage (Km)</th>
                        <th class="text-center">Zone Name</th>
                        <th class="text-center">Circle Name</th>
                        <th class="text-center">Division Name</th>
                        <th class="text-center">Sub Division Name</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($CbrDetails as $key)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $key->chainage_from }}</td>
                                <td class="text-center">{{ $key->chainage_to }}</td>
                                <td class="text-center">{{ $key->remaining_chainage_length }}</td>
                                <td class="text-center">{{ $key->zone_name }}</td>
                                <td class="text-center">{{ $key->circle_name }}</td>
                                <td class="text-center">{{ $key->division_name }}</td>
                                <td class="text-center">{{ $key->sub_div_name }}</td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#cbr_value_table").DataTable({}).buttons().container().appendTo(
                '#cbr_value_table_wrapper .col-md-11:eq(1)');
        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>

    {{-- Calculating The segment length --}}
    <script>
        const start_chainage = document.getElementById('start_chainage');
        const end_chainage = document.getElementById('end_chainage');
        const segment_length = document.getElementById('segment_length');

        const total_rd_length = document.getElementById('total_length_of_the_road');
        const total_segment_length = document.getElementById('total_entered_segment_length');
        const remaining_segment_length = document.getElementById('remaining_length');

        // get the road length and the total segment length
        const totalRoadLength = parseFloat(total_rd_length.value);
        const totalSegmentLength = parseFloat(total_segment_length.value);

        // display the remaining segment length
        const remainingSegLength = totalRoadLength - totalSegmentLength;
        remaining_segment_length.value = remainingSegLength.toFixed(3);

        function calculateSegmentLength() {
            const startChainageValue = parseFloat(start_chainage.value);
            const endChainageValue = parseFloat(end_chainage.value);

            if (!isNaN(startChainageValue) && !isNaN(endChainageValue)) {
                const segmentValue = endChainageValue - startChainageValue;
                segment_length.value = segmentValue;

                const newTotalSegmentLengthValue = totalSegmentLength + segmentValue;
                total_segment_length.value = newTotalSegmentLengthValue.toFixed(3);
                const remainingLength = totalRoadLength - newTotalSegmentLengthValue;
                if (remainingLength >= 0) {
                    remaining_segment_length.value = remainingLength.toFixed(3);
                } else {
                    remaining_segment_length.value = remainingLength.toFixed(3);
                    alert('Invalid! Remaining length can\'t be negative');
                }
            }
        }

        start_chainage.addEventListener('input', calculateSegmentLength);
        end_chainage.addEventListener('input', calculateSegmentLength);
    </script>
@endpush
