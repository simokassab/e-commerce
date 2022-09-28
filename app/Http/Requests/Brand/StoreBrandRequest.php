<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\MainRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends MainRequest
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

        $rules =  [

            'name.en' => 'required',
            'name.ar' => 'required',

            // 'image' => 'nullable | file | max:' . config('defaults.default_string_length_2') . '
            // | mimes:' . config('defaults.default_image_extentions') . '
            // | max:' . config('defaults.default_image_size') . '
            // | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),

            'meta_title.en' => 'nullable',
            'meta_title.ar' => 'nullable',
            'meta_description.en' => 'nullable',
            'meta_description.ar' => 'nullable',
            'meta_keyword.en' => 'nullable',
            'meta_keyword.ar' => 'nullable',
            'description.en' => 'nullable',
            'description.ar' => 'nullable',
            'sort' => 'nullable | integer',


            'fields.*.field_id' => 'required | exists:fields,id,entity,brand',
            'fields.*.type' => ['required', 'exists:fields,type,entity,brand'],

            'labels.*' => 'required | integer | exists:labels,id',


        ];
        $fieldsRules = [];
        if ( $this->has('fields') ) {
            foreach ($this->fields as $field) {
                if ($field['type'] == 'date') {
                    $fieldsRules = [
                        'fields.*.value' => 'date'
                    ];
                } elseif ($field['type'] == 'select') {

                    $fieldsRules = [
                        'fields.*.value' => 'integer', 'exists:fields_values,id'
                    ];
                } elseif ($field['type'] == 'checkbox') {

                    $fieldsRules = [
                        'fields.*.value' => 'boolean'
                    ];
                }elseif($field['type'] == 'text' || $field['type'] == 'textarea'){
                    $fieldsRules = [
                        'fields.*.value.en' => 'required|string',
                        'fields.*.value.ar' => 'required|string',

                    ];
                }
                $rules = array_merge($rules, $fieldsRules);
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [

            'name.en.required' => 'the :attribute field is required',
            'name.ar.required' => 'the :attribute field is required',
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

        throw new HttpResponseException(
            response()->json(
                [
                    'message' => 'The input validation has failed, check your inputs',
                    'code' => -1,
                    'errors' => $validator->errors()->messages(),
                ],
                200
            )

        );
    }
}
