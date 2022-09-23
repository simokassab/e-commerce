<?php

namespace App\Http\Requests\Brand;


use App\Http\Requests\MainRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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

        return [

            'name' => 'required',
            'code' => 'required | max:' . config('defaults.default_string_length'),

            'image' => 'nullable | file | max:' . config('defaults.default_string_length_2') . '
            | mimes:' . config('defaults.default_image_extentions') . '
            | max:' . config('defaults.default_image_size') . '
            | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),

            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'sort' => 'nullable | integer',
            'fields' => 'nullable|array',
            'fields.*.field_id' => 'required | exists:fields,id,entity,category',
            'fields.*.value' => [Rule::when($request->type == 'select', ['integer', 'exists:fields_values,id'], 'required'), 'required', 'max:' . config('defaults.default_string_length_2')],
            'fields.*.type' => 'required | exists:fields,type,entity,category',

            'labels.*' => 'required | integer | exists:labels,id',


        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'the :attribute field is required',
//            'code.required' => 'the :attribute field is required',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extension.',
            'image.dimensions' => 'Invalid dimensions, minimum(' . config('defaults.default_image_minimum_width') . 'x' . config('defaults.default_image_minimum_height') . '),maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',

            'sort.integer' => 'the :attribute should be an integer',


            'fields.*.field_id.required' => 'The field is required',
            'fields.*.field_id.exists' => 'The field must be exists',

            'fields.*.value.required' => 'The value is required',
            'fields.*.value.max' => 'Invalid string length',

            'fields.*.type.required' =>  'The type is required',
            'fields.*.type.exists' =>  'The type is not exists',

            'labels.*.label_id.required' => 'The label is required',
            'labels.*.label_id.integer' => 'The label must be integer',
            'labels.*.label_id.exists' => 'The label is not exists',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        throw new HttpResponseException(response()->json(
            [
                'message' => 'The input validation has failed, check your inputs',
                'code' => -1,
                'errors' => $validator->errors()->messages(),
            ], 200)

        );
    }
}
