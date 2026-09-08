@props(['roadChainage'])
<div class="mb-1 text-sm">
    <fieldset class="border p-3 fl">
        <legend class="w-auto px-2 mb-3" style="font-size:14px">Road Information</legend>
        <div class="">
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
            <span class="me-3">
                <i class="fa fa-circle text-primary text-xs"></i>
                Road Length: <span class="text-bold">{{ session('road_length') }}
                </span>
            </span>
            {{-- <span class="me-3">
                <i class="fa fa-circle text-primary text-xs"></i>
                Chainage:
                <span class="text-bold">{{ optional($roadChainage)->chainage_from ?? '00.00' }} -
                    {{ optional($roadChainage)->chainage_to ?? '00.00' }}
                </span>
            </span> --}}
        </div>
    </fieldset>
</div>