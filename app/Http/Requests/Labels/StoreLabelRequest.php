<?php

namespace App\Http\Requests\Labels;

use App\Http\Requests\MainRequest;
use App\Models\Label\Label;

class StoreLabelRequest extends MainRequest
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
            'title.en' => 'required',
            'title.ar' => 'required',
            'entity' => 'required|in:' . Label::$entities,
            'color' => 'required | max:' . config('defaults.default_string_length'),

            // 'image' => 'nullable | image | max:' . config('defaults.default_string_length') . '
            // | mimes:' . config('defaults.default_image_extentions') . '
            // | max:' . config('defaults.default_image_size') . '
            // | dimensions:min_width=' . config('defaults.default_image_minimum_width') . ',min_height=' . config('defaults.default_image_minimum_height') . '
            //     ,max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),

            'key' => 'required | unique:labels,key,' . $this->id . ' | max:' . config('defaults.default_string_length'),
        ];
    }

    public function message()
    {
        return [
            'title.en' => 'the field is required',
            'title.ar' => 'the field is required',
            'entity.required' => 'the :attribute field is required',
            'entity.in' => 'The entity must be one on the following: ' . Label::$entities,

            'color.required' => 'the :attribute field is required',
            'color.max' => 'the maximum string length is :max',

            'image.image' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum(' . config('defaults.default_image_minimum_width') . 'x' . config('defaults.default_image_minimum_height') . '),
                 maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',

            'key.required' => 'the :attribute field is required',
            'key.max' => 'the maximum string length is :max',

        ];
    }
}
