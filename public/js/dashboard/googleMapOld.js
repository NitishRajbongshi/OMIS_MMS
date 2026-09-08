$(document).ready(function () {
    // let map;
    let lastInteractedFeatureIds = [];
    let lastClickedFeatureIds = [];
    let datasetLayer;

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        // const { LatLng } = await google.maps.importLibrary("core");

        const myLatLng = { lat: 25.67, lng: 94.12 };
        const styleId = "Nagaland";
        const mapId = "3d95c646e2e4e33c";
        const datasetId = "78f1fe3e-e77d-4780-a45b-d76c2903391c";
        // const styleOptions = {
        //     strokeColor: "blue",
        //     strokeWeight: 2,
        //     strokeOpacity: 1,
        //     fillColor: "green",
        //     fillOpacity: 0.3,
        // };
        const map = new Map(document.getElementById("map"), {
            zoom: 12,
            center: myLatLng,
            mapId: mapId,
            mapTypeControl: false,
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: "Road GIS Info!",
        });
        var infowindow = new google.maps.InfoWindow();

        //@ts-ignore

        // const datasetLayer = new google.maps.Data({ map: map });
        // datasetLayer.loadGeoJson('/read-local-file');
        datasetLayer = map.getDatasetFeatureLayer(datasetId);

        datasetLayer.style = applyStyle;
        // datasetLayer.setStyle(styleOptions);

        datasetLayer.addListener("click", handleClick);
        datasetLayer.addListener("mousemove", handleMouseMove);

        // //   map.data.addListener('mouseover', function(event) {
        // //   var title = event.feature.getProperty('OBJECTID');
        // //  console.log("Title: " + title);
        // // })
        //   // Map event listener.
        map.addListener("mousemove", () => {
            // If the map gets a mousemove, that means there are no feature layers
            // with listeners registered under the mouse, so we clear the last
            // interacted feature ids.
            console.log("Inside Map mouseMove");
            // if (lastInteractedFeatureIds.length > 0)
            if (lastInteractedFeatureIds?.length) {
                lastInteractedFeatureIds = [];
                datasetLayer.style = applyStyle;
            }
        });

        const attributionDiv = document.createElement("div");
        const attributionControl = createAttribution(map);

        attributionDiv.appendChild(attributionControl);
        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
            attributionDiv
        );
    }

    function handleClick(/* MouseEvent */ e) {
        var jsonString;
        if (e.features) {
            var obj = new Object();

            lastClickedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["OBJECTID"]
            );
            var rd_name = e.features.map(
                (f) => f.datasetAttributes["Road_Name_"]
            );
            var rd_id = e.features.map((f) => f.datasetAttributes["Road_ID_"]);
            var dist_name = e.features.map(
                (f) => f.datasetAttributes["District_N"]
            );
            var block_name = e.features.map(
                (f) => f.datasetAttributes["Block_Name"]
            );
            var road_num = e.features.map(
                (f) => f.datasetAttributes["road_num"]
            );
            var road_length = e.features.map(
                (f) => f.datasetAttributes["Length_"]
            );

            jsonString =
                "<p>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>object_id: </strong> " +
                lastClickedFeatureIds[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>road_name: </strong>" +
                rd_name[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>road_id: </strong>" +
                rd_id[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>dist_name: </strong>" +
                dist_name[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>block_name: </strong>" +
                block_name[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>road_num: </strong>" +
                road_num[0] +
                "<br>" +
                "<i class='fa fa-caret-right mr-1 text-xs'></i><strong>road_length: </strong>" +
                road_length[0] +
                "</p>";
        }

        // @ts-ignore
        datasetLayer.style = applyStyle;
        $(".modal-title").html("<i class='fa fa-map mr-1 text-xs'></i>NL PWD ROAD INFORMATION");
        $(".modal-body").html(jsonString);
        $("#road-sum-info").html(jsonString);
        $("#myModal").modal("show");
    }

    function handleMouseMove(/* MouseEvent */ e) {
        if (e.features) {
            console.log("Mouse Move function***************" + e.features);
            // console.log("rrrr: " + e.features.map((f) => f.datasetAttributes["OBJECTID"],));
            lastInteractedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["OBJECTID"]
            );
            // lastInteractedFeatureIds = e.feature.getProperty("OBJECTID");
            datasetLayer.style = applyStyle;
        }
        // else
        // {
        //   console.log("Not a e.features")
        // }

        // @ts-ignore
    }

    const styleDefault = {
        strokeColor: "green",
        strokeWeight: 2.0,
        strokeOpacity: 1.0,
        fillColor: "green",
        fillOpacity: 0.3,
    };
    const styleClicked = {
        ...styleDefault,
        strokeColor: "blue",
        fillColor: "blue",
        fillOpacity: 0.5,
    };
    const styleMouseMove = {
        ...styleDefault,
        strokeWeight: 5.0,
    };

    function applyStyle(/* FeatureStyleFunctionOptions */ params) {
        console.log("Inside Apply Style");
        const datasetFeature = params.feature;
        // Note, 'OBJECTID' is an attribute in this dataset.
        //@ts-ignore

        if (
            lastClickedFeatureIds.includes(
                datasetFeature.datasetAttributes["OBJECTID"]
            )
        ) {
            return styleClicked;
        }

        //@ts-ignore
        if (
            lastInteractedFeatureIds.includes(
                datasetFeature.datasetAttributes["OBJECTID"]
            )
        ) {
            return styleMouseMove;
        }
        return styleDefault;
    }

    // [END maps_dds_datasets_polygon_click_stylefunction]

    function createAttribution(map) {
        const attributionLabel = document.createElement("div");

        // Define CSS styles.
        attributionLabel.style.backgroundColor = "#fff";
        attributionLabel.style.opacity = "0.7";
        attributionLabel.style.fontFamily = "Roboto,Arial,sans-serif";
        attributionLabel.style.fontSize = "5px";
        attributionLabel.style.padding = "2px";
        attributionLabel.style.margin = "2px";
        attributionLabel.textContent = "Data source: Nagaland PWD Road Data";
        return attributionLabel;
    }

    initMap();
    // window.initMap = initMap;
});
