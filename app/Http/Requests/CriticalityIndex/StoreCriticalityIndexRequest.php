<?php

namespace App\Http\Requests\CriticalityIndex;

use Illuminate\Foundation\Http\FormRequest;

class StoreCriticalityIndexRequest extends FormRequest
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
            'asset_type' => [
                'required',
                'string',
                'max:5',
            ],

            'asset_id' => [
                'required',
                'string',
                'max:30',
            ],

            'criticality_index' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_type.required' =>
                'Please select an asset type.',

            'asset_id.required' =>
                'Please select an asset.',

            'criticality_index.required' =>
                'Please enter the criticality index.',

            'criticality_index.numeric' =>
                'Criticality index must be a valid number.',
        ];
    }
}
