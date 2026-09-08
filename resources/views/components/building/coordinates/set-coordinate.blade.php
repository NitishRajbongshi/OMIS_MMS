<div class="modal fade" id="mapModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modal-title text-sm font-bold" id="mapTitle">
                    Nagaland PWD Map. Drag the map or drag the
                    marker on the map or click on the map to set the geolocation of the building asset.
                </label>
                <button type="button" class="btn-close" id="btnClose" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="row justify-content-center align-item-center">
                <div class="col-md-11 mb-1">
                    <div class="modal-body modal-dialog-centered" id='map' style='width: 100%; height: 450px;'>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id='mapFooter'>
                <div class="">
                    <button type="button" class="calssSaveGeoLocation btn btn-primary btn-sm text-sm" style="width: 8rem;"
                        id="btnSaveGeoLocation">
                        Set Geo Location
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
