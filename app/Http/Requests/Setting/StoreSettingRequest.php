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
            'value' => 'required',
            'key' => 'required',
            'is_developer' => 'nullable|boolean',

        ];
    }

    public function messages()
    {
        return [
        'is_developer.boolean' => 'the :attribute must be either true or false',
        'value.required' => 'the :attribute field is required',
        'key.required' => 'the :attribute field is required',

        ];
    }
}
