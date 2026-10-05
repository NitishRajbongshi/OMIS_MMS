<?php

return [

    0 => [
        'name' => 'CD Works',
        'table' => 'public.asset_road_cdwork_details',
        'id_field' => 'rd_cdwork_cd',
        'name_field' => 'culvert_no',
        'display_format' => '{id} — {name}',
    ],

    1 => [
        'name' => 'Bridges',
        'table' => 'public.asset_road_bridge_details',
        'id_field' => 'rd_bridge_cd',
        'name_field' => 'bridge_name',
        'display_format' => '{id} — {name}',
    ],

    2 => [
        'name' => 'Pavements',
        'table' => 'public.asset_road_pavement_condition_indexes',
        'id_field' => 'pci_section_cd',
        'name_field' => 'pci_section_length_in_meter',
        'display_format' => '{id} — {name} m',
    ],

    10 => [
        'name' => 'Roads',
        'table' => 'public.asset_road_details',
        'id_field' => 'rd_system_id',
        'name_field' => 'rd_name',
        'display_format' => '{id} — {name}',
    ],

];