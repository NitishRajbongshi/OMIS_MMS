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
                <li class="breadcrumb-item">Add Pavement LandSlide</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <x-road-info :roadChainage="$roadChainage" />
            <x-road-tab-navigation />

            <form action="{{ route('pavement.create.landslide') }}" method="post" autocomplete="off" id="pavementForm">
                @csrf
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">Pavement LandSlide Section</legend><br>
                    <h6 class="p-1 border border-info text-light bg-info">
                        <span class="border border-info text-light bg-info text-xs text-uppercase">
                            Adding pavement LandSlide information for pavement id : {{ session('pavement_id') }}
                        </span>
                    </h6>
                    {{-- Hidden fields --}}
                    <input type="hidden" id="road_system_id" name="road_system_id" value="{{ session('system_id') }}">
                    <input type="hidden" id="pavement_id" name="pavement_id" value="{{ session('pavement_id') }}">

                    {{-- Basic Common Fields --}}
                    <div class="row form-1-box border mt-2 pb-2">
                        <div class="col-md-3">
                            <label for="start_chainage">Start Chainage:</label>
                            <input type="number" step="0.01" placeholder="0.00" id="start_chainage"
                                class="form-control form-control-sm @error('start_chainage') is-invalid @enderror"
                                name="start_chainage" value="{{ old('start_chainage') }}"
                                oninput="restrictDecimalPointsUptoThree(event)">

                            @error('start_chainage')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="end_chainage">End Chainage:</label>
                            <input type="number" step="0.01" placeholder="0.00" id="end_chainage"
                                class="form-control form-control-sm @error('end_chainage') is-invalid @enderror"
                                name="end_chainage" value="{{ old('end_chainage') }}"
                                oninput="restrictDecimalPointsUptoThree(event)">

                            @error('end_chainage')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="severity_type">Severity Type: <span class="star"></span></label>
                            <select class="form-control form-control-sm @error('severity_type') is-invalid @enderror"
                                id="severity_type" name="severity_type">
                                <option value="">Choose one</option>
                                @foreach ($severityTypes as $severityType)
                                    <option value="{{ $severityType->severity_cd }}">
                                        {{ $severityType->severity_descr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('severity_type')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </fieldset>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2" id="saveBtn">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                        <i class="fa fa-undo"></i> Reset
                    </button>
                    <a href="{{ route('manageRoad') }}" class="btn btn-secondary btn-sm rounded-0 mt-2">
                        <i class="fa fa-backward"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF PAVEMENT LANDSLIDE DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <table class="table-responsive text-xs table table-bordered table-striped" id="pavement_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <tr class="text-center">
                            <th>Serial Number</th>
                            <th>LandSlide Code</th>
                            <th>Pavement Code</th>
                            <th>Start Chainage</th>
                            <th>End Chainage</th>
                            <th>Severity Type</th>
                            <th>Edit Drainage</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php $i = 1; ?>
                        @foreach ($pavementLansSlideDetails as $detail)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $detail->pv_land_slide_cd }}</td>
                                <td>{{ $detail->rd_pavement_cd }}</td>
                                <td>{{ $detail->land_slide_start_chainage ?? 'N/A' }}</td>
                                <td>{{ $detail->land_slide_end_chainage ?? 'N/A' }}</td>
                                <td>{{ $detail->severity_cd ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a class="text-primary edit" data-toggle="modal"
                                        data-target="#editModal{{ $detail->pv_land_slide_cd }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @foreach ($pavementLansSlideDetails as $item)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $item->rd_pavement_cd }}" tabindex="-1">
            <div class="modal-dialog modal-lg text-xs">
                <form id="update_pavement_{{ $item->rd_pavement_cd }}" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->rd_pavement_cd }}">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5><i class="fas fa-clipboard-list text-dark"></i> <strong class="text-md">Update Pavement
                                    Details</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#subbase_layer_type').select2();
            $('#pavement_details_table').DataTable({});
            // Form submission handling
            $('#pavementForm').on('submit', function() {
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Saving...');
            });

            // Error handling for modals
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('.is-invalid').first().focus();
            });

        });

        function restrictDecimalPoints(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 3) {
                input.value = value.slice(0, decimalIndex + 3);
            }
        }

        function restrictDecimalPointsUptoThree(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 4) {
                input.value = value.slice(0, decimalIndex + 4);
            }
        }
    </script>
@endpush
