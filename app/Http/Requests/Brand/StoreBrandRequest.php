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
            'meta_title' => 'required',
            'meta_description' => 'required',
            'meta_keyword' => 'required',
            'description' => 'required',
            'sort' => 'required | integer',
            'is_disabled' => 'required | boolean',

        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'the name field is required',
            'code.required' => 'the code field is required',
            'meta_title.required' => 'the meta_title field is required',
            'meta_description.required' => 'the meta_description field is required',
            'meta_keyword.required' => 'the meta_keyword field is required',
            'description.required' => 'the description field is required',
            'sort.required' => 'the sort field is required',
            'is_disabled.required' => 'the is_disabled field is required',
            'is_disabled.boolean' => 'The is_disabled field accepts only 0 or 1',

        ];
    }
}
