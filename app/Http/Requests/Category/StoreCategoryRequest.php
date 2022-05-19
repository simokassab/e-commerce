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
            'code' => 'required',
            'slug' => 'required',
            'title' => 'required',
            'description' => 'required',
            'keyword' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the name field is required',
            'code.required' => 'the code field is required',
            'slug.required' => 'the slug field is required',
            'title.required' => 'the title field is required',
            'description.required' => 'the description field is required',
            'keyword.required' => 'the keyword field is required',
        ];

    }
}
