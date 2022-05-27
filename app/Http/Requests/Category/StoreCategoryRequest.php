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
            'code' => 'required | max:'.config('defaults.default_string_length'),

            'image' => 'nullable | file
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:min_width='.config('defaults.default_image_minimum_width').',min_height='.config('defaults.default_image_minimum_height').',max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),


            'icon' => 'nullable | file
            | mimes:'.config('defaults.default_icon_extentions').'
            | max:'.config('defaults.default_icon_size').'
            | dimensions:max_width='.config('defaults.default_icon_maximum_width').',max_height='.config('defaults.default_icon_maximum_height'),

            'parent_id' => 'nullable | integer',
            'slug' => 'required | max:'.config('defaults.default_string_length').'| unique:categories,slug,'.$this->id,

            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',

            'description' => 'nullable',
            'sort' => 'nullable | integer',
            'is_disabled' => 'required | boolean'

        ];
    }

    public function messages()
    {

        return [
            'name.required' => 'the :attribute field is required',

            'code.required' => 'the :attribute field is required',
            'code.max' => 'the maximum string length is :max',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum('.config('defaults.default_image_minimum_width').'x'.config('defaults.default_image_minimum_height').'),maximum('.config('defaults.default_image_maximum_width').'x'.config('defaults.default_image_maximum_height').')',

            // 'icon.file' => 'The input is not an image',
            'icon.max' => 'The maximum :attribute size is :max.',
            'icon.mimes' => 'Invalid extention.',
            'icon.dimensions' => 'Invalid dimentions, minimum('.config('defaults.default_icon_minimum_width').'x'.config('defaults.default_icon_minimum_height').'),maximum('.config('defaults.default_icon_maximum_width').'x'.config('defaults.default_icon_maximum_height').')',

            'parent_id.integer' => 'the :attribute should be an integer',


            'slug.required' => 'the :attribute field is required',
            'slug.max' => 'the maximum string length is :max',
            'slug.unique' => 'The :attribute already exists!',

            'sort.integer' => 'the :attribute should be an integer',

            'is_disabled.required' => 'The :attribute field is required',
            'is_disabled.boolean' => 'The :attribute field accepts only 0 or 1',

        ];

    }
}
