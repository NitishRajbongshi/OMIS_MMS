@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Show Road Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content text-xs">
        <div class="container-fluid mainBody py-1">
            <x-show-road-info />
            <x-show-road-nav-link />

            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT CD WORKS DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <!-- table content -->
            <div class="container-fluid mainBody py-3 text-xs border border-primary" id="road_details_table">
                <table class="table-responsive text-xs table table-bordered table-striped user_list" id="road_details">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Road Name</th>
                        <th class="text-center">Road Number</th>
                        <th class="text-center">Road Type</th>
                        <th class="text-center">Road Length (Kms)</th>
                        <th class="text-center">Road Category</th>
                        <th class="text-center">Road Owner</th>
                        <th class="text-center">Office Type</th>
                        <th class="text-center">Office Name</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ $roadDetails->rd_name }}</td>
                            <td class="text-center">{{ $roadDetails->rd_number }}</td>
                            <td class="text-center">{{ $roadDetails->rd_type_descr }}</td>
                            <td class="text-center">{{ $roadDetails->road_length }}</td>
                            <td class="text-center">{{ $roadDetails->rd_catg_descr }}</td>
                            <td class="text-center">{{ $roadDetails->owner_name }}</td>
                            <td class="text-center">{{ $roadDetails->office_type_desc }}</td>
                            <td class="text-center">{{ $roadDetails->office_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#road_details").DataTable({}).buttons().container().appendTo(
                '#road_details_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush
