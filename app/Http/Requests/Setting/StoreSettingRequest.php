<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
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
            'value' => 'required | max:125',
            'key' => 'required | max:125',
            'is_developer' => 'nullable | boolean',

        ];
    }

    public function messages()
    {
        return [

        'value.required' => 'the :attribute field is required',
        'value.max' => 'the maximum string length is :max',

        'key.required' => 'the :attribute field is required',
        'key.max' => 'the maximum string length is :max',

        'is_developer.boolean' => 'the :attribute must be either true or false',

        ];
    }
}
