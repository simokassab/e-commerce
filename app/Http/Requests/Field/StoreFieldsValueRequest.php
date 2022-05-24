<?php

namespace App\Http\Requests\Field;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldsValueRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'field_id' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [

        'field_id.required' => 'the :attribute is required',
        'value.required' => 'the :attribute is required'
        ];
    }
}
