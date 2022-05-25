<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required | max:125',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'required',
            'sort' => 'required | integer',
            'is_disabled' => 'required | boolean',

        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'the :attribute field is required',
            'code.required' => 'the :attribute field is required',
            'description.required' => 'the :attribute field is required',
            'sort.required' => 'the :attribute field is required',
            'is_disabled.required' => 'the :attribute field is required',
            'is_disabled.boolean' => 'The :attribute field accepts only 0 or 1',

        ];
    }
}
