$(document).ready(function () {
    window.require([
        "esri/Map",
        "esri/views/MapView",
        "esri/layers/MapImageLayer"
    ], function (Map, MapView, MapImageLayer) {
        const roadsLayer = new MapImageLayer({
            url: "http://npwdomisgis.nagaland.gov.in/server/rest/services/all_nagaland_roads/MapServer"
        });

        const map = new Map({
            basemap: "streets-vector",
            layers: [roadsLayer]
        });

        const view = new MapView({
            container: "map",
            map: map,
            center: [94.1, 26.1],
            zoom: 8
        });

        roadsLayer.when(function () {
            if (roadsLayer.fullExtent) {
                view.goTo(roadsLayer.fullExtent);
            }
        }).catch(function (error) {
            console.error("ArcGIS road layer failed to load:", error);
        });
    });
});