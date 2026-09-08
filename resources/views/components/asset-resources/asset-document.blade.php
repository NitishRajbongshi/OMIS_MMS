<div class="row form-1-box border mt-2" style="display: none;" id="asset_document_container">
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
                    <div class="col-md-4">
                        <label for="workorder">1. Upload workorder:</label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" class="text-xs text-success" id="workorder" name="workorder"
                            onchange="showRemoveBtn('workorder')">
                        <button type="button" id="removeBtn_workorder"
                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                            style="background:rgb(252, 217, 217); display:none;" onclick="removeFile('workorder')">
                            <i class="fa fa-trash mr-1 text-xs"></i>
                            Remove
                        </button>
                        @error('workorder')
                            <div class="text-danger text-xs">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row form-1-box my-1">
                    <div class="col-md-4">
                        <label for="design_doc">2. Upload Design Document:</label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" class="text-xs text-success" id="design_doc" name="design_doc"
                            onchange="showRemoveBtn('design_doc')">
                        <button type="button" id="removeBtn_design_doc"
                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                            style="background:rgb(252, 217, 217); display:none;" onclick="removeFile('design_doc')">
                            <i class="fa fa-trash mr-1 text-xs"></i>
                            Remove
                        </button>
                        @error('design_doc')
                            <div class="text-danger text-xs">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row form-1-box my-1">
                    <div class="col-md-4">
                        <label for="sanction_order">3. Upload Saction Order:</label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" class="text-xs text-success" id="sanction_order" name="sanction_order"
                            onchange="showRemoveBtn('sanction_order')">
                        <button type="button" id="removeBtn_sanction_order"
                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                            style="background:rgb(252, 217, 217); display:none;" onclick="removeFile('sanction_order')">
                            <i class="fa fa-trash mr-1 text-xs"></i>
                            Remove
                        </button>
                        @error('sanction_order')
                            <div class="text-danger text-xs">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row form-1-box my-1">
                    <div class="col-md-4">
                        <label for="inspection_report">4. Upload Last Inspection
                            Report:</label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" class="text-xs text-success" id="inspection_report"
                            name="inspection_report" onchange="showRemoveBtn('inspection_report')">
                        <button type="button" id="removeBtn_inspection_report"
                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                            style="background:rgb(252, 217, 217); display:none;"
                            onclick="removeFile('inspection_report')">
                            <i class="fa fa-trash mr-1 text-xs"></i>
                            Remove
                        </button>
                        @error('inspection_report')
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
