<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return ;
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
            'symbol' => 'nullable | max:125',
            'rate' => 'nullable | doubleval',
            'is_default' => 'nullable | boolean',

            'image' => 'nullable | image
            | mimes:'.config('app.default_image_extentions').'
            | max:'.config('app.default_image_size').'
            | dimensions:min_width='.config('app.default_image_minimum_width').',min_height='.config('app.default_image_minimum_height').'
                ,max_width='.config('app.default_image_maximum_width').',max_height='.config('app.default_image_maximum_height'),

            'sort' => 'nullable | integer'
         ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',

            'code.required' => 'The :attribute is required.',
            'code.max' => 'the maximum string length is :max',

            'symbol.max' => 'the maximum string length is :max',

            'rate.doubleval' => 'The :attribute must be decimal.',

            'is_default.boolean' => 'The :attribute field accepts only 0 or 1',

            'image.image' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum('.config('app.default_image_minimum_width').'x'.config('app.default_image_minimum_height').'),
                 maximum('.config('app.default_image_maximum_width').'x'.config('app.default_image_maximum_height').')',

            'sort.integer' => 'the :attribute should be an integer',
          ];
    }
}
