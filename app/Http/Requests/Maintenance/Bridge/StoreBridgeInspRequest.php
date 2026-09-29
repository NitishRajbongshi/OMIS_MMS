<?php

namespace App\Http\Requests\Maintenance\Bridge;

use Illuminate\Foundation\Http\FormRequest;

class StoreBridgeInspRequest extends FormRequest
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
                'unique:maintenance.mtn_bridge_insp_details,insp_cd',
            ],

            'insp_date' => [
                'nullable',
                'date',
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

            'rd_system_id' => [
                'nullable',
                'string',
                'max:30',
            ],

            'rd_bridge_cd' => [
                'nullable',
                'string',
                'max:30',
            ],

            'bridge_condtn' => [
                'nullable',
                'string',
                'max:10',
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
        ];
    }
}
