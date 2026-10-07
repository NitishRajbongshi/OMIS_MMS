<?php

namespace App\Http\Requests\CriticalityIndex;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCriticalityIndexRequest extends FormRequest
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
            'criticality_index' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'criticality_index.required' =>
            'Please enter the criticality index.',

            'criticality_index.numeric' =>
            'Criticality index must be a valid number.',
        ];
    }
}
