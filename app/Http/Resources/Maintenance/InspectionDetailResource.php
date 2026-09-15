<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'insp_cd' => $this->insp_cd,
            'insp_date' => optional($this->insp_date)->toIso8601String(),
            'insp_type_cd' => $this->insp_type_cd,

            'inspector' => [
                'name' => $this->inspector_name,
                'designation' => $this->inspector_designation,
            ],

            'asset' => [
                'type' => $this->insp_asset_type,
                'id' => $this->insp_asset_id,
                'location' => $this->insp_asset_location,
                'surface_type_id' => $this->surface_type_id,
                'pci_section_cd' => $this->pci_section_cd,
            ],

            'section' => [
                'start' => $this->start_section !== null ? (float) $this->start_section : null,
                'end' => $this->end_section !== null ? (float) $this->end_section : null,
            ],

            'condition' => [
                'overall' => $this->cndtn_overall,
                'pavement' => $this->cndtn_pavement,
                'drainage' => $this->cndtn_drainage,
                'shoulder' => $this->cndtn_shoulder,
                'structural' => $this->cndtn_structural,
                'safety_features' => $this->cndtn_safety_features,
                'signage' => $this->cndtn_signage,
            ],

            'observation' => [
                'description' => $this->observation_descr,
                'is_defects_observed' => $this->is_defects_observed === 'Y',
                'risk_type_cd' => $this->risk_type_cd,
            ],

            'recommendation' => [
                'action_type_cd' => $this->recmnd_action_type_cd,
                'priority' => $this->recmnd_priority,
                'work' => $this->recmnd_work,
            ],

            'remarks' => $this->remarks,

            'audit' => [
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                'created_at' => optional($this->created_at)->toIso8601String(),
                'updated_at' => optional($this->updated_at)->toIso8601String(),
            ],
        ];
    }
}
