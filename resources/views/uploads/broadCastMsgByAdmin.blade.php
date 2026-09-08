@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid mainBody py-3">
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

            @if (session('invalid'))
                <div class="alert alert-success">
                    {{ session('invalid') }}
                </div>
            @endif

            <form action="{{ route('submitShortMsg') }}" method="post" id="frmShortMsg" autocomplete="off">
                @csrf
                <fieldset class="border p-3 fl">
                    <div class="row form-1-box">
                        <div class="col-md-12">
                            <label for="txtBroadcastMsg">Sample Msg: Server will undergo scheduled maintenance on [Date]
                                (Today),
                                from [Start Time] to [End Time]. During this time, the server and associated services
                                may be temporarily unavailable.
                            </label>
                            <label for="txtBroadcastMsg">Message:</label>
                            <textarea class="form-control text-sm" id="txtBroadcastMsg" name="txtBroadcastMsg" rows="2"
                                placeholder="Write message here..." required></textarea>
                        </div>
                        <div class="col-md-3">
                            <label for="txtBroadcastMsg">Duration In Minutes : <span class="star">*</span></label>
                            <input type="text" id="txtMinute" class="custom-select form-control" name="txtMinute"
                                placeholder="0" required />
                        </div>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                    Publish</button>
                <button class="btn btn-danger btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a class="text-white"
                        href="# ">
                        Cancel</a></button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function removeFile(inputId) {
            var fileInput = document.getElementById(inputId);
            fileInput.value = '';
            hideRemoveBtn(inputId);
        }

        function showRemoveBtn(inputId) {
            var removeButton = document.getElementById('removeBtn_' + inputId);
            removeButton.style.display = 'inline-block';
        }

        function hideRemoveBtn(inputId) {
            var removeButton = document.getElementById('removeBtn_' + inputId);
            removeButton.style.display = 'none';
        }


        $(document).ready(function() {
            $('form.frmUploadTender').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {},
                    error: function(response) {
                        console.log(response);

                    }
                });
            });
        });
    </script>
@endpush
