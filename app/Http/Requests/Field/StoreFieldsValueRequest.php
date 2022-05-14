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
            'fields_id' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [

        'fields_id.required' => 'the field id is required',
        'value' => 'the field value is required'
        ];
    }
}
