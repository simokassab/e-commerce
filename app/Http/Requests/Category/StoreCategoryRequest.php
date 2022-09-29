<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\MainRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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

        $rules = [

            'name.en' => 'required',
            'name.ar' => 'required',
            // 'code' => 'required | max:'.config('defaults.default_string_length'),

//            'image' => 'nullable | file | string
//            | mimes:'.config('defaults.default_image_extentions').'
//            | max:'.config('defaults.default_image_size').'
//            | dimensions:max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),
//
            // 'icon' => 'nullable | file
            // | mimes:' . config('defaults.default_icon_extentions') . '
            // | max:' . config('defaults.default_icon_size') . '
            // | dimensions:max_width=' . config('defaults.default_icon_maximum_width') . ',max_height=' . config('defaults.default_icon_maximum_height'),

            'parent_id' => 'nullable | integer',
            'slug' => 'required | max:' . config('defaults.default_string_length_2') . ' | unique:categories,slug,' . $this->id ?? null,
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'sort' => 'nullable | integer',

            'fields.*.field_id' => 'required | exists:fields,id,entity,brand',
            'fields.*.type' => ['required', 'exists:fields,type,entity,brand'],

            'label' => 'nullable|array',
            'labels.*' => 'required | integer | exists:labels,id',

            'order.*.id' => 'required | integer | exists:categories,id',
            'order.*.sort' => 'required | integer',

        ];
        $fieldsRules = [];
        if ($this->has('fields')) {
            foreach ($this->fields as $key => $field) {
                if ($field['type'] == 'date') {
                    $fieldsRules[] = [
                        'fields.*.value' => 'required | date'
                    ];
                } elseif ($field['type'] == 'select') {

                    $fieldsRules[] = [
                        'fields.*.value' => 'required | integer', 'exists:fields_values,id'
                    ];
                } elseif ($field['type'] == 'checkbox') {
                    $fieldsRules[] = [
                        'fields.*.value' => 'required | boolean'
                    ];
                } elseif ($field['type'] == 'text' || $field['type'] == 'textarea') {
                    $fieldsRules[] = [
                        'fields.*.value' => 'required | string',
                    ];
                }
                $rules = array_merge($rules, $fieldsRules);
            }
        }

        return $rules;
    }

    public function getValidatorInstance()
    {
        $this->changeImageAndIconAndParentIdToNull();

        return  parent::getValidatorInstance();
    }

    protected function changeImageAndIconAndParentIdToNull()
    {
        if ($this->image == 'undefined')
            $this->merge(['image' => null]);
        if ($this->icon == 'undefined')
            $this->merge(['icon' => null]);
        if ($this->parent_id == 'null')
            $this->merge(['parent_id' => null]);
    }

    public function messages()
    {

        return [
            'name.en' => 'the field is required',
            'name.ar' => 'the field is required',

            'code.required' => 'the :attribute field is required',
            'code.max' => 'the maximum string length is :max',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions! maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',

            'icon.file' => 'The input is not an image',
            'icon.max' => 'The maximum :attribute size is :max.',
            'icon.mimes' => 'Invalid extention.',
            'icon.dimensions' => 'Invalid dimentions! maximum(' . config('defaults.default_icon_maximum_width') . 'x' . config('defaults.default_icon_maximum_height') . ')',

            'parent_id.integer' => 'the :attribute should be an integer',


            'slug.required' => 'the :attribute field is required',
            'slug.max' => 'the maximum string length is :max',
            'slug.unique' => 'The :attribute already exists!',

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

            'order.*.id.required' => 'The id is required',
            'order.*.id.integer' => 'The id should be an integer',
            'order.*.id.exists' => 'The id is not exists',
            'order.*.sort.required' => 'The sort is required',
            'order.*.sort.integer' => 'The sort should be an integer',

        ];
    }

    protected function failedValidation(Validator $validator)
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
