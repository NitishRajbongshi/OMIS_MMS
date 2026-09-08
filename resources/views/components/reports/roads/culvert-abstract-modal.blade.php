<div class="modal fade" id="culvertDetailsForARoadModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" id="culvertDetailsForARoadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-sm" id="">Culvert Details</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center align-item-center text-sm">
                    <div class="col-12 col-md-3 mb-1">
                        <label for="name" class="col-form-label">Road ID:</label>
                        <label name="culvertDetails_modal_road_id" id ="culvertDetails_modal_road_id"
                            class="col-form-label"></label>
                    </div>
                    <div class="col-12 col-md-6 mb-1">
                        <label for="name" class="col-form-label">Road name:</label>
                        <label name="culvertDetails_road_name" id ="culvertDetails_road_name"
                            class="col-form-label"></label>
                    </div>
                    <div class="col-12 col-md-3 mb-1">
                        <label for="name" class="col-form-label">Division name:</label>
                        <label name="culvertDetails_div_name" id ="culvertDetails_div_name"
                            class="col-form-label"></label>
                    </div>
                </div>
                <div class="row">
                    <table class="table-responsive text-xs table table-bordered table-striped"
                        id="cd_work_details_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                            <th class="text-center" style="min-width: 5rem;">Culvert No.</th>
                            <th class="text-center" style="min-width: 4rem;">Chainage</th>
                            <th class="text-center" style="min-width: 6rem;">Discharge</th>
                            <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                            <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                            <th class="text-center" style="min-width: 8rem;">Culvert Condition</th>
                            <th class="text-center" style="min-width: 8rem;">Culvert Type</th>
                        </thead>
                        <tbody class="text-center">
                            {{-- dynamic table body --}}
                        </tbody>
                    </table>
                    {{-- table-responsive text-xs table table-bordered table-striped  --}}
                </div>
            </div>
        </div>
    </div>
</div>
