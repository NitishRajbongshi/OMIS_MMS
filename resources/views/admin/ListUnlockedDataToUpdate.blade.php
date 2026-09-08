@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ '#' }}" class="mr-2">Dashboard</a>/Unlocked {{ $asset_name }}
        </div>
        <div class="col-md-12">
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
        </div>
    </div>
    <section class="content" style="border-radius: .2rem;">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <table class="table-responsive text-xs table table-bordered table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road ID</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Type</th>
                    <th class="text-center">Road Length</th>
                    <th class="text-center">District</th>
                    <th class="text-center">Division Name</th>
                    <th class="text-center">Block Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Unlock Valid Upto</th>
                    <th> Action </th>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    {{-- {{ $finalJsonData }} --}}
                    @foreach ($arrFinalData as $key)
                        @php
                        @endphp
                        <tr class="text-center">
                            <td class="text-center">{{ $i }}</td>

                            <td>
                                {{ $key['asset_cd'] }}
                            </td>
                            <td>
                                {{-- {{ json_encode($key['arr_fields_to_update']) }} --}}
                                {{ $key['tblData']->rd_catg_descr }}
                            </td>
                            <td>
                                {{ $key['tblData']->rd_number }}
                            </td>
                            <td>
                                {{ $key['tblData']->rd_name }}
                            </td>
                            <td>
                                {{ $key['tblData']->rd_type_descr }}
                            </td>
                            <td>
                                {{ $key['tblData']->road_length }}
                            </td>
                            <td>
                                {{ $key['tblData']->district_name }}
                            </td>
                            <td>
                                {{ $key['tblData']->division_name }}
                            </td>
                            <td>
                                {{ $key['tblData']->block_name }}
                            </td>
                            <td>
                                {{ $key['tblData']->owner_name }}
                            </td>
                            <td>
                                {{ $key['unlock_valid_upto'] }}
                            </td>
                            <td>
                                <form class="fromViewUnlockDetails" id="fromViewUnlockDetails{{ $key['asset_cd'] }}"
                                    action="{{ route('editUnlockedAssetData') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="asset_cd" id="asset_cd" value="{{ $key['asset_cd'] }}">
                                    <input type="hidden" name="asset_type_cd" id="asset_type_cd" value="10">
                                    <input type="hidden" id="hdnFieldsToUnlock" name="hdnFieldsToUnlock"
                                        value="{{ json_encode($key['arr_fields_to_update']) }}">
                                    <input type="hidden" name="arrUnlockCDs" id="arrUnlockCDs"
                                        value="{{ json_encode($key['arrUnlockCDs']) }}">
                                    <button type="submit" class="fa fa-unlock"></button>
                                </form>
                            </td>
                        </tr>
                        <?php $i = $i + 1; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/admin/UnlockData.js') }}" defer></script>
@endpush
