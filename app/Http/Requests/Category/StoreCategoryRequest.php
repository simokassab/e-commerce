<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\MainRequest;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends MainRequest
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
        $id = $this->route('category') ? $this->route('category')->id : null;

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
            'slug' => 'required | max:' . config('defaults.default_string_length_2') . ' | unique:categories,slug,' . $id,
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'sort' => 'nullable | integer',

            'fields.*.field_id' => 'required | exists:fields,id,entity,category',
            'fields.*.type' => ['required', 'exists:fields,type,entity,category'],

            'label' => 'nullable|array',
            'labels.*' => 'required | integer | exists:labels,id',

            'order.*.id' => 'required | integer | exists:categories,id',
            'order.*.sort' => 'required | integer',

        ];
        if ($this->has('fields')) {
            $rules =  array_merge($rules,Category::generateValidationRules($this->fields,'fields'));
        }

        return $rules;
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
}
