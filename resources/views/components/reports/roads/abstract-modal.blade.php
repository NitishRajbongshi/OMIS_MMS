<div class="modal fade" id="roadAbstractModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-sm" id="">Nagaland PWD Road Abstract</h5>
                <button type="button" class="btn-close btn-md" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row justify-content-between align-item-center text-sm px-3">
                <div class="col-12 col-md-4 mb-1">
                    <label for="name" class="col-form-label">Road ID:</label>
                    <label name="modal_road_id" id ="modal_road_id" class="col-form-label"></label>
                </div>
                <div class="col-12 col-md-4 mb-1">
                    <label for="name" class="col-form-label">Road name:</label>
                    <label name="roadAbstractModal_road_name" id ="roadAbstractModal_road_name"
                        class="col-form-label"></label>
                </div>
                <div class="col-12 col-md-4 mb-1">
                    <label for="name" class="col-form-label">Division name:</label>
                    <label name="roadAbstractModal_div_name" id ="roadAbstractModal_div_name"
                        class="col-form-label"></label>
                </div>
            </div>
            <div class="row justify-content-center align-item-center">
                <table class="text-xs table table-bordered table-striped" id="road_asset_abstract_details_table"
                    style="width: 95%">
                    <thead class="theader text-light" style="background-color:#6ea051" aria-colspan="3">
                        <th class="text-center" style="min-width: 3rem;" colspan="3">
                            <h6>Roads Assets Abstract Details</h6>
                        </th>
                    </thead>
                    <thead class="theader text-light text-xs" style="background-color:#417dbe">
                        <th class="text-center" style="min-width: 3rem;">Serial Number</th>
                        <th class="text-center" style="min-width: 6rem;">Asset Name</th>
                        <th class="text-center" style="min-width: 6rem;">Total Count</th>
                    </thead>
                    <tbody>
                        {{-- dynamic table body --}}
                    </tbody>
                </table>
                {{-- table-responsive text-xs table table-bordered table-striped  --}}
            </div>
        </div>
    </div>
</div>
