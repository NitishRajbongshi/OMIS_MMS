@if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show" class="row justify-content-end mr-2">
        <p class="col-sm-12 col-md-6 bg-success text-sm px-3 py-2 rounded-2"><span class="text-bold"> <span class="fa fa-check"></span> Success: </span>{{ session('success') }}</p>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" class="row justify-content-end mr-2">
        <p class="col-sm-12 col-md-6 bg-danger text-sm px-3 py-2 rounded-2"><span class="text-bold"> <span class="fa fa-question"></span> Error: </span>{{ session('error') }}</p>
    </div>
@endif