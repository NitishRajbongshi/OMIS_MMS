@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">R&B Abstract</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="px-2">
        <div id="tabBtn">
            <table class="text-xs table table-bordered table-striped" id="divisionWiseAbstractTable">
                <thead class="theader text-white" style="background-color:#417DBE;">
                    <th class="text-center">
                        Serial No.
                    </th>
                    <th class="text-center">
                        Road Category
                    </th>
                    <th class="text-center">
                        Total Road Length
                    </th>
                    <th class="text-center">
                        Total No.s of Road
                    </th>
                </thead>

                <tbody>
                    <?php $i = 1;
                    $count = 0; ?>
                    @foreach ($roadDetails as $roadDetail)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td>
                                {{ $roadDetail->rd_catg_descr }}
                            </td>
                            <td class="text-center">
                                {{ number_format($roadDetail->road_length, 2) }} (Km)
                            </td>
                            <td class="text-center">
                                {{ $roadDetail->road_count }}
                            </td>
                        </tr>
                        <?php $i++;
                        $count = $i; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">

    <style>
        #tabBtn ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #tabBtn li {
            display: inline-block;
        }

        #tabBtn a:hover {
            color: white;
            background: rgb(47, 102, 185);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            $("#divisionWiseAbstractTable")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".generalAbsDivContainer");
        });
    </script>
@endpush
