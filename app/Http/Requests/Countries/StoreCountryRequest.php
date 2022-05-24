<?php

namespace App\Http\Requests\Countries;

use Illuminate\Foundation\Http\FormRequest;


class StoreCountryRequest extends FormRequest
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
            'iso_code_1' => 'required | max:125',
            'iso_code_2' => 'required | max:125',
            'phone_code' => ['required' , 'max:6' , 'regex:/^\+\d{1,3}$/'],
            'flag' => 'required | image
                | mimes:'.config('app.default_icon_extentions').'
                | max:'.config('app.default_icon_size').'
                | dimensions:min_width='.config('app.default_icon_minimum_width').',min_height='.config('app.default_icon_minimum_height').'
                    ,max_width='.config('app.default_icon_maximum_width').',max_height='.config('app.default_icon_maximum_height')

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',

            'iso_code_1.required' => 'The :attribute is required.',
            'iso_code_1.max' => 'Invalid length for :attribute!',

            'is_code_2.required'  => 'The :attribute is required.',
            'is_code_2.max'  => 'Invalid length for :attribute!',

            'phone_code.required' => 'The :attribute is required.',
            'phone_code.max' => 'Invalid length for :attribute!',
            'phone_code.regex' => 'Invalid format for :attribute!',

            'flag.image' => 'The input is not an image',
            'flag.max' => 'The maximum :attribute size is :max.',
            'flag.mimes' => 'Invalid extention.',
            'flag.dimensions' => 'Invalid dimentions! minimum('.config('app.default_icon_minimum_width').'x'.config('app.default_icon_minimum_height').'),
                 maximum('.config('app.default_icon_maximum_width').'x'.config('app.default_icon_maximum_height').')',


        ];
    }
}
