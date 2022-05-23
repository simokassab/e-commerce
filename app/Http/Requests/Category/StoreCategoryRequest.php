<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'image' => 'nullable | max:125',
            'icon' => 'nullable | max:125',
            'parent_id' => 'nullable | integer',
            'slug' => 'required | max:125 | unique:App\Models\Category\Category,slug',
            'title' => 'required',
            'description' => 'required',
            'keyword' => 'required',
            'sort' => 'nullable | integer',
            'is_disabled' => 'required | boolean'

        ];
    }

    public function messages()
    {
        $stringLength='The maximum string length is 125!';
        return [
            'name.required' => 'the name field is required',
            'code.required' => 'the code field is required',
            'code.max' => $stringLength,
            'image.max' => $stringLength,
            'icon.max' => $stringLength,
            'slug.required' => 'the slug field is required',
            'slug.unique' => 'The slug already exists!',
            'title.required' => 'the title field is required',
            'description.required' => 'the description field is required',
            'keyword.required' => 'the keyword field is required',
            'is_disabled.required' => 'The is_disabled field is required',
            'is_disabled.boolean' => 'The is_disabled field accepts only 0 or 1',

        ];

    }
}
