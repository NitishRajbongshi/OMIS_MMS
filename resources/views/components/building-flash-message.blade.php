@if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show"
        style="position: absolute; top: 15px; right: 15px; z-index: 9999;">
        <p class="bg-success text-sm px-3 py-2 rounded-2 shadow"><span class="text-bold"> <span
                    class="fa fa-check-circle"></span> Success: </span>{{ session('success') }}</p>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show"
        style="position: absolute; top: 15px; right: 15px; z-index: 9999;">
        <p class="bg-danger text-sm px-3 py-2 rounded-2 shadow"><span class="text-bold"> <span
                    class="fa fa-question-circle"></span> Error: </span>{{ session('error') }}</p>
    </div>
@endif
