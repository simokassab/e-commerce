<?php

namespace App\Http\Requests\Labels;

use Illuminate\Foundation\Http\FormRequest;

class StoreLabelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return auth()->hasPermissions('permissions name');
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
            'title' => 'required',
            'entity' => 'required|in:'.config('app.validation_default_entities'),
            'color' => 'required | max:125',

            'image' => 'nullable | image
            | mimes:'.config('app.default_image_extentions').'
            | max:'.config('app.default_image_size').'
            | dimensions:min_width='.config('app.default_image_minimum_width').',min_height='.config('app.default_image_minimum_height').'
                ,max_width='.config('app.default_image_maximum_width').',max_height='.config('app.default_image_maximum_height'),

            'key' => 'required | max:125',
        ];
    }

    public function message(){
        return [
            'title.required' =>  'the :attribute field is required',

            'entity.required' => 'the :attribute field is required',
            'entity.in' => 'The entity must be one on the following: '.config('app.validation_default_entities'),

            'color.required' => 'the :attribute field is required',
            'color.max' => 'the maximum string length is :max',

            'image.image' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum('.config('app.default_image_minimum_width').'x'.config('app.default_image_minimum_height').'),
                 maximum('.config('app.default_image_maximum_width').'x'.config('app.default_image_maximum_height').')',

            'key.required' => 'the :attribute field is required',
            'key.max' => 'the maximum string length is :max',

        ];
    }

}
