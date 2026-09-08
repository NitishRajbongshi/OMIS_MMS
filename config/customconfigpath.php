<?php
return [
    // Doc Path
    'DOC_ROOT_FOLDER_NAME' => 'NL_PWD_DOCS',
    'ROAD_DOCS_PATH' => 'NL_PWD_DOCS/ROADS/',
    'CDWORK_DOCS_PATH' => 'NL_PWD_DOCS/CD_WORK/',
    'BRIDGES_DOCS_PATH' => 'NL_PWD_DOCS/BRIDGES/',
    'PROTECTION_WALL_DOCS_PATH' => 'NL_PWD_DOCS/PROTECTION_WALL/',
    'PCI_DOCS_PATH' => 'NL_PWD_DOCS/PCI/',
    'PAVEMENT_DOCS_PATH' => 'NL_PWD_DOCS/PAVEMENT/',
    'VEHICLE_DOCS_PATH' => 'NL_PWD_DOCS/VEHICLE/',
    'EQUIPMT_DOCS_PATH' => 'NL_PWD_DOCS/EQUIPMENT/',
    'BUILDING_DOCS_PATH' => 'NL_PWD_DOCS/BUILDINGS/',
    'TENDER_DOCS_PATH' => 'NL_PWD_DOCS/TENDERS/',
    'NOTIFICATION_DOCS_PATH' => 'NL_PWD_DOCS/NOTIFICATIONS/',
    'UNLOCK_DATA_SUPPORTING_DOCS_PATH' => 'NL_PWD_DOCS/UNLOCK_DATA_SUPPORTING_DOCS/',
    // Image Path
    'PCI_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/PCI/',
    'CDWORK_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/CD_WORK/',
    'BRIDGE_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/BRIDGE/',
    'PROTECTION_WALL_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/protection_wall/',
    'PAVEMENT_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/pavement/',
    'ROADS_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/ROADS/',
    'BUILDING_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/BUILDINGS/',

    //PMS DOC & IMAGE PATH
    'PROJECT_DOCS_PATH' => 'NL_PWD_DOCS/PROJECTS/',
    'PMS_DOCS_PATH' => 'NL_PWD_DOCS/PMS/',
    'PMS_ASSET_IMAGES_PATH' => 'images/NL_Road_Asset_Mgmt_Images/PMS/',
    'PMS_PROGRESS_IMAGES_PATH' => 'images\\NL_Road_Asset_Mgmt_Images\\PROJECT_PROGRESS\\',

    // Chainage value: 43.205.45.246:8085/getLatLongByChainage?uid=xxx&road_id=xxxx&distance=xx
    'CHAINAGE_POSITION' => 'http://43.205.45.246:8085/getLatLongByChainage',

    // PCI Value API
    'PCI_FINAL_VALUE' => 'http://43.205.45.246:8085/calculatePCIValue',

    // APK link
    'APK' => 'http://43.205.45.246:8085/getURLAssetManagementAPK',

    // Distress image API
    'DISTRESS_IMAGES' => 'http://43.205.45.246:8085/getAssetImagesURL',
    // 'DISTRESS_IMAGES' => 'http://http://192.168.1.126:8085/getAssetImagesURL?uid=xxx&asset_cd=xxxxxxx&asset_type_cd=xxxx',
    'ASSET_IMAGES_URL' => 'http://43.205.45.246:8085/getAssetImagesURL',
    //KML File Handle API
    'KML_FILE_CONVERT_API' => 'http://192.168.1.126:8085/convertKMLFile',
    'KML_FILE_CONVERT_INTER_DIV_API' => 'http://127.0.0.1:8085/convertKMLFileInterDivision',
    'MERGE_TO_FINAL_GEOJASON_API' => 'http://192.168.1.126:8085/mergeToFinalGeojsonFile',
    'GET_GEOJASON_DATA_OF_ROAD_API' => 'http://192.168.1.126:8085/getGeoJsonDataOfRoad',
    'GET_ALL_STATES_GEOJSON_DATA_OF_ROAD_API' => 'http://192.168.1.126:8085/getAllStatesRoadsGeoJsonData',
    'GET_DIVISIONS_GEOJSON_DATA_OF_ROAD_API' => 'http://192.168.1.126:8085/getDivisionsRoadsGeoJsonData', 
    'GET_GEOJSON_DATA_OF_SINGLE_ROAD_API' => 'http://192.168.1.126:8085/getSingleRoadsGeoJsonData'
];