<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticTypeUpdateRequest extends FormRequest
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
            'name' => 'string|max:150',             
            'units' => 'string|max:150',
            'is_numeric' => 'boolean',
            'num_decimal_places' => 'integer|gte:0',
            'value' => 'numeric|gte:0'
        ];
    }
}
