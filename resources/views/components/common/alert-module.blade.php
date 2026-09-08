{{-- alert section --}}
<div id="alertContainer" style="text-align: right">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
            style="position: absolute; top: 0; right: 0; z-index: 1;">
            <strong> <i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close btn-xs" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-inline-block" role="alert"
            style="position: absolute; top: 1px; right: 2px; z-index: 1;">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
