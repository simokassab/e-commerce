<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
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
            'name' => 'required | max:'.config('defaults.string_length'),
            'code' => 'required | max:'.config('defaults.string_length'),
            'is_default' => 'nullable | boolean',
            'is_disabled' => 'nullable | boolean',

            'image' => 'nullable | image
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:min_width='.config('defaults.default_image_minimum_width').',min_height='.config('defaults.default_image_minimum_height').'
                ,max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),

            'sort' => 'nullable | integer'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The field name is required.',
            'name.max' => 'the maximum string length is :max',

            'code.required' => 'The code is required.',
            'code.max' => 'the maximum string length is :max',

            'is_default.boolean' => 'The :attribute field accepts only 0 or 1',
            'is_disabled.boolean' => 'The :attribute field accepts only 0 or 1',

            'image.image' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum('.config('defaults.default_image_minimum_width').'x'.config('defaults.default_image_minimum_height').'),
                 maximum('.config('defaults.default_image_maximum_width').'x'.config('defaults.default_image_maximum_height').')',


           'sort.integer' => 'the :attribute should be an integer',
                ];
    }
}
