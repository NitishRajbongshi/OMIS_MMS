<div class="mb-1 text-sm">
    <fieldset class="border p-2 fl">
        <legend class="w-auto px-2 mb-3" style="font-size:14px">Road Information</legend>
        <div class="">
            <div>
                <span class="me-3">
                    <i class="fa fa-circle text-primary text-xs"></i>
                    Road Number: <span class="text-bold">{{ session('road_number') }}
                    </span>
                </span>
                <span class="me-3">
                    <i class="fa fa-circle text-primary text-xs"></i>
                    Road Name: <span class="text-bold">{{ session('road_name') }}
                    </span>
                </span>
            </div>
            {{-- <div>
                @if ($freeze == 1)
                    <span id="freezeBtn" class="">
                        
                        <a href="{{ url('/asset-management/finalize-road-asset/' . session('system_id')) }}"
                            class="px-2 btn btn-xs btn-outline-danger text-sm text-bold">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            Finalize Data
                        </a>
                    </span>
                @endif
            </div> --}}
        </div>
    </fieldset>
</div>