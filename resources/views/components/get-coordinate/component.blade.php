<div id="asset_location_container" style="display: none;">
    <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
            <span class="text-bold text-sm">Culvert Location</span>
        </div>
        <div class="col-md-12" id="coordinates_geting_info" style="display: none;">
            <span class="text-sm text-info">Calculating the coordinates for the culvert...</span>
        </div>
        <div class="col-md-3">
            <label for="lat">Latitude</label>
            <input type="number" placeholder="0" id="lat"
                class="form-control form-control-sm" name="lat"
                value="{{ old('lat') }}" readonly>
            @error('lat')
                <div class="text-danger text-xs">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3">
            <label for="lng">Longitude</label>
            <input type="number" placeholder="0" id="lng"
                class="form-control form-control-sm" name="lng"
                value="{{ old('lng') }}" readonly>
            @error('lng')
                <div class="text-danger text-xs">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-3">
            <br>
            <button type="button" class="btn btn-sm text-xs fw-bold btn-outline-primary mt-2">View In Map</button>
        </div>
    </div>
</div>