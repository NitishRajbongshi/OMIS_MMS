$("#vehicle_type, #seating_capacity, #no_of_wheels, #maker, #fuel_type,#vehicle_condition").select2();


$('#editDraftVehicleModal').on('show.bs.modal ', function(event) {
    var button = $(event.relatedTarget);
    // var button = event.target;
    var veh_cd = button.data('veh-cd');
    var veh_name = button.data('veh-name');
    var veh_regn_no = button.data('veh-regn-no');
    var veh_chasi_no = button.data('veh-chasi-no');
    var eng_no = button.data('veh-eng-no');
    var veh_tp_descr = button.data('veh-type-desc');
    var veh_tp_cd = button.data('veh-type-cd');
    var seat_cap = button.data('veh-seat-cap');
    var no_of_whl = button.data('veh-no-of-whl');
    var maker_name = button.data('veh-maker-name');
    var maker_cd = button.data('veh-maker-cd');
    var model = button.data('veh-model');
    var fuel_tp_descr = button.data('veh-fuel-type-descr');
    var fuel_tp_cd = button.data('veh-fuel-type-cd');
    var date_of_purchase = button.data('veh-date-of-purchase');
    var purchase_cost = button.data('veh-purchase-cost');
    var veh_cond_descr = button.data('veh-condition-descr');
    var veh_cond_cd = button.data('veh-condition-cd');
    var ld_wt = button.data('veh-laden-weight');
    var unld_wt = button.data('veh-unladen-weight');
    var alloted_to = button.data('veh-alloted-to');
    var alloted_fr = button.data('veh-alloted-from');
    var veh_remarks = button.data('veh-remarks');
    var reason_rejection = button.data('veh-reason-of-rejection');
    
    console.log("veh_tp_cd: " + veh_tp_cd);

    $('#editDraftVehicleForm input[id="veh_asset_cd"]').val(veh_cd);
    $('#editDraftVehicleForm input[id="vehicle_regn_no"]').val(veh_regn_no);
    $('#editDraftVehicleForm input[id="vehicle_name"]').val(veh_name);
    $('#editDraftVehicleForm input[id="chassis_no"]').val(veh_chasi_no);
    $('#editDraftVehicleForm input[id="engine_no"]').val(eng_no);
    $('#editDraftVehicleForm input[id="vehicle_type"]').val(veh_tp_cd);
    $('#editDraftVehicleForm input[id="seating_capacity"]').val(seat_cap);
    $('#editDraftVehicleForm input[id="no_of_wheels"]').val(no_of_whl);
    $('#editDraftVehicleForm input[id="maker"]').val(maker_cd);
    $('#editDraftVehicleForm input[id="model"]').val(model);
    $('#editDraftVehicleForm input[id="fuel_type"]').val(fuel_tp_cd);
    $('#editDraftVehicleForm input[id="date_of_purchase"]').val(date_of_purchase);
    $('#editDraftVehicleForm input[id="purchase_cost"]').val(purchase_cost);
    $('#editDraftVehicleForm input[id="vehicle_condition"]').val(veh_cond_cd);
    $('#editDraftVehicleForm input[id="laden_weight"]').val(ld_wt);
    $('#editDraftVehicleForm input[id="unladen_weight"]').val(unld_wt);
    $('#editDraftVehicleForm input[id="alloted_to"]').val(alloted_to);
    $('#editDraftVehicleForm input[id="alloted_from"]').val(alloted_fr);
    $('#editDraftVehicleForm input[id="remarks"]').val(veh_remarks);
    $('#editDraftVehicleForm input[id="txt_reason_of_rejection"]').val(reason_rejection);
});  

function editDraftData() {
    var formData = $("#editDraftVehicleForm").serialize();
    $.ajax({
        type: "POST",
        url: "/asset-management/editDraftVehicleData",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status === "success" ) {
                
                alert(response.message);
                location.reload(true);
            }
            if (response.status === "failed") {
                alert(response.message);
            }
            if (response.status === 500 || response.status === 409) {
                console.log(response.message);
            }
        },
        error: function (error) {
            // Handle errors
            console.log(error);
        },
    });
}








$(document).ready(function () {
    

    // send data for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm('Are you sure?');
        if (status) {
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("road-id");
                })
                .get();
            if (selectedAsset.length === 0) {
                showDashboardModal("Select atleast one vehicle to send for finalization!");
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-vehicle-details-finalization",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        assetList: selectedAsset
                    },
                    cache: false,
                    success: function (response) {
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        }
                        if (response.status === 503) {
                            showDashboardModal(response.message);
                        }

                        if (response.status === 401) {
                            showDashboardModal(response.message);
                        }

                        if (response.status === 500) {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        }
    });
});