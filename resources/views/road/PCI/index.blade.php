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
                <li class="breadcrumb-item">Add PCI</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
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

            <form action="{{ route('road.store-pci') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">PCI Section</legend>
                    {{-- Hidden field --}}
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <input type="hidden" id="road_system_id" class="form-control form-control-sm"
                                name="road_system_id" value="{{ session('system_id') }}">
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" id="road_length" class="form-control form-control-sm" name="road_length"
                                value="{{ session('road_length') }}">
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" id="start_chainage" class="form-control form-control-sm"
                                name="start_chainage" value="{{ $roadChainage->chainage_from }}">
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" id="end_chainage" class="form-control form-control-sm" name="end_chainage"
                                value="{{ $roadChainage->chainage_to }}">
                        </div>

                        <div>
                            <input type="hidden" id="preSectionLength" class="form-control form-control-sm"
                                name="preSectionLength" value="{{ $pciPrevValue->pci_section_length_in_meter ?? 'null' }}">
                        </div>
                        <div>
                            <input type="hidden" id="preChainage" class="form-control form-control-sm" name="preChainage"
                                value="{{ $pciPrevValue->chainage ?? 'null' }}">
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-12">
                            <label for="pci_method" class="text-danger">
                                Choose method of PCI calculation:
                            </label><br>
                            <input type="radio" id="manual" name="pci_method" value="M">
                            <label for="manual">I want to manually enter the PCI value</label><br>
                            <input type="radio" id="param" name="pci_method" value="P">
                            <label for="param">I want to calculate PCI from other factors</label>
                        </div>
                    </div>
                    <div class="row form-1-box" id="chanage_section" style="display: none;">
                        <div class="col-md-3">
                            <label for="pci_section_length_in_meter">PCI Section Length(Mtrs):<span
                                    class="star"></span></label>
                            <input type="number" placeholder="0" step="1" min="0"
                                id="pci_section_length_in_meter" class="form-control form-control-sm" value="500"
                                name="pci_section_length_in_meter">
                        </div>
                        <div class="col-md-3">
                            <label for="chainage">Chainage(KMs):<span class="star"></span></label>
                            <input type="number" min="0" step="1" value="" id="chainage"
                                class="form-control form-control-sm" name="chainage" readonly>
                        </div>
                        <div class="col-md-3" id="pci_value" style="display: none;">
                            <label for="pci">PCI:<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0" id="pci"
                                class="form-control form-control-sm" name="pci" value="{{ old('pci') }}">
                        </div>
                    </div>
                    <div class="row form-1-box border mt-2 pb-2" id="pci_param_section"
                        style="background-color: rgb(231, 231, 231); display: none;">
                        <div class="col-md-3">
                            <label for="cracking_percent">Cracking(%) <span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="cracking_percent" class="form-control form-control-sm" name="cracking_percent"
                                value="{{ old('cracking_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="ravelling_percent">Ravelling(%) <span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="ravelling_percent" class="form-control form-control-sm" name="ravelling_percent"
                                value="{{ old('ravelling_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="pot_holes_percent">Pot Holes(%) <span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="pot_holes_percent" class="form-control form-control-sm" name="pot_holes_percent"
                                value="{{ old('pot_holes_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="shoving_percent">Shoving(%) <span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="shoving_percent" class="form-control form-control-sm" name="shoving_percent"
                                value="{{ old('shoving_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="patching_percent">Patching(%) <span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="patching_percent" class="form-control form-control-sm" name="patching_percent"
                                value="{{ old('patching_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="settlement_depression_percent">
                                Settlement and Depression(%)
                                <span class="star"></span>
                            </label>
                            <input type="number" step="0.001" placeholder="0.000" min="0"
                                id="settlement_depression_percent" class="form-control form-control-sm"
                                name="settlement_depression_percent" value="{{ old('settlement_depression_percent') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="rut_depth">
                                Rut depth
                                <span class="star"></span>
                            </label>
                            <input type="number" placeholder="0" min="0" id="rut_depth"
                                class="form-control form-control-sm" name="rut_depth" value="{{ old('rut_depth') }}">
                        </div>
                    </div>
                    <div class="row form-1-box" id="other_section" style="display: none;">
                        <div class="col-md-3">
                            <label for="tot_motorized_traffic_per_day ">Total Motorized
                                Traffic/Day<span class="star"></span></label>
                            <input type="number" min="0" placeholder="0" id="tot_motorized_traffic_per_day"
                                class="form-control form-control-sm" name="tot_motorized_traffic_per_day"
                                value="{{ old('tot_motorized_traffic_per_day') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="pv_traffic_light">Pavement Traffic Light <span class="star"></span></label><br>
                            <input type="radio" id="yes" name="pv_traffic_light" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="pv_traffic_light" value="N">
                            <label for="no">No</label>
                        </div>
                        <div class="col-12">
                            <label for="pci_remarks">Remarks</label>
                            <textarea class="form-control form-control-sm text-sm" id="pci_remarks" name="pci_remarks" rows="2"
                                placeholder="Write PCI remarks..."></textarea>
                        </div>
                    </div>

                    {{-- Upload Image --}}
                    <x-asset-resources.asset-image />
                    {{-- End of upload image --}}
                    {{-- upload documents --}}
                    <x-asset-resources.asset-document />
                    {{-- End of upload documents --}}
                </fieldset>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                        Submit</button>
                    <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Reset
                    </button>
                    <button class="btn btn-secondary btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a
                            class="text-white" href="{{ route('manageRoad') }} ">
                            Cancel</a>
                    </button>
                </div>
            </form>
        </div>

        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT PCI DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
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
                    id="pavement_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">PCI Code</th>
                        <th class="text-center">PCI Length</th>
                        <th class="text-center">PCI Chainage</th>
                        <th class="text-center">PCI Value</th>
                        <th class="text-center">Cracking percentages</th>
                        <th class="text-center">Ravelling percentages</th>
                        <th class="text-center">Pot Holes percentages</th>
                        <th class="text-center">Shoving percentages</th>
                        <th class="text-center">Patching percentages</th>
                        <th class="text-center">Settlement percentages</th>
                        <th class="text-center">Rut Depth</th>
                        <th class="text-center">M. Traffic/Day</th>
                        <th class="text-center">Traffice Light</th>
                        <th class="text-center">PCI Remarks</th>
                        <th class="text-center">Rejection Reason</th>
                        <th class="text-center">Edit PCI</th>
                        <th class="text-center">Select</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($pciDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->pci_section_cd ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pci_section_length_in_meter ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->chainage ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pci_value ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->cracking_percent ? $item->cracking_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->ravelling_percent ? $item->ravelling_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pot_holes_percent ? $item->pot_holes_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->shoving_percent ? $item->shoving_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->patching_percent ? $item->patching_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->settlement_depression_percent ? $item->settlement_depression_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->rut_depth ? $item->rut_depth : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->tot_motorized_traffic_per_day ?? 'NA' }}
                                </td>
                                <td>
                                    @if ($item->pv_traffic_light == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->pci_remarks ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->reason_of_rejection ?? 'NA' }}
                                </td>
                                <td>
                                    <a class="text-primary edit" data-toggle="modal"
                                        data-target="#editModal{{ $item->pci_section_cd }}"><i
                                            class="fas fa-edit"></i></a>
                                </td>
                                <td>
                                    <input type="checkbox" class="selected-asset"
                                        data-pci="{{ $item->pci_section_cd }}" />
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/road/PCI/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/road/pci/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>
@endpush
