<div class="row form-1-box border mt-2" id="asset_image_container" style="display: none;">
    <div class="col-md-12 pt-2" style="background-color: #efeeee;">
        <fieldset class="">
            <legend class="w-auto px-2" style="font-size:13px ">
                Upload Asset Images
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
                            Only JPG, JPEG files are supported for upload in this section.
                        </li>
                        <li>
                            <strong>
                                File Size Limit:
                            </strong>
                            The maximum allowed file size is 1 MB.
                        </li>
                    </ul>
                </div>
                <div class="row form-1-box my-1">
                    <div class="col-md-4">
                        <label for="images">Upload Asset Image: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" class="text-xs text-success" id="images" name="images[]"
                            onchange="showRemoveBtn('images')">
                        <button type="button" id="removeBtn_images"
                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                            style="background:rgb(252, 217, 217); display:none;" onclick="removeFile('images')">
                            <i class="fa fa-trash mr-1 text-xs"></i>
                            Remove
                        </button>
                        @error('images')
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