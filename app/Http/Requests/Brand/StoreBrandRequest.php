<?php

namespace App\Http\Requests\Brand;


use App\Http\Requests\MainnRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function rules(Request $request)
    {

        $validation =$request->validate([

            ],
            [

            ]);

        return [
            'name' => 'required',
            'code' => 'required | max:'.config('defaults.default_string_length'),

            'image' => 'nullable | file
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),


            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'sort' => 'nullable | integer',


            'fields.*.field_id' => 'required | exists:fields,id,entity,brands',
            'fields.*.field_value_id' =>  'integer | exists:fields_values,id',
            'fields.*.value'=> 'nullable',


            'labels.*.label_id' => 'required | exists:labels,id',

        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'the :attribute field is required',
            'code.required' => 'the :attribute field is required',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions, minimum('.config('defaults.default_image_minimum_width').'x'.config('defaults.default_image_minimum_height').'),maximum('.config('defaults.default_image_maximum_width').'x'.config('defaults.default_image_maximum_height').')',

            'sort.integer' => 'the :attribute should be an integer',


            'fields.*.field_id.required' => 'The field_id is required',
            'fields.*.field_id.exists' => 'The field_id is not exists or not for brands entity',
            'fields.*.field_value_id.required' => 'The field_value_id  is required',
            'fields.*.field_value_id.exists' => 'The field_value_id  is not exists',
            'fields.*.value.required' => 'The value is required',

            'labels.*.label_id.required' => 'The label_id is required',
            'labels.*.label_id.exists' => 'The label_id is not exists',

        ];
    }
}
