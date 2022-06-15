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
            'title' => 'required | max:'.config('defaults.default_string_length').' | unique:settings,title,'.$this->id,
            'value' => 'required | max:'.config('defaults.default_string_length'),
            'is_developer' => 'required | boolean',

        ];
    }

    public function messages()
    {
        return [


        'title.required' => 'the :attribute field is required',
        'title.unique' => 'the :attribute field already exist',
        'title.max' => 'the maximum string length is :max',

        'value.required' => 'the :attribute field is required',
        'value.max' => 'the maximum string length is :max',

        'is_developer.required' => 'the :attribute field is required',
        'is_developer.boolean' => 'the :attribute must be either false or true (0 or 1)',

        ];
    }
}
