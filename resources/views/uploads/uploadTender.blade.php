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

            <form action="{{ route('saveTenderDetails') }}" method="post" id="frmUploadTender" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <fieldset class="border p-3 fl">
                    <input type="hidden" id="txtDeptCD" class="form-control" name="txtDeptCD" value="{{ $deptCd }}">
                    {{-- <input type="text" id="txtDeptName" class="form-control" name="txtDeptName"
                                value="{{ $deptName }}" readonly> --}}
                    <legend class="w-auto px-2" style="font-size:14px;"><strong>Upload Tender</strong>
                    </legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <input type="hidden" id="userOfficeType" value="{{ session('users_office_type_cd') }}">
                        </div>
                    </div>

                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="txtDeptName">Department Name <span class="star">*</span></label>
                            <input type="text" id="txtDeptName" class="form-control" name="txtDeptName"
                                value="{{ $deptName }}" readonly>
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="txt_tender_title">Tender Title <span class="star">*</span></label>
                            <input type="text" id="txt_tender_title" class="form-control" name="txt_tender_title">

                            @error('txt_tender_title')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="col-md-3">
                            <label for="tender_expiry_date">Tender Expiry Date <span class="star">*</span></label>
                            <input type="date" id="tender_expiry_date" class="custom-select form-control"
                                name="tender_expiry_date" />
                            @error('tender_expiry_date')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-6">
                            <label for="txt_tender_descr">Tender Description <span class="star">*</span></label>
                            <textarea type="text" id="txt_tender_descr" class="form-control" name="txt_tender_descr"></textarea>

                            @error('txt_tender_descr')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="row form-1-box border mt-2">
                            <div class="col-md-12 pt-2" style="background-color: #efeeee;">
                                <fieldset class="">
                                    <legend class="w-auto px-2" style="font-size:13px ">
                                        Upload Documents
                                    </legend>
                                    <div class="p-2">
                                        <div>
                                            <p class="text-sm text-info text-underline"><strong>
                                                    <i class="fa fa-info-circle mr-1 text-xs"></i>Important:
                                                </strong></p>
                                            <ul class="text-xs text-secondary">
                                                <li>
                                                    <strong>
                                                        File Type:
                                                    </strong>
                                                    Only PDF files are supported for upload in this section.
                                                </li>
                                                <li>
                                                    <strong>
                                                        File Size Limit:
                                                    </strong>
                                                    The maximum allowed file size is 2 MB.
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row form-1-box my-1">
                                            <div class="col-md-2">
                                                <label for="firstFile">1. <span class="star">*</span>Upload
                                                    First File:</label>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="file" class="text-xs text-success" id="firstFile"
                                                    name="firstFile" onchange="showRemoveBtn('firstFile')" required>
                                                <button type="button" id="removeBtn_firstFile"
                                                    class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                    style="background:rgb(252, 217, 217); display:none;"
                                                    onclick="removeFile('firstFile')">
                                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                                    Remove
                                                </button>
                                                @error('firstFile')
                                                    <div class="text-danger text-xs">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="row form-1-box my-1">
                                            <div class="col-md-2">
                                                <label for="secondFile">2. Upload Second File:</label>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="file" class="text-xs text-success" id="secondFile"
                                                    name="secondFile" onchange="showRemoveBtn('secondFile')">
                                                <button type="button" id="removeBtn_secondFile"
                                                    class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                    style="background:rgb(252, 217, 217); display:none;"
                                                    onclick="removeFile('secondFile')">
                                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                                    Remove
                                                </button>
                                                @error('secondFile')
                                                    <div class="text-danger text-xs">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="row form-1-box my-1">
                                            <div class="col-md-2">
                                                <label for="thirdFile">3. Upload Third File:</label>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="file" class="text-xs text-success" id="thirdFile"
                                                    name="thirdFile" onchange="showRemoveBtn('thirdFile')">
                                                <button type="button" id="removeBtn_thirdFile"
                                                    class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                    style="background:rgb(252, 217, 217); display:none;"
                                                    onclick="removeFile('thirdFile')">
                                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                                    Remove
                                                </button>
                                                @error('thirdFile')
                                                    <div class="text-danger text-xs">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row form-1-box my-1">
                                            <div class="col-md-2">
                                                <label for="fourthFile">4. Upload Fourth File:</label>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="file" class="text-xs text-success" id="fourthFile"
                                                    name="fourthFile" onchange="showRemoveBtn('fourthFile')">
                                                <button type="button" id="removeBtn_fourthFile"
                                                    class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                    style="background:rgb(252, 217, 217); display:none;"
                                                    onclick="removeFile('fourthFile')">
                                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                                    Remove
                                                </button>
                                                @error('fourthFile')
                                                    <div class="text-danger text-xs">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                    Submit</button>
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
