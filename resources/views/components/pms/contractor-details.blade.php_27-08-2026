@props(['contractorCategories', 'districts', 'states'])
<div class="modal fade" id="contractorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add Contractor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contractorForm" class="row" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6 mb-2">
                        <label>Registration No</label>
                        <input type="text" name="regn_no" class="form-control form-control-sm"
                            placeholder="Enter registration number" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Contractor Name</label>
                        <input type="text" name="contractors_name" class="form-control form-control-sm"
                            placeholder="Enter Contractor Name" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Category</label>
                        <select name="category_cd" class="form-control form-control-sm">
                            @foreach ($contractorCategories as $cat)
                                <option value="{{ $cat->category_cd }}">{{ $cat->category_descr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Address line 1</label>
                        <input type="text" name="address_line_1" class="form-control form-control-sm"
                            placeholder="Enter Address Line 1">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Address line 2</label>
                        <input type="text" name="address_line_2" class="form-control form-control-sm"
                            placeholder="Enter Address Line 2">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>District</label>
                        <select name="district_cd" class="form-control form-control-sm">
                            @foreach ($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>State</label>
                        <select name="state_cd" class="form-control form-control-sm">
                            @foreach ($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Phone</label>
                        <input type="tel" maxlength="10" minlength="10" pattern="[0-9]{10}" name="phone_no"
                            placeholder="Enter Phone Number" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6 mb-2">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter Email Address"
                            class="form-control form-control-sm">
                    </div>

                    <div class="col-12 mt-2">
                        <h6 class="text-xs font-weight-bold text-primary border-bottom pb-1">Bank
                            Details</h6>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>PAN No.</label>
                        <input type="text" name="pan_no" class="form-control form-control-sm"
                            placeholder="Enter PAN Number" maxlength="12">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Bank Account No.</label>
                        <input type="text" name="bank_acc_no" id="modal_bank_acc_no"
                            class="form-control form-control-sm numeric-only" placeholder="Enter Account Number"
                            maxlength="16">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>Confirm Bank Account No.</label>
                        <input type="text" name="confirm_bank_acc_no" id="modal_confirm_bank_acc_no"
                            class="form-control form-control-sm numeric-only" placeholder="Confirm Account Number"
                            maxlength="16" onpaste="return false;">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control form-control-sm"
                            placeholder="Enter IFSC Code" maxlength="20">
                    </div>

                    <div class="row form-1-box border mt-2" style="margin: 0 1.5px;" id="asset_document_container">
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
                                                    Pdf file Size Limit:
                                                </strong>
                                                The maximum allowed file size is 2 MB.
                                            </li>
                                            <li>
                                                <strong>
                                                    Photo file Size Limit:
                                                </strong>
                                                The maximum allowed file size is 1 MB.
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="passportPhoto">1. Upload Passport Size Photo
                                                (jpg,jpeg):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" class="text-xs text-success" id="passportPhoto"
                                                name="_passportPhoto_raw" accept=".jpg,.jpeg">

                                            {{-- Hidden input that will carry the cropped image as base64 --}}
                                            <input type="hidden" name="passportPhoto" id="passportPhotoCropped">

                                            {{-- Preview of final cropped image --}}
                                            <div id="passportPreviewContainer" style="margin-top:10px; display:none;">
                                                <p class="text-xs text-muted mb-1">Final Preview:</p>
                                                <img id="passportPreview"
                                                    style="width:120px; height:150px; object-fit:cover; border:2px solid #28a745; border-radius:5px;">
                                            </div>

                                            <button type="button" id="removeBtn_passportPhoto"
                                                class="outline-0 border border-danger text-danger text-xs rounded-0 mt-1"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removePhoto('passportPhoto')">
                                                <i class="fa fa-trash mr-1 text-xs"></i> Remove
                                            </button>

                                            @error('passportPhoto')
                                                <div class="text-danger text-xs">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="panCardDoc">2. Upload PAN Card (PDF):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" name="panCardDoc" id="panCardDoc"
                                                class="text-xs text-success" accept=".pdf">
                                            <button type="button" id="removeBtn_panCardDoc"
                                                class="outline-0 border border-danger text-danger text-xs rounded-0"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('panCardDoc')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('panCardDoc')
                                                <div class="text-danger text-xs">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="passbookDoc">3. Upload Passbook (PDF):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" name="passbookDoc" id="passbookDoc"
                                                class="text-xs text-success" accept=".pdf">
                                            <button type="button" id="removeBtn_passbookDoc"
                                                class="outline-0 border border-danger text-danger text-xs rounded-0"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('passbookDoc')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('passbookDoc')
                                                <div class="text-danger text-xs">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-primary">Save Contractor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    #contractorModal .modal-body {
        overflow-y: auto !important;
        max-height: 75vh;
    }

    #cropModal {
        z-index: 1060 !important;
    }

    /* Prevent body scroll lock from affecting contractor modal */
    body.modal-open #contractorModal .modal-body {
        overflow-y: auto !important;
    }
</style>
<script>
    // open contractor modal
    $('#add-constructor').click(function() {
        $('#contractorModal').modal('show');
    });
    //start By Pulak
    // Keep contractor modal scrollable whenever it opens
    $('#contractorModal').on('shown.bs.modal', function() {
        $(this).find('.modal-body').css('overflow-y', 'auto');
    });

    // When crop modal is about to show
    $('#cropModal').on('show.bs.modal', function() {
        // Save scroll position of contractor modal body
        var contractorBody = document.querySelector('#contractorModal .modal-body');
        if (contractorBody) {
            window._contractorScrollTop = contractorBody.scrollTop;
        }
    });

    // When crop modal finishes showing - restore contractor modal scroll
    $('#cropModal').on('shown.bs.modal', function() {
        var contractorModal = document.querySelector('#contractorModal');
        var contractorBody = document.querySelector('#contractorModal .modal-body');
        if (contractorModal) contractorModal.style.overflowY = 'auto';
        if (contractorBody) contractorBody.style.overflowY = 'auto';
    });

    // When crop modal is fully hidden - restore everything
    $('#cropModal').on('hidden.bs.modal', function() {
        var contractorModal = document.querySelector('#contractorModal');
        var contractorBody = document.querySelector('#contractorModal .modal-body');

        if (contractorModal) contractorModal.style.overflowY = 'auto';

        setTimeout(function() {
            if (contractorBody) {
                contractorBody.style.overflowY = 'auto';
                // Restore scroll position
                if (window._contractorScrollTop !== undefined) {
                    contractorBody.scrollTop = window._contractorScrollTop;
                }
            }
            // Make sure body doesn't stay locked
            document.body.style.overflow = '';
            document.body.classList.add('modal-open');
        }, 100);
    });

    // submit contractor form
    $('#contractorForm').submit(function(e) {
        e.preventDefault();

        let accNo = $('#modal_bank_acc_no').val().trim();
        let confirmAccNo = $('#modal_confirm_bank_acc_no').val().trim();

        if (accNo) {
            if (accNo.length < 9 || accNo.length > 16) {
                alert("Account number must be between 9 and 16 digits.");
                return;
            }
            if (accNo !== confirmAccNo) {
                alert("Bank Account Number and Confirm Account Number do not match!");
                return;
            }
        }

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('contractor.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {

                // add new contractor to dropdown
                $('#project_awarded_to').append(
                    `<option value="${res.regn_no}" selected>${res.contractors_name}</option>`
                );

                // close modal
                $('#contractorModal').modal('hide');

                // reset form
                $('#contractorForm')[0].reset();

                Swal.fire({
                    icon: res.status,
                    title: res.status.toUpperCase(),
                    text: res.message,
                    timer: 2000
                })
            },
            error: function(xhr) {
                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';

                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '<br>';
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMsg
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Something went wrong!'
                    });

                }
            }
        });
    });
</script>
