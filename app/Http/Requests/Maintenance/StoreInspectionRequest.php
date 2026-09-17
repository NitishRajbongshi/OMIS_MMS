<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'insp_cd' => [
                'nullable',
                'string',
                'max:50',
                'unique:maintenance.mtn_inspection_details,insp_cd',
            ],

            'insp_date' => [
                'nullable',
                'date',
            ],

            'insp_type_cd' => [
                'nullable',
                'string',
                'max:10',
            ],

            'inspector_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'inspector_designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'insp_asset_type' => [
                'nullable',
                'string',
                'max:5',
            ],

            'insp_asset_id' => [
                'nullable',
                'string',
                'max:50',
            ],

            'insp_asset_location' => [
                'nullable',
                'string',
                'max:150',
            ],

            'start_section' => [
                'nullable',
                'numeric',
                'numeric',
            ],

            'end_section' => [
                'nullable',
                'numeric',
                'numeric',
            ],

            'cndtn_overall' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_pavement' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_drainage' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_shoulder' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_structural' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_safety_features' => [
                'nullable',
                'string',
                'max:10',
            ],

            'cndtn_signage' => [
                'nullable',
                'string',
                'max:10',
            ],

            'observation_descr' => [
                'nullable',
                'string',
                'max:255',
            ],

            'risk_type_cd' => [
                'nullable',
                'string',
                'max:10',
            ],

            'recmnd_action_type_cd' => [
                'nullable',
                'string',
                'max:10',
            ],

            'recmnd_priority' => [
                'nullable',
                'string',
                'max:10',
            ],

            'recmnd_work' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_defects_observed' => [
                'nullable',
                'in:Y,N',
            ],

            'pci_section_cd' => [
                'nullable',
                'string',
                'max:30',
            ],

            'surface_type_id' => [
                'nullable',
                'integer',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:255',
            ],

            'created_by' => [
                'nullable',
                'integer',
            ],

            'updated_by' => [
                'nullable',
                'integer',
            ],

            'observations' => [
                'nullable',
                'array',
            ],

            'pci_value' => [
                'nullable',
                'numeric',
            ],

            'pci_section_length_in_meter' => [
                'nullable',
                'numeric',
            ],

            'rd_system_id' => [
                'nullable',
                'string',
                'max:30',
            ],

            'chainage' => [
                'nullable',
                'numeric',
            ],

            'cracking_percent' => [
                'nullable',
                'numeric',
            ],

            'ravelling_percent' => [
                'nullable',
                'numeric',
            ],

            'pot_holes_percent' => [
                'nullable',
                'numeric',
            ],

            'shoving_percent' => [
                'nullable',
                'numeric',
            ],

            'patching_percent' => [
                'nullable',
                'numeric',
            ],

            'settlement_depression_percent' => [
                'nullable',
                'numeric',
            ],

            'rut_depth' => [
                'nullable',
                'numeric',
            ],

            'tot_motorized_traffic_per_day' => [
                'nullable',
                'numeric',
            ],

            'tot_comm_veh_traffic_per_day' => [
                'nullable',
                'numeric',
            ],

            'pv_traffic_light' => [
                'nullable',
                'string',
                'max:1',
            ],

            'pci_remarks' => [
                'nullable',
                'string',
                'max:255',
            ],

            'created_at_office_cd' => [
                'nullable',
                'integer',
            ],

            'pci_year' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
